<?php
require_once 'config.php';

if (!isLoggedIn() || $_SESSION['role'] != 'prl') {
    header("Location: index.php");
    exit();
}

// Get filter values
$academic_year = isset($_GET['academic_year']) ? $_GET['academic_year'] : '';
$semester = isset($_GET['semester']) ? $_GET['semester'] : '';
$module = isset($_GET['module']) ? $_GET['module'] : '';
$lecturer = isset($_GET['lecturer']) ? $_GET['lecturer'] : '';

// Base query with optimized joins to prevent redundant data
$query = "
    SELECT DISTINCT
           wr.id,
           wr.chapter_covered,
           wr.learning_outcomes,
           wr.mode_of_delivery,
           wr.student_attendance,
           wr.challenges,
           wr.recommendations,
           wr.malpractice_instances,
           wr.report_date,
           wr.created_at,
           u.full_name as lecturer_name,
           m.module_name,
           c.class_name,
           ay.year_name,
           s.semester_name
    FROM weekly_reports wr
    JOIN users u ON wr.lecturer_id = u.id
    JOIN modules m ON wr.module_id = m.id
    JOIN classes c ON wr.class_id = c.id
    JOIN lecturer_assignments la ON (
        wr.lecturer_id = la.lecturer_id AND 
        wr.module_id = la.module_id AND 
        wr.class_id = la.class_id
    )
    JOIN academic_years ay ON la.academic_year_id = ay.id
    JOIN semesters s ON la.semester_id = s.id
    WHERE 1=1
";

$params = [];

if ($academic_year) {
    $query .= " AND ay.id = ?";
    $params[] = $academic_year;
}
if ($semester) {
    $query .= " AND s.id = ?";
    $params[] = $semester;
}
if ($module) {
    $query .= " AND m.id = ?";
    $params[] = $module;
}
if ($lecturer) {
    $query .= " AND u.id = ?";
    $params[] = $lecturer;
}

$query .= " ORDER BY wr.report_date DESC, wr.created_at DESC";

// Execute query
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$reports = $stmt->fetchAll();

// Get filter options with optimized queries
$stmt = $pdo->query("SELECT DISTINCT id, year_name FROM academic_years WHERE status = 1 ORDER BY year_name DESC");
$academic_years = $stmt->fetchAll();

$stmt = $pdo->query("SELECT DISTINCT id, semester_name FROM semesters WHERE status = 1 ORDER BY semester_name");
$semesters = $stmt->fetchAll();

$stmt = $pdo->query("SELECT DISTINCT id, module_name FROM modules WHERE status = 1 ORDER BY module_name");
$modules = $stmt->fetchAll();

