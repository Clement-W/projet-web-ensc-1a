<?php
require_once("../inclues/functions.php");
session_start();
session_destroy();
redirect('accueil.php');
?>