<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark transparent" id="nav">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="btn btn-default" href="#">
                <i class="fas fa-coffee"></i>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="navbartarget" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand  text-light" href="#">Annuaire</a>
        </div>

        <div class="collapse navbar-collapse" id="navbartarget"></div>
        <!--Met les éléments de la navbar à l'opposé l'un de l'autre-->

        <?php $testPasConnecte = false; ?>
        <?php if ($testPasConnecte) { ?>
            <form class="form-inline my-2 my-lg-0">
                <button class="btn btn-outline-secondary my-2 my-sm-0 text-light " type="submit">J'ai déjà un compte</button>
            </form>
        <?php } ?>

        <!-- if (isAdminConnected()) { -->
        <?php $testGestionnaireConnecte = true; ?>
        <?php if ($testGestionnaireConnecte) { ?>
            <form class="form-inline my-2 my-lg-0">
                <button class="btn btn-outline-secondary my-2 my-sm-0 text-light " type="submit">Bonjour Utilisateur</button>
            </form>
        <?php } ?>
    </div>
</nav>