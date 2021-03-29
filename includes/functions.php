<?php
/* MODULE DE PROGRAMMATION WEB
 * Rôle du fichier : 
 * Contient toutes les fonctions php utilisées dans notre code
 *
 *
 * Copyright 2021, MARQUETON Emma & WEINREICH Clément
 * https://ensc.bordeaux-inp.fr/fr
 *
 */


function getBDD()
{
    $server = "localhost";
    $username = "annuaireUser";
    $password = "explodingkittens"; // très bon jeu de société
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

function estUnCompteValide($idCompte)
{
    $BDD = getBDD();
    $requeteCompteValide =  $BDD->prepare("SELECT CompteValide FROM Eleve WHERE IdCompte = ?");
    $requeteCompteValide->execute(array($idCompte));
    if ($requeteCompteValide->rowCount() == 0) {
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

        $nomUtilisateur = escape($_POST["nomUtilisateur"]);
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

    unset($_POST);
    $_POST = array();

    $_SESSION["alert"] = $alert;
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
}
//validerCompteEleve(2);



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

        // On supprime ses paramètres
        $requeteDeleteParametres = $BDD->prepare("DELETE FROM Parametres WHERE IdEleve = ?");
        $requeteDeleteParametres->execute(array($idEleve));

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


// Permet d'afficher la popup d'alerte qui indique que l'utilisateur n'a pas d'experience pro
function afficherPopUpExperiencePro()
{
    require_once "../includes/modals/alertePasDExperiencePro.php";
}

// Verifie qu'un compte Élève a bien des experiences pro (lorsqu'il est connecté)
// retourne true s'il en a, false sinon
function verifierExperiencesPro()
{
    $BDD = getBDD();
    $nomUtilisateur = $_SESSION["nomUtilisateur"];

    $requeteExperiencesPro = $BDD->prepare("SELECT IdExperiencePro FROM ExperiencePro, Eleve, Compte WHERE ExperiencePro.IdEleve = Eleve.IdEleve AND Eleve.IdCompte = Compte.IdCompte AND NomUtilisateur = ?");
    $requeteExperiencesPro->execute(array($nomUtilisateur));

    if ($requeteExperiencesPro->rowCount() == 0) {
        return false;
    }
    return true;
}

//retourne toutes les informations du profil de quelqu'un de connecté
function getInfosPerso()
{
    $BDD = getBDD();

    $nomUtilisateur = $_SESSION["nomUtilisateur"];
    //On recupère les infos personelles et les informations de compte de l'utilisateur
    $requeteInfosPerso = $BDD->PREPARE("SELECT * FROM Compte,Eleve,InfosPerso WHERE Compte.IdCompte = Eleve.IdCompte AND Eleve.IdEleve = InfosPerso.IdEleve AND Compte.NomUtilisateur = ?");
    $requeteInfosPerso->execute(array($nomUtilisateur));
    return $requeteInfosPerso->fetch();
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

//retourne les experiences professionnelles d'un élève connecté
function getExperiencesPro()
{
    $BDD = getBDD();

    $nomUtilisateur = $_SESSION["nomUtilisateur"];
    //On recupère les experiences pro de l'utilisateur
    $requeteExperiencesPro = $BDD->PREPARE("SELECT * FROM Compte,Eleve,ExperiencePro WHERE Compte.IdCompte = Eleve.IdCompte AND Eleve.IdEleve = ExperiencePro.IdEleve AND Compte.NomUtilisateur = ? ORDER BY ExperiencePro.DateDebut DESC");
    $requeteExperiencesPro->execute(array($nomUtilisateur));
    return $requeteExperiencesPro->fetchAll();
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

//formate le format date de mysql pour l'afficher dans les experiences pro
function formaterDateExperiencePro($date)
{
    if ($date == null) {
        return "";
    } else {
        $mois = substr($date, 5, 2);
        $annee = substr($date, 0, 4);

        $newDate = $mois . "/" . $annee;
        return $newDate;
    }
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

function creerCompteEleveParGestionnaire()
{
    $BDD = getBDD();

    if (!empty($_POST["prenom"]) && !empty($_POST["nom"]) && !empty($_POST["motDePasse"]) && !empty($_POST["promo"]) && !empty($_POST["mail"])) {

        $nom = ucwords(strtolower(escape($_POST["nom"]))); // on met la première lettre en majuscule (on met tout en minuscule avant pour être sûr que ce soit apreil pour tout le monde)
        $prenom = ucwords(strtolower(escape($_POST["prenom"])));
        $nomUtilisateur = genererNomUtilisateur($nom, $prenom);
        $mdp = escape($_POST["motDePasse"]);
        $promo = escape($_POST["promo"]);
        $mail = escape($_POST["mail"]);

        // On créé un compte associé au mail, nom d'utilisateur et mot de passe
        $requeteInsertionCompte = $BDD->prepare("INSERT INTO Compte(NomUtilisateur, MotDePasse, AdresseMail) VALUES (?,?,?)");
        $requeteInsertionCompte->execute(array($nomUtilisateur, $mdp, $mail));

        // On recupère l'id du compte inséré
        $idCompte = $BDD->lastInsertId();

        // On créé un élève à partir de l'id du compte créé
        $requeteInsertionEleve = $BDD->prepare("INSERT INTO Eleve(CompteValide, IdCompte) VALUES (TRUE,?)");
        $requeteInsertionEleve->execute(array($idCompte));

        // On recupère l'id de l'élève inséré
        $idEleve = $BDD->lastInsertId();

        // On insert ses informations personnelles en base
        $requeteInsertionInfosPerso = $BDD->prepare("INSERT INTO InfosPerso(Nom, Prenom, Promotion, IdEleve) VALUES (?,?,?,?)");
        $requeteInsertionInfosPerso->execute(array($nom, $prenom, $promo, $idEleve));

        // Visibilite des informations personnelles
        $informationsParametrable =  array("Genre", "Adresse", "Ville", "CodePostal", "NumTelephone", "AdresseMail"); // Contient les noms des champs dont la visibilité est parametrable
        foreach ($informationsParametrable as $libelleInformation) { // pour chaque information du compte dont la visibilité est modifiable
            insererParametre($BDD, $idEleve, true, $libelleInformation); // on insert en base le parametre comme visible
        }
        $alert["bootstrapClassAlert"] = "success";
        $alert["messageAlert"] = "Le compte a bien été créé.";
    } else {
        $alert["bootstrapClassAlert"] = "danger";
        $alert["messageAlert"] = "Une erreur est survenue, le compte n'a pas pu être créé.";
    }

    unset($_POST);
    $_POST = array();

    $_SESSION["alert"] = $alert;
}


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

function getCSVDepuisTelechargement($chemin_fichier)
{

    if ($_FILES["templateUploaded"]["size"] <= 500000) { // Si la taille du fichier dépasse 500kb, 

        if (move_uploaded_file($_FILES["templateUploaded"]["tmp_name"], $chemin_fichier)) {

            return true;
        }
    }
    return false;
}


function creerComptesElevesDepuisCSV()
{

    if (!empty($_POST["validerFileUpload"])) {

        $repertoire = "../temp/";
        $chemin_fichier = $repertoire . basename($_FILES["templateUploaded"]["name"]);

        if (getCSVDepuisTelechargement($chemin_fichier)) {
            $csvTemplate = file($chemin_fichier);


            foreach ($csvTemplate as $infosEleve) {
                $infosEleve = explode(",", escape($infosEleve)); // on tranasforme la ligne en un tableau contenant les champs (on aurait également pu utiliser les méthodes de lecture de csv de php)

                if ($infosEleve[0] == "Nom") {
                    // Cela correspond aux intitulés du template, on analyse donc pas cette ligne
                    continue;
                }


                //on analyse les infos d'un élève pour verifier la validiter des inputs
                // la csv a toujours la même forme donc on peut utiliser des indices fixes
                $nom = ucwords(strtolower(trim($infosEleve[0]))); // on trim pour enlever les espaces au début et à la fin
                $prenom = ucwords(strtolower(trim($infosEleve[1])));
                $mdp = trim($infosEleve[2]);
                $mail = trim($infosEleve[3]);

                // On controle la taille pour être cohérent avec la bdd
                if (strlen($nom) < 50 && strlen($nom) > 0 && strlen($prenom) < 50 && strlen($prenom) > 0 && strlen($mdp) < 50 && strlen($mdp) > 0 && strlen($mail) < 50 && strlen($mail) > 0) {

                    //on contrôle que le mail est bien un email valide
                    if (filter_var($mail, FILTER_VALIDATE_EMAIL) !== false) {

                        //Tout est ok, on peut insérer cet élève.
                        $BDD = getBDD();

                        $nomUtilisateur = genererNomUtilisateur($nom, $prenom);

                        // On créé un compte associé au mail, nom d'utilisateur et mot de passe
                        $requeteInsertionCompte = $BDD->prepare("INSERT INTO Compte(NomUtilisateur, MotDePasse, AdresseMail) VALUES (?,?,?)");
                        $requeteInsertionCompte->execute(array($nomUtilisateur, $mdp, $mail));

                        // On recupère l'id du compte inséré
                        $idCompte = $BDD->lastInsertId();

                        // On créé un élève à partir de l'id du compte créé
                        $requeteInsertionEleve = $BDD->prepare("INSERT INTO Eleve(CompteValide, IdCompte) VALUES (TRUE,?)");
                        $requeteInsertionEleve->execute(array($idCompte));

                        // On recupère l'id de l'élève inséré
                        $idEleve = $BDD->lastInsertId();

                        // On insert ses informations personnelles en base
                        $requeteInsertionInfosPerso = $BDD->prepare("INSERT INTO InfosPerso(Nom, Prenom, IdEleve) VALUES (?,?,?)");
                        $requeteInsertionInfosPerso->execute(array($nom, $prenom, $idEleve));

                        // Visibilite des informations personnelles
                        $informationsParametrable =  array("Genre", "Adresse", "Ville", "CodePostal", "NumTelephone", "AdresseMail"); // Contient les noms des champs dont la visibilité est parametrable
                        foreach ($informationsParametrable as $libelleInformation) { // pour chaque information du compte dont la visibilité est modifiable
                            insererParametre($BDD, $idEleve, true, $libelleInformation); // on insert en base le parametre comme visible
                        }
                    } else {
                        $alert["bootstrapClassAlert"] = "danger";
                        $alert["messageAlert"] = "Le mail '$mail' n'est pas valide.";
                        break;
                    }
                } else {
                    $alert["bootstrapClassAlert"] = "danger";
                    $alert["messageAlert"] = "Il y a un problème dans les valeurs de l'élève $nom $prenom";
                    break;
                }
            }
        } else {
            $alert["bootstrapClassAlert"] = "danger";
            $alert["messageAlert"] = "Un problème a été rencontré lors de la lecture du fichier.";
        }
    }

    if (!isset($alert)) {
        $alert["bootstrapClassAlert"] = "success";
        $alert["messageAlert"] = "Tous les comptes ont bien été créés.";
    }


    unlink($chemin_fichier);

    unset($_POST);
    $_POST = array();

    $_SESSION["alert"] = $alert;
}
