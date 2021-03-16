<?php

// Pour se connecter à la base de données
// Retourne un objet PDO
function getDb() {
    // Déploiement en local
    $server = "localhost";
    $username = "quizz_user";
    $password = "admin";
    $db = "quizz";
    
    $BDD = new PDO("mysql:host=$server;dbname=$db;charset=utf8", "$username", "$password",
array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}


?>