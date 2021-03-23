<?php
//fonctionne avec un parametre get, par exemple: pages/profil.php?idEleve=4
//affiche le profil en conséquent. Si le compte n'est pas validé alors seul un gestionnaire peut le voir
//Sinon affiche une 404
//On affiche pas les infos cachées par l'utilisateur en question SAUF si c'est le profil de l'utilisatur connecté actuellement ou si c'est le gestionnaire qui regarde
?>

<?php
require_once("../includes/functions.php");
session_start();

if (!estConnecte()) {
    redirect("404.php");
} else {

    $infos = getInfosPerso();
    $nom = $infos["Nom"];
    $prenom = $infos["Prenom"];
    $promo = $infos["Promotion"];
    $genre = $infos["Genre"];
    $adresse = $infos["Adresse"];
    $ville = $infos["Ville"];
    $codePostal = $infos["CodePostal"];
    $mail = $infos["AdresseMail"];
    $tel = $infos["NumTelephone"];

    $experiencePro = getExperiencesPro();

?>

    <!doctype html>
    <html lang="fr">

    <?php
    $titrePage = "Profil";
    require_once "../includes/fragments/head.php";
    ?>

    <body class="background">
        <?php require_once "../includes/fragments/header.php"; ?>
        <div class="container">
            <?php require_once('../includes/fragments/alert.php'); ?>

            <!-- Faire le form de connexion -->


            <div class="whitecontainer">
                <h2 class="ml-5 pt-3">Profil</h2>
                <hr />
                <div class="ml-4 row text-secondary">
                    <div class="col-md-6 h4">
                        <div class="col-md-12">
                            <div class="affichage"><?= $prenom ?> <?= $nom ?></div>
                            <div class="affichage">Promotion <?= $promo ?></div>
                            <div class="affichage">Genre: <?= $genre ?></div>
                        </div>
                    </div>
                </div>
                <hr class="ml-5 mr-5" />
                <div class="ml-4 row text-secondary">
                    <div class="col-md-6 h5">
                        <div class="col-md-12">
                            <div class="affichage text-underline-auto"><u>Contact</u>: </div>
                            <div class="affichage"><?= $mail ?></div>
                            <div class="affichage"><?= $tel ?></div>
                        </div>
                    </div>
                    <div class="col-md-6 h5">
                        <div class="col-md-12">
                            <div class="affichage"><u>Adresse</u>: </div>
                            <div class="affichage"><?= $adresse ?></div>
                            <div class="affichage"><?= $ville ?></div>
                            <div class="affichage"><?= $codePostal ?></div>
                        </div>
                    </div>
                </div>
                <hr class="ml-5 mr-5" />

            </div>
            </br>

            <?php
            require_once "../includes/fragments/head.php";

            print_r(getInfosPerso());
            echo "</br>";
            echo "</br>";
            print_r(getExperiencesPro());
            echo "</br>";
            echo "</br>";
            ?>


        </div>
    </body>

<?php } ?>