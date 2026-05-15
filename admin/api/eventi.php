<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '/home/corsettistore360/public_html/admin/includes/config.php';

$conn = db_connect();
$result = $conn->query("SELECT * FROM eventi WHERE pubblicato=1 ORDER BY data_evento ASC");

$eventi = [];
while ($row = $result->fetch_assoc()) {
    $eventi[] = [
        'id'             => $row['id'],
        'titolo'         => $row['titolo'],
        'descrizione'    => $row['descrizione'],
        'data_evento'    => $row['data_evento'],
        'luogo'          => $row['luogo'],
        'link_biglietti' => $row['link_biglietti'],
        'immagine'       => $row['immagine'],
    ];
}

echo json_encode($eventi, JSON_UNESCAPED_UNICODE);
