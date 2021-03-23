<?php
//fonctionne avec un parametre get, par exemple: pages/profil.php?idEleve=4
//affiche le profil en conséquent. Si le compte n'est pas validé alors seul un gestionnaire peut le voir
//Sinon affiche une 404
//On affiche pas les infos cachées par l'utilisateur en question SAUF si c'est le profil de l'utilisatur connecté actuellement ou si c'est le gestionnaire qui regarde
?>

<?php
require_once("../includes/functions.php");
session_start();
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

        <div class="whitecontainer d-flex">
            <div class="d-flex flex-column">
                <h2 class=" ml-5 pt-4">Profil</h2>

                <div class="container d-flex justify-content-between ml-5 flex-wrap">
                    <div class="d-flex flex-column">
                        <div><?= $nom ?></div>
                        <div><?= $prenom ?></div>
                        <div><?= $promo ?></div>
                        <div><?= $genre ?></div>
                    </div>
                    <div class="d-flex flex-column">
                        <div><?= $adresse ?></div>
                        <div><?= $ville ?></div>
                        <div><?= $codePostal ?></div>
                        <div><?= $mail ?></div>
                        <div><?= $tel ?></div>
                    </div>

                </div>
            </div>
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