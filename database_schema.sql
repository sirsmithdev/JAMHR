-- Database Schema for JamHR (MySQL / MariaDB)
-- Use this to initialize your Laravel migrations or database structure

-- 1. USERS & EMPLOYEES
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'manager', 'employee', 'kiosk') DEFAULT 'employee',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE employees (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    job_title VARCHAR(100),
    department VARCHAR(100),
    trn_number VARCHAR(20), -- Tax Registration Number
    nis_number VARCHAR(20), -- National Insurance Scheme
    start_date DATE,
    salary_annual DECIMAL(15, 2),
    hourly_rate DECIMAL(10, 2),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- 2. TIME & ATTENDANCE
CREATE TABLE time_entries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id BIGINT UNSIGNED NOT NULL,
    date DATE NOT NULL,
    clock_in TIMESTAMP NULL,
    clock_out TIMESTAMP NULL,
    total_hours DECIMAL(5, 2),
    status ENUM('on_time', 'late', 'absent', 'overtime') DEFAULT 'on_time',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
);

-- 3. LEAVE MANAGEMENT
CREATE TABLE leave_requests (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id BIGINT UNSIGNED NOT NULL,
    type ENUM('vacation', 'sick', 'personal', 'maternity', 'unpaid') NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    days_count DECIMAL(4, 1) NOT NULL,
    reason TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    approved_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id)
);

-- 4. PAYROLL (Jamaican Statutory Deductions)
CREATE TABLE payrolls (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id BIGINT UNSIGNED NOT NULL,
    period_start DATE NOT NULL,
    period_end DATE NOT NULL,
    gross_pay DECIMAL(15, 2) NOT NULL,
    
    -- Deductions
    nht_employee DECIMAL(10, 2) NOT NULL, -- 2%
    nht_employer DECIMAL(10, 2) NOT NULL, -- 3%
    nis_employee DECIMAL(10, 2) NOT NULL, -- 3%
    nis_employer DECIMAL(10, 2) NOT NULL, -- 3%
    ed_tax_employee DECIMAL(10, 2) NOT NULL, -- 2.25%
    ed_tax_employer DECIMAL(10, 2) NOT NULL, -- 3.5%
    heart_employer DECIMAL(10, 2) NOT NULL, -- 3% (Employer only)
    income_tax DECIMAL(10, 2) NOT NULL, -- 25% usually
    
    net_pay DECIMAL(15, 2) NOT NULL,
    status ENUM('draft', 'finalized', 'paid') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id)
);

-- 5. INCIDENTS
CREATE TABLE incidents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reporter_id BIGINT UNSIGNED,
    type VARCHAR(50) NOT NULL,
    severity ENUM('low', 'medium', 'high') NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(255),
    occurred_at DATETIME NOT NULL,
    status ENUM('open', 'investigating', 'resolved') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reporter_id) REFERENCES users(id)
);

-- 6. DOCUMENTS
CREATE TABLE documents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id BIGINT UNSIGNED,
    name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    category VARCHAR(50),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id)
);
