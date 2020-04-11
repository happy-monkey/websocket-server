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
     * @var string|null $room
     */
    protected $room;

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
                $message = new Message($json->action, property_exists($json, 'data') ? $json->data : null);
                if( property_exists($json, 'room') )
                {
                    $message->setRoom($json->room);
                }
                return $message;
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
        $this->room = null;
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
     * @return string|null
     */
    public function getRoom()
    {
        return $this->room;
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
     * @param string|null $room
     */
    public function setRoom($room)
    {
        $this->room = $room;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $data = [
            'action' => $this->action,
            'data' => $this->data
        ];

        if( is_null($this->getRoom()) )
        {
            $data['room'] = $this->room;
        }

        return $data;
    }
}