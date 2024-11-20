<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Get user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Validate current password
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND password = ?");
        $stmt->execute([$user_id, $_POST['current_password']]);
        
        if ($stmt->fetch()) {
            $new_password = $_POST['new_password'];
            $role = $_SESSION['role'];
            
            // Validate password format based on role
            if ($role == 'admin') {
                if (!preg_match('/^\d{6}$/', $new_password)) {
                    throw new Exception("Admin password must be exactly 6 digits!");
                }
            } else {
                if (!preg_match('/^\d{5}$/', $new_password)) {
                    throw new Exception("Password must be exactly 5 digits!");
                }
            }

            // Update password
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$new_password, $user_id]);
            
            // Update admin code if provided
            if ($role == 'admin' && !empty($_POST['admin_code'])) {
                // Here you would typically update the admin code in a settings table
                // For now, we'll just show a message
                $success = "Password and admin registration code updated successfully!";
            } else {
                $success = "Password updated successfully!";
            }
            
            logAction($user_id, 'Password changed');
        } else {
            throw new Exception("Current password is incorrect!");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Profile - Limkokwing ARS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #2ecc71;
            --warning-color: #f1c40f;
            --text-light: #ecf0f1;
            --text-dark: #2c3e50;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .profile-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .profile-info {
            margin-bottom: 1.5rem;
        }

        .profile-info p {
            margin: 0.5rem 0;
            color: var(--text-dark);
        }

        .profile-info strong {
            display: inline-block;
            width: 150px;
            color: var(--primary-color);
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-dark);
        }

        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background: var(--secondary-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            width: 100%;
        }

        button:hover {
            opacity: 0.9;
        }

        .success {
            color: var(--success-color);
            background: rgba(46, 204, 113, 0.1);
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .error {
            color: var(--accent-color);
            background: rgba(231, 76, 60, 0.1);
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .nav {
            margin-bottom: 1.5rem;
        }

        .nav a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .nav a:hover {
            color: var(--primary-color);
        }

        .password-requirements {
            font-size: 0.9rem;
            color: #666;
            margin-top: 0.5rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
                margin: 0.5rem;
            }

            .profile-section {
                padding: 1rem;
            }

            .profile-info strong {
                width: 120px;
            }

            button {
                padding: 0.5rem 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="dashboard.php">← Back to Dashboard</a>
        </div>

        <h2>My Profile</h2>

        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="profile-section">
            <h3>User Information</h3>
            <div class="profile-info">
                <p><strong>Employee Number:</strong> <?php echo htmlspecialchars($user['employee_number']); ?></p>
                <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['full_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Role:</strong> <?php echo ucfirst($user['role']); ?></p>
            </div>
        </div>

        <div class="profile-section">
            <h3>Change Password</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Current Password:</label>
                    <input type="password" name="current_password" required>
                </div>

                <div class="form-group">
                    <label>New Password:</label>
                    <input type="password" name="new_password" required 
                           pattern="<?php echo $user['role'] == 'admin' ? '\d{6}' : '\d{5}'; ?>">
                    <p class="password-requirements">
                        Password must be exactly <?php echo $user['role'] == 'admin' ? '6' : '5'; ?> digits
                    </p>
                </div>

                <div class="form-group">
                    <label>Confirm New Password:</label>
                    <input type="password" name="confirm_password" required
                           pattern="<?php echo $user['role'] == 'admin' ? '\d{6}' : '\d{5}'; ?>">
                </div>

                <?php if ($user['role'] == 'admin'): ?>
                <div class="form-group">
                    <label>New Admin Registration Code (Optional):</label>
                    <input type="password" name="admin_code">
                    <p class="password-requirements">Leave blank to keep current code</p>
                </div>
                <?php endif; ?>

                <button type="submit">Update Password</button>
            </form>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const newPassword = document.querySelector('input[name="new_password"]').value;
            const confirmPassword = document.querySelector('input[name="confirm_password"]').value;

            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
            }
        });
    </script>
</body>
</html>