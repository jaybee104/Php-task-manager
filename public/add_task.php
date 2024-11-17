<?php
require_once '../src/Auth.php';
require_once '../src/Task.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $priority = $_POST['priority'];
    $due_date = $_POST['due_date'];

    if (Task::create($user_id, $title, $description, $category, $priority, $due_date)) {
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Failed to add task!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Task</title>
</head>
<body>
    <h2>Add Task</h2>

    <?php if (isset($error)) echo "<p>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="title" placeholder="Task Title" required><br>
        <textarea name="description" placeholder="Task Description"></textarea><br>
        <input type="text" name="category" placeholder="Category (e.g., Work, Personal)" required><br>
        <select name="priority">
            <option value="Low">Low</option>
            <option value="Medium" selected>Medium</option>
            <option value="High">High</option>
        </select><br>
        <input type="date" name="due_date" required><br>
        <button type="submit">Add Task</button>
    </form>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
