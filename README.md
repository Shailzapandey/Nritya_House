# Nritya House: Full-Stack Dance Academy Platform

A comprehensive, stateful web platform engineered to manage user profiles, class scheduling, and seamless data retrieval for an online dance academy. Built with a strict MVC architecture, focusing on relational data integrity, security, and responsive UI design.

### 🛠️ Tech Stack

- **Frontend:** HTML5, CSS3 (Custom Variables), Vanilla JavaScript
- **Backend:** PHP 8+ (Object-Oriented MVC)
- **Database:** MySQL (Relational, Foreign Key Enforced)
- **Server:** Apache (XAMPP environment)

### ⚙️ Core Architecture & Features

- **Custom MVC Framework:** Built a bespoke Model-View-Controller architecture featuring a centralized Front Controller (`index.php`) and dynamic routing.
- **Enterprise Security:** Implemented global CSRF token validation, MIME-type file verification for uploads, and PDO prepared statements to prevent SQL injection.
- **Relational Database Management:** Engineered normalized MySQL schemas to efficiently link user profiles, secure enrollments, course syllabuses, and time-aware event schedules.
- **Asynchronous Progress Tracking:** Integrated the YouTube IFrame API with asynchronous JavaScript (`fetch`) to silently log user course progress and calculate gamification streaks via transactional mass inserts without page reloads.

### 🚀 Local Installation & Setup

1. Clone the repository into your XAMPP `htdocs` directory: `git clone https://github.com/Shailzapandey/Nritya-House.git`
2. Start Apache and MySQL modules in the XAMPP Control Panel.
3. Configure the MySQL database:
   - Access `http://localhost/phpmyadmin`.
   - Create a database named `nritya_house`.
   - Import the SQL schema and seed data.
   - Update the database credentials in `config/database.php` if necessary.
4. Access the application via `http://localhost/Nritya_House`.
