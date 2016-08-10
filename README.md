Fomo PHP SDK
================

*Fomo PHP SDK* is the official SDK wrapper for the [Fomo API service](https://www.usefomo.com)

API docs: [http://docs.usefomo.com/reference](http://docs.usefomo.com/reference)

Requirements
------------

- PHP Version 5.3.0+

User Installation
-----------------

Download [src/fomo.php](src/fomo.php) and include the file in your PHP project.

Check out our examples in [example/fomo-example.php](example/fomo-example.php), quick usage examples:

Initialize Fomo client via:

    $client = new FomoClient($authToken); // auth token can be found Fomo application admin dashboard (App -> API Access)

To create a new event:

    $event = new FomoEventBasic();
    $event->event_type_id = "4"; // Event type ID is found on Fomo dashboard (Templates -> Template ID)
    $event->title = "Test event";
    $event->first_name = "Dean";
    $event->city = "San Francisco";
    $event->url = "https://www.usefomo.com";
    // for additional parameters check code documentation
    $fomoEvent = $client->createEvent($event);

To get an event:

    $fomoEvent = $client->getEvent("<event ID>");

To get all events:

    $fomoEvents = $client->getEvents();

To delete an event:

    $client->deleteEvent("<event ID>");

To update an event:

    $fomoEvent = $client->getEvent("<event ID>");
    $fomoEvent->first_name = "John";
    $fomoEvent = $client->updateEvent($fomoEvent);

If you have questions, email us at [hello@usefomo.com](mailto:hello@usefomo.com).
