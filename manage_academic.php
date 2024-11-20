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
        if ($_POST['action'] == 'add_year') {
            // Reset current flag if this year is set as current
            if (isset($_POST['is_current']) && $_POST['is_current'] == 1) {
                $pdo->query("UPDATE academic_years SET is_current = 0");
            }

            $stmt = $pdo->prepare("
                INSERT INTO academic_years (year_name, is_current) 
                VALUES (?, ?)
            ");
            $stmt->execute([
                $_POST['year_name'],
                isset($_POST['is_current']) ? 1 : 0
            ]);
            $success = "Academic year added successfully!";
        }
        elseif ($_POST['action'] == 'add_semester') {
            // Reset current flag if this semester is set as current
            if (isset($_POST['is_current']) && $_POST['is_current'] == 1) {
                $pdo->query("UPDATE semesters SET is_current = 0");
            }

            $stmt = $pdo->prepare("
                INSERT INTO semesters (
                    semester_name, academic_year_id, 
                    start_date, end_date, is_current
                ) VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $_POST['semester_name'],
                $_POST['academic_year_id'],
                $_POST['start_date'],
                $_POST['end_date'],
                isset($_POST['is_current']) ? 1 : 0
            ]);
            $success = "Semester added successfully!";
        }
        elseif ($_POST['action'] == 'update_year') {
            if (isset($_POST['is_current']) && $_POST['is_current'] == 1) {
                $pdo->query("UPDATE academic_years SET is_current = 0");
            }

            $stmt = $pdo->prepare("
                UPDATE academic_years 
                SET year_name = ?, is_current = ?, status = ? 
                WHERE id = ?
            ");
            $stmt->execute([
                $_POST['year_name'],
                isset($_POST['is_current']) ? 1 : 0,
                $_POST['status'],
                $_POST['year_id']
            ]);
            $success = "Academic year updated successfully!";
        }
        elseif ($_POST['action'] == 'update_semester') {
            if (isset($_POST['is_current']) && $_POST['is_current'] == 1) {
                $pdo->query("UPDATE semesters SET is_current = 0");
            }

            $stmt = $pdo->prepare("
                UPDATE semesters 
                SET semester_name = ?, academic_year_id = ?, 
                    start_date = ?, end_date = ?, 
                    is_current = ?, status = ? 
                WHERE id = ?
            ");
            $stmt->execute([
                $_POST['semester_name'],
                $_POST['academic_year_id'],
                $_POST['start_date'],
                $_POST['end_date'],
                isset($_POST['is_current']) ? 1 : 0,
                $_POST['status'],
                $_POST['semester_id']
            ]);
            $success = "Semester updated successfully!";
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch academic years
$stmt = $pdo->query("SELECT * FROM academic_years ORDER BY year_name DESC");
$academic_years = $stmt->fetchAll();

// Fetch semesters with academic year info
$stmt = $pdo->query("
    SELECT s.*, ay.year_name 
    FROM semesters s 
    JOIN academic_years ay ON s.academic_year_id = ay.id 
    ORDER BY ay.year_name DESC, s.semester_name
");
$semesters = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Academic Years - Limkokwing ARS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
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
        input[type="date"],
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .checkbox-group {
            margin: 10px 0;
        }

        .checkbox-group label {
            display: inline;
            margin-left: 5px;
        }

        button {
            background: var(--secondary-color);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }

        button:hover {
            background: #2980b9;
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
            color: var(--success-color);
            background: rgba(46, 204, 113, 0.1);
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .error { 
            color: var(--accent-color);
            background: rgba(231, 76, 60, 0.1);
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .nav { 
            margin-bottom: 20px;
        }

        .nav a { 
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .nav a:hover {
            color: var(--primary-color);
        }

        .edit-btn {
            background: var(--warning-color);
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            text-decoration: none;
            font-size: 0.9em;
        }

        .edit-btn:hover {
            background: #f39c12;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
        }

        .close {
            float: right;
            cursor: pointer;
            font-size: 1.5em;
        }

        .close:hover {
            color: var(--accent-color);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="dashboard.php">← Back to Dashboard</a>
        </div>

        <h2>Manage Academic Years & Semesters</h2>

        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="form-section">
            <h3>Add Academic Year</h3>
            <form method="POST">
                <input type="hidden" name="action" value="add_year">
                <div class="form-group">
                    <label>Academic Year:</label>
                    <input type="text" name="year_name" placeholder="e.g., 2023-2024" required>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="is_current" value="1" id="year_current">
                    <label for="year_current">Set as Current Academic Year</label>
                </div>
                <button type="submit">Add Academic Year</button>
            </form>
        </div>

        <div class="form-section">
            <h3>Add Semester</h3>
            <form method="POST">
                <input type="hidden" name="action" value="add_semester">
                <div class="form-group">
                    <label>Academic Year:</label>
                    <select name="academic_year_id" required>
                        <option value="">Select Academic Year</option>
                        <?php foreach ($academic_years as $year): ?>
                            <option value="<?php echo $year['id']; ?>">
                                <?php echo htmlspecialchars($year['year_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Semester Name:</label>
                    <input type="text" name="semester_name" placeholder="e.g., Semester 1" required>
                </div>
                <div class="form-group">
                    <label>Start Date:</label>
                    <input type="text" name="start_date" class="datepicker" required>
                </div>
                <div class="form-group">
                    <label>End Date:</label>
                    <input type="text" name="end_date" class="datepicker" required>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="is_current" value="1" id="semester_current">
                    <label for="semester_current">Set as Current Semester</label>
                </div>
                <button type="submit">Add Semester</button>
            </form>
        </div>

        <h3>Academic Years</h3>
        <table>
            <thead>
                <tr>
                    <th>Academic Year</th>
                    <th>Current</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($academic_years as $year): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($year['year_name']); ?></td>
                        <td><?php echo $year['is_current'] ? 'Yes' : 'No'; ?></td>
                        <td><?php echo $year['status'] ? 'Active' : 'Inactive'; ?></td>
                        <td>
                            <button onclick="editYear(<?php echo htmlspecialchars(json_encode($year)); ?>)" 
                                    class="edit-btn">Edit</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Semesters</h3>
        <table>
            <thead>
                <tr>
                    <th>Academic Year</th>
                    <th>Semester</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Current</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($semesters as $semester): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($semester['year_name']); ?></td>
                        <td><?php echo htmlspecialchars($semester['semester_name']); ?></td>
                        <td><?php echo date('Y-m-d', strtotime($semester['start_date'])); ?></td>
                        <td><?php echo date('Y-m-d', strtotime($semester['end_date'])); ?></td>
                        <td><?php echo $semester['is_current'] ? 'Yes' : 'No'; ?></td>
                        <td><?php echo $semester['status'] ? 'Active' : 'Inactive'; ?></td>
                        <td>
                            <button onclick="editSemester(<?php echo htmlspecialchars(json_encode($semester)); ?>)" 
                                    class="edit-btn">Edit</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Academic Year Modal -->
    <div id="yearModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeYearModal()">&times;</span>
            <h3>Edit Academic Year</h3>
            <form method="POST">
                <input type="hidden" name="action" value="update_year">
                <input type="hidden" name="year_id" id="edit_year_id">
                <div class="form-group">
                    <label>Academic Year:</label>
                    <input type="text" name="year_name" id="edit_year_name" required>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="is_current" value="1" id="edit_year_current">
                    <label for="edit_year_current">Set as Current Academic Year</label>
                </div>
                <div class="form-group">
                    <label>Status:</label>
                    <select name="status" id="edit_year_status">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <button type="submit">Update Academic Year</button>
            </form>
        </div>
    </div>

    <!-- Edit Semester Modal -->
    <div id="semesterModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeSemesterModal()">&times;</span>
            <h3>Edit Semester</h3>
            <form method="POST">
                <input type="hidden" name="action" value="update_semester">
                <input type="hidden" name="semester_id" id="edit_semester_id">
                <div class="form-group">
                    <label>Academic Year:</label>
                    <select name="academic_year_id" id="edit_semester_year" required>
                        <?php foreach ($academic_years as $year): ?>
                            <option value="<?php echo $year['id']; ?>">
                                <?php echo htmlspecialchars($year['year_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Semester Name:</label>
                    <input type="text" name="semester_name" id="edit_semester_name" required>
                </div>
                <div class="form-group">
                    <label>Start Date:</label>
                    <input type="text" name="start_date" id="edit_start_date" class="datepicker" required>
                </div>
                <div class="form-group">
                    <label>End Date:</label>
                    <input type="text" name="end_date" id="edit_end_date" class="datepicker" required>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="is_current" value="1" id="edit_semester_current">
                    <label for="edit_semester_current">Set as Current Semester</label>
                </div>
                <div class="form-group">
                    <label>Status:</label>
                    <select name="status" id="edit_semester_status">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <button type="submit">Update Semester</button>
            </form>
        </div>
    </div>

    <script>
        // Initialize date pickers
        flatpickr(".datepicker", {
            dateFormat: "Y-m-d"
        });

        // Academic Year Modal Functions
        function editYear(year) {
            document.getElementById('edit_year_id').value = year.id;
            document.getElementById('edit_year_name').value = year.year_name;
            document.getElementById('edit_year_current').checked = year.is_current == 1;
            document.getElementById('edit_year_status').value = year.status;
            document.getElementById('yearModal').style.display = 'flex';
        }

        function closeYearModal() {
            document.getElementById('yearModal').style.display = 'none';
        }

        // Semester Modal Functions
        function editSemester(semester) {
            document.getElementById('edit_semester_id').value = semester.id;
            document.getElementById('edit_semester_year').value = semester.academic_year_id;
            document.getElementById('edit_semester_name').value = semester.semester_name;
            document.getElementById('edit_start_date').value = semester.start_date;
            document.getElementById('edit_end_date').value = semester.end_date;
            document.getElementById('edit_semester_current').checked = semester.is_current == 1;
            document.getElementById('edit_semester_status').value = semester.status;
            document.getElementById('semesterModal').style.display = 'flex';

            // Reinitialize date pickers for modal
            flatpickr("#edit_start_date", {
                dateFormat: "Y-m-d",
                defaultDate: semester.start_date
            });
            flatpickr("#edit_end_date", {
                dateFormat: "Y-m-d",
                defaultDate: semester.end_date
            });
        }

        function closeSemesterModal() {
            document.getElementById('semesterModal').style.display = 'none';
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>