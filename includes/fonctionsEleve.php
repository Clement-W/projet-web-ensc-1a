<?php
/* MODULE DE PROGRAMMATION WEB
 * Rôle du fichier : 
 * Contient les fonctions liées aux utilisateurs élèves
 *
 *
 * Copyright 2021, MARQUETON Emma & WEINREICH Clément
 * https://ensc.bordeaux-inp.fr/fr
 *
 */

require_once("../includes/fonctionsBDD.php");
require_once("../includes/fonctionsUtilitaires.php");

// genere un nom d'utilisateur de façon unique
function genererNomUtilisateur($nom, $prenom)
{
    $BDD = getBDD();
    $nomUtilisateurBase = strtolower($prenom[0] . $nom); // première lettre du prenom suivi du nom de famille
    $dejaUtilise = true;
    $cpt = 0;

    while ($dejaUtilise) { //Tant que le pseudo existe, on incremente le compteur qui s'ajoute à a fin du pseudo (exemple : cweinreich3)
        if ($cpt != 0) { // Si le pseudo basique nom+prenom existe, alors on utilise ajoute un indice à la fin
            $nomUtilisateur = $nomUtilisateurBase . $cpt;
        } else {
            $nomUtilisateur = $nomUtilisateurBase;
        }
        $requeteUtilisateur = $BDD->prepare("SELECT NomUtilisateur FROM Compte WHERE NomUtilisateur=?");
        $requeteUtilisateur->execute(array($nomUtilisateur));

        if ($requeteUtilisateur->rowCount() == 1) {
            $cpt++;
        } else {
            $dejaUtilise = false;
        }
    }

    return $nomUtilisateur;
}

// Insert un paramètre dans la bdd
function insererParametre($BDD, $idEleve, $visibilite, $libelleInformation)
{
    $requeteInsertionParametre = $BDD->prepare("INSERT INTO Parametres(LibelleInformation, Visibilite, IdEleve) VALUES (?,?,?)");
    $requeteInsertionParametre->execute(array($libelleInformation, $visibilite, $idEleve));
}


function inscription()
{
    $BDD = getBDD();

    if (!empty($_POST["nom"]) && !empty($_POST["prenom"]) && !empty($_POST["motDePasse"]) && !empty($_POST["promo"]) && !empty($_POST["genre"]) && !empty($_POST["adresse"]) && !empty($_POST["ville"]) && !empty($_POST["codePostal"]) && !empty($_POST["email"]) && !empty($_POST["telephone"])) {

        $nom = ucwords(strtolower(escape($_POST["nom"]))); // on met la première lettre en majuscule (on met tout en minuscule avant pour être sûr que ce soit apreil pour tout le monde)
        $prenom = ucwords(strtolower(escape($_POST["prenom"])));
        $nomUtilisateur = genererNomUtilisateur($nom, $prenom);
        $mdp = escape($_POST["motDePasse"]);
        $promo = escape($_POST["promo"]);
        $genre = escape($_POST["genre"]);
        $adresse = escape($_POST["adresse"]);
        $ville = escape($_POST["ville"]);
        $codePostal = escape($_POST["codePostal"]);
        $email = escape($_POST["email"]);
        $telephone = escape($_POST["telephone"]);

        // On créé un compte associé au mail, nom d'utilisateur et mot de passe
        $requeteInsertionCompte = $BDD->prepare("INSERT INTO Compte(NomUtilisateur, MotDePasse, AdresseMail) VALUES (?,?,?)");
        $requeteInsertionCompte->execute(array($nomUtilisateur, $mdp, $email));

        // On recupère l'id du compte inséré
        $idCompte = $BDD->lastInsertId();

        // On créé un élève à partir de l'id du compte créé
        $requeteInsertionEleve = $BDD->prepare("INSERT INTO Eleve(CompteValide, IdCompte) VALUES (FALSE,?)");
        $requeteInsertionEleve->execute(array($idCompte));

        // On recupère l'id de l'élève inséré
        $idEleve = $BDD->lastInsertId();

        // On insert ses informations personnelles en base
        $requeteInsertionInfosPerso = $BDD->prepare("INSERT INTO InfosPerso(Nom, Prenom, Genre, Promotion, Adresse, Ville, CodePostal, NumTelephone, IdEleve) VALUES (?,?,?,?,?,?,?,?,?)");
        $requeteInsertionInfosPerso->execute(array($nom, $prenom, $genre, $promo, $adresse, $ville, $codePostal, $telephone, $idEleve));

        // Visibilite des informations personnelles
        $informationsParametrable =  array("Genre", "Adresse", "Ville", "CodePostal", "NumTelephone", "AdresseMail"); // Contient les noms des champs dont la visibilité est parametrable
        foreach ($informationsParametrable as $libelleInformation) { // pour chaque information du compte dont la visibilité est modifiable
            insererParametre($BDD, $idEleve, true, $libelleInformation); // on insert en base le parametre comme visible
        }

        $_SESSION["nomUtilisateurCompteNonValide"] = $nomUtilisateur; // permet de donner le nom d'utilisateur à la personne qui a créé le compte 

        redirect("attenteValidation.php");
    } else { // Tous les champs n'ont pas été remplis
        $alert["bootstrapClassAlert"] = "danger";
        $alert["messageAlert"] = "Veuillez remplir toutes les informations pour vous inscrire.";
    }

    unset($_POST);
    $_POST = array();

    $_SESSION["alert"] = $alert;
}


