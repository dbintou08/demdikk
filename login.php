<?php
session_start();
require_once('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_utilisateur = $_POST['nom_utilisateur'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $requete = "SELECT * FROM utilisateurs WHERE nom_utilisateur='$nom_utilisateur' AND mot_de_passe='$mot_de_passe'";
    $resultat = $connexion->query($requete);

    if ($resultat->num_rows == 1) {
        $_SESSION['nom_utilisateur'] = $nom_utilisateur;
        header("Location: accueil.php");
     
    } else {
        $message = "Nom d'utilisateur ou mot de passe incorrect.";

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <title>Connexion</title>
</head>
<body>





<form method="post" action="">
   <h2>Dem Dikk Carburant </h2>
   
 <?php if(isset($message)) { echo "<p class='error-message'>$message</p>"; } ?>

    <label for="nom_utilisateur">Nom d'utilisateur:</label>
    <input type="text" name="nom_utilisateur" placeholder="Nom Utilisateur" required><br>

    <label for="mot_de_passe">Mot de passe:</label>
    <input type="password" name="mot_de_passe" placeholder="Mot de passe"  required><br>

    <input type="submit" value="Se connecter">
    
</form>

</body>
</html>
