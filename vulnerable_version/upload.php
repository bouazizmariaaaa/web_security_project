<?php
include('includes/config.php');

$message = "";
if(isset($_FILES['file'])) {
    $filename = $_FILES['file']['name'];
    $tmpname = $_FILES['file']['tmp_name'];
    
    if(move_uploaded_file($tmpname, "uploads/" . $filename)) {
        $message = "Fichier uploadé avec succès : <a href='uploads/" . htmlspecialchars($filename) . "'>uploads/" . htmlspecialchars($filename) . "</a>";
    } else {
        $message = "Erreur lors de l'envoi du fichier.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload de fichiers</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h2>Espace d'envoi de documents</h2>
    <nav>
        <a href="index.php">Accueil</a> | 
        <a href="search.php">Recherche</a> | 
        <a href="profile.php?id=1">Mon Profil</a> | 
        <a href="upload.php">Upload</a>
    </nav>
    <hr>

    <?php if($message != ""): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Sélectionner un fichier à envoyer :</label><br><br>
        <input type="file" name="file"><br><br>
        <input type="submit" value="Envoyer">
    </form>
    
    <br><a href="index.php">Retour à l'accueil</a>
</body>
</html>
