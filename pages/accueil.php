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

        <?php if (!estConnecte()) { ?>
            <!-- Si l'utilisateur n'est pas connecté on affiche la page d'accueil avec le form d'inscription -->

            <div class="row d-flex justify-content-center">
                <div class="col-lg-6 align-self-center">
                    <div class="small-12 large-6 columns">
                        <h1 class="text-center">Bienvenue sur l'annuaire des élèves de l'ENSC !</h1>
                    </div>
                </div>

                <div class="col-lg-6 align-item-center">
                    <div class="whitecontainer flex-column d-flex justify-content-center">

                        <form method="POST" action="accueil.php" class="register-form" id="register-form">
                            <h2 class="d-flex justify-content-center mt-3">Inscription</h2>
                            <div id="carouselExampleIndicators" class="carousel slide divCarousel marge-inscription" data-interval="false">
                                <div class="carousel-inner divCarousel">
                                    <div class="carousel-item active">

                                        <div class="form-group">
                                            <label for="prenom"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                            <input type="text" name="prenom" id="prenom" placeholder="Prénom" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="nom"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                            <input type="text" name="nom" id="nom" placeholder="Nom" required />
                                        </div>
                                        <div class="form-group mt-5">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" id="defaultInline1" name="genre" value="Masculin" required>
                                                <label class="custom-control-label text-secondary" for="defaultInline1">Masculin</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" id="defaultInline2" name="genre" value="Féminin">
                                                <label class="custom-control-label text-secondary" for="defaultInline2">Féminin</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" id="defaultInline3" name="genre" value="Autre">
                                                <label class="custom-control-label text-secondary" for="defaultInline3">Autre</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="motDePasse"><i class="zmdi zmdi-lock"></i></label>
                                            <input type="password" name="motDePasse" id="motDePasse" placeholder="Mot de passe" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="promo"><i class="zmdi zmdi-email"></i></label>
                                            <input type="number" name="promo" id="promo" placeholder="Promo" required />
                                        </div>
                                        <div class="form-group form-button">
                                            <input type="button" class="btn btn-outline-secondary" name="signup" id="continuer1" class="form-submit" value="Continuer" />
                                        </div>
                                        <script type="text/javascript">
                                            $("#continuer1").click(() => $(".carousel").carousel("next"));
                                        </script>
                                    </div>

                                    <div class="carousel-item">
                                        <div class="form-group">
                                            <label for="adresse"><i class="zmdi zmdi-lock"></i></label>
                                            <input type="text" name="adresse" id="adresse" placeholder="Adresse" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="ville"><i class="zmdi zmdi-lock-outline"></i></label>
                                            <input type="text" name="ville" id="ville" placeholder="Ville" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="codePostal"><i class="zmdi zmdi-lock-outline"></i></label>
                                            <input type="number" name="codePostal" id="codePostal" placeholder="Code Postal" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="email"><i class="zmdi zmdi-lock-outline"></i></label>
                                            <input type="email" name="email" id="email" placeholder="E-mail" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="telephone"><i class="zmdi zmdi-lock-outline"></i></label>
                                            <input type="number" name="telephone" id="telephone" placeholder="Téléphone" required />
                                        </div>
                                        <div class="form-group form-button d-flex ">
                                            <input type="button" class="btn btn-outline-secondary mr-1" name="precedent" id="precedent" value="Précédent" />
                                            <input type="submit" class="btn btn-outline-success" name="inscription" id="inscription" value="Terminer" />
                                        </div>
                                        <script type="text/javascript">
                                            $("#precedent").click(() => $(".carousel").carousel("prev"));
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        <?php } else { ?>
            <!-- Si l'utilisateur est connecté, on lui affiche la page d'accueil d'un utilisateur connecte -->
            <script src="../js/recherche.js"></script>
            <link type="text/css" rel="stylesheet" href="../css/search_style.css" />

            <div id="wrapper">
                <h1> Test barre de recherche</h1>
                <p> Vous pouvez chercher par promo, organisation, secteur d'activité, etc.</p>


                <div id="search_box">
                    <form method="post" onsubmit="return do_search();">
                        <select name="search_param" id="search_param" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                            <option value="TypeOrganisation">Type d'organisation</option>
                            <option value="DomainesCompetences">Domaines de compétences</option>
                            <option value="SecteursActivites">Secteurs d'activités</option>
                        </select>
                        <input type="text" id="search_term" name="search_term" placeholder="Entrez une recherche" onkeyup="do_search();"> <!-- enlever le onkeyup pour pas que ca recherche tout seul -->
                        <input type="submit" name="search" value="SEARCH">
                    </form>
                </div>

                <div id="resultat_recherche"></div>

            </div>


        <?php } ?>
    </div>
</body>

</html>