<?php
require_once __DIR__ . '/db.php';

// recupera gli ultimi 100 preventivi 
$stmt = $pdo->query("SELECT * FROM preventivi ORDER BY created_at DESC LIMIT 100");
$preventivi = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <title>Storico Preventivi</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="style.css">
  <style>
    .storico-container { padding: 30px; max-width:1100px; margin: 40px auto; }
    .storico-table { width: 100%; border-collapse: collapse; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 6px 18px rgba(0,0,0,0.06); }
    .storico-table thead { background:#f5f7fb; }
    .storico-table th, .storico-table td { padding:10px 12px; text-align:left; border-bottom: 1px solid #eee; font-size:0.95rem; }
    .storico-table th { font-weight:600; color:#333; }
    .no-data { padding:20px; background:#fff; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.04); text-align:center; color:#666; }
    .top-actions { display:flex; justify-content:space-between; align-items:center; gap:10px; margin-bottom:12px; }
    .btn-primary, .btn-secondary { display:inline-block; padding:8px 12px; border-radius:8px; color:#fff; text-decoration:none; }
    .btn-secondary { background:#6c757d; }
    .btn-primary { background:#007bff; }
    @media (max-width:800px) {
      .storico-table th, .storico-table td { font-size:0.85rem; padding:8px; }
    }
  </style>
</head>
<body>
  <header>
    <img src="logoauto.PNG" alt="Logo" style="height:42px; margin:12px;">
  </header>

  <main class="storico-container">
    <div class="top-actions">
      <h1>Storico Preventivi</h1>
      <div>
        <a href="index.php" class="btn-secondary">Torna al calcolatore</a>
        <a href="storico.php" class="btn-primary">Aggiorna</a>
      </div>
    </div>

    <?php if (count($preventivi) === 0): ?>
      <div class="no-data">Nessun preventivo salvato per ora.</div>
    <?php else: ?>
      <table class="storico-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Categoria</th>
            <th>Ultra</th>
            <th>Provincia</th>
            <th>kW</th>
            <th>Portata</th>
            <th>IPT (€)</th>
            <th>Totale (€)</th>
            <th>Data</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($preventivi as $p): ?>
            <tr>
              <td><?= htmlspecialchars($p['id']) ?></td>
              <td><?= htmlspecialchars($p['categoria']) ?></td>
              <td><?= htmlspecialchars($p['ultra'] ?? '-') ?></td>
              <td><?= htmlspecialchars($p['provincia'] ?? '-') ?></td>
              <td><?= $p['kw'] !== null ? htmlspecialchars($p['kw']) : '-' ?></td>
              <td><?= htmlspecialchars($p['portata'] ?? '-') ?></td>
              <td><?= number_format($p['ipt'], 2, ',', '.') ?></td>
              <td><strong><?= number_format($p['totale'], 2, ',', '.') ?></strong></td>
              <td><?= htmlspecialchars($p['created_at']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </main>

  <footer>
    <p style="text-align:center; padding:20px 0; color:#fff; background:#0056b3;">© <?= date('Y') ?> Calcolo Passaggio di Proprietà</p>
  </footer>
</body>
</html>
