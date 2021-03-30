<?php
/* MODULE DE PROGRAMMATION WEB
* Rôle du fichier :
* Permet à un utilisateur connecté de se déconnecter. Renvoie vers la page d'accueil.
*
*
* Copyright 2021, MARQUETON Emma & WEINREICH Clément
* https://ensc.bordeaux-inp.fr/fr
*
*/

require_once("../includes/fonctionsUtilitaires.php");
session_start();
session_destroy();
redirect('accueil.php');
?>