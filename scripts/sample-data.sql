-- Sample data for Maid Agency Management System
-- Run this after setting up the main database schema

USE maid_agency;

-- Insert sample helpers
INSERT INTO helpers (name, email, password_hash, nationality, date_of_birth, gender, contact_number, emergency_contact, experience_level, previous_experience, status, is_active, salary_min, salary_max, special_skills, dietary_restrictions, medical_conditions) VALUES 
('Maria Santos', 'maria.santos@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Filipino', '1995-03-15', 'Female', '+65 9123 4567', 'Sister - +63 912 345 6789', 'Intermediate (2-5 years)', '3 years with expatriate families in Singapore', 'Available', 1, 800, 1000, 'Western and Asian cooking, basic first aid', 'None', 'None'),
('Siti Rahman', 'siti.rahman@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Indonesian', '1991-07-22', 'Female', '+65 8234 5678', 'Mother - +62 821 234 5678', 'Experienced (5+ years)', '5 years with local and expatriate families', 'Deployed', 1, 900, 1200, 'Traditional Indonesian cooking, elderly care', 'Vegetarian', 'None'),
('Thida Win', 'thida.win@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Myanmar', '1998-11-08', 'Female', '+65 9345 6789', 'Brother - +95 912 345 678', 'Beginner (0-2 years)', '1 year with local family', 'Training', 1, 700, 900, 'Basic cleaning and pet care', 'None', 'None'),
('Priya Sharma', 'priya.sharma@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Indian', '1993-05-12', 'Female', '+65 8456 7890', 'Husband - +91 987 654 3210', 'Intermediate (2-5 years)', '4 years experience in childcare and cooking', 'Available', 1, 850, 1100, 'Indian cuisine, childcare, yoga', 'Vegetarian', 'None'),
('Lakshmi Perera', 'lakshmi.perera@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sri Lankan', '1989-09-30', 'Female', '+65 9567 8901', 'Sister - +94 771 234 567', 'Experienced (5+ years)', '6 years with various families', 'Reserved', 1, 950, 1300, 'Cooking, cleaning, elderly care', 'None', 'Mild hypertension (controlled)');

-- Insert helper skills relationships
INSERT INTO helper_skills (helper_id, skill_id) VALUES 
-- Maria Santos (ID: 1)
(1, 1), (1, 2), (1, 3), (1, 11), -- Cooking, Cleaning, Childcare, Basic First Aid
-- Siti Rahman (ID: 2)
(2, 1), (2, 2), (2, 4), (2, 7), -- Cooking, Cleaning, Elderly Care, Laundry
-- Thida Win (ID: 3)
(3, 2), (3, 5), (3, 7), -- Cleaning, Pet Care, Laundry
-- Priya Sharma (ID: 4)
(4, 1), (4, 3), (4, 2), -- Cooking, Childcare, Cleaning
-- Lakshmi Perera (ID: 5)
(5, 1), (5, 2), (5, 4), (5, 8); -- Cooking, Cleaning, Elderly Care, Ironing

-- Insert helper languages relationships
INSERT INTO helper_languages (helper_id, language_id) VALUES 
-- Maria Santos
(1, 1), (1, 7), -- English, Tagalog
-- Siti Rahman
(2, 1), (2, 8), (2, 4), -- English, Indonesian, Malay
-- Thida Win
(3, 1), (3, 9), -- English, Myanmar
-- Priya Sharma
(4, 1), (4, 6), -- English, Hindi
-- Lakshmi Perera
(5, 1), (5, 5); -- English, Tamil

-- Insert sample clients
INSERT INTO clients (name, email, password_hash, phone, address, family_size, children_ages, special_requirements, budget_min, budget_max, preferred_nationality, status, is_active) VALUES 
('Johnson Family', 'mjohnson@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+65 6123 4567', '123 Orchard Road, #12-34, Singapore 238901', 4, '8, 12', 'Need help with cooking and childcare, prefer someone who speaks English well', 900, 1200, 'Filipino', 'Active', 1),
('The Lims', 'david.lim@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+65 6234 5678', '456 Marina Bay Drive, #25-10, Singapore 018982', 2, '', 'Elderly couple needs help with daily activities and light cooking', 800, 1000, '', 'Active', 1),
('Sarah Chen', 'sarah.chen@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+65 6345 6789', '789 Sentosa Cove, #08-15, Singapore 098765', 3, '5', 'Single mother needs reliable help with childcare and household chores', 850, 1100, 'Indonesian', 'Active', 1),
('The Rajahs', 'raj.rajah@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+65 6456 7890', '321 Holland Village, #15-20, Singapore 275123', 5, '3, 7, 10', 'Large family needs experienced helper with cooking skills', 1000, 1400, 'Indian', 'Active', 1);

