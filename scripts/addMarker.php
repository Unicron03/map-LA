<?php
header('Content-Type: application/json');  // Retour JSON

try {
    $pdo = new PDO("mysql:host=localhost;dbname=map-LA", "root", "");

    // Récupération des données JSON
    $data = json_decode(file_get_contents('php://input'), true);
    
    $x = $data['x'];
    $y = $data['y'];
    $titre = $data['titre'];
    $description = $data['description'];
    $typeMarker = $data['typeMarker'];
    $userID = $data['userID'];

    // Préparer et exécuter la requête
    $stmt = $pdo->prepare("INSERT INTO marker (x, y, titre, description, typeMarker, userID) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$x, $y, $titre, $description, $typeMarker, $userID])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'insertion']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
