<?php
// Tải cấu hình từ file .env ở thư mục gốc
function loadEnv($path) {
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

loadEnv(__DIR__ . '/../.env');

define('CLIENT_ID', $_ENV['MICROSOFT_CLIENT_ID'] ?? '');
define('TENANT_ID', $_ENV['MICROSOFT_TENANT_ID'] ?? 'common');
define('CLIENT_SECRET', $_ENV['MICROSOFT_CLIENT_SECRET'] ?? '');
define('REDIRECT_URI', $_ENV['MICROSOFT_REDIRECT_URI'] ?? 'http://localhost:8000/callback.php');

$auth_url = "https://login.microsoftonline.com/" . TENANT_ID . "/oauth2/v2.0/authorize";
$token_url = "https://login.microsoftonline.com/" . TENANT_ID . "/oauth2/v2.0/token";
$scopes = "openid profile email User.Read";
