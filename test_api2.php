<?php
$url = "https://api.bloom-s.de:780/api/interfaceversion";

// cURL-Session starten
$ch = curl_init($url);

// cURL-Optionen setzen
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// SSL-Überprüfung ggf. deaktivieren (für Tests, nicht für Produktion empfohlen!):
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

// Anfrage ausführen
$response = curl_exec($ch);

// cURL-Fehlermeldung abfragen
$error = curl_error($ch);

// HTTP-Statuscode abfragen
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Verbindung schließen
curl_close($ch);

// Auswertung der Ergebnisse
if ($error) {
    // cURL selbst hat einen Fehler
    echo "cURL-Fehler: " . $error . "<br>";
} else {
    // Wenn kein cURL-Fehler, dann den HTTP-Statuscode prüfen
    if ($httpCode === 200) {
        echo "Es hat geklappt!<br>";
        echo "Antwort: " . $response;
    } else {
        echo "Fehler beim Aufruf!<br>";
        echo "HTTP-Code: " . $httpCode . "<br>";
        echo "Antwort: " . $response;
    }
}
?>

