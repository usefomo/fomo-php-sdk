Fomo-PHP-SDK
================

*Fomo-PHP-SDK* is official SDK wrapper around [Fomo API service](https://www.usefomo.com)

API docs: [http://docs.usefomo.com/reference](http://docs.usefomo.com/reference)

Requirements
------------

- PHP Version 5.3.0+

User Installation
-----------------

Download src/fomo.php and include file to your own PHP project.

Check out our examples in example/fomo-example.php, quick usage examples:

Initialize Fomo client via:

    $client = new FomoClient($authToken); // auth token can be found Fomo application admin dashboard (App -> API Access)

To create new event:

    $event = new FomoEventBasic();
    $event->event_type_id = "4"; // Event type ID is found on Fomo dashboard (Templates -> Template ID)
    $event->title = "Test event";
    $event->first_name = "Dean";
    $event->city = "San Francisco";
    $event->url = "https://www.usefomo.com";
    // for additional parameters check code documentation
    $fomoEvent = $client->createEvent($event);

To get event:

    $fomoEvent = $client->getEvent("<event ID>");

To get all events:

    $fomoEvent = $client->getEvents();

To delete event:

    $client->deleteEvent($fomoEvent1->id);

To update event:

    $fomoEvent = $client->getEvent("<event ID>");
    $fomoEvent->first_name = "John";
    $fomoEvent = $client->updateEvent($fomoEvent);

If you have any questions we are available via e-mail support: [hello@usefomo.com](hello@usefomo.com)
