<div class="modal fade" id="modifierMotDePasse">
    <div class="modal-dialog">
        <div class="modal-content">

            <script type="text/javascript">
            console.log("test");
            </script>
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Modifier le mot de passe</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                Vous n'avez pas encore ajouté d'expérience professionnelle à votre profil.
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <a href="modifierProfil.php?idEleve=<?= getIdEleveParNomUtilisateur($_SESSION["nomUtilisateur"]); ?>" class="btn btn-success" type="button">Mettre à jour</a>
            </div>
        </div>
    </div>
</div>


