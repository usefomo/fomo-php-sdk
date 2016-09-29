<?php
namespace Fomo;

include_once 'FomoEvent.php';
include_once 'FomoEventsMeta.php';

/**
 * Class FomoEventsWithMeta
 * @package Fomo
 */
class FomoEventsWithMeta
{
    /**
     * @var FomoEvent[] Array of events
     */
    public $events = array();

    /**
     * @var FomoEventsMeta Meta data
     */
    public $meta;
}

