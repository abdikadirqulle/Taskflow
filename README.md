
# Task Management System

## Description

The Task Management System is a simple web application designed to help users manage and organize their tasks efficiently. Users can create, update, assign, and track tasks, set priorities, and manage their accounts. The application is built using **PHP**, **HTML**, **CSS**, and **MySQL**.

This project is developed as part of my university semester 7 project to demonstrate my ability to create a functional and user-friendly web application.

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

## Usage

- **User Registration & Login:**
  - New users can register an account using their email and password.
  - Registered users can log in using their credentials.

- **Creating and Managing Tasks:**
  - After logging in, users can create new tasks by entering task details such as title, description, priority, and deadline.
  - Users can edit, delete, and update the status of their tasks.
    
- **Password Reset:**
  - If users forget their password, they can reset it by entering their email address (this feature currently doesn’t involve email verification).

## Technologies Used

- **Frontend:**
  - **HTML:** Markup language for creating web page structure.
  - **CSS:** For styling and making the website responsive.

- **Backend:**
  - **PHP:** For server-side logic, including user authentication, task management, and password reset.
  
- **Database:**
  - **MySQL:** For managing user data, task data, and password reset information.


## Methods Used

### Agile Methodology:
- The project follows **Agile** development methodology to ensure flexibility and adaptability during the development process. The project was broken down into **sprints** for incremental and iterative progress, with continuous feedback and adjustments based on evolving requirements.

### Timeline:
- **Project Duration:** 2 months (November and December 2024)
- The timeline was divided into the following phases:
  - **November:** Initial planning, design, and setup of database structure. Completion of core features (user registration, task creation, etc.).
  - **December:** Implementation of task management features, testing, bug fixing, and final optimizations.



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

---

## the APIs for the **Task Management System**:

### 1. **User Authentication APIs**
   - **POST `/register`**: Register a new user.
   - **POST `/login`**: Log in the user.
   - **POST `/logout`**: Log out the user.
   - **POST `/reset-password`**: Handle password reset (without email).

### 2. **Task Management APIs**
   - **GET `/tasks`**: Get all tasks or tasks by user.
   - **POST `/tasks`**: Create a new task.
   - **PUT `/tasks/{taskId}`**: Update task (status, priority, etc.).
   - **DELETE `/tasks/{taskId}`**: Delete a task.

### 3. **Task Assignment APIs**
   - **PUT `/tasks/{taskId}/assign`**: Assign a task to a user.
   - **PUT `/tasks/{taskId}/unassign`**: Unassign a task from a user.

These APIs handle user management and task operations for your system. You can implement these using PHP and MySQL for the backend.

## Future Improvements

- **Email-based Forgot Password**: Adding email functionality to send password reset links.
- **Notifications**: Implementing task reminder notifications or email alerts for upcoming tasks.
- **Advanced Reporting**: Adding analytics to track task completion rates, user productivity, etc.
- **User Roles**: Adding more granular user roles and permissions, such as project manager or team leader.

---

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.
