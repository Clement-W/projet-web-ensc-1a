<?php
//fonctionne avec un parametre get, par exemple: pages/profil.php?idEleve=4
//affiche le profil en conséquent. Si le compte n'est pas validé alors seul un gestionnaire peut le voir
//Sinon affiche une 404
//On affiche pas les infos cachées par l'utilisateur en question SAUF si c'est le profil de l'utilisatur connecté actuellement ou si c'est le gestionnaire qui regarde
?>

<?php
require_once("../includes/functions.php");
session_start();

if (!estConnecte() || empty($_GET["idEleve"]) || !idEleveValide(escape($_GET["idEleve"]))) {
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



            <div class="whitecontainer">
                <div class="d-flex justify-content-between pt-3">
                    <h2 class="ml-5 ">Profil</h2>
                    <!-- On affiche le bouton "Modifier" seulement si c'est le profil de l'utilisateur connecté-->
                    <?php if(getIdEleveParNomUtilisateur($_SESSION["nomUtilisateur"]) == $_GET["idEleve"]){?>
                        <a href="modifierProfil.php?idEleve=<?=getIdEleveParNomUtilisateur($_SESSION["nomUtilisateur"]);?>" class="btn btn-outline-dark mr-5" type="button">Modifier</a>
                        <?php }?>
                </div>
                <hr class="ml-5 mr-5" />
                <div class="ml-4 row text-secondary">
                    <div class="col-md-6 h4">
                        <div class="col-md-12">
                            <div class="affichageProfil"><?= $prenom ?> <?= $nom ?></div>
                            <div class="affichageProfil">Promotion <?= $promo ?></div>
                            <div class="affichageProfil">Genre: <?= $genre ?></div>
                        </div>
                    </div>
                </div>
                <hr class="ml-5 mr-5" />
                <div class="ml-4 row text-secondary">
                    <div class="col-md-6 h5">
                        <div class="col-md-12">
                            <div class="affichageProfil"><u>Contact</u> </div>
                            <div class="affichageProfil">
                                <i class="fa fa-at fa-lg" aria-hidden="true"></i>
                                <?= $mail ?>
                            </div>
                            <div class="affichageProfil">
                                <i class="fa fa-phone fa-lg" aria-hidden="true"></i>
                                <?= $tel ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 h5">
                        <div class="col-md-12">
                            <div class="affichageProfil"><u>Adresse</u> </div>
                            <div class="affichageProfil"><?= $adresse ?></div>
                            <div class="affichageProfil"><?= $ville ?></div>
                            <div class="affichageProfil"><?= $codePostal ?></div>
                        </div>
                    </div>
                </div>
                <hr class="ml-5 mr-5" />

                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-body ml-3">
                                <h6 class="card-title h5 text-secondary"><u>Expériences</u> </h6>
                                <div id="content">
                                    <ul class="timeline">
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
                                        ?>

                                            <?php $dates = formaterDateExperiencePro($dateDebut) . "-" . formaterDateExperiencePro($dateFin); ?>
                                            <li class="event" title=<?= $dates ?>>
                                                <h4><?= $intituleExp ?></h4>
                                                <p class="h6">
                                                    <?php echo $libelleOrganisation . " - " . $typeExp ?>
                                                    </br>
                                                    <?php echo $region . " - " . $ville ?>
                                                </p>
                                                <p><?= $description ?> </p>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

<?php } ?>