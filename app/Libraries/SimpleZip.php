<?php

namespace App\Libraries;

class SimpleZip
{
    private $compressedData = [];
    private $centralDirectory = []; // central directory
    private $endOfCentralDirectory = "\x50\x4b\x05\x06\x00\x00\x00\x00"; // end of central dir signature
    private $oldOffset = 0;

    /**
     * Add a file to the zip
     *
     * @param string $path Path to the file on disk
     * @param string $name Name of the file inside the zip
     */
    public function addFile($path, $name)
    {
        if (!file_exists($path)) {
            return;
        }

        $data = file_get_contents($path);

        $name = str_replace('\\', '/', $name);
        $hexdtime = pack('V', $this->unix2DosTime(filemtime($path)));

        $unc_len = strlen($data);
        $crc = crc32($data);

        // Compress data if zlib is available
        if (function_exists('gzcompress')) {
            $zdata = gzcompress($data);
            $zdata = substr(substr($zdata, 0, strlen($zdata) - 4), 2); // fix crc bug
            $c_len = strlen($zdata);
            $c_method = "\x08\x00"; // DEFLATE
        } else {
            $zdata = $data;
            $c_len = $unc_len;
            $c_method = "\x00\x00"; // STORE
        }

        // Local file header
        $fr = "\x50\x4b\x03\x04";
        $fr .= "\x14\x00"; // ver needed to extract
        $fr .= "\x00\x00"; // gen purpose bit flag
        $fr .= $c_method; // compression method
        $fr .= $hexdtime; // last mod time and date
        $fr .= pack('V', $crc); // crc32
        $fr .= pack('V', $c_len); // compressed filesize
        $fr .= pack('V', $unc_len); // uncompressed filesize
        $fr .= pack('v', strlen($name)); // length of filename
        $fr .= pack('v', 0); // extra field length
        $fr .= $name;
        $fr .= $zdata; // "file data"

        $this->compressedData[] = $fr;

        // Central Directory Record
        $cdrec = "\x50\x4b\x01\x02";
        $cdrec .= "\x00\x00"; // version made by
        $cdrec .= "\x14\x00"; // version needed to extract
        $cdrec .= "\x00\x00"; // gen purpose bit flag
        $cdrec .= $c_method; // compression method
        $cdrec .= $hexdtime; // last mod time & date
        $cdrec .= pack('V', $crc); // crc32
        $cdrec .= pack('V', $c_len); // compressed filesize
        $cdrec .= pack('V', $unc_len); // uncompressed filesize
        $cdrec .= pack('v', strlen($name)); // length of filename
        $cdrec .= pack('v', 0); // extra field length
        $cdrec .= pack('v', 0); // file comment length
        $cdrec .= pack('v', 0); // disk number start
        $cdrec .= pack('v', 0); // internal file attributes
        $cdrec .= pack('V', 32); // external file attributes - 'archive' bit set
        $cdrec .= pack('V', $this->oldOffset); // relative offset of local header
        $cdrec .= $name;

        $this->oldOffset += strlen($fr);
        $this->centralDirectory[] = $cdrec;
    }

    /**
     * Download the zip file
     *
     * @param string $fileName
     */
    public function download($fileName)
    {
        $data = implode('', $this->compressedData);
        $centralDir = implode('', $this->centralDirectory);

        $stream = $data .
            $centralDir .
            $this->endOfCentralDirectory .
            pack('v', count($this->centralDirectory)) . // total # of entries "on this disk"
            pack('v', count($this->centralDirectory)) . // total # of entries overall
            pack('V', strlen($centralDir)) . // size of central dir
            pack('V', strlen($data)) . // offset to start of central dir
            "\x00\x00"; // .zip file comment length

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . strlen($stream));
        echo $stream;
        exit;
    }

    private function unix2DosTime($unixtime = 0)
    {
        $timearray = ($unixtime == 0) ? getdate() : getdate($unixtime);

        if ($timearray['year'] < 1980) {
            $timearray['year'] = 1980;
            $timearray['mon'] = 1;
            $timearray['mday'] = 1;
            $timearray['hours'] = 0;
            $timearray['minutes'] = 0;
            $timearray['seconds'] = 0;
        }

        return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) |
            ($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
    }
}
