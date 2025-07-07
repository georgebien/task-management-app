This project is a simple task manager built with a Vue.js frontend and a Laravel backend.
It demonstrates best practices in  code structure to ensure a maintainable, scalable, and user-friendly experience.

---

## 🎨 UI & UX

- This app focuses on the implementation of the backend so the UI and UX is in simplest state and might not be mobile-friendly 

---

## 🧠 Code Implementation Strategy

### ✅ Frontend (Vue 3 + Vite)
- Uses `axios` with a centralized base URL and service abstraction to keep API logic clean.
- State and props are managed cleanly across components, and dynamic UI changes are reactive and predictable.

### ✅ Backend (Laravel 10)
- Follows Laravel’s MVC structure, with clean separation of Controllers, Services, and Routes.
- Uses dependency injection and service classes (e.g. `TaskService`) to keep controllers thin.
- API routes are versioned and follow RESTful best practices.

### ✅ Testing
- Unit testing was not implemented since I focused on the main objective which is the funcationality

---

## 🔗 Tech Stack

| Layer     | Technology     |
|-----------|----------------|
| Frontend  | Vue 3 + Vite   |
| Styling   | Bootstrap 5 + Custom CSS |
| Backend   | Laravel 10 (PHP 8) |
| API Client| Axios          |

---

## 💡 Why This Approach is Ideal

- **Scalability**: Easy to extend with new features or services.
- **Maintainability**: Organized and testable code base.
- **Performance**: Vite ensures fast build and reload times.
- **Developer Experience**: Clean separation of concerns makes onboarding and collaboration smooth.

---
