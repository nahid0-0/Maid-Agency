-- Maid Agency Management System Database Setup
-- Run this script in MySQL Workbench or phpMyAdmin

-- Create database
CREATE DATABASE IF NOT EXISTS maid_agency 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE maid_agency;

-- Users table for authentication
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'manager', 'staff') DEFAULT 'staff',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Helpers table
CREATE TABLE helpers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    nationality VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    age INT,
    gender ENUM('Male', 'Female') NOT NULL,
    contact_number VARCHAR(20),
    emergency_contact VARCHAR(200),
    experience_level ENUM('Beginner (0-2 years)', 'Intermediate (2-5 years)', 'Experienced (5+ years)') NOT NULL,
    previous_experience TEXT,
    status ENUM('Available', 'Reserved', 'Deployed', 'Training', 'Inactive') DEFAULT 'Available',
    salary_min DECIMAL(8,2),
    salary_max DECIMAL(8,2),
    special_skills TEXT,
    dietary_restrictions TEXT,
    medical_conditions TEXT,
    photo_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Helper skills (many-to-many relationship)
CREATE TABLE skills (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE helper_skills (
    helper_id INT,
    skill_id INT,
    PRIMARY KEY (helper_id, skill_id),
    FOREIGN KEY (helper_id) REFERENCES helpers(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
);

-- Helper languages (many-to-many relationship)
CREATE TABLE languages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE helper_languages (
    helper_id INT,
    language_id INT,
    PRIMARY KEY (helper_id, language_id),
    FOREIGN KEY (helper_id) REFERENCES helpers(id) ON DELETE CASCADE,
    FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE
);

-- Clients table
CREATE TABLE clients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    family_size INT,
    children_ages VARCHAR(100),
    special_requirements TEXT,
    budget_min DECIMAL(8,2),
    budget_max DECIMAL(8,2),
    preferred_nationality VARCHAR(50),
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Service requests table
CREATE TABLE requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    helper_id INT NULL,
    request_type ENUM('New Hire', 'Replacement', 'Transfer') NOT NULL,
    urgency ENUM('Low', 'Medium', 'High', 'Urgent') DEFAULT 'Medium',
    start_date DATE,
    requirements TEXT,
    status ENUM('Pending', 'In Progress', 'Matched', 'Completed', 'Cancelled') DEFAULT 'Pending',
    notes TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (helper_id) REFERENCES helpers(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Helper identity documents table (Passport & ID)
CREATE TABLE helper_identity_documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    helper_id INT NOT NULL,
    document_type ENUM('Passport', 'National ID', 'Work Permit', 'Visa', 'Other') NOT NULL,
    document_number VARCHAR(100),
    issuing_country VARCHAR(50),
    issue_date DATE,
    expiry_date DATE,
    s3_url VARCHAR(500) NOT NULL,
    s3_key VARCHAR(255) NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    file_size INT,
    mime_type VARCHAR(100),
    status ENUM('Pending Review', 'Approved', 'Rejected', 'Expired') DEFAULT 'Pending Review',
    reviewed_by INT,
    reviewed_at TIMESTAMP NULL,
    review_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (helper_id) REFERENCES helpers(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Helper medical documents table
CREATE TABLE helper_medical_documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    helper_id INT NOT NULL,
    document_type ENUM('General Health Check', 'Annual Physical', 'Vaccination Record', 'Medical Clearance', 'Blood Test', 'Other') NOT NULL,
    record_date DATE NOT NULL,
    doctor_name VARCHAR(100),
    clinic_hospital VARCHAR(100),
    s3_url VARCHAR(500) NOT NULL,
    s3_key VARCHAR(255) NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    file_size INT,
    mime_type VARCHAR(100),
    status ENUM('Pending Review', 'Approved', 'Rejected', 'Expired') DEFAULT 'Pending Review',
    expiry_date DATE,
    reviewed_by INT,
    reviewed_at TIMESTAMP NULL,
    review_notes TEXT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (helper_id) REFERENCES helpers(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Helper training documents table
CREATE TABLE helper_training_documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    helper_id INT NOT NULL,
    document_type ENUM('Training Certificate', 'Skill Certification', 'Course Completion', 'Professional License', 'Other') NOT NULL,
    course_name VARCHAR(100) NOT NULL,
    training_provider VARCHAR(100),
    certificate_number VARCHAR(50),
    issue_date DATE,
    expiry_date DATE,
    s3_url VARCHAR(500) NOT NULL,
    s3_key VARCHAR(255) NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    file_size INT,
    mime_type VARCHAR(100),
    status ENUM('Pending Review', 'Approved', 'Rejected', 'Expired') DEFAULT 'Pending Review',
    reviewed_by INT,
    reviewed_at TIMESTAMP NULL,
    review_notes TEXT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (helper_id) REFERENCES helpers(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Contracts table
CREATE TABLE contracts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    helper_id INT NOT NULL,
    contract_type ENUM('Full-time', 'Part-time', 'Temporary', 'Trial') NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE,
    salary DECIMAL(8,2) NOT NULL,
    terms TEXT,
    status ENUM('Draft', 'Active', 'Completed', 'Terminated', 'Cancelled') DEFAULT 'Draft',
    document_path VARCHAR(255),
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (helper_id) REFERENCES helpers(id),
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Deployments table
CREATE TABLE deployments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    contract_id INT NOT NULL,
    helper_id INT NOT NULL,
    client_id INT NOT NULL,
    deployment_date DATE NOT NULL,
    expected_end_date DATE,
    actual_end_date DATE,
    status ENUM('Planned', 'Active', 'Completed', 'Terminated', 'On Hold') DEFAULT 'Planned',
    performance_rating DECIMAL(2,1) CHECK (performance_rating >= 1.0 AND performance_rating <= 5.0),
    client_feedback TEXT,
    helper_feedback TEXT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (contract_id) REFERENCES contracts(id),
    FOREIGN KEY (helper_id) REFERENCES helpers(id),
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

-- Audit log table
CREATE TABLE audit_log (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(50) NOT NULL,
    record_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert default skills
INSERT INTO skills (name) VALUES 
('Cooking'), ('Cleaning'), ('Childcare'), ('Elderly Care'), 
('Pet Care'), ('Gardening'), ('Laundry'), ('Ironing'),
('Shopping'), ('Driving'), ('Basic First Aid');

-- Insert default languages
INSERT INTO languages (name) VALUES 
('English'), ('Mandarin'), ('Cantonese'), ('Malay'), 
('Tamil'), ('Hindi'), ('Tagalog'), ('Indonesian'), 
('Myanmar'), ('Thai'), ('Vietnamese');

-- Insert default admin user (password: admin123)
INSERT INTO users (username, email, password_hash, full_name, role) VALUES 
('admin', 'admin@maidagency.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin');

-- Create indexes for better performance
CREATE INDEX idx_helpers_status ON helpers(status);
CREATE INDEX idx_helpers_nationality ON helpers(nationality);
CREATE INDEX idx_requests_status ON requests(status);
CREATE INDEX idx_requests_client ON requests(client_id);
CREATE INDEX idx_contracts_status ON contracts(status);
CREATE INDEX idx_deployments_status ON deployments(status);
CREATE INDEX idx_audit_user ON audit_log(user_id);
CREATE INDEX idx_audit_table ON audit_log(table_name, record_id);

-- Document tables indexes
CREATE INDEX idx_identity_docs_helper ON helper_identity_documents(helper_id);
CREATE INDEX idx_identity_docs_status ON helper_identity_documents(status);
CREATE INDEX idx_identity_docs_type ON helper_identity_documents(document_type);
CREATE INDEX idx_medical_docs_helper ON helper_medical_documents(helper_id);
CREATE INDEX idx_medical_docs_status ON helper_medical_documents(status);
CREATE INDEX idx_medical_docs_type ON helper_medical_documents(document_type);
CREATE INDEX idx_training_docs_helper ON helper_training_documents(helper_id);
CREATE INDEX idx_training_docs_status ON helper_training_documents(status);
CREATE INDEX idx_training_docs_type ON helper_training_documents(document_type);
