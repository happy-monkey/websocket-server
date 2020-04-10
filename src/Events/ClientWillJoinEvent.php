<?php namespace HappyMonkey\WebSocket\Events;

use HappyMonkey\WebSocket\ServerEvent;
use HappyMonkey\WebSocket\ServerEventNames;

/**
 * Class ClientWillJoinEvent
 * @package HappyMonkey\WebSocket\Events
 */
class ClientWillJoinEvent extends ServerEvent
{
    public function __construct($client, $emitter = null)
    {
        parent::__construct($client, $emitter);
        $this->name = is_null($this->getRoom()) ? ServerEventNames::ClientWillJoinServer : ServerEventNames::ClientWillJoinRoom;
    }
}