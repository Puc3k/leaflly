# 🌿 Leaflly – Smart Plant Care API (Laravel)

**Leaflly** is a backend application built with **Laravel 10** that provides a full-featured REST API for managing indoor plant collections. It supports user authentication, plant database browsing, personal collections, notes, and a flexible notification system. Designed as a backend layer for future SPA or mobile frontend integration.

---

## 🚀 Features

- ✅ User registration and login
- ✅ Email verification during signup
- ✅ Password reset
- ✅ Browse plant database
- ✅ Filter/search plants by criteria
- ✅ Add and manage user’s own plant collection
- ✅ Customize plants (name, location, last watering date)
- ✅ Add personal notes for each plant
- ✅ View upcoming care reminders
- ✅ Mark tasks (e.g. watering) as completed
- ✅ Configure notification types
- ✅ Push/email notifications

---

## 🛠️ Tech Stack

- Laravel 10
- PHP 8.x
- MySQL
- Sanctum (authentication)
- Push notifications Firebase + kreait/firebase-php
- Laravel Validation, Eloquent, Policies
- REST API (JSON)
- Documentation Scribe

---

## ⚙️ Local Installation

```bash
git clone https://github.com/Puc3k/leaflly.git
cd leaflly

composer install
cp .env.example .env
php artisan key:generate
```

## 📷 Screenshots
(Coming soon – to be added in the /public/screenshots/ folder)

## 📄 API Documentation
Leaflly uses Laravel Scribe to auto-generate API documentation.
You can view it locally after running the app at:

http://localhost:8000/docs
