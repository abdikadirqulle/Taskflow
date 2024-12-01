<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $task_id = $_GET['id'];
    
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$task_id, $user_id]);
    $task = $stmt->fetch();
    
    if ($task) {
        if (isset($_GET['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode($task);
            exit();
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Task not found']);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        $required_fields = ['task_id', 'title', 'description', 'priority', 'status', 'due_date'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        $task_id = $_POST['task_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $priority = $_POST['priority'];
        $status = $_POST['status'];
        $due_date = $_POST['due_date'];

        // Validate priority and status values
        $valid_priorities = ['Low', 'Medium', 'High'];
        $valid_statuses = ['Todo', 'In Progress', 'Done'];

        if (!in_array($priority, $valid_priorities)) {
            throw new Exception("Invalid priority value");
        }

        if (!in_array($status, $valid_statuses)) {
            throw new Exception("Invalid status value");
        }

        // Verify task belongs to user
        $check_stmt = $pdo->prepare("SELECT id FROM tasks WHERE id = ? AND user_id = ?");
        $check_stmt->execute([$task_id, $user_id]);
        if (!$check_stmt->fetch()) {
            throw new Exception("Task not found or unauthorized");
        }

        // Update task
        $stmt = $pdo->prepare("
            UPDATE tasks 
            SET title = ?, description = ?, priority = ?, status = ?, due_date = ? 
            WHERE id = ? AND user_id = ?
        ");
        
        $result = $stmt->execute([
            $title,
            $description,
            $priority,
            $status,
            $due_date,
            $task_id,
            $user_id
        ]);

        if (!$result) {
            throw new Exception("Database update failed");
        }

        // Log activity
        $stmt = $pdo->prepare("
            INSERT INTO activities (user_id, task_id, action, created_at) 
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->execute([$user_id, $task_id, "Updated task: $title"]);

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit();

    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode([
            'error' => 'Failed to update task',
            'message' => $e->getMessage()
        ]);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task - TaskFlow</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="assets/faviconIco.png" type="image/x-icon">
</head>
<body>
    <div class="container">
        <header>
            <h1>Edit Task</h1>
            <nav>
                <a href="dashboard.php">Back to Dashboard</a>
            </nav>
        </header>
        
        <main>
            <div class="task-form">
                <form method="POST">
                    <input type="hidden" name="task_id" value="<?php echo $_GET['id']; ?>">
                    <input type="text" name="title" placeholder="Task Title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
                    <textarea name="description" placeholder="Task Description"><?php echo htmlspecialchars($task['description']); ?></textarea>
                    <select name="priority">
                        <option value="Low" <?php echo $task['priority'] == 'Low' ? 'selected' : ''; ?>>Low</option>
                        <option value="Medium" <?php echo $task['priority'] == 'Medium' ? 'selected' : ''; ?>>Medium</option>
                        <option value="High" <?php echo $task['priority'] == 'High' ? 'selected' : ''; ?>>High</option>
                    </select>
                    <select name="status">
                        <option value="Todo" <?php echo $task['status'] == 'Todo' ? 'selected' : ''; ?>>Todo</option>
                        <option value="In Progress" <?php echo $task['status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                        <option value="Done" <?php echo $task['status'] == 'Done' ? 'selected' : ''; ?>>Done</option>
                    </select>
                    <input type="date" name="due_date" value="<?php echo $task['due_date']; ?>">
                    <button type="submit">Update Task</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
