<?php

//-------------------------Connexion------------------------------------------------------------------------------------
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    //---------------------Récupèration des données de l'adresse email entrez par l'utilisateur--------------------------
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    //--------------------Vérification que le mot de passe entrez par l'utilisateur est le bon---------------------------
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        //----------------------Charger checkPoints cochés par l'utilisateur---------------------------------------------(To Check)
        //faire ici ou dans le html (surement plus dans le html)
    } else {
        echo "<p>Échec de la connexion. Veuillez vérifier vos identifiants.</p>";
    }
}


