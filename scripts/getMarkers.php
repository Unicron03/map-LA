<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO("mysql:host=localhost;dbname=map-LA", "root", "");

    $stmt = $pdo->query("SELECT x, y, titre, description, typeMarker FROM marker");
    $marqueurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($marqueurs);  // Envoi des marqueurs sous forme JSON
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>