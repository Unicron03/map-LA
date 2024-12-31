<?php
$pdo = Database::get();

//-------------------------Connexion------------------------------------------------------------------------------------
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    //---------------------Récupération des données de l'adresse email entrée par l'utilisateur--------------------------
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    //--------------------Vérification que le mot de passe entré par l'utilisateur est correct---------------------------
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        //----------------------Charger checkPoints cochés par l'utilisateur---------------------------------------------(To Check)
        //faire ici ou dans le html (sûrement plus dans le html)
    } else {
        // Générer un popup en cas d'échec
        echo "<script>alert('Échec de la connexion. Veuillez vérifier vos identifiants.');</script>";
    }
}