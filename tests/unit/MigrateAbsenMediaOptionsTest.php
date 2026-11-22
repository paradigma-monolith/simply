<?php

namespace Tests\Unit;

use App\Commands\MigrateAbsenMedia;
use App\Libraries\AbsenMediaMigrator;
use App\Libraries\AbsenMediaStorage;
use PHPUnit\Framework\TestCase;

class MigrateAbsenMediaOptionsTest extends TestCase
{
    public function testParseCliOptionsSupportsEqualsSyntaxAndSpaceSyntax(): void
    {
        // CI4 CLI::parseCommandLine() stores `--limit=500` as ['limit=500' => null]
        $equalsStyle = [
            'dry-run'          => null,
            'limit=500'        => null,
            'batch=100'        => null,
            'start-id=4200'    => null,
            'sleep-ms=30'      => null,
            'max-history=150'  => null,
            'legacy-dir=/tmp/legacy_fotos' => null,
        ];

        $parsed = MigrateAbsenMedia::parseCliOptions($equalsStyle, []);

        $this->assertTrue($parsed['dry_run']);
        $this->assertSame(500, $parsed['limit']);
        $this->assertSame(100, $parsed['batch']);
        $this->assertSame(4200, $parsed['start_id']);
        $this->assertSame(30, $parsed['sleep_ms']);
        $this->assertSame(150, $parsed['max_history']);
        $this->assertSame('/tmp/legacy_fotos', $parsed['legacy_dir']);

        // Space-separated CI4 style (`--limit 250 --batch 50 --dry-run=0`)
        $spaceStyle = [
            'dry-run=0'   => null,
            'limit'       => '250',
            'batch'       => '50',
            'start-id'    => '10',
            'sleep-ms'    => '75',
            'max-history' => '300',
            'legacy-dir'  => '/var/legacy',
        ];

        $parsed2 = MigrateAbsenMedia::parseCliOptions($spaceStyle, []);

        $this->assertFalse($parsed2['dry_run']);
        $this->assertSame(250, $parsed2['limit']);
        $this->assertSame(50, $parsed2['batch']);
        $this->assertSame(10, $parsed2['start_id']);
        $this->assertSame(75, $parsed2['sleep_ms']);
        $this->assertSame(300, $parsed2['max_history']);
        $this->assertSame('/var/legacy', $parsed2['legacy_dir']);
    }

    public function testWaitForPurgeLagReturnsNullAndPausesConservativelyWhenTelemetryUnavailable(): void
    {
        $tmpDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'simply_purge_null_' . uniqid('', true);
        mkdir($tmpDir, 0775, true);

        try {
            $sleeps = [];
            $storage = new AbsenMediaStorage($tmpDir);
            $migrator = new AbsenMediaMigrator(
                $storage,
                static fn (): ?int => null,
                static function (int $ms) use (&$sleeps): void {
                    $sleeps[] = $ms;
                }
            );

            $result = $migrator->waitForPurgeLag(200, 10);

            $this->assertNull($result);
            $this->assertCount(1, $sleeps);
            $this->assertGreaterThan(0, $sleeps[0]);
        } finally {
            @rmdir($tmpDir);
        }
    }
}
