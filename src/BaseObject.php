<?php namespace HappyMonkey\WebSocket;

use JsonSerializable;
use stdClass;

class BaseObject implements JsonSerializable
{
    /**
     * @var string
     */
    protected string $uid_prefix = '';

    /**
     * @var mixed $uid
     */
    protected $uid;

    /**
     * @var mixed $data
     */
    protected $data;

    public function __construct($uid=null)
    {
        $this->uid = $uid? : uniqid($this->uid_prefix);
        $this->data = new stdClass();
    }

    public function __set($name, $value)
    {
        $this->data->$name = $value;
    }

    public function __get($name)
    {
        return property_exists($this->data, $name) ? $this->data->$name : null;
    }

    /**
     * @param mixed $uid
     */
    public function setUid( $uid )
    {
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'uid' => $this->uid,
            'data' => $this->data
        ];
    }
}