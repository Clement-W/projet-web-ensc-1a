<?php
require_once "../includes/functions.php";
session_start();
?>


<!doctype html>
<html lang="fr">

<?php
$pageTitle = "Accueil";
require_once "../includes/fragments/head.php";
?>

<body class="background">
    <?php require_once "../includes/fragments/header.php"; ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 d-flex">
                <div class="small-12 large-6 columns align-self-center">
                    <h1>Une plateforme rapide, accessible et réaliste pour tester vos compétences en hacking.</h1>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="whitecontainer">
                    <form method="POST" class="register-form" id="register-form">
                        <div class="form-group">
                            <label for="name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                            <input type="text" name="name" id="name" placeholder="Your Name" />
                        </div>
                        <div class="form-group">
                            <label for="email"><i class="zmdi zmdi-email"></i></label>
                            <input type="email" name="email" id="email" placeholder="Your Email" />
                        </div>
                        <div class="form-group">
                            <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                            <input type="password" name="pass" id="pass" placeholder="Password" />
                        </div>
                        <div class="form-group">
                            <label for="re-pass"><i class="zmdi zmdi-lock-outline"></i></label>
                            <input type="password" name="re_pass" id="re_pass" placeholder="Repeat your password" />
                        </div>
                        <button class="btn btn--pill btn--green" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>




    <?php require_once "../includes/scripts/script.php"; ?>

</body>

</html>