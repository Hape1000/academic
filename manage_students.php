<?php
require_once 'config.php';

if (!isLoggedIn() || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$success = '';
$error = '';

// Fetch all classes for dropdown
$stmt = $pdo->query("SELECT * FROM classes WHERE status = 1 ORDER BY class_name");
$classes = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if ($_POST['action'] == 'add') {
            // Validate student number (must be 9 digits)
            if (!preg_match('/^\d{9}$/', $_POST['student_number'])) {
                throw new Exception("Student number must be exactly 9 digits!");
            }

            // Check if student number already exists
            $stmt = $pdo->prepare("SELECT id FROM students WHERE student_number = ?");
            $stmt->execute([$_POST['student_number']]);
            if ($stmt->fetch()) {
                throw new Exception("Student number already exists!");
            }

            $stmt = $pdo->prepare("INSERT INTO students (student_number, full_name, class_id) VALUES (?, ?, ?)");
            $stmt->execute([
                $_POST['student_number'],
                $_POST['full_name'],
                $_POST['class_id']
            ]);
            $success = "Student added successfully!";
        }
        elseif ($_POST['action'] == 'toggle_status') {
            $stmt = $pdo->prepare("UPDATE students SET status = NOT status WHERE id = ?");
            $stmt->execute([$_POST['student_id']]);
            $success = "Student status updated successfully!";
        }
        elseif ($_POST['action'] == 'update_class') {
            $stmt = $pdo->prepare("UPDATE students SET class_id = ? WHERE id = ?");
            $stmt->execute([$_POST['new_class_id'], $_POST['student_id']]);
            $success = "Student class updated successfully!";
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch all students with their class names
$stmt = $pdo->query("
    SELECT s.*, c.class_name 
    FROM students s 
    JOIN classes c ON s.class_id = c.id 
    ORDER BY s.status DESC, s.student_number
");
$students = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Students - Limkokwing ARS</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        input[type="text"],
        select {
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
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #f5f5f5;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .success { 
            color: #2ecc71;
            background: rgba(46, 204, 113, 0.1);
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .error { 
            color: #e74c3c;
            background: rgba(231, 76, 60, 0.1);
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .nav { 
            margin-bottom: 20px;
        }
        .nav a { 
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
        }
        .action-btn {
            padding: 5px 10px;
            margin: 2px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            color: white;
        }
        .enable-btn { background: #2ecc71; }
        .disable-btn { background: #e74c3c; }
        .update-btn { background: #3498db; }
        .inactive-row {
            opacity: 0.7;
            background: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="dashboard.php">← Back to Dashboard</a>
        </div>

        <h2>Manage Students</h2>

        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" id="addStudentForm">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label>Student Number:</label>
                <input type="text" name="student_number" required 
                       placeholder="Enter 9-digit student number">
            </div>
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="full_name" required>
            </div>
            <div class="form-group">
                <label>Class:</label>
                <select name="class_id" required>
                    <option value="">Select Class</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo $class['id']; ?>">
                            <?php echo htmlspecialchars($class['class_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit">Add Student</button>
        </form>

        <h3>Existing Students</h3>
        <table>
            <thead>
                <tr>
                    <th>Student Number</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr class="<?php echo $student['status'] ? '' : 'inactive-row'; ?>">
                        <td><?php echo htmlspecialchars($student['student_number']); ?></td>
                        <td><?php echo htmlspecialchars($student['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($student['class_name']); ?></td>
                        <td><?php echo $student['status'] ? 'Active' : 'Inactive'; ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="toggle_status">
                                <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                <button type="submit" 
                                        class="action-btn <?php echo $student['status'] ? 'disable-btn' : 'enable-btn'; ?>">
                                    <?php echo $student['status'] ? 'Disable' : 'Enable'; ?>
                                </button>
                            </form>

                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="update_class">
                                <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                <select name="new_class_id" 
                                        onchange="this.form.submit()" 
                                        class="action-btn update-btn">
                                    <option value="">Change Class</option>
                                    <?php foreach ($classes as $class): ?>
                                        <?php if ($class['id'] != $student['class_id']): ?>
                                            <option value="<?php echo $class['id']; ?>">
                                                <?php echo htmlspecialchars($class['class_name']); ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        document.getElementById('addStudentForm').addEventListener('submit', function(e) {
            const studentNum = document.querySelector('input[name="student_number"]').value;
            
            if (!/^\d{9}$/.test(studentNum)) {
                e.preventDefault();
                alert('Student number must be exactly 9 digits!');
            }
        });
    </script>
</body>
</html>