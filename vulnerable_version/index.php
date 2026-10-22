<?php
include('includes/config.php');
include('includes/database.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Accueil - Événements</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>Bienvenue sur le portail étudiant</h1>
    
    <nav>
        <a href="index.php">Accueil</a> | 
        <a href="search.php">Recherche</a> | 
        <a href="profile.php?id=1">Mon Profil</a> | 
        <a href="upload.php">Upload</a> 
        
        <?php if(isset($_SESSION['user'])): ?>
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                | <a href="admin/index.php">Administration</a>
            <?php endif; ?>
            | <a href="logout.php">Déconnexion (<?php echo $_SESSION['user']; ?>)</a>
        <?php else: ?>
            | <a href="login.php">Connexion</a> | <a href="register.php">Inscription</a>
        <?php endif; ?>
    </nav>
    <hr>

    <?php
    if(isset($_GET['page'])) {
        include($_GET['page']); 
    } else {
        echo "<h2>Événements à proximité</h2>";
        $sql = "SELECT * FROM events";
        $res = db_query($conn, $sql);
        while($row = mysqli_fetch_assoc($res)) {
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
