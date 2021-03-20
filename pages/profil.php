<?php
//fonctionne avec un parametre get, par exemple: pages/profil.php?idEleve=4
//affiche le profil en conséquent. Si le compte n'est pas validé alors seul un gestionnaire peut le voir
//Sinon affiche une 404
//On affiche pas les infos cachées par l'utilisateur en question SAUF si c'est le profil de l'utilisatur connecté actuellement ou si c'est le gestionnaire qui regarde
?>