<?php
require_once("../includes/functions.php");
session_start();

?>

<!doctype html>
<html lang="fr">

<?php
$titrePage = "Validation en attente";
require_once "../includes/fragments/head.php";
?>

<body class="background">
    <?php require_once "../includes/fragments/header.php"; ?>

    <div class="d-flex justify-content-center container h-100">

        <div class="align-self-center mb-5 text-center">
            <h1>~</h1>
            <h2>Votre compte a été créé. Veuillez attendre la validation de votre compte par un gestionnaire pour pouvoir vous connecter.</h2>
            <h3><a href="accueil.php">Retour vers la page d'accueil</a></h3>
            <h1>~</h1>
        </div>



    </div>
</body>