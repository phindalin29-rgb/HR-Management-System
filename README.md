# 👨‍💼 HR Management System

> 🚀 A modern **Human Resource Management System** built with **Laravel 12**, **Tailwind CSS 4**, and **Vite** to streamline employee management, attendance, leave, payroll, recruitment, performance, training, and HR operations.

---

## 📌 About The Project

**HR Management System** is a web-based platform designed to help organizations manage their human resources and daily employee operations in one centralized system.

The system provides separate workflows for **HR/Admin** and **Employees**, allowing HR managers to manage employee information while employees can access self-service features such as attendance and leave requests.

---

## ✨ Key Features

### 👥 Employee Management

* 👤 Employee profiles
* 🏢 Departments
* 💼 Designations / Positions
* 🔎 Employee search
* 📋 Employee information management

### ⏰ Attendance Management

* 🟢 Check-in / Check-out
* 📅 Daily attendance
* 📊 Monthly attendance matrix
* 🕐 Working hours
* ⚠️ Late arrival detection
* 🌓 Half-day detection
* 👨‍💼 Admin attendance management
* 👤 Employee attendance history

### 🏖️ Leave Management

* 📝 Employee leave requests
* ✅ Leave approval
* ❌ Leave rejection
* 📋 Leave types
* 📅 Holidays
* ⚙️ Leave settings
* 🔔 Leave status notifications

### 💰 Payroll

* 💵 Staff salary management
* 🧾 Payroll information
* 📄 Payslip / PDF support
* 📊 Salary-related records

### 📈 Performance Management

* 🎯 Performance indicators
* 📝 Performance reviews
* ⭐ Employee appraisals

### 🎓 Training Management

* 📚 Training programs
* 👨‍🏫 Trainers
* 🏷️ Training types
* 👥 Employee training management

### 🎯 Recruitment

* 💼 Job vacancies
* 👤 Candidates / Applications
* 📄 Applications
* ❓ Interview questions
* 🗓️ Interview management

### 💳 Finance

* 💸 Expenses
* 📑 Estimates
* 💰 Payment-related records

### 🔐 User & Access Management

* 👤 Users
* 🛡️ Roles
* 🔑 Permissions
* 🔒 Authentication
* ⚙️ User profiles

### 📝 Activity / Audit Log

* 📌 Create activity tracking
* ✏️ Update activity tracking
* 🗑️ Delete activity tracking
* 🔎 Module filtering
* 🔎 Action filtering
* 👨‍💼 Admin activity monitoring

### 📊 Dashboard & Reports

* 👥 Total Employees
* 🏢 Total Departments
* 🟢 Present Today
* 🏖️ Pending Leave Requests
* 📊 Employees by Department
* 📈 Attendance – Last 7 Days
* 📑 HR reports

---

# 🆕 What's New in This Update

## ⏰ 1. Real Attendance Module

Attendance is now **database-backed** instead of using static demo data.

### Included

* `attendances` database table
* `Attendance` model
* Employee Check-in
* Employee Check-out
* Automatic late detection
* Half-day detection
* Working-hour calculation
* Monthly attendance matrix
* Personal attendance history

### Workflow

```text
👤 Employee
     │
     ▼
🟢 Check In
     │
     ▼
💼 Working
     │
     ▼
🔴 Check Out
     │
     ▼
📊 Attendance Record
```

---

## 📝 2. Activity / Audit Log

The system now records important activities performed by users.

### Tracked Actions

```text
➕ Create
✏️ Update
🗑️ Delete
```

### Database

```text
audit_logs
```

### Model

```text
AuditLog
```

Audit logging is automatically handled through the `LogsAudit` trait.

Currently tracked modules include:

* 👥 Employees
* 🏖️ Leaves
* ⏰ Attendance

Admins can view the records from:

```text
User Controller → Activity Log
```

Filters are available by:

* Module
* Action

---

# 📊 3. Real Dashboard Data

The Dashboard has been updated to use **real database data** instead of hard-coded demo numbers.

### Dashboard Statistics

```text
👥 Employees
🏢 Departments
🟢 Present Today
🏖️ Pending Leave Requests
```

### Charts

📊 **Employees by Department**

Shows the number of employees in each department.

📈 **Attendance – Last 7 Days**

Shows attendance activity during the previous seven days.

The charts use **Morris.js** without requiring an additional project dependency.

---

# 🧹 4. Project Cleanup

The project was cleaned up for distribution and GitHub usage.

