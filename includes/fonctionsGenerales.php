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

// Retourne true si un utilisateur est connecté, false sinon
function estConnecte()
{
    return isset($_SESSION['nomUtilisateur']);
}


// Retourne true si l'utilisateur connecté est un gestionnaire, false sinon
function estGestionnaire()
{
    return $_SESSION['estGestionnaire'];
}

// Permet de se connecter sur l'annuaire
function connexion()
{
    $BDD = getBDD();

    if (!empty($_POST["nomUtilisateur"]) && !empty($_POST["motDePasse"])) {
        $nomUtilisateur = escape($_POST["nomUtilisateur"]);
        $mdp = escape($_POST["motDePasse"]);
        $mdpHash = hash("sha512",$mdp);

        // On récupère l'utilisateur dans la base de données 
        $requeteUtilisateur = $BDD->prepare("SELECT * FROM Compte WHERE NomUtilisateur=? AND MotDePasse=?");
        $requeteUtilisateur->execute(array($nomUtilisateur, $mdpHash));

        // On vérifie qu'il y a bien un compte correspondant à ce nom d'utilisateur et ce mdp
        if ($requeteUtilisateur->rowCount() == 1) {

            // On recupère l'id du compte
            $compte = $requeteUtilisateur->fetch();
            $idCompte = $compte["IdCompte"];

            // On regarde s'il y a cet idcompte dans la table élève pour savoir si c'est un élève ou un gestionnaire
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

                    // feedback utilisateur
                    $alert["bootstrapClassAlert"] = "success";
                    $alert["messageAlert"] = "Vous êtes maintenant connecté.";
                    redirect('accueil.php');
                } else {
                    // Le compte existe mais n'est pas validé
                    $_SESSION["nomUtilisateurCompteNonValide"] = $nomUtilisateur; // Pour que la page attenteValidation.php lui indique son nom d'utilisateur (comme lors de l'inscription)
                    redirect("attenteValidation.php");
                }
            } else { // Sinon c'est un gestionnaire
                $_SESSION["nomUtilisateur"] = $nomUtilisateur;
                $_SESSION["estGestionnaire"] = true;

                $alert["bootstrapClassAlert"] = "success";
                $alert["messageAlert"] = "Vous êtes maintenant connecté.";

                redirect('accueil.php');
            }


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

// Permet de mettre à jour le mot de passe de l'utilisateur
function mettreAJourMotDePasse()
{
    if (!empty($_POST["ancienMotDePasse"]) && !empty($_POST["nouveauMotDePasse"]) && !empty($_POST["confirmeNouveauMotDePasse"])) {

        // On les hash dès les début pour faire la comparaison avec l'ancien mdp et pour éviter de devoir hash avant l'insertion
        $ancienMotDePasse = hash("sha512",escape($_POST["ancienMotDePasse"]));
        $nouveauMotDePasse = hash("sha512",escape($_POST["nouveauMotDePasse"]));
        $confirmeNouveauMotDePasse = hash("sha512",escape($_POST["confirmeNouveauMotDePasse"]));
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

            if (!estGestionnaire()) { // Si on est un élève, on est redirigé sur notre profil
                redirect("profil.php?idEleve=" . getIdEleveParNomUtilisateur($nomUtilisateur));
            }
        } else {
            $alert["bootstrapClassAlert"] = "danger";
            $alert["messageAlert"] = "Veuillez vérifier les mots de passe rentrés";
            $_SESSION["alert"] = $alert;
        }
    }
}

// Permet de récuperer et de traiter une recherche d'un utilisateur sur la barre de recherche
function recupererResultatsRecherche()
{
    $BDD = getBDD();

    $texteRecherche = escape($_POST['texteRecherche']);
    $filtreRecherche = escape($_POST['filtreRecherche']);
    if ($texteRecherche != "") { // Si on ne fait pas ce contrôle, tous les résultats s'affichent

        // Si le filtre correspond à un élement de la table ExperiencePro : 
        if ($filtreRecherche == "TypeExperiencePro" || $filtreRecherche == "TypeOrganisation" || $filtreRecherche == "LibelleOrganisation" || $filtreRecherche == "Region" || $filtreRecherche == "SecteursActivites" || $filtreRecherche == "DomainesCompetences") {
            $requeteExperiencePro = $BDD->prepare("SELECT * FROM ExperiencePro, Parametres, InfosPerso,Eleve WHERE Eleve.IdEleve = ExperiencePro.IdEleve AND ExperiencePro.IdEleve = Parametres.IdEleve AND ExperiencePro.IdEleve = InfosPerso.IdEleve AND ExperiencePro.IdExperiencePro = Parametres.LibelleInformation AND $filtreRecherche LIKE CONCAT('%',?,'%')");  
            // Le traitement des paramètres par PDO (avec ? ou :nomparam) transforme l'attribut en string avec des quotes, on ne peut donc pas faire WHERE ? LIKE %cc% sinon il recherche les caracteres %cc% dans le string passé en paramètre.
            // Pour pallier ce problème on escape la valeur de la variable au préalable avec des paramètres restrictifs sur la fonction htmlspecialchars
            // Puis on inclu la variable dans le string de la requete préparée (sachant que c'est la valeur d'un élément du menu dropdown et non une entrée utilisateur direct)
            $requeteExperiencePro->execute(array($texteRecherche));
           

            while ($experiencePro = $requeteExperiencePro->fetch()) {

                // Si l'experiece pro est visible et que le compte est validé alors on la présente dans les résultats
                if ((($experiencePro["Visibilite"] == true && $experiencePro["CompteValide"] == true)) || estGestionnaire()) { 
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
                // Ce qui est affiché sera récupéré par la fonction recherche.js et sera injecté dans l'html 
                }
            }
        }
        // Si le filtre correspond au nom ou au prenom
        else if ($filtreRecherche == "NomPrenom") {

            $requeteNomPrenom = $BDD->prepare("SELECT Eleve.IdEleve,Nom,Prenom,Promotion,CompteValide FROM Eleve,InfosPerso WHERE Eleve.IdEleve = InfosPerso.IdEleve AND (InfosPerso.Nom LIKE CONCAT('%',?,'%') OR InfosPerso.Prenom LIKE CONCAT('%',?,'%')) ");  // Probleme : Si on passe $filtreRecherche avec un ? dans le execute, ça ne fonctionne pas
            $requeteNomPrenom->execute(array($texteRecherche, $texteRecherche));

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
        else if ($filtreRecherche == "Promotion" || $filtreRecherche == "Ville") {
            $requeteProfil = $BDD->prepare("SELECT Eleve.IdEleve, Nom, Prenom, Promotion, Ville, CompteValide FROM InfosPerso,Eleve WHERE Eleve.IdEleve = InfosPerso.IdEleve AND InfosPerso.$filtreRecherche LIKE CONCAT('%',?,'%') ");
            $requeteProfil->execute(array($texteRecherche));
            while ($profil = $requeteProfil->fetch()) {
                $visibilite = getVisibiliteInfosProfil($profil["IdEleve"]);
                if($filtreRecherche == "Ville" && $visibilite["Ville"]==false && !estGestionnaire()){
                    continue; // On affiche pas le résultat si le filtre est la ville est que la ville est rendue privée et que le compte qui cherche n'est pas un compte gestionnaire
                }
            
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
