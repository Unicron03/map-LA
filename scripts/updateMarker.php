<?php
header('Content-Type: application/json');  // Retour JSON

try {
    $pdo = new PDO("mysql:host=localhost;dbname=map-LA", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $data = json_decode(file_get_contents('php://input'), true);
    
    $id = $data['id'];
    $titre = $data['titre'];
    $description = $data['description'];

    $stmt = $pdo->prepare("UPDATE marker SET titre = :titre, description = :description WHERE id = :id");
    if ($stmt->execute([':id' => $id, ':titre' => $titre, ':description' => $description])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Erreur lors de la mise à jour']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
