<?php
include('includes/config.php');
include('includes/functions.php');

$error = "";

if(isset($_POST['username']) && isset($_POST['password'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];
    
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ? AND password = ?");
    mysqli_stmt_bind_param($stmt, "ss", $u, $p);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    
    if($res && mysqli_num_rows($res) > 0) {
        $user = mysqli_fetch_assoc($res);
        $_SESSION['user'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        
        header("Location: index.php");
        exit();
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Connexion Sécurisée</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h2>Connexion</h2>
    
    <?php if($error != ""): ?>
        <p style="color: red; font-weight: bold;"><?php echo e($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label>Nom d'utilisateur :</label><br>
        <input type="text" name="username" required><br>
        
        <label>Mot de passe :</label><br>
        <input type="password" name="password" required><br><br>
        
        <input type="submit" value="Se connecter">
    </form>
    
    <br><a href="index.php">Retour à l'accueil</a>
</body>
</html>
