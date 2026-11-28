<?php

namespace App\Libraries;

use RuntimeException;

/**
 * Pure-PHP PDF merger tailored for Dompdf-generated PDFs so documents with
 * different page orientations (e.g. F4 Portrait + F4 Landscape) can be combined
 * while preserving each document's /MediaBox and /Resources.
 */
class SimplePdfMerger
{
    /**
     * Merges an ordered list of raw PDF binaries into a single valid PDF binary.
     *
     * @param array<int, string> $pdfBinaries
     */
    public static function merge(array $pdfBinaries): string
    {
        $inputs = [];
        foreach ($pdfBinaries as $bin) {
            if (is_string($bin) && $bin !== '') {
                $inputs[] = $bin;
            }
        }

        if ($inputs === []) {
            throw new RuntimeException('Minimal satu PDF valid diperlukan untuk penggabungan.');
        }

        if (count($inputs) === 1) {
            return $inputs[0];
        }

        $parsedDocs = [];
        foreach ($inputs as $idx => $bin) {
            $parsedDocs[] = self::parsePdf($bin, $idx);
        }

        $mergedObjects = [];
        $childPagesIds = [];
        $totalPageCount = 0;
        $nextId = 1;
        $mergedRootId = null;
        $mergedInfoId = null;

        foreach ($parsedDocs as $docIdx => $doc) {
            $idMap = [];
            foreach (array_keys($doc['objects']) as $oldId) {
                $idMap[$oldId] = $nextId++;
            }

            if (!isset($idMap[$doc['rootId']], $idMap[$doc['pagesId']])) {
                throw new RuntimeException("Struktur Catalog/Pages tidak valid pada dokumen PDF #{$docIdx}.");
            }

            $mappedRootId = $idMap[$doc['rootId']];
            $mappedPagesId = $idMap[$doc['pagesId']];
            $childPagesIds[] = $mappedPagesId;
            $totalPageCount += $doc['pageCount'];

            if ($docIdx === 0) {
                $mergedRootId = $mappedRootId;
                $mergedInfoId = ($doc['infoId'] !== null && isset($idMap[$doc['infoId']]))
                    ? $idMap[$doc['infoId']]
                    : null;
            }

            foreach ($doc['objects'] as $oldId => $rawBody) {
                $newId = $idMap[$oldId];

                if ($docIdx > 0 && $oldId === $doc['rootId']) {
                    $mergedObjects[$newId] = '<< >>';
                    continue;
                }

                [$headerPart, $streamPart] = self::splitHeaderAndStream($rawBody);
                $rewrittenHeader = self::rewriteIndirectRefs($headerPart, $idMap);

                if ($streamPart !== '') {
                    $mergedObjects[$newId] = $rewrittenHeader . $streamPart;
                } else {
                    $mergedObjects[$newId] = $rewrittenHeader;
                }
            }
        }

        $rootPagesId = $nextId++;
        $kidsList = implode(' ', array_map(static fn (int $pid): string => $pid . ' 0 R', $childPagesIds));
        $mergedObjects[$rootPagesId] = "<< /Type /Pages /Kids [{$kidsList}] /Count {$totalPageCount} >>";

        // Point the single /Catalog to our new combined root /Pages node
        $mergedObjects[$mergedRootId] = self::replacePagesRefInCatalog(
            $mergedObjects[$mergedRootId],
            $rootPagesId
        );

        // Attach /Parent on every child /Pages node
        foreach ($childPagesIds as $childPagesId) {
            $mergedObjects[$childPagesId] = self::setParentInPagesDict(
                $mergedObjects[$childPagesId],
                $rootPagesId
            );
        }

        return self::serializePdf($mergedObjects, $mergedRootId, $mergedInfoId, $nextId);
    }

