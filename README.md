# PSOL FRANCA - Consultancy Platform & Admin Management System

![Project Banner](https://via.placeholder.com/1200x400?text=PSOL+FRANCA+Consultancy+Platform)

**PSOL FRANCA** is a comprehensive full-stack web application designed for a professional consultancy firm. It features a modern, responsive frontend for clients and a powerful, secure backend dashboard for administrators to manage services, projects, and inquiries.

---

## 🚀 Key Features

### **Visitor Frontend**

- **Service Showcase:** Exploratory pages for Study Abroad, IELTS/PTE Prep, and Digital Marketing services.
- **Lead Generation:** Integrated "Get Started" inquiry system with real-time feedback.
- **Project Portfolio:** Dynamic display of consultancy projects with high-quality images and embedded video support.
- **Review System:** Public-facing reviews from satisfied clients to build trust.
- **Responsive Design:** Fully mobile-optimized interface built with Bootstrap 5.

### **Admin Dashboard**

- **Project Management:** Add, edit, and delete projects. Supports multiple image uploads and YouTube/Vimeo links.
- **Inquiry Management:** Centralized hub to view and manage client leads.
- **Review Moderation:** System to approve and manage user reviews.
- **Profile Security:** Update admin credentials, manage email, and secure password reset via token-based recovery.
- **Session Management:** Secure login/logout functionality.

---

## 🛠️ Tech Stack

- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5
- **Backend:** PHP (Procedural/OOP)
- **Database:** MySQL
- **Tooling:** XAMPP, Git, Google Fonts

---

## 📸 Screenshots

| Home Page                                    | Admin Dashboard                                   | Inquiry Form                                 |
| -------------------------------------------- | ------------------------------------------------- | -------------------------------------------- |
| ![Home](https://via.placeholder.com/400x250) | ![Dashboard](https://via.placeholder.com/400x250) | ![Form](https://via.placeholder.com/400x250) |

---

## ⚙️ Installation & Setup

### **Prerequisites**

- [XAMPP](https://www.apachefriends.org/index.html) or any local server environment (Apache, MySQL, PHP).

### **Steps**

1. **Clone the repository:**

   ```bash
   git clone https://github.com/Furqan15380/PSOL_FRANCA.git
   ```

2. **Database Setup:**
   - Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
   - Create a new database named `psol_franca`.
   - Import the `database_setup.sql` file located in the project root.

3. **Configure Connection:**
   - Open `includes/db_connect.php`.
   - Update the database credentials (host, user, pass, dbname) if they differ from default.

4. **Run Application:**
   - Move the project folder to `C:/xampp/htdocs/`.
   - Access the site via `http://localhost/PsolFranca`.

5. **Admin Access:**
   - **Login URL:** `http://localhost/PsolFranca/login.php`
   - **Default Email:** `admin@psolfranca.com`
   - **Default Password:** `admin123` _(Change this immediately after login!)_

---

## 🔒 Security Measures

- **Password Hashing:** Uses `password_hash()` for secure credential storage.
- **SQL Injection Protection:** Implemented using MySQLi prepared statements.
- **Token-based Reset:** Secure password recovery using expiring tokens.
- **Session Security:** Server-side session validation for all admin routes.

---

## 📄 License

This project is for portfolio purposes. Feel free to use and modify for learning.

---

## 👤 Author

**Furqan**

- GitHub: [@Furqan15380](https://github.com/Furqan15380)

---

_Developed with ❤️ as part of my Full-Stack Portfolio._
