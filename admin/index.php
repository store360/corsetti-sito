<?php
require_once 'includes/config.php';
if (!isset($_SESSION['logged_in'])) { header('Location: /admin/login.php'); exit; }
$conn = db_connect();
$n_eventi = $conn->query("SELECT COUNT(*) as n FROM eventi")->fetch_assoc()['n'];
$n_notizie = $conn->query("SELECT COUNT(*) as n FROM notizie")->fetch_assoc()['n'];
$n_immagini = $conn->query("SELECT COUNT(*) as n FROM immagini")->fetch_assoc()['n'];
$n_documenti = $conn->query("SELECT COUNT(*) as n FROM documenti")->fetch_assoc()['n'];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corsetti Admin</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; background: #f0f2f5; min-height: 100vh; }
        header { background: #1a1a2e; color: white; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        header h1 { font-size: 1.2rem; }
        header a { color: #aaa; text-decoration: none; font-size: 0.9rem; }
        header a:hover { color: white; }
        .container { padding: 1.5rem; max-width: 900px; margin: 0 auto; }
        h2 { margin-bottom: 1.5rem; color: #1a1a2e; font-size: 1.1rem; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; }
        .card { background: white; border-radius: 12px; padding: 1.5rem; text-align: center; text-decoration: none; color: inherit; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: transform 0.2s, box-shadow 0.2s; }
        .card:hover { transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0,0,0,0.12); }
        .card .icon { font-size: 2.5rem; margin-bottom: 0.5rem; }
        .card .numero { font-size: 2rem; font-weight: 700; color: #4a90e2; }
        .card .label { font-size: 0.9rem; color: #666; margin-top: 0.3rem; }
    </style>
</head>
<body>
    <header>
        <h1>🏥 Corsetti Admin</h1>
        <a href="/admin/logout.php">Esci</a>
    </header>
    <div class="container">
        <h2>Cosa vuoi gestire?</h2>
        <div class="grid">
            <a href="/admin/eventi.php" class="card">
                <div class="icon">📅</div>
                <div class="numero"><?= $n_eventi ?></div>
                <div class="label">Eventi</div>
            </a>
            <a href="/admin/notizie.php" class="card">
                <div class="icon">📰</div>
                <div class="numero"><?= $n_notizie ?></div>
                <div class="label">Notizie</div>
            </a>
            <a href="/admin/immagini.php" class="card">
                <div class="icon">🖼️</div>
                <div class="numero"><?= $n_immagini ?></div>
                <div class="label">Immagini</div>
            </a>
            <a href="/admin/documenti.php" class="card">
                <div class="icon">📄</div>
                <div class="numero"><?= $n_documenti ?></div>
                <div class="label">Documenti</div>
            </a>
        </div>
    </div>
</body>
</html>
