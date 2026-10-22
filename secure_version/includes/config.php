<?php
session_start();

header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");

$host = "db";
$user = "root";
$pass = "rootpassword";
$dbname = "student_events_db";

$conn = mysqli_connect($host,$user, $pass,$dbname);

if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}
?>
