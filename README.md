Sure! Below is a **README.md** template for your Task Management System. You can use this for your project repository on GitHub.

---

# Task Management System

## Description

The Task Management System is a simple web application designed to help users manage and organize their tasks efficiently. Users can create, update, assign, and track tasks, set priorities, and manage their accounts. The application is built using **PHP**, **HTML**, **CSS**, and **MySQL**.

This project is developed as part of my university final year project to demonstrate my ability to create a functional and user-friendly web application.

## Features

- **User Authentication:**
  - Secure user registration and login system.
  - Password reset functionality (without email verification for now).
  
- **Task Management:**
  - Users can create, view, edit, and delete tasks.
  - Tasks can have a status (e.g., Pending, In Progress, Completed).
  - Tasks can be prioritized (High, Medium, Low).
  - Tasks can be assigned to different users.

- **Responsive Design:**
  - The system is mobile-friendly and adapts to different screen sizes.

## Technologies Used

- **Frontend:**
  - **HTML:** Markup language for creating web page structure.
  - **CSS:** For styling and making the website responsive.

- **Backend:**
  - **PHP:** For server-side logic, including user authentication, task management, and password reset.
  
- **Database:**
  - **MySQL:** For managing user data, task data, and password reset information.

## Setup and Installation

### Prerequisites

- **PHP** (version 7.4 or above)
- **MySQL**
- A **web server** (e.g., Apache) or local development environment like **XAMPP** or **MAMP**

### Installation Steps

1. **Clone the repository:**
   ```bash
   git clone https://github.com/your-username/task-management-system.git
   ```

2. **Set up the database:**
   - Create a MySQL database named `task_manager`.
   - Import the SQL schema for creating necessary tables:
     ```sql
     CREATE TABLE users (
         id INT AUTO_INCREMENT PRIMARY KEY,
         email VARCHAR(255) NOT NULL UNIQUE,
         password VARCHAR(255) NOT NULL,
         role ENUM('admin', 'user') DEFAULT 'user'
     );

     CREATE TABLE tasks (
         id INT AUTO_INCREMENT PRIMARY KEY,
         title VARCHAR(255) NOT NULL,
         description TEXT,
         status ENUM('Pending', 'In Progress', 'Completed') DEFAULT 'Pending',
         priority ENUM('High', 'Medium', 'Low') DEFAULT 'Medium',
         user_id INT,
         deadline DATE,
         FOREIGN KEY (user_id) REFERENCES users(id)
     );
     ```

3. **Configure the database connection:**
   - Open the `db_config.php` file (located in the project root folder) and configure the database credentials:
     ```php
     define('DB_SERVER', 'localhost');
     define('DB_USERNAME', 'root');
     define('DB_PASSWORD', '');
     define('DB_NAME', 'task_manager');
     ```

4. **Start the web server:**
   - If you're using XAMPP, open the XAMPP control panel and start the Apache and MySQL services.
   - Navigate to the project directory in your browser (e.g., `http://localhost/task-management-system/`).

## Usage

- **User Registration & Login:**
  - New users can register an account using their email and password.
  - Registered users can log in using their credentials.

- **Creating and Managing Tasks:**
  - After logging in, users can create new tasks by entering task details such as title, description, priority, and deadline.
  - Users can edit, delete, and update the status of their tasks.
  - Tasks can be assigned to different users based on roles.

- **Password Reset:**
  - If users forget their password, they can reset it by entering their email address (this feature currently doesn’t involve email verification).

## Database Structure

### `users` Table:
- `id`: Unique identifier for each user.
- `email`: The email address of the user.
- `password`: The hashed password for the user.
- `role`: Defines the user role (admin or regular user).

### `tasks` Table:
- `id`: Unique identifier for each task.
- `title`: The title or name of the task.
- `description`: A detailed description of the task.
- `status`: Current status of the task (Pending, In Progress, Completed).
- `priority`: Priority level of the task (High, Medium, Low).
- `user_id`: The ID of the user assigned to the task.
- `deadline`: The deadline for the task.

## Screenshots

Here you can add screenshots or images that demonstrate how the system looks. For example:

![Login Screen](path/to/login_screenshot.png)

---

## Future Improvements

- **Email-based Forgot Password**: Adding email functionality to send password reset links.
- **Notifications**: Implementing task reminder notifications or email alerts for upcoming tasks.
- **Advanced Reporting**: Adding analytics to track task completion rates, user productivity, etc.
- **User Roles**: Adding more granular user roles and permissions, such as project manager or team leader.

---

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

---

## Acknowledgements

- **[Supervisor's Name]** for their support and guidance throughout the project.
- **[Any other acknowledgements or references]**

---

Let me know if you need any modifications or additions to the README!
