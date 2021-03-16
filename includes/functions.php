<?php

// Vérifie si l'utilisateur est connecté
// Retourne TRUE or FALSE
function isUserConnected() {
    return isset($_SESSION['pseudo']);
}

// Vérifie si l'utilisateur est un gestionnaire
// Retourne TRUE or FALSE
function estGestionnaire() {
    return isset($_SESSION['estGestionnaire']);
}

// Redirige sur une page web
// Prend un chemin d'url en paramètre
function redirect($url) {
    header("Location: $url");
}

// Enlève les caractères spéciaux
// Prend une chaîne de caractères en paramètre
// Retourne une chaîne de caractères simple
function escape($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
}

// Permet de se connecter à un compte sur le site web
// Retourne une alerte pour le feedback positif ou négatif
function seConnecter(){

}

// Permet de s'inscrire en BD
function inscription(){

}