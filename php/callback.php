<?php
require_once 'config.php';

session_start();

if (!isset($_GET['code'])) {
    die("Lỗi: Không tìm thấy mã code từ Microsoft.");
}

// Kiểm tra state để chống CSRF
if (!isset($_GET['state']) || $_GET['state'] !== $_SESSION['state']) {
    die("Lỗi: State không khớp. Có thể bạn đang bị tấn công CSRF.");
}

$code = $_GET['code'];

// 1. Đổi Authorization Code lấy Access Token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $token_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'client_id' => CLIENT_ID,
    'scope' => 'openid profile email User.Read',
    'code' => $code,
    'redirect_uri' => REDIRECT_URI,
    'grant_type' => 'authorization_code',
    'client_secret' => CLIENT_SECRET,
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$data = json_decode($response, true);
// curl_close($ch); // Không cần thiết trong PHP 8.0+


if (isset($data['error'])) {
    echo "<h2>Lỗi lấy Token:</h2>";
    echo "<pre>" . print_r($data, true) . "</pre>";
    exit;
}

$access_token = $data['access_token'];

// 2. Sử dụng Access Token để gọi Microsoft Graph API lấy thông tin User
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://graph.microsoft.com/v1.0/me");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $access_token",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$user_data = curl_exec($ch);
$user = json_decode($user_data, true);
// curl_close($ch); // Không cần thiết trong PHP 8.0+


?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin đăng nhập</title>
    <style>
        body { font-family: sans-serif; padding: 2rem; background: #f0f2f5; }
        .container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 600px; margin: auto; }
        h1 { color: #1a73e8; }
        pre { background: #eee; padding: 1rem; border-radius: 4px; overflow-x: auto; }
        .success { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="success">Đăng nhập thành công!</h1>
        <p>Chào mừng, <strong><?php echo htmlspecialchars($user['displayName'] ?? 'User'); ?></strong>!</p>
        
        <h3>Thông tin chi tiết từ Microsoft Graph:</h3>
        <pre><?php echo json_encode($user, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); ?></pre>

        <p><a href="index.php">Quay lại trang chủ</a></p>
    </div>
</body>
</html>
