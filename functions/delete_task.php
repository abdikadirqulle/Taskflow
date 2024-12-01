<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $task_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    try {
        // Start transaction
        $pdo->beginTransaction();

        // First get the task details
        $stmt = $pdo->prepare("SELECT title FROM tasks WHERE id = ? AND user_id = ?");
        $stmt->execute([$task_id, $user_id]);
        $task = $stmt->fetch();

        if ($task) {
            // Log the activity first (before deletion)
            $action = "Deleted task: " . $task['title'];
            $stmt = $pdo->prepare("INSERT INTO activities (user_id, task_id, action, created_at) VALUES (?, NULL, ?, NOW())");
            $stmt->execute([$user_id, $action]);

            // Then delete the task
            $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
            $stmt->execute([$task_id, $user_id]);

            // Commit the transaction
            $pdo->commit();
        }
    } catch (PDOException $e) {
        // Rollback the transaction if something fails
        $pdo->rollBack();
        throw $e;
    }
}

header('Location: ../dashboard.php');
exit();
?>
