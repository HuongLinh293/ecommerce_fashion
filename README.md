# Vivillan - E-commerce Laravel (local dev)

Small guide to clone and run this repository locally.

Prerequisites
- PHP 8.1+ (this project used PHP 8.2)
- Composer
- Node.js + npm (for assets)
- MySQL or compatible database

Quickstart (Windows / PowerShell)

1. Clone

```powershell
git clone https://github.com/<your-username>/<repo>.git
cd <repo>
```

2. Copy environment example and set secrets

```powershell
Copy-Item .env.example .env
# Edit .env and set DB_DATABASE, DB_USERNAME, DB_PASSWORD, APP_KEY if needed
```

3. Install PHP dependencies

```powershell
composer install
```

4. Install JS dependencies & build assets (optional for dev)

```powershell
npm ci
npm run dev   # or npm run build
```

5. Generate app key

```powershell
php artisan key:generate
```

6. Create DB and run migrations

Create an empty database matching .env DB_DATABASE, then:

```powershell
php artisan migrate --seed
```

7. Serve

```powershell
php artisan serve
# Open http://127.0.0.1:8000
```

Notes
- Do NOT commit `.env` (it's in .gitignore). Keep secrets out of git.
- `vendor/` and `node_modules/` are ignored and should not be pushed.
- If you use ngrok or webhooks (payments), update `VNPAY_RETURN_URL` and other keys in `.env`.

If you want, I can:
- prepare `.env.example` automatically from your current `.env` and scrub secrets (performed),
- add a `setup.sh` or `Makefile` for easier setup, or
- create a short GitHub Actions workflow to run tests on push.
# 🛍️ Ecommerce Fashion

> Một dự án thương mại điện tử xây dựng bằng **Laravel** — hỗ trợ quản lý sản phẩm, đơn hàng, khách hàng, và trang quản trị.

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

---

## ✨ Tính năng chính

- 🧍 Quản lý khách hàng, đăng ký/đăng nhập  
- 🛒 Giỏ hàng & đặt hàng theo thời gian thực  
- 📦 Quản lý sản phẩm (CRUD)  
- 📊 Dashboard thống kê doanh thu, đơn hàng  
- 🔐 Phân quyền (Admin & User)  
- 📱 Giao diện responsive, thân thiện với người dùng

---

## 🧰 Công nghệ sử dụng

- **Backend:** Laravel 10+, PHP 8.2+  
- **Database:** MySQL  
- **Frontend:** Blade template, Tailwind CSS  
- **Chart:** Chart.js / Recharts (trang thống kê)

---

## 🚀 Cài đặt

### 1️⃣ Clone project
```bash
git clone https://github.com/HuongLinh293/ecommerce_fashion.git
cd ecommerce_fashion
 