<?php

declare(strict_types=1);

use App\Libraries\SinadiVerifikatorHelper;
use PHPUnit\Framework\TestCase;

final class SinadiVerifikatorHelperTest extends TestCase
{
    public function testResolvePeriodeFilterDefaultsToThirtyDays(): void
    {
        $this->assertSame('30d', SinadiVerifikatorHelper::resolvePeriodeFilter(null));
        $this->assertSame('30d', SinadiVerifikatorHelper::resolvePeriodeFilter(''));
        $this->assertSame('30d', SinadiVerifikatorHelper::resolvePeriodeFilter('30d'));
        $this->assertSame('30d', SinadiVerifikatorHelper::resolvePeriodeFilter('invalid'));
        $this->assertSame('all', SinadiVerifikatorHelper::resolvePeriodeFilter('all'));
        $this->assertSame('all', SinadiVerifikatorHelper::resolvePeriodeFilter(' ALL '));
    }

    public function testResolvePeriodeCutoffDateReturnsMinusThirtyDaysOrNull(): void
    {
        $this->assertSame('2026-10-26', SinadiVerifikatorHelper::resolvePeriodeCutoffDate('30d', '2026-11-25'));
        $this->assertSame('2026-10-26', SinadiVerifikatorHelper::resolvePeriodeCutoffDate(null, '2026-11-25'));
        $this->assertNull(SinadiVerifikatorHelper::resolvePeriodeCutoffDate('all', '2026-11-25'));
    }
}
