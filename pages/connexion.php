<?php
require_once("../includes/functions.php");
session_start();

// Si on appuie sur le bouton de connexion 
if (!empty($_POST["connexion"])) {
    $alert = connexion();
}
?>

<!doctype html>
<html lang="fr">


<?php
$titrePage = "Connexion";
require_once "../includes/fragments/head.php";
?>

<body class="background">
    <?php require_once "../includes/fragments/header.php"; ?>

    <div class="container">

        <?php require_once('../includes/fragments/alert.php'); ?>

        <!-- Faire le form de connexion --> 
        form de connexion

</body>