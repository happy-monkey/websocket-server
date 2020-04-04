<?php namespace HappyMonkey\WebSocket;

use HappyMonkey\WebSocket\Exceptions\MalformedJsonException;
use JsonSerializable;

class Message implements JsonSerializable
{
    /**
     * @var string $action
     */
    protected $action;

    /**
     * @var mixed|null $data
     */
    protected $data;

    /**
     * @param $json
     * @return Message
     * @throws MalformedJsonException
     */
    public static function fromJSON( $json )
    {
        $json = json_decode($json);
        if( $json && json_last_error() === JSON_ERROR_NONE )
        {
            if( property_exists($json, 'action') )
            {
                return new Message($json->action, property_exists($json, 'data') ? $json->data : null);
            }
            else
            {
                throw new MalformedJsonException();
            }
        }
        else
        {
            throw new MalformedJsonException();
        }
    }

    public function __construct( $action='', $data=null )
    {
        $this->action = $action;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return mixed|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @param mixed|null $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'action' => $this->action,
            'data' => $this->data
        ];
    }
}