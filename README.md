# 📚 Group Study Finder System

A web-based application designed to help students find study partners, create study groups, and schedule collaborative study sessions for better academic performance.

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![HTML](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

---

## 🎯 Project Overview

**Group Study Finder** is a BCA 4th Semester Project developed at **Lumbini City College**, affiliated with **Tribhuvan University (TU), Nepal**. 

The system enables students to:
- 🔍 Find study partners based on subject, semester, and location
- ➕ Create and join study groups (online/offline)
- 📅 Schedule study sessions with date, time, and topic
- 👤 Manage profiles with semester and bio information
- ⭐ Browse group members
- 👨‍💼 Admin panel for system management

---

## ✨ Features

### 👤 User Features
- ✅ User registration & secure login (password hashing with bcrypt)
- ✅ Profile management with bio, semester, and contact info
- ✅ Create study groups with custom settings
- ✅ Search & filter groups by subject, keyword, and type
- ✅ Join/leave study groups
- ✅ Schedule study sessions (date, time, location, topic, duration)
- ✅ View upcoming and past sessions
- ✅ Browse group members

### 👨‍💼 Admin Features
- ✅ Admin dashboard with statistics
- ✅ Manage users (view/delete)
- ✅ Manage groups (open/close/delete)
- ✅ View system reports and analytics
- ✅ User & group statistics

---

## 🛠️ Technology Stack

| Category | Technology |
|----------|-----------|
| **Frontend** | HTML5, CSS3, JavaScript |
| **Backend** | PHP 8.x |
| **Database** | MySQL 8.x |
| **Web Server** | XAMPP (Apache) |
| **Code Editor** | VS Code |
| **Version Control** | Git & GitHub |
| **CASE Tools** | Draw.io, StarUML |

---

## 📂 Project Structure


---

## 🗄️ Database Schema

The system uses **6 main tables**:

1. **users** - Student & admin accounts
2. **subjects** - Available subjects by semester
3. **study_groups** - Study group information
4. **group_members** - Membership records (many-to-many)
5. **study_sessions** - Scheduled study sessions
6. **reviews** - Group reviews & ratings

### Entity Relationship:

---

## 🚀 Installation & Setup

### Prerequisites
- XAMPP (or any PHP/MySQL server)
- Web browser (Chrome, Firefox, Edge)

### Steps to Install

**1. Install XAMPP**
- Download from: https://www.apachefriends.org/
- Install with default settings

**2. Copy Project Files**
- Copy `group-study-finder` folder to `C:\xampp\htdocs\`

**3. Start XAMPP Services**
- Open XAMPP Control Panel
- Start **Apache** ✅
- Start **MySQL** ✅

**4. Create Database**
- Open browser → http://localhost/phpmyadmin
- Click **Import** (or **SQL** tab)
- Import the file `database.sql` from this project folder
- Confirm database `group_study_finder` and all 6 tables were created

**5. Access Application**
- Open: http://localhost/Group-study-finder-fixed/
  (use the folder name you placed under `htdocs`)

---

## 👤 Default Credentials

| Role    | Email             | Password |
|---------|-------------------|----------|
| Admin   | admin@gsf.com     | admin123 |
| Student | prasant@gmail.com | admin123 |

> ⚠️ **Important:** Change these passwords after first login. Delete or protect `reset.php` in production.

---

## 🧪 Testing

The project includes comprehensive testing:

### ✅ Unit Testing
- Registration validation
- Login authentication
- Group creation
- Group joining
- Session scheduling

### ✅ System Testing
- Complete user journey
- Multiple users in same group
- SQL injection prevention
- XSS attack prevention
- Cross-browser compatibility
- Mobile responsiveness

---

## 🔒 Security Features

- ✅ Password hashing using PHP's `password_hash()` (bcrypt)
- ✅ Password verification using `password_verify()`
- ✅ SQL injection prevention using `mysqli_real_escape_string()`
- ✅ XSS prevention using `htmlspecialchars()`
- ✅ Session-based authentication
- ✅ Role-based access control (Admin/Student)

---

## 📖 Documentation

Complete project report is available following **TU BCA Project I (CACS256)** guidelines with **IEEE referencing standard**.

### Report Structure:
1. **Chapter 1:** Introduction
2. **Chapter 2:** Background Study & Literature Review
3. **Chapter 3:** System Analysis & Design
4. **Chapter 4:** Implementation and Testing
5. **Chapter 5:** Conclusion and Future Recommendations

---

## 🎓 Academic Information

| Detail | Information |
|--------|------------|
| **Course** | BCA 4th Semester Project I (CACS256) |
| **Institution** | Lumbini City College |
| **University** | Tribhuvan University (TU) |
| **Faculty** | Humanities and Social Sciences |
| **Year** | 2026 |
| **Location** | Tilottama, Nepal |

---

## 👨‍💻 Developer

**Prasant Kafle**
- 🎓 BCA Student, Lumbini City College
- 📍 Tilottama, Nepal
- 📧 Email: prasantkafle07@gmail.com
- 🔗 GitHub: [@Prasant07S](https://github.com/Prasant07S)

---

## 🙏 Acknowledgements

- **Tribhuvan University**, Faculty of Humanities and Social Sciences
- **Lumbini City College**, Tilottama, Nepal
- Project Supervisor and Faculty Members
- Classmates and friends for their support
- Open-source community for tools and resources

---

## 🔮 Future Enhancements

The system can be enhanced with:
- 📱 Mobile application (Android/iOS)
- 💬 Real-time chat using WebSockets
- 📹 Video conferencing integration
- 🤖 AI-based study partner recommendations
- 🔔 Push notifications for sessions
- 📊 Advanced analytics dashboard
- 🌐 Multi-language support (Nepali/English)
- 📧 Email notifications
- 🎨 Profile picture upload

---

## 📄 License

This project is created for academic purposes as part of the BCA curriculum at Tribhuvan University. All rights reserved by the developer.

---

## 📞 Support

For any queries or issues:
- 📧 Email: prasantkafle07@gmail.com
- 🐛 Issues: [GitHub Issues](https://github.com/Prasant07S/group-study-finder/issues)

---

⭐ **If you find this project useful, please give it a star on GitHub!**

Made with ❤️ by Prasant Kafle | BCA 4th Semester | TU
