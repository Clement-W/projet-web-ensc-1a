<?php
/* MODULE DE PROGRAMMATION WEB
* Rôle du fichier : 
* Affiche un message d'alerte en haut de la page pour faire un retour à l'utilisateur 
* concernant une action qu'il a réalisé. 
*
*
* Copyright 2021, MARQUETON Emma & WEINREICH Clément
* https://ensc.bordeaux-inp.fr/fr
*
*/

if (isset($_SESSION["alert"])) {
    // On utilise la variable de session afin de pouvoir transporter les messages d'alerte de pages en pages.
    $alert = $_SESSION["alert"];
    $bootstrapClassAlert =  $alert['bootstrapClassAlert'];
    $messageAlert = $alert['messageAlert'];
?>

    <div class="alert alert-<?= $bootstrapClassAlert ?>" id="alert">
        <button type="button" class="close" data-dismiss="alert" >x</button>
        <strong><?= $messageAlert ?></strong>
    </div>
    <script type="text/javascript">
        $("#alert").fadeTo(2000, 500).slideUp(500, function() {
            $("#alert").slideUp(500);
        });
    </script> 

    



<?php
} 


?>