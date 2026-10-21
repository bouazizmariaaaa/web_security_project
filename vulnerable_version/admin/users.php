<?php
include('../includes/config.php');
include('../includes/auth.php');
check_auth();
check_admin();
?>
<!DOCTYPE html>
<html>
<head><title>Administration</title></head>
<body>
    <h1>Panneau d'administration</h1>
    <ul>
        <li><a href="users.php">Gérer les utilisateurs</a></li>
        <li><a href="products.php">Gérer les produits</a></li>
    </ul>
    <br><a href="../index.php">Retour au site</a>
</body>
</html>
