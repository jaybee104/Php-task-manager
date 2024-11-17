<?php
require_once 'config/db.php';

class Task {

    // Create a new task
    public static function create($user_id, $title, $description, $category, $priority, $due_date) {
        global $pdo;

        // Prepare and execute SQL statement
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description, category, priority, due_date) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$user_id, $title, $description, $category, $priority, $due_date]);
    }

    // Get all tasks for a specific user
    public static function getAll($user_id, $status = null, $category = null, $priority = null) {
        global $pdo;

        // Construct the query
        $query = "SELECT * FROM tasks WHERE user_id = ?";
        $params = [$user_id];

        // Apply filters if they are provided
        if ($status) {
            $query .= " AND status = ?";
            $params[] = $status;
        }
        if ($category) {
            $query .= " AND category = ?";
            $params[] = $category;
        }
        if ($priority) {
            $query .= " AND priority = ?";
            $params[] = $priority;
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single task by its ID
    public static function getById($task_id) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->execute([$task_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update task details
    public static function update($task_id, $title, $description, $category, $priority, $due_date, $status) {
        global $pdo;

        $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, category = ?, priority = ?, 
                               due_date = ?, status = ? WHERE id = ?");
        return $stmt->execute([$title, $description, $category, $priority, $due_date, $status, $task_id]);
    }

    // Delete a task
    public static function delete($task_id) {
        global $pdo;

        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
        return $stmt->execute([$task_id]);
    }
}
