<?php
require_once("../includes/functions.php");
session_start();


if (!estConnecte() || empty($_GET["idEleve"]) || !idEleveValide(escape($_GET["idEleve"]) || !(getIdEleveParNomUtilisateur($_SESSION["nomUtilisateur"]) == $_GET["idEleve"]))) {
    // Si un utilisateur n'est pas connecté, ou que l'id eleve n'est pas valide ou pas donné en get alors on redirige vers 404 error
    //redirect("404.php");
} else {
    if (!empty($_POST["ajouterExperiencePro"])) {
        ajouterExperiencePro();
    }
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


            <div class="whitecontainer">
                <div class="d-flex justify-content-between pt-3">
                    <h2 class="ml-5 ">Modifier votre profil</h2>
                    <?php require_once('../includes/modals/modifierMotDePasse.php'); ?>
                    <button type="button" id="boutonModifierMotDePasse" class="btn btn-outline-danger mr-5">Modifier le mot de passe</button>
                    <script type="text/javascript">
                        $('#boutonModifierMotDePasse').on('click', function() {
                            $('#modifierMotDePasse').modal('show');
                        });
                    </script>
                </div>

                <hr class="ml-5 mr-5" />

                <p class="ml-5 h5 text-secondary"><i class="fa fa-exclamation-triangle fa-sm" style="color:black" aria-hidden="true"></i> Il est possible de rendre invisible aux yeux des autres utilisateurs certaines de vos informations. Pour cela, décochez celles que vous ne souhaitez pas montrer dans votre profil.</p>
                </br>


                <div class="d-flex flex-wrap flex-column ml-5">
                    <form method="POST" action="modifierProfil.php">
                        <p class="h3 text-secondary"><u>Informations personnelles</u></p>
                        </br>
                        <div class="d-flex">
                            <div class="mt-2 col-sm-3">
                                <p for="nom">Nom</p>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" value="<?= $nom ?>" class="form-control" id="nom">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="mt-2 col-sm-3">
                                <p for="prenom">Prénom</p>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" value="<?= $prenom ?>" class="form-control" id="prenom">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="custom-control custom-checkbox col-sm-3">
                                <input type="checkbox" class="custom-control-input" id="Genre">
                                <label class="custom-control-label" for="Genre">Genre</label>
                            </div>
                            <div class="form-group col-sm-8">
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
                        </div>
                        <div class="d-flex">
                            <div class="mt-2 col-sm-3">
                                <p for="promotion">Promotion</p>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="number" value="<?= $promo ?>" class="form-control" id="promotion">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="custom-control custom-checkbox mt-2 col-sm-3">
                                <input type="checkbox" class="custom-control-input" id="adresseVisibilite">
                                <label class="custom-control-label" for="adresseVisibilite">Adresse</label>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" value="<?= $adresse ?>" class="form-control" id="adresse">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="custom-control custom-checkbox mt-2 col-sm-3">
                                <input type="checkbox" class="custom-control-input" id="villeVisibilite">
                                <label class="custom-control-label" for="villeVisibilite">Ville</label>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" value="<?= $ville ?>" class="form-control" id="ville">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="custom-control custom-checkbox mt-2 col-sm-3">
                                <input type="checkbox" class="custom-control-input" id="codePostalVisibilite">
                                <label class="custom-control-label" for="codePostalVisibilite">Code Postal</label>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="number" value="<?= $codePostal ?>" class="form-control" id="codePostal">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="custom-control custom-checkbox mt-2 col-sm-3">
                                <input type="checkbox" class="custom-control-input" id="telephoneVisibilite">
                                <label class="custom-control-label" for="telephoneVisibilite">Téléphone</label>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" value="<?= $tel ?>" class="form-control" id="telephone">
                            </div>
                        </div>

                        </br>
                        <div class="d-flex justify-content-between pt-3">
                            <p class="h3 text-secondary"><u>Expériences professionnelles</u></p>
                            <?php require_once('../includes/modals/ajouterExperience.php'); ?>
                            <button type="button" id="boutonAjouterExperience" class="btn btn-outline-primary mr-5">Ajouter une expérience</button>
                            <script type="text/javascript">
                                $('#boutonAjouterExperience').on('click', function() {
                                    $('#ajouterExperience').modal('show');
                                });
                            </script>
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
                            <input type="submit" class="btn btn-success col-sm-2 mr-5 mb-3 mt-3" name="enregistrerModifProfil" id="enregistrerModifProfil" value="Enregistrer" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>

<?php } ?>