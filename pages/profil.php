<?php
//fonctionne avec un parametre get, par exemple: pages/profil.php?idEleve=4
//affiche le profil en conséquent. Si le compte n'est pas validé alors seul un gestionnaire peut le voir
//Sinon affiche une 404
//On affiche pas les infos cachées par l'utilisateur en question SAUF si c'est le profil de l'utilisatur connecté actuellement ou si c'est le gestionnaire qui regarde
?>

<?php
require_once("../includes/functions.php");
session_start();

if (!estConnecte() || empty($_GET["idEleve"]) || !idEleveValide(escape($_GET["idEleve"]))) {
    // Si un utilisateur n'est pas connecté, ou que l'id eleve n'est pas valide ou pas donné en get alors on redirige vers 404 error
    redirect("404.php");
} else {

    $idEleve = escape($_GET["idEleve"]);
    $infos = getInfosCompteEleveParId($idEleve);
    $nom = $infos["Nom"];
    $prenom = $infos["Prenom"];
    $promo = $infos["Promotion"];
    $genre = $infos["Genre"];
    $adresse = $infos["Adresse"];
    $ville = $infos["Ville"];
    $codePostal = $infos["CodePostal"];
    $mail = $infos["AdresseMail"];
    $tel = $infos["NumTelephone"];

    $experiencePro = getExperiencesProParId($idEleve);

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
                <div class="d-flex justify-content-between pt-3">
                    <h2 class="ml-5 ">Profil</h2>
                    <button type="button" class="btn btn-outline-dark mr-5">Modifier</button>
                </div>
                <hr class="ml-5 mr-5" />
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
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Timeline</h6>
                                    <div id="content">
                                        <ul class="timeline">
                                            <li class="event" data-date="12:30 - 1:00pm">
                                                <h3>Registration</h3>
                                                <p>Get here on time, it's first come first serve. Be late, get turned away.</p>
                                            </li>
                                            <li class="event" data-date="2:30 - 4:00pm">
                                                <h3>Opening Ceremony</h3>
                                                <p>Get ready for an exciting event, this will kick off in amazing fashion with MOP &amp; Busta Rhymes as an opening show.</p>
                                            </li>
                                            <li class="event" data-date="5:00 - 8:00pm">
                                                <h3>Main Event</h3>
                                                <p>This is where it all goes down. You will compete head to head with your friends and rivals. Get ready!</p>
                                            </li>
                                            <li class="event" data-date="8:30 - 9:30pm">
                                                <h3>Closing Ceremony</h3>
                                                <p>See how is the victor and who are the losers. The big stage is where the winners bask in their own glory.</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            </br>

            <?php

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