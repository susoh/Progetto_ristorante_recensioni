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
    <title>Info ristorante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./styles2.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
      <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
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
    <div width="80%" style="text-align: center; margin-top: 20px; margin-left: 20%; margin-right: 20%;">
        <?php
    $sql = "SELECT latitudine, longitudine FROM ristorante WHERE id_ristorante = '$id_ristorante';";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    ?>
    <div id='map' style='width: 100%; height: 500px;'></div>
    <script src='https://unpkg.com/leaflet@1.7.1/dist/leaflet.js'></script>
    <script>
        <?php
            if ($row && isset($row['latitudine']) && isset($row['longitudine'])) {
                $lat = floatval($row['latitudine']);
                $lng = floatval($row['longitudine']);
                echo "const dbLat = $lat;\n";
                echo "const dbLng = $lng;\n";
                echo "const hasCoords = true;\n";
            } else {
                echo "const dbLat = 41.8719;\n";
                echo "const dbLng = 12.5674;\n";
                echo "const hasCoords = false;\n";
            }
        ?>
        const map = L.map('map').setView([dbLat, dbLng], hasCoords ? 100 : 6);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let marker = null;
        if (hasCoords) {
            marker = L.marker([dbLat, dbLng]).addTo(map)
                .bindPopup("Posizione ristorante: " + dbLat + ", " + dbLng)
                .openPopup();
        }
    </script>
    </div>
</body>
</html>
