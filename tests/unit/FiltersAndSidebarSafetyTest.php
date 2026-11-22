<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class FiltersAndSidebarSafetyTest extends TestCase
{
    public function testProtectedModulesAreRegisteredInAuthFilter(): void
    {
        $props = (new \ReflectionClass(\Config\Filters::class))->getDefaultProperties();
        $before = $props['filters']['auth']['before'] ?? [];

        foreach (['ekip', 'ekip/*', 'anggaran', 'anggaran/*', 'portal_link', 'portal_link/*', 'sisovi', 'sisovi/*'] as $pattern) {
            $this->assertContains($pattern, $before, "Missing AuthFilter protection for {$pattern}");
        }
    }

    public function testSidebarHandlesNullAccessMenuSafely(): void
    {
        $source = file_get_contents(APPPATH . 'Views/layouts/sidebar.php');
        $this->assertIsString($source);
        $this->assertStringContainsString('$accessMenu = (array) (session()->get(\'accessMenu\') ?? []);', $source);
        $this->assertStringNotContainsString("session()->get('accessMenu')", substr($source, strpos($source, '?>') ?: 0));
        $this->assertStringNotContainsString("session()->set('_sidebar_rawat_cache'", $source);
    }

    public function testNotificationAndCutiPollingIntervalsAreThrottled(): void
    {
        $sweetAlertJs = file_get_contents(FCPATH . 'js/sweetAlert3.js');
        $cutiJs = file_get_contents(FCPATH . 'js/validation_data_cuti.js');

        $this->assertIsString($sweetAlertJs);
        $this->assertStringContainsString('90000', $sweetAlertJs);
        $this->assertStringContainsString('__notifLastFetchedAt', $sweetAlertJs);

        $this->assertIsString($cutiJs);
        $this->assertStringContainsString('60000', $cutiJs);
    }
}
