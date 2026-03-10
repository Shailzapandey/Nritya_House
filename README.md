# Nritya House: Full-Stack Dance Academy Platform

A comprehensive, stateful web platform engineered to manage user profiles, class scheduling, and seamless data retrieval for an online dance academy. Built with a focus on relational data integrity and responsive UI design.

### 🛠️ Tech Stack
* **Frontend:** HTML5, CSS3, Tailwind CSS
* **Backend:** Java (Servlets/JSP)
* **Database:** MySQL
* **Server:** Apache Tomcat (or whatever you used)

### ⚙️ Core Architecture & Features
* **Relational Database Management:** Engineered normalised MySQL schemas to efficiently link user profiles, instructor availability, and class schedules.
* **Stateful User Sessions:** Handled secure data routing and session management between the Java backend and the frontend UI.
* **Responsive Interface:** Implemented a mobile-first, responsive frontend using Tailwind CSS for zero-latency DOM rendering.

### 🚀 Local Installation & Setup
1. Clone the repository: `git clone https://github.com/Shailzapandey/Nritya-House.git`
2. Configure the MySQL database:
   * Import the `database_schema.sql` file provided in the `/db` directory.
   * Update the database credentials in `src/main/resources/db.properties`.
3. Deploy the application to your local Tomcat server.
4. Access via `http://localhost:8080/nritya-house`.
