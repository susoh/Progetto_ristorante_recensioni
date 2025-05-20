<?php
    include("connessione.php");
    $id_ristorante = $_POST["nome"];
    $sql = "SELECT r.voto, r.data, u.username FROM recensione r JOIN utente u ON r.id_utente = u.id_utente WHERE r.codiceristorante = '$id_ristorante';";
    $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <?php
    if($result->num_rows == 0) {
        echo "<h4>Nessuna recensione presente.</h4>";
    } else {
        echo "<table class='table'>
                <thead>
                    <tr>
                        <th>Voto</th>
                        <th>Data</th>
                        <th>Utente</th>
                    </tr>
                </thead>
                <tbody>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>".$row["voto"]."</td>
                    <td>".$row["data"]."</td>
                    <td>".$row["username"]."</td>
                  </tr>";
        }
        echo "</tbody>
              </table>";
    }

    echo "<a class='back-link' href='javascript:history.back()'>Torna indietro</a>";
?>
    <?php
    $sql = "SELECT latitudine, longitudine FROM ristorante WHERE id_ristorante = '$id_ristorante';";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    ?>
    <div id='map' style='width: 600px; height: 400px;'></div>
    <script src='https://unpkg.com/leaflet@1.7.1/dist/leaflet.js'></script>
    <script>
        const defaultLat = 41.8719;
            const defaultLng = 12.5674;
            const map = L.map('map').setView([defaultLat, defaultLng], 6);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
            let marker = null;
            map.on('click', function (e) {
                const lat = e.latlng.lat.toFixed(6);
                const lng = e.latlng.lng.toFixed(6);
                if (marker) {
                    map.removeLayer(marker);
                }
                marker = L.marker([lat, lng]).addTo(map)
                    .bindPopup("Posizione selezionata: " + lat + ", " + lng)
                    .openPopup();
                document.getElementById('latitudine').value = lat;
                document.getElementById('longitudine').value = lng;
            });
    </script>
</body>
</html>
