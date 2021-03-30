<?php
/* MODULE DE PROGRAMMATION WEB
 * Rôle du fichier : 
 * Contient les fonctions php liées au gestionnaire.
 *
 *
 * Copyright 2021, MARQUETON Emma & WEINREICH Clément
 * https://ensc.bordeaux-inp.fr/fr
 *
 */

require_once("../includes/fonctionsBDD.php");


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


// Valide un compte Élève
function validerCompteEleve($idEleve)
{

    $BDD = getBDD();

    $requeteUpdate = $BDD->prepare("UPDATE Eleve SET CompteValide=TRUE WHERE IdEleve = ?");
    $requeteUpdate->execute(array($idEleve));
}

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

?>