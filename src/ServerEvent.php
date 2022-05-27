<?php namespace HappyMonkey\WebSocket;

use League\Event\AbstractEvent;

/**
 * Class BaseEvent
 * @package HappyMonkey\WebSocket\Events
 */
class ServerEvent extends AbstractEvent
{
    protected string $name = 'default_server_event';

    /**
     * @var Client|null $client
     */
    private ?Client $client = null;

    /**
     * @var Room|null $room
     */
    private ?Room $room = null;

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

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Client|null
     */
    public function getClient(): ?Client
    {
        return $this->client;
    }

    /**
     * @return Room|null
     */
    public function getRoom(): ?Room
    {
        return $this->room;
    }
}