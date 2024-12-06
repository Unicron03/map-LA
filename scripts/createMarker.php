<?php
require_once 'scripts/drawMarkers.php';
?>

<script>
    function createMarker(event, x, y, form) {
        event.preventDefault();  // Empêche le rechargement de la page

        var titre = form.elements['title'].value;
        var description = form.elements['description'].value;

        // Envoi des données au serveur via AJAX
        fetch('scripts/addMarker.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ x: x, y: y, titre: titre, description: description, typeMarker: 16, userID: 0 })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                map.closePopup();
                location.reload();
            } else {
                alert("Erreur lors de l'ajout du marqueur.");
            }
        })
        .catch(error => console.error('Erreur:', error));
    }

    function deleteMarker(event, id) {
        event.preventDefault();  // Empêche le rechargement de la page

        // Envoi des données au serveur via AJAX
        fetch('scripts/deleteMarker.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                map.closePopup();
                location.reload();
            } else {
                // alert("Erreur lors de la suppression du marqueur.");
            }
        })
        .catch(error => console.error('Erreur:', error));
    }

    function updateMarker(event, id, form) {
        event.preventDefault();  // Empêche le rechargement de la page

        var titre = form.elements['title'].value;
        var description = form.elements['description'].value;

        // Envoi des données au serveur via AJAX
        fetch('scripts/updateMarker.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id, titre: titre, description: description })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                map.closePopup();
                location.reload();
            } else {
                // alert("Erreur lors de la suppression du marqueur.");
            }
        })
        .catch(error => console.error('Erreur:', error));
    }
</script>