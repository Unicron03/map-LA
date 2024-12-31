<?php

// ----------------------Inscription-----------------------------------------------------------------------------------
if (isset($_POST['register'])) {
    $pdo = Database::get();
    
    // ----------------------- Encaptulation des données entrez par l'utilisateur--------------------------------------
    $username = $_POST['username'];
    $email = $_POST['email'];
    $fullname = $_POST['fullname'];

    // ---------------------Cryptage du mot de passe-------------------------------------------------------------------
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    //----------------------Insertion ---------------------------------------------------------------------------------
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, fullname) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$username, $password, $email, $fullname])) {
        echo "<p id='success-message'>Inscription réussie ! Veuillez vous connecter.</p>";

        // --------------------Revenir au formulaire de Connection-------------------------------------------------------
        echo "<script>document.addEventListener('DOMContentLoaded', function() { toggleForm('login-form'); });</script>"; 
    } else {
        echo "<p>Erreur lors de l'inscription. Veuillez réessayer.</p>";
    }
    
}