<?php
require_once("../includes/functions.php");
session_start();

?>

<!doctype html>
<html lang="fr">

<?php
$titrePage = "404";
require_once "../includes/fragments/head.php";
?>

<body class="background">
    <?php require_once "../includes/fragments/header.php"; ?>

    <div class="d-flex justify-content-center h-100">

        <div class="align-self-center mb-5 text-center">
            <h1><strong>Erreur 404 : </strong> La page demandée n'est pas disponible :'(</h1>
            <h3><a href="accueil.php">Retour vers la page d'accueil</a></h3>
        </div>
    </div>
</body>