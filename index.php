<?php
session_start();

// ---------------------Connexion à la base de données-----------------------------------------------------------------
$pdo = new PDO("mysql:host=localhost;dbname=map-LA", "root", "");


// ---------------------Fonction pour vérifier qu'on est connecter ----------------------------------------------------(To check)
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// ----------------------Inscription-----------------------------------------------------------------------------------
if (isset($_POST['register'])) {
    // ----------------------- Encaptulation des données entrez par l'utilisateur--------------------------------------
    $username = $_POST['username'];
    $email = $_POST['email'];
    $fullname = $_POST['fullname'];

    // ---------------------Cryptage du mot de passe-------------------------------------------------------------------
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, fullname) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$username, $password, $email, $fullname])) {
        echo "<p id='success-message'>Inscription réussie ! Veuillez vous connecter.</p>";

        // --------------------Revenir au formulaire de Connection-------------------------------------------------------
        echo "<script>document.addEventListener('DOMContentLoaded', function() { toggleForm('login-form'); });</script>"; 
    } else {
        echo "<p>Erreur lors de l'inscription. Veuillez réessayer.</p>";
    }
    
}

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

// ------------------------Déconnexion-----------------------------------------------------------------------------------
if (isset($_GET['logout'])) {
    session_destroy();

    //-------------------Voir que faire quand l'utilisateur se déconnect-------------------------------------------------(To Check)
    header("Location: map.php");
    exit;
}

include 'scripts/drawMarkers.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Carte Leaflet avec Tuiles d'Images</title>

    <link rel="stylesheet" href="css/leaflet.css" />
    <link rel="stylesheet" href="css/index.css?v=2.2" >

    <style>
        #map {
            height: 800px;
            width: 100%;
            background-color: #434343;
            height: 1000px !important;
        }
    </style>
</head>
<body>
    
    <div class="panel">       
        <!-- ----------- Vérifie si l'utilisateur est connecter --------------------------------------------------------------- -->
        <?php if (isLoggedIn()): ?>
            <!-- Ce qui change quand on est connecter -------------------------------------------------------------------------(To Check) -->
            <h2>Bienvenue, <?= $_SESSION['username']; ?> !</h2>
            <a href="?logout=true">Déconnexion</a>
        <?php else: ?>
            <div class="panel-flag">
                <div class="panel-flag-bandeau">
                    <!-- ---------Bonton Connexion---------------------------------------------------------------------------------- -->
                    <button onclick="toggleForm('login-form')">
                        <img src="./img/icon-silhuet.png" alt="icon-silhuet"/>
                    </button>
                    
                    <!-- ---------Bouton Reduire Taille------------------------------------------------------------------------------ -->
                    <button onclick="adjustPanelHeight()">
                        <img src="img/icon-reduce.png" alt="icon-reduce"/>
                    </button>
                </div>

                <h2 class="subtitle">Zelda: Link's Awakening Interactive Map</h2>
            </div>

            <!-- ------------------Normalement se sera les différent type de check point --------------------------------------(To Check) -->
            <div class="panel-controls">
                <br><a>Cacher les points marqués</a>
                <br><a onclick="disableAllMarkers()">Déselectionner toutes les catégories</a>
                <br><a onclick="enableAllMarkers()">Sélectionner toutes les catégories</a>
            </div>

            <div class="panel-icons" id="panelIcons">
            </div>

            <!-- -----------------Formulaire connexion------------------------------------------------------------------------ -->
            <div id="login-form" class="form-container">
                <h2>Hey, listen! Welcome back!</h2>
                <form id="formconnex" method="POST">
                    <input id="emailco" type="email" name="email" placeholder="Email" required>
                    <input id="passco" type="password" name="password" placeholder="Mot de passe" required>
                    <button type="submit" id="btnconnect" name="login">Login</button>
                </form>
                <button id="register-button" onclick="toggleForm('register-form')">Inscription</button>
            </div>

            <!-- -----------------Formulaire inscription------------------------------------------------------------------------ -->
            <div id="register-form" class="form-container">
                <h2>It's dangerous to go alone! We're glad you're here with us.</h2>
                <form method="POST">
                    <input id="usere" type="text" name="username" placeholder="Nom d'utilisateur" required>
                    <input id="passre" type="password" name="password" placeholder="Mot de passe" required>
                    <input id="fullre" type="text" name="fullname" placeholder="Nom complet" required>
                    <input id="emailre" type="email" name="email" placeholder="Email" required>
                    <button id="registerbtn" type="submit" name="register">S'inscrire</button>
                </form>
            </div>   
        <?php endif; ?> 
    </div>

    <div id="map"></div>
    
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="scripts/createMarker.js"></script>
    <script>
        var Perso = L.icon({iconUrl: 'img/markers/perso.png', iconSize: [32, 32]});

        var map = L.map('map', {
            zoomSnap: 1, // Zoom par paliers entiers
            zoomDelta: 1 // Contrôle la vitesse du zoom (facultatif)
        });

        // Ajout de la couche de tuiles
        L.tileLayer("http://localhost:8000/imgUp/{z}/{x}/{y}.png", {
            attribution: "",
            minZoom: 0,
            maxZoom: 4,
            noWrap: true // Empêche la répétition des tuiles horizontalement
        }).addTo(map);

        // Centrer la vue de la carte
        map.setView([0, 0], 2);

        // Bornes de la map
        var southWest = L.latLng(-70.5, -180); // Coin en bas à gauche
        var northEast = L.latLng(70.5, 180);   // Coin en haut à droite
        var bounds = L.latLngBounds(southWest, northEast);

        map.on('click', function(e) {
            if (bounds.contains(e.latlng)) {
                var coords = e.latlng;
                var formContent = `
                    <form onsubmit="createMarker(event, ${coords.lng}, ${-coords.lat}, this);">
                        <label for="markerTitle">Titre :</label><br>
                        <input type="text" id="markerTitle" name="title" required minlength="4" maxlength="20" size="15"><br><br>
                        <label for="markerDescription">Description :</label><br>
                        <textarea id="markerDescription" name="description" rows="3" cols="20"></textarea><br><br>
                        <button type="submit">Créer le marqueur</button>
                    </form>
                `;

                L.popup()
                    .setLatLng(e.latlng)
                    .setContent(formContent)
                    .openOn(map);
            }
        });

        function addMarkersToMap(x, y, titre, iconUrl, popupContent) {
            // y+1 sur la SQL par rapport au local
            L.marker([-y, x], { icon: iconUrl, title: titre })
                .bindPopup(popupContent)
                .addTo(map);
        }
    </script>

    <script>
        function adjustPanelHeight() {
            if (document.querySelector('.panel-icons').style.display == 'none') {
                document.querySelector('.panel-icons').style.display = 'inline-grid';
            } else {
                document.querySelector('.panel-icons').style.display = 'none';
            }
        }
    </script>

    <?php drawMarkers($pdo); ?>
</body>
</html>

<!-- NE PAS OUBLIER DE LANCER SERVEUR PYTHON python -m http.server -->