# HR Management System

ប្រព័ន្ធគ្រប់គ្រងធនធានមនុស្ស (HR Management System) សាងសង់ដោយ **Laravel 12** + **Tailwind CSS 4** + **Vite**។

## Technologies
- Laravel 12, PHP ^8.2
- Vite, Tailwind CSS 4, Axios
- barryvdh/laravel-dompdf (PDF), maatwebsite/excel (Excel), php-flasher (Notifications)

## Modules
- User Management (Users, Roles, Permissions)
- Employee Management (Employees, Departments, Positions, Profile)
- Payroll (Staff Salary)
- Leave Management (Leaves, Leave Types, Holidays)
- **Attendance** (Check-in / Check-out, monthly matrix, personal history) — *newly added*
- Performance (Indicators, Appraisals)
- Training (Trainings, Trainers, Training Types)
- Recruitment (Jobs, Applications, Interview Questions)
- Finance (Expenses, Estimates)
- **Activity / Audit Log** (tracks create / update / delete on Employees, Leaves, Attendance) — *newly added*
- Company Settings

## What's new in this update
1. **Attendance module (real, database-backed)**
   - `attendances` table + `Attendance` model.
   - Employees can check in / check out from **Attendance (Employee)**; late arrivals and half-days are detected automatically.
   - Admins see a live monthly attendance matrix at **Attendance (Admin)** (previously this page only showed static demo data).
2. **Activity / Audit Log**
   - `audit_logs` table + `AuditLog` model, auto-recorded via the `LogsAudit` trait on the Employee, Leave and Attendance models.
   - Viewable by Admins at **User Controller → Activity Log**, with filters by module/action.
3. **Dashboard now shows real data** instead of hard-coded demo numbers:
   - Employees, Departments, Present Today, Pending Leave Requests.
   - "Employees by Department" and "Attendance – Last 7 Days" charts (Morris.js, no new dependency).
4. **Cleanup for distribution**
   - Removed `.git`, the real `.env` (keep `.env.example` only), and a duplicate README.

## Not included (needs external services/hardware, out of scope for this pass)
- Fingerprint / Face Recognition attendance, QR-code attendance
- SMS notifications
- Public REST API for a mobile app

## Setup
```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
npm install && npm run build
php artisan serve
```
