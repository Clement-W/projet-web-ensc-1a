<?php

try{
    $BDD = new PDO("mysql:host=localhost;dbname=testWebUsers;charset=utf8","user","pass",array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
    // connexion serveur de Base de données MySQL et choix de base
}catch(Exception $e){
    die('Erreur fatale' . $e->getMessage());
}

if ($BDD) {

    $querySettings = $BDD->prepare("SELECT * FROM Settings WHERE IdEleve=1");
    $querySettings->execute();

    // Normalement c'est ça : 
        //$queryInfoPerso = $BDD->prepare("SELECT * FROM InfosPerso WHERE IdEleve=?");
        // mais pas envie de recréer la bdd pour l'instant donc je fais un équivalent mais ce sera pareil (je crois) :
    $queryInfosPerso = $BDD->prepare("SELECT * FROM InfosPerso WHERE IdInfosPersos=1");
    $queryInfosPerso->execute();
    $infos = $queryInfosPerso->fetch();


    while($tuple= $querySettings -> fetch()){
        $nomParam = $tuple["nomParam"];
        $valeur = $infos[$nomParam];
        $visibilite = $tuple["visibilite"];

        echo "Nomparam : " . $nomParam . " --- valeur : " . $valeur . " --- visibilite : " . $visibilite . "<br/>";

    }


} else {
    echo "oof probleme de connexion à la bdd";
}



?>


