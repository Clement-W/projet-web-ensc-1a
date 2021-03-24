<?php
require_once("functions.php");
$BDD = getBDD();

if (isset($_POST['search'])) {
    $search_val = $_POST['search_term'];
    $search_filter = $_POST['search_param'];
    if ($search_val != "") {

        // Si le filtre correspond à un élement de la table ExperiencePro : 
        if($search_filter=="TypeExperiencePro" || $search_filter=="TypeOrganisation" || $search_filter=="LibelleOrganisation" || $search_filter=="Region" || $search_filter=="SecteursActivites" || $search_filter=="DomainesCompetences"){
            //$requeteExperiencePro = $BDD->prepare("SELECT * FROM ExperiencePro, ")
        }

        $req = $BDD->prepare("select * from ExperiencePro where $search_filter LIKE '%$search_val%'");
        $req->execute();
        while ($row = $req->fetch()) {    
            echo "<li>" . $row["TypeOrganisation"] . " ; " . $row["Description"] ." ; " .$row["SecteursActivites"] ." ; ".$row["DomainesCompetences"] ."</li>" . '<br/>';
        }
    }   
    exit();
}
