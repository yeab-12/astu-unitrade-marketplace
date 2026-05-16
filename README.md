# 📘 UniTrade Student Marketplace & Resource Exchange System

## 🏫 ADAMA SCIENCE AND TECHNOLOGY UNIVERSITY (ASTU)
Department of Software Engineering  
Course: Engineering Web-Based System (SEng3202)

---

## 👨‍🎓 Team Members

- Faysel Nessro — Ugr/34398/16  
- Eyoab Nigusie — Ugr/34353/16  
- Fitsum Kurabachew — Ugr/34462/16  
- Simret Mesfin — Ugr/35426/16  
- Yeabsira Getachew — Ugr/35614/16  
- Biruk Abebe — Ugr/25917/14  

---

**Submitted to:** Mr. Alemayew Megersa  
**Submission Date:** May 14, 2026  

---

# 📌 Project Overview

UniTrade is a student-to-student marketplace platform designed for ASTU students.  
It enables students to buy, sell, and exchange items such as electronics, clothing, stationery, dorm materials, and food.

The platform ensures safe trading through:
- Verified student accounts
- Admin approval system
- Telegram-based communication

---

# 🎯 Objectives

- Build a campus-based digital marketplace  
- Ensure safe and verified student trading  
- Provide fast communication via Telegram  
- Offer simple and responsive UI design  

---

# ⚙️ Features

## 👤 User System
- Student registration & login
- Profile management
- Seller verification system

## 🛒 Marketplace
- Category-based browsing
- Search & filter system
- Detailed item pages

## 📤 Selling System
- Upload items with images
- Admin approval workflow
- Status tracking (Pending / Approved / Rejected)

## 💬 Communication
- Telegram contact integration
- Feedback system

---

# 🧱 Tech Stack

- Frontend: HTML5, CSS3, Bootstrap, JavaScript  
- Backend: PHP  
- Database: MySQL (XAMPP)  
- Icons: Bootstrap Icons  

---

## 🖼️ Screenshots

### 🏠 Home Page
![Home Page](screenshots/home.png)

### 🛒 Profile
![profile](screenshots/profile.png)

### 🔐 Login Page
![Login](screenshots/login.png)

### 📊 Admin Dashboard
![admin](screenshots/admin.png)

### 🔐 Marketplace Page
![marketplace](screenshots/marketplace.png)

### 📊 Items 
![Items](screenshots/items.png)

# 📁 Project Structure
```text
unitrade/
├── index.php             # Home page (Landing page / featured items)
├── login.php             # User authentication (Login page)
├── signup.php            # User registration (Sign up page)
├── logout.php            # Destroys session and logs out user
├── marketplace.php       # Main browsing hub for available items
├── item.php              # Detailed view of a single product/item
├── profile.php           # User profile and managed listings
├── sell.php              # Form page to post/list a new item
├── about.php             # Platform information and team details
├── contact.php           # Customer support / feedback form
│
├── admin/                # Admin Panel Directory
│   ├── dashboard.php     # Admin overview statistics and metrics
│   ├── items.php         # Manage / moderate listed items
│   ├── users.php         # Manage registered user accounts
│   ├── edit_item.php     # Admin tool to modify item details
│   └── messages.php      # View and handle user contact submissions
│
├── includes/             # Shared Components & Backend Scripts
│   ├── db.php            # Database connection configuration
│   ├── auth.php          # Session validation and access control helpers
│   ├── header.php        # Global header template (Navigation bar)
│   └── footer.php        # Global footer template
│
├── css/                  # Stylesheets
│   └── style.css         # Main application stylesheet
│
├── js/                   # Clientside Scripts
│   └── app.js            # Main JavaScript file for UI interactions
│
├── uploads/              # Dynamic Directory for uploaded item images
│
└── database/             # Database Backups
    └── unitrade.sql      # Core SQL dump for initial database schema setup
---

# 🚀 Conclusion

UniTrade is a secure and efficient student marketplace system for ASTU.  
It improves student trading, communication, and campus digital economy.

Future improvements:
- Online payment system
- Rating & review system
- Mobile application
