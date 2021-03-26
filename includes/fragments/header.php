<nav class="navbar sticky-top navbar-expand-lg navbar-dark transparent" id="nav">
    <div class="container-fluid">
        <div class="navbar-header">

            <a class="btn btn-default" href="accueil.php">
                <i class="fa fa-address-book-o fa-2x" style="color:white" aria-hidden="true"></i>
            </a>
            <a href="accueil.php" class="navbar-brand text-light" href="#">Annuaire</a>

        </div>

        <!--Met les éléments de la navbar à l'opposé l'un de l'autre-->

        <?php if (estConnecte() && !estGestionnaire()) { ?>
            <div class="collapse navbar-collapse" id="navbartarget"></div>

            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle text-light" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Bonjour <?= $_SESSION["nomUtilisateur"]; ?>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="profil.php?idEleve=<?= getIdEleveParNomUtilisateur($_SESSION["nomUtilisateur"]); ?>">Profil</a> <!-- On récupère notre propre idEleve pour accéder à notre profil-->
                    <a class="dropdown-item" href="deconnexion.php">Se déconnecter</a>
                </div>
            </div>
        <?php } else if (estConnecte() && estGestionnaire()) { ?>

            <a href="creerCompte.php" class="navbar-brand text-light" href="#">Créer un compte</a>

            <a href="validerCompte.php" class="navbar-brand text-light" href="#">Valider un compte</a>

            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle text-light" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Bonjour <?= $_SESSION["nomUtilisateur"]; ?>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="deconnexion.php">Se déconnecter</a>
                </div>
            </div>


        <?php } else { ?>
            <a href="connexion.php" class="btn btn-outline-light my-2 my-sm-0 text-light" type="button"> J'ai déjà un compte</a>
        <?php } ?>

    </div>
</nav>