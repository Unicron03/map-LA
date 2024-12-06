<?php
function loadCatMarkers() {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=map-LA", "root", "");

        // Requête pour récupérer les catMarkers avec leurs images
        $stmt = $pdo->query("SELECT m.id, m.subId, m.nom, m.image
                            FROM typemarker m");
        $catMarkers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Génération du script JavaScript pour chaque catMarker
        echo "<script>";
        foreach ($catMarkers as $catMarker) {
            $id = $catMarker['id'];
            $subID = $catMarker['subId'];
            $nom = addslashes($catMarker['nom']);

            // Gérer l'image de la catégorie
            $iconBase64 = 'data:image/png;base64,' . base64_encode($catMarker['image']);

            // JavaScript pour ajouter le catMarker à la carte
            echo "
                var popupContent = `<div class='popupMarker' " . ($typeId == 16 ? "style='min-width: 280px !important;'" : "") . ">
                    <h1 class='subtitle' style='margin: 0;'>$titre</h1>
                    " . (!empty($popupImage) ? "<img src='$popupImage' style='width: auto; max-width: 382px; height: auto;'>" : "") . "
                    <div id='info-popup-marker' " . (empty($description) && empty($sourceLink) ? "style='display: none;'" : "") . ">
                        " . (!empty($description) ? "<p>$description</p>" : "") . "
                        " . (!empty($sourceLink) ? "<p>Source: <a href='$sourceLink' target='blank'>$nomCat Guide</a></p>" : "") . "
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
                        " . ($typeId == 16 ? "<button onclick=\"openEditForm('$titre', '$description', $x, $y, $id, this)\">
                            <img class='icon-template' src='./img/icon-modif.png' title='Modify the Marker' alt='icon-modif'/>
                        </button>" : "") . "
                    </div>
                </div>`;

                addMarkersToMap($x, $y, '$titre', customIconCat, popupContent);
            ";
        }
        echo "</script>";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
