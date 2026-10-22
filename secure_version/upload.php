<?php
include('includes/config.php');
include('includes/functions.php');

$message = "";

if(isset($_FILES['file'])) {
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_size = $_FILES['file']['size'];
    
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];
    $max_size = 2 * 1024 * 1024;

    if (in_array($file_ext, $allowed_extensions) && $file_size <= $max_size) {
        $new_file_name = uniqid('secure_', true) . '.' . $file_ext;
        $upload_destination = "uploads/" . $new_file_name;
        
        if (move_uploaded_file($file_tmp, $upload_destination)) {
            $message = "Fichier envoyé et sécurisé avec succès : " . e($new_file_name);
        } else {
            $message = "Erreur lors de l'enregistrement du fichier.";
        }
    } else {
        $message = "Erreur : Type de fichier non autorisé ou fichier trop volumineux (Max 2Mo, formats autorisés : JPG, PNG, PDF).";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload Sécurisé</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h2>Espace d'envoi de documents sécurisé</h2>
    <nav>
        <a href="index.php">Accueil</a> | 
        <a href="search.php">Recherche</a> | 
        <a href="profile.php?id=<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; ?>">Mon Profil</a> | 
        <a href="upload.php">Upload</a>
    </nav>
    <hr>

    <?php if($message != ""): ?>
        <p><?php echo e($message); ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Sélectionner un document (JPG, PNG, PDF - Max 2Mo) :</label><br><br>
        <input type="file" name="file" required><br><br>
        <input type="submit" value="Envoyer">
    </form>
    
    <br><a href="index.php">Retour à l'accueil</a>
</body>
</html>
