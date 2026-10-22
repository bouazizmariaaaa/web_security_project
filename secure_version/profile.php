<?php
include('includes/config.php');
include('includes/functions.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$profile_id = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['user_id'];

if ($_SESSION['user_id'] != $profile_id && $_SESSION['role'] !== 'admin') {
    die("<h2 style='color:red;'>Accès refusé : Vous n'avez pas l'autorisation de voir ce profil.</h2><a href='index.php'>Retour</a>");
}

$stmt = mysqli_prepare($conn, "SELECT username, email, role FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $profile_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($res);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mon Profil Sécurisé</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h2>Page de Profil</h2>
    <nav>
        <a href="index.php">Accueil</a> | 
        <a href="search.php">Recherche</a> | 
        <a href="profile.php?id=<?php echo $_SESSION['user_id']; ?>">Mon Profil</a> | 
        <a href="upload.php">Upload</a>
    </nav>
    <hr>

    <?php if($user): ?>
        <p><strong>Nom d'utilisateur :</strong> <?php echo e($user['username']); ?></p>
        <p><strong>Email :</strong> <?php echo e($user['email']); ?></p>
        <p><strong>Rôle :</strong> <?php echo e($user['role']); ?></p>
    <?php else: ?>
        <p>Utilisateur introuvable.</p>
    <?php endif; ?>

    <br><a href="index.php">Retour à l'accueil</a>
</body>
</html>
