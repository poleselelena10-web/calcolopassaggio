<?php
// index.php - Pagina principale
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calcolo Passaggio di Proprietà</title>

  <!-- Collegamento al foglio di stile -->
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- HEADER BLU CON LOGO -->
  <header>
    <img src="logoauto.PNG" alt="Logo sito">
    <h1>Calcolo Passaggio Proprietà</h1>
  </header>

  <!-- SEZIONE PRINCIPALE -->
  <section class="hero">
    <div class="hero-text">
      <h1>Hai bisogno di aiuto per calcolare il costo del tuo prossimo passaggio di proprietà?</h1>
      <p class="lead">Inserisci i dati richiesti e ottieni subito un preventivo trasparente e aggiornato.</p>
      <button id="startCalc" class="btn-primary">Calcola ora</button>
    </div>

    <!-- immagine dell'auto -->
    <img src="autosito.png" alt="Auto" class="hero-img">
  </section>

  <!-- SEZIONE SPIEGAZIONE -->
  <section class="section-info">
    <h3>Come viene calcolato un preventivo?</h3>
    <p>
      Il calcolo del passaggio di proprietà di un veicolo tiene conto di diversi fattori che variano
      in base alle caratteristiche del mezzo e alla situazione dell’acquirente.
      Innanzitutto, il costo base comprende le imposte dovute al PRA (Pubblico Registro Automobilistico)
      e alla Motorizzazione Civile, che possono differire a seconda della regione di residenza dell’acquirente.
      A questi si aggiungono i diritti e gli oneri di agenzia, necessari per la gestione delle pratiche burocratiche.
      Tali costi possono variare da un’agenzia all’altra, in base ai servizi offerti e alle tariffe applicate.
      Nel nostro sistema di calcolo, viene considerato un importo medio di 80 euro per i diritti di agenzia,
      valore che rappresenta la media più comune sul territorio nazionale.
      Un altro elemento importante è la tipologia del veicolo (auto, moto, autocarro) e la sua potenza espressa in kW,
      che incidono direttamente sull’importo dell’Imposta Provinciale di Trascrizione (IPT).
      Per i veicoli ultratrentennali o di interesse storico, sono previste agevolazioni e riduzioni specifiche sui costi di registrazione.
      Il nostro sistema di calcolo tiene conto automaticamente di tutti questi parametri, offrendo un preventivo preciso
      e aggiornato in base ai dati inseriti, così da conoscere subito il costo reale del passaggio di proprietà, senza sorprese.
    </p>
  </section>

  <!-- FORM DI CALCOLO (inizialmente nascosto) -->
  <section id="form-section" class="hidden">
    <h3>Calcola il tuo preventivo</h3>
    <form id="calcForm" action="calcolo.php" method="POST">
      <!-- qui JS aggiunge dinamicamente le domande -->
    </form>
  </section>

  <!-- FOOTER -->
  <footer>
    <p>© 2025 Calcolo Passaggio di Proprietà</p>
  </footer>

  <!-- COLLEGAMENTO SCRIPT JS -->
  <script src="script.js"></script>
</body>
</html>
