<?php
/* MODULE DE PROGRAMMATION WEB
* Rôle du fichier :
* Signale que la demande d'inscription a été prise en compte et qu'il faut attendre la validation du compte par un gestionnaire
*
*
* Copyright 2021, MARQUETON Emma & WEINREICH Clément
* https://ensc.bordeaux-inp.fr/fr
*
*/

require_once("../includes/fonctionsUtilitaires.php");
require_once("../includes/fonctionsGenerales.php");
session_start();

// Si un utilisateur déjà connecté assez d'accéder à cette page ou que le nom d'utilisateur n'est pas valide, on redirige vers l'accueil du site 
if (estConnecte() || !isset($_SESSION["nomUtilisateurCompteNonValide"])) {
    redirect("accueil.php");
} else {
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

            <!-- On affiche le nom du nouvel utilisateur pour qu'il puisse se connecter quand son compte sera validé -->
            <h5>Votre nom d'utilisateur est <strong> <?= $_SESSION["nomUtilisateurCompteNonValide"] ?> </strong> </h5>
            <h3><a href="accueil.php">Retour vers la page d'accueil</a></h3>
            <h1>~</h1>
        </div>

    </body>

<?php } ?>