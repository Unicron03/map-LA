
function createMarker(event, x, y, form) {
    event.preventDefault();  // Empêche le rechargement de la page

    // Création de l'icône personnalisée
    var persoIcon = L.icon({
        iconUrl: 'img/markers/perso.png',
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32]
    }); 

    var titre = form.elements['title'].value
    var description = form.elements['description'].value

    // Construction dynamique du contenu du popup
    var popupContent = `<div class='popupMarker' style='min-width: 280px !important;'>
        <h1 class='subtitle' style='margin: 0;'>${titre}</h1>
        <div id='info-popup-marker' ${!description ? "style='display: none;'" : ""}>
            ${description ? `<p>${description}</p>` : ""}
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
            <button onclick="openEditForm('${titre}', '${description}', ${x}, ${y}, this)">
                <img class='icon-template' src='./img/icon-modif.png' title='Modify the Marker' alt='icon-modif'/>
            </button>
        </div>
    </div>`;

    addMarkersToMap(x, y, titre, persoIcon, popupContent);
    map.closePopup();  // Ferme le popup initial après validation
}