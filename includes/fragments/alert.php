<?php
if (isset($alert)) {
    $bootstrapClassAlert =  $alert['bootstrapClassAlert'];
    $messageAlert = $alert['messageAlert'];
?>
    <div class="alert alert-<?= $bootstrapClassAlert ?>">
        <strong><?= $messageAlert ?></strong>
    </div>
<?php
} ?>