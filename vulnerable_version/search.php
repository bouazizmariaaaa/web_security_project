<?php
include('includes/config.php');
?>
<!DOCTYPE html>
<html>
<head><title>Recherche</title></head>
<body>
    <h2>Recherche d'événements</h2>
    <form method="GET">
        Rechercher: <input type="text" name="q">
        <input type="submit" value="Chercher">
    </form>
    <?php
    if(isset($_GET['q'])) {
        $q = $_GET['q'];
        echo "<p>Résultats pour la recherche : " . $q . "</p>";
        $sql = "SELECT * FROM events WHERE title LIKE '%$q%' OR description LIKE '%$q%'";
        $res = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($res)) {
            echo "<div><strong>" . $row['title'] . "</strong> - " . $row['location'] . "</div>";
        }
    }
    ?>
</body>
</html>
