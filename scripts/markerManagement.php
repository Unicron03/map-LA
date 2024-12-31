<?php
require_once 'scripts/drawMarkers.php';
?>

<script>
    function createMarker(event, x, y, form) {
        event.preventDefault();  // Empêche le rechargement de la page

        var titre = form.elements['title'].value;
        var description = form.elements['description'].value;

        console.log(x, y, titre, description, 16, 0);
        // Envoi des données au serveur via AJAX
        fetch('scripts/management/bdd/addMarker.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ x: x, y: y, titre: titre, description: description, typeMarker: 16 })
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
        fetch('scripts/management/bdd/deleteMarker.php', {
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
        fetch('scripts/management/bdd/updateMarker.php', {
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

    function markAsFavorite(event, id) {
        event.preventDefault(); // Empêche le rechargement de la page

        fetch('scripts/management/bdd/markAsFavorite.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ id: id }) // Envoi des données en format x-www-form-urlencoded
        })
        .then(() => {
            map.closePopup();
            location.reload();
        })
        .catch(error => console.error('Erreur:', error));
    }

    function markAsComplete(event, id) {
        event.preventDefault();  // Empêche le rechargement de la page

        // Envoi des données au serveur via AJAX
        fetch('scripts/management/bdd/markAsComplete.php', {
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
                alert("Erreur lors de la modification du marqueur.");
            }
        })
        .catch(error => console.error('Erreur:', error));
    }
</script>