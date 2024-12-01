<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $due_date = $_POST['due_date'];
    $user_id = $_SESSION['user_id'];

    try {
        $pdo->beginTransaction();

        // Add task
        $stmt = $pdo->prepare("INSERT INTO tasks (title, description, priority, status, due_date, user_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $priority, $status, $due_date, $user_id]);
        $task_id = $pdo->lastInsertId();

        // Record activity
        $stmt = $pdo->prepare("INSERT INTO activities (user_id, task_id, action) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $task_id, "Created new task: $title"]);

        $pdo->commit();
        header('Location: ../dashboard.php');
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}
?>
