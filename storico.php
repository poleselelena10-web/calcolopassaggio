<?php
require_once __DIR__ . '/db.php';
$stmt = $pdo->query("SELECT * FROM preventivi ORDER BY created_at DESC LIMIT 50");
$preventivi = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Storico Preventivi</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header class="header">
    <div class="logo">
      <img src="logo.png" alt="Logo Agenzia" />
      <h1>Storico Preventivi</h1>
    </div>
  </header>

  <main class="container">
    <h2>Ultimi preventivi registrati</h2>
    <table class="storico-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Categoria</th>
          <th>Provincia</th>
          <th>kW</th>
          <th>Portata</th>
          <th>Totale (€)</th>
          <th>Data</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($preventivi as $p): ?>
          <tr>
            <td><?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['categoria']) ?></td>
            <td><?= htmlspecialchars($p['provincia']) ?></td>
            <td><?= $p['kw'] ?: '-' ?></td>
            <td><?= htmlspecialchars($p['portata']) ?: '-' ?></td>
            <td><strong><?= number_format($p['totale'], 2, ',', '.') ?></strong></td>
            <td><?= $p['created_at'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </main>

  <footer>
    <p>© 2025 Calcolo Passaggio di Proprietà</p>
  </footer>
</body>
</html>
