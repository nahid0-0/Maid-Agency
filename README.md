# 🏠 CleanCare Maid Agency Management System

<div align="center">

![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.4-8892BF?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![AWS S3](https://img.shields.io/badge/AWS%20S3-Enabled-FF9900?style=for-the-badge&logo=amazon-aws)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**A comprehensive web-based management system for domestic helper agencies**

[Features](#-features) • [Installation](#-installation) • [Usage](#-usage) • [API](#-api-routes) • [Contributing](#-contributing)

</div>

---

## 📋 Table of Contents

- [Overview](#-overview)
- [Features](#-features)
- [System Architecture](#-system-architecture)
- [Prerequisites](#-prerequisites)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Usage](#-usage)
- [User Roles](#-user-roles)
- [API Routes](#-api-routes)
- [Security Features](#-security-features)
- [Database Schema](#-database-schema)
- [Screenshots](#-screenshots)
- [Troubleshooting](#-troubleshooting)
- [Contributing](#-contributing)
- [License](#-license)

---

## 🌟 Overview

**CleanCare Maid Agency Management System** is a full-featured web application designed to streamline the operations of domestic helper (maid) agencies. The system manages the entire lifecycle of helper recruitment, client requests, document management, medical records, training certifications, and deployments.

Built with modern PHP and integrated with AWS S3 for secure document storage, this system provides separate dashboards for three user types: **Clients**, **Helpers (Maids)**, and **Staff (Agency Administrators)**.

---

## ✨ Features

### 👥 For Clients
- 🔐 **Secure Registration & Login** - Client account management with profile pictures
- 🔍 **Browse Available Helpers** - Search and filter domestic helpers by skills and availability
- 📝 **Submit Requests** - Request helpers with specific requirements
- 📊 **Track Request Status** - Monitor request progress through workflow stages
- 👤 **Profile Management** - Update personal information and preferences

### 👩‍💼 For Helpers (Maids)
- 📋 **Profile Management** - Comprehensive profile with skills and experience
- 📄 **Document Upload** - Submit identity, medical, and training documents
- 📑 **Document Status Tracking** - View approval status of submitted documents
- 💼 **Employment History** - Track deployment and work history
- 🔔 **Notifications** - Receive updates on document reviews and deployments

### 🏢 For Staff (Agency Admins)
- 📊 **Dashboard Overview** - Comprehensive view of agency operations
- 👥 **Helper Management** - Create, edit, and manage helper profiles
- 📝 **Request Workflow** - Process client requests through multiple stages
- ✅ **Document Review** - Approve or reject helper documents
- 🏥 **Medical Records Management** - Track medical examinations and health records
- 🎓 **Training Management** - Manage training certifications and courses
- 🚀 **Deployment Management** - Assign helpers to clients and track deployments
- 📈 **Analytics & Reporting** - Generate reports on operations

### 🔧 Technical Features
- ☁️ **AWS S3 Integration** - Secure cloud storage for documents and images
- 🔒 **Advanced Security** - Input sanitization, CSRF protection, SQL injection prevention
- 📱 **Responsive Design** - Mobile-friendly interface with Tailwind CSS
- 🎨 **Modern UI/UX** - Clean and intuitive user interface
- 🔄 **MVC Architecture** - Well-organized Model-View-Controller pattern
- 🗄️ **Database Abstraction** - PDO-based database layer with prepared statements
- 🔐 **Session Management** - Secure session handling
- 📝 **Comprehensive Logging** - Error and activity logging

---

## 🏗️ System Architecture

```
┌─────────────────────────────────────────────────────────┐
│                    Client Browser                        │
└──────────────────────┬──────────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────────┐
│                  Front Controller                        │
│                    (index.php)                           │
└──────────────────────┬──────────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────────┐
│                      Router                              │
│              (Core/Router.php)                           │
└──────────────────────┬──────────────────────────────────┘
                       │
        ┌──────────────┼──────────────┐
        │              │              │
        ▼              ▼              ▼
┌─────────────┐  ┌──────────┐  ┌─────────────┐
│   Client    │  │  Helper  │  │    Staff    │
│ Controller  │  │Controller│  │ Controller  │
└──────┬──────┘  └─────┬────┘  └──────┬──────┘
       │               │              │
       └───────────────┼──────────────┘
                       │
        ┌──────────────┼──────────────┐
        │              │              │
        ▼              ▼              ▼
┌─────────────┐  ┌──────────┐  ┌─────────────┐
│   Models    │  │   Core   │  │    Views    │
│  (Data)     │  │(Services)│  │  (Templates)│
└──────┬──────┘  └─────┬────┘  └─────────────┘
       │               │
       └───────┬───────┘
               │
        ┌──────┴───────┐
        │              │
        ▼              ▼
┌─────────────┐  ┌─────────────┐
│   MySQL     │  │   AWS S3    │
│  Database   │  │   Storage   │
└─────────────┘  └─────────────┘
```

---

## 📦 Prerequisites

Before installing the CleanCare Maid Agency Management System, ensure you have:

- **PHP** >= 7.4 (with extensions: PDO, pdo_mysql, mbstring, json)
- **MySQL** >= 5.7 or **MariaDB** >= 10.2
- **Composer** (for dependency management)
- **XAMPP/WAMP/MAMP** or similar local development environment
- **AWS Account** (for S3 storage) - Optional but recommended
- **Git** (for version control)

---

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/jabin11/Maid-Agency.git
cd Maid-Agency
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

Create a `.env` file in the root directory:

```bash
cp .env.production .env
```

Edit `.env` with your configuration:

```ini
# Database Configuration
DB_HOST=localhost
DB_NAME=maid_agency_db
DB_USER=root
DB_PASSWORD=your_password

# AWS S3 Configuration (Optional)
AWS_ACCESS_KEY_ID=your_aws_access_key
AWS_SECRET_ACCESS_KEY=your_aws_secret_key
AWS_REGION=us-east-1
S3_BUCKET_NAME=your-bucket-name

# Application Configuration
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost/Maid-Agency
```

### 4. Database Setup

Create the database and import the schema:

```bash
mysql -u root -p
```

```sql
CREATE DATABASE maid_agency_db;
USE maid_agency_db;
SOURCE database/schema.sql;
```

Or use phpMyAdmin to import the SQL file.

### 5. Set Permissions

Ensure proper permissions for uploads and logs directories:

```bash
chmod -R 755 uploads/
chmod -R 755 logs/
```

### 6. Start the Server

If using XAMPP, place the project in `htdocs` directory and access via:

```
http://localhost/Maid-Agency
```

Or use PHP's built-in server:

```bash
php -S localhost:8000
```

---

## ⚙️ Configuration

### AWS S3 Setup

1. Create an S3 bucket in your AWS account
2. Set up IAM user with S3 access permissions
3. Configure CORS policy for the bucket
4. Add credentials to `.env` file

### Database Configuration

The system uses the following main tables:

- `users` - All user accounts (clients, helpers, staff)
- `helpers` - Helper profile information
- `clients` - Client profile information
- `staff` - Staff profile information
- `requests` - Client requests for helpers
- `deployments` - Helper-client assignments
- `medical_records` - Helper medical information
- `training_records` - Helper training certifications
- `helper_identity_documents` - Identity documents
- `helper_medical_documents` - Medical documents
- `helper_training_documents` - Training certificates

---

## 📖 Usage

### First Time Setup

1. **Create Staff Account**: Register first staff member at `/staff/signup`
2. **Staff Login**: Access staff dashboard at `/staff/login`
3. **Add Helpers**: Create helper profiles with complete information
4. **Review Documents**: Approve uploaded documents from helpers
5. **Process Requests**: Handle client requests through workflow stages

### Client Workflow

1. Register at `/client/signup`
2. Browse available helpers at `/client/browse-helpers`
3. Submit a request specifying requirements
4. Track request status in dashboard
5. Receive helper assignment notification

### Helper Workflow

1. Register at `/maid/signup` (or created by staff)
2. Complete profile information
3. Upload required documents (ID, medical, training)
4. Wait for document approval
5. Get notified of deployment assignments

### Staff Workflow

1. Login to staff dashboard
2. Manage helper profiles and documents
3. Review and approve submitted documents
4. Process client requests through stages:
   - New Request
   - Under Review
   - Helper Assigned
   - Deployment Scheduled
   - Active Deployment
   - Completed
5. Track deployments and performance

---

## 👥 User Roles

| Role | Access Level | Key Permissions |
|------|-------------|-----------------|
| **Client** | Limited | Browse helpers, submit requests, view own profile |
| **Helper** | Limited | Manage profile, upload documents, view employment |
| **Staff** | Admin | Full system access, manage all entities |

---

## 🔗 API Routes

### Authentication Routes

```
GET  /client/login          - Client login page
POST /client/authenticate   - Client authentication
GET  /client/signup         - Client registration page
POST /client/register       - Client registration

GET  /maid/login           - Helper login page
POST /maid/authenticate    - Helper authentication
GET  /maid/signup          - Helper registration page
POST /maid/register        - Helper registration

GET  /staff/login          - Staff login page
POST /staff/authenticate   - Staff authentication
GET  /staff/signup         - Staff registration page
POST /staff/register       - Staff registration
```

### Client Routes

```
GET  /client/dashboard              - Client dashboard
GET  /client/browse-helpers         - Browse available helpers
GET  /client/profile                - View/edit profile
POST /client/profile/update         - Update profile
POST /client/profile/upload-picture - Upload profile picture
GET  /client/my-requests           - View submitted requests
POST /client/submit-request         - Submit new request
```

### Helper Routes

```
GET  /maid/dashboard        - Helper dashboard
GET  /maid/profile          - View/edit profile
POST /maid/profile/update   - Update profile
GET  /maid/documents        - Document management
POST /maid/upload-document  - Upload documents
GET  /maid/employment       - Employment history
```

### Staff Routes

```
GET  /staff/dashboard              - Staff dashboard
GET  /staff/helper-management      - Manage helpers
GET  /staff/create-helper          - Create new helper
POST /staff/helper/create          - Save new helper
GET  /staff/edit-helper/{id}       - Edit helper
POST /staff/helper/update          - Update helper
GET  /staff/document-review        - Review documents
POST /staff/document/approve       - Approve document
POST /staff/document/reject        - Reject document
GET  /staff/medical-training       - Medical & training records
GET  /staff/request-workflow       - Client request workflow
POST /staff/request/update-status  - Update request status
GET  /staff/deployments            - Deployment management
POST /staff/deployment/create      - Create deployment
```

---

## 🔒 Security Features

- ✅ **Input Sanitization** - All user inputs are sanitized
- ✅ **Prepared Statements** - SQL injection prevention via PDO
- ✅ **CSRF Protection** - Token-based CSRF protection
- ✅ **Password Hashing** - Bcrypt password hashing
- ✅ **Session Security** - Secure session configuration
- ✅ **XSS Protection** - Output escaping and CSP headers
- ✅ **File Upload Validation** - File type and size validation
- ✅ **Rate Limiting** - Protection against brute force attacks
- ✅ **Security Headers** - Comprehensive HTTP security headers

---

## 💾 Database Schema

### Key Tables

**Users Table**
```sql
- id (Primary Key)
- username (Unique)
- email (Unique)
- password_hash
- user_type (client/helper/staff)
- created_at
- updated_at
```

**Helpers Table**
```sql
- id (Primary Key)
- user_id (Foreign Key)
- full_name
- nationality
- date_of_birth
- skills
- experience_years
- availability_status
- profile_picture
```

**Requests Table**
```sql
- id (Primary Key)
- client_id (Foreign Key)
- helper_type
- requirements
- status
- created_at
- assigned_helper_id (Foreign Key)
```

---

## 📸 Screenshots

### Client Dashboard
![Client Dashboard](docs/screenshots/client-dashboard.png)

### Staff Helper Management
![Staff Dashboard](docs/screenshots/staff-helpers.png)

### Document Review
![Document Review](docs/screenshots/document-review.png)

---

## 🐛 Troubleshooting

### Common Issues

**Issue: Database Connection Failed**
```bash
Solution: Check .env file for correct database credentials
```

**Issue: AWS S3 Upload Failed**
```bash
Solution: Verify AWS credentials and bucket permissions
```

**Issue: Session Not Working**
```bash
Solution: Check session.save_path in php.ini
```

**Issue: File Upload Error**
```bash
Solution: Check upload_max_filesize and post_max_size in php.ini
```

### Debug Mode

Enable debug mode in `.env`:
```ini
APP_DEBUG=true
```

Check error logs:
```bash
tail -f logs/error.log
```

---

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Coding Standards

- Follow PSR-12 coding standards
- Write clear, descriptive commit messages
- Add comments for complex logic
- Update documentation for new features

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👨‍💻 Authors

- **Development Team** - *Initial work* - [jabin11](https://github.com/jabin11)

---

## 🙏 Acknowledgments

- AWS SDK for PHP
- Tailwind CSS for styling
- PHP community for excellent documentation
- All contributors who help improve this project

---

## 📞 Support

For support, email support@cleancare-agency.com or open an issue on GitHub.

---

## 🗺️ Roadmap

- [ ] Mobile app integration
- [ ] Multi-language support
- [ ] Advanced analytics dashboard
- [ ] SMS/Email notifications
- [ ] Payment gateway integration
- [ ] Real-time chat system
- [ ] Calendar integration for scheduling
- [ ] Performance reviews and ratings

---

<div align="center">

**Made with ❤️ by the CleanCare Team**

⭐ Star us on GitHub — it helps!

[Report Bug](https://github.com/jabin11/Maid-Agency/issues) • [Request Feature](https://github.com/jabin11/Maid-Agency/issues)

</div>
