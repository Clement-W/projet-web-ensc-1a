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

function estCompteValide(){
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


function seConnecter(){
    if(!empty($_POST["nomUtilisateur"]) && !empty($_POST["motDePasse"])){
        $nomUtilisateur = $_POST["nomUtilisateur"];
        $mdp = $_POST["motDePasse"];

        // On récupère l'utilisateur dans la base de données 
        $requeteUtilisateur = getBDD()->prepare("SELECT * FROM Compte WHERE NomUtilisateur=? AND MotDePasse=?");
        $requeteUtilisateur->execute(array($nomUtilisateur,$mdp));

        // On vérifie qu'il y a bien un compte correspondant à ce nom d'utilisateur et ce mdp
        if($requeteUtilisateur->rowCount()==1){
            //le compte existe, on sauvegarde l'username dans la variable de session
            $_SESSION["nomUtilisateur"] = $nomUtilisateur;

            // on recupère l'id du compte
            $compte = $requeteUtilisateur->fetch();
            $idCompte = $compte["IdCompte"];
            
            // S'il y  aun resultat dans la table eleve avec cet identifiant c'est que ce compte est un compte élève,
            // sinon c'est un compte Gestionnaire.

            //faire une grosse requete avec une jointure pour avoir tout avec un id

            // S'il y a un resultat dans la table élève avec cet idCompte, alors c'est un élève, sinon c'est un gestionnaire.
            // On en profite pour récupèrer le nom de l'élève si s'en est un.
            $requeteTypeCompte =  getBdd()->prepare("SELECT Prenom, CompteValide FROM InfosPerso,Eleve WHERE Eleve.IdEleve = InfosPerso.IdEleve AND IdCompte = ?");
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
            $alert["bootstrapClassAlert"] = "success";
            $alert["messageAlert"] = "Vous êtes maintenant connecté.";
            
            //redirect('accueil.php');           

        }else{ // Il n'y a pas de compte correspondant à ces identifiants
            $alert["bootstrapClassAlert"] = "danger";
            $alert["messageAlert"] = "Aucun utilisateur ne correspond à ces informations.";
            
        }
        print_r($alert);


        return $alert;

    }
}

function inscription(){

    

}




