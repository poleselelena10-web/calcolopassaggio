<?php
// calcolo.php - versione corretta con validazioni server-side

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Prendo i valori
$categoria = $_POST['categoria'] ?? '';
$ultra = $_POST['ultra'] ?? null; // potrebbe non esistere se non selezionato
$provincia = $_POST['provincia'] ?? ($_POST['provincia2'] ?? '');
$kw_raw = $_POST['kw'] ?? '';
$kw = is_numeric($kw_raw) ? (int)$kw_raw : null;
$portata = $_POST['portata'] ?? '';

// Se categoria non selezionata -> errore
if (!$categoria) {
    die('tutti i campi devono essere compilati');
}

// Spese fisse
$spese = [
    "Marca da bollo" => 16.00,
    "Pago PA" => 9.28,
    "Emolumenti PRA" => 27.00,
    "Motorizzazione" => 58.20,
    "Diritti Agenzia più IVA" => 97.60
];
$totale_spese_fisse = array_sum($spese);

// Mappe maggiorazioni come da tua lista
$no_magg = ["Aosta", "Bolzano", "Trento"];
$magg_25 = ["Crotone", "Sondrio", "Ferrara"];
$magg_20 = ["Arezzo", "Avellino", "Benevento", "Grosseto", "Latina", "Reggio Emilia", "Vicenza", "Trieste", "Udine", "Gorizia", "Pordenone"];
// tutte le altre province -> 30%

function getMaggiorazione($prov) {
    global $no_magg, $magg_20, $magg_25;
    if (in_array($prov, $no_magg)) return 0.0;
    if (in_array($prov, $magg_20)) return 0.20;
    if (in_array($prov, $magg_25)) return 0.25;
    return 0.30;
}

// Validazioni per categoria
$ipt = 0.0;

if ($categoria === 'Auto') {
    // ultratrentennale deve esistere
    if (!isset($ultra)) die('tutti i campi devono essere compilati');

    if ($ultra === 'si') {
        $ipt = 51.65;
    } else {
        // No -> serve provincia e kw validi
        if (!$provincia || $kw_raw === '') die('tutti i campi devono essere compilati');

        // kw deve essere intero 0-999
        if (!preg_match('/^\d{1,3}$/', (string)$kw_raw)) die('Inserisci un valore valido');

        $kw = (int)$kw_raw;
        $ipt = 151;
        if ($kw > 53) {
            $ipt += ($kw - 53) * 4.57;
        }
        $magg = getMaggiorazione($provincia);
        $ipt += $ipt * $magg;
    }
}
elseif ($categoria === 'Moto') {
    if (!isset($ultra)) die('tutti i campi devono essere compilati');

    if ($ultra === 'si') {
        $ipt = 25.82;
    } else {
        // moto non ultratrentennale: IPT = 0, ma richiediamo comunque la provincia
        if (!$provincia) die('tutti i campi devono essere compilati');
        $ipt = 0.0;
    }
}
elseif ($categoria === 'Autocarro') {
    // richiedo portata e provincia2
    if (!$portata || !$provincia) die('tutti i campi devono essere compilati');

    switch ($portata) {
        case "fino-7": $ipt = 199.35; break;
        case "oltre7-15": $ipt = 290.25; break;
        case "oltre15-30": $ipt = 326.40; break;
        case "oltre30-45": $ipt = 380.63; break;
        case "oltre45-60": $ipt = 452.93; break;
        case "oltre60-80": $ipt = 519.56; break;
        case "oltre80": $ipt = 646.60; break;
        default: die('tutti i campi devono essere compilati');
    }

    $ipt += $ipt * getMaggiorazione($provincia);
}
else {
    die('Categoria non valida');
}

$totale = $totale_spese_fisse + $ipt;
// -----------------------------
// SALVATAGGIO NEL DATABASE
// -----------------------------
try {
    require_once __DIR__ . '/db.php'; // carica $pdo da db.php

    $sql = "INSERT INTO preventivi (categoria, ultra, provincia, kw, portata, ipt, totale)
            VALUES (:categoria, :ultra, :provincia, :kw, :portata, :ipt, :totale)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':categoria' => $categoria,
        ':ultra'     => $ultra ?: null,
        ':provincia' => $provincia ?: null,
        ':kw'        => $kw ?: null,
        ':portata'   => $portata ?: null,
        ':ipt'       => $ipt,
        ':totale'    => $totale
    ]);

    $lastId = $pdo->lastInsertId();
} catch (Exception $e) {
    // Logga l'errore ma non interrompere la visualizzazione
    error_log("Errore DB: " . $e->getMessage());
    $lastId = null;
}

?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Risultato Preventivo</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <img src="logoauto.PNG" alt="Logo sito">
    <h1>Calcolo Passaggio Proprietà</h1>
  </header>

  <main style="padding:40px 10%;display:flex;justify-content:center;">
    <div class="card" style="max-width:900px;">
      <h1>Risultato del tuo Preventivo</h1>

      <p><strong>Categoria:</strong> <?= htmlspecialchars($categoria) ?></p>
      <?php if ($categoria === "Auto" || $categoria === "Moto"): ?>
        <p><strong>Veicolo ultratrentennale:</strong> <?= htmlspecialchars($ultra) ?></p>
        <p><strong>Provincia:</strong> <?= htmlspecialchars($provincia) ?></p>
        <?php if ($categoria === "Auto"): ?><p><strong>kW:</strong> <?= htmlspecialchars($kw) ?></p><?php endif; ?>
      <?php endif; ?>
      <?php if ($categoria === "Autocarro"): ?>
        <p><strong>Portata:</strong> <?= htmlspecialchars($portata) ?></p>
        <p><strong>Provincia:</strong> <?= htmlspecialchars($provincia) ?></p>
      <?php endif; ?>

      <hr>
      <h3>Dettaglio spese</h3>
      <ul>
        <?php foreach ($spese as $nome => $val): ?>
          <li><?= htmlspecialchars($nome) ?>: € <?= number_format($val, 2, ',', '.') ?></li>
        <?php endforeach; ?>
        <li><strong>IPT:</strong> € <?= number_format($ipt, 2, ',', '.') ?></li>
      </ul>

      <h2>Totale complessivo: € <?= number_format($totale, 2, ',', '.') ?></h2>

      <div style="display:flex;gap:10px;margin-top:18px;">
        <button onclick="window.print()" class="btn-primary">Stampa preventivo</button>
        <a href="index.php" class="btn-primary" style="display:inline-block; text-align:center; padding:10px 16px; border-radius:8px; text-decoration:none; color:#fff;">Torna indietro</a>
      </div>
    </div>
  </main>

  <footer>
    <p>© <?= date('Y') ?> Calcolo Passaggio di Proprietà</p>
  </footer>
</body>
</html>
