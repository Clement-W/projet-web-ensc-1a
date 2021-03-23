<?php
require_once("../includes/functions.php");
session_start();

if (estConnecte()) {
    redirect("profil.php"); // Si on est connecté et qu'on essaie d'accéder à cette page, redirect vers la page profil.
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
                unset($_SESSION["alert"]);
            }



            ?>

            <!-- Faire le form de connexion -->

            <div class="container">

                <div class="whitecontainer flex-column d-flex justify-content-center">
                    <form method="POST" action="connexion.php" class="register-form" id="register-form">
                        <h2 class="d-flex justify-content-center mt-4">Connexion</h2>

                        <div id="carouselExampleIndicators" class="carousel slide divCarousel marge-connexion" data-interval="false">
                            <div class="carousel-inner divCarousel">
                                <div class="carousel-item active">

                                    <div class="form-group">
                                        <label for="nomUtilisateur"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                        <input type="text" name="nomUtilisateur" id="nomUtilisateur" placeholder="Nom d'utilisateur" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="motDePasse"><i class="zmdi zmdi-lock"></i></label>
                                        <input type="password" name="motDePasse" id="motDePasse" placeholder="Mot de passe" required/>
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