$stmt = $pdo->query("SELECT DISTINCT id, full_name FROM users WHERE role = 'lecturer' ORDER BY full_name");
$lecturers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Reports - Limkokwing ARS</title>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #2ecc71;
            --warning-color: #f1c40f;
            --text-light: #ecf0f1;
            --text-dark: #2c3e50;
            --border-color: #ddd;
            --card-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f6fa;
            color: var(--text-dark);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: var(--card-shadow);
        }

        .filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .filter-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            background: white;
            font-size: 0.95rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            outline: none;
        }

        .report-card {
            border: 1px solid var(--border-color);
            margin-bottom: 1.5rem;
            padding: 1.5rem;
            border-radius: 8px;
            background: white;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .report-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .report-meta {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .report-meta strong {
            color: var(--primary-color);
        }

        .report-content {
            margin-top: 1.5rem;
        }

        .report-section {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .report-section h4 {
            margin: 0 0 0.75rem 0;
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .report-section p {
            margin: 0;
            line-height: 1.6;
            color: #444;
        }

        .nav {
            margin-bottom: 2rem;
        }

        .nav a {
            color: var(--secondary-color);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav a:hover {
            color: var(--primary-color);
        }

        .no-reports {
            text-align: center;
            padding: 3rem;
            color: #666;
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .filters {
                grid-template-columns: 1fr;
            }

            .report-header {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="dashboard.php">← Back to Dashboard</a>
        </div>

        <h2>Weekly Reports Overview</h2>

        <form method="GET" class="filters">
            <div class="filter-group">
                <label>Academic Year:</label>
                <select name="academic_year" onchange="this.form.submit()">
                    <option value="">All Years</option>
                    <?php foreach ($academic_years as $year): ?>
                        <option value="<?php echo $year['id']; ?>" 
                                <?php echo $academic_year == $year['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($year['year_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label>Semester:</label>
                <select name="semester" onchange="this.form.submit()">
                    <option value="">All Semesters</option>
                    <?php foreach ($semesters as $sem): ?>
                        <option value="<?php echo $sem['id']; ?>"
                                <?php echo $semester == $sem['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($sem['semester_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label>Module:</label>
                <select name="module" onchange="this.form.submit()">
                    <option value="">All Modules</option>
                    <?php foreach ($modules as $mod): ?>
                        <option value="<?php echo $mod['id']; ?>"
                                <?php echo $module == $mod['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($mod['module_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label>Lecturer:</label>
                <select name="lecturer" onchange="this.form.submit()">
                    <option value="">All Lecturers</option>
                    <?php foreach ($lecturers as $lect): ?>
                        <option value="<?php echo $lect['id']; ?>"
                                <?php echo $lecturer == $lect['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($lect['full_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <?php if (empty($reports)): ?>
            <div class="no-reports">
                <p>No reports found matching the selected criteria.</p>
            </div>
        <?php else: ?>
            <?php foreach ($reports as $report): ?>
                <div class="report-card">
                    <div class="report-header">
                        <div class="report-meta">
                            <strong>Lecturer:</strong> <?php echo htmlspecialchars($report['lecturer_name']); ?><br>
                            <strong>Module:</strong> <?php echo htmlspecialchars($report['module_name']); ?><br>
                            <strong>Class:</strong> <?php echo htmlspecialchars($report['class_name']); ?><br>
                            <strong>Academic Year:</strong> <?php echo htmlspecialchars($report['year_name']); ?><br>
                            <strong>Semester:</strong> <?php echo htmlspecialchars($report['semester_name']); ?>
                        </div>
                        <div class="report-meta">
                            <strong>Report Date:</strong> <?php echo date('d M Y', strtotime($report['report_date'])); ?><br>
                            <strong>Submitted:</strong> <?php echo date('d M Y H:i', strtotime($report['created_at'])); ?>
                        </div>
                    </div>

                    <div class="report-content">
                        <div class="report-section">
                            <h4>Chapter Covered</h4>
                            <p><?php echo htmlspecialchars($report['chapter_covered']); ?></p>
                        </div>

                        <div class="report-section">
                            <h4>Learning Outcomes</h4>
                            <p><?php echo nl2br(htmlspecialchars($report['learning_outcomes'])); ?></p>
                        </div>

                        <div class="report-section">
                            <h4>Mode of Delivery</h4>
                            <p><?php echo htmlspecialchars($report['mode_of_delivery']); ?></p>
                        </div>

                        <div class="report-section">
                            <h4>Student Attendance</h4>
                            <p><?php echo htmlspecialchars($report['student_attendance']); ?> students</p>
                        </div>

                        <div class="report-section">
                            <h4>Challenges</h4>
                            <p><?php echo nl2br(htmlspecialchars($report['challenges'])); ?></p>
                        </div>

                        <div class="report-section">
                            <h4>Recommendations</h4>
                            <p><?php echo nl2br(htmlspecialchars($report['recommendations'])); ?></p>
                        </div>

                        <?php if ($report['malpractice_instances']): ?>
                            <div class="report-section">
                                <h4>Assessment Malpractice Instances</h4>
                                <p><?php echo nl2br(htmlspecialchars($report['malpractice_instances'])); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>