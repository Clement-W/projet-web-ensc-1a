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

// Genere un nom d'utilisateur de façon unique pour un élève
function genererNomUtilisateur($nom, $prenom)
{
    $BDD = getBDD();
    $nomUtilisateurBase = strtolower($prenom[0] . $nom); // Première lettre du prenom suivi du nom de famille
    $dejaUtilise = true;
    $cpt = 0;

    while ($dejaUtilise) { // Tant que le pseudo existe, on incremente le compteur qui s'ajoute à a fin du pseudo (exemple : emarqueton8)
        if ($cpt != 0) { // Si l'username de base nom+prenom existe, alors on ajoute un indice à la fin
            $nomUtilisateur = $nomUtilisateurBase . $cpt;
        } else {
            $nomUtilisateur = $nomUtilisateurBase;
        }
        $requeteUtilisateur = $BDD->prepare("SELECT NomUtilisateur FROM Compte WHERE NomUtilisateur=?"); // On requete les noms d'utilisateurs de la base qui sont égaux à celui-ci
        $requeteUtilisateur->execute(array($nomUtilisateur));

        if ($requeteUtilisateur->rowCount() == 1) { //S'il y en a un alors on incrémente le compteur
            $cpt++;
        } else {
            $dejaUtilise = false;
        }
    }

    return $nomUtilisateur;
}

// Insert un paramètre de visibilité dans la bdd
function insererParametre($BDD, $idEleve, $visibilite, $libelleInformation)
{
    $requeteInsertionParametre = $BDD->prepare("INSERT INTO Parametres(LibelleInformation, Visibilite, IdEleve) VALUES (?,?,?)");
    $requeteInsertionParametre->execute(array($libelleInformation, $visibilite, $idEleve));
}


// Permet à un élève de s'inscrire sur l'annuaire
function inscription()
{
    $BDD = getBDD();

    if (!empty($_POST["nom"]) && !empty($_POST["prenom"]) && !empty($_POST["motDePasse"]) && !empty($_POST["promo"]) && !empty($_POST["genre"]) && !empty($_POST["adresse"]) && !empty($_POST["ville"]) && !empty($_POST["codePostal"]) && !empty($_POST["email"]) && !empty($_POST["telephone"])) {

        $nom = ucwords(strtolower(escape($_POST["nom"]))); // on met la première lettre en majuscule (on met tout en minuscule avant pour être sûr que ce soit pareil pour tout le monde)
        $prenom = ucwords(strtolower(escape($_POST["prenom"])));
        $nomUtilisateur = genererNomUtilisateur($nom, $prenom);
        $mdp = escape($_POST["motDePasse"]);
        $mdpHash = hash("sha512",$mdp); // Pour ne pas stocker le mdp en clair dans la bdd on hash le mdp en sha512
        $promo = escape($_POST["promo"]);
        $genre = escape($_POST["genre"]);
        $adresse = escape($_POST["adresse"]);
        $ville = escape($_POST["ville"]);
        $codePostal = escape($_POST["codePostal"]);
        $email = escape($_POST["email"]);
        $telephone = escape($_POST["telephone"]);

        // On créé un compte associé au mail, nom d'utilisateur et mot de passe
        $requeteInsertionCompte = $BDD->prepare("INSERT INTO Compte(NomUtilisateur, MotDePasse, AdresseMail) VALUES (?,?,?)");
        $requeteInsertionCompte->execute(array($nomUtilisateur, $mdpHash, $email));

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

        // On insert les parametres de visibilite des informations personnelles en base
        $informationsParametrable =  array("Genre", "Adresse", "Ville", "CodePostal", "NumTelephone", "AdresseMail"); // Contient les noms des champs dont la visibilité est parametrable
        // Il serait plus pertinent d'utiliser un fichier de config pour récupèrer les informations modifiables car si cela vient à bouger, il faudra modifier le code
        foreach ($informationsParametrable as $libelleInformation) { // pour chaque information du compte dont la visibilité est modifiable
            insererParametre($BDD, $idEleve, true, $libelleInformation); // on insert en base le parametre comme visible
        }

        $_SESSION["nomUtilisateurCompteNonValide"] = $nomUtilisateur; // permet de transmettre le nom d'utilisateur à la personne qui a créé le compte 

        redirect("attenteValidation.php");

    } else { // Tous les champs n'ont pas été remplis 
        $alert["bootstrapClassAlert"] = "danger";
        $alert["messageAlert"] = "Veuillez remplir toutes les informations pour vous inscrire.";
    }

    $_SESSION["alert"] = $alert; // Si le message d'alert est levé, on le met dans la variable de session pour qu'il soit affiché sur la page d'accueil
}


