<?php
require_once("../../includes/functions.php");
$BDD = getBDD();

if (isset($_POST['search'])) {
    $search_val = $_POST['search_term'];
    $search_filter = $_POST['search_param'];
    if ($search_val != "") {

        $req = $BDD->prepare("select * from ExperiencePro where $search_filter LIKE '%$search_val%'");
        $req->execute();
        while ($row = $req->fetch()) {
           
            echo "<li>" . $row["TypeOrganisation"] . " ; " . $row["Description"] ." ; " .$row["SecteursActivites"] ." ; ".$row["DomainesCompetences"] ."</li>" . '<br/>';
        }
    }   
    exit();
}
