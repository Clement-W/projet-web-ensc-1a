<html>

<head>
    <link type="text/css" rel="stylesheet" href="search_style.css" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <title>Test barre recherche </title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script type="text/javascript">
        function do_search() {
            var search_term = $("#search_term").val(); // val = get value element of the input form
            $.ajax({
                type: 'post',
                url: 'get_results.php',
                data: {
                    search: "search",
                    search_term: search_term
                },
                success: function(response) {
                    document.getElementById("result_div").innerHTML = response;
                }
            });

            return false;
        }
    </script>
</head>

<body>


    <div id="wrapper">
        <h1> Test barre de recherche</h1>

        <div id="search_box">
            <form method="post" action="get_results.php" onsubmit="return do_search();">
                <input type="text" id="search_term" name="search_term" placeholder="Enter Search" onkeyup="do_search();"> <!-- enlever le onkeyup pour pas que ca recherche tout seul -->
                <input type="submit" name="search" value="SEARCH">
            </form>
        </div>

        <div id="result_div"></div>

    </div>
</body>

</html>