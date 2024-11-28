<?php
// create_marker.php

// Récupère les données envoyées par JavaScript
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $x = $_POST['x'];
    $y = $_POST['y'];
    $titre = $_POST['titre'];
    $description = $_POST['description'];

    // Connexion à la base de données
    $pdo = new PDO("mysql:host=localhost;dbname=map-LA", "root", "");

    // Insère le nouveau marqueur dans la base de données
    $stmt = $pdo->prepare("INSERT INTO marker (x, y, titre, description) VALUES (?, ?, ?, ?)");
    $stmt->execute([$x, $y, $titre, $description]);

    echo json_encode(["status" => "success", "message" => "Marqueur ajouté avec succès!"]);
}
?>
