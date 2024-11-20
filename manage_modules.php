<?php
require_once 'config.php';

if (!isLoggedIn() || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if ($_POST['action'] == 'add') {
            $stmt = $pdo->prepare("INSERT INTO modules (module_code, module_name) VALUES (?, ?)");
            $stmt->execute([
                $_POST['module_code'],
                $_POST['module_name']
            ]);
            $success = "Module added successfully!";
        } elseif ($_POST['action'] == 'update') {
            $stmt = $pdo->prepare("UPDATE modules SET module_code = ?, module_name = ? WHERE id = ?");
            $stmt->execute([
                $_POST['module_code'],
                $_POST['module_name'],
                $_POST['module_id']
            ]);
            $success = "Module updated successfully!";
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch module for editing if ID is provided
$editing = false;
$edit_module = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM modules WHERE id = ? AND status = 1");
    $stmt->execute([$_GET['edit']]);
    $edit_module = $stmt->fetch();
    if ($edit_module) {
        $editing = true;
    }
}

// Fetch all modules
$stmt = $pdo->query("SELECT * FROM modules WHERE status = 1 ORDER BY module_code");
$modules = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Modules - Limkokwing ARS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f4f4f4;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        .cancel-btn {
            background: #666;
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
        }
        .success { color: green; }
        .error { color: red; }
        .nav { margin-bottom: 20px; }
        .nav a { color: #333; text-decoration: none; }
        .edit-link {
            color: #2196F3;
            text-decoration: none;
            margin-right: 10px;
        }
        .edit-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="dashboard.php">← Back to Dashboard</a>
        </div>

        <h2>Manage Modules</h2>

        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="action" value="<?php echo $editing ? 'update' : 'add'; ?>">
            <?php if ($editing): ?>
                <input type="hidden" name="module_id" value="<?php echo $edit_module['id']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label>Module Code:</label>
                <input type="text" name="module_code" required 
                       value="<?php echo $editing ? htmlspecialchars($edit_module['module_code']) : ''; ?>">
            </div>
            <div class="form-group">
                <label>Module Name:</label>
                <input type="text" name="module_name" required
                       value="<?php echo $editing ? htmlspecialchars($edit_module['module_name']) : ''; ?>">
            </div>
            <button type="submit"><?php echo $editing ? 'Update' : 'Add'; ?> Module</button>
            <?php if ($editing): ?>
                <a href="manage_modules.php" class="button cancel-btn" style="color: white; text-decoration: none;">Cancel</a>
            <?php endif; ?>
        </form>

        <h3>Existing Modules</h3>
        <table>
            <thead>
                <tr>
                    <th>Module Code</th>
                    <th>Module Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($modules as $module): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($module['module_code']); ?></td>
                        <td><?php echo htmlspecialchars($module['module_name']); ?></td>
                        <td><?php echo $module['status'] ? 'Active' : 'Inactive'; ?></td>
                        <td>
                            <a href="?edit=<?php echo $module['id']; ?>" class="edit-link">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>