// Permet d'afficher la popup d'alerte qui indique que l'utilisateur n'a pas d'experience pro
function afficherPopUpExperiencePro()
{
    require_once("../includes/modals/alertePasDExperiencePro.php");
}



// Retourne les informations d'un élève à partir de son id
function getInfosCompteEleveParId($id)
{
    $BDD = getBDD();

    //On recupère les infos personelles et les informations de compte de l'utilisateur
    $requeteInfosPerso = $BDD->PREPARE("SELECT * FROM Compte,Eleve,InfosPerso WHERE Compte.IdCompte = Eleve.IdCompte AND Eleve.IdEleve = InfosPerso.IdEleve AND Eleve.IdEleve = ?");
    $requeteInfosPerso->execute(array($id));
    return $requeteInfosPerso->fetch();
}


// Verifie que l'id passé en paramètre est bien présent en base
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



// Retourne les experiences professionnelles d'un élève à partir de son id
function getExperiencesProParId($id)
{
    $BDD = getBDD();

    //On recupère les experiences pro de l'utilisateur
    $requeteExperiencesPro = $BDD->PREPARE("SELECT * FROM Eleve,ExperiencePro WHERE Eleve.IdEleve = ExperiencePro.IdEleve AND Eleve.IdEleve = ? ORDER BY ExperiencePro.DateDebut DESC");
    $requeteExperiencesPro->execute(array($id));
    return $requeteExperiencesPro->fetchAll();
}


// Permet d'ajouter une experience professionnelle
// Prend en compte le fait que certaines informations sont facultatives, mais les insert si elles sont présentes.
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
        $secteursActivites = escape($_POST["SecteursActivites"]);
        $domainesCompetences = escape($_POST["DomainesCompetences"]);
        $libelleOrganisation = escape($_POST["LibelleOrganisation"]);
        $visibilite = (isset($_POST["visibiliteExpPro"])) ? 1 : 0; //Si la checkbox est checked alors c'est envoyé par le post, sinon non, on teste donc cela avec la fonction isset
        // On donne la valeur 1 ou 0 pour être compatible avec le tinyint de mysql

        $idEleve = getIdEleveParNomUtilisateur($_SESSION["nomUtilisateur"]);

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

        // On ajoute le parametre de visibilité en bas
        // Pour une experience pro, le libelle correspond à l'identifiant de l'experience pro.
        insererParametre($BDD, $idEleve, $visibilite, $idExperiencePro);

        $alert["bootstrapClassAlert"] = "success";
        $alert["messageAlert"] = "L'experience professionnelle a bien été ajoutée.";
    } else {
        $alert["bootstrapClassAlert"] = "danger";
        $alert["messageAlert"] = "Des informations sont manquantes.";
    }

    $_SESSION["alert"] = $alert;

    redirect("profil.php?idEleve=" . $idEleve);
}

// Permet de récupérer l'id de l'élève à partir de son nom d'utilisateur
function getIdEleveParNomUtilisateur($nomUtilisateur)
{
    $BDD = getBDD();
    $requeteIdEleve = $BDD->prepare("SELECT IdEleve FROM Compte, Eleve WHERE Compte.IdCompte=Eleve.IdCompte AND Compte.NomUtilisateur = ?");
    $requeteIdEleve->execute(array($nomUtilisateur));
    $idEleve = $requeteIdEleve->fetch()[0];
    return $idEleve;
}


//retourne un tableau associatif contenant la visibilité des différents paramètres
// par exemple : ["Genre"] = False ; ["Adresse"] = True ; ....
// Cela permet de gérer plus facilement les problèmes d'affichage liés à la visibilité de certains paramètres
function getVisibiliteInfosProfil($idEleve)
{
    $BDD = getBDD();

    // On récupère les libéllés et la visibilité associée des paramètres d'un élève par son identifiant
    $requeteParametres = $BDD->prepare("SELECT LibelleInformation,Visibilite FROM Parametres WHERE IdEleve = ?");
    $requeteParametres->execute(array($idEleve));
    $parametres = $requeteParametres->fetchAll();

    // On créé le tableau associatif
    $visibilite = array();
    foreach ($parametres as $parametre) {
        $visibilite[$parametre["LibelleInformation"]] = $parametre["Visibilite"];
    }

    return $visibilite;
}