    /**
     * Parses a PDF binary using its xref table and trailer.
     *
     * @return array{rootId: int, infoId: ?int, pagesId: int, pageCount: int, objects: array<int, string>}
     */
    private static function parsePdf(string $binary, int $docIdx): array
    {
        if (strpos($binary, '%PDF-') !== 0) {
            throw new RuntimeException("Input dokumen #{$docIdx} bukan file PDF yang valid.");
        }

        $startxrefPos = strrpos($binary, 'startxref');
        if ($startxrefPos === false) {
            throw new RuntimeException("Marker startxref tidak ditemukan pada dokumen #{$docIdx}.");
        }

        if (!preg_match('/startxref\s+(\d+)/', $binary, $sxMatch, 0, $startxrefPos)) {
            throw new RuntimeException("Offset startxref tidak valid pada dokumen #{$docIdx}.");
        }

        $xrefOffset = (int) $sxMatch[1];
        $trailerPos = strpos($binary, 'trailer', $xrefOffset);
        if ($trailerPos === false) {
            throw new RuntimeException("Trailer PDF tidak ditemukan (atau PDF terkompresi xref stream) pada dokumen #{$docIdx}.");
        }

        $xrefBlock = substr($binary, $xrefOffset, $trailerPos - $xrefOffset);
        $offsets = self::parseXrefOffsets($xrefBlock, $docIdx);

        if ($offsets === []) {
            throw new RuntimeException("Tabel xref kosong pada dokumen #{$docIdx}.");
        }

        $trailerSegment = substr($binary, $trailerPos, $startxrefPos - $trailerPos);
        if (!preg_match('/\/Root\s+(\d+)\s+0\s+R/', $trailerSegment, $rootMatch)) {
            throw new RuntimeException("Referensi /Root tidak ditemukan di trailer dokumen #{$docIdx}.");
        }
        $rootId = (int) $rootMatch[1];

        $infoId = null;
        if (preg_match('/\/Info\s+(\d+)\s+0\s+R/', $trailerSegment, $infoMatch)) {
            $infoId = (int) $infoMatch[1];
        }

        $sortedOffsets = $offsets;
        asort($sortedOffsets);
        $orderedIds = array_keys($sortedOffsets);
        $orderedOffs = array_values($sortedOffsets);
        $numObjs = count($orderedIds);

        $objects = [];
        for ($i = 0; $i < $numObjs; $i++) {
            $objId = $orderedIds[$i];
            $startOff = $orderedOffs[$i];
            $endOff = ($i + 1 < $numObjs) ? $orderedOffs[$i + 1] : $xrefOffset;
            if ($endOff <= $startOff) {
                throw new RuntimeException("Rentang byte objek #{$objId} tidak valid pada dokumen #{$docIdx}.");
            }

            $rawObj = substr($binary, $startOff, $endOff - $startOff);
            $objects[$objId] = self::extractObjectBody($rawObj, $objId, $docIdx);
        }

        ksort($objects);

        if (!isset($objects[$rootId])) {
            throw new RuntimeException("Objek /Catalog #{$rootId} tidak ditemukan pada dokumen #{$docIdx}.");
        }

        if (!preg_match('/\/Pages\s+(\d+)\s+0\s+R/', $objects[$rootId], $pagesMatch)) {
            throw new RuntimeException("Referensi /Pages tidak ditemukan pada /Catalog dokumen #{$docIdx}.");
        }
        $pagesId = (int) $pagesMatch[1];

        if (!isset($objects[$pagesId])) {
            throw new RuntimeException("Objek /Pages #{$pagesId} tidak ditemukan pada dokumen #{$docIdx}.");
        }

        if (!preg_match('/\/Count\s+(\d+)/', $objects[$pagesId], $countMatch)) {
            throw new RuntimeException("Atribut /Count tidak ditemukan pada /Pages dokumen #{$docIdx}.");
        }
        $pageCount = (int) $countMatch[1];

        return [
            'rootId'    => $rootId,
            'infoId'    => $infoId,
            'pagesId'   => $pagesId,
            'pageCount' => $pageCount,
            'objects'   => $objects,
        ];
    }

    /**
     * Parses classic ASCII xref subsections into `[objectId => byteOffset]`.
     *
     * @return array<int, int>
     */
    private static function parseXrefOffsets(string $xrefBlock, int $docIdx): array
    {
        $trimmed = trim($xrefBlock);
        if (stripos($trimmed, 'xref') !== 0) {
            throw new RuntimeException("Format tabel xref tidak dikenali pada dokumen #{$docIdx}.");
        }

        $tokens = preg_split('/\s+/', trim(substr($trimmed, 4)));
        if (!is_array($tokens)) {
            return [];
        }

        $offsets = [];
        $len = count($tokens);
        $idx = 0;

        while ($idx + 1 < $len) {
            $startId = (int) $tokens[$idx++];
            $count = (int) $tokens[$idx++];

            for ($k = 0; $k < $count; $k++) {
                if ($idx + 2 >= $len) {
                    break;
                }
                $entryOff = (int) $tokens[$idx++];
                $idx++; // generation
                $flag = $tokens[$idx++];

                $objId = $startId + $k;
                if ($flag === 'n' && $objId > 0) {
                    $offsets[$objId] = $entryOff;
                }
            }
        }

        return $offsets;
    }

    /**
     * Strips `<id> 0 obj` and `endobj` from a raw sliced object string while preserving binary stream bytes.
     */
    private static function extractObjectBody(string $rawObj, int $objId, int $docIdx): string
    {
        if (!preg_match('/^\s*' . $objId . '\s+\d+\s+obj\b[ \t]*(?:\r\n|\r|\n)?/', $rawObj, $startMatch)) {
            throw new RuntimeException("Header objek #{$objId} tidak valid pada dokumen #{$docIdx}.");
        }

        $afterHeader = substr($rawObj, strlen($startMatch[0]));
        $endObjPos = strrpos($afterHeader, 'endobj');
        if ($endObjPos === false) {
            throw new RuntimeException("Marker endobj hilang untuk objek #{$objId} pada dokumen #{$docIdx}.");
        }

        return rtrim(substr($afterHeader, 0, $endObjPos), "\r\n ");
    }

