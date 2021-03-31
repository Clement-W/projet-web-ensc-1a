<?php
/* MODULE DE PROGRAMMATION WEB
 * Rôle du fichier :
 * Si un utilisateur n'est pas connecté, affiche la page d'accueil avec le formulaire de d'inscription.
 * Si un utilisateur est connecté, affiche la barre de recherche et le dropdown avec les 9 critères de recherche. Affiche dynamiquement les résultats d'une recherche.
 *
 *
 * Copyright 2021, MARQUETON Emma & WEINREICH Clément
 * https://ensc.bordeaux-inp.fr/fr
 *
 */

require_once("../includes/fonctionsEleve.php");
require_once("../includes/fonctionsGestionnaire.php");
require_once("../includes/fonctionsGenerales.php");
require_once("../includes/fonctionsUtilitaires.php");

session_start();

// Lorsqu'on clique sur le bouton submit pour terminer l'inscription, on appelle la fonction inscription
if (!empty($_POST["inscription"])) {
    inscription();
}

// Lorsque le gestionnaire clique sur l'onglet "modifier mot de passe", le form de la modale qui contient ce formulaire envoie une requete http post vers accueil, on appelle donc la fonction associée
if (!empty($_POST["modifierMotDePasse"])) {
    mettreAJourMotDePasse();
}

// Lorsqu'un utilisateur effectue une recherche, la fonction js qui est appelée envoie une requete http post vers accueil pour appeler la fonction php associée
if(!empty($_POST["rechercher"])){
    recupererResultatsRecherche();
}

?>

<!doctype html>
<html lang="fr">


<?php
$titrePage = "Accueil";
require_once("../includes/fragments/head.php");
?>

<body class="background">
    <?php require_once("../includes/fragments/header.php"); ?>


    <div class="container">

        <?php require_once('../includes/fragments/alert.php');
        // Si une alerte a été émise, alors elle sera affichée car on require_once alert.php. 
        if (isset($_SESSION["alert"])) { // Si une alerte est affichée dans accueil, on ne veut plus qu'elle réaparaisse plus tard donc on l'unset
            unset($_SESSION["alert"]);  // Utiliser la variable de session pour l'alerte permet de transmettre l'alerte entre les redirections de pages
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

                        <form method="POST" action="accueil.php" class="register-form" id="formInscription">
                            <h2 class="d-flex justify-content-center mt-3">Inscription</h2>
                            <div class="carousel slide marge-inscription" data-interval="false">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">

                                        <div class="form-group">
                                            <label for="prenom"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                            <input type="text" name="prenom" id="prenom" placeholder="Prénom" maxlength="50" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="nom"><i class="zmdi zmdi-account material-icons-name "></i></label>
                                            <input type="text" name="nom" id="nom" placeholder="Nom" maxlength="50" required />
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
                                            <input type="password" name="motDePasse" id="motDePasse" maxlength="50" placeholder="Mot de passe" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="promo"><i class="zmdi zmdi-email"></i></label>
                                            <input type="number" name="promo" id="promo" placeholder="Promo" min="2000" max="9999" required />
                                        </div>
                                        <div class="form-group form-button">
                                            <input type="button" class="btn btn-outline-secondary" name="signup" id="continuer1" class="form-submit" value="Continuer" />
                                        </div>
                                        <script type="text/javascript">
                                            $("#continuer1").click(() => $(".carousel").carousel("next")); // pour faire tourner la page du carousel à l'aide du bouton connecter
                                        </script>
                                    </div>
                                    <!-- Deuxième slide du formulaire d'inscription -->
                                    <div class="carousel-item">
                                        <div class="form-group">
                                            <label for="adresse"><i class="zmdi zmdi-lock"></i></label>
                                            <input type="text" name="adresse" id="adresse" placeholder="Adresse" maxlength="50" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="ville"><i class="zmdi zmdi-lock-outline"></i></label>
                                            <input type="text" name="ville" id="ville" placeholder="Ville" maxlength="50" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="codePostal"><i class="zmdi zmdi-lock-outline"></i></label>
                                            <input type="number" name="codePostal" id="codePostal" max="99999" placeholder="Code Postal" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="email"><i class="zmdi zmdi-lock-outline"></i></label>
                                            <input type="email" name="email" id="email" maxlength="50" placeholder="E-mail" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="telephone"><i class="zmdi zmdi-lock-outline"></i></label>
                                            <input type="number" name="telephone" id="telephone" max="9999999999" placeholder="Téléphone" required />
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
            <script src="../includes/fonction.js"></script>

            <div class="container d-flex">
                <div class="col">
                    <div class="row justify-content-center">
                        <h1 class="mt-5 text-center">Rechercher dans l'annuaire</h1>
                    </div>
                    <form class="row mt-5" method="post">
                        <!-- appelle la fonction js dans js/function.js -->
                        <div class="col d-flex justify-content-center barreRecherche">
                            <select name="filtreRecherche" id="filtreRecherche" class="btn btn-dark dropdown-toggle" data-toggle="dropdown">

                                <optgroup label="Par profil">
                                    <option value="Promotion">Promotion</option>
                                    <option value="NomPrenom">Nom ou prenom</option>
                                    <option value="Ville">Ville</option>
                                </optgroup>

                                <optgroup label="Par experience pro">
                                    <option value="TypeOrganisation">Type d'organisation</option>
                                    <option value="DomainesCompetences">Domaines de compétences</option>
                                    <option value="SecteursActivites">Secteurs d'activités</option>
                                    <option value="TypeExperiencePro">Type d'experience</option>
                                    <option value="LibelleOrganisation">Libelle de l'organisation</option>
                                    <option value="Region">Region</option>
                                </optgroup>


                            </select>

                            <input type="text" maxlength="200" class="form-control" id="texteRecherche" name="texteRecherche" placeholder="Entrez une recherche" onkeyup="rechercher();"> <!-- permet de rechercher dynamiquement -->
                        </div>
                    </form>

                    <div id="resultatRecherche"></div> <!-- ici s'affiche les resultats (inséré par ajax)-->

                </div>
            </div>


        <?php } ?>
    </div>
    <?php 

    ?>
</body>

</html>