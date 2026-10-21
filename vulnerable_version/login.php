<?php
include('includes/config.php');
if(isset($_POST['username']) && isset($_POST['password'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];
    $sql = "SELECT * FROM users WHERE username = '$u' AND password = '$p'";
    $res = mysqli_query($conn, $sql);
    if(mysqli_num_rows($res) > 0) {
        $user = mysqli_fetch_assoc($res);
        $_SESSION['user'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
    } else {
        $error = "Identifiants invalides";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Connexion</title></head>
<body>
    <h2>Connexion</h2>
    <?php if(isset($error)) echo "<p>$error</p>"; ?>
    <form method="POST">
        Nom d'utilisateur: <input type="text" name="username"><br>
        Mot de passe: <input type="password" name="password"><br>
        <input type="submit" value="Se connecter">
    </form>
</body>
</html>
