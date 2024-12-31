<?php

// ------------------------Déconnexion-----------------------------------------------------------------------------------
if (isset($_GET['logout'])) {
    session_destroy();

    //-------------------Voir que faire quand l'utilisateur se déconnect-------------------------------------------------(To Check)
    header("Location: index.php");
    exit;
}