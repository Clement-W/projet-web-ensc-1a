/* MODULE DE PROGRAMMATION WEB
* Rôle du fichier : 
* Contient les 3 fonctions javascript qui sont utilisées dans notre code
*
*
* Copyright 2021, MARQUETON Emma & WEINREICH Clément
* https://ensc.bordeaux-inp.fr/fr
*
*/



function rechercher() {
    var search_term = $("#search_term").val();
    var search_param = $("#search_param").val();
    $.ajax({
        type: 'post',
        url: 'accueil.php',
        data: {
            search: "search",
            search_term: search_term,
            search_param: search_param
        },
        success: function (response) {
            document.getElementById("resultat_recherche").innerHTML = response;
        }
    });

    return false; // permet de ne pas faire recharger la page lorsqu'on appuie sur rechercher
}


// envoie une requete post vers validationCompte.php pour appeller la méthode de validation de compte de function.php puis supprime l'affichage du compte lorsqu'il a été validé
// cela permet de réalisser une action avec le onclick du bouton
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
            $(element).slideUp(500);
        }
    });
}

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
            $(element).slideUp(500);
        }
    });
}

