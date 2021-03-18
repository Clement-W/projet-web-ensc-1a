<?php

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

function estConnecte() {
    return isset($_SESSION['nomUtilisateur']);
}

function compteValide(){
    return $_SESSION['compteValide'];
}


function estGestionnaire() {
    return $_SESSION['estGestionnaire'];
}


function redirect($url) {
    header("Location: $url");
}


function escape($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
}


function connexion(){
    $BDD = getBDD();

    if(!empty($_POST["nomUtilisateur"]) && !empty($_POST["motDePasse"])){
        $nomUtilisateur = escape($_POST["nomUtilisateur"]);
        $mdp = escape($_POST["motDePasse"]);
        echo " " . $nomUtilisateur . " " . $mdp;

        // On récupère l'utilisateur dans la base de données 
        $requeteUtilisateur = $BDD->prepare("SELECT * FROM Compte WHERE NomUtilisateur=? AND MotDePasse=?");
        $requeteUtilisateur->execute(array($nomUtilisateur,$mdp));

        // On vérifie qu'il y a bien un compte correspondant à ce nom d'utilisateur et ce mdp
        if($requeteUtilisateur->rowCount()==1){
            //le compte existe, on sauvegarde l'username dans la variable de session
            $_SESSION["nomUtilisateur"] = $nomUtilisateur;

            // on recupère l'id du compte
            $compte = $requeteUtilisateur->fetch();
            $idCompte = $compte["IdCompte"];
            
            $requeteTypeCompte =  $BDD->prepare("SELECT Prenom, CompteValide FROM InfosPerso,Eleve WHERE Eleve.IdEleve = InfosPerso.IdEleve AND IdCompte = ?");
            $requeteTypeCompte->execute(array($idCompte));

            // S'il y a eu un resultat (élève)
            if($requeteTypeCompte->rowCount()==1){
                $_SESSION["estGestionnaire"] = false;
                
                $infosEleve = $requeteTypeCompte->fetch();
                $prenom = $infosEleve["Prenom"];
                $compteValide = $infosEleve["CompteValide"];

                $_SESSION["prenom"] = $prenom;
                $_SESSION["compteValide"] = $compteValide;
            }else{
                $_SESSION["estGestionnaire"] = true;        
            }

            // On affiche un feedback positif 
            if(estGestionnaire() || compteValide()){
                $alert["bootstrapClassAlert"] = "success";
                $alert["messageAlert"] = "Vous êtes maintenant connecté.";
            }else{ 
                $alert["bootstrapClassAlert"] = "success";
                $alert["messageAlert"] = "Votre compte a été créé. Veuillez attendre la validation de votre compte par un gestionnaire.";
            }
            
            //redirect('accueil.php');        

        }else{ // Il n'y a pas de compte correspondant à ces identifiants
            $alert["bootstrapClassAlert"] = "danger";
            $alert["messageAlert"] = "Aucun utilisateur ne correspond à ces informations.";  
        }

        print_r($alert);
   

    }else{ // Les champs n'ont pas été remplis
        $alert["bootstrapClassAlert"] = "danger";
        $alert["messageAlert"] = "Veuillez remplir tous les champs."; 
    }

    return $alert;
}

// genere un nom d'utilisateur de façon unique
function genererNomUtilisateur($nom,$prenom){
    // On retourne la première lettre du prenom suivi du nom de famille
    $res = strtolower($prenom[0] . $nom);
    //faire requete dans la BDD pour voir si un id comme ça existe, si oui on boucle en ajoutant un int à la fin
    // TODO: pour permettre de créer un max d'utilisateurs sans conflits
    return $res;
}

function verifierLongueur($mot,$limite){
    return strlen($mot) < $limite;
}

function raccourcirString($string,$limite){
    if(strlen($string) > $limite){
        $string = substr($string, 0, $limite);
    }
    return $string;
}

function inscription(){
    $BDD = getBDD();
    
    if(!empty($_POST["nom"]) && !empty($_POST["prenom"]) && !empty($_POST["motDePasse"]) && !empty($_POST["promo"]) && !empty($_POST["genre"]) && !empty($_POST["adresse"]) && !empty($_POST["ville"]) && !empty($_POST["codePostal"]) && !empty($_POST["email"]) && !empty($_POST["telephone"])){
        // On raccourcis le string si il dépasse la longueur spécifiée dans la BDD
        $limiteTailleStringBDD = 50;
        $nom = raccourcirString(escape($_POST["nom"]),$limiteTailleStringBDD);
        $prenom = raccourcirString(escape($_POST["prenom"]),$limiteTailleStringBDD);
        $nomUtilisateur = genererNomUtilisateur($nom,$prenom);
        $mdp = raccourcirString(escape($_POST["motDePasse"]),$limiteTailleStringBDD); 
        $promo = escape($_POST["promo"]);
        $genre = escape($_POST["genre"]);
        $adresse = raccourcirString(escape($_POST["adresse"]),$limiteTailleStringBDD);
        $ville = raccourcirString(escape($_POST["ville"]),$limiteTailleStringBDD);
        $codePostal = escape($_POST["codePostal"]);
        $email = raccourcirString(escape($_POST["email"]),$limiteTailleStringBDD);
        $telephone = escape($_POST["telephone"]);


        // On créé un compte associé au mail, nom d'utilisateur et mot de passe
        $requeteInsertionCompte = $BDD->prepare("INSERT INTO Compte(NomUtilisateur, MotDePasse, AdresseMail) VALUES (?,?,?)");
        $requeteInsertionCompte->execute(array($nomUtilisateur,$mdp,$email));

        // On recupère l'id du compte inséré
        $idCompte = $BDD->lastInsertId();

        echo $idCompte;
        // On créé un élève à partir de l'id du compte créé
        $requeteInsertionEleve = $BDD->prepare("INSERT INTO Eleve(CompteValide, IdCompte) VALUES (FALSE,?)");
        $requeteInsertionEleve->execute(array($idCompte));

        // On recupère l'id de l'élève inséré
        $idEleve = $BDD->lastInsertId();

        // On insert ses informations personnelles en base
        $requeteInsertionInfosPerso = $BDD->prepare("INSERT INTO InfosPerso(Nom, Prenom, Genre, Promotion, Adresse, Ville, CodePostal, NumTelephone, IdEleve) VALUES (?,?,?,?,?,?,?,?,?)");
        $requeteInsertionInfosPerso->execute(array($nom,$prenom,$genre,$promo,$adresse,$ville,$codePostal,$telephone,$idEleve));

        
        $_POST["nomUtilisateur"] = $nomUtilisateur; // pour pouvoir se connecter (le mot de passe est déjà dans la variable POST)
        $alert = connexion();
        

        //redirect("accueil.php");

    }else{ // Tous les champs n'ont pas été remplis
        $alert["bootstrapClassAlert"] = "danger";
        $alert["messageAlert"] = "Veuillez remplir toutes les informations pour vous inscrire.";  
    }

    return $alert;
}
/*
$_POST["nom"] = "Weinreich";
$_POST["prenom"] = "Clément";
$_POST["motDePasse"] = "so6j$";
$_POST["promo"] = 2023;
$_POST["genre"] = "M";
$_POST["adresse"] = "100B avenue roul";
$_POST["ville"] = "Talence";
$_POST["codePostal"] = 33400;
$_POST["email"] = "clement@weinreich.fr";
$_POST["telephone"] = 624396336;

$alert = inscription();*/








