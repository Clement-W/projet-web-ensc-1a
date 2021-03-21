<?php
require_once("../../includes/functions.php");
$BDD = getBDD();

if (isset($_POST['search'])) {
    $search_val = $_POST['search_term'];
    if ($search_val != "") {

        $req = $BDD->prepare("select * from ExperiencePro where TypeOrganisation LIKE '%$search_val%'");
        $req->execute();
        while ($row = $req->fetch()) {
            echo "<li>" . $row["TypeOrganisation"] . " : " . $row["Description"] . "</li>" . '<br/>';
        }
    }   
    exit();
}
