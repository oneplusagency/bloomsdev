<?php

require_once __DIR__ . '/lib/base.php';
$f3 = Base::instance();
$f3->set('DEBUG', 3);

$url = 'https://api.bloom-s.de:780/api/ping';
$web = \Web::instance();
$response = $web->request($url);

// Zusätzliche Ausgabe des Fehlertexts
echo "===== Blooms WebAPI Test via Fat-Free Framework =====\n\n";
echo "Aufgerufene URL: $url\n\n";

echo "HTTP-Status und Header:\n";
print_r($response['headers']);

echo "\n\nBody:\n";
echo $response['body'];

echo "\n\nFehler (falls vorhanden):\n";
if (!empty($response['error'])) {
    echo $response['error'];
} else {
    echo "(kein Fehlertext zurückgegeben)\n";
}

echo "\n============================================\n";
