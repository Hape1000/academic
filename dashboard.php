<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

// Get user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Limkokwing ARS</title>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #2ecc71;
            --warning-color: #f1c40f;
            --text-light: #ecf0f1;
            --text-dark: #2c3e50;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f6fa;
            color: var(--text-dark);
        }

        .header {
            background: var(--primary-color);
            color: var(--text-light);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header-title {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .header-link {
            color: var(--text-light);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .header-link:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .logout-btn {
            background: var(--accent-color);
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .menu {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            padding: 1rem;
        }

        .menu-item {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            overflow: hidden;
        }

        .menu-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--secondary-color);
        }

        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .menu-item a {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .menu-item.admin::before { background: var(--accent-color); }
        .menu-item.reports::before { background: var(--success-color); }
        .menu-item.attendance::before { background: var(--warning-color); }

        .welcome-message {
            text-align: center;
            margin-bottom: 2rem;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: var(--card-shadow);
        }

        .welcome-message h1 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .welcome-message p {
            color: #666;
            font-size: 1.1rem;
        }

        .user-info {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .user-info h3 {
            color: var(--primary-color);
            margin-top: 0;
        }

        .user-info p {
            margin: 0.5rem 0;
            color: #666;
        }

        .user-info strong {
            color: var(--text-dark);
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-title">Limkokwing ARS</div>
        <div class="header-actions">
            <a href="profile.php" class="header-link">My Profile</a>
            <a href="logout.php" class="header-link logout-btn">Logout</a>
        </div>
    </header>
    
    <div class="container">
        <div class="welcome-message">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h1>
            <p>Access your academic reporting tools below</p>
        </div>

        <div class="user-info">
            <h3>User Information</h3>
            <p><strong>Employee Number:</strong> <?php echo htmlspecialchars($user['employee_number']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Role:</strong> <?php echo ucfirst($user['role']); ?></p>
        </div>

        <div class="menu">
            <?php if ($role == 'admin'): ?>
                <div class="menu-item admin">
                    <a href="manage_lecturers.php">Manage Lecturers</a>
                </div>
                <div class="menu-item admin">
                    <a href="manage_academic.php">Manage Academic Years</a>
                </div>
                <div class="menu-item admin">
                    <a href="manage_modules.php">Manage Modules</a>
                </div>
                <div class="menu-item admin">
                    <a href="manage_classes.php">Manage Classes</a>
                </div>
                <div class="menu-item admin">
                    <a href="manage_students.php">Manage Students</a>
                </div>
                <div class="menu-item admin">
                    <a href="assign_modules.php">Assign Modules</a>
                </div>
                <!--
                <div class="menu-item admin">
                    <a href="view_logs.php">View System Logs</a>
                </div>
                -->
            <?php endif; ?>

            <?php if ($role == 'lecturer'): ?>
                <div class="menu-item reports">
                    <a href="weekly_report.php">Submit Weekly Report</a>
                </div>
                <div class="menu-item attendance">
                    <a href="attendance.php">Mark Attendance</a>
                </div>
            <?php endif; ?>

            <?php if ($role == 'prl'): ?>
                <div class="menu-item reports">
                    <a href="view_reports.php">View Reports</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