Removed:

```text
❌ .git
❌ Real .env
❌ Duplicate README
```

Kept:

```text
✅ .env.example
```

> 🔐 Never commit your real `.env` file because it may contain database credentials, application keys, API keys, or other sensitive configuration.

---

# 🧩 Modules

| #  | Module              | Description                       |
| -- | ------------------- | --------------------------------- |
| 1  | 👤 Employees        | Employee information and profiles |
| 2  | 🏢 Departments      | Department management             |
| 3  | 💼 Designations     | Position / job title management   |
| 4  | ⏰ Attendance        | Employee attendance tracking      |
| 5  | 📝 Timesheet        | Work-hour and task tracking       |
| 6  | 🕐 Shift & Schedule | Work shifts and schedules         |
| 7  | ⏱️ Overtime         | Overtime management               |
| 8  | 🏖️ Leaves          | Employee leave requests           |
| 9  | ⚙️ Leave Settings   | Leave configuration               |
| 10 | 📅 Holidays         | Organization holidays             |
| 11 | 💰 Payroll          | Salary and payroll management     |
| 12 | 📈 Performance      | Employee performance              |
| 13 | 🎓 Training         | Training and trainers             |
| 14 | 🎯 Recruitment      | Jobs and applications             |
| 15 | 💸 Finance          | Expenses and estimates            |
| 16 | 🔐 Users            | Users, roles and permissions      |
| 17 | 📝 Audit Log        | User activity tracking            |
| 18 | 📊 Dashboard        | HR statistics and charts          |
| 19 | 📑 Reports          | HR reports                        |
| 20 | ⚙️ Company Settings | Organization configuration        |

---

# 🛠️ Technologies

### Backend

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge\&logo=laravel\&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge\&logo=php\&logoColor=white)

* Laravel 12
* PHP ^8.2
* Laravel Eloquent ORM
* Laravel Blade
* Laravel Authentication

### Frontend

![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?style=for-the-badge\&logo=tailwindcss\&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-7-646CFF?style=for-the-badge\&logo=vite\&logoColor=white)

* Tailwind CSS 4
* Vite
* Axios
* Blade Templates
* JavaScript
* Morris.js

### Database

![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=for-the-badge\&logo=mysql\&logoColor=white)

* MySQL
* Laravel Migrations
* Eloquent Relationships

### Additional Packages

* 📄 `barryvdh/laravel-dompdf` — PDF generation
* 📊 `maatwebsite/excel` — Excel export/import
* 🔔 `php-flasher` — Notifications

---

# 🏗️ System Architecture

```text
                    👨‍💼 HR MANAGER
                          │
                          ▼
                    📊 DASHBOARD
                          │
        ┌─────────────────┼─────────────────┐
        │                 │                 │
        ▼                 ▼                 ▼
   👥 Employees      ⏰ Attendance       🏖️ Leave
        │                 │                 │
        ▼                 ▼                 ▼
 Departments         Timesheet          Approval
 Designations        Overtime          Settings
        │                 │                 │
        └─────────────────┼─────────────────┘
                          │
                          ▼
                      💰 Payroll
                          │
                          ▼
                       🧾 Payslip
```

### Recruitment Flow

```text
💼 Job Vacancy
      │
      ▼
👤 Candidate
      │
      ▼
📄 Application / Resume
      │
      ▼
🔎 Shortlist
      │
      ▼
🗓️ Interview
      │
      ▼
❓ Assessment
      │
      ▼
📨 Offer
      │
      ▼
✅ Hired
      │
      ▼
👥 Employee
```

---

# 🔄 HR Workflow

```text
Employee
   │
   ├── 👤 Profile
   ├── ⏰ Attendance
   ├── 📝 Timesheet
   ├── 🏖️ Leave Request
   ├── ⏱️ Overtime
   ├── 💰 Payslip
   ├── 📈 Performance
   └── 🎓 Training
```

### Leave Workflow

```text
👤 Employee
     │
     ▼
📝 Submit Leave Request
     │
     ▼
⏳ Pending
     │
     ▼
👨‍💼 HR Manager
     │
   ┌─┴─┐
   ▼   ▼
 ✅    ❌
Approve Reject
```

---

# 📁 Project Structure

