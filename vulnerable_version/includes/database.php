<?php
$host = 'db';
$db   = 'student_events_db';
$user = 'root';
$pass = 'rootpassword';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

function db_query($connection, $sql) {
    return mysqli_query($connection, $sql);
}
?>
