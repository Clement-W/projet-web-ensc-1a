<?php
require_once("../includes/functions.php");
session_start();

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
                    <h2 class="ml-5 ">Modifier votre profil</h2>
                    <!-- On affiche le bouton "Modifier" seulement si c'est le profil de l'utilisateur connecté-->
                    <a href="" class="btn btn-outline-danger mr-5" type="button">Modifier le mot de passe</a>
                </div>

                <hr class="ml-5 mr-5" />

                <p class="ml-5 h5 text-secondary"><i class="fa fa-exclamation-triangle fa-sm" style="color:red" aria-hidden="true"></i> Il est possible de rendre invisible aux yeux des autres utilisateurs certaines de vos informations. Pour cela, décochez celles que vous ne souhaitez pas montrer dans votre profil.</p>
                </br>


                <div class="d-flex flex-wrap flex-column ml-5">
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
                            <label class="custom-control-label" for="adresse">Adresse</label>
                        </div>
                        <div class="form-group col-sm-8">
                            <input type="text" value="<?= $adresse ?>" class="form-control" id="adresse">
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="custom-control custom-checkbox mt-2 col-sm-3">
                            <input type="checkbox" class="custom-control-input" id="villeVisibilite">
                            <label class="custom-control-label" for="ville">Ville</label>
                        </div>
                        <div class="form-group col-sm-8">
                            <input type="text" value="<?= $ville ?>" class="form-control" id="ville">
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="custom-control custom-checkbox mt-2 col-sm-3">
                            <input type="checkbox" class="custom-control-input" id="codePostalVisibilite">
                            <label class="custom-control-label" for="codePostal">Code Postal</label>
                        </div>
                        <div class="form-group col-sm-8">
                            <input type="number" value="<?= $codePostal ?>" class="form-control" id="codePostal">
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="custom-control custom-checkbox mt-2 col-sm-3">
                            <input type="checkbox" class="custom-control-input" id="telephoneVisibilite">
                            <label class="custom-control-label" for="telephone">Téléphone</label>
                        </div>
                        <div class="form-group col-sm-8">
                            <input type="text" value="<?= $tel ?>" class="form-control" id="telephone">
                        </div>
                    </div>

                    </br>
                    <p class="h3 text-secondary"><u>Expériences professionnelles</u></p>
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
                        $ville = $expPro["Ville"];
                        $secteursActivites = $expPro["SecteursActivites"];
                        $domainesCompetences = $expPro["DomainesCompetences"];
                        $description = $expPro["Description"];
                        $salaire = $expPro["Salaire"];
                        $idExpPro = $expPro["IdExperiencePro"];

                    ?>
                        <div class="d-flex">
                            <div class="custom-control custom-checkbox mt-2 col-sm-3">
                                <input type="checkbox" class="custom-control-input" id="intituleVisibilite<?= $idExpPro ?>">
                                <label class="custom-control-label" for="intitule">Intitulé de l'expérience</label>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" value="<?= $intituleExp ?>" class="form-control" id="intitule<?= $idExpPro ?>">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="mt-2 col-sm-3">
                                <p class="ml-5">Type d'expérience</p>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" value="<?= $typeExp ?>" class="form-control" id="typeExp">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="mt-2 col-sm-3">
                                <p class="ml-5">Date de début</p>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="date" value="<?= $dateDebut ?>" class="form-control" id="dateDebut<?= $dateDebut ?>">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="mt-2 col-sm-3">
                                <p class="ml-5">Date de fin</p>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="date" value="<?= $dateDebut ?>" class="form-control" id="dateFin<?= $dateFin ?>">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="mt-2 col-sm-3">
                                <p class="ml-5">Type d'organisation</p>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" value="<?= $typeOrganisation ?>" class="form-control" id="typeOrganisation<?= $typeOrganisation ?>">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="mt-2 col-sm-3">
                                <p class="ml-5">Libellé de l'organisation</p>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" value="<?= $libelleOrganisation ?>" class="form-control" id="libelleOrganisation<?= $libelleOrganisation ?>">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="mt-2 col-sm-3">
                                <p class="ml-5">Type de poste</p>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" value="<?= $typePoste ?>" class="form-control" id="typePoste<?= $typePoste ?>">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="mt-2 col-sm-3">
                                <p class="ml-5">Région</p>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" value="<?= $region ?>" class="form-control" id="region<?= $region ?>">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="mt-2 col-sm-3">
                                <p class="ml-5">Ville</p>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" value="<?= $ville ?>" class="form-control" id="ville<?= $ville ?>">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="mt-2 col-sm-3">
                                <p class="ml-5">Secteur(s) d'activité</p>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" value="<?= $secteursActivites ?>" class="form-control" id="secteursActivites<?= $secteursActivites ?>">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="mt-2 col-sm-3">
                                <p class="ml-5">Domaine(s) de compétence</p>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" value="<?= $domainesCompetences ?>" class="form-control" id="domainesCompetences<?= $domainesCompetences ?>">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="mt-2 col-sm-3">
                                <p class="ml-5">Description</p>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="text" value="<?= $description ?>" class="form-control" id="description<?= $description ?>">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="mt-2 col-sm-3">
                                <p class="ml-5">Salaire</p>
                            </div>
                            <div class="form-group col-sm-8">
                                <input type="number" value="<?= $salaire ?>" class="form-control" id="salaire<?= $salaire ?>">
                            </div>
                        </div>

                        <hr class="ml-5 mr-5" style="border: 1px solid grey;" />
                    <?php } ?>
                </div>
                <div class="d-flex justify-content-end ">
                    <input type="submit" class="btn btn-success col-sm-2 mr-5 mb-3 mt-3" name="enregistrerModifProfil" id="enregistrerModifProfil" value="Enregistrer" />
                </div>
            </div>
        </div>
    </body>

<?php } ?>