    /**
     * Splits an object body into `[nonStreamHeader, streamAndEndstream]` without touching binary stream bytes.
     *
     * @return array{0: string, 1: string}
     */
    private static function splitHeaderAndStream(string $body): array
    {
        $dictEnd = self::findTopLevelDictEnd($body);
        if ($dictEnd === null) {
            return [$body, ''];
        }

        $afterDict = substr($body, $dictEnd + 2);
        if (preg_match('/^\s*stream(?:\r\n|\r|\n)/', $afterDict)) {
            $streamPos = strpos($body, 'stream', $dictEnd + 2);
            if ($streamPos !== false) {
                return [substr($body, 0, $streamPos), substr($body, $streamPos)];
            }
        }

        return [$body, ''];
    }

    /**
     * Finds the byte index of the closing `>>` for a leading top-level `<< ... >>` dictionary,
     * ignoring string literals `(...)` and nested dictionaries.
     */
    private static function findTopLevelDictEnd(string $body): ?int
    {
        $len = strlen($body);
        $i = 0;
        while ($i < $len && ($body[$i] === ' ' || $body[$i] === "\t" || $body[$i] === "\r" || $body[$i] === "\n")) {
            $i++;
        }

        if ($i + 1 >= $len || $body[$i] !== '<' || $body[$i + 1] !== '<') {
            return null;
        }

        $depth = 0;
        $inLiteral = false;
        $parenDepth = 0;

        while ($i < $len) {
            $ch = $body[$i];

            if ($inLiteral) {
                if ($ch === '\\') {
                    $i += 2;
                    continue;
                }
                if ($ch === '(') {
                    $parenDepth++;
                } elseif ($ch === ')') {
                    $parenDepth--;
                    if ($parenDepth <= 0) {
                        $inLiteral = false;
                    }
                }
                $i++;
                continue;
            }

            if ($ch === '(') {
                $inLiteral = true;
                $parenDepth = 1;
                $i++;
                continue;
            }

            if ($ch === '<' && ($i + 1 < $len) && $body[$i + 1] === '<') {
                $depth++;
                $i += 2;
                continue;
            }

            if ($ch === '>' && ($i + 1 < $len) && $body[$i + 1] === '>') {
                $depth--;
                if ($depth === 0) {
                    return $i;
                }
                $i += 2;
                continue;
            }

            $i++;
        }

        return null;
    }

    /**
     * Rewrites `<id> 0 R` indirect references in a non-stream PDF object header using `$idMap`.
     *
     * @param array<int, int> $idMap
     */
    private static function rewriteIndirectRefs(string $header, array $idMap): string
    {
        return (string) preg_replace_callback(
            '/\b(\d+)\s+0\s+R\b/',
            static function (array $m) use ($idMap): string {
                $oldId = (int) $m[1];
                $newId = $idMap[$oldId] ?? $oldId;

                return $newId . ' 0 R';
            },
            $header
        );
    }

    private static function replacePagesRefInCatalog(string $catalogBody, int $rootPagesId): string
    {
        if (preg_match('/\/Pages\s+\d+\s+0\s+R/', $catalogBody)) {
            return (string) preg_replace('/\/Pages\s+\d+\s+0\s+R/', '/Pages ' . $rootPagesId . ' 0 R', $catalogBody, 1);
        }

        return (string) preg_replace('/>>\s*$/', ' /Pages ' . $rootPagesId . ' 0 R >>', $catalogBody, 1);
    }

    private static function setParentInPagesDict(string $pagesBody, int $rootPagesId): string
    {
        $cleaned = (string) preg_replace('/\s*\/Parent\s+\d+\s+0\s+R/', '', $pagesBody);

        if (preg_match('/\/Type\s+\/Pages\b/', $cleaned)) {
            return (string) preg_replace(
                '/\/Type\s+\/Pages\b/',
                '/Type /Pages /Parent ' . $rootPagesId . ' 0 R',
                $cleaned,
                1
            );
        }

        return (string) preg_replace('/>>\s*$/', ' /Parent ' . $rootPagesId . ' 0 R >>', $cleaned, 1);
    }

    /**
     * Serializes objects, contiguous xref table, and trailer into the final PDF binary.
     *
     * @param array<int, string> $objects
     */
    private static function serializePdf(array $objects, int $rootId, ?int $infoId, int $totalSize): string
    {
        ksort($objects);

        $out = "%PDF-1.7\n%\xE2\xE3\xCF\xD3\n";
        $offsets = [];

        for ($id = 1; $id < $totalSize; $id++) {
            $body = $objects[$id] ?? '<< >>';
            $offsets[$id] = strlen($out);
            $out .= $id . " 0 obj\n" . $body . "\nendobj\n";
        }

        $xrefOffset = strlen($out);
        $out .= "xref\n";
        $out .= "0 {$totalSize}\n";
        $out .= "0000000000 65535 f \n";
        for ($id = 1; $id < $totalSize; $id++) {
            $out .= sprintf("%010d 00000 n \n", $offsets[$id]);
        }

        $infoPart = $infoId !== null ? " /Info {$infoId} 0 R" : '';
        $out .= "trailer\n<< /Size {$totalSize} /Root {$rootId} 0 R{$infoPart} >>\n";
        $out .= "startxref\n{$xrefOffset}\n%%EOF\n";

        return $out;
    }
}
