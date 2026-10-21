<?php
include('includes/config.php');
?>
<!DOCTYPE html>
<html>
<head><title>Événements Étudiants</title></head>
<body>
    <h1>Événements à proximité</h1>
    <nav>
        <a href="index.php">Accueil</a> | 
        <a href="index.php?page=search.php">Rechercher</a> | 
        <a href="profile.php?id=1">Mon Profil</a> | 
        <a href="upload.php">Uploader un fichier</a> | 
        <a href="index.php?page=about.php">À propos</a>
        <?php if(isset($_SESSION['user'])): ?>
            <a href="logout.php">Déconnexion</a>
        <?php else: ?>
            <a href="login.php">Connexion</a> | <a href="register.php">Inscription</a>
        <?php endif; ?>
    </nav>
    <hr>
    <?php
    if(isset($_GET['page'])) {
        $file = $_GET['page'];
        include($file);
    } else {
        $query = "SELECT * FROM events";
        $result = mysqli_query($conn, $query);
        while($row = mysqli_fetch_assoc($result)) {
            echo "<div>";
            echo "<h3>" . $row['title'] . "</h3>";
            echo "<p>" . $row['description'] . "</p>";
            echo "<small>Lieu : " . $row['location'] . " | Date : " . $row['date'] . "</small>";
            echo "</div><hr>";
        }
    }
    ?>
</body>
</html>
