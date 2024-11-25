<?php
function drawMarkers($pdo) {
    try {
        // Requête pour récupérer les marqueurs avec leurs images
        $stmt = $pdo->query("SELECT m.x, m.y, m.titre, m.description, m.image, m.source, t.image as imgCat, t.nom as nomCat
                            FROM marker m
                            JOIN typemarker t ON m.typeMarker = t.id");
        $marqueurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Génération du script JavaScript pour chaque marqueur
        echo "<script>";
        foreach ($marqueurs as $marqueur) {
            $x = $marqueur['x'];
            $y = $marqueur['y'];
            $titre = addslashes($marqueur['titre']);  // Échapper les guillemets simples
            $description = addslashes($marqueur['description']);
            $sourceLink = addslashes($marqueur['source']);
            $nomCat = addslashes($marqueur['nomCat']);

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

                var popupContent = `<div class='popupMarker'>
                    <h1 class='subtitle' style='margin: 0;'>$titre</h1>
                    " . (!empty($popupImage) ? "<img src='$popupImage' style='width: auto; max-width: 382px; height: auto; '>" : "") . "
                    <div id='info-popup-marker' " . (empty($description) & empty($sourceLink) ? "style='display: none;'" : "") . ">
                        " . (!empty($description) ? "<p>$description</p>" : "") . "
                        " . (!empty($sourceLink) ? "<p> Source: <a href=$sourceLink target='blank' style='text-decoration: none;'>$nomCat Guide</a></p>" : "") . "
                    </div>
                    <div id='btn-popup-marker'>
                        <button>
                            <img class='icon-template' src='./img/icon-globe.png' title='Go to Source' alt='icon-globe'/>
                        </button>
                        <button>
                            <img class='icon-template' src='./img/icon-favorite.png' title='Mark as Favorite' alt='icon-favorite'/>
                        </button>
                        <button>
                            <img class='icon-template' src='./img/icon-mark.png' title='Mark as Complete' alt='icon-mark'/>
                        </button>
                    </div>
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
