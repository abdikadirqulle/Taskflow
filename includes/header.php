<?php
require_once './config.php';

// Ensure user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ./login.php');
    exit();
}
// Get user information from session
$user_id = $_SESSION['user_id'];

// Fetch current user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskFlow</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            
        }

        .nav-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #1D4ED8;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .logo:hover {
            color: #1e40af;
        }

        .logo i {
            font-size: 1.25rem;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .user-menu {
            position: relative;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .user-info:hover {
            background-color: #f1f5f9;
        }

        .user-info span {
            font-weight: 500;
            color: #1e293b;
        }

        .user-info i {
            color: #64748b;
            font-size: 0.875rem;
            transition: transform 0.3s ease;
        }

        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            width: 200px;
            padding: 0.5rem;
            margin-top: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .user-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown a {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            color: #1e293b;
            text-decoration: none;
            border-radius: 0.375rem;
            transition: background-color 0.3s ease;
        }

        .user-dropdown a:hover {
            background-color: #f1f5f9;
        }

        .user-dropdown i {
            color: #64748b;
            font-size: 1rem;
        }

        /* Animation for dropdown arrow */
        .user-info.active i {
            transform: rotate(180deg);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .nav-content {
                padding: 0 1rem;
            }

            .logo {
                font-size: 1.25rem;
            }

            .user-info span {
                display: none;
            }

            .user-dropdown {
                right: -1rem;
            }
        }
    </style>
</head>
<body>
<nav class="navbar">
    <div class="nav-content">
        <a href="dashboard.php" class="logo">
            <i class="fas fa-tasks"></i>
            TaskFlow
        </a>
        <div class="nav-links">
            <div class="user-menu">
                <div class="user-info" onclick="toggleDropdown()">
                <span><?php echo htmlspecialchars($user['name']); ?></span>
                <i class="fas fa-chevron-down"></i>
                </div>
                <div class="user-dropdown" id="userDropdown">
                    <a href="settings.php"><i class="fas fa-user"></i> Profile Settings</a>
                    <a href="billing.php"><i class="fas fa-credit-card"></i> Billing</a>
                    <a href="functions/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
function toggleDropdown() {
    const userInfo = document.querySelector('.user-info');
    const dropdown = document.getElementById('userDropdown');
    userInfo.classList.toggle('active');
    dropdown.classList.toggle('show');
}

// Close dropdown when clicking outside
window.onclick = function(event) {
    if (!event.target.matches('.user-info') && !event.target.matches('.user-info *')) {
        const userInfo = document.querySelector('.user-info');
        const dropdowns = document.getElementsByClassName('user-dropdown');
        userInfo.classList.remove('active');
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}
</script>
</body>
</html>