<?php
use Fomo\FomoClient;
use Fomo\FomoEventBasic;

include_once '../src/Fomo/FomoClient.php';
include_once '../src/Fomo/FomoEventBasic.php';
$token = '<token>';
$client = new FomoClient($token);
// $client->setProxy('tcp://127.0.0.1:8888');

// create event
$event = new FomoEventBasic();
$event->event_type_tag = 'new-order'; // Event type tag is found on Fomo dashboard (Templates -> Template name)
$event->title = 'Test event';
$event->first_name = 'Dean';
$event->city = 'San Francisco';
$event->url = 'https://www.usefomo.com';
$event->addCustomEventField('variable_name', 'value');
$fomoEvent1 = $client->createEvent($event);
print_r($fomoEvent1);
$fomoEvent2 = $client->createEvent($event);
print_r($fomoEvent2);

// create event with event type id
$event->event_type_tag = '';
$event->event_type_id = '183'; // Event type ID is found on Fomo dashboard (Templates -> Template ID)
$fomoEvent3 = $client->createEvent($event);
print_r($fomoEvent3);

// get event
$fomoEventGet = $client->getEvent($fomoEvent1->id);
print_r($fomoEventGet);

// get events
$events = $client->getEvents();
echo 'Events count: ', count($events), '\n';
print_r($events);

// update event
$fomoEventGet->first_name = 'John';
$fomoEventUpdated = $client->updateEvent($fomoEventGet);
print_r($fomoEventUpdated);
if ($fomoEventUpdated->first_name != $fomoEventGet->first_name) {
    die('Update function does not work.');
}

// delete event
print_r($client->deleteEvent($fomoEvent1->id));
print_r($client->deleteEvent($fomoEvent2->id));
print_r($client->deleteEvent($fomoEvent3->id));