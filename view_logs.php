<?php
require_once 'config.php';

if (!isLoggedIn() || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Fetch all logs with user information
$stmt = $pdo->query("
    SELECT l.*, u.full_name, u.employee_number 
    FROM logs l
    JOIN users u ON l.user_id = u.id
    ORDER BY l.timestamp DESC
    LIMIT 1000
");
$logs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>System Logs - Limkokwing ARS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #f5f5f5;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .nav { margin-bottom: 20px; }
        .nav a { color: #333; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="dashboard.php">← Back to Dashboard</a>
        </div>

        <h2>System Logs</h2>

        <table>
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>User</th>
                    <th>Employee Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?php echo date('Y-m-d H:i:s', strtotime($log['timestamp'])); ?></td>
                        <td><?php echo htmlspecialchars($log['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($log['employee_number']); ?></td>
                        <td><?php echo htmlspecialchars($log['action']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>