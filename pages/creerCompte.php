<?php
require_once "../includes/functions.php";
session_start();
if (!empty($_POST["inscription"])) {
    inscription();
}
?>

<!doctype html>
<html lang="fr">

<?php
$titrePage = "Accueil";
require_once "../includes/fragments/head.php";
?>

<body class="background">
    <?php require_once "../includes/fragments/header.php"; ?>



    <div class="container">

        <?php require_once('../includes/fragments/alert.php');
        if (isset($_SESSION["alert"])) {
            unset($_SESSION["alert"]);
        }

        ?>

        <div class="row d-flex justify-content-center">

            <div class="col-lg-6 align-item-center">
                <div class="whitecontainer flex-column d-flex justify-content-center">
                <h2 class="d-flex justify-content-center mt-3">Créer un seul compte</h2>
                    <div class="form-group form-button d-flex ">
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
                                <form method="POST" action="accueil.php" class="register-form" id="register-form">

                                    <div class="form-group">
                                        <label for="prenom"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                        <input type="text" name="prenom" id="prenom" placeholder="Prénom" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="nom"><i class="zmdi zmdi-account material-icons-name "></i></label>
                                        <input type="text" name="nom" id="nom" placeholder="Nom" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="motDePasse"><i class="zmdi zmdi-lock"></i></label>
                                        <input type="password" name="motDePasse" id="motDePasse" placeholder="Mot de passe" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="promo"><i class="zmdi zmdi-email"></i></label>
                                        <input type="number" name="promo" id="promo" placeholder="Promo" required />
                                    </div>
                                    <div class="form-group form-button d-flex ">
                                        <input type="submit" class="btn btn-outline-success" name="creerCompteEleve" id="creerCompteEleve" value="Créer Elève" />
                                    </div>
                                </form>


                            </div>

                            <div class="carousel-item">
                                <form method="POST" action="accueil.php" class="register-form" id="register-form">
                                    <div class="form-group">
                                        <label for="nom"><i class="zmdi zmdi-account material-icons-name "></i></label>
                                        <input type="text" name="nom" id="nom" placeholder="Nom" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="motDePasse"><i class="zmdi zmdi-lock"></i></label>
                                        <input type="password" name="motDePasse" id="motDePasse" placeholder="Mot de passe" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="email"><i class="zmdi zmdi-lock-outline"></i></label>
                                        <input type="email" name="email" id="email" placeholder="E-mail" required />
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

            <div class="col-lg-6 align-item-center">
                <div class="whitecontainer flex-column d-flex justify-content-center">

                    <h2 class="d-flex justify-content-center mt-3">Créer plusieurs comptes</h2>
                    <h4 class="d-flex justify-content-center mt-3 mr-5 ml-5 text-center">Vous pouvez télécharger ici le template excel permettant de créer plusieurs comptes: </h4>

                    <a href="../README.md" type="button" class="btn btn-outline-success mr-5 ml-5 mb-5 mt-2" download="README.md">Télécharger</a>
                    <div class="file-upload-wrapper">
                        <input type="file" id="input-file-now" class="file-upload" />
                    </div>
                    <script>
                        $('.file-upload').file_upload();
                    </script>
                </div>
            </div>
        </div>



    </div>
</body>

</html>