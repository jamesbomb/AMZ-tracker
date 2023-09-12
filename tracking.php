<?php

$trackingNumbers = [
    'IT2299847153',
    'IT2299009233',
    'IT2299018942',
];

foreach ($trackingNumbers as $trackingNumber) {
    $url = "https://track.amazon.it/api/tracker/$trackingNumber";
    $jsonResponse = file_get_contents($url);

    if ($jsonResponse === FALSE) {
        echo "Errore nel recupero dei dati per il tracking number: $trackingNumber\n";
        continue;
    }

    $responseData = json_decode($jsonResponse, true);
    $progressTracker = json_decode($responseData['progressTracker'], true);
    $eventHistory = json_decode($responseData['eventHistory'], true);

    $eventsArray = [];

    foreach ($eventHistory['eventHistory'] as $event) {
        $date = $event['eventTime'];
        $status = $event['statusSummary']['localisedStringId'];
        $status = str_replace('swa_rex_', '', $status);
        $status = str_replace('_', ' ', $status);

        $location = $event['location'];
        $city = isset($location['city']) ? $location['city'] : "N/A";
        $stateProvince = isset($location['stateProvince']) ? $location['stateProvince'] : "N/A";
        $countryCode = isset($location['countryCode']) ? $location['countryCode'] : "N/A";
        $postalCode = isset($location['postalCode']) ? $location['postalCode'] : "N/A";

        $locationString = "Città: $city, Provincia: $stateProvince, Codice Paese: $countryCode, Codice Postale: $postalCode";
        
        $eventArray = [
            'Data' => $date,
            'Stato' => $status,
            'Luogo' => $locationString,
        ];
        
        $eventsArray[] = $eventArray;
        
        echo "Data: $date - Stato: $status - Luogo: $locationString\n";
    }
    
    $jsonOutput = json_encode($eventsArray, JSON_PRETTY_PRINT);
    file_put_contents($trackingNumber . '_data.json', $jsonOutput);
}

// TLDR:
// -Nello script itero su un array di numeri di tracking.
// -Per ogni numero di tracking, faccio una richiesta GET all'URL dell'API e otteniamo la risposta JSON.
// -Decodifico la risposta JSON per ottenere un array PHP.
// -Decodifico ulteriormente le stringhe JSON annidate presenti nelle proprietà progressTracker e eventHistory.
// -Estraggo e stampo alcune informazioni dalle strutture dati ottenute.
// -Pulisco la stringa e ne genero uno leggibile
// -Salvo i dati estratti in file JSON separati per ogni numero di tracking.

?>





