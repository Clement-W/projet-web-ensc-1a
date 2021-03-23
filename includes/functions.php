<?php

// CERTAINES MÉTHODES DOIVENT ÊTRE DÉPLACÉES DANS LES FICHIERS PAGES ASSOCIÉES LORSQU'ELLES NE SONT UTILISÉES QU'UNE FOIS

function getBDD()
{
    // Déploiement en local
    $server = "localhost";
    $username = "annuaireUser";
    $password = "explodingkittens";
    $db = "annuaireEleves";

    try {
        $BDD = new PDO(
            "mysql:host=$server;dbname=$db;charset=utf8",
            "$username",
            "$password",
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
    } catch (Exception $e) {
        die('Erreur fatale' . $e->getMessage());
    }

    return $BDD;
}

function estConnecte()
{
    return isset($_SESSION['nomUtilisateur']);
}


function estGestionnaire()
{
    return $_SESSION['estGestionnaire'];
}

function estUnCompteValide($idCompte){
    $BDD = getBDD();
    $requeteCompteValide =  $BDD->prepare("SELECT CompteValide FROM Eleve WHERE IdCompte = ?");
    $requeteCompteValide->execute(array($idCompte));
    if($requeteCompteValide->rowCount()==0){
        throw new Exception("L'IdCompte spécifié ne correspond à aucun compte présent en base.");
    }
    return $requeteCompteValide->fetch()[0];

    
}


function redirect($url)
{
    header("Location: $url");
}


function escape($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
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

                }else{
                    // SI le compte n'est pas validé, on ne peut pas se connecter
                    $alert["bootstrapClassAlert"] = "danger";
                    $alert["messageAlert"] = "Votre compte a été créé. Veuillez attendre la validation de votre compte par un gestionnaire pour pouvoir vous connecter.";
                }
            } else { // sinon c'est un gestionnaire
                $_SESSION["nomUtilisateur"] = $nomUtilisateur;
                $_SESSION["estGestionnaire"] = true;

                $alert["bootstrapClassAlert"] = "success";
                    $alert["messageAlert"] = "Vous êtes maintenant connecté.";

            }

            redirect('accueil.php');        

        } else { // Il n'y a pas de compte correspondant à ces identifiants
            $alert["bootstrapClassAlert"] = "danger";
            $alert["messageAlert"] = "Aucun utilisateur ne correspond à ces informations.";
        }
    } else { // Les champs n'ont pas été remplis
        $alert["bootstrapClassAlert"] = "danger";
        $alert["messageAlert"] = "Veuillez remplir tous les champs.";
    }

    return $alert;
}

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



function inscription()
{
    $BDD = getBDD();

    if (!empty($_POST["nom"]) && !empty($_POST["prenom"]) && !empty($_POST["motDePasse"]) && !empty($_POST["promo"]) && !empty($_POST["genre"]) && !empty($_POST["adresse"]) && !empty($_POST["ville"]) && !empty($_POST["codePostal"]) && !empty($_POST["email"]) && !empty($_POST["telephone"])) {
 
        $nom = escape($_POST["nom"]);
        $prenom = escape($_POST["prenom"]);
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

        $alert["bootstrapClassAlert"] = "success";
        $alert["messageAlert"] = "Votre compte a été créé. Veuillez attendre la validation de votre compte par un gestionnaire pour pouvoir vous connecter.";

        redirect("accueil.php");

    } else { // Tous les champs n'ont pas été remplis
        $alert["bootstrapClassAlert"] = "danger";
        $alert["messageAlert"] = "Veuillez remplir toutes les informations pour vous inscrire.";
    }

    unset($_POST);
    $_POST = array();

    return $alert;
}
/*
$_POST["nom"] = "weinreich";
$_POST["prenom"] = "clément";
$_POST["motDePasse"] = "so6j$";
$_POST["promo"] = 2023;
$_POST["genre"] = "M";
$_POST["adresse"] = "100B avenue roul";
$_POST["ville"] = "Talence";
$_POST["codePostal"] = 33400;
$_POST["email"] = "clement@weinreich.fr";
$_POST["telephone"] = 624396336;

$alert = inscription();

print_r($alert);*/



