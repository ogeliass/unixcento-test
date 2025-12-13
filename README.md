# UNIXCENTO – sito pronto da hostare

## Cosa include
- Sito statico multi‑pagina (HTML/CSS/JS) con menu responsive e dropdown “News”.
- Evidenziazione automatica della pagina attiva nel menu.
- Pagine “News UniPa” e “News ERSU” con feed RSS tramite PHP (con cache).

## Requisiti hosting
### Opzione A (consigliata): hosting con PHP
Funziona su qualunque hosting Apache/Nginx con PHP abilitato.
1. Carica **tutti** i file così come sono (stessa cartella).
2. Apri `index.html`.
3. Le pagine:
   - `news-unipa.html` carica `unipa-news.php` in iframe
   - `news-ersu.html` carica `ersu-news.php` in iframe

> Nota: i file `*_cache.xml` vengono creati automaticamente dai PHP nella stessa cartella.

### Opzione B: hosting solo statico (GitHub Pages / Netlify static)
Il sito funziona, ma **le news RSS non verranno caricate** (perché PHP non è eseguito).
In quel caso, usa i link “Apri fonte ufficiale” mostrati nell’iframe (o sostituisci i feed con una soluzione JS con proxy/CORS).

## Dove cambiare i contenuti
- Link Drive: `drive.html`
- PDF Guide: `guide.html`
- Modulistica: `modulistica.html`
- Contatti/social/orari sportello: `contatti.html`
- Testi home / sezioni: `index.html`, `chi-siamo.html`