// Permet de mettre à jour le profil d'un élève
function mettreAJourProfil()
{
    if (!empty($_POST["mettreAJourProfil"])) {
        $BDD = getBDD();
        $miseAJour = false; // Sera true si on fait une mise à jour dans le code suivant


        /* Modification des champs d'information personelle */

        $idEleve = getIdEleveParNomUtilisateur($_SESSION["nomUtilisateur"]);
        $infos = getInfosCompteEleveParId($idEleve);
        // Une fois de plus, un fichier de config aurait été plus pertinent pour stocker les noms des champs modifiables
        $labelsInfosPerso = array("Nom", "Prenom", "Promotion", "Genre", "Adresse", "Ville", "CodePostal", "NumTelephone"); // Ne contient pas l'adresse mail car c'est pas dans la table info perso (on le fait à part)

        // On verifie si les infos perso ont été modifiées
        foreach ($labelsInfosPerso as $label) {
            if ($infos[$label] != escape($_POST[$label])) { // On modifie la bdd uniquement si c'est différent
                $requeteUpdateInfoPerso = $BDD->prepare("UPDATE InfosPerso SET $label=? WHERE IdEleve=?"); // On ne peut pas passer un nom de colonne par un parametre du prepare et on connait les valeurs de $label donc pas de problème de sécurité
                $requeteUpdateInfoPerso->execute(array(escape($_POST[$label]), $idEleve));
                $miseAJour = true;
            }
        }


        // On verifie si l'adresse mail a été modifiée
        if ($infos["AdresseMail"] != escape($_POST["AdresseMail"])) {
            $requeteUpdateMail = $BDD->prepare("UPDATE Compte SET AdresseMail=? WHERE nomUtilisateur=?");
            $requeteUpdateMail->execute(array(escape($_POST["AdresseMail"]), $_SESSION["nomUtilisateur"]));

            $miseAJour = true;
        }


        /* Modification de la visibilité des champs des infos perso */

        $parametres = getVisibiliteInfosProfil($idEleve); // tableau associatif contenant le nom du champ et sa visibilité associée

        $labelsVisibiliteInfosProfil = array("Adresse", "Ville", "CodePostal", "AdresseMail", "NumTelephone", "Genre"); // Les champs dont la visibilité est modifiable
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
                $idExperiencePro = $experience["IdExperiencePro"]; // on recupere l'id de l'experience pro car les noms des key du $_POST sont sous la forme "NomChampIdExperiencePro" (ex: LibelleOrganisation3)
                if ($experience[$label] != escape($_POST[$label . $idExperiencePro])) { // Si le champ de l'experience est différent du champ passé dans le post pour cette experience alors on update la bdd
                    $requeteUpdateExperiencePro = $BDD->prepare("UPDATE ExperiencePro SET $label=? WHERE IdExperiencePro = ?");
                    $requeteUpdateExperiencePro->execute(array(escape($_POST[$label . $idExperiencePro]), $idExperiencePro));
                    $miseAJour = true;
                }
            }
            /* Modification de la visiblité des experiences pro */
            // (On profite de boucler dans les experiences pro pour gerer la visibilité)
            $visibilite = (isset($_POST["visibiliteExpPro" . $idExperiencePro])) ? 1 : 0; // Si ce champs du post n'est pas set c'est que la checkbox n'est pas cochée, s'il est set alors c'est que la checkbox est cochée
            if ($parametres[$idExperiencePro] != $visibilite) { // Si le parametre de visibilité est différent entre la valeur du form et la bdd alors on update
                // pour rappel dans les parametres, les experiences pro sont identifiées dans LibelleInformation par leur identifiant
                $requeteUpdateParametres = $BDD->prepare("UPDATE Parametres SET Visibilite=? WHERE IdEleve = ? AND LibelleInformation = ?");
                $requeteUpdateParametres->execute(array($visibilite, $idEleve, $idExperiencePro));
                $miseAJour = true;
            }
        }

        if ($miseAJour) {
            $alert["bootstrapClassAlert"] = "success";
            $alert["messageAlert"] = "Le profil a bien été mis à jour";
            $_SESSION["alert"] = $alert;
        } // Feedback à l'utilisateur pour confirmer la modification de son profil


        redirect("profil.php?idEleve=" . $idEleve);
    }
}

// Retourne la liste des comptes non validés
function getComptesNonValide()
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
    if ($requetePossedeExperiencePro->rowCount() == 0) { // Si aucune expérience n'a été rentré, on return false
        return false;
    } else { // S'il est présent alors on return true
        return true;
    }
}
?>