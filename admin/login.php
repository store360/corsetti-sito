<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'includes/config.php';
$errore = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    if ($user === ADMIN_USER && password_verify($pass, ADMIN_PASS)) {
        $_SESSION['logged_in'] = true;
        header('Location: /admin/index.php');
        exit;
    } else {
        $errore = 'Credenziali non valide';
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corsetti Admin</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; background: #f0f2f5; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .login-box { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); width: 100%; max-width: 380px; }
        h1 { text-align: center; margin-bottom: 0.5rem; font-size: 1.5rem; color: #1a1a2e; }
        p.subtitle { text-align: center; color: #666; margin-bottom: 2rem; font-size: 0.9rem; }
        label { display: block; margin-bottom: 0.3rem; font-size: 0.85rem; color: #444; font-weight: 500; }
        input { width: 100%; padding: 0.75rem 1rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; margin-bottom: 1rem; }
        button { width: 100%; padding: 0.85rem; background: #4a90e2; color: white; border: none; border-radius: 8px; font-size: 1rem; cursor: pointer; font-weight: 600; }
        button:hover { background: #357abd; }
        .errore { background: #fee; color: #c00; padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.9rem; text-align: center; }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>Corsetti</h1>
        <p class="subtitle">Pannello di amministrazione</p>
        <?php if ($errore): ?>
            <div class="errore"><?= htmlspecialchars($errore) ?></div>
        <?php endif; ?>
        <form method="POST">
            <label>Username</label>
            <input type="text" name="username" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit">Accedi</button>
        </form>
    </div>
</body>
</html>
