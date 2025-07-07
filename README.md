# 📝 Task Management App

This project is a simple task manager built with a Vue.js frontend and a Laravel backend.  
It demonstrates best practices in code structure to ensure a maintainable, scalable, and user-friendly experience.

---

## 🎨 UI & UX

- This app focuses on the implementation of the backend, so the UI and UX is in its simplest state and may not be mobile-friendly.

---

## 🧠 Code Implementation Strategy

### ✅ Frontend (Vue 3 + Vite)
- Uses `axios` with a centralized base URL and service abstraction to keep API logic clean.
- State and props are managed cleanly across components, and dynamic UI changes are reactive and predictable.

### ✅ Backend (Laravel 10)
- Follows Controllers, Services, and Repository structure.
- Uses custom FormRequest classes for validation.
- Uses dependency injection and service classes (e.g. `TaskService`) to keep controllers thin.
- API routes are versioned and follow RESTful best practices.
- Uses **SQLite** as the database for portability and simplicity.

### ✅ Testing
- Unit testing was not implemented since I focused on the main objective, which is the functionality.

---

## 🔗 Tech Stack

| Layer     | Technology             |
|-----------|------------------------|
| Frontend  | Vue 3 + Vite           |
| Styling   | Bootstrap 5 + Custom CSS |
| Backend   | Laravel 10 (PHP 8)     |
| API Client| Axios                  |
| Database  | SQLite                 |

---

## ⚙️ Manual Setup (No Docker)

> These steps assume you have **PHP 8**, **Composer**, **Node.js**, and **npm** installed.

### 📁 Folder Structure

task-management-app/
├── backend/ # Laravel API
└── frontend/ # Vue 3 SPA

---

### ▶️ Backend Setup

open the folder location in bash or any command line
cd backend

## 1. Install dependencies
composer install

## 2. Copy .env and set up database
cp .env.example .env
touch database/database.sqlite

## 3. Configure .env (use SQLite)
## DB_CONNECTION=sqlite
## DB_DATABASE=database/database.sqlite

## 4. Generate app key
php artisan key:generate

## 5. Run migrations
php artisan migrate

## 6. Start Laravel backend (http://localhost:8000)
php artisan serve

### ▶️ Frontend Setup

cd frontend

## 1. Install dependencies
npm install

## 2. Start dev server (http://localhost:5173)
npm run dev
