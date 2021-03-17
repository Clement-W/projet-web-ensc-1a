<?php
if (isset($alert)) {
    $bootstrapClassAlert =  $alert['classAlert'];
    $messageAlert = $alert['messageAlert'];
?>
    <div class="alert alert-<?= $bootstrapClassAlert ?>">
        <strong><?= $messageAlert ?></strong>
    </div>
<?php
} ?>