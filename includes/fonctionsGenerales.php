<?php
/* MODULE DE PROGRAMMATION WEB
 * Rôle du fichier : 
 * Contient les fonctions php utilisées par les élèves ET les gestionnaires
 *
 *
 * Copyright 2021, MARQUETON Emma & WEINREICH Clément
 * https://ensc.bordeaux-inp.fr/fr
 *
 */

require_once("../includes/fonctionsBDD.php");

function estConnecte()
{
    return isset($_SESSION['nomUtilisateur']);
}


function estGestionnaire()
{
    return $_SESSION['estGestionnaire'];
}

function connexion()
{
    $BDD = getBDD();

    if (!empty($_POST["nomUtilisateur"]) && !empty($_POST["motDePasse"])) {
        $nomUtilisateur = escape($_POST["nomUtilisateur"]);
        $mdp = escape($_POST["motDePasse"]);
        //echo " " . $nomUtilisateur . " " . $mdp;

        // On récupère l'utilisateur dans la base de données 
        $requeteUtilisateur = $BDD->prepare("SELECT * FROM Compte WHERE NomUtilisateur=? AND MotDePasse=?");
        $requeteUtilisateur->execute(array($nomUtilisateur, $mdp));

        // On vérifie qu'il y a bien un compte correspondant à ce nom d'utilisateur et ce mdp
        if ($requeteUtilisateur->rowCount() == 1) {

            // on recupère l'id du compte
            $compte = $requeteUtilisateur->fetch();
            $idCompte = $compte["IdCompte"];

            // on regarde s'il y a cet idcompte dans la table élève pour savoir si c'est un élève ou un gestionnaire
            $requeteTypeCompte =  $BDD->prepare("SELECT CompteValide FROM Eleve WHERE IdCompte = ?");
            $requeteTypeCompte->execute(array($idCompte));


            // S'il y a eu un resultat c'est que c'est un élève
            if ($requeteTypeCompte->rowCount() == 1) {
                $compteValide = $requeteTypeCompte->fetch()[0];

                // On verifie que le compte est bien validé
                if ($compteValide) {
                    // On rempli la variable de session
                    $_SESSION["nomUtilisateur"] = $nomUtilisateur;
                    $_SESSION["estGestionnaire"] = false;
                    $_SESSION["compteValide"] = $compteValide;
                    // feedback
                    $alert["bootstrapClassAlert"] = "success";
                    $alert["messageAlert"] = "Vous êtes maintenant connecté.";
                    redirect('accueil.php');
                } else {
                    $_SESSION["nomUtilisateurCompteNonValide"] = $nomUtilisateur; // Pour que la page attenteValidation.php lui indique son nom d'utilisateur (comme lors de l'inscription)
                    redirect("attenteValidation.php");
                }
            } else { // sinon c'est un gestionnaire
                $_SESSION["nomUtilisateur"] = $nomUtilisateur;
                $_SESSION["estGestionnaire"] = true;

                $alert["bootstrapClassAlert"] = "success";
                $alert["messageAlert"] = "Vous êtes maintenant connecté.";

                redirect('accueil.php');
            }

            $_SESSION["alert"] = $alert;
            unset($_POST);
            $_POST = array();
        } else { // Il n'y a pas de compte correspondant à ces identifiants
            $alert["bootstrapClassAlert"] = "danger";
            $alert["messageAlert"] = "Aucun utilisateur ne correspond à ces informations.";
        }
    } else { // Les champs n'ont pas été remplis
        $alert["bootstrapClassAlert"] = "danger";
        $alert["messageAlert"] = "Veuillez remplir tous les champs.";
    }

    $_SESSION["alert"] = $alert; //permet d'afficher un feedback

    unset($_POST);
    $_POST = array();
}

function mettreAJourMotDePasse()
{
    if (!empty($_POST["ancienMotDePasse"]) && !empty($_POST["nouveauMotDePasse"]) && !empty($_POST["confirmeNouveauMotDePasse"])) {

        $ancienMotDePasse = escape($_POST["ancienMotDePasse"]);
        $nouveauMotDePasse = escape($_POST["nouveauMotDePasse"]);
        $confirmeNouveauMotDePasse = escape($_POST["confirmeNouveauMotDePasse"]);
        $nomUtilisateur = $_SESSION["nomUtilisateur"];

        $BDD = getBDD();

        $requeteMotDePasse = $BDD->prepare("SELECT MotDePasse FROM Compte WHERE NomUtilisateur = ?");
        $requeteMotDePasse->execute(array($nomUtilisateur));
        $motDePasseActuel = $requeteMotDePasse->fetch()[0];

        if (($ancienMotDePasse == $motDePasseActuel) && ($nouveauMotDePasse == $confirmeNouveauMotDePasse)) {
            $requeteUpdateMotDePasse = $BDD->prepare("UPDATE Compte SET MotDePasse = ? WHERE NomUtilisateur = ?");
            $requeteUpdateMotDePasse->execute(array($nouveauMotDePasse, $nomUtilisateur));

            $alert["bootstrapClassAlert"] = "success";
            $alert["messageAlert"] = "Le mot de passe a bien été mis à jour";
            $_SESSION["alert"] = $alert;

            if (!estGestionnaire()) {
                redirect("profil.php?idEleve=" . getIdEleveParNomUtilisateur($nomUtilisateur));
            }
        } else {
            $alert["bootstrapClassAlert"] = "danger";
            $alert["messageAlert"] = "Veuillez vérifier les mots de passe rentrés";
            $_SESSION["alert"] = $alert;
        }
        unset($_POST); // On vide la variable post pour eviter d'avoir des problèmes avec certains navigateurs qui gardent cette information en cache
        $_POST = array();
    }
}