//permet de créer le compte d'un gestionnaire (utilisé par un gestionnaire)
function creerCompteGestionnaire()
{
    if (!empty($_POST["nomUtilisateur"]) && !empty($_POST["motDePasse"]) && !empty($_POST["email"])) {

        $BDD = getBDD();

        $nomUtilisateur = escape($_POST["email"]);
        $email = escape($_POST["email"]);
        $mdp = escape($_POST["motDePasse"]);

        // On verifie que le nom d'utilisateur n'est pas déjà pris
        $requeteUtilisateur = $BDD->prepare("SELECT NomUtilisateur FROM Compte WHERE NomUtilisateur=?");
        $requeteUtilisateur->execute(array($nomUtilisateur));

        // S'il n'y a pas de compte utilisant ce nom d'utilisateur
        if ($requeteUtilisateur->rowCount() == 0) {
            // On créé un compte associé au mail, nom d'utilisateur et mot de passe
            $requeteInsertionCompte = $BDD->prepare("INSERT INTO Compte(NomUtilisateur, MotDePasse, AdresseMail) VALUES (?,?,?)");
            $requeteInsertionCompte->execute(array($nomUtilisateur, $mdp, $email));

            // On recupère l'id du compte inséré
            $idCompte = $BDD->lastInsertId();

            // On créé un gestionnaire à partir de l'id du compte créé
            $requeteInsertionGestionnaire = $BDD->prepare("INSERT INTO Gestionnaire(IdCompte) VALUES (?)");
            $requeteInsertionGestionnaire->execute(array($idCompte));

            $alert["bootstrapClassAlert"] = "success";
            $alert["messageAlert"] = "Le compte gestionnaire a bien été créé.";
        } else {
            $alert["bootstrapClassAlert"] = "danger";
            $alert["messageAlert"] = "Ce nom d'utilisateur est déjà pris, veuillez en choisir un nouveau.";
        }
    } else { // Tous les champs n'ont pas été remplis
        $alert["bootstrapClassAlert"] = "danger";
        $alert["messageAlert"] = "Veuillez remplir toutes les informations créer un comte gestionnaire";
    }

    return $alert;
}
/*
$_POST["nomUtilisateur"] = "gestionax";
$_POST["motDePasse"] = "oui";
$_POST["email"] = "gestionax@gmail.com";

creerCompteGestionnaire();*/


// Valide un compte Élève
function validerCompteEleve($idEleve)
{

    $BDD = getBDD();

    $requeteUpdate = $BDD->prepare("UPDATE Eleve SET CompteValide=TRUE WHERE IdEleve = ?");
    $requeteUpdate->execute(array($idEleve));

    $alert["bootstrapClassAlert"] = "success";
    $alert["messageAlert"] = "Le compte élève n°$idEleve a bien été validé.";

    return $alert;
}
validerCompteEleve(1);



function supprimerCompteEleve($idEleve)
{
    $BDD = getBDD();

    // On recupère l'i'dentifiant de son compte grâce à son identifiant élève
    $requeteIdCompte = $BDD->prepare("SELECT Compte.IdCompte FROM Compte,Eleve WHERE Compte.IdCompte = Eleve.IdCompte AND IdEleve = ?");
    $requeteIdCompte->execute(array($idEleve));

    if ($requeteIdCompte->rowCount() > 0) {
        $idCompte = $requeteIdCompte->fetch()[0];

        // On supprime ses experiences professionnelles
        $requeteDeleteExperiencesPro = $BDD->prepare("DELETE FROM ExperiencePro WHERE IdEleve = ?");
        $requeteDeleteExperiencesPro->execute(array($idEleve));

        // On supprime ses informations personnelles
        $requeteDeleteInfosPerso = $BDD->prepare("DELETE FROM InfosPerso WHERE IdEleve = ?");
        $requeteDeleteInfosPerso->execute(array($idEleve));

        // On supprime l'élève
        $requeteDeleteEleve = $BDD->prepare("DELETE FROM Eleve WHERE IdEleve = ?");
        $requeteDeleteEleve->execute(array($idEleve));

        //On supprime son compte
        $requeteDeleteCompte = $BDD->prepare("DELETE FROM Compte WHERE IdCompte = ?");
        $requeteDeleteCompte->execute(array($idCompte));
    } else {
        throw new Exception("supprimerCompteEleve() : Pas de compte correspondant à cet identifiant.");
    }
}


