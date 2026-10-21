<?php
include('../includes/config.php');
include('../includes/auth.php');
check_auth();
check_admin();

if(isset($_POST['name'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    mysqli_query($conn, "INSERT INTO products (name, description, price) VALUES ('$name', '$desc', '$price')");
}

if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM products WHERE id = $id");
}

$res = mysqli_query($conn, "SELECT * FROM products");
?>
<!DOCTYPE html>
<html>
<head><title>Gestion des Produits</title></head>
<body>
    <h2>Ajouter un produit</h2>
    <form method="POST">
        Nom: <input type="text" name="name"><br>
        Description: <textarea name="description"></textarea><br>
        Prix: <input type="text" name="price"><br>
        <input type="submit" value="Ajouter">
    </form>
    <h2>Liste des produits</h2>
    <table border="1">
        <tr><th>ID</th><th>Nom</th><th>Description</th><th>Prix</th><th>Action</th></tr>
        <?php while($row = mysqli_fetch_assoc($res)): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo $row['price']; ?></td>
            <td><a href="products.php?delete=<?php echo $row['id']; ?>">Supprimer</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <br><a href="index.php">Retour Admin</a>
</body>
</html>
