<?php
require_once("functions.php");
$BDD = getBDD();

if (isset($_POST['search'])) {
    $search_val = escape($_POST['search_term']);
    $search_filter = escape($_POST['search_param']);
    if ($search_val != "") {

        // Si le filtre correspond à un élement de la table ExperiencePro : 
        if ($search_filter == "TypeExperiencePro" || $search_filter == "TypeOrganisation" || $search_filter == "LibelleOrganisation" || $search_filter == "Region" || $search_filter == "SecteursActivites" || $search_filter == "DomainesCompetences") {
            $requeteExperiencePro = $BDD->prepare("SELECT * FROM ExperiencePro, Parametres WHERE ExperiencePro.IdEleve = Parametres.IdEleve AND ExperiencePro.IdExperiencePro = Parametres.LibelleInformation AND $search_filter LIKE CONCAT('%',?,'%')");  // Probleme : Si on passe $search_filter avec un ? dans le execute, ça ne fonctionne pas
            $requeteExperiencePro->execute(array($search_val));  
            //echo $search_filter . " " . $search_val;
            
            while ($experiencePro = $requeteExperiencePro->fetch()) {
                if ($experiencePro["Visibilite"] == true) {
                    echo "<li>" . $experiencePro["IntituleExperiencePro"] . " ; " . $experiencePro["TypeOrganisation"] . " ; ". $experiencePro["LibelleOrganisation"] . " ; " . $experiencePro["TypePoste"] . " ; " . $experiencePro["Region"] . " ; " . $experiencePro["SecteursActivites"] . " ; " . $experiencePro["DomainesCompetences"] . "</li>" . '<br/>';
                }
            }
        } 

        //TODO : REPRENDRE ICI 
        // Si le filtre correspond au nom ou au prenom
        else if($search_filter == "NomPrenom"){

            $requeteNom = $BDD->prepare("SELECT * FROM InfosPerso, Parametres WHERE InfosPerso.IdEleve = Parametres.IdEleve AND ExperiencePro.IdExperiencePro = Parametres.LibelleInformation AND $search_filter LIKE CONCAT('%',?,'%')");  // Probleme : Si on passe $search_filter avec un ? dans le execute, ça ne fonctionne pas
            $requeteNom->execute(array($search_val)); 
            
        }

        // Si le filtre correspond à la promotion ou à la ville (table infos perso)
        else if($search_filter == "Promotion" || $search_filter == "Ville"){

        }

    }
    exit();
}