```text
HR-Management-System/
│
├── 📁 app/
│   ├── Models/
│   ├── Http/
│   └── Traits/
│
├── 📁 database/
│   ├── migrations/
│   └── seeders/
│
├── 📁 resources/
│   ├── views/
│   ├── css/
│   └── js/
│
├── 📁 routes/
│   └── web.php
│
├── 📁 public/
│
├── 📄 .env.example
├── 📄 artisan
├── 📄 composer.json
├── 📄 package.json
├── 📄 vite.config.js
└── 📄 README.md
```

---

# 🚀 Installation

## 1️⃣ Clone the Repository

```bash
git clone https://github.com/phindalin29-rgb/HR-Management-System.git
```

```bash
cd HR-Management-System
```

---

## 2️⃣ Install PHP Dependencies

```bash
composer install
```

---

## 3️⃣ Configure Environment

Copy `.env.example` to `.env`.

### Windows

```cmd
copy .env.example .env
```

### Linux / macOS

```bash
cp .env.example .env
```

---

## 4️⃣ Generate Application Key

```bash
php artisan key:generate
```

---

## 5️⃣ Configure Database

Create a MySQL database and update your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=root
DB_PASSWORD=
```

---

## 6️⃣ Run Migrations

```bash
php artisan migrate
```

If the project includes seeders:

```bash
php artisan db:seed
```

---

## 7️⃣ Install Frontend Dependencies

```bash
npm install
```

---

## 8️⃣ Build Frontend Assets

```bash
npm run build
```

For development:

```bash
npm run dev
```

---

## 9️⃣ Start Laravel Server

```bash
php artisan serve
```

Open the application at:

```text
http://127.0.0.1:8000
```

---

# 🔐 Authentication & Authorization

The system supports role-based access control.

```text
👑 Admin
   │
   ├── Users
   ├── Roles
   ├── Permissions
   ├── Employees
   ├── Attendance
   ├── Leaves
   ├── Payroll
   └── Reports

👤 Employee
   │
   ├── Profile
   ├── Attendance
   ├── Leave
   ├── Timesheet
   └── Payslip
```

---

# 📊 Database Concept

Core relationships include:

```text
🏢 Department
      │
      └──────→ 👥 Employees
                    │
          ┌─────────┼─────────┐
          ▼         ▼         ▼
      ⏰ Attendance 🏖️ Leave 💰 Payroll
          │
          ▼
       ⏱️ Overtime
```

---

# ⚠️ Not Included

The following features are outside the scope of this version and require external services or hardware:

* ❌ Fingerprint attendance
* ❌ Face recognition attendance
* ❌ QR-code attendance
* ❌ SMS notifications
* ❌ Public REST API for mobile applications

---

# 🧪 Development

Useful Laravel commands:

```bash
php artisan migrate
```

```bash
php artisan migrate:fresh --seed
```

```bash
php artisan route:list
```

```bash
php artisan optimize:clear
```

```bash
php artisan storage:link
```

---

# 🛡️ Security Notes

For production deployment:

* 🔐 Keep `.env` private
* 🔑 Use strong database credentials
* 🚫 Never commit API keys or passwords
* 🛡️ Configure proper user permissions
* 🔒 Use HTTPS
* 🧹 Disable `APP_DEBUG` in production

Example:

```env
APP_ENV=production
APP_DEBUG=false
```

---

# 🎯 Project Goals

The main goals of this project are:

* 👥 Centralize employee information
* ⏰ Improve attendance management
* 🏖️ Simplify leave management
* 💰 Organize payroll information
* 📈 Track employee performance
* 🎓 Manage employee training
* 🎯 Improve recruitment workflow
* 📝 Maintain activity history
* 📊 Provide useful HR reports
* 🚀 Reduce manual HR processes

---

# 💡 Future Improvements

Possible future features:

* 📱 Mobile Application
* 🔌 REST API
* 🔔 Email Notifications
* 📲 SMS Notifications
* 🖐️ Fingerprint Integration
* 👁️ Face Recognition
* 📱 QR Attendance
* 🤖 AI-powered HR Assistant
* 📊 Advanced HR Analytics
* ☁️ Cloud Deployment

---

# 👨‍💻 Developer

**Phin Dalin**

🎓 Full-Stack Web Development
💻 Laravel / PHP
🌐 JavaScript / Tailwind CSS
🗄️ MySQL
🚀 Building portfolio-ready software projects

---

# 📄 License

This project is developed for **educational, learning, and portfolio purposes**.

---

# ⭐ Support

If you find this project useful, consider giving the repository a ⭐ on GitHub.

**Thank you for checking out the HR Management System! 🚀**
