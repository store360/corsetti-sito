<?php
require_once 'includes/config.php';
if (!isset($_SESSION['logged_in'])) { header('Location: /admin/login.php'); exit; }

$conn = db_connect();
$msg = '';

// Elimina
if (isset($_GET['elimina'])) {
    $id = (int)$_GET['elimina'];
    $conn->query("DELETE FROM eventi WHERE id=$id");
    $msg = 'Evento eliminato.';
}

$eventi = $conn->query("SELECT * FROM eventi ORDER BY data_evento DESC");
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventi — Corsetti Admin</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; background: #f0f2f5; min-height: 100vh; }
        header { background: #1a1a2e; color: white; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; }
        header a { color: #aaa; text-decoration: none; font-size: 0.9rem; }
        header a:hover { color: white; }
        .container { padding: 1.5rem; max-width: 900px; margin: 0 auto; }
        .top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        h2 { color: #1a1a2e; }
        .btn { padding: 0.6rem 1.2rem; border-radius: 8px; border: none; cursor: pointer; font-size: 0.9rem; font-weight: 600; text-decoration: none; display: inline-block; }
        .btn-primary { background: #4a90e2; color: white; }
        .btn-danger { background: #e74c3c; color: white; }
        .btn-edit { background: #f39c12; color: white; }
        .msg { background: #d4edda; color: #155724; padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem; }
        table { width: 100%; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-collapse: collapse; overflow: hidden; }
        th { background: #1a1a2e; color: white; padding: 0.75rem 1rem; text-align: left; font-size: 0.85rem; }
        td { padding: 0.75rem 1rem; border-bottom: 1px solid #eee; font-size: 0.9rem; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        .actions { display: flex; gap: 0.5rem; }
        .vuoto { text-align: center; padding: 2rem; color: #666; }
        @media (max-width: 600px) {
            .col-luogo, .col-link { display: none; }
            .actions { flex-direction: column; }
        }
    </style>
</head>
<body>
    <header>
        <h1>📅 Eventi</h1>
        <a href="/admin/index.php">← Dashboard</a>
    </header>
    <div class="container">
        <?php if ($msg): ?><div class="msg"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
        <div class="top">
            <h2>Lista eventi</h2>
            <a href="/admin/evento-edit.php" class="btn btn-primary">+ Nuovo evento</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Titolo</th>
                    <th>Data</th>
                    <th class="col-luogo">Luogo</th>
                    <th>Stato</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($eventi->num_rows === 0): ?>
                <tr><td colspan="5" class="vuoto">Nessun evento ancora. <a href="/admin/evento-edit.php">Aggiungine uno!</a></td></tr>
            <?php else: ?>
                <?php while ($e = $eventi->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($e['titolo']) ?></td>
                    <td><?= $e['data_evento'] ? date('d/m/Y', strtotime($e['data_evento'])) : '—' ?></td>
                    <td class="col-luogo"><?= htmlspecialchars($e['luogo'] ?? '—') ?></td>
                    <td><?= $e['pubblicato'] ? '✅' : '⏸️' ?></td>
                    <td>
                        <div class="actions">
                            <a href="/admin/evento-edit.php?id=<?= $e['id'] ?>" class="btn btn-edit">Modifica</a>
                            <a href="?elimina=<?= $e['id'] ?>" class="btn btn-danger" onclick="return confirm('Eliminare questo evento?')">Elimina</a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
