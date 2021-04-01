<?php
/* MODULE DE PROGRAMMATION WEB
* Rôle du fichier :
* Affiche le profil d'un élève. Fonctionne avec un parametre get, par exemple: pages/profil.php?idEleve=4 
* Si le compte n'est pas validé alors seul un gestionnaire peut le voir
* Sinon affiche une 404
* On affiche pas les infos cachées par l'utilisateur en question SAUF si c'est le profil de l'utilisatur connecté actuellement ou si c'est le gestionnaire qui regarde
*
* Copyright 2021, MARQUETON Emma & WEINREICH Clément
* https://ensc.bordeaux-inp.fr/fr
*
*/
?>

<?php
require_once("../includes/fonctionsUtilitaires.php");
require_once("../includes/fonctionsGenerales.php");
require_once("../includes/fonctionsEleve.php");
session_start();

if (!estConnecte() || empty($_GET["idEleve"]) || !idEleveValide(escape($_GET["idEleve"]))) {
    // Si un utilisateur n'est pas connecté, ou que l'id eleve n'est pas valide ou pas donné en get alors on redirige vers 404 error
    redirect("404.php");
} else {

    //On nomme tous les paramètres qui peuvent nous servir pour modifier le profil afin de gagner en lisibilité
    $idEleve = escape($_GET["idEleve"]); // escape pour éviter une injection depuis l'url
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
    $parametres = getVisibiliteInfosProfil($idEleve);

?>

    <!doctype html>
    <html lang="fr">

    <?php
    $titrePage = "Profil - $prenom $nom";
    require_once "../includes/fragments/head.php";
    ?>

    <body class="background">
        <?php require_once "../includes/fragments/header.php"; ?>
        <div class="container">
            <?php require_once('../includes/fragments/alert.php');
            if (isset($_SESSION["alert"])) { // Si une alerte a été émise, alors elle sera affichée car on require_once alert.php. 
                unset($_SESSION["alert"]); // Pour ne plus l'afficher, on l'enlève de la variable de session. 
            }

            // Si le profil correspondant est celui de l'utilisateur connecté, alors c'est true, on affiche la popup, sinon c'est false.
            if (!estGestionnaire()) {
                $estProfilDeLUtilisateurCo = (getIdEleveParNomUtilisateur($_SESSION["nomUtilisateur"]) == $_GET["idEleve"]);
                if (!possedeExperiencePro($idEleve) && $estProfilDeLUtilisateurCo) { // Si l'utilisateur n'a aucune experience pro alors on lui affiche un message d'alerte
                    afficherPopUpExperiencePro();
                }
            }

            ?>

            <div class="whitecontainer">
                <div class="d-flex justify-content-between pt-3">
                    <h2 class="ml-5 ">Profil</h2>
                    <!-- On affiche le bouton "Modifier" seulement si c'est le profil de l'utilisateur connecté -->
                    <?php if (!estGestionnaire() && $estProfilDeLUtilisateurCo) { ?>
                        <a href="modifierProfil.php?idEleve=<?= getIdEleveParNomUtilisateur($_SESSION["nomUtilisateur"]); ?>" class="btn btn-outline-dark mr-5">Modifier</a>
                    <?php } ?>
                </div>
                <hr class="ml-5 mr-5" />
                <div class="ml-4 row text-secondary">
                    <div class="col-md-6 h4">
                        <div class="col-md-12">
                            <div class="affichageProfil"><?= $prenom ?> <?= $nom ?></div> <!-- On affiche dans Prénom, Nom et Promo car ils sont obligatoirement visibles -->
                            <div class="affichageProfil">Promotion <?= $promo ?></div>

                            <!-- Maintenant, si la personne connectée n'est pas un admin ou que ce n'est pas le propriétaire du compte, on affiche que les informations cochées "visibles" -->
                            <?php if (estGestionnaire() || $estProfilDeLUtilisateurCo || $parametres["Genre"]) { ?>
                                <div class="affichageProfil">Genre: <?= $genre ?></div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <hr class="ml-5 mr-5" />
                <div class="ml-4 row text-secondary">
                    <div class="col-md-6 h5">
                        <div class="col-md-12">
                            <div class="affichageProfil"><u>Contact</u> </div>
                            <?php if (estGestionnaire() || $estProfilDeLUtilisateurCo || $parametres["AdresseMail"]) { ?>
                                <div class="affichageProfil">
                                    <i class="fa fa-at fa-lg" aria-hidden="true"></i>
                                    <?= $mail ?>
                                </div>
                            <?php } ?>
                            <?php if (estGestionnaire() || $estProfilDeLUtilisateurCo || $parametres["NumTelephone"]) { ?>
                                <div class="affichageProfil">
                                    <i class="fa fa-phone fa-lg" aria-hidden="true"></i>
                                    <?= $tel ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-6 h5">
                        <div class="col-md-12">
                            <div class="affichageProfil"><u>Adresse</u> </div>
                            <?php if (estGestionnaire() || $estProfilDeLUtilisateurCo || $parametres["Adresse"]) { ?>
                                <div class="affichageProfil"><?= $adresse ?></div>
                            <?php } ?>
                            <?php if (estGestionnaire() || $estProfilDeLUtilisateurCo || $parametres["Ville"]) { ?>
                                <div class="affichageProfil"><?= $ville ?></div>
                            <?php } ?>
                            <?php if (estGestionnaire() || $estProfilDeLUtilisateurCo || $parametres["CodePostal"]) { ?>
                                <div class="affichageProfil"><?= $codePostal ?></div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <hr class="ml-5 mr-5" />

                <!-- Affichage des expériences pro-->
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-body ml-3">
                                <h6 class="card-title h5 text-secondary"><u>Expériences</u> </h6>
                                <!-- Si l'élève possède au moins une expériences pro -->
                                <?php if (possedeExperiencePro($idEleve)) { ?>

                                    <div>
                                        <ul class="timeline">
                                            <?php foreach ($experiencePro as $expPro) { // on boucle dans les experiences pro pour les afficher
                                                $idExperiencePro = $expPro["IdExperiencePro"];
                                                // Si l'experience pro est rendue invisible par l'utilisateur alors on ne le montre pas
                                                if ($parametres[$idExperiencePro] || estGestionnaire() || $estProfilDeLUtilisateurCo) {

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

                                                    // on formate l'affichage de la date
                                                    $dates = formaterDateExperiencePro($dateDebut) . "-" . formaterDateExperiencePro($dateFin);

                                            ?>

                                                    <li class="event" title=<?= $dates ?>>
                                                        <h4><?= $intituleExp . " - " . $typePoste ?> </h4>
                                                        <p class="h6">
                                                            <?php echo $libelleOrganisation . " (" . $typeOrganisation . ")" . " - " . $typeExp ?>
                                                            <br/>
                                                            <?php echo $region . " - " . $ville ?>
                                                            <br/><br/></p>
                                                        <p><?= $description ?></p>
                                                        Secteur(s) d'activité : <?= $secteursActivites ?><br/>
                                                        Domaine(s) de compétence : <?= $domainesCompetences ?><br/>
                                                        <?php if ($salaire != null) { ?>Salaire : <?= $salaire ?> <?php } ?>
                                                    <!-- s'il n'y a pas de salaire on affiche pas le libelle salaire -->
                                                    

                                                    </li>
                                            <?php }
                                            } ?>
                                        </ul>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

<?php } ?>