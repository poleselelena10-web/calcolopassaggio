# 🚗 Calcolo Passaggio di Proprietà

Applicazione web sviluppata in **PHP**, **HTML**, **CSS** e **JavaScript** che permette di calcolare il costo del passaggio di proprietà per:
- Auto  
- Moto  
- Autocarri  

Il calcolo include:
- IPT (Imposta Provinciale di Trascrizione)
- Spese fisse
- Maggiorazioni provinciali
- Regole specifiche per ultratrentennali

L’app salva automaticamente ogni preventivo nel database, permettendo di consultare uno **Storico Preventivi**.

---

## 📁 Struttura del Progetto

calcolopassaggio/
│── index.php → Form principale per inserire i dati
│── calcolo.php → Logica di calcolo + salvataggio nel DB
│── storico.php → Visualizzazione dello storico preventivi
│── db.php → Connessione al database (local-only)
│── style.css → Stili dell’interfaccia
│── script.js → Logica lato client e validazioni
│── .gitignore → Protezione dati sensibili

---

## 🗄️ Database MySQL

Il progetto utilizza un database MySQL locale (XAMPP).  
Il file `db.php` contiene le credenziali **solo per uso in locale**:

- **host:** 127.0.0.1  
- **utente:** root  
- **password:** *(vuota per XAMPP)*  
- **database:** calcolo_preventivi  

---
### 📌 Struttura tabella `preventivi`

```sql
CREATE TABLE preventivi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria VARCHAR(20),
    ultra VARCHAR(5),
    provincia VARCHAR(50),
    kw INT NULL,
    portata VARCHAR(20),
    ipt DECIMAL(10,2),
    totale DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```
## ▶️ Come usare il progetto
1. Copia il progetto nella cartella:
C:\xampp\htdocs\progetti\calcolopassaggio

2. Avvia Apache e MySQL da XAMPP.

3. Crea il database `calcolo_preventivi` e importa la tabella `preventivi` come indicato sopra.

4. Apri il progetto nel browser:
http://localhost/progetti/calcolopassaggio
5. Compila il form per ottenere un preventivo.

6. Visualizza lo storico dei preventivi qui:
http://localhost/progetti/calcolopassaggio/storico.php
---


## 🛠️ Tecnologie utilizzate

- PHP 8  
- HTML5  
- CSS3  
- JavaScript  
- MySQL   
- XAMPP  

---

## 👩‍💻 Autore

Progetto sviluppato da **Elena Polesel**
