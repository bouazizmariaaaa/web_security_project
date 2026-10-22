<?php
include('includes/config.php');
include('includes/database.php');
include('includes/functions.php');

$q = isset($_GET['q']) ? $_GET['q'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Recherche Sécurisée</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h2>Recherche d'événements</h2>
    <nav>
        <a href="index.php">Accueil</a> | 
        <a href="search.php">Recherche</a> | 
        <a href="profile.php?id=1">Mon Profil</a> | 
        <a href="upload.php">Upload</a>
    </nav>
    <hr>

    <form method="GET" action="search.php">
        <label>Rechercher :</label>
        <input type="text" name="q" value="<?php echo e($q); ?>">
        <input type="submit" value="Chercher">
    </form>

    <?php
    if($q !== '') {
        echo "<p>Résultats pour la recherche : <strong>" . e($q) . "</strong></p><hr>";
        
        $searchTerm = "%" . $q . "%";
        $stmt = mysqli_prepare($conn, "SELECT * FROM events WHERE title LIKE ? OR description LIKE ?");
        mysqli_stmt_bind_param($stmt, "ss", $searchTerm, $searchTerm);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        
        while($row = mysqli_fetch_assoc($res)) {
            echo "<div>";
            echo "<h3>" . e($row['title']) . "</h3>";
            echo "<p>" . e($row['description']) . "</p>";
            echo "<small>Lieu : " . e($row['location']) . " | Date : " . e($row['date']) . "</small>";
            echo "</div><hr>";
        }
    }
    ?>
    <br><a href="index.php">Retour à l'accueil</a>
</body>
</html>
