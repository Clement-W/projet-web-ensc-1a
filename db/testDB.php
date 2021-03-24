<?php

try{
    $BDD = new PDO("mysql:host=localhost;dbname=annuaireEleves;charset=utf8","annuaireUser","explodingkittens",array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
    // connexion serveur de Base de données MySQL et choix de base
}catch(Exception $e){
    die('Erreur fatale' . $e->getMessage());
}

if ($BDD) {


    // On recpuere les paramètres correspondant à l'élève 1
    $id = 3;
    $querySettings = $BDD->prepare("SELECT * FROM Parametres WHERE IdEleve=?");
    $querySettings->execute(array($id));
 
    // On récupère les informations personelles de l'élève 1
    $queryInfosPerso = $BDD->prepare("SELECT * FROM InfosPerso WHERE IdEleve=?");
    $queryInfosPerso->execute(array($id));
    $infos = $queryInfosPerso->fetch(); //fetch seul car un seul resultat

    // On récupère les experiences pro de l'élève 1
    $queryExperiencePro = $BDD->prepare("SELECT * FROM ExperiencePro WHERE IdEleve=?");
    $queryExperiencePro->execute(array($id));
    $experiences = $queryExperiencePro->fetchAll();

    // On récupère l'adresse mail de l'élève 1 :
    $queryCompte = $BDD->prepare("SELECT AdresseMail FROM Compte,Eleve WHERE Compte.IdCompte = Eleve.IdCompte AND IdEleve=?");
    $queryCompte->execute(array($id));
    $adresseMail = $queryCompte->fetch()[0]; //fetch seul car un seul resultat  , et on prend l'unique valeur du tableau qui est retourné avec [0]


    while($tuple= $querySettings -> fetch()){
        $nomParam = $tuple["LibelleInformation"];
        if(is_numeric($nomParam)){ // SI c'est numérique c'est une experience pro

            foreach($experiences as $experience){
                if($experience["IdExperiencePro"]==(int)$nomParam){
                    $valeur = $experience["TypeExperiencePro"] . " " .$experience["Lieu"];
                    break;
                }
            }
            
        }else{ 
            if($nomParam=="AdresseMail"){ // On gère le cas particulier de l'adresse mail qui est une information de compte et pas une information personelle
                $valeur = $adresseMail;
            }else{
                $valeur = $infos[$nomParam];
            }
        }
        $visibilite = $tuple["Visibilite"];
        

        echo "LibelleInformation : " . $nomParam . " --- valeur : " . $valeur . " --- visibilite : " . $visibilite . "<br/>";
    }


} else {
    echo "oof probleme de connexion à la bdd";
}



?>


