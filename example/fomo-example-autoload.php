<?php

$loader = include 'vendor/autoload.php';
$loader->add('Fomo\Fomo', __DIR__.'/src');

// activate the autoloader
$loader->register();

$token = getenv('FOMO_TOKEN');

if (!$token) {
    echo PHP_EOL . PHP_EOL . "Please setup the env variable FOMO_TOKEN=<YOUR_FOMO_TOKEN_HERE>". PHP_EOL . PHP_EOL;
    exit(1);
}

function showEventObject($fomoEvent, $method)
{
    echo PHP_EOL,
        ($method ? "{$method} Event Object Type: " : "")
        , get_class($fomoEvent), PHP_EOL;
    echo "Methods:", PHP_EOL;
    
    foreach (get_object_vars($fomoEvent) as $name=>$var) {
        echo " - {$name}={$var}", PHP_EOL;
    }
    
    echo PHP_EOL;
}
