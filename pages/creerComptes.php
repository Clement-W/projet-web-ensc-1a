
<?php
/* MODULE DE PROGRAMMATION WEB
* Rôle du fichier :
* Permet à un gestionnaire de créer un compte élève, un compte gestionnaire, ou plusieurs comptes élève d'un coup grâce à un fichier csv
*
*
* Copyright 2021, MARQUETON Emma & WEINREICH Clément
* https://ensc.bordeaux-inp.fr/fr
*
*/

require_once "../includes/functions.php";
session_start();
if (!estGestionnaire()) {
    // Si ce n'est pas un gestionnaire qui est connecté, on redirige vers 404 error
    redirect("404.php");
} else {
    // Si on clique sur le submit Créer un gestionnaire
    if (!empty($_POST["creerCompteGestionnaire"])) {
        creerCompteGestionnaire();
    }
    // Si on clique sur le submit Créer un élève
    if (!empty($_POST["creerCompteEleve"])) {
        creerCompteEleveParGestionnaire();
    }
    // Si on clique sur le submit Valider pour créer plusieurs comptes élèves
    if (!empty($_POST["validerFileUpload"])) {
        creerComptesElevesDepuisCSV();
    }
?>

    <!doctype html>
    <html lang="fr">

    <?php
    $titrePage = "Création de comptes";
    require_once "../includes/fragments/head.php";
    ?>

    <body class="background">
        <?php require_once "../includes/fragments/header.php"; ?>

        <div class="container">

            <?php require_once('../includes/fragments/alert.php');
            if (isset($_SESSION["alert"])) { // Si une alerte a été émise, alors elle sera affichée car on require_once alert.php. 
                unset($_SESSION["alert"]); // Pour ne plus l'afficher, on l'enlève de la variable de session. 
            }

            ?>

            <div class="row d-flex justify-content-center">

                <div class="col-lg-6">
                    <div class="whitecontainer flex-column d-flex justify-content-center">
                        <h2 class="d-flex justify-content-center mt-3">Créer un seul compte</h2>
                        <div class="d-flex mt-2">
                            <input type="button" class="btn btn-outline-secondary ml-4 mr-3" name="precedent" id="precedent" value="Créer un Elève" />
                            <script type="text/javascript">
                                $("#precedent").click(() => $(".carousel").carousel(0));
                            </script>
                            <input type="button" class="btn btn-outline-secondary mr-4" name="suivant" id="suivant" value="Créer un Gestionnaire" />
                            <script type="text/javascript">
                                $("#suivant").click(() => $(".carousel").carousel(1));
                            </script>


                        </div>

                        <div id="carouselExampleIndicators" class="carousel slide divCarousel marge-inscription" data-interval="false">
                            <div class="carousel-inner divCarousel">
                                <div class="carousel-item active">

                                    <!-- Formulaire pour créer un compte élève -->
                                    <form method="POST" action="creerComptes.php" class="register-form" id="register-form">

                                        <div class="form-group">
                                            <label for="prenom"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                            <input type="text" name="prenom" maxlength="50" id="prenom" placeholder="Prénom" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="nom"><i class="zmdi zmdi-account material-icons-name "></i></label>
                                            <input type="text" name="nom" maxlength="50" id="nom" placeholder="Nom" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="motDePasse"><i class="zmdi zmdi-lock"></i></label>
                                            <input type="password" maxlength="50" name="motDePasse" id="motDePasse" placeholder="Mot de passe" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="promo"><i class="zmdi zmdi-email"></i></label>
                                            <input type="number" name="promo" min="2000" max="9999" id="promo" placeholder="Promo" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="mail"><i class="zmdi zmdi-email"></i></label>
                                            <input type="email" name="mail" id="mail" maxlength="50" placeholder="Mail" required />
                                        </div>
                                        <div class="form-group form-button d-flex ">
                                            <input type="submit" class="btn btn-outline-success" name="creerCompteEleve" id="creerCompteEleve" value="Créer Elève" />
                                        </div>
                                    </form>


                                </div>


                                <div class="carousel-item">
                                    <!-- Formulaire pour créer un compte gestionnaire -->
                                    <form method="POST" action="creerComptes.php" class="register-form" id="register-form">
                                        <div class="form-group">
                                            <label for="nom"><i class="zmdi zmdi-account material-icons-name "></i></label>
                                            <input type="text" name="nomUtilisateur" maxlength="50" id="nom" placeholder="Nom d'utilisateur" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="motDePasse"><i class="zmdi zmdi-lock"></i></label>
                                            <input type="password" name="motDePasse" maxlength="50" id="motDePasse" placeholder="Mot de passe" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="email"><i class="zmdi zmdi-lock-outline"></i></label>
                                            <input type="email" name="email" id="email" maxlength="50" placeholder="E-mail" required />
                                        </div>
                                        <div class="form-group form-button d-flex ">
                                            <input type="submit" class="btn btn-outline-success" name="creerCompteGestionnaire" id="creerCompteGestionnaire" value="Créer Gestionnaire" />
                                        </div>
                                    </form>

                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                <!-- Pour créer plusieurs comptes élève -->
                <div class="col-lg-6 ">
                    <div class="whitecontainer flex-column d-flex justify-content-center">

                        <h2 class="d-flex justify-content-center mt-3 text-center">Créer plusieurs comptes Élève</h2>
                        <h4 class="d-flex justify-content-center mt-3 mr-5 ml-5 ">Télécharger le template excel (csv) permettant de créer plusieurs comptes Élève </h4>

                        <a href="../assets/creerComptes.csv" type="button" class="btn btn-outline-success mr-5 ml-5 mb-5 mt-2" download="creerComptes.csv">Télécharger</a>
                            <!-- Download permet de télécharger le template creerComptes.csv à remplir avec les informations des nouveaux comptes -->

                        <h4 class="d-flex justify-content-center mt-3 mr-5 ml-5">Vous pouvez déposer ce même fichier une fois rempli ci-dessous afin de créer plusieurs comptes</h4>

                        <form method="post" action="creerComptes.php" class="" enctype="multipart/form-data">
                            <!-- Ce enctype permet l'envoi de fichier -->

                            <div class="mt-3">
                                <input type="file" accept=".csv" id="templateUploaded" name="templateUploaded" class="file-upload" required />

                            </div>

                            <div class="mr-5 ml-5 mb-5 mt-3">
                                <input type="submit" class="btn btn-outline-success" name="validerFileUpload" id="validerFileUpload" value="Valider" />
                            </div>

                        </form>
                    </div>
                </div>
            </div>



        </div>
    </body>

    </html>
<?php } ?>