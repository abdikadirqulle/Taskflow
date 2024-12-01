<?php
// Start the session to maintain user state
session_start();

// Include database connection and utility functions
require_once 'config.php';

// Check if user is logged in, redirect to login if not
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get user information from session
$user_id = $_SESSION['user_id'];

// Fetch user information from database
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Fetch task statistics
$total_stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ?");
$total_stmt->execute([$user_id]);
$total_tasks = $total_stmt->fetchColumn();

$completed_stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ? AND status = 'Done'");
$completed_stmt->execute([$user_id]);
$completed_tasks = $completed_stmt->fetchColumn();

$progress_percentage = $total_tasks > 0 ? round(($completed_tasks / $total_tasks) * 100) : 0;

// Get tasks with filtering and sorting
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'due_date';

$where_clause = "WHERE user_id = ?";
if ($status_filter !== 'all') {
    $where_clause .= " AND status = ?";
}

$stmt = $pdo->prepare("SELECT * FROM tasks $where_clause ORDER BY $sort_by ASC");
$params = [$user_id];
if ($status_filter !== 'all') {
    $params[] = $status_filter;
}
$stmt->execute($params);
$tasks = $stmt->fetchAll();

// Get recent activities
$stmt = $pdo->prepare("SELECT a.*, t.title FROM activities a 
                      LEFT JOIN tasks t ON a.task_id = t.id 
                      WHERE a.user_id = ? 
                      ORDER BY a.created_at DESC LIMIT 5");
$stmt->execute([$user_id]);
$activities = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TaskFlow</title>
    <!-- External CSS and Font dependencies -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="shortcut icon" href="assets/faviconIco.png" type="image/x-icon">
</head>
<body>
    <!-- Main Layout Container -->
    <div class="layout">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="#" class="logo">
                    <i class="fas fa-tasks"></i>
                    TaskFlow
                </a>
                <button class="mobile-menu-close"><i class="fas fa-times"></i></button>
            </div>
            <!-- Navigation Menu -->
            <nav class="sidebar-nav">
                <a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
                <a href="#" onclick="showAddTaskModal()"><i class="fas fa-plus"></i> Add Task</a>
                <a href="tasks.php"><i class="fas fa-tasks"></i> Task List</a>
                <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Top Header with Welcome Message -->
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
                        <a href="functions/logout.php"><i class="fas fa-sign-out-alt"></i> logout</a>
                    </div>
                </div>
            </header>

            <!-- Statistics Cards -->
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

            <!-- Task Overview Section -->
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
                                        <a href="#" onclick="showEditTaskModal(<?php echo $task['id']; ?>)" class="edit-btn"><i class="fas fa-edit"></i></a>
                                        <a href="functions/delete_task.php?id=<?php echo $task['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="activities-section">
                    <h2>Recent Activities</h2>
                    <div class="activities-list">
                        <?php foreach ($activities as $activity): ?>
                            <?php 
                                $actionClass = '';
                                $action = strtolower($activity['action']);
                                if (strpos($action, 'created') !== false) {
                                    $actionClass = 'activity-create';
                                } elseif (strpos($action, 'deleted') !== false) {
                                    $actionClass = 'activity-delete';
                                } elseif (strpos($action, 'updated') !== false) {
                                    $actionClass = 'activity-update';
                                }
                            ?>
                            <div class="activity-item <?php echo $actionClass; ?>">
                                <i class="fas fa-circle"></i>
                                <div class="activity-details">
                                    <p><?php echo htmlspecialchars($activity['action']); ?></p>
                                    <small><?php echo date('M d, Y H:i', strtotime($activity['created_at'])); ?></small>
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
            <form action="functions/add_task.php" method="POST">
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
                <button type="submit" class="btn-primary">Add Task</button>
            </form>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div id="editTaskModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Task</h2>
                <span class="close" onclick="closeEditTaskModal()">&times;</span>
            </div>
            <form id="editTaskForm" method="POST" onsubmit="return updateTask(event)">
                <input type="hidden" name="task_id" id="edit_task_id">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" id="edit_title" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="edit_description"></textarea>
                </div>
                <div class="form-group">
                    <label>Priority</label>
                    <select name="priority" id="edit_priority" required>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="edit_status" required>
                        <option value="Todo">Todo</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Done">Done</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Due Date</label>
                    <input type="date" name="due_date" id="edit_due_date" required>
                </div>
                <button type="submit" class="btn-primary">Update Task</button>
            </form>
        </div>
    </div>

    <script>
        // Mobile menu functionality
        document.querySelector('.mobile-menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.add('show');
        });

        document.querySelector('.mobile-menu-close').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.remove('show');
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

        // Add Task Modal Functions
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

        // Edit Task Modal Functions
        function showEditTaskModal(taskId) {
            fetch(`functions/edit_task.php?id=${taskId}&ajax=1`)
                .then(response => response.json())
                .then(task => {
                    document.getElementById('edit_task_id').value = task.id;
                    document.getElementById('edit_title').value = task.title;
                    document.getElementById('edit_description').value = task.description;
                    document.getElementById('edit_priority').value = task.priority;
                    document.getElementById('edit_status').value = task.status;
                    document.getElementById('edit_due_date').value = task.due_date;
                    
                    document.getElementById('editTaskModal').style.display = 'block';
                })
                .catch(error => console.error('Error:', error));
        }

        function closeEditTaskModal() {
            document.getElementById('editTaskModal').style.display = 'none';
        }

        function updateTask(event) {
            event.preventDefault();
            const form = document.getElementById('editTaskForm');
            const formData = new FormData(form);
            formData.append('ajax', '1');

            fetch('functions/edit_task.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeEditTaskModal();
                    window.location.reload();
                } else {
                    const errorMessage = data.message || 'Failed to update task';
                    alert(errorMessage);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update task. Please try again.');
            });

            return false;
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editTaskModal');
            if (event.target == modal) {
                closeEditTaskModal();
            }
        }
    </script>
</body>
</html>
