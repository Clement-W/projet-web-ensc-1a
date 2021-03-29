/* MODULE DE PROGRAMMATION WEB
* Rôle du fichier :
* Affichage d'un message d'erreur lorsqu'une page n'est pas disponible
*
*
* Copyright 2021, MARQUETON Emma & WEINREICH Clément
* https://ensc.bordeaux-inp.fr/fr
*
*/

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

    <div class="text-center mt-5">
        <h1><strong>Erreur 404 : </strong> La page demandée n'est pas disponible :'(</h1>
        <h3><a href="accueil.php">Retour vers la page d'accueil</a></h3>
    </div>
</body>