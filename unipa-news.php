<?php
header('Content-Type: text/html; charset=utf-8');

$feedUrl = 'https://www.unipa.it/servizi/segreterie/news/?format=feed&type=rss';
$maxItems = 12;

$cacheFile = __DIR__ . '/unipa_cache.xml';
$cacheTtl  = 15 * 60;

if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTtl)) {
  $xmlString = file_get_contents($cacheFile);
} else {
  $ctx = stream_context_create([
    'http' => ['timeout' => 8, 'user_agent' => 'UNIXCENTO/1.0 (+https://unixcento.it)']
  ]);
  $xmlString = @file_get_contents($feedUrl, false, $ctx);
  if ($xmlString) file_put_contents($cacheFile, $xmlString);
}

function render_error($msg) {
  echo '<!doctype html><html lang="it"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
  echo '<title>News UniPa (Segreterie Studenti)</title>';
  echo '<style>body{font-family:system-ui,-apple-system,Segoe UI,sans-serif;margin:0;padding:14px;background:#f3f4f6;color:#111827}.card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:14px}a{color:#2563eb}</style>';
  echo '</head><body><div class="card"><p>' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</p>';
  echo '<p><a href="https://www.unipa.it" target="_blank" rel="noopener">Apri fonte ufficiale</a></p></div></body></html>';
  exit;
}

if (!$xmlString) render_error('Feed non disponibile al momento.');

$xml = @simplexml_load_string($xmlString);
if (!$xml || !isset($xml->channel->item)) render_error('Feed non valido.');

echo '<!doctype html><html lang="it"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
echo '<title>News UniPa (Segreterie Studenti)</title>';
echo '<style>
  body{font-family:system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;margin:0;padding:0;background:#f3f4f6;color:#111827}
  .wrap{padding:14px}
  .card{background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:14px;box-shadow:0 10px 25px rgba(15,23,42,.05)}
  h3{margin:0 0 10px;font-size:1rem}
  ul{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:10px}
  li{padding:10px;border:1px solid #e5e7eb;border-radius:14px}
  a{color:#2563eb;text-decoration:none}
  a:hover{text-decoration:underline}
  small{color:#6b7280}
  .meta{margin-top:10px;font-size:12px;color:#6b7280}
</style>';
echo '</head><body><div class="wrap"><div class="card">';
echo '<h3>News UniPa (Segreterie Studenti)</h3><ul>';

$count = 0;
foreach ($xml->channel->item as $item) {
  if ($count++ >= $maxItems) break;

  $t = htmlspecialchars((string)$item->title, ENT_QUOTES, 'UTF-8');
  $l = htmlspecialchars((string)$item->link,  ENT_QUOTES, 'UTF-8');
  $rawDate = (string)$item->pubDate;
  $ts = $rawDate ? strtotime($rawDate) : false;
  $d = $ts ? date('d/m/Y', $ts) : '';

  echo "<li><a href=\"$l\" target=\"_blank\" rel=\"noopener\">$t</a>";
  if ($d) echo " <small>— $d</small>";
  echo "</li>";
}

echo '</ul>';
echo '<div class="meta">Fonte: <a href="https://www.unipa.it" target="_blank" rel="noopener">Università di Palermo</a></div>';
echo '</div></div></body></html>';
