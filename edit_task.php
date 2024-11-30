<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$task_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $due_date = $_POST['due_date'];

    $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, category = ?, due_date = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$title, $description, $category, $due_date, $task_id, $user_id]);

    header('Location: index.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
$stmt->execute([$task_id, $user_id]);
$task = $stmt->fetch();

if (!$task) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task - TaskFlow</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Edit Task</h1>
            <nav>
                <a href="index.php">Back to Dashboard</a>
            </nav>
        </header>
        
        <main>
            <div class="task-form">
                <form method="POST">
                    <input type="text" name="title" placeholder="Task Title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
                    <textarea name="description" placeholder="Task Description"><?php echo htmlspecialchars($task['description']); ?></textarea>
                    <select name="category">
                        <option value="work" <?php echo $task['category'] == 'work' ? 'selected' : ''; ?>>Work</option>
                        <option value="personal" <?php echo $task['category'] == 'personal' ? 'selected' : ''; ?>>Personal</option>
                        <option value="shopping" <?php echo $task['category'] == 'shopping' ? 'selected' : ''; ?>>Shopping</option>
                        <option value="other" <?php echo $task['category'] == 'other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                    <input type="date" name="due_date" value="<?php echo $task['due_date']; ?>">
                    <button type="submit">Update Task</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
