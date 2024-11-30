<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Get task statistics
$total_stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ?");
$total_stmt->execute([$user_id]);
$total_tasks = $total_stmt->fetchColumn();

$completed_stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ? AND status = 'Done'");
$completed_stmt->execute([$user_id]);
$completed_tasks = $completed_stmt->fetchColumn();

$progress_percentage = $total_tasks > 0 ? round(($completed_tasks / $total_tasks) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TaskFlow</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>TaskFlow</h2>
                <button class="mobile-menu-close"><i class="fas fa-times"></i></button>
            </div>
            <nav class="sidebar-nav">
                <a href="index.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
                <a href="#" onclick="showAddTaskModal()"><i class="fas fa-plus"></i> Add Task</a>
                <a href="tasks.php"><i class="fas fa-tasks"></i> Task List</a>
                <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="top-header">
                <button class="mobile-menu-toggle"><i class="fas fa-bars"></i></button>
                <h1>Dashboard</h1>
                <div class="user-menu">
                    <div class="user-profile" onclick="toggleUserMenu()">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['name']); ?>&background=1D4ED8&color=fff" alt="Profile">
                        <span><?php echo htmlspecialchars($user['name']); ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="user-dropdown" id="userDropdown">
                        <a href="settings.php"><i class="fas fa-user"></i> Profile Settings</a>
                        <a href="#"><i class="fas fa-credit-card"></i> Billing</a>
                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            </header>

            <div class="stats-container">
                <div class="stat-card total">
                    <h3>Total Tasks</h3>
                    <div class="number"><?php echo $total_tasks; ?></div>
                </div>
                <div class="stat-card completed">
                    <h3>Completed Tasks</h3>
                    <div class="number"><?php echo $completed_tasks; ?></div>
                </div>
                <div class="stat-card progress">
                    <h3>Progress</h3>
                    <div class="progress-bar">
                        <div class="fill" style="width: <?php echo $progress_percentage; ?>%"></div>
                    </div>
                    <div class="number"><?php echo $progress_percentage; ?>% complete</div>
                </div>
            </div>

            <div class="dashboard-content">
                <div class="tasks-section">
                    <div class="section-header">
                        <h2>Your Tasks</h2>
                        <button class="add-task-btn" onclick="showAddTaskModal()">
                            <i class="fas fa-plus"></i> Add Task
                        </button>
                    </div>

                    <div class="filters">
                        <select onchange="window.location.href='?status=' + this.value">
                            <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>All Status</option>
                            <option value="Todo" <?php echo $status_filter === 'Todo' ? 'selected' : ''; ?>>Todo</option>
                            <option value="In Progress" <?php echo $status_filter === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                            <option value="Done" <?php echo $status_filter === 'Done' ? 'selected' : ''; ?>>Done</option>
                        </select>
                        <select onchange="window.location.href='?sort=' + this.value">
                            <option value="due_date">Sort by Due Date</option>
                            <option value="priority">Sort by Priority</option>
                            <option value="title">Sort by Title</option>
                        </select>
                    </div>

                    <div class="tasks-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>TITLE</th>
                                    <th>DUE DATE</th>
                                    <th>PRIORITY</th>
                                    <th>STATUS</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tasks as $task): ?>
                                <tr>
                                    <td>
                                        <div class="task-title">
                                            <?php echo htmlspecialchars($task['title']); ?>
                                            <small><?php echo htmlspecialchars($task['description']); ?></small>
                                        </div>
                                    </td>
                                    <td><?php echo $task['due_date']; ?></td>
                                    <td><span class="priority <?php echo strtolower($task['priority']); ?>"><?php echo $task['priority']; ?></span></td>
                                    <td><span class="status <?php echo str_replace(' ', '-', strtolower($task['status'])); ?>"><?php echo $task['status']; ?></span></td>
                                    <td class="actions">
                                        <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="edit-btn"><i class="fas fa-edit"></i></a>
                                        <a href="delete_task.php?id=<?php echo $task['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="activities-section">
                    <h2>Recent Activities</h2>
                    <div class="activities-list">
                        <?php foreach ($activities as $activity): ?>
                        <div class="activity-item">
                            <i class="fas fa-circle"></i>
                            <div class="activity-content">
                                <p><?php echo htmlspecialchars($activity['action']); ?></p>
                                <small><?php echo date('M j, Y g:i A', strtotime($activity['created_at'])); ?></small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Task Modal -->
    <div id="addTaskModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddTaskModal()">&times;</span>
            <h2>Add New Task</h2>
            <form action="add_task.php" method="POST">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Priority</label>
                        <select name="priority">
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="Todo">Todo</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Done">Done</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Due Date</label>
                    <input type="date" name="due_date" required>
                </div>
                <button type="submit">Add Task</button>
            </form>
        </div>
    </div>

    <script>
        function showAddTaskModal() {
            document.getElementById('addTaskModal').style.display = 'block';
        }

        function closeAddTaskModal() {
            document.getElementById('addTaskModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == document.getElementById('addTaskModal')) {
                closeAddTaskModal();
            }
        }

        // Mobile menu toggle
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const mobileMenuClose = document.querySelector('.mobile-menu-close');
        const sidebar = document.querySelector('.sidebar');

        mobileMenuToggle.addEventListener('click', () => {
            sidebar.classList.add('show');
        });

        mobileMenuClose.addEventListener('click', () => {
            sidebar.classList.remove('show');
        });

        // User menu toggle
        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        window.addEventListener('click', (e) => {
            if (!e.target.closest('.user-menu')) {
                document.getElementById('userDropdown').classList.remove('show');
            }
        });
    </script>
</body>
</html>
