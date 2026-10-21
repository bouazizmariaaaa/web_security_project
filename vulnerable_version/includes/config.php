<?php
$host = 'localhost';
$db   = 'student_events_db';
$user = 'root';
$pass = '';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Erreur de connexion");
}
session_start();
?>
