<?php
require_once("functions.php");
$BDD = getBDD();

if (isset($_POST['search'])) {
    $search_val = escape($_POST['search_term']);
    $search_filter = escape($_POST['search_param']);
    if ($search_val != "") {

        // Si le filtre correspond à un élement de la table ExperiencePro : 
        if ($search_filter == "TypeExperiencePro" || $search_filter == "TypeOrganisation" || $search_filter == "LibelleOrganisation" || $search_filter == "Region" || $search_filter == "SecteursActivites" || $search_filter == "DomainesCompetences") {
            $requeteExperiencePro = $BDD->prepare("SELECT * FROM ExperiencePro, Parametres, InfosPerso WHERE ExperiencePro.IdEleve = Parametres.IdEleve AND ExperiencePro.IdEleve = InfosPerso.IdEleve AND ExperiencePro.IdExperiencePro = Parametres.LibelleInformation AND $search_filter LIKE CONCAT('%',?,'%')");  // Probleme : Si on passe $search_filter avec un ? dans le execute, ça ne fonctionne pas, donc pour l'instant on donne la variable dans le prepare (ce n'est pas une entrée utilisateur)
            $requeteExperiencePro->execute(array($search_val));
            //echo $search_filter . " " . $search_val;

            while ($experiencePro = $requeteExperiencePro->fetch()) {
                if ($experiencePro["Visibilite"] == true) { // Si l'experiece pro est visible alors on la présente dans les résultats
                    echo '<div class="whitecontainer mt-3 mb-3"> 
                    <div class="ml-4 row text-secondary">
                        <div class="col-md-6 h5">
                            <div class="col-md-12">
                                <div class="affichageProfil h3">' . $experiencePro["Prenom"] . " " . $experiencePro["Nom"] . " - Promotion " . $experiencePro["Promotion"] . '</div> </hr>' .
                        '<div class="affichageProfil"><u>' . $experiencePro["IntituleExperiencePro"] . "</u></br>" . $experiencePro["LibelleOrganisation"] . " - " . $experiencePro["TypePoste"] . '</div>' .
                        '<div class="affichageProfil">' . $experiencePro["Region"] . "</br>" . $experiencePro["SecteursActivites"] . " - " . $experiencePro["DomainesCompetences"] . '</div>' .
                        '<a href="profil.php?idEleve=' . $experiencePro["IdEleve"] . '" class="btn btn-outline-dark mr-5" type="button">Voir le profil</a>
                            </div>
                        </div>
                    </div>
                </div>';
                }
            }
        }
        // Si le filtre correspond au nom ou au prenom
        else if ($search_filter == "NomPrenom") {

            $requeteNomPrenom = $BDD->prepare("SELECT IdEleve,Nom,Prenom,Promotion FROM InfosPerso WHERE(InfosPerso.Nom LIKE CONCAT('%',?,'%') OR InfosPerso.Prenom LIKE CONCAT('%',?,'%')) ");  // Probleme : Si on passe $search_filter avec un ? dans le execute, ça ne fonctionne pas
            $requeteNomPrenom->execute(array($search_val, $search_val));

            while ($profil = $requeteNomPrenom->fetch()) {
                echo '<div class="whitecontainer mt-3 mb-3"> 
                        <div class="ml-4 row text-secondary">
                            <div class="col-md-6 h3">
                                <div class="col-md-12">
                                    <div class="affichageProfil">' . $profil["Prenom"] . " " . $profil["Nom"] . " - Promotion " . $profil["Promotion"] . '</div>' .
                    '<a href="profil.php?idEleve=' . $profil["IdEleve"] . '" class="btn btn-outline-dark mr-5" type="button">Voir le profil</a>
                                </div>
                            </div>
                        </div>
                    </div>';
            }
        }


        // Si le filtre correspond à la promotion ou à la ville (table infos perso)
        else if ($search_filter == "Promotion" || $search_filter == "Ville") {
            $requeteProfil = $BDD->prepare("SELECT IdEleve, Nom, Prenom, Promotion, Ville FROM InfosPerso WHERE (InfosPerso.Promotion LIKE CONCAT('%',?,'%') OR InfosPerso.Ville LIKE CONCAT('%',?,'%')) ");
            $requeteProfil->execute(array($search_val, $search_val));

            while ($profil = $requeteProfil->fetch()) {
                echo '<div class="whitecontainer mt-3 mb-3"> 
                <div class="ml-4 row text-secondary">
                    <div class="col-md-6 h3">
                        <div class="col-md-12">
                            <div class="affichageProfil">' . $profil["Prenom"] . " " . $profil["Nom"] . " - Promotion " . $profil["Promotion"] . '</div>' .
                            '<div class="affichageProfil h5">' . $profil["Ville"] . '</div>' .
                            '<a href="profil.php?idEleve=' . $profil["IdEleve"] . '" class="btn btn-outline-dark mr-5" type="button">Voir le profil</a>
                        </div>
                    </div>
                </div>
            </div>';
            }
        }
    }
    exit();
}
