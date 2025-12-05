# JamHR - Technical Handoff Specification

## Project Overview
JamHR is a Human Resource Management System (HRMS) tailored for Jamaican labor laws and statutory requirements. This document serves as the technical blueprint for building the backend in **PHP / Laravel**.

## Tech Stack Recommendations
- **Backend Framework:** Laravel 10/11 (PHP 8.2+)
- **Database:** MySQL 8.0 or MariaDB 10.6
- **Frontend:** Blade Templates with Tailwind CSS (or Vue.js/React via Inertia.js)
- **Authentication:** Laravel Breeze or Jetstream (Role-based access)

## Database Schema Blueprint
The following entities are required to support the frontend prototype features.

### 1. Users & Roles
- **Table:** `users`
- **Columns:** `id`, `name`, `email`, `password`, `role` (admin, manager, employee, kiosk), `is_active`, `last_login_at`
- **Notes:** Use Laravel's built-in Auth.

### 2. Employees
- **Table:** `employees`
- **Columns:** `id`, `user_id` (FK), `first_name`, `last_name`, `job_title`, `department`, `start_date`, `trn_number`, `nis_number`, `hourly_rate`, `salary_annual`

### 3. Payroll & Taxes (Jamaican Context)
- **Table:** `payrolls`
- **Columns:** `id`, `employee_id`, `period_start`, `period_end`, `gross_pay`, `nht_amount` (2%), `nis_amount` (3%), `ed_tax_amount` (2.25%), `heart_contribution` (Employer 3%), `net_pay`, `status` (draft, processed)
- **Logic:**
  - NHT: 2% Employee, 3% Employer
  - NIS: 3% Employee, 3% Employer (Capped at $5M JMD/yr)
  - Ed Tax: 2.25% Employee, 3.5% Employer

### 4. Time & Attendance
- **Table:** `time_entries`
- **Columns:** `id`, `employee_id`, `date`, `clock_in`, `clock_out`, `break_start`, `break_end`, `total_hours`, `status` (on_time, late, absent)

### 5. Leave Management
- **Table:** `leave_requests`
- **Columns:** `id`, `employee_id`, `type` (vacation, sick, personal), `start_date`, `end_date`, `days_count`, `reason`, `status` (pending, approved, rejected), `approved_by` (FK)

### 6. Incidents
- **Table:** `incidents`
- **Columns:** `id`, `reporter_id` (FK), `type`, `severity`, `description`, `location`, `witnesses`, `status` (open, investigating, resolved), `occurred_at`

### 7. Performance Reviews
- **Table:** `appraisals`
- **Columns:** `id`, `employee_id`, `reviewer_id`, `cycle` (Q1, Q2, Annual), `score_competency` (JSON), `score_goals` (JSON), `rating_overall`, `manager_comments`, `status`

## Feature Roadmap for Backend Build

### Phase 1: Core Foundation
- [ ] Set up Laravel project
- [ ] Configure MySQL database
- [ ] Implement Authentication (Login/Logout) with Roles
- [ ] Build "Employees" CRUD (Create, Read, Update, Delete)

### Phase 2: Time & Payroll
- [ ] Create Kiosk API endpoint for Clock In/Out
- [ ] Build Payroll Calculator service (implementing Jamaican tax formulas)
- [ ] Generate Payslip PDFs (using `dompdf` or similar)

### Phase 3: Compliance & Reporting
- [ ] Implement Leave Request workflow (Email notifications to managers)
- [ ] Build Incident Reporting form
- [ ] Create SO1 and P24 Tax Report generators

## Frontend Assets
The design in this prototype uses **Tailwind CSS**.
- **Colors:**
  - Primary Blue: `hsl(216, 90%, 35%)` (#09429A)
  - Secondary Gold: `hsl(45, 90%, 55%)` (#F2C925)
  - Accent Green: `hsl(150, 80%, 35%)` (#12A156)
- **Fonts:** `Plus Jakarta Sans` (UI) and `Merriweather` (Headers)

You can copy the HTML structure from the `client/src/pages` components directly into your Laravel Blade files, replacing React state with Blade syntax (`{{ $variable }}`).
