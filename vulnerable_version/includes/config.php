<?php
$host = 'localhost';
$db   = 'student_events';
$user = 'root';
$pass = '';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed");
}

session_start();
?>
