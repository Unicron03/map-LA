document.addEventListener('DOMContentLoaded', function() {
    let scale = 1;
    const scaleStep = 0.1;
    const maxScale = 2.5;
    const minScale = 0.5;
    let isDragging = false;
    let startX, startY, initialOffsetX, initialOffsetY;

    const mapContainer = document.getElementById('mapContainer');
    const mapParts = document.querySelectorAll('.map-part');

    // Fonction de zoom
    function zoom(event) {
        event.preventDefault();

        const mouseX = event.clientX;
        const mouseY = event.clientY;

        const rect = mapContainer.getBoundingClientRect();
        const offsetX = mouseX - rect.left;
        const offsetY = mouseY - rect.top;

        const prevScale = scale;

        if (event.deltaY < 0) {
            // Zoom avant
            scale = Math.min(scale + scaleStep, maxScale);
        } else {
            // Zoom arrière
            scale = Math.max(scale - scaleStep, minScale);
        }

        const deltaX = offsetX - offsetX * (scale / prevScale);
        const deltaY = offsetY - offsetY * (scale / prevScale);

        mapParts.forEach(part => {
            const currentTransform = getComputedStyle(part).transform;
            const matrix = new DOMMatrix(currentTransform);
            matrix.translateSelf(deltaX, deltaY);
            part.style.transform = `matrix(${matrix.a}, ${matrix.b}, ${matrix.c}, ${matrix.d}, ${matrix.e}, ${matrix.f}) scale(${scale})`;
        });
    }

    // Fonction pour le démarrage du glissement
    function startDrag(event) {
        isDragging = true;
        startX = event.clientX;
        startY = event.clientY;

        // Récupère la position actuelle de la carte
        const rect = mapContainer.getBoundingClientRect();
        initialOffsetX = rect.left;
        initialOffsetY = rect.top;

        // Ajoute un style de transition pour le mouvement
        mapContainer.style.transition = "none";
    }

    // Fonction pour le déplacement lors du glissement
    function drag(event) {
        if (isDragging) {
            const currentX = event.clientX;
            const currentY = event.clientY;

            const deltaX = currentX - startX;
            const deltaY = currentY - startY;

            mapContainer.style.transform = `translate(${initialOffsetX + deltaX}px, ${initialOffsetY + deltaY}px)`;
        }
    }

    // Fonction pour terminer le glissement
    function stopDrag() {
        isDragging = false;

        // Réinitialise le style de transition
        mapContainer.style.transition = "";
    }

    // Événements pour le glissement
    mapContainer.addEventListener('mousedown', startDrag);
    document.addEventListener('mousemove', drag);
    document.addEventListener('mouseup', stopDrag);

    // Événement pour le zoom
    document.body.addEventListener('wheel', zoom);
});