function recupererResultatsRecherche()
{
    $BDD = getBDD();


    $search_val = escape($_POST['search_term']);
    $search_filter = escape($_POST['search_param']);
    if ($search_val != "") {

        // Si le filtre correspond à un élement de la table ExperiencePro : 
        if ($search_filter == "TypeExperiencePro" || $search_filter == "TypeOrganisation" || $search_filter == "LibelleOrganisation" || $search_filter == "Region" || $search_filter == "SecteursActivites" || $search_filter == "DomainesCompetences") {
            $requeteExperiencePro = $BDD->prepare("SELECT * FROM ExperiencePro, Parametres, InfosPerso,Eleve WHERE Eleve.IdEleve = ExperiencePro.IdEleve AND ExperiencePro.IdEleve = Parametres.IdEleve AND ExperiencePro.IdEleve = InfosPerso.IdEleve AND ExperiencePro.IdExperiencePro = Parametres.LibelleInformation AND $search_filter LIKE CONCAT('%',?,'%')");  // Probleme : Si on passe $search_filter avec un ? dans le execute, ça ne fonctionne pas, donc pour l'instant on donne la variable dans le prepare (ce n'est pas une entrée utilisateur)
            $requeteExperiencePro->execute(array($search_val));
            //echo $search_filter . " " . $search_val;

            while ($experiencePro = $requeteExperiencePro->fetch()) {
                if (($experiencePro["Visibilite"] == true && $experiencePro["CompteValide"] == true) || estGestionnaire()) { // Si l'experiece pro est visible et que le compte est validé alors on la présente dans les résultats
                    echo '<div class="whitecontainer mt-3 mb-3"> 
                    <div class="ml-4 row text-secondary">
                        <div class="col-md-6 h5">
                            <div class="col-md-12">
                                <div class="affichageProfil h3">' . $experiencePro["Prenom"] . " " . $experiencePro["Nom"] . " - Promotion " . $experiencePro["Promotion"] . '</div> </hr>' .
                        '<div class="affichageProfil"><u>' . $experiencePro["IntituleExperiencePro"] . "</u></br>" . $experiencePro["LibelleOrganisation"] . " - " . $experiencePro["TypePoste"] . '</div>' .
                        '<div class="affichageProfil">' . $experiencePro["Region"] . "</br>" . $experiencePro["SecteursActivites"] . " - " . $experiencePro["DomainesCompetences"] . '</div>' .
                        '<a href="profil.php?idEleve=' . $experiencePro["IdEleve"] . '" class="btn btn-outline-dark mr-5" type="button">Voir le profil</a>
                            </div>
                        </div>
                    </div>
                </div>';
                }
            }
        }
        // Si le filtre correspond au nom ou au prenom
        else if ($search_filter == "NomPrenom") {

            $requeteNomPrenom = $BDD->prepare("SELECT Eleve.IdEleve,Nom,Prenom,Promotion,CompteValide FROM Eleve,InfosPerso WHERE Eleve.IdEleve = InfosPerso.IdEleve AND (InfosPerso.Nom LIKE CONCAT('%',?,'%') OR InfosPerso.Prenom LIKE CONCAT('%',?,'%')) ");  // Probleme : Si on passe $search_filter avec un ? dans le execute, ça ne fonctionne pas
            $requeteNomPrenom->execute(array($search_val, $search_val));

            while ($profil = $requeteNomPrenom->fetch()) {
                if ($profil["CompteValide"] == true || estGestionnaire()) { // On vérifie que le compte est bien validé pour l'afficher
                    echo '<div class="whitecontainer mt-3 mb-3"> 
                        <div class="ml-4 row text-secondary">
                            <div class="col-md-6 h3">
                                <div class="col-md-12">
                                    <div class="affichageProfil">' . $profil["Prenom"] . " " . $profil["Nom"] . " - Promotion " . $profil["Promotion"] . '</div>' .
                        '<a href="profil.php?idEleve=' . $profil["IdEleve"] . '" class="btn btn-outline-dark mr-5" type="button">Voir le profil</a>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            }
        }


        // Si le filtre correspond à la promotion ou à la ville (table infos perso)
        else if ($search_filter == "Promotion" || $search_filter == "Ville") {
            $requeteProfil = $BDD->prepare("SELECT Eleve.IdEleve, Nom, Prenom, Promotion, Ville, CompteValide FROM InfosPerso,Eleve WHERE Eleve.IdEleve = InfosPerso.IdEleve AND InfosPerso.$search_filter LIKE CONCAT('%',?,'%') ");
            $requeteProfil->execute(array($search_val));
            while ($profil = $requeteProfil->fetch()) {
                if ($profil["CompteValide"] == true || estGestionnaire()) {
                    echo '<div class="whitecontainer mt-3 mb-3"> 
                              <div class="ml-4 row text-secondary">
                                  <div class="col-md-6 h3">
                                    <div class="col-md-12">
                                        <div class="affichageProfil">' . $profil["Prenom"] . " " . $profil["Nom"] . " - Promotion " . $profil["Promotion"] . '</div>' .
                        '<div class="affichageProfil h5">' . $profil["Ville"] . '</div>' .
                        '<a href="profil.php?idEleve=' . $profil["IdEleve"] . '" class="btn btn-outline-dark mr-5" type="button">Voir le profil</a>
                                    </div>
                                </div>
                            </div>
                            </div>';
                }
            }
        }
    }
    exit();
}

?>