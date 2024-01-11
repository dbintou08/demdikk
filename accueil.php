<?php
session_start();
if (!isset($_SESSION['nom_utilisateur'])) {
    header("Location: login.php");
    exit();
}

$nom_utilisateur = $_SESSION['nom_utilisateur'];

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="accueil.css">
    <title>Convertisseur de fichier</title>
</head>
<body>
   
     
<div class="container">
    <img src="logo.png" alt="Logo" class="logo">
    
        <h1>Convertisseur de fichier</h1>

        <form action="traitement.php" method="post" enctype="multipart/form-data">
            <label for="file">Sélectionnez votre fichier texte :</label>
            <input type="file" name="file" id="file" accept=".txt" required>
            <br>
            <input type="submit" name="submit" value="Téléverser et Convertir">
        </form>
    </body>
    </html>

    <a href="logout.php">Se déconnecter</a>
</div>

</body>
</html>
