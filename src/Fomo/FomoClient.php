<?php
/**
 * Copyright (c) 2016. Fomo. https://www.usefomo.com
 **/

namespace Fomo;

/**
 * Fomo Client is wrapper around official Fomo API
 *
 * @link http://docs.usefomo.com/reference
 * @version 1.0.8
 * @author Fomo <https://www.usefomo.com>
 * @package Fomo
 *
 * Requires PHP version >= 5.3.0
 */

if (version_compare(phpversion(), '5.3.0', '<')) {
    die("FomoClient requires PHP >= 5.3.0");
}

class FomoClient
{
    /**
     * @var string SDK version
     */
    private $sdkVersion = '1.0.8';

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
     * @throws \InvalidArgumentException
     */
    public function getEvent($id)
    {
        if (!$id) {
            throw new \InvalidArgumentException('Missing parameter "id" in '. __METHOD__);
        }

        return $this->cast('\Fomo\FomoEvent', $this->makeRequest('/api/v1/applications/me/events/' . $id, 'GET'));
    }

    /**
     * Get events
     * @param int $size Page size, default = 30
     * @param int $page Page number, default = 1
     * @return FomoEvent[] Fomo event
     */
    public function getEvents($size = 30, $page = 1)
    {
        $objects = $this->makeRequest('/api/v1/applications/me/events?per=' . $size . '&page=' . $page, 'GET');
        $list = array();
        if ($objects != null && is_array($objects)) {
            foreach ($objects as $object) {
                $list[] = $this->cast('\Fomo\FomoEvent', $object);
            }
        }
        return $list;
    }

    /**
     * Get events with meta data
     * @param int $size Page size, default = 30
     * @param int $page Page number, default = 1
     * @return FomoEventsWithMeta Fomo events with meta data
     */
    public function getEventsWithMeta($size = 30, $page = 1)
    {
        $data = $this->makeRequest('/api/v1/applications/me/events?per=' . $size . '&page=' . $page . '&show_meta=true', 'GET');
        $object = $this->cast('\Fomo\FomoEventsWithMeta', $data);
        if (isset($object->events)) {
            for ($i = 0; $i < count($object->events); $i++) {
                $object->events[$i] = $this->cast('\Fomo\FomoEvent', $object->events[$i]);
            }
        }
        if (isset($object->meta)) {
            $object->meta = $this->cast('\Fomo\FomoEventsMeta', $object->meta);
        }
        return $object;
    }

    /**
     * Create event
     * @param FomoEventBasic $event
     * @return FomoEvent Saved Fomo event
     */
    public function createEvent(FomoEventBasic $event)
    {
        return $this->cast('\Fomo\FomoEvent', $this->makeRequest('/api/v1/applications/me/events', 'POST', array('event' => $event)));
    }

    /**
     * Update event
     * @param $event FomoEvent Fomo event
     * @return FomoEvent
     */
    public function updateEvent(FomoEvent $event)
    {
        return $this->cast('\Fomo\FomoEvent', $this->makeRequest('/api/v1/applications/me/events/' . $event->id, 'PATCH', array('event' => $event)));
    }

    /**
     * Delete event
     * @param $id int Event ID
     * @return FomoDeleteMessageResponse Delete message response
     */
    public function deleteEvent($id)
    {
        return $this->cast('\Fomo\FomoDeleteMessageResponse', $this->makeRequest('/api/v1/applications/me/events/' . $id, 'DELETE'));
    }


    /**
     * Make authorized request to Fomo API
     *
     * @param $path string API path
     * @param $method string HTTP Method
     * @param mixed $data Object to send, object is JSON serialized before it is sent
     * @param array $headers List of headers to be added to request
     *
     * @return mixed Data received from API response
     * @throws \RuntimeException
     */
    private function makeRequest($path, $method, $data = null, $headers = array())
    {
        $curl = curl_init($this->endpoint . $path);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        // curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                                                    'Content-Type: application/json',
                                                    'User-Agent: Fomo/PHP/' . $this->sdkVersion,
                                                    'Authorization: Token ' . $this->authToken
                                                ));

        if ($data || in_array($method, array( "POST", "PATCH" ))) {
            // convert from obj to array
            $json  = json_encode($data);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        }

        // Make the REST call, returning the result
        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            echo PHP_EOL . PHP_EOL . "Fomo API CURL error: " . curl_error($curl) . PHP_EOL;
            exit(1);
        }

        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $httpResponse = substr($response, $header_size);

        if (!$httpResponse) {
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            echo PHP_EOL . PHP_EOL . "Fomo API CURL ERROR: HTTP response code ". $httpcode . PHP_EOL;
            echo "No HTTP response message received from the API: {$method} {$this->endpoint}{$path}". PHP_EOL;
            exit(1);
        }

        $response = json_decode($httpResponse);
        // var_dump($http_response_header);
        // var_dump($response);
        return $response;
    }

    /**
     * Make authorized request to Fomo API
     *
     * @param $path string API path
     * @param $method string HTTP Method
     * @param mixed $data Object to send, object is JSON serialized before it is sent
     * @param array $headers List of headers to be added to request
     *
     * @return mixed Data received from API response
     * @throws \RuntimeException
     */
    private function makeRequest2($path, $method, $data = null, $headers = array())
    {
        if ($headers == null) {
            $headers = array();
        }
        if (count($headers) == 0) {
            if ($data != null) {
                $headers[] = 'Content-Type: application/json';
            }
            $headers[] = 'User-Agent: Fomo/PHP/' . $this->sdkVersion;
            $headers[] = 'Authorization: Token ' . $this->authToken;
        }
        $opts = array(
            'http' => array(
                'method' => $method,
                'header' => $headers
            ),
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            )
        );
        if ($data != null) {
            $opts['http']['content'] = json_encode($data);
        }
        if ($this->proxy != null) {
            $opts['http']['proxy'] = $this->proxy;
        }
        $context = stream_context_create($opts);
        $httpResponse = file_get_contents($this->endpoint . $path, false, $context);

        if ($httpResponse === false) {
            throw new \RuntimeException("API Error");
        }

        $response = json_decode($httpResponse);
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
        $sourceReflection = new \ReflectionObject($sourceObject);
        $destinationReflection = new \ReflectionObject($destination);
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
