<?php
include('includes/config.php');
include('includes/functions.php');

$message = "";

if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {
    $u = $_POST['username'];
    $p = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    
    $stmt = mysqli_prepare($conn, "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'user')");
    mysqli_stmt_bind_param($stmt, "sss", $u, $p, $email);
    
    if(mysqli_stmt_execute($stmt)) {
        $message = "Inscription réussie ! Vous pouvez vous connecter.";
    } else {
        $message = "Erreur lors de l'inscription.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inscription Sécurisée</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h2>Inscription</h2>
    <?php if($message != ""): ?>
        <p><?php echo e($message); ?></p>
    <?php endif; ?>
    <form method="POST" action="register.php">
        <label>Nom d'utilisateur :</label><br>
        <input type="text" name="username" required><br>
        <label>Email :</label><br>
        <input type="email" name="email" required><br>
        <label>Mot de passe :</label><br>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="S'inscrire">
    </form>
    <br><a href="index.php">Retour à l'accueil</a>
</body>
</html>
