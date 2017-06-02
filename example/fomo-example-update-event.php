<?php

// change me
define('FOMO_EVENT_ID', 2997586);

include 'fomo-example-autoload.php';

use Fomo\FomoClient;

$client = new FomoClient($token);
// $client->setProxy('tcp://127.0.0.1:8888');

// get all events
$event = $client->getEvent(FOMO_EVENT_ID);

if (!$event) {
    echo PHP_EOL, "No event with id ". FOMO_EVENT_ID ." found.", PHP_EOL, PHP_EOL;
    exit(1);
}

// we have the event, let's update it
$event->first_name = 'Anne';

$fomoEventUpdated = $client->updateEvent($event);

if ($fomoEventUpdated->first_name != $event->first_name) {
    echo PHP_EOL, PHP_EOL, 'Update function does not work.', PHP_EOL, PHP_EOL;
    exit(1);
}

showEventObject($fomoEventUpdated, "Updated");
