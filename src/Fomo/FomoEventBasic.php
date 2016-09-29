<?php
namespace Fomo;

include_once 'FomoEventCustomAttribute.php';

/**
 * Class FomoEventBasic
 * @package Fomo
 */
class FomoEventBasic
{
    /**
     * @var string Event type unique ID (optional|required if event_type_tag = '')
     */
    public $event_type_id = '';

    /**
     * @var string Event type tag (optional|required if event_type_id = '')
     */
    public $event_type_tag = '';

    /**
     * @var string Url to redirect on the event click. Size range: 0..255 (required)
     */
    public $url = '';

    /**
     * @var string First name of the person on the event. Size range: 0..255
     */
    public $first_name = '';

    /**
     * @var string City where the event happened. Size range: 0..255
     */
    public $city = '';

    /**
     * @var string Province where the event happened. Size range: 0..255
     */
    public $province = '';

    /**
     * @var string Country where the event happened ISO-2 standard. Size range: 0..255
     */
    public $country = '';

    /**
     * @var string Title of the event. Size range: 0..255
     */
    public $title = '';

    /**
     * @var string Url of the image to be displayed. Size range: 0..255
     */
    public $image_url = '';

    /**
     * @var FomoEventCustomAttribute[] Array to create custom event fields
     */
    public $custom_event_fields_attributes = array();

    /**
     * Add custom attribute value
     * @param $key string Custom attribute key
     * @param $value string Custom attribute value
     */
    public function addCustomEventField($key, $value)
    {
        $this->custom_event_fields_attributes[] = new FomoEventCustomAttribute($key, $value);
    }
}