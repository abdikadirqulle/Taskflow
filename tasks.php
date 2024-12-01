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

// Prepare SQL query to retrieve user name
$stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Handle task filtering
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'due_date';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare the SQL query based on filters
$where_clause = "WHERE user_id = ?";
$params = [$user_id];

if ($status_filter !== 'all') {
    $where_clause .= " AND status = ?";
    $params[] = $status_filter;
}

if ($search) {
    $where_clause .= " AND (title LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql = "SELECT * FROM tasks $where_clause ORDER BY $sort_by ASC";

// Execute the filtered query
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tasks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks - TaskFlow</title>
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
                <a href="dashboard.php" class="logo">
                    <i class="fas fa-tasks"></i>
                    TaskFlow
                </a>
                <button class="mobile-menu-close"><i class="fas fa-times"></i></button>
            </div>
            <!-- Navigation Menu -->
            <nav class="sidebar-nav">
                <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                <a href="#" onclick="showAddTaskModal()"><i class="fas fa-plus"></i> Add Task</a>
                <a href="tasks.php" class="active"><i class="fas fa-tasks"></i> Task List</a>
                <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Top Header with Filters -->
            <header class="top-header">
                <button class="mobile-menu-toggle"><i class="fas fa-bars"></i></button>
                <h1>Tasks</h1>
                <div class="user-menu">
                    <div class="user-profile" onclick="toggleUserMenu()">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['name']); ?>&background=1D4ED8&color=fff" alt="Profile">
                        <span><?php echo htmlspecialchars($user['name']); ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="user-dropdown" id="userDropdown">
                        <a href="settings.php"><i class="fas fa-user"></i> Profile Settings</a>
                        <a href="billing.php"><i class="fas fa-credit-card"></i> Billing</a>
                        <a href="functions/logout.php"><i class="fas fa-sign-out-alt"></i> logout</a>
                    </div>
                </div>
            </header>

            <div class="tasks-page">
                <div class="tasks-controls">
                    <div class="search-box">
                        <input type="text" placeholder="Search tasks..." value="<?php echo htmlspecialchars($search); ?>" 
                               onchange="window.location.href='?search=' + this.value">
                    </div>
                    <div class="filters">
                        <select onchange="window.location.href='?status=' + this.value">
                            <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>All Status</option>
                            <option value="Todo" <?php echo $status_filter === 'Todo' ? 'selected' : ''; ?>>Todo</option>
                            <option value="In Progress" <?php echo $status_filter === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                            <option value="Done" <?php echo $status_filter === 'Done' ? 'selected' : ''; ?>>Done</option>
                        </select>
                        <select onchange="window.location.href='?sort=' + this.value">
                            <option value="due_date" <?php echo $sort_by === 'due_date' ? 'selected' : ''; ?>>Sort by Due Date</option>
                            <option value="priority" <?php echo $sort_by === 'priority' ? 'selected' : ''; ?>>Sort by Priority</option>
                            <option value="title" <?php echo $sort_by === 'title' ? 'selected' : ''; ?>>Sort by Title</option>
                        </select>
                        <button class="add-task-btn" onclick="showAddTaskModal()">
                            <i class="fas fa-plus"></i> Add Task
                        </button>
                    </div>
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

        // Modal functions
        function showAddTaskModal() {
            document.getElementById('addTaskModal').style.display = 'block';
        }

        function closeAddTaskModal() {
            document.getElementById('addTaskModal').style.display = 'none';
        }

        // User menu toggle
        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
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
