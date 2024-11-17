<?php
require_once '../src/Auth.php';
require_once '../src/Task.php';

session_start();

 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

 
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$task_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

 
$task = Task::getById($task_id);

 
if (!$task || $task['user_id'] != $user_id) {
    header("Location: dashboard.php");
    exit();
}

 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $priority = $_POST['priority'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    if (Task::update($task_id, $title, $description, $category, $priority, $due_date, $status)) {
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Failed to update task!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
</head>
<body>
    <h2>Edit Task</h2>

    <?php if (isset($error)) echo "<p>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" required><br>
        <textarea name="description"><?= htmlspecialchars($task['description']) ?></textarea><br>
        <input type="text" name="category" value="<?= htmlspecialchars($task['category']) ?>" required><br>
        
        <select name="priority">
            <option value="Low" <?= ($task['priority'] == 'Low') ? 'selected' : '' ?>>Low</option>
            <option value="Medium" <?= ($task['priority'] == 'Medium') ? 'selected' : '' ?>>Medium</option>
            <option value="High" <?= ($task['priority'] == 'High') ? 'selected' : '' ?>>High</option>
        </select><br>

        <input type="date" name="due_date" value="<?= htmlspecialchars($task['due_date']) ?>" required><br>
        
        <select name="status">
            <option value="Pending" <?= ($task['status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
            <option value="Completed" <?= ($task['status'] == 'Completed') ? 'selected' : '' ?>>Completed</option>
        </select><br>
        
        <button type="submit">Update Task</button>
    </form>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
