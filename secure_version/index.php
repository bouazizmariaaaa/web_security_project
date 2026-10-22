<?php
include('includes/config.php');
include('includes/database.php');
include('includes/functions.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Accueil - Portail Étudiant Sécurisé</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>Bienvenue sur le portail étudiant (Version Sécurisée)</h1>
    <nav>
        <a href="index.php">Accueil</a> | 
        <a href="search.php">Recherche</a> | 
        <a href="profile.php?id=<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; ?>">Mon Profil</a> | 
        <a href="upload.php">Upload</a> 
        
        <?php if(isset($_SESSION['user'])): ?>
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                | <a href="admin/index.php">Administration</a>
            <?php endif; ?>
            | <a href="logout.php">Déconnexion (<?php echo e($_SESSION['user']); ?>)</a>
        <?php else: ?>
            | <a href="login.php">Connexion</a> | <a href="register.php">Inscription</a>
        <?php endif; ?>
    </nav>
    <hr>

    <?php
    if(isset($_GET['page'])) {
        $allowed_pages = [
            'events' => 'pages/events.php',
            'home' => 'pages/home.php'
        ];
        $page = $_GET['page'];
        if(array_key_exists($page, $allowed_pages)) {
            include($allowed_pages[$page]);
        } else {
            echo "<p style='color:red;'>Erreur : Page non autorisée.</p>";
        }
    } else {
        echo "<h2>Événements à proximité</h2>";
        $sql = "SELECT * FROM events";
        $res = db_query($conn, $sql);
        while($row = mysqli_fetch_assoc($res)) {
            echo "<div>";
            echo "<h3>" . e($row['title']) . "</h3>";
            echo "<p>" . e($row['description']) . "</p>";
            echo "<small>Lieu : " . e($row['location']) . " | Date : " . e($row['date']) . "</small>";
            echo "</div><hr>";
        }
    }
    ?>
</body>
</html>
