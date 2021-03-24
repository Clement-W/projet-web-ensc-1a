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

            <!-- Faire le form de connexion -->


            <div class="whitecontainer">
                <div class="d-flex justify-content-between pt-3">
                    <h2 class="ml-5 ">Profil</h2>
                    <button type="button" class="btn btn-outline-dark mr-5">Modifier</button>
                </div>
                <hr class="ml-5 mr-5" />
                <div class="ml-4 row text-secondary">
                    <div class="col-md-6 h4">
                        <div class="col-md-12">
                            <div class="affichage"><?= $prenom ?> <?= $nom ?></div>
                            <div class="affichage">Promotion <?= $promo ?></div>
                            <div class="affichage">Genre: <?= $genre ?></div>
                        </div>
                    </div>
                </div>
                <hr class="ml-5 mr-5" />
                <div class="ml-4 row text-secondary">
                    <div class="col-md-6 h5">
                        <div class="col-md-12">
                            <div class="affichage"><u>Contact</u>: </div>
                            <div class="affichage">
                                <i class="fa fa-at fa-lg" aria-hidden="true"></i>
                                <?= $mail ?>
                            </div>
                            <div class="affichage">
                                <i class="fa fa-phone fa-lg" aria-hidden="true"></i>
                                <?= $tel ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 h5">
                        <div class="col-md-12">
                            <div class="affichage"><u>Adresse</u>: </div>
                            <div class="affichage"><?= $adresse ?></div>
                            <div class="affichage"><?= $ville ?></div>
                            <div class="affichage"><?= $codePostal ?></div>
                        </div>
                    </div>
                </div>
                <hr class="ml-5 mr-5" />

                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-body ml-3">
                                <h6 class="card-title h5 text-secondary"><u>Expériences:</u> </h6>
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
                                            <?php $dates = $dateDebut . " - "; ?> </br> <?php $dates .= $dateFin; ?>
                                        
                                            <li class="event" data-date="<?= $dates ?>">
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