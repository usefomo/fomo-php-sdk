<?php

include 'fomo-example-autoload.php';

use Fomo\FomoClient;

// change me
define('FOMO_EVENT_ID', 2997586);


$client = new FomoClient($token);
// $client->setProxy('tcp://127.0.0.1:8888');

$response = $client->deleteEvent(FOMO_EVENT_ID);
showEventObject($response, null);
