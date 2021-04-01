/* MODULE DE PROGRAMMATION WEB
* Rôle du fichier : 
* Contient les 3 fonctions javascript qui sont utilisées dans notre code
*
*
* Copyright 2021, MARQUETON Emma & WEINREICH Clément
* https://ensc.bordeaux-inp.fr/fr
*
*/


// Cette fonction permet d'envoyer une requête post vers accueil.php. Elle est appelée dès qu'un 
// caractère est entré dans la barre de recherche. Elle affiche ensuite la sortie de la fonction
// php dans la div resultat_recherche de la page d'accueil
function rechercher() {
    var texteRecherche = $("#texteRecherche").val(); // .val pour récuperer la valeur de ces champs
    var filtreRecherche = $("#filtreRecherche").val();
    $.ajax({
        type: 'post',
        url: 'accueil.php',
        data: {
            rechercher: "rechercher",
            texteRecherche: texteRecherche,
            filtreRecherche: filtreRecherche
        },
        success: function (response) {
            document.getElementById("resultatRecherche").innerHTML = response; // On remplit la div resultatRecherche par ce qui a été echo dans la fonction php appelée dans accueil.php
        }
    });
}


// Envoie une requete post vers validationCompte.php pour appeller la méthode de validation de compte puis supprime l'affichage du compte lorsqu'il a été validé
// Cela permet de réaliser une action avec le onclick du bouton
function validerCompte(idEleve) {
    $.ajax({
        type: 'post',
        url: 'validationCompte.php',
        data: {
            validation: "validation",
            IdEleve: idEleve,
        },
        success: function (response) {
            var element = document.getElementById("compteNonValide" + idEleve);
            $(element).slideUp(500); // Pour faire un effet de disparition vers le haut
        }
    });
}


// Envoie une requete post vers validationCompte.php pour appeller la méthode de suprression de compte de function.php puis supprime l'affichage du compte lorsqu'il a été invalidé
// Cela permet de réaliser une action avec le onclick du bouton
function invaliderCompte(idEleve) {
    $.ajax({
        type: 'post',
        url: 'validationCompte.php',
        data: {
            invalidation: "invalidation",
            IdEleve: idEleve,
        },
        success: function (response) {
            var element = document.getElementById("compteNonValide" + idEleve);
            $(element).slideUp(500); // Pour faire un effet de disparition vers le haut
        }
    });
}