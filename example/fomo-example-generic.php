<?php

include 'fomo-example-autoload.php';

use Fomo\FomoClient;

$client = new FomoClient($token);
// $client->setProxy('tcp://127.0.0.1:8888');
$eventsWithMeta = $client->getEventsWithMeta(100, 1);
echo 'Current page: ', $eventsWithMeta->meta->page, PHP_EOL;
echo 'Total pages: ', $eventsWithMeta->meta->total_pages, PHP_EOL;
echo 'Page size: ', $eventsWithMeta->meta->per_page, PHP_EOL;
echo 'Total count: ', $eventsWithMeta->meta->total_count, PHP_EOL;
echo 'Total count <array>: ', count($eventsWithMeta->events), PHP_EOL;
