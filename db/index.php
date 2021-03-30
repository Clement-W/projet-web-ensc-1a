<?php
/* MODULE DE PROGRAMMATION WEB
* Rôle du fichier :
* Permet de rediriger automatiquement vers accueil si un utilisateur essaie de remonter dans l'arborescence depuis l'url.
* C'est est d'autant plus pertinent dans le répertoire contenant les scripts sql car cela donnerait la structure de la BDD
*
* Copyright 2021, MARQUETON Emma & WEINREICH Clément
* https://ensc.bordeaux-inp.fr/fr
*
*/

require_once("../includes/fonctionsUtilitaires.php");
redirect("../pages/accueil.php");
?>