<?php namespace HappyMonkey\WebSocket;

use HappyMonkey\WebSocket\Exceptions\MalformedJsonException;
use JsonSerializable;

class Message implements JsonSerializable
{
    /**
     * @var string $action
     */
    protected mixed $action;

    /**
     * @var mixed|null $data
     */
    protected mixed $data;

    /**
     * @var string|null $room
     */
    protected ?string $room;

    /**
     * @var string $callback
     */
    protected mixed $callback;

    /**
     * @param $json
     * @return Message
     * @throws MalformedJsonException
     */
    public static function fromJSON( $json )
    {
        $json = json_decode($json, false, 512, JSON_INVALID_UTF8_SUBSTITUTE);
        if( $json && json_last_error() === JSON_ERROR_NONE )
        {
            if( property_exists($json, 'action') )
            {
                $message = new Message($json->action, property_exists($json, 'data') ? $json->data : null);
                if( property_exists($json, 'room') )
                    $message->setRoom($json->room);
                if( property_exists($json, 'callback') )
                    $message->setCallback($json->callback);
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

    public function __construct( $action='', $data=null, $callback=null )
    {
        $this->action = $action;
        $this->data = $data;
        $this->room = null;
        $this->callback = $callback;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * @return string|null
     */
    public function getRoom(): ?string
    {
        return $this->room;
    }

    /**
     * @param null $type
     * @return string|null
     */
    public function getCallback( $type=null ): ?string
    {
        return $this->callback . ($type ?: '');
    }

    /**
     * @param string $action
     */
    public function setAction( string $action )
    {
        $this->action = $action;
    }

    /**
     * @param mixed $data
     */
    public function setData( mixed $data )
    {
        $this->data = $data;
    }

    /**
     * @param string|null $room
     */
    public function setRoom( ?string $room )
    {
        $this->room = $room;
    }

    /**
     * @param string|null $callback
     */
    public function setCallback( ?string $callback )
    {
        $this->callback = $callback;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        $data = [
            'action' => $this->action,
            'data' => $this->data
        ];

        if( !is_null($this->getRoom()) )
            $data['room'] = $this->room;
        if( !is_null($this->getCallback()) )
            $data['callback'] = $this->callback;

        return $data;
    }
}