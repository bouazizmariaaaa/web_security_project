<?php
include('includes/config.php');
$id = $_GET['id'];
$sql = "SELECT * FROM users WHERE id = $id";
$res = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($res);
?>
<!DOCTYPE html>
<html>
<head><title>Profil</title></head>
<body>
    <h2>Profil Utilisateur</h2>
    <?php if($user): ?>
        <p>ID : <?php echo $user['id']; ?></p>
        <p>Nom : <?php echo $user['username']; ?></p>
        <p>Email : <?php echo $user['email']; ?></p>
        <p>Rôle : <?php echo $user['role']; ?></p>
    <?php else: ?>
        <p>Utilisateur introuvable.</p>
    <?php endif; ?>
</body>
</html>
