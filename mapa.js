let map;
let markers = [];

function initMap() {
    map = L.map('map').setView([-36.2048, -60.0369], 7); // Centro aproximado de la provincia de Buenos Aires

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Cargar los problemas ambientales
    fetch('obtener_problemas.php')
        .then(response => response.json())
        .then(problemas => {
            problemas.forEach(problema => {
                addMarker(problema);
            });
        });
}

function addMarker(problema) {
    const marker = L.marker([parseFloat(problema.latitud), parseFloat(problema.longitud)], {
        title: problema.descripcion,
        icon: L.icon({
            iconUrl: `icons/${problema.icono}.png`,
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34]
        })
    }).addTo(map);

    marker.bindPopup(`
        <h3>${problema.descripcion}</h3>
        <p>Reportado por: ${problema.nombre_usuario}</p>
        <p>Escuela: ${problema.escuela}</p>
    `);

    markers.push(marker);
}

document.addEventListener('DOMContentLoaded', initMap);