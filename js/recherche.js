function rechercher() {
    var search_term = $("#search_term").val(); 
    var search_param = $("#search_param").val();
    $.ajax({
        type: 'post',
        url: '../includes/recherche_resultats.php',
        data: {
            search: "search",
            search_term: search_term,
            search_param: search_param
        },
        success: function (response) {
            document.getElementById("resultat_recherche").innerHTML = response;
        }
    });

    return false;
}


