<?php
/* MODULE DE PROGRAMMATION WEB
 * Rôle du fichier : 
 * Contient toutes les foncttions utilitaires utilisées dans notre code (n'utilise pas la BDD)
 *
 *
 * Copyright 2021, MARQUETON Emma & WEINREICH Clément
 * https://ensc.bordeaux-inp.fr/fr
 *
 */

// Permet de rediriger l'utilisateur sur une autre page
function redirect($url)
{
    header("Location: $url");
}

// Permet de remplacer certains caractères pour éviter les injections js ou sql
function escape($value)
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false); // paramètres les plus sécurisés pour htmlspecialchars
}


// Formate le format date de mysql pour l'afficher comme on le souhaite dans les experiences pro
function formaterDateExperiencePro($date)
{
    if ($date == null) { // Pour gérer le cas de la date de fin qui est facultative
        return "";
    } else {
        $mois = substr($date, 5, 2);
        $annee = substr($date, 0, 4);

        $nouvelleDate = $mois . "/" . $annee;
        return $nouvelleDate;
    }
}

// Permet de télécharger le CSV uploadé par l'utilisateur
function getCSVDepuisTelechargement($chemin_fichier)
{

    if ($_FILES["templateUploaded"]["size"] <= 500000) { // Si la taille du fichier dépasse 500kb, on ne le télécharge pas

        if (move_uploaded_file($_FILES["templateUploaded"]["tmp_name"], $chemin_fichier)) {

            return true;
        }
    }
    return false;
}
?>