<?php
require_once 'config.php';

session_start();

// Tạo state để bảo mật (CSRF)
if (empty($_SESSION['state'])) {
    $_SESSION['state'] = bin2hex(random_bytes(16));
}

$params = [
    'client_id' => CLIENT_ID,
    'response_type' => 'code',
    'redirect_uri' => REDIRECT_URI,
    'response_mode' => 'query',
    'scope' => 'openid profile email User.Read',
    'state' => $_SESSION['state'],
    'prompt' => 'select_account', // Ép buộc hiển thị bảng chọn tài khoản
];

$login_url = $auth_url . "?" . http_build_query($params);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Microsoft SSO Login - PHP Thuần</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f4f4f9; margin: 0; }
        .card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; max-width: 400px; width: 100%; }
        h1 { color: #333; margin-bottom: 1.5rem; }
        .btn-ms { background-color: #2f2f2f; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-flex; align-items: center; gap: 10px; font-weight: 600; transition: background 0.3s; }
        .btn-ms:hover { background-color: #000; }
        .btn-ms img { width: 20px; height: 20px; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Đăng nhập</h1>
        <p>Sử dụng tài khoản Microsoft của bạn để tiếp tục.</p>
        <a href="<?php echo $login_url; ?>" class="btn-ms">
            <img src="https://auth.microsoft.com/favicon.ico" alt="MS Logo">
            Đăng nhập với Microsoft
        </a>
    </div>
</body>
</html>
