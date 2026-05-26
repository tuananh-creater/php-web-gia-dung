# ⚙️ Cài đặt dự án

## 1. Clone project

```bash
git clone https://github.com/yourname/project-name.git
```

---

## 2. Cài package

```bash
composer install
```

---

## 3. Copy file env

```bash
cp .env.example .env
```

---

## 4. Tạo key

```bash
php artisan key:generate
```

---

## 5. Cấu hình database trong `.env`

```env
DB_DATABASE=shop
DB_USERNAME=root
DB_PASSWORD=
```

---

## 6. Chạy migration

```bash
php artisan migrate
```

---

## 7. Chạy seed dữ liệu mẫu

```bash
php artisan db:seed
```

---

## 8. Tạo symbolic link storage

```bash
php artisan storage:link
```

---

## 9. Chạy project

```bash
php artisan serve
```

---

# 🔐 Đăng nhập hệ thống

## 👑 Admin

```plaintext
http://127.0.0.1:8000/admin/login
```

---

## 👤 Người dùng

```plaintext
http://127.0.0.1:8000/login
```
