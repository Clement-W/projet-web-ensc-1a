<?php
/* MODULE DE PROGRAMMATION WEB
 * Rôle du fichier : 
 * Contient toutes les foncttions utilitaires utilisées dans notre code
 *
 *
 * Copyright 2021, MARQUETON Emma & WEINREICH Clément
 * https://ensc.bordeaux-inp.fr/fr
 *
 */

function redirect($url)
{
    header("Location: $url");
}


function escape($value)
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false); // paramètres les plus sécurisés pour htmlspecialchars
}


//formate le format date de mysql pour l'afficher dans les experiences pro
function formaterDateExperiencePro($date)
{
    if ($date == null) {
        return "";
    } else {
        $mois = substr($date, 5, 2);
        $annee = substr($date, 0, 4);

        $newDate = $mois . "/" . $annee;
        return $newDate;
    }
}

function getCSVDepuisTelechargement($chemin_fichier)
{

    if ($_FILES["templateUploaded"]["size"] <= 500000) { // Si la taille du fichier dépasse 500kb, 

        if (move_uploaded_file($_FILES["templateUploaded"]["tmp_name"], $chemin_fichier)) {

            return true;
        }
    }
    return false;
}




?>