<?php
require_once "../includes/functions.php";
session_start();
?>


<!doctype html>
<html lang="fr">

<?php
$titrePage = "Accueil";
require_once "../includes/fragments/head.php";

?>

<body class="background">
    <?php require_once "../includes/fragments/header.php"; ?>

    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-6 align-self-center">
                <div class="small-12 large-6 columns">
                    <h1>Une plateforme rapide, accessible et réaliste pour tester vos compétences en hacking.</h1>
                </div>
            </div>

            <div class="col-lg-6 align-item-center">
                <div class="whitecontainer d-flex justify-content-center">
                    <section class="signup">
                        <div class="signup-content">
                            <div class="signup-form">
                                <h2>Inscription</h2>
                                <form method="POST" class="register-form" id="register-form">
                                    <div id="carouselExampleIndicators" class="carousel slide divCarousel" data-interval="false">
                                        <div class="carousel-inner divCarousel">
                                            <div class="carousel-item active">

                                                <div class="form-group">
                                                    <label for="name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                                    <input type="text" name="name" id="name" placeholder="Name" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="email"><i class="zmdi zmdi-email"></i></label>
                                                    <input type="email" name="email" id="email" placeholder="Email" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                                                    <input type="password" name="pass" id="pass" placeholder="Password" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="re-pass"><i class="zmdi zmdi-lock-outline"></i></label>
                                                    <input type="password" name="re_pass" id="re_pass" placeholder="Repeat your password" />
                                                </div>
                                                <div class="form-group form-button">
                                                    <input type="button" name="signup" id="signup" class="form-submit" value="Continuer" />
                                                </div>
                                                <script type="text/javascript">
                                                    $("#signup").click(() => $(".carousel").carousel("next"));
                                                </script>

                                            </div>
                                            <div class="carousel-item">
                                            <div class="form-group">
                                                    <label for="name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                                    <input type="text" name="name" id="name" placeholder="Promo" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="email"><i class="zmdi zmdi-email"></i></label>
                                                    <input type="email" name="email" id="email" placeholder="Genre" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                                                    <input type="password" name="pass" id="pass" placeholder="Adresse" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="re-pass"><i class="zmdi zmdi-lock-outline"></i></label>
                                                    <input type="password" name="re_pass" id="re_pass" placeholder="Repeat your password" />
                                                </div>
                                                <div class="form-group form-button">
                                                    <input type="button" name="signup" id="signup" value="Précédent" />
                                                </div>
                                                <script type="text/javascript">
                                                    $("#signup").click(() => $(".carousel").carousel("prev"));
                                                </script>
                                            </div>
                                            <div class="carousel-item">
                                                <img class="d-block w-100" src="images/archi3.jpg" alt="Third slide">
                                            </div>
                                        </div>
                                      
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

</body>

</html>