<?php
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