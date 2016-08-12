Fomo PHP SDK
================

*Fomo PHP SDK* is the official SDK wrapper for the [Fomo API service](https://www.usefomo.com)

API docs: [http://docs.usefomo.com/reference](http://docs.usefomo.com/reference)

## Requirements

- PHP Version 5.3.0+

## Installation

Install the latest version with

```bash
$ composer require usefomo/fomo-php-sdk
```

## Manual User Installation

Download [src/Fomo/FomoClient.php](src/Fomo/FomoClient.php) and include the file in your PHP project.

Check out our examples in [example/fomo-example.php](example/fomo-example.php), quick usage examples:

## Basic Usage

Initialize Fomo client via:

```php
<?php
use Fomo\FomoClient;
$client = new FomoClient($authToken); // auth token can be found Fomo application admin dashboard (App -> API Access)
```

To create a new event:

```php
use Fomo\FomoEventBasic;
$event = new FomoEventBasic();
$event->event_type_id = "4"; // Event type ID is found on Fomo dashboard (Templates -> Template ID)
$event->title = "Test event";
$event->first_name = "Dean";
$event->city = "San Francisco";
$event->url = "https://www.usefomo.com";
// for additional parameters check code documentation
$fomoEvent = $client->createEvent($event);
```

To get an event:

```php
$fomoEvent = $client->getEvent("<event ID>");
```

To get all events:

```php
$fomoEvents = $client->getEvents();
```

To delete an event:

```php
$client->deleteEvent("<event ID>");
```

To update an event:

```php
$fomoEvent = $client->getEvent("<event ID>");
$fomoEvent->first_name = "John";
$fomoEvent = $client->updateEvent($fomoEvent);
```

## Support

If you have questions, email us at [hello@usefomo.com](mailto:hello@usefomo.com).