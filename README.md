Task Manager
A simple and efficient Task Management System built with PHP. This application allows users to create, view, edit, and delete tasks. It also features user authentication (registration and login), and task filtering, sorting, and pagination for better task management.

Features
User Authentication: Users can register, log in, and log out.
Task CRUD Operations: Create, read, update, and delete tasks.
Task Prioritization: Tasks can be marked with priorities (Low, Medium, High).
Task Status: Tasks can be marked as "Pending" or "Completed".
Filtering: Filter tasks by status, category, or priority.
Pagination: Pagination for tasks to handle large task lists.
Task Editing: Edit task details (title, description, due date, priority, status).
Responsive UI: Clean and simple interface for managing tasks.
Installation
Prerequisites
PHP (>=7.4)
MySQL or MariaDB
 

1. Clone the Repository
 
git clone https://github.com/your-username/task-manager.git
cd task-manager

2. Configure Database
Create a new MySQL database, and then import the database.sql file.


CREATE DATABASE task_manager;
Run the SQL queries to create the necessary tables. Example schema:

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(100),
    priority ENUM('Low', 'Medium', 'High') DEFAULT 'Medium',
    status ENUM('Pending', 'Completed') DEFAULT 'Pending',
    due_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
3. Configure Database Connection
Update the database connection details in your PHP script. Usually, this will be located in a file like db.php or config.php.

Example:

<?php
$pdo = new PDO('mysql:host=localhost;dbname=task_manager', 'username', 'password');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

4. Install Dependencies (Optional)
If you're using Composer for autoloading, you can install dependencies by running:

5. Start the Application
Once your environment is set up, navigate to your project folder and access the application via your web server.


php -S localhost:8000
Visit http://localhost:8000 in your browser to use the Task Manager.

Usage
Registration and Login
Sign Up: New users can sign up with a username, email, and password.
Login: Registered users can log in using their credentials.
Log Out: Users can log out of their account to securely end their session.
Managing Tasks
Create a Task: Users can create a new task by specifying a title, description, category, priority, and due date.
Edit a Task: Users can edit existing tasks to update details.
Delete a Task: Tasks can be deleted if no longer needed.
Mark as Completed: Users can mark tasks as "Completed" once done.
Filter Tasks: Users can filter tasks by status, category, or priority.
Pagination: Tasks are displayed with pagination, so users can navigate through large lists of tasks easily.
Folder Structure
bash
Copy code
/task-manager
    /public
        - index.php
        - dashboard.php
        - login.php
        - register.php
        - edit_task.php
    /src
        - Auth.php
        - Task.php
        - Database.php
    /config
        - config.php
    /assets
        - css/
        - js/
    /templates
        - header.php
        - footer.php
    .gitignore

README.md
public/: Contains public-facing pages like login, registration, and dashboard.
src/: Contains the PHP classes for authentication, task management, and database interactions.
config/: Configuration files for the database connection.
assets/: Stores static files like CSS and JS.
templates/: Includes reusable templates (header, footer) for consistent page layout.
Contributing
Contributions are welcome! Feel free to fork this repository, make improvements, and submit pull requests.
