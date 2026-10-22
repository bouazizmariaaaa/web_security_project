<?php
include('includes/config.php');
include('includes/database.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = db_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit;
    }
    $error = "Identifiants incorrects";
}
?>
<!DOCTYPE html>
<html>
<head><title>Connexion</title></head>
<body>
    <h2>Connexion</h2>
    <?php if ($error): ?><p style="color:red"><?php echo $error; ?></p><?php endif; ?>
    <form method="POST" action="login.php">
        Nom d'utilisateur: <input type="text" name="username"><br>
        Mot de passe: <input type="password" name="password"><br>
        <input type="submit" value="Se connecter">
    </form>
</body>
</html>
