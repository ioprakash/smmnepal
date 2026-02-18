<?php

define('PATH', realpath('.'));

$dotenv = [];
$dotenvPath = __DIR__ . '/../.env';
if (is_readable($dotenvPath)) {
  $dotenv = parse_ini_file($dotenvPath, false, INI_SCANNER_RAW);
  if (!is_array($dotenv)) {
    $dotenv = [];
  }
}

function env_value(string $key, $default = null)
{
  global $dotenv;

  $value = getenv($key);
  if ($value !== false) {
    return $value;
  }

  if (isset($_ENV[$key])) {
    return $_ENV[$key];
  }

  if (array_key_exists($key, $dotenv)) {
    $raw = (string) $dotenv[$key];
    return trim($raw, " \t\n\r\0\x0B\"'");
  }

  return $default;
}

function env_bool(string $key, bool $default = false): bool
{
  $value = env_value($key, $default ? 'true' : 'false');
  return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? $default;
}

function default_app_url(): string
{
  if (!empty($_SERVER['HTTP_HOST'])) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    return $scheme . '://' . $_SERVER['HTTP_HOST'];
  }

  return 'http://localhost';
}

define('SUBFOLDER', env_bool('SUBFOLDER', false));

$appUrl = rtrim((string) env_value('APP_URL', default_app_url()), '/');
define('URL', $appUrl);

$assetsUrl = (string) env_value('STYLESHEETS_URL', '');
if ($assetsUrl === '') {
  $parsed = parse_url($appUrl);
  if (is_array($parsed) && !empty($parsed['host'])) {
    $assetsUrl = '//' . $parsed['host'];
    if (!empty($parsed['port'])) {
      $assetsUrl .= ':' . $parsed['port'];
    }
    if (!empty($parsed['path']) && $parsed['path'] !== '/') {
      $assetsUrl .= rtrim($parsed['path'], '/');
    }
  } else {
    $assetsUrl = $appUrl;
  }
}
define('STYLESHEETS_URL', $assetsUrl);

$timezone = (string) env_value('APP_TIMEZONE', 'Asia/Kolkata');
@date_default_timezone_set($timezone);

$debug = env_bool('APP_DEBUG', false);
if ($debug) {
  ini_set('display_errors', '1');
  ini_set('display_startup_errors', '1');
  error_reporting(E_ALL);
} else {
  ini_set('display_errors', '0');
  error_reporting(0);
}

return [
  'db' => [
    'name' => (string) env_value('DB_DATABASE', 'smmnepal'),
    'host' => (string) env_value('DB_HOST', 'localhost'),
    'port' => (int) env_value('DB_PORT', 3306),
    'user' => (string) env_value('DB_USERNAME', 'smmnepal'),
    'pass' => (string) env_value('DB_PASSWORD', ''),
    'charset' => (string) env_value('DB_CHARSET', 'utf8mb4'),
  ],
];