// Permet d'afficher la popup d'alerte qui indique que l'utilisateur n'a pas d'experience pro
function afficherPopUpExperiencePro()
{
    require_once "../includes/modals/alertePasDExperiencePro.php";
}



//retourne les informations du profil par id
function getInfosCompteEleveParId($id)
{
    $BDD = getBDD();

    //On recupère les infos personelles et les informations de compte de l'utilisateur
    $requeteInfosPerso = $BDD->PREPARE("SELECT * FROM Compte,Eleve,InfosPerso WHERE Compte.IdCompte = Eleve.IdCompte AND Eleve.IdEleve = InfosPerso.IdEleve AND Eleve.IdEleve = ?");
    $requeteInfosPerso->execute(array($id));
    return $requeteInfosPerso->fetch();
}


//verifie que l'id passé en paramètre est bien présent en base
function idEleveValide($id)
{
    $BDD = getBDD();

    $requeteIdEleve = $BDD->prepare("SELECT IdEleve FROM Eleve WHERE IdEleve=?");
    $requeteIdEleve->execute(array($id));
    if ($requeteIdEleve->rowCount() == 0) { //Si l'id n'est pas présent en base alors on return false
        return false;
    } else { // S'il est présent alors on return true
        return true;
    }
}



//retourne les experiences professionnelles par id
function getExperiencesProParId($id)
{
    $BDD = getBDD();

    //On recupère les experiences pro de l'utilisateur
    $requeteExperiencesPro = $BDD->PREPARE("SELECT * FROM Eleve,ExperiencePro WHERE Eleve.IdEleve = ExperiencePro.IdEleve AND Eleve.IdEleve = ? ORDER BY ExperiencePro.DateDebut DESC");
    $requeteExperiencesPro->execute(array($id));
    return $requeteExperiencesPro->fetchAll();
}


// permet d'ajouter une experience professionnelle
// prend en compte le fait que certaines informations sont facultatives, et les ajoutes si elles sont présentes.
function ajouterExperiencePro()
{

    if (!empty($_POST["IntituleExperiencePro"]) && !empty($_POST["TypeExperiencePro"]) && !empty($_POST["DateDebut"]) &&  !empty($_POST["TypeOrganisation"]) && !empty($_POST["LibelleOrganisation"]) && !empty($_POST["Region"]) && !empty($_POST["Ville"]) &&  !empty($_POST["TypePoste"]) && !empty($_POST["SecteursActivites"]) && !empty($_POST["DomainesCompetences"])) {


        $BDD = getBDD();

        $intituleExperiencePro = escape($_POST["IntituleExperiencePro"]);
        $typeExperiencePro = escape($_POST["TypeExperiencePro"]);
        $dateDebutStr = escape($_POST["DateDebut"]);
        $dateDebut = date("Y-m-d H:i:s", strtotime($dateDebutStr));
        $typeOrganisation = escape($_POST["TypeOrganisation"]);
        $region = escape($_POST["Region"]);
        $ville = escape($_POST["Ville"]);
        $typePoste = escape($_POST["TypePoste"]);
        $secteursActivites = $_POST["SecteursActivites"];
        $domainesCompetences = $_POST["DomainesCompetences"];
        $libelleOrganisation = escape($_POST["LibelleOrganisation"]);
        $visibilite = (isset($_POST["visibiliteExpPro"])) ? 1 : 0; //Si la checkbox est checked alors c'est envoyé par le post, sinon non
        // 1 ou 0 pour être compatible avec le tinyint de mysql

        //On recupere le nom d'utilisateur et on requete la bdd pour avoir l'id de l'eleve afin de lui associer une nouvelle experience pro
        $nomUtilisateur = $_SESSION["nomUtilisateur"];
        $requeteIdEleve = $BDD->prepare("SELECT IdEleve FROM Compte,Eleve WHERE Compte.IdCompte = Eleve.IdCompte AND Compte.NomUtilisateur = ?");
        $requeteIdEleve->execute(array($nomUtilisateur));
        $idEleve = $requeteIdEleve->fetch()[0];

        //On insert les informations obligatoires de la nouvelle experience professionnelle
        $requeteInsertionExpPro = $BDD->prepare("INSERT INTO ExperiencePro(IntituleExperiencePro,TypeExperiencePro, DateDebut, TypeOrganisation, LibelleOrganisation, TypePoste, Region, Ville, SecteursActivites, DomainesCompetences, IdEleve) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        $requeteInsertionExpPro->execute(array($intituleExperiencePro, $typeExperiencePro, $dateDebut, $typeOrganisation, $libelleOrganisation, $typePoste, $region, $ville, $secteursActivites, $domainesCompetences, $idEleve));

        // On recupère l'id de l'experience pro insérée
        $idExperiencePro = $BDD->lastInsertId();


        // Si la date de fin de l'experience pro est donnée alors on l'ajoute également
        if (!empty($_POST["DateFin"])) {
            $dateFinStr = escape($_POST["DateFin"]);
            $dateFin = date("Y-m-d H:i:s", strtotime($dateFinStr));
            $requeteUpdateDateFin = $BDD->prepare("UPDATE ExperiencePro SET DateFin=? WHERE IdExperiencePro=?");
            $requeteUpdateDateFin->execute(array($dateFin, $idExperiencePro));
        }

        // Si la description de l'experience pro est donnée alors on l'ajoute également
        if (!empty($_POST["Description"])) {
            $description = escape($_POST["Description"]);
            $requeteUpdateDescription = $BDD->prepare("UPDATE ExperiencePro SET Description=? WHERE IdExperiencePro=?");
            $requeteUpdateDescription->execute(array($description, $idExperiencePro));
        }

        // Si le salaire de l'experience pro est donnée alors on l'ajoute également
        if (!empty($_POST["Salaire"])) {
            $salaire = escape($_POST["Salaire"]);
            $requeteUpdateSalaire = $BDD->prepare("UPDATE ExperiencePro SET Salaire=? WHERE IdExperiencePro=?");
            $requeteUpdateSalaire->execute(array($salaire, $idExperiencePro));
        }

        // On ajoute le parametre de visibilité en base (par défaut, l'exp pro est visibile)
        // Pour une experience pro, le libelle correspond à l'identifiant de l'experience pro.

        insererParametre($BDD, $idEleve, $visibilite, $idExperiencePro);

        $alert["bootstrapClassAlert"] = "success";
        $alert["messageAlert"] = "L'experience professionnelle a bien été ajoutée.";
    } else {
        $alert["bootstrapClassAlert"] = "danger";
        $alert["messageAlert"] = "Des informations sont manquantes.";
    }

    unset($_POST); // On vide la variable post pour eviter d'avoir des problèmes avec certains navigateurs qui gardent cette information en cache
    $_POST = array();

    $_SESSION["alert"] = $alert;

    redirect("profil.php?idEleve=" . $idEleve);
}

function getIdEleveParNomUtilisateur($nomUtilisateur)
{
    $BDD = getBDD();
    $requeteIdEleve = $BDD->prepare("SELECT IdEleve FROM Compte, Eleve WHERE Compte.IdCompte=Eleve.IdCompte AND Compte.NomUtilisateur = ?");
    $requeteIdEleve->execute(array($nomUtilisateur));
    $idEleve = $requeteIdEleve->fetch()[0];
    return $idEleve;
}


//retourne un tableau associatif contenant la visibilité des différents paramètres
function getVisibiliteInfosProfil($idEleve)
{
    $BDD = getBDD();

    $requeteParametres = $BDD->prepare("SELECT LibelleInformation,Visibilite FROM Parametres WHERE IdEleve = ?");
    $requeteParametres->execute(array($idEleve));
    $parametres = $requeteParametres->fetchAll();

    $visibilite = array();
    foreach ($parametres as $parametre) {
        $visibilite[$parametre["LibelleInformation"]] = $parametre["Visibilite"];
    }

    return $visibilite;
}

function mettreAJourProfil()
{
    if (!empty($_POST["mettreAJourProfil"])) { // pas besoin de verifier les autres champs vu qu'ils sont en required
        $BDD = getBDD();
        $miseAJour = false; //sera true si on fait une mise à jour


        /* Modification des champs d'information personelle */

        $idEleve = getIdEleveParNomUtilisateur($_SESSION["nomUtilisateur"]);
        $infos = getInfosCompteEleveParId($idEleve);
        $labelsInfosPerso = array("Nom", "Prenom", "Promotion", "Genre", "Adresse", "Ville", "CodePostal", "NumTelephone"); // Ne contient pas l'adresse mail car c'est pas dans la table info perso (on le fait à part)

        // on verifie si les infos perso ont été modifiées
        foreach ($labelsInfosPerso as $label) {
            if ($infos[$label] != escape($_POST[$label])) {
                $requeteUpdateInfoPerso = $BDD->prepare("UPDATE InfosPerso SET $label=? WHERE IdEleve=?"); // On ne peut pas passer un nom de colonne par un parametre du prepare et on connait les valeurs de $label donc pas de problème de sécurité
                $requeteUpdateInfoPerso->execute(array(escape($_POST[$label]), $idEleve));
                $miseAJour = true;
            }
        }


        // on verifie si l'adresse mail a été modifiée
        if ($infos["AdresseMail"] != escape($_POST["AdresseMail"])) {
            $requeteUpdateMail = $BDD->prepare("UPDATE Compte SET AdresseMail=? WHERE nomUtilisateur=?");
            $requeteUpdateMail->execute(array(escape($_POST["AdresseMail"]), $_SESSION["nomUtilisateur"]));

            $miseAJour = true;
        }


        /* Modification de la visibilité des champs des infos perso */

        $parametres = getVisibiliteInfosProfil($idEleve);

        $labelsVisibiliteInfosProfil = array("Adresse", "Ville", "CodePostal", "AdresseMail", "NumTelephone", "Genre");
        foreach ($labelsVisibiliteInfosProfil as $labelVisibilite) {
            $visibilite = (isset($_POST[$labelVisibilite . "Visibilite"])) ? 1 : 0; // Si ce champs du post n'est pas set c'est que la checkbox n'est pas cochée, s'il est set alors c'est que la checkbox est cochée
            if ($parametres[$labelVisibilite] != $visibilite) { // Si le parametre de visibilité est différent entre la valeur du form et la bdd alors on update
                $requeteUpdateParametres = $BDD->prepare("UPDATE Parametres SET Visibilite=? WHERE IdEleve = ? AND LibelleInformation = ?");
                $requeteUpdateParametres->execute(array($visibilite, $idEleve, $labelVisibilite));
                $miseAJour = true;
            }
        }



        /* Modification des champs des experiences pro */

        // Les champs de la classe experience pro qui sont modifiables 
        $labelsExperiencesPro = array("IntituleExperiencePro", "TypeExperiencePro", "DateDebut", "DateFin", "TypeOrganisation", "LibelleOrganisation", "TypePoste", "Region", "Ville", "SecteursActivites", "DomainesCompetences", "Description", "Salaire");

        $experiencesPro = getExperiencesProParId($idEleve); // On recupère les experiences pro

        foreach ($experiencesPro as $experience) { //pour chaque experience pro
            foreach ($labelsExperiencesPro as $label) { //pour chaque label (champ) de l'experience pro
                $idExperiencePro = $experience["IdExperiencePro"]; // on recupere l'id de l'experience pro car les noms des key du $_POST sont sous la forme "NomChampIdExperiencePro" 
                if ($experience[$label] != escape($_POST[$label . $idExperiencePro])) { // Si le champ de l'experience est différent du champ passé dans le post pour cette experience alors on update la bdd
                    $requeteUpdateExperiencePro = $BDD->prepare("UPDATE ExperiencePro SET $label=? WHERE IdExperiencePro = ?");
                    $requeteUpdateExperiencePro->execute(array(escape($_POST[$label . $idExperiencePro]), $idExperiencePro));
                    $miseAJour = true;
                }
            }
            /* Modification de la visiblité des experiences pro */

            // On profite de boucler dans les experiences pro pour gerer la visibilité
            $visibilite = (isset($_POST["visibiliteExpPro" . $idExperiencePro])) ? 1 : 0; // Si ce champs du post n'est pas set c'est que la checkbox n'est pas cochée, s'il est set alors c'est que la checkbox est cochée
            if ($parametres[$idExperiencePro] != $visibilite) { // Si le parametre de visibilité est différent entre la valeur du form et la bdd alors on update
                // pour rappel dans les parametres, les experiences pro sont identifiées par leur identifiant
                $requeteUpdateParametres = $BDD->prepare("UPDATE Parametres SET Visibilite=? WHERE IdEleve = ? AND LibelleInformation = ?");
                $requeteUpdateParametres->execute(array($visibilite, $idEleve, $idExperiencePro));
                $miseAJour = true;
            }
        }

        unset($_POST); // On vide la variable post pour eviter d'avoir des problèmes avec certains navigateurs qui gardent cette information en cache
        $_POST = array();

        if ($miseAJour) {
            $alert["bootstrapClassAlert"] = "success";
            $alert["messageAlert"] = "Le profil a bien été mis à jour";
            $_SESSION["alert"] = $alert;
        }


        redirect("profil.php?idEleve=" . $idEleve);
    }
}

function getCompteNonValide()
{
    $BDD = getBDD();

    //On recupère les infos personnelles et les informations de compte de l'utilisateur
    $requeteCompteNonValide = $BDD->prepare("SELECT Nom, Prenom, Promotion, CompteValide, Eleve.IdEleve FROM Eleve, InfosPerso WHERE Eleve.IdEleve = InfosPerso.IdEleve AND Eleve.CompteValide = 0");
    $requeteCompteNonValide->execute();
    return $requeteCompteNonValide->fetchAll();
}

// Verifie qu'un compte Élève a bien des experiences pro (lorsqu'il est connecté)
// retourne true s'il en a, false sinon
function possedeExperiencePro($id)
{
    $BDD = getBDD();

    $requetePossedeExperiencePro = $BDD->prepare("SELECT * FROM ExperiencePro WHERE IdEleve=?");
    $requetePossedeExperiencePro->execute(array($id));
    if ($requetePossedeExperiencePro->rowCount() == 0) { //Si aucune expérience n'a été rentré, on return false
        return false;
    } else { // S'il est présent alors on return true
        return true;
    }
}

?>