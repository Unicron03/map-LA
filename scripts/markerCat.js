function initPanelIcons() {
    Object.values(markersLib).forEach(marker => {
        const aElement = document.createElement('a');
        aElement.className = 'icon-panel';

        const imgElement = document.createElement('img');
        imgElement.src = marker.icon;
        imgElement.alt = marker.cat;

        const pElement = document.createElement('p');
        pElement.className = 'label';
        pElement.textContent = marker.name;

        aElement.appendChild(imgElement);
        aElement.appendChild(pElement);

        aElement.addEventListener("mouseover", () => {
            pElement.textContent = getNbMarkerCompleted(marker);
            // pElement.style.display = "block";
        })
        aElement.addEventListener("mouseout", () => {
            pElement.textContent = marker.name;
            // pElement.style.display = "table-caption";
        })
        aElement.onclick = () => displayMarkers(marker, aElement);
        document.getElementById('panelIcons').appendChild(aElement);
    });
}