<?php
include('includes/config.php');
if(isset($_POST['username'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];
    $e = $_POST['email'];
    $sql = "INSERT INTO users (username, password, email, role) VALUES ('$u', '$p', '$e', 'user')";
    mysqli_query($conn, $sql);
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html>
<head><title>Inscription</title></head>
<body>
    <h2>Inscription</h2>
    <form method="POST">
        Nom d'utilisateur: <input type="text" name="username"><br>
        Email: <input type="email" name="email"><br>
        Mot de passe: <input type="password" name="password"><br>
        <input type="submit" value="S'inscrire">
    </form>
</body>
</html>
