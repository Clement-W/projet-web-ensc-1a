<?php
require_once("../includes/functions.php");
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

if (!estConnecte() || empty($_GET["idEleve"]) || !idEleveValide(escape($_GET["idEleve"]) || !(getIdEleveParNomUtilisateur($_SESSION["nomUtilisateur"]) == $_GET["idEleve"]))) {
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
            <?php require_once('../includes/fragments/alert.php'); ?>


            <div class="whitecontainer">
                <div class="d-flex justify-content-between pt-3">

                    <h2 class="ml-5 ">Modifier votre profil</h2>

                    <div class="d-flex">

                        <!-- Bouton de modification du mot de passe -->
                        <?php require_once('../includes/modals/ajouterExperience.php'); ?>
                        <button type="button" id="boutonAjouterExperience" class="btn btn-outline-primary mr-5">Ajouter une expérience</button>
                        <script type="text/javascript">
                            $('#boutonAjouterExperience').on('click', function() {
                                $('#ajouterExperience').modal('show');
                            });
                        </script>

                        <!-- Bouton de modification de profil -->
                        <?php require_once('../includes/modals/modifierMotDePasse.php'); ?>
                        <button type="button" id="boutonModifierMotDePasse" class="btn btn-outline-danger mr-5">Modifier le mot de passe</button>
                        <script type="text/javascript">
                            $('#boutonModifierMotDePasse').on('click', function() {
                                $('#modifierMotDePasse').modal('show');
                            });
                        </script>

                        
                    </div>
                </div>

                <hr class="ml-5 mr-5" />

                <p class="ml-5 h5 text-secondary"><i class="fa fa-exclamation-triangle fa-sm" style="color:black" aria-hidden="true"></i> Il est possible de rendre invisible aux yeux des autres utilisateurs certaines de vos informations. Pour cela, décochez celles que vous ne souhaitez pas montrer dans votre profil.</p>
                </br>


                <div class="d-flex flex-wrap flex-column ml-5">
                    <form method="POST" action="modifierProfil.php?idEleve=<?= getIdEleveParNomUtilisateur($_SESSION["nomUtilisateur"]); ?>">
                        <p class="h3 text-secondary"><u>Informations personnelles</u></p>
                        </br>
                        <div class="d-flex">
                            <div class="mt-2 col-sm-3">
                                <p for="nom">Nom</p>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" name="Nom" value="<?= $nom ?>" class="form-control" id="nom" required>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="mt-2 col-sm-3">
                                <p for="prenom">Prénom</p>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" name="Prenom" value="<?= $prenom ?>" class="form-control" id="prenom" required>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="custom-control custom-checkbox col-sm-3">
                                <input type="checkbox" name="GenreVisibilite" class="custom-control-input" id="genreVisibilite" <?=($visibilite["Genre"] == true) ? "checked" : "" ?>>
                                <label class="custom-control-label" for="genreVisibilite">Genre</label>
                            </div>
                            <div class="form-group col-sm-8">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="defaultInline1" name="Genre" value="Masculin" required <?=($genre == "Masculin") ? "checked" : "" ?> >
                                    <label class="custom-control-label text-secondary" for="defaultInline1">Masculin</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="defaultInline2" name="Genre" value="Féminin" <?=($genre == "Féminin") ? "checked" : "" ?>>
                                    <label class="custom-control-label text-secondary" for="defaultInline2">Féminin</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="defaultInline3" name="Genre" value="Autre" <?=($genre == "Autre") ? "checked" : "" ?>>
                                    <label class="custom-control-label text-secondary" for="defaultInline3">Autre</label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="mt-2 col-sm-3">
                                <p for="promotion">Promotion</p>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="number" name="Promotion" value="<?= $promo ?>" class="form-control" id="promotion" required>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="custom-control custom-checkbox mt-2 col-sm-3">
                                <input type="checkbox" name="AdresseVisibilite" class="custom-control-input" id="adresseVisibilite" <?=($visibilite["Adresse"] == true) ? "checked" : "" ?>  > <!-- on coche si l'utilisateur a ce parametre en visible ou non -->
                                <label class="custom-control-label" for="adresseVisibilite">Adresse</label>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" name="Adresse" value="<?= $adresse ?>" class="form-control" id="adresse" required>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="custom-control custom-checkbox mt-2 col-sm-3">
                                <input type="checkbox" name="VilleVisibilite" class="custom-control-input" id="villeVisibilite" <?=($visibilite["Ville"] == true) ? "checked" : "" ?>>
                                <label class="custom-control-label" for="villeVisibilite">Ville</label>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" name="Ville" value="<?= $ville ?>" class="form-control" id="ville" required>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="custom-control custom-checkbox mt-2 col-sm-3">
                                <input type="checkbox" name="CodePostalVisibilite" class="custom-control-input" id="codePostalVisibilite" <?=($visibilite["CodePostal"] == true) ? "checked" : "" ?> >
                                <label class="custom-control-label" for="codePostalVisibilite">Code Postal</label>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="number" name="CodePostal" value="<?= $codePostal ?>" class="form-control" id="codePostal" required>
                            </div>
                        </div>

                        <div class="d-flex">
                            <div class="custom-control custom-checkbox mt-2 col-sm-3">
                                <input type="checkbox" name="AdresseMailVisibilite" class="custom-control-input" id="adresseMailVisibilite" <?=($visibilite["AdresseMail"] == true) ? "checked" : "" ?> >
                                <label class="custom-control-label" for="adresseMailVisibilite">Adresse Mail</label>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" name="AdresseMail" value="<?= $mail ?>" class="form-control" id="adresseMail" required>
                            </div>
                        </div>

                        <div class="d-flex">
                            <div class="custom-control custom-checkbox mt-2 col-sm-3">
                                <input type="checkbox" name="NumTelephoneVisibilite" class="custom-control-input" id="numTelephoneVisibilite" <?=($visibilite["NumTelephone"] == true) ? "checked" : "" ?> >
                                <label class="custom-control-label" for="numTelephoneVisibilite">Téléphone</label>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" name="NumTelephone" value="<?= $tel ?>" class="form-control" id="telephone" required>
                            </div>
                        </div>

                        </br>
                        <hr class=" mr-5" />
                        <div class="d-flex justify-content-between pt-3">
                            <p class="h3 text-secondary"><u>Expériences professionnelles</u></p>

                        </div>

                        </br>
                        <p class="h5 text-secondary"><i class="fa fa-exclamation-triangle fa-sm" style="color:black" aria-hidden="true"></i> Les champs avec des astérisques (*) sont obligatoires. </p>
                        </br>
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

                            //On appelle pour chaque expérience le formExperiencePro avec require()
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

<?php } ?>