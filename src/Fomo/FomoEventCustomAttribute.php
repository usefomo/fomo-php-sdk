<?php
namespace Fomo;

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