<?php
require_once("../includes/functions.php");
session_start();

if(estConnecte()){
    redirect("accueil.php");
}else{
?>

<!doctype html>
<html lang="fr">

<?php
$titrePage = "Validation en attente";
require_once "../includes/fragments/head.php";
?>

<body class="background">
    <?php require_once "../includes/fragments/header.php"; ?>

        <div class="text-center mt-5">
            <h1>~</h1>
            <h2>Votre compte a été créé. Veuillez attendre la validation de votre compte par un gestionnaire pour pouvoir vous connecter.</h2>
            <h5>Votre nom d'utilisateur correspond à la première lettre de votre prénom + nom de famille (ex : Guy Tard -> gtard)</h5>
            <h3><a href="accueil.php">Retour vers la page d'accueil</a></h3>
            <h1>~</h1>
        </div>

</body>

<?php }?>