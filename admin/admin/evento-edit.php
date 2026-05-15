<?php
require_once 'includes/config.php';
if (!isset($_SESSION['logged_in'])) { header('Location: /admin/login.php'); exit; }

$conn = db_connect();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$evento = ['titolo'=>'','descrizione'=>'','data_evento'=>'','luogo'=>'','link_biglietti'=>'','pubblicato'=>1];
$msg = '';

if ($id) {
    $r = $conn->query("SELECT * FROM eventi WHERE id=$id");
    if ($r->num_rows) $evento = $r->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titolo = $conn->real_escape_string($_POST['titolo']);
    $descrizione = $conn->real_escape_string($_POST['descrizione']);
    $data_evento = $conn->real_escape_string($_POST['data_evento']);
    $luogo = $conn->real_escape_string($_POST['luogo']);
    $link_biglietti = $conn->real_escape_string($_POST['link_biglietti']);
    $pubblicato = isset($_POST['pubblicato']) ? 1 : 0;

    if ($id) {
        $conn->query("UPDATE eventi SET titolo='$titolo', descrizione='$descrizione', data_evento='$data_evento', luogo='$luogo', link_biglietti='$link_biglietti', pubblicato=$pubblicato WHERE id=$id");
        $msg = 'Evento aggiornato!';
    } else {
        $conn->query("INSERT INTO eventi (titolo, descrizione, data_evento, luogo, link_biglietti, pubblicato) VALUES ('$titolo','$descrizione','$data_evento','$luogo','$link_biglietti',$pubblicato)");
        $id = $conn->insert_id;
        $msg = 'Evento creato!';
    }
    $r = $conn->query("SELECT * FROM eventi WHERE id=$id");
    $evento = $r->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evento — Corsetti Admin</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; background: #f0f2f5; min-height: 100vh; }
        header { background: #1a1a2e; color: white; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        header a { color: #aaa; text-decoration: none; font-size: 0.9rem; }
        .container { padding: 1.5rem; max-width: 600px; margin: 0 auto; }
        h2 { color: #1a1a2e; margin-bottom: 1.5rem; }
        .card { background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        label { display: block; margin-bottom: 0.3rem; font-size: 0.85rem; color: #444; font-weight: 500; }
        input, textarea, select { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; margin-bottom: 1.2rem; font-family: sans-serif; }
        textarea { height: 120px; resize: vertical; }
        .check-row { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.2rem; }
        .check-row input { width: auto; margin: 0; }
        button { width: 100%; padding: 0.85rem; background: #4a90e2; color: white; border: none; border-radius: 8px; font-size: 1rem; cursor: pointer; font-weight: 600; }
        button:hover { background: #357abd; }
        .msg { background: #d4edda; color: #155724; padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <header>
        <h1><?= $id ? 'Modifica' : 'Nuovo' ?> Evento</h1>
        <a href="/admin/eventi.php">← Lista eventi</a>
    </header>
    <div class="container">
        <?php if ($msg): ?><div class="msg"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
        <div class="card">
            <form method="POST">
                <label>Titolo *</label>
                <input type="text" name="titolo" value="<?= htmlspecialchars($evento['titolo']) ?>" required>
                <label>Descrizione</label>
                <textarea name="descrizione"><?= htmlspecialchars($evento['descrizione'] ?? '') ?></textarea>
                <label>Data evento</label>
                <input type="date" name="data_evento" value="<?= $evento['data_evento'] ?>">
                <label>Luogo</label>
                <input type="text" name="luogo" value="<?= htmlspecialchars($evento['luogo'] ?? '') ?>">
                <label>Link biglietti</label>
                <input type="url" name="link_biglietti" value="<?= htmlspecialchars($evento['link_biglietti'] ?? '') ?>">
                <div class="check-row">
                    <input type="checkbox" name="pubblicato" id="pubblicato" <?= $evento['pubblicato'] ? 'checked' : '' ?>>
                    <label for="pubblicato" style="margin:0">Pubblicato</label>
                </div>
                <button type="submit">💾 Salva evento</button>
            </form>
        </div>
    </div>
</body>
</html>
