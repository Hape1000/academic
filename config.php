<?php
$host = 'fdb1030.awardspace.net';
$dbname = '4546421_limkokwing';
$username = '4546421_limkokwing';
$password = '12345678Hape';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function logAction($userId, $action) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO logs (user_id, action) VALUES (?, ?)");
    $stmt->execute([$userId, $action]);
}

// Clear any existing session if accessing login page
if (basename($_SERVER['PHP_SELF']) == 'index.php' && !isset($_POST['employee_number'])) {
    session_destroy();
    session_start();
}
?>