// Invalide un compte Élève et le supprime
function invaliderCompteEleve($idEleve)
{
    $BDD = getBDD();

    supprimerCompteEleve($idEleve);

    $alert["bootstrapClassAlert"] = "danger";
    $alert["messageAlert"] = "Le compte élève n°$idEleve a été supprimé.";

    return $alert;
}

// Permet d'afficher un popup (appelé dans verifierExperiencesPro())
function afficherPopUp()
{
    require_once "../includes/fragments/popup.php";
}

// Verifie qu'un compte Élève a bien des experiences pro (lorsqu'il est connecté)
function verifierExperiencesPro()
{
    $BDD = getBDD();
    $nomUtilisateur = $_SESSION["nomUtilisateur"];

    $requeteExperiencesPro = $BDD->prepare("SELECT IdExperiencePro FROM ExperiencePro, Eleve, Compte WHERE ExperiencePro.IdEleve = Eleve.IdEleve AND Eleve.IdCompte = Compte.IdCompte AND NomUtilisateur = ?");
    $requeteExperiencesPro->execute(array($nomUtilisateur));

    if ($requeteExperiencesPro->rowCount() == 0) {
        afficherPopUp();
    }
}

//retourne toutes les informations du profil de quelqu'un de connecté
function getInfosPerso() {
    $BDD = getBDD();

    $nomUtilisateur = $_SESSION["nomUtilisateur"];
    //On recupère les infos personelles et les informations de compte de l'utilisateur
    $requeteInfosPerso = $BDD->PREPARE("SELECT * FROM Compte,Eleve,InfosPerso WHERE Compte.IdCompte = Eleve.IdCompte AND Eleve.IdEleve = InfosPerso.IdEleve AND Compte.NomUtilisateur = ?");
    $requeteInfosPerso->execute(array($nomUtilisateur));
    return $requeteInfosPerso->fetch();
}

//retourne les experiences professionnelles d'un élève connecté
function getExperiencesPro() {
    $BDD = getBDD();

    $nomUtilisateur = $_SESSION["nomUtilisateur"];
    //On recupère les experiences pro de l'utilisateur
    $requeteExperiencesPro= $BDD->PREPARE("SELECT * FROM Compte,Eleve,ExperiencePro WHERE Compte.IdCompte = Eleve.IdCompte AND Eleve.IdEleve = ExperiencePro.IdEleve AND Compte.NomUtilisateur = ?");
    $requeteExperiencesPro->execute(array($nomUtilisateur));
    return $requeteExperiencesPro->fetchAll();
}

