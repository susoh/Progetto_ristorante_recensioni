<?php
include("connessione.php");
session_start();

if (!$_SESSION["login"]) {
    header("Location:paginalogin.php");
    exit;
}

$username = $_SESSION["username"];

$sql = "SELECT admin FROM utente WHERE username = '$username';";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if (!$row["admin"]) {
    header("Location:paginalogin.php");
    exit;
}

$sql = "SELECT r.*, 
            (SELECT COUNT(*) FROM recensione rec 
            WHERE rec.codiceristorante = r.id_ristorante) 
        AS numero_recensioni FROM ristorante r;";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Pannello Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./styles2.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
      <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
</head>
<body>
    <div class="input rounded benvenuto" style="border: 2px solid black; padding: 3%">
        <h2>Benvenuto Admin: <b style="text-decoration: underline;"><?php echo $username; ?></b></h2><br>
        
        <?php
        if ($result->num_rows > 0) {
            echo '<table class="table table-bordered tabella">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Indirizzo</th>
                            <th>Città</th>
                            <th>Numero Recensioni</th>
                        </tr>
                    </thead>
                    <tbody>';
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["nome"] . "</td>
                        <td>" . $row["indirizzo"] . "</td>
                        <td>" . $row["citta"] . "</td>
                        <td>" . $row["numero_recensioni"] . "</td>
                      </tr>";
            }
            echo '</tbody></table>';
        } else {
            echo "<h4>Nessun ristorante presente.</h4><br>";
        }
        ?>

        <button type="button" class="btn btn-info mt-4" data-bs-toggle="modal" data-bs-target="#ModalRistorante">
            Inserisci un nuovo ristorante
        </button>

        <div class="modal fade" id="ModalRistorante" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Inserisci un nuovo ristorante</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form method="post" action="inserisciristorante.php" id="formRistorante">
                            <div class="form-group mb-2">
                                <label for="nome"><b>Nome ristorante:</b></label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                            <div class="form-group mb-2">
                                <label for="indirizzo"><b>Indirizzo ristorante:</b></label>
                                <input type="text" class="form-control" id="indirizzo" name="indirizzo" required>
                            </div>
                            <div class="form-group mb-2">
                                <label for="id"><b>Id ristorante:</b></label>
                                <input type="text" class="form-control" id="id" name="id" required>
                            </div>
                            <div class="form-group mb-2">
                                <label for="citta"><b>Città:</b></label>
                                <input type="text" class="form-control" id="citta" name="citta" required>
                            </div>
                            <div class="form-group mb-2">
                                <label><b>Coordinate:</b></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="coord_mode" id="coordManual" value="manual" checked>
                                    <label class="form-check-label" for="coordManual">
                                        Inserisci manualmente
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="coord_mode" id="coordMap" value="map">
                                    <label class="form-check-label" for="coordMap">
                                        Seleziona su mappa
                                    </label>
                                </div>
                                <div id="coordInputs">
                                    <div class="form-group mb-2">
                                        <label for="latitudine"><b>Latitudine:</b></label>
                                        <input type="text" class="form-control" id="latitudine" name="latitudine" required>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="longitudine"><b>Longitudine:</b></label>
                                        <input type="text" class="form-control" id="longitudine" name="longitudine" required>
                                    </div>
                                </div>
                                <div id="mapContainer" style="height: 300px; display: none; margin-bottom: 10px;">
                                    <div id="map" style="height: 100%;"></div>
                                </div>
                            </div>
                            <input type="submit" class="btn btn-success" value="Inserisci">
                        </form>
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const coordManual = document.getElementById('coordManual');
                            const coordMap = document.getElementById('coordMap');
                            const coordInputs = document.getElementById('coordInputs');
                            const mapContainer = document.getElementById('mapContainer');
                            let map, marker;

                            function showManual() {
                                coordInputs.style.display = '';
                                mapContainer.style.display = 'none';
                            }
                            function showMap() {
                                coordInputs.style.display = 'none';
                                mapContainer.style.display = '';
                                if (!map) {
                                    map = L.map('map').setView([41.9028, 12.4964], 6); // Center Italy
                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        maxZoom: 19,
                                    }).addTo(map);
                                    map.on('click', function(e) {
                                        if (marker) {
                                            marker.setLatLng(e.latlng);
                                        } else {
                                            marker = L.marker(e.latlng).addTo(map);
                                        }
                                        document.getElementById('latitudine').value = e.latlng.lat.toFixed(6);
                                        document.getElementById('longitudine').value = e.latlng.lng.toFixed(6);
                                    });
                                }
                                setTimeout(() => { map.invalidateSize(); }, 200); // Fix map display in modal
                            }

                            coordManual.addEventListener('change', function() {
                                if (this.checked) showManual();
                            });
                            coordMap.addEventListener('change', function() {
                                if (this.checked) showMap();
                            });

                            // Default state
                            showManual();
                        });
                        </script>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-outline-danger mt-4" data-bs-toggle="modal" data-bs-target="#logoutModal">Log-Out</button>

        <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="logoutLabel">Log-Out</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                    </div>
                    <div class="modal-body">
                        <p>Sei sicuro di voler effettuare il Log-Out?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Annulla</button>
                        <a href="script_logout.php"><button type="button" class="btn btn-danger">Log-Out</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
