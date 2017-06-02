<?php

include 'fomo-example-autoload.php';

use Fomo\FomoClient;

$client = new FomoClient($token);
// $client->setProxy('tcp://127.0.0.1:8888');

// get all events
$events = $client->getEvents();

if (!$events) {
    echo PHP_EOL, "No events found.", PHP_EOL, PHP_EOL;
    exit(1);
}

foreach ($events as $fomoEvent) {
    showEventObject($fomoEvent, "Retrieved");
}

echo PHP_EOL, 'Events count: ', count($events), PHP_EOL, PHP_EOL;
