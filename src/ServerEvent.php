<?php namespace HappyMonkey\WebSocket;

use League\Event\AbstractEvent;

/**
 * Class BaseEvent
 * @package HappyMonkey\WebSocket\Events
 */
class ServerEvent extends AbstractEvent
{
    protected $name = 'default_server_event';

    /**
     * @var Client|null $client
     */
    private $client = null;

    /**
     * @var Room|null $room
     */
    private $room = null;

    /**
     * BaseEvent constructor.
     * @param $client
     * @param null $emitter
     */
    public function __construct( $client, $emitter=null )
    {
        $this->client = $client;
        $this->room = $emitter instanceof Room ? $emitter : null;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Client|null
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return Room|null
     */
    public function getRoom()
    {
        return $this->room;
    }
}