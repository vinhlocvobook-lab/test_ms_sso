# Microsoft SSO Test Lab

Dự án này được tạo ra để thử nghiệm chức năng đăng nhập vào ứng dụng web bằng tài khoản Microsoft thông qua giao thức SSO (Single Sign-On).

Dự án bao gồm 2 phương thức triển khai chính:
1. **Cách 1**: Sử dụng PHP thuần.
2. **Cách 2**: Sử dụng kết hợp ReactJS (Frontend) và NodeJS (Backend).

---

## 1. Cấu hình Azure Portal

Trước khi bắt đầu, bạn cần đăng ký một ứng dụng trên [Azure Portal](https://portal.azure.com/):

1. Truy cập **Azure Active Directory** -> **App registrations** -> **New registration**.
2. Đặt tên ứng dụng và chọn **Supported account types** (thường là "Accounts in any organizational directory and personal Microsoft accounts").
3. Thêm **Redirect URI**:
   - Đối với PHP: `http://localhost:8000/callback.php` (tùy cấu hình server của bạn).
   - Đối với React/Node: `http://localhost:3000/auth/callback`.
4. Sau khi đăng ký, copy **Application (client) ID** và **Directory (tenant) ID**.
5. Truy cập **Certificates & secrets** -> **New client secret** -> Copy giá trị **Value**.

---

## 2. Cấu hình Môi trường

Sao chép file `.env_sample` thành `.env` và điền các thông tin đã lấy từ Azure Portal:

```bash
cp .env_sample .env
```

Nội dung file `.env`:
```env
MICROSOFT_CLIENT_ID=your_client_id
MICROSOFT_TENANT_ID=common
MICROSOFT_CLIENT_SECRET=your_client_secret
MICROSOFT_REDIRECT_URI=your_redirect_uri
```

---

## 3. Hướng dẫn sử dụng

### Cách 1: PHP Thuần (Pure PHP)
Thư mục: `php/`

- Cài đặt các thư viện cần thiết (nếu có sử dụng Composer).
- Chạy server PHP:
  ```bash
  cd php
  php -S localhost:8000
  ```

### Cách 2: ReactJS + NodeJS
- **Backend (NodeJS)**:
  Thư mục: `nodejs/`
  ```bash
  cd nodejs
  npm install
  npm start
  ```
- **Frontend (ReactJS)**:
  Thư mục: `reactjs/`
  ```bash
  cd reactjs
  npm install
  npm start
  ```

---

## 4. GitHub Repository
Link: [https://github.com/vinhlocvobook-lab/test_ms_sso.git](https://github.com/vinhlocvobook-lab/test_ms_sso.git)
