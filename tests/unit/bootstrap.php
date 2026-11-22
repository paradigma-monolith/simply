<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR;

if (!defined('ROOTPATH')) {
    define('ROOTPATH', $root);
}
if (!defined('APPPATH')) {
    define('APPPATH', $root . 'app' . DIRECTORY_SEPARATOR);
}
if (!defined('WRITEPATH')) {
    define('WRITEPATH', $root . 'writable' . DIRECTORY_SEPARATOR);
}
if (!defined('FCPATH')) {
    define('FCPATH', $root . 'public' . DIRECTORY_SEPARATOR);
}
if (!defined('SYSTEMPATH')) {
    define('SYSTEMPATH', $root . 'vendor' . DIRECTORY_SEPARATOR . 'codeigniter4' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR);
}

require_once $root . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once APPPATH . 'Helpers' . DIRECTORY_SEPARATOR . 'tanggal_helper.php';

if (!function_exists('env')) {
    function env(string $key, $default = null)
    {
        $val = getenv($key);
        return ($val === false) ? $default : $val;
    }
}

if (!function_exists('esc')) {
    function esc($data, string $context = 'html', ?string $encoding = null)
    {
        return htmlspecialchars((string) $data, ENT_QUOTES | ENT_SUBSTITUTE, $encoding ?? 'UTF-8');
    }
}

if (!function_exists('log_message')) {
    function log_message(string $level, string $message, array $context = []): bool
    {
        return true;
    }
}

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $rel  = substr($class, strlen($prefix));
    $file = APPPATH . str_replace('\\', DIRECTORY_SEPARATOR, $rel) . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});
