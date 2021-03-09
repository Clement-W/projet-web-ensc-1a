<?php

try{
    $BDD = new PDO("mysql:host=localhost;dbname=annuaireEleves;charset=utf8","annuaireUser","explodingkittens",array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
    // connexion serveur de Base de données MySQL et choix de base
}catch(Exception $e){
    die('Erreur fatale' . $e->getMessage());
}

if ($BDD) {

    $id = 1;
    $querySettings = $BDD->prepare("SELECT * FROM Parametres WHERE IdEleve=?");
    $querySettings->execute(array($id));
 
    $queryInfosPerso = $BDD->prepare("SELECT * FROM InfosPerso WHERE IdEleve=?");
    $queryInfosPerso->execute(array($id));
    $infos = $queryInfosPerso->fetch(); //fetch seul car un seul resultat

    $queryExperiencePro = $BDD->prepare("SELECT * FROM ExperiencePro WHERE IdEleve=?");
    $queryExperiencePro->execute(array($id));
    $experiences = $queryExperiencePro->fetchAll();






    while($tuple= $querySettings -> fetch()){
        $nomParam = $tuple["LibelleInformation"];
        if(is_numeric($nomParam)){

            foreach($experiences as $experience){
                if($experience["IdExperiencePro"]==(int)$nomParam){
                    $valeur = $experience["TypeExperiencePro"] . " " .$experience["Lieu"];
                    break;
                }
            }
            
        }else{
            $valeur = $infos[$nomParam];
        }
        $visibilite = $tuple["Visibilite"];
        

        echo "LibelleInformation : " . $nomParam . " --- valeur : " . $valeur . " --- visibilite : " . $visibilite . "<br/>";
    }


} else {
    echo "oof probleme de connexion à la bdd";
}



?>


