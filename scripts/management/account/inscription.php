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
        echo "<script>alert('Successful registration! Please log in.');</script>";

        // --------------------Revenir au formulaire de Connection-------------------------------------------------------
        echo "<script>document.addEventListener('DOMContentLoaded', function() { toggleForm('login-form'); });</script>"; 
    } else {
        echo "<script>alert('Registration error. Please try again.');</script>";
    }
}