// permet d'ajouter une experience professionnelle
// prend en compte le fait que certaines informations sont facultatives, et les ajoutes si elles sont présentes.
function ajouterExperiencePro(){
    if (!empty($_POST["intituleExperiencePro"]) && !empty($_POST["typeExperiencePro"]) && !empty($_POST["dateDebut"]) &&  !empty($_POST["typeOrganisation"]) && !empty($_POST["libelleOrganisation"]) && !empty($_POST["region"]) && !empty($_POST["ville"]) &&  !empty($_POST["typePoste"]) && !empty($_POST["secteursActivites"]) && !empty($_POST["domainesCompetences"])) {


        $BDD = getBDD();

        $intituleExperiencePro = escape($_POST["intituleExperiencePro"]);
        $typeExperiencePro = escape($_POST["typeExperiencePro"]);
        $dateDebutStr = escape($_POST["dateDebut"]);
        $dateDebut = date("Y-m-d H:i:s",strtotime($dateDebutStr));
        $typeOrganisation = escape($_POST["typeOrganisation"]);
        $region = escape($_POST["region"]);
        $ville = escape($_POST["ville"]);
        $typePoste = escape($_POST["ville"]);
        $secteursActivitesArray = $_POST["secteursActivites"];
        $domainesCompetencesArray = $_POST["domainesCompetences"];
        $libelleOrganisation = escape($_POST["libelleOrganisation"]);


        // On convertit le tableau de secteurs d'activités en strings séparés par des virgules
        $secteursActivites = "";
        foreach($secteursActivitesArray as $secteurAct){
            $secteursActivites .= escape($secteurAct) . ", ";
        }

        // On convertit le tableau de domaines de compétences en strings séparés par des virgules
        $domainesCompetences = "";
        foreach($domainesCompetencesArray as $domaineComp){
            $domainesCompetences .= escape($domaineComp) . ", ";
        }

        //On recupere le nom d'utilisateur et on requete la bdd pour avoir l'id de l'eleve afin de lui associer une nouvelle experience pro
        $nomUtilisateur = $_SESSION["nomUtilisateur"];
        $requeteIdEleve = $BDD->prepare("SELECT IdEleve FROM Compte,Eleve WHERE Compte.IdCompte = Eleve.IdCompte AND Compte.NomUtilisateur = ?");
        $requeteIdEleve->execute(array($nomUtilisateur));
        $idEleve = $requeteIdEleve->fetch()[0];

        //On insert les informations obligatoires de la nouvelle experience professionnelle
        $requeteInsertionExpPro = $BDD->prepare("INSERT INTO ExperiencePro(IntituleExperiencePro,TypeExperiencePro, DateDebut, TypeOrganisation, LibelleOrganisation, TypePoste, Region, Ville, SecteursActivites, DomainesCompetences, IdEleve) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        $requeteInsertionExpPro->execute(array($intituleExperiencePro,$typeExperiencePro,$dateDebut,$typeOrganisation,$libelleOrganisation,$typePoste,$region,$ville,$secteursActivites,$domainesCompetences,$idEleve));

        // On recupère l'id de l'experience pro insérée
        $idExperiencePro = $BDD->lastInsertId();


        // Si la date de fin de l'experience pro est donnée alors on l'ajoute également
        if(!empty($_POST["dateFin"])){
            $dateFinStr = escape($_POST["dateFin"]);
            $dateFin=date("Y-m-d H:i:s",strtotime($dateFinStr));
            $requeteUpdateDateFin = $BDD->prepare("UPDATE ExperiencePro SET DateFin=? WHERE IdExperiencePro=?");
            $requeteUpdateDateFin->execute(array($dateFin,$idExperiencePro));
        }

        // Si la description de l'experience pro est donnée alors on l'ajoute également
        if(!empty($_POST["description"])){
            $description = escape($_POST["description"]);
            $requeteUpdateDescription = $BDD->prepare("UPDATE ExperiencePro SET Description=? WHERE IdExperiencePro=?");
            $requeteUpdateDescription->execute(array($description,$idExperiencePro));
        }

        // Si le salaire de l'experience pro est donnée alors on l'ajoute également
        if(!empty($_POST["salaire"])){
            $salaire = escape($_POST["salaire"]);
            $requeteUpdateSalaire = $BDD->prepare("UPDATE ExperiencePro SET Salaire=? WHERE IdExperiencePro=?");
            $requeteUpdateSalaire->execute(array($salaire,$idExperiencePro));
        }

        $alert["bootstrapClassAlert"] = "success";
        $alert["messageAlert"] = "L'experience professionnelle a bien été ajoutée.";

    }else{
        $alert["bootstrapClassAlert"] = "danger";
        $alert["messageAlert"] = "Des informations sont manquantes.";
    }
    return $alert;
}
/*
$_SESSION["nomUtilisateur"] = "cweinreich";
$_POST["intituleExperiencePro"] = "Stage de 2ème année chez thales";
$_POST["typeExperiencePro"] = "Stage";
$_POST["dateDebut"] = "02-04-2023 10:29:39";
$_POST["typeOrganisation"] = "Entreprise";
$_POST["libelleOrganisation"] = "thales";
$_POST["typePoste"] = "Stagiaire";
$_POST["ville"] = "Avranches";
$_POST["region"] = "Normandie";
$_POST["secteursActivites"] = array("Transport","Aéronautique");
$_POST["domainesCompetences"] = array("IA","SHS","UX");
$_POST["dateFin"] = "02-06-2023 00:00:00";
$_POST["description"] = "gros stage";
$_POST["salaire"] = "2942";


$alert = ajouterExperiencePro();
print_r($alert);*/
