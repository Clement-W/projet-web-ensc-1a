<?php
/* MODULE DE PROGRAMMATION WEB
* Rôle du fichier :
* Permet de se connecter à son compte utilisateur ou gestionnaire.
*
*
* Copyright 2021, MARQUETON Emma & WEINREICH Clément
* https://ensc.bordeaux-inp.fr/fr
*
*/

require_once("../includes/fonctionsUtilitaires.php");
require_once("../includes/fonctionsGenerales.php");
session_start();

if (estConnecte()) {
    redirect("profil.php"); // Si on est déjà connecté et qu'on essaie d'accéder à cette page, redirect vers la page profil.
} else {
    // Si on appuie sur le bouton de connexion 
    if (!empty($_POST["connexion"])) {
        connexion();
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

            <?php require_once('../includes/fragments/alert.php');
            if (isset($_SESSION["alert"]) && $_SESSION["alert"]["bootstrapClassAlert"] != "success") {
                // S'il y a un alert de succes, on va être redirigé instantanément vers la page d'accueil, donc on ne veut pas unset l'alert dans connexion
                unset($_SESSION["alert"]); // Pour ne plus l'afficher, on l'enlève de la variable de session. 
            }



            ?>

            <!-- Formulaire de connexion -->

            <div class="container">

                <div class="whitecontainer flex-column d-flex justify-content-center">
                    <form method="POST" action="connexion.php" class="register-form" id="register-form">
                        <h2 class="d-flex justify-content-center mt-4">Connexion</h2>

                        <div id="carouselExampleIndicators" class="carousel slide divCarousel marge-connexion" data-interval="false">
                            <div class="carousel-inner divCarousel">
                                <div class="carousel-item active">

                                    <div class="form-group">
                                        <label for="nomUtilisateur"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                        <input type="text" maxlength="50" name="nomUtilisateur" id="nomUtilisateur" placeholder="Nom d'utilisateur" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="motDePasse"><i class="zmdi zmdi-lock"></i></label>
                                        <input type="password" maxlength="50" name="motDePasse" id="motDePasse" placeholder="Mot de passe" required />
                                    </div>
                                    <div class="form-group form-button">
                                        <input type="submit" class="btn btn-outline-success" name="connexion" id="connexion" value="Terminer" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


    </body>
<?php } ?>