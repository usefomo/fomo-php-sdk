<?php
/**
 * Copyright (c) 2016. Fomo. https://www.usefomo.com
 **/

namespace Fomo;

/**
 * Fomo Client is wrapper around official Fomo API
 *
 * @link http://docs.usefomo.com/reference
 * @version 1.0
 * @author Fomo <https://www.usefomo.com>
 *
 * Requires PHP version >= 5.3.0
 */

if (version_compare(phpversion(), '5.3.0', '<')) {
    die("FomoClient requires PHP >= 5.3.0");
}

class FomoClient
{
    /**
     * @var string Fomo Auth token
     */
    public $authToken;

    /**
     * @var string|null Proxy URL, example: tcp://127.0.0.1:8888
     */
    public $proxy = null;

    /**
     * @var string Fomo server endpoint
     */
    private $endpoint = 'https://www.usefomo.com';

    /**
     * FomoClient constructor to setup auth token
     * @param $authToken string Auth token
     */
    public function __construct($authToken)
    {
        $this->authToken = $authToken;
    }

    /**
     * Set proxy to be used for making requests
     * @param $proxy string Proxy URL, example: tcp://127.0.0.1:8888
     */
    public function setProxy($proxy)
    {
        $this->proxy = $proxy;
    }

    /**
     * Get event
     * @param $id int Event ID
     * @return FomoEvent Fomo event
     */
    public function getEvent($id)
    {
        return $this->cast('FomoEvent', $this->makeRequest('/api/v1/applications/me/events/' . $id, 'GET'));
    }

    /**
     * Get events
     * @return FomoEvent[] Fomo event
     */
    public function getEvents()
    {
        $objects = $this->makeRequest('/api/v1/applications/me/events', 'GET');
        $list = array();
        if ($objects != null && is_array($objects)) {
            foreach ($objects as $object) {
                $list[] = $this->cast('FomoEvent', $object);
            }
        }
        return $list;
    }

    /**
     * Create event
     * @param FomoEventBasic $event
     * @return FomoEvent Saved Fomo event
     */
    public function createEvent(FomoEventBasic $event)
    {
        return $this->cast('FomoEvent', $this->makeRequest('/api/v1/applications/me/events', 'POST', $event));
    }

    /**
     * Update event
     * @param $event FomoEvent Fomo event
     * @return FomoEvent
     */
    public function updateEvent(FomoEvent $event)
    {
        return $this->cast('FomoEvent', $this->makeRequest('/api/v1/applications/me/events/' . $event->id, 'PATCH', $event));
    }

    /**
     * Delete event
     * @param $id int Event ID
     * @return FomoDeleteMessageResponse Delete message response
     */
    public function deleteEvent($id)
    {
        return $this->cast('FomoDeleteMessageResponse', $this->makeRequest('/api/v1/applications/me/events/' . $id, 'DELETE'));
    }

    /**
     * Make authorized request to Fomo API
     * @param $path string API path
     * @param $method string HTTP Method
     * @param mixed $data Object to send, object is JSON serialized before it is sent
     * @param array $headers List of headers to be added to request
     * @return mixed Data received from API response
     */
    private function makeRequest($path, $method, $data = null, $headers = array())
    {
        if ($headers == null) {
            $headers = array();
        }
        if (count($headers) == 0) {
            if ($data != null) {
                $headers[] = 'Content-Type: application/json';
            }
            $headers[] = 'Authorization: Token ' . $this->authToken;
        }
        $opts = array(
            'http' => array(
                'method' => $method,
                'header' => $headers
            )
        );
        if ($data != null) {
            $opts['http']['content'] = json_encode($data);
        }
        if ($this->proxy != null) {
            $opts['http']['proxy'] = $this->proxy;
        }
        $context = stream_context_create($opts);
        $response = json_decode(file_get_contents($this->endpoint . $path, false, $context));
        // var_dump($http_response_header);
        // var_dump($response);
        return $response;
    }

    /**
     * Class casting
     *
     * @param string|object $destination
     * @param object $sourceObject
     * @return object
     */
    private function cast($destination, $sourceObject)
    {
        if (is_string($destination)) {
            $destination = new $destination();
        }
        $sourceReflection = new ReflectionObject($sourceObject);
        $destinationReflection = new ReflectionObject($destination);
        $sourceProperties = $sourceReflection->getProperties();
        foreach ($sourceProperties as $sourceProperty) {
            $sourceProperty->setAccessible(true);
            $name = $sourceProperty->getName();
            $value = $sourceProperty->getValue($sourceObject);
            if ($destinationReflection->hasProperty($name)) {
                $propDest = $destinationReflection->getProperty($name);
                $propDest->setAccessible(true);
                $propDest->setValue($destination, $value);
            } else {
                $destination->$name = $value;
            }
        }
        return $destination;
    }
}

/**
 * Class FomoEventBasic
 */
class FomoEventBasic
{
    /**
     * @var string Event type unique ID (required)
     */
    public $event_type_id = '';

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

/**
 * Class FomoEvent
 */
class FomoEvent extends FomoEventBasic
{
    /**
     * @var string Id of the event type (needed only for the update)
     */
    public $id;

    /**
     * @var string Created timestamp (received info)
     */
    public $created_at;

    /**
     * @var string Updated timestamp (received info)
     */
    public $updated_at;

    /**
     * @var string Message template (received info)
     */
    public $message;

    /**
     * @var string Full link (received info)
     */
    public $link;
}

/**
 * Class FomoEventCustomAttribute
 */
class FomoEventCustomAttribute
{
    /**
     * @var int Attribute ID (needed only for the update)
     */
    public $id;

    /**
     * @var string Custom attribute key
     */
    public $key = '';

    /**
     * @var string Custom attribute value
     */
    public $value = '';

    /**
     * FomoEventCustomAttribute constructor.
     * @param $key string Custom attribute key
     * @param $value string Custom attribute value
     */
    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }
}

/**
 * Class FomoDeleteMessageResponse
 */
class FomoDeleteMessageResponse
{
    /**
     * @var string Message
     */
    public $message;
}
