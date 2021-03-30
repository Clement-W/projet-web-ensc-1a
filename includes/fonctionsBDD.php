<?php
/* MODULE DE PROGRAMMATION WEB
 * Rôle du fichier : 
 * Contient la fonction d'accès à la base de données
 *
 *
 * Copyright 2021, MARQUETON Emma & WEINREICH Clément
 * https://ensc.bordeaux-inp.fr/fr
 *
 */

function getBDD()
{
    $server = "localhost";
    $username = "annuaireUser";
    $password = "explodingkittens"; // très bon jeu de société
    $db = "annuaireEleves";

    try {
        $BDD = new PDO(
            "mysql:host=$server;dbname=$db;charset=utf8",
            "$username",
            "$password",
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
    } catch (Exception $e) {
        die('Erreur fatale' . $e->getMessage());
    }

    return $BDD;
}






?>