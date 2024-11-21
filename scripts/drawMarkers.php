<?php
function drawMarkers($pdo) {
    try {
        // Requête pour récupérer les marqueurs avec leurs images
        $stmt = $pdo->query("SELECT m.x, m.y, m.titre, m.image, t.image as imgCat
                             FROM marker m
                             JOIN typemarker t ON m.typeMarker = t.id");
        $marqueurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Génération du script JavaScript pour chaque marqueur
        echo "<script>";
        foreach ($marqueurs as $marqueur) {
            $x = $marqueur['x'];
            $y = $marqueur['y'];
            $titre = addslashes($marqueur['titre']);  // Échapper les guillemets simples

            // Gérer l'image du popup (si elle existe)
            $popupImage = !empty($marqueur['image']) ? 'data:image/png;base64,' . base64_encode($marqueur['image']) : '';

            // Convertir l'image de l'icône en base64
            $iconBase64 = 'data:image/png;base64,' . base64_encode($marqueur['imgCat']);

            // JavaScript pour ajouter le marqueur à la carte
            echo "
                var customIconCat = L.icon({
                    iconUrl: '$iconBase64',
                    iconSize: [32, 32],
                    iconAnchor: [16, 32],
                    popupAnchor: [0, -32]
                });

                var popupContent = `<div style='text-align: center; max-width: 382px;'>
                    " . (!empty($popupImage) ? "<img src='$popupImage' style='width: auto; max-width: 382px; height: auto; margin-bottom: 5px;'>" : "") . "
                    <h1 class='subtitle' style='margin: 0;'>$titre</h1>
                </div>`;

                addMarkersToMap($x, $y, customIconCat, popupContent);
            ";
        }
        echo "</script>";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
