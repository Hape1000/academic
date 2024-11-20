<?php
require_once 'config.php';

if (isLoggedIn()) {
    logAction($_SESSION['user_id'], 'User logged out');
    session_destroy();
}

header("Location: index.php");
exit();
?>