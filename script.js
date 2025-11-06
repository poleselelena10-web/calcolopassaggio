// script.js - crea il form, popola le province e valida i campi prima dell'invio

document.addEventListener("DOMContentLoaded", () => {
  console.log("✅ Script caricato correttamente");

  const startBtn = document.getElementById("startCalc");
  const formSection = document.getElementById("form-section");
  let form = document.getElementById("calcForm");

  // Lista completa delle province (come richiesto)
  const provinceList = [
    "Agrigento","Alessandria","Ancona","Ascoli Piceno","Asti","Aosta","Arezzo",
    "Avellino","Bari","Barletta-Andria-Trani","Belluno","Benevento","Bergamo","Biella",
    "Bologna","Bolzano","Brescia","Brindisi","Cagliari","Caltanissetta","Campobasso",
    "Caserta","Catania","Catanzaro","Chieti","Como","Cosenza","Cremona","Cuneo","Enna",
    "Fermo","Ferrara","Firenze","Foggia","Forlì-Cesena","Frosinone","Genova","Gorizia",
    "Grosseto","Imperia","Isernia","L'Aquila","La Spezia","Latina","Lecce","Lecco",
    "Livorno","Lodi","Lucca","Macerata","Mantova","Massa Carrara","Matera","Messina",
    "Milano","Modena","Monza e Brianza","Napoli","Novara","Nuoro","Oristano","Padova",
    "Palermo","Parma","Pavia","Perugia","Pesaro Urbino","Pescara","Piacenza","Pisa",
    "Pistoia","Pordenone","Potenza","Prato","Ragusa","Ravenna","Reggio Calabria",
    "Reggio Emilia","Rieti","Rimini","Roma","Rovigo","Salerno","Sassari","Savona",
    "Siena","Sondrio","Siracusa","Sud Sardegna","Taranto","Teramo","Terni","Torino",
    "Trapani","Trento","Treviso","Trieste","Udine","Varese","Venezia","Verbano-Cusio-Ossola",
    "Vercelli","Verona","Vibo Valentia","Viterbo","Crotone"
  ];

  function ensureFormStructure() {
    if (!form) {
      // crea form se non esiste
      form = document.createElement("form");
      form.id = "calcForm";
      form.method = "POST";
      form.action = "calcolo.php";
      formSection.appendChild(form);
    }

    // se mancano i campi base li aggiungiamo
    if (!form.querySelector('select[name="categoria"]')) {
      form.innerHTML = `
        <label for="categoria">Seleziona la categoria del veicolo:</label>
        <select id="categoria" name="categoria" required>
          <option value="">-- Seleziona --</option>
          <option value="Auto">Auto</option>
          <option value="Moto">Moto</option>
          <option value="Autocarro">Autocarro</option>
        </select>

        <div id="extraFields"></div>

        <div id="formError" class="error" style="display:none;margin-top:8px;color:#d00;">Tutti i campi devono essere compilati</div>

        <button type="submit" class="btn-secondary" style="margin-top:18px;">Vedi preventivo</button>
      `;
    }
  }

  // popola una select con le province
  function buildProvinciaSelect(name, id) {
    let select = document.createElement('select');
    select.name = name;
    select.id = id;
    select.required = true;

    let opt = document.createElement('option');
    opt.value = "";
    opt.textContent = "-- Seleziona provincia --";
    select.appendChild(opt);

    provinceList.forEach(p => {
      let o = document.createElement('option');
      o.value = p;
      o.textContent = p;
      select.appendChild(o);
    });
    return select;
  }

  // aggiungi listener al select categoria (dopo che il form è creato)
  function addCategoriaListener() {
    form = document.getElementById("calcForm");
    const categoriaSelect = form.querySelector('#categoria');
    const extraFields = form.querySelector('#extraFields');

    if (!categoriaSelect) return;

    categoriaSelect.addEventListener('change', () => {
      const categoria = categoriaSelect.value;
      extraFields.innerHTML = '';

      if (categoria === 'Auto' || categoria === 'Moto') {
        // DOMANDA ultratrentennale (obbligatoria)
        const ultraLabel = document.createElement('label');
        ultraLabel.textContent = 'Si tratta di un veicolo ultratrentennale?';
        extraFields.appendChild(ultraLabel);

        const ultraDiv = document.createElement('div');
        ultraDiv.style.display = "flex";
        ultraDiv.style.gap = "12px";
        ultraDiv.style.alignItems = "center";

        const radiosHtml = `
          <label><input type="radio" name="ultra" value="si"> Sì</label>
          <label><input type="radio" name="ultra" value="no"> No</label>
        `;
        ultraDiv.innerHTML = radiosHtml;
        extraFields.appendChild(ultraDiv);

        // Provincia (menu a tendina) - richiesta sempre
        const provLabel = document.createElement('label');
        provLabel.setAttribute('for','provincia');
        provLabel.textContent = 'Provincia di residenza:';
        extraFields.appendChild(provLabel);
        extraFields.appendChild(buildProvinciaSelect('provincia','provincia'));

        if (categoria === 'Auto') {
          // kW per le auto (numero intero 0-999)
          const kwLabel = document.createElement('label');
          kwLabel.setAttribute('for','kw');
          kwLabel.textContent = 'kW del veicolo (numero intero):';
          extraFields.appendChild(kwLabel);

          const kwInput = document.createElement('input');
          kwInput.type = 'number';
          kwInput.name = 'kw';
          kwInput.id = 'kw';
          kwInput.min = 0;
          kwInput.max = 999;
          kwInput.step = 1;
          kwInput.required = true;
          kwInput.placeholder = 'Es. 85';
          extraFields.appendChild(kwInput);
        }
      }

      if (categoria === 'Autocarro') {
        // Portata (select)
        const portLabel = document.createElement('label');
        portLabel.setAttribute('for','portata');
        portLabel.textContent = 'Portata (quintali):';
        extraFields.appendChild(portLabel);

        const portSelect = document.createElement('select');
        portSelect.name = 'portata';
        portSelect.id = 'portata';
        portSelect.required = true;
        portSelect.innerHTML = `
          <option value="">-- Seleziona --</option>
          <option value="fino-7">fino a 7</option>
          <option value="oltre7-15">oltre 7 e fino a 15</option>
          <option value="oltre15-30">oltre 15 e fino a 30</option>
          <option value="oltre30-45">oltre 30 e fino a 45</option>
          <option value="oltre45-60">oltre 45 e fino a 60</option>
          <option value="oltre60-80">oltre 60 e fino a 80</option>
          <option value="oltre80">oltre 80</option>
        `;
        extraFields.appendChild(portSelect);

        // Provincia per autocarro (select)
        const provLabel2 = document.createElement('label');
        provLabel2.setAttribute('for','provincia2');
        provLabel2.textContent = 'Provincia di residenza:';
        extraFields.appendChild(provLabel2);
        extraFields.appendChild(buildProvinciaSelect('provincia2','provincia2'));
      }
    });
  }

  // validazione prima dell'invio (client-side)
  function addFormValidation() {
    form = document.getElementById("calcForm");
    if (!form) return;

    form.addEventListener('submit', (e) => {
      const errDiv = form.querySelector('#formError');
      errDiv.style.display = 'none';
      let categoria = form.querySelector('select[name="categoria"]').value;
      if (!categoria) {
        errDiv.textContent = 'tutti i campi devono essere compilati';
        errDiv.style.display = 'block';
        e.preventDefault();
        return;
      }

      // controlli per Auto
      if (categoria === 'Auto') {
        // ultratrentennale deve essere selezionato
        const ultra = form.querySelector('input[name="ultra"]:checked');
        const prov = form.querySelector('select[name="provincia"]');
        const kw = form.querySelector('input[name="kw"]');

        if (!ultra || !prov || !prov.value || !kw || kw.value === '') {
          errDiv.textContent = 'tutti i campi devono essere compilati';
          errDiv.style.display = 'block';
          e.preventDefault();
          return;
        }

        // kW deve essere intero 0-999
        if (!/^\d{1,3}$/.test(kw.value)) {
          errDiv.textContent = 'Inserisci un valore valido';
          errDiv.style.display = 'block';
          e.preventDefault();
          return;
        }
      }

      // controlli per Moto
      if (categoria === 'Moto') {
        const ultra = form.querySelector('input[name="ultra"]:checked');
        const prov = form.querySelector('select[name="provincia"]');
        if (!ultra || !prov || !prov.value) {
          errDiv.textContent = 'tutti i campi devono essere compilati';
          errDiv.style.display = 'block';
          e.preventDefault();
          return;
        }
      }

      // controlli per Autocarro
      if (categoria === 'Autocarro') {
        const port = form.querySelector('select[name="portata"]');
        const prov2 = form.querySelector('select[name="provincia2"]');
        if (!port || !port.value || !prov2 || !prov2.value) {
          errDiv.textContent = 'tutti i campi devono essere compilati';
          errDiv.style.display = 'block';
          e.preventDefault();
          return;
        }
      }

      // se arrivo qui, passa la validazione: il form verrà inviato
    });
  }

  // evento start: crea struttura, mostra form e collega listener
  startBtn.addEventListener("click", () => {
    ensureFormStructure();
    formSection.classList.remove("hidden");
    startBtn.style.display = "none";
    addCategoriaListener();
    addFormValidation();
    // forza l'apertura del select categoria se vuoi (non necessario)
    // document.getElementById('categoria').focus();
    formSection.scrollIntoView({ behavior: "smooth" });
  });

});
