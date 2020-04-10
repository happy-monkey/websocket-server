<?php namespace HappyMonkey\WebSocket\Events;

use HappyMonkey\WebSocket\ServerEvent;
use HappyMonkey\WebSocket\ServerEventNames;

/**
 * Class ClientDidJoinEvent
 * @package HappyMonkey\WebSocket\Events
 */
class ClientDidJoinEvent extends ServerEvent
{
    public function __construct($client, $emitter = null)
    {
        parent::__construct($client, $emitter);
        $this->name = is_null($this->getRoom()) ? ServerEventNames::ClientDidJoinServer : ServerEventNames::ClientDidJoinRoom;
    }
}