<?php
if (isset($alert)) {
    $bootstrapClassAlert =  $alert['bootstrapClassAlert'];
    $messageAlert = $alert['messageAlert'];
?>

    <script type="text/javascript">
        $("#alert").fadeTo(2000, 500).slideUp(500, function() {
            $("#alert").slideUp(500);
        });
    </script>
    <div class="alert alert-<?= $bootstrapClassAlert ?>" id="alert">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong><?= $messageAlert ?></strong>
    </div>


<?php
} ?>