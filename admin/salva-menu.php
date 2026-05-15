<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Metodo non consentito']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!$data || !isset($data['sezioniHome'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Dati non validi']);
    exit;
}

$menu = array_values(array_filter(
    array_map(fn($s) => [
        'id'     => strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $s['nome'])),
        'label'  => $s['nome'],
        'attiva' => $s['attiva']
    ], $data['sezioniHome']),
    fn($s) => !empty($s['label'])
));

$path = __DIR__ . '/menu.json';
file_put_contents($path, json_encode($menu, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

echo json_encode(['ok' => true, 'voci' => count($menu)]);