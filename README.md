Fomo PHP SDK
================

*Fomo PHP SDK* is the official SDK wrapper for the [Fomo API service](https://fomo.com)

API docs: [https://docs.fomo.com](https://docs.fomo.com)

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

Initialize Fomo client:

```php
<?php
use Fomo\FomoClient;
$client = new FomoClient($authToken); // auth token can be found Fomo application admin dashboard (App -> API Access)
```

Create a new event:

with a template name...

```php
use Fomo\FomoEventBasic;
$event = new FomoEventBasic();
$event->event_type_tag = "new_order"; // Event type tag is found on Fomo dashboard (Templates -> Template name)
$event->title = "Test event";
$event->first_name = "Ryan";
$event->email_address = "ryan.kulp@fomo.com"; // used to fetch Gravatar for notification image
$event->ip_address = "128.177.108.102"; // used for extracting location parameters (city, province, country)
$event->city = "New York City";
$event->url = "https://www.fomo.com";
// for additional parameters check code documentation

// Add event custom attributes
$event->addCustomEventField('variable_name', 'value');

$fomoEvent = $client->createEvent($event);
```

or with a template ID:

```php
use Fomo\FomoEventBasic;
$event = new FomoEventBasic();
$event->event_type_id = "4"; // Event type ID is found on Fomo dashboard (Templates -> Template ID)
$event->title = "Test event";
$event->first_name = "Ryan";
$event->email_address = "ryan.kulp@fomo.com";
$event->ip_address = "128.177.108.102";
$event->url = "https://www.fomo.com";
// for additional parameters check code documentation

// Add event custom attributes
$event->addCustomEventField('variable_name', 'value');

$fomoEvent = $client->createEvent($event);
```

Fetch an event:

```php
$fomoEvent = $client->getEvent("<event ID>");
```

Get events:

```php
$fomoEvents = $client->getEvents(30 /* page size */, 1 /* page */);
```

Get events with meta data:

```php
$fomoEventsWithMeta = $client->getEventsWithMeta(30 /* page size */, 1 /* page */);

/* Events */
print_r($fomoEventsWithMeta->events);

/* Meta data */
echo 'Current page: ', $fomoEventsWithMeta->meta->page, "\n";
echo 'Total pages: ', $fomoEventsWithMeta->meta->total_pages, "\n";
echo 'Page size: ', $fomoEventsWithMeta->meta->per_page, "\n";
echo 'Total count: ', $fomoEventsWithMeta->meta->total_count, "\n";
```

Delete an event:

```php
$client->deleteEvent("<event ID>");
```

Update an event:

```php
$fomoEvent = $client->getEvent("<event ID>");
$fomoEvent->first_name = "John";
$fomoEvent = $client->updateEvent($fomoEvent);
```

## Support

If you have questions, email us at [hello@fomo.com](mailto:hello@fomo.com).
