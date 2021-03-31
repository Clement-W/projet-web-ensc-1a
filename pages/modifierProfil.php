<?php
/* MODULE DE PROGRAMMATION WEB
* Rôle du fichier :
* Permet de modifier le profil d'un utilisateur. Comprend la modif des info perso, des expériences pro, et de leur visibilité. 
* Permet aussi de changer le mot de passe et d'ajouter une expérience pro au profil.
*
* Copyright 2021, MARQUETON Emma & WEINREICH Clément
* https://ensc.bordeaux-inp.fr/fr
*
*/

require_once("../includes/fonctionsUtilitaires.php");
require_once("../includes/fonctionsGenerales.php");
require_once("../includes/fonctionsEleve.php");
session_start();

//Si on appuie sur le bouton submit du form d'ajout d'une experience pro 
if (!empty($_POST["ajouterExperiencePro"])) {
    ajouterExperiencePro();
}

//Si on appuie sur le bouton submit du form de mise à jour du profil
if (!empty($_POST["mettreAJourProfil"])) {
    mettreAJourProfil();
}
//Si on appuie sur le bouton submit de la modal Modifier le mot de passe
if (!empty($_POST["modifierMotDePasse"])) {
    mettreAJourMotDePasse();
}

if (estConnecte() && !empty($_GET["idEleve"]) && idEleveValide(escape($_GET["idEleve"]) && (getIdEleveParNomUtilisateur($_SESSION["nomUtilisateur"]) == $_GET["idEleve"]))) {
    // Si un utilisateur n'est pas connecté, ou que l'id eleve n'est pas valide ou pas donné en get, alors on redirige vers 404 error

    //On nomme tous les paramètres qui peuvent nous servir pour modifier le profil afin de gagner en lisibilité
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
    $visibilite = getVisibiliteInfosProfil($idEleve);

?>

    <!doctype html>
    <html lang="fr">

    <?php
    $titrePage = "Modifier le profil";
    require_once "../includes/fragments/head.php";
    ?>

    <body class="background">
        <?php require_once "../includes/fragments/header.php"; ?>

        <div class="container">
            <?php require_once('../includes/fragments/alert.php');
            if (isset($_SESSION["alert"]) && $_SESSION["alert"]["bootstrapClassAlert"] != "success") {
                // S'il y a un alert de succes, on va être redirigé instantanément vers la page profil, donc on ne veut pas unset l'alert dans modifierprofil
                unset($_SESSION["alert"]); // Pour ne plus l'afficher, on l'enlève de la variable de session. 
            } ?>


            <div class="whitecontainer">

                <div class="d-flex justify-content-between pt-3">
                    <h2 class="ml-5 ">Modifier votre profil</h2>

                    <div class="d-flex flex-wrap">

                        <!-- Bouton d'ajout d'expérience -->
                        <?php require_once('../includes/modals/ajouterExperience.php'); ?>
                        <div>
                            <button type="button" id="boutonAjouterExperience" class="btn btn-outline-primary mr-5">Ajouter une expérience</button>
                        </div>
                        <!-- On appelle la fenêtre modale pour ajouter une expérience pro -->
                        <script type="text/javascript">
                            $('#boutonAjouterExperience').on('click', function() {
                                $('#ajouterExperience').modal('show');
                            });
                        </script>

                        <!-- Bouton de modification de mot de passe -->
                        <?php require_once('../includes/modals/modifierMotDePasse.php'); ?>
                        <div>
                            <button type="button" id="boutonModifierMotDePasse" class="btn btn-outline-danger mr-5">Modifier le mot de passe</button>
                        </div>
                        <!-- On appelle la fenêtre modale pour modifier le mot de passe -->
                        <script type="text/javascript">
                            $('#boutonModifierMotDePasse').on('click', function() {
                                $('#modifierMotDePasse').modal('show');
                            });
                        </script>
                    </div>
                </div>

                <hr class="ml-5 mr-5" />

                <p class="ml-5 h5 text-secondary mr-3"><i class="fa fa-exclamation-triangle fa-sm" style="color:black" aria-hidden="true"></i> Il est possible de rendre invisible aux yeux des autres utilisateurs certaines de vos informations. Pour cela, décochez celles que vous ne souhaitez pas montrer dans votre profil.</p>
                </br>

                <!-- Formulaire de modification des infos perso et de leur visibilité, et affichage des expérience pro -->
                <div class="d-flex flex-wrap flex-column ml-5">
                    <form method="POST" action="modifierProfil.php?idEleve=<?= getIdEleveParNomUtilisateur($_SESSION["nomUtilisateur"]); ?>">
                        <p class="h3 text-secondary"><u>Informations personnelles</u></p>
                        </br>
                        <div class="d-flex">
                            <div class="mt-2 col-sm-3">
                                <p for="nom">Nom</p>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" name="Nom" maxlength="50" value="<?= $nom ?>" class="form-control" id="nom" required>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="mt-2 col-sm-3">
                                <p for="prenom">Prénom</p>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" name="Prenom" maxlength="50" value="<?= $prenom ?>" class="form-control" id="prenom" required>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="custom-control custom-checkbox col-sm-3">
                                <input type="checkbox" name="GenreVisibilite" class="custom-control-input" id="genreVisibilite" <?= ($visibilite["Genre"] == true) ? "checked" : "" ?>>
                                <label class="custom-control-label" for="genreVisibilite">Genre</label>
                            </div>
                            <div class="form-group col-sm-8">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="genreMasculin" name="Genre" value="Masculin" required <?= ($genre == "Masculin") ? "checked" : "" ?>>
                                    <label class="custom-control-label text-secondary" for="genreMasculin">Masculin</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="genreFeminin" name="Genre" value="Féminin" <?= ($genre == "Féminin") ? "checked" : "" ?>>
                                    <label class="custom-control-label text-secondary" for="genreFeminin">Féminin</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="genreAutre" name="Genre" value="Autre" <?= ($genre == "Autre") ? "checked" : "" ?>>
                                    <label class="custom-control-label text-secondary" for="genreAutre">Autre</label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="mt-2 col-sm-3">
                                <p for="promotion">Promotion</p>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="number" name="Promotion" min="2000" max="9999" value="<?= $promo ?>" class="form-control" id="promotion" required>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="custom-control custom-checkbox mt-2 col-sm-3">
                                <input type="checkbox" name="AdresseVisibilite" class="custom-control-input" id="adresseVisibilite" <?= ($visibilite["Adresse"] == true) ? "checked" : "" ?>> <!-- on coche si l'utilisateur a ce parametre en visible ou non -->
                                <label class="custom-control-label" for="adresseVisibilite">Adresse</label>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" name="Adresse" maxlength="50" value="<?= $adresse ?>" class="form-control" id="adresse" required>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="custom-control custom-checkbox mt-2 col-sm-3">
                                <input type="checkbox" name="VilleVisibilite" class="custom-control-input" id="villeVisibilite" <?= ($visibilite["Ville"] == true) ? "checked" : "" ?>>
                                <label class="custom-control-label" for="villeVisibilite">Ville</label>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" name="Ville" maxlength="50" value="<?= $ville ?>" class="form-control" id="ville" required>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="custom-control custom-checkbox mt-2 col-sm-3">
                                <input type="checkbox" name="CodePostalVisibilite" class="custom-control-input" id="codePostalVisibilite" <?= ($visibilite["CodePostal"] == true) ? "checked" : "" ?>>
                                <label class="custom-control-label" for="codePostalVisibilite">Code Postal</label>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="number" name="CodePostal" max="99999" value="<?= $codePostal ?>" class="form-control" id="codePostal" required>
                            </div>
                        </div>

                        <div class="d-flex">
                            <div class="custom-control custom-checkbox mt-2 col-sm-3">
                                <input type="checkbox" name="AdresseMailVisibilite" class="custom-control-input" id="adresseMailVisibilite" <?= ($visibilite["AdresseMail"] == true) ? "checked" : "" ?>>
                                <label class="custom-control-label" for="adresseMailVisibilite">Adresse Mail</label>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" name="AdresseMail" maxlength="50" value="<?= $mail ?>" class="form-control" id="adresseMail" required>
                            </div>
                        </div>

                        <div class="d-flex">
                            <div class="custom-control custom-checkbox mt-2 col-sm-3">
                                <input type="checkbox" name="NumTelephoneVisibilite" class="custom-control-input" id="numTelephoneVisibilite" <?= ($visibilite["NumTelephone"] == true) ? "checked" : "" ?>>
                                <label class="custom-control-label" for="numTelephoneVisibilite">Téléphone</label>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" name="NumTelephone" max="9999999999" value="<?= $tel ?>" class="form-control" id="telephone" required>
                            </div>
                        </div>

                        </br>

                        <!-- Affichage des expérience pro -->
                        <hr class="mr-5" />
                        <div class="d-flex justify-content-between pt-3">
                            <p class="h3 text-secondary"><u>Expériences professionnelles</u></p>
                        </div>

                        </br>
                        <p class="h5 text-secondary"><i class="fa fa-exclamation-triangle fa-sm" style="color:black" aria-hidden="true"></i> Les champs avec des astérisques (*) sont obligatoires. </p>
                        </br>

                        <!-- On boucle sur les expériences pro stockées dans la variable $experiencePro -->
                        <?php foreach ($experiencePro as $expPro) {
                            $intituleExp = $expPro["IntituleExperiencePro"];
                            $typeExp = $expPro["TypeExperiencePro"];
                            $dateDebut = $expPro["DateDebut"];
                            $dateFin = $expPro["DateFin"];
                            $typeOrganisation = $expPro["TypeOrganisation"];
                            $libelleOrganisation = $expPro["LibelleOrganisation"];
                            $typePoste = $expPro["TypePoste"];
                            $region = $expPro["Region"];
                            $villeExperience = $expPro["Ville"];
                            $secteursActivites = $expPro["SecteursActivites"];
                            $domainesCompetences = $expPro["DomainesCompetences"];
                            $description = $expPro["Description"];
                            $salaire = $expPro["Salaire"];
                            $idExpPro = $expPro["IdExperiencePro"];

                            //On appelle pour chaque expérience le formExperiencePro avec require() pour pouvoir en avoir plusieurs
                            require("../includes/fragments/formExperiencePro.php");
                        ?>

                        <?php } ?>
                        <div class="d-flex justify-content-end ">
                            <input type="submit" class="btn btn-success col-sm-2 mr-5 mb-3 mt-3" name="mettreAJourProfil" id="mettreAJourProfil" value="Enregistrer" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>

<?php }else{
    redirect("404.php");
} ?>