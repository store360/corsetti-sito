<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('/home/corsettistore360/public_html/admin/includes/config.php');

$conn = db_connect();
$result = $conn->query("SELECT * FROM eventi WHERE pubblicato=1 ORDER BY data_evento ASC");
$eventi = [];
while ($row = $result->fetch_assoc()) {
    $eventi[] = $row;
}
$conn->close();

$mesi = ['Gen','Feb','Mar','Apr','Mag','Giu','Lug','Ago','Set','Ott','Nov','Dic'];
$mesi_long = ['gennaio','febbraio','marzo','aprile','maggio','giugno','luglio','agosto','settembre','ottobre','novembre','dicembre'];

function fmt_date($d, $long = false) {
    global $mesi, $mesi_long;
    if (!$d) return '';
    [$y, $m, $day] = explode('-', $d);
    $ms = $long ? $mesi_long[(int)$m - 1] : $mesi[(int)$m - 1];
    return $long ? "$day $ms $y" : $day . ' ' . $ms . ' ' . $y;
}

$json_ld_events = [];
foreach ($eventi as $ev) {
    $item = [
        '@type' => 'Event',
        'name' => $ev['titolo'],
        'description' => $ev['descrizione'] ?? '',
        'startDate' => $ev['data_evento'],
        'location' => [
            '@type' => 'Place',
            'name' => $ev['luogo'] ?? 'Farmacia Corsetti',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Roma',
                'addressCountry' => 'IT',
            ],
        ],
        'organizer' => [
            '@type' => 'Organization',
            'name' => 'Farmacia Corsetti',
            'url' => 'https://corsetti.store360.it',
        ],
    ];
    if (!empty($ev['link_biglietti'])) {
        $item['offers'] = ['@type' => 'Offer', 'url' => $ev['link_biglietti']];
    }
    if (!empty($ev['immagine'])) {
        $item['image'] = '/uploads/' . $ev['immagine'];
    }
    $json_ld_events[] = $item;
}
$json_ld = json_encode([
    '@context' => 'https://schema.org',
    '@graph' => $json_ld_events,
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

$count = count($eventi);
$meta_desc = $count > 0
    ? "Scopri i prossimi $count eventi di Farmacia Corsetti: workshop, screening gratuiti e iniziative di prevenzione. Iscriviti o acquista i biglietti online."
    : "Scopri gli eventi e le iniziative di Farmacia Corsetti: workshop, screening gratuiti e giornate di prevenzione." ;
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Eventi e Iniziative – Farmacia Corsetti</title>
<meta name="description" content="<?= htmlspecialchars($meta_desc) ?>" />
<link rel="canonical" href="https://corsetti.store360.it/eventi" />
<meta property="og:type" content="website" />
<meta property="og:title" content="Eventi e Iniziative – Farmacia Corsetti" />
<meta property="og:description" content="<?= htmlspecialchars($meta_desc) ?>" />
<meta property="og:url" content="https://corsetti.store360.it/eventi" />
<meta property="og:locale" content="it_IT" />
<script type="application/ld+json"><?= $json_ld ?></script>
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Display:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,700;1,800&display=swap" rel="stylesheet" />
<style>
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  html{scroll-behavior:smooth}
  body{font-family:'Noto Sans Display',sans-serif;background:#fff;color:#1a2332;-webkit-font-smoothing:antialiased}
  ::-webkit-scrollbar{width:5px}
  ::-webkit-scrollbar-thumb{background:#c5d8ea;border-radius:3px}
  a{color:inherit;text-decoration:none}
  .blue{color:#1a7ec8}
  header{position:sticky;top:0;z-index:200;background:#fff;border-bottom:1px solid #e8eff7;box-shadow:0 2px 16px rgba(26,126,200,.08)}
  .nav-inner{max-width:1180px;margin:0 auto;padding:0 20px;display:flex;align-items:center;height:62px;gap:12px}
  .nav-inner nav{flex:1;display:flex;gap:2px}
  .nav-inner nav a{padding:6px 13px;border-radius:7px;font-size:13.5px;color:#4a5f75;transition:background .18s}
  .nav-inner nav a:hover{background:#f3f7fc}
  .nav-inner nav a.active{background:#1a7ec812;color:#1a7ec8}
  .btn-primary{background:#1a7ec8;color:#fff;border:none;cursor:pointer;padding:8px 22px;border-radius:8px;font-size:13.5px;font-family:'Noto Sans Display',sans-serif;box-shadow:0 2px 10px rgba(26,126,200,.27);text-decoration:none;display:inline-block}
  .hero{background:linear-gradient(160deg,#edf7fa 0%,#e6f4f8 50%,#f2fafb 100%);padding:64px 28px 72px}
  .hero-inner{max-width:1180px;margin:0 auto}
  .eyebrow{font-size:11px;color:#1a7ec8;letter-spacing:.12em;text-transform:uppercase;margin-bottom:14px}
  h1{font-size:46px;line-height:1.1;letter-spacing:-.03em;color:#0f1e2d;margin-bottom:8px;font-weight:400}
  h1 em{font-style:italic;color:#1a7ec8}
  .hero p{font-size:16px;color:#4a6070;line-height:1.7;max-width:520px;margin-top:20px}
  .events-section{background:#fff;padding:64px 28px}
  .events-inner{max-width:1180px;margin:0 auto}
  .events-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}
  .event-card{background:#fff;border-radius:16px;border:1px solid #e4f0f8;padding:28px 24px;display:flex;gap:20px;align-items:flex-start;transition:all .22s}
  .event-card:hover{box-shadow:0 8px 28px rgba(26,126,200,.08);border-color:rgba(26,126,200,.21);transform:translateY(-3px)}
  .date-block{flex-shrink:0;width:58px;height:66px;border-radius:12px;background:rgba(26,126,200,.06);border:1px solid rgba(26,126,200,.15);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:1px}
  .date-day{font-size:22px;color:#1a7ec8;line-height:1}
  .date-month{font-size:11px;color:#1a7ec8;text-transform:uppercase;letter-spacing:.06em}
  .date-year{font-size:10px;color:#9ab4c8}
  .event-tag{display:inline-block;background:rgba(26,126,200,.08);color:#1a7ec8;border-radius:6px;padding:3px 10px;font-size:11px;letter-spacing:.04em;margin-bottom:10px}
  .event-card h2{font-size:16px;color:#0f1e2d;margin-bottom:8px;line-height:1.35;font-weight:500}
  .event-card p{font-size:13.5px;color:#6a8090;line-height:1.65;margin-bottom:14px}
  .event-meta{display:flex;gap:18px;flex-wrap:wrap}
  .event-meta span{display:flex;align-items:center;gap:6px;font-size:12px;color:#8aa4b8}
  .event-link{display:inline-block;margin-top:12px;font-size:12.5px;color:#1a7ec8;border:1px solid rgba(26,126,200,.3);border-radius:7px;padding:5px 14px;transition:all .18s}
  .event-link:hover{background:#1a7ec8;color:#fff}
  .cta-box{margin-top:48px;background:linear-gradient(135deg,#edf7fa 0%,#e0f2f7 100%);border-radius:18px;padding:36px 40px;border:1px solid #d0eaf4;display:flex;justify-content:space-between;align-items:center;gap:20px}
  .cta-box h3{font-size:18px;color:#0f1e2d;margin-bottom:6px;font-weight:500}
  .cta-box p{font-size:14px;color:#6a8090;line-height:1.6}
  .cta-btns{display:flex;gap:12px;flex-wrap:wrap}
  .btn-wa{background:#25d366;color:#fff;border:none;cursor:pointer;padding:12px 24px;border-radius:9px;font-size:14px;font-family:'Noto Sans Display',sans-serif;text-decoration:none;display:inline-block}
  footer{background:#f5f9fc;border-top:1px solid #e8eff7;padding:28px 20px;text-align:center;font-size:13px;color:#8aa4b8}
  @media(max-width:900px){.events-grid{grid-template-columns:1fr}}
  @media(max-width:600px){
    h1{font-size:34px}
    .hero{padding:40px 18px 48px}
    .events-section{padding:40px 18px}
    .cta-box{flex-direction:column;align-items:flex-start;padding:28px 20px}
    .nav-inner nav{display:none}
  }
</style>
</head>
<body>

<header>
  <div class="nav-inner">
    <a href="/" style="flex-shrink:0;display:flex;align-items:center">
      <img src="/corsetti-logo.png" alt="Farmacia Corsetti" style="height:46px;object-fit:contain" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'" />
      <span style="display:none;align-items:center;gap:6px;font-size:14px;color:#1a2332">Farmacia Corsetti</span>
    </a>
    <nav id="main-nav">
      <!-- popolato da menu.json -->
    </nav>
    <script>
    fetch('/menu.json?t='+Date.now())
      .then(r=>r.json())
      .then(voci=>{
        const nav=document.getElementById('main-nav');
        voci.filter(v=>v.attiva).forEach(v=>{
          const isEventi = v.id==='eventi' || v.label.toLowerCase().includes('event');
          const href = isEventi ? '/eventi.php' : '/#'+v.id;
          const a=document.createElement('a');
          a.href=href;
          a.textContent=v.label;
          if(isEventi) a.className='active';
          nav.appendChild(a);
        });
      })
      .catch(()=>{
        document.getElementById('main-nav').innerHTML=`
          <a href="/">Home</a>
          <a href="/eventi.php" class="active">Eventi</a>
          <a href="/#contatti">Contatti</a>`;
      });
    </script>
    <a href="/#contatti" class="btn-primary" style="margin-left:auto">Accedi</a>
  </div>
</header>

<main>
  <div class="hero">
    <div class="hero-inner">
      <p class="eyebrow">Farmacia Corsetti</p>
      <h1>Eventi<br><em>e iniziative</em></h1>
      <p>Incontri, screening gratuiti, workshop e giornate di prevenzione. Resta informato su tutto quello che succede in farmacia.</p>
    </div>
  </div>

  <section class="events-section" aria-label="Lista eventi">
    <div class="events-inner">
      <?php if (empty($eventi)): ?>
        <p style="color:#6a8090;font-size:16px;text-align:center;padding:40px 0">Nessun evento in programma al momento. Torna presto!</p>
      <?php else: ?>
      <div class="events-grid">
        <?php foreach ($eventi as $ev):
          $d = $ev['data_evento'] ? explode('-', $ev['data_evento']) : null;
          $day   = $d ? ltrim($d[2], '0') : '--';
          $month = $d ? $mesi[(int)$d[1] - 1] : '---';
          $year  = $d ? $d[0] : '----';
          $date_full = $d ? fmt_date($ev['data_evento'], true) : '';
        ?>
        <article class="event-card">
          <div class="date-block" aria-label="Data: <?= htmlspecialchars($date_full) ?>">
            <div class="date-day"><?= htmlspecialchars($day) ?></div>
            <div class="date-month"><?= htmlspecialchars($month) ?></div>
            <div class="date-year"><?= htmlspecialchars($year) ?></div>
          </div>
          <div>
            <div class="event-tag">Evento</div>
            <h2><?= htmlspecialchars($ev['titolo']) ?></h2>
            <?php if (!empty($ev['descrizione'])): ?>
              <p><?= htmlspecialchars($ev['descrizione']) ?></p>
            <?php endif ?>
            <div class="event-meta">
              <?php if (!empty($ev['luogo'])): ?>
              <span>
                <svg width="13" height="13" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M7 1C4.8 1 3 2.8 3 5c0 3 4 8 4 8s4-5 4-8c0-2.2-1.8-4-4-4z" stroke="currentColor" stroke-width="1.3"/><circle cx="7" cy="5" r="1.5" stroke="currentColor" stroke-width="1.3"/></svg>
                <?= htmlspecialchars($ev['luogo']) ?>
              </span>
              <?php endif ?>
              <span>
                <svg width="13" height="13" viewBox="0 0 14 14" fill="none" aria-hidden="true"><circle cx="7" cy="7" r="5.5" stroke="currentColor" stroke-width="1.3"/><path d="M5 7a2 2 0 1 0 4 0 2 2 0 0 0-4 0z" stroke="currentColor" stroke-width="1.3"/></svg>
                <?= empty($ev['link_biglietti']) ? 'Accesso libero' : 'A pagamento' ?>
              </span>
            </div>
            <?php if (!empty($ev['link_biglietti'])): ?>
              <a href="<?= htmlspecialchars($ev['link_biglietti']) ?>" class="event-link" target="_blank" rel="noopener">Acquista biglietti</a>
            <?php endif ?>
          </div>
        </article>
        <?php endforeach ?>
      </div>
      <?php endif ?>

      <div class="cta-box">
        <div>
          <h3>Vuoi essere avvisato sui prossimi eventi?</h3>
          <p>Iscriviti alla newsletter o scrivici su WhatsApp per restare aggiornato.</p>
        </div>
        <div class="cta-btns">
          <a href="/#contatti" class="btn-primary" style="padding:12px 24px;font-size:14px">Iscriviti alla newsletter</a>
          <a href="https://wa.me/39060000000" class="btn-wa" target="_blank" rel="noopener">📲 WhatsApp</a>
        </div>
      </div>
    </div>
  </section>
</main>

<footer>
  <p>&copy; <?= date('Y') ?> Farmacia Corsetti &mdash; Via <span>...</span>, Roma &mdash; <a href="/" style="color:#1a7ec8">Torna alla home</a></p>
</footer>

</body>
</html>