-- Insert sample staff
INSERT INTO staff (name, email, password_hash, username, role, department, position, date_of_birth, age, gender, contact_number, emergency_contact, address, is_active, email_verified, hire_date, salary) VALUES
('System Administrator', 'admin@maidagency.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'admin', 'Management', 'System Administrator', '1990-01-01', 34, 'Male', '+65 9000 0000', 'Office HR - +65 9111 1111', '123 Agency Way, Singapore', 1, 1, '2024-01-01', 5000.00),
('Jane Staff', 'staff@maidagency.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff', 'staff', 'Operations', 'Placement Coordinator', '1995-05-15', 29, 'Female', '+65 9222 2222', 'Family - +65 9333 3333', '456 Agency Road, Singapore', 1, 1, '2024-02-01', 3500.00);

-- Insert sample service requests
INSERT INTO requests (client_id, helper_id, request_type, urgency, start_date, requirements, status, notes, created_by) VALUES 
(1, NULL, 'New Hire', 'Medium', '2024-10-01', 'Need helper for cooking and childcare. Must speak English and be comfortable with children.', 'Pending', 'Client prefers Filipino nationality', 1),
(2, 2, 'New Hire', 'High', '2024-09-25', 'Elderly care assistance needed. Helper should be patient and experienced with seniors.', 'Matched', 'Siti Rahman assigned - good match for elderly care', 1),
(3, NULL, 'Replacement', 'Urgent', '2024-09-30', 'Previous helper had to return home. Need immediate replacement for childcare.', 'In Progress', 'Urgently looking for replacement', 1),
(4, NULL, 'New Hire', 'Low', '2024-11-01', 'Looking for experienced cook who can handle Indian cuisine for large family.', 'Pending', 'Client willing to wait for right match', 1);

-- Insert sample medical documents
INSERT INTO helper_medical_documents (helper_id, document_type, record_date, doctor_name, clinic_hospital, status, notes, expiry_date) VALUES 
(1, 'General Health Check', '2024-01-15', 'Dr. Smith', 'Singapore General Hospital', 'Approved', 'All tests normal, cleared for work', '2025-01-15'),
(1, 'Annual Physical', '2023-07-20', 'Dr. Johnson', 'Mount Elizabeth Hospital', 'Approved', 'Good health condition', '2024-07-20'),
(2, 'Medical Clearance', '2024-02-10', 'Dr. Lee', 'Raffles Hospital', 'Approved', 'Cleared for domestic work', '2025-02-10'),
(3, 'General Health Check', '2024-03-05', 'Dr. Tan', 'National University Hospital', 'Approved', 'Healthy, no issues found', '2025-03-05'),
(4, 'Annual Physical', '2024-01-20', 'Dr. Kumar', 'Singapore General Hospital', 'Approved', 'Excellent health condition', '2025-01-20'),
(5, 'Medical Clearance', '2024-02-28', 'Dr. Wong', 'Changi General Hospital', 'Pending Review', 'Hypertension under control with medication', '2025-02-28');

-- Insert sample training documents
INSERT INTO helper_training_documents (helper_id, document_type, course_name, training_provider, issue_date, expiry_date, status, notes) VALUES 
(1, 'Course Completion', 'Basic First Aid', 'Singapore Red Cross', '2024-01-10', '2026-01-10', 'Approved', 'Excellent performance in practical assessments'),
(1, 'Course Completion', 'Cooking Skills', 'Culinary Institute', '2023-12-15', NULL, 'Approved', 'Learned Western and Asian cooking techniques'),
(2, 'Skill Certification', 'Elderly Care Certification', 'Healthcare Academy', '2023-11-30', '2025-11-30', 'Approved', 'Specialized in elderly care and mobility assistance'),
(3, 'Course Completion', 'Basic Housekeeping', 'Domestic Skills Center', '2024-03-15', NULL, 'Approved', 'Foundation course in household management'),
(4, 'Skill Certification', 'Childcare Essentials', 'Early Childhood Center', '2023-08-30', '2025-08-30', 'Approved', 'Certified in child safety and development'),
(5, 'Course Completion', 'Advanced Cooking', 'Professional Chef Academy', '2023-06-30', NULL, 'Approved', 'Specialized in multi-cuisine cooking');


-- Insert sample contracts
INSERT INTO contracts (client_id, helper_id, contract_type, start_date, end_date, salary, terms, status, created_by) VALUES 
(2, 2, 'Full-time', '2024-02-15', '2026-02-14', 1100, 'Standard 2-year contract with elderly care duties. Includes medical insurance and annual leave.', 'Active', 1),
(1, 1, 'Full-time', '2024-10-01', '2026-09-30', 950, 'Standard contract for cooking and childcare. Trial period of 3 months.', 'Draft', 1);

-- Insert sample deployments
INSERT INTO deployments (contract_id, helper_id, client_id, deployment_date, expected_end_date, status, performance_rating, client_feedback, notes) VALUES 
(1, 2, 2, '2024-02-15', '2026-02-14', 'Active', 4.8, 'Excellent care for elderly parents. Very patient and reliable.', 'Helper has adapted well to family routine'),
(2, 1, 1, '2024-10-01', '2026-09-30', 'Planned', NULL, NULL, 'Deployment scheduled to start October 1st');

-- Insert some audit log entries
INSERT INTO audit_log (user_id, action, table_name, record_id, new_values, ip_address) VALUES 
(1, 'CREATE', 'helpers', 1, '{"name":"Maria Santos","nationality":"Filipino","status":"Available"}', '127.0.0.1'),
(1, 'CREATE', 'clients', 1, '{"name":"Johnson Family","status":"Active"}', '127.0.0.1'),
(1, 'UPDATE', 'helpers', 2, '{"status":"Deployed"}', '127.0.0.1'),
(1, 'CREATE', 'contracts', 1, '{"client_id":2,"helper_id":2,"status":"Active"}', '127.0.0.1');
