<?php
header('Content-Type: application/json');  // Retour JSON

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=localhost;dbname=map-LA", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Récupération des données JSON
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id']) || empty($data['id'])) {
        echo json_encode(['success' => false, 'error' => 'ID manquant']);
        exit();
    }

    $id = $data['id'];

    // Préparer et exécuter la requête
    $stmt = $pdo->prepare("UPDATE marker SET complete = NOT complete WHERE id = :id");
    if ($stmt->execute([':id' => $id])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Erreur lors du marquage en favori']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}