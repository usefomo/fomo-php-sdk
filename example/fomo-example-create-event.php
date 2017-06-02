<?php

include 'fomo-example-autoload.php';

use Fomo\FomoClient;
use Fomo\FomoEventBasic;

$client = new FomoClient($token);
// $client->setProxy('tcp://127.0.0.1:8888');

// create event
$event = new FomoEventBasic();
$event->event_type_id = '10099'; // Event type id is found on Fomo dashboard (Templates -> Template id)
$event->event_type_tag = 'user_signup'; // Event type tag is found on Fomo dashboard (Templates -> Template name)
$event->title = 'Test event';
$event->first_name = 'Dean';
$event->email_address = 'dean@somewhere.com';
$event->city = 'San Francisco';
$event->url = 'https://www.usefomo.com';
$event->addCustomEventField('variable_name', 'value');
$fomoEvent = $client->createEvent($event);

if ($fomoEvent->error) {
    showEventObject($fomoEvent, null);
    exit(1);
}

// newly created event
showEventObject($fomoEvent, "Created");


// retrieve latest added event by id
$fomoEvent_tmp = $client->getEvent($fomoEvent->id);

if (!$fomoEvent_tmp) {
    echo PHP_EOL, PHP_EOL, "Event creation failed, could not retrieve newly created event by its id.", PHP_EOL, PHP_EOL;
}

showEventObject($fomoEvent_tmp, "Retrieved");
