<?php
require_once '../src/Auth.php';
require_once '../src/Task.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get filters from GET parameters
$status = isset($_GET['status']) ? $_GET['status'] : null;
$category = isset($_GET['category']) ? $_GET['category'] : null;
$priority = isset($_GET['priority']) ? $_GET['priority'] : null;

 
$tasks_per_page = 5;  
$page = isset($_GET['page']) ? $_GET['page'] : 1;  // Current page number
$offset = ($page - 1) * $tasks_per_page;  // Calculate the offset for the SQL query

// Get tasks with pagination
$tasks = Task::getAll($user_id, $status, $category, $priority, $tasks_per_page, $offset);

// Get the total number of tasks for pagination controls
$total_tasks_query = "SELECT COUNT(*) FROM tasks WHERE user_id = ?";
$total_tasks_stmt = $pdo->prepare($total_tasks_query);
$total_tasks_stmt->execute([$user_id]);
$total_tasks = $total_tasks_stmt->fetchColumn();

// Calculate total number of pages
$total_pages = ceil($total_tasks / $tasks_per_page);

// Handle task deletion
if (isset($_GET['delete'])) {
    $task_id = $_GET['delete'];
    Task::delete($task_id);
    header("Location: dashboard.php");
    exit();
}

// Handle task completion update
if (isset($_GET['complete'])) {
    $task_id = $_GET['complete'];
    Task::update($task_id, null, null, null, null, null, 'Completed');
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task Dashboard</title>
    <style>
        /* Add some basic styling for the dashboard */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .pagination a {
            margin: 0 5px;
            text-decoration: none;
            padding: 5px 10px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }
        .pagination span {
            padding: 5px 10px;
        }
        .filters {
            margin-bottom: 20px;
        }
        .filters select, .filters button {
            padding: 5px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <h2>Task Dashboard</h2>

    <!-- Filters Section -->
    <form method="GET" class="filters">
        <select name="status">
            <option value="">All Statuses</option>
            <option value="Pending" <?= ($status == 'Pending') ? 'selected' : '' ?>>Pending</option>
            <option value="Completed" <?= ($status == 'Completed') ? 'selected' : '' ?>>Completed</option>
        </select>
        
        <select name="category">
            <option value="">All Categories</option>
            <option value="Work" <?= ($category == 'Work') ? 'selected' : '' ?>>Work</option>
            <option value="Personal" <?= ($category == 'Personal') ? 'selected' : '' ?>>Personal</option>
        </select>

        <select name="priority">
            <option value="">All Priorities</option>
            <option value="Low" <?= ($priority == 'Low') ? 'selected' : '' ?>>Low</option>
            <option value="Medium" <?= ($priority == 'Medium') ? 'selected' : '' ?>>Medium</option>
            <option value="High" <?= ($priority == 'High') ? 'selected' : '' ?>>High</option>
        </select>

        <button type="submit">Filter</button>
    </form>

    <!-- Task List -->
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Category</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?= htmlspecialchars($task['title']) ?></td>
                    <td><?= htmlspecialchars($task['description']) ?></td>
                    <td><?= htmlspecialchars($task['category']) ?></td>
                    <td><?= htmlspecialchars($task['priority']) ?></td>
                    <td><?= htmlspecialchars($task['status']) ?></td>
                    <td><?= htmlspecialchars($task['due_date']) ?></td>
                    <td>
                        <!-- Mark as completed -->
                        <?php if ($task['status'] == 'Pending'): ?>
                            <a href="?complete=<?= $task['id'] ?>">Mark as Completed</a>
                        <?php endif; ?>

                        <!-- Delete task -->
                        <a href="?delete=<?= $task['id'] ?>" onclick="return confirm('Are you sure you want to delete this task?')">Delete</a>

                        <!-- Edit task -->
                        <a href="edit_task.php?id=<?= $task['id'] ?>">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination Controls -->
    <div class="pagination">
        <a href="?page=1&status=<?= $status ?>&category=<?= $category ?>&priority=<?= $priority ?>">First</a>
        <a href="?page=<?= max(1, $page - 1) ?>&status=<?= $status ?>&category=<?= $category ?>&priority=<?= $priority ?>">Previous</a>
        
        <span>Page <?= $page ?> of <?= $total_pages ?></span>
        
        <a href="?page=<?= min($total_pages, $page + 1) ?>&status=<?= $status ?>&category=<?= $category ?>&priority=<?= $priority ?>">Next</a>
        <a href="?page=<?= $total_pages ?>&status=<?= $status ?>&category=<?= $category ?>&priority=<?= $priority ?>">Last</a>
    </div>

    <br>
    <a href="add_task.php">Add New Task</a>
    <br><br>
    <a href="logout.php">Logout</a>

<?php
include '../templates/footer.php';  // footer
?>

