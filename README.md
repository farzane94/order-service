# پروژه Laravel Orders API (داکرایز + ماژولار + JWT Auth)

پروژه‌ای برای ثبت سفارش با قابلیت انتخاب چند محصول، مدیریت روش ارسال، هندل race condition و ساختار ماژولار با لاراول ۱۲ و داکر.

---

## ⚙️ راه‌اندازی پروژه

### پیش‌نیازها

- Docker
- Docker Compose

---

### مراحل

1. کلون کردن پروژه:

```bash
git clone https://github.com/your-username/your-project.git
cd your-project
```

2. کپی فایل محیطی:

```bash
cp .env.example .env
```

3. اجرای داکر و ساخت کانتینرها:

```bash
docker-compose up -d --build
```

4. نصب وابستگی‌ها:

```bash
docker exec app composer install
```

5. تولید کلید اپلیکیشن و JWT secret:

```bash
docker exec app php artisan key:generate
docker exec app php artisan jwt:secret
```

6. اجرای مایگریشن :

```bash
docker exec app php artisan migrate
```

7. وارد کردن محصولات از API خارجی:

```bash
docker exec app php artisan import:products
```

---

## 🔐 احراز هویت JWT

### ثبت‌نام

```http
POST /api/register
Content-Type: application/json

{
  "name": "Test User",
  "email": "test@example.com",
  "password": "123456"
}
```

### ورود

```http
POST /api/login
Content-Type: application/json

{
  "email": "test@example.com",
  "password": "123456"
}
```

در پاسخ توکن JWT دریافت می‌کنید که باید در درخواست‌های بعدی ارسال شود:

```
Authorization: Bearer <token>
```

---

## 📦 API سفارش‌گذاری

### ثبت سفارش

```http
POST /api/orders
Content-Type: application/json
Authorization: Bearer <token>

{
  "shipping_method": "post", // یا "tipax" یا "chapar"
  "products": [
    { "product_id": 1, "quantity": 2 },
    { "product_id": 3, "quantity": 1 }
  ]
}
```

> مبلغ کل سفارش شامل جمع قیمت محصولات ضرب در تعداد به اضافه هزینه ارسال محاسبه و ذخیره می‌شود.

---

## 🔄 به‌روزرسانی وضعیت سفارشات

برای تغییر وضعیت سفارشات قدیمی (بیش از ۲۴ ساعت در وضعیت `shipped` به `delivered`):

```bash
docker exec app php artisan orders:update-status
```
🌐 phpMyAdmin: http://localhost:8080


🌐 API: http://localhost:8000
---

## 🛡️ هندل Race Condition

- در زمان ثبت سفارش برای هر محصول، از `lockForUpdate()` استفاده شده است.
- این باعث می‌شود در مواقع رقابت سفارش‌دهی روی کالای محدود، تنها یکی بتواند موفق به سفارش شود.

---

## 🧱 ساختار پروژه

- Laravel 12
- ساختار ماژولار با استفاده از پکیج [`nWidart/laravel-modules`](https://github.com/nWidart/laravel-modules)
- احراز هویت با JWT
- مدیریت سفارش با پشتیبانی چند محصول در هر سفارش
- استفاده از Docker و Docker Compose

---

---

## 🧑‍💻 توسعه‌دهنده

Farzaneh Rahmani

---
