<?php namespace HappyMonkey\WebSocket\Events;

use HappyMonkey\WebSocket\ServerEvent;
use HappyMonkey\WebSocket\ServerEventNames;

/**
 * Class ClientDidLeaveEvent
 * @package HappyMonkey\WebSocket\Events
 */
class ClientDidLeaveEvent extends ServerEvent
{
    public function __construct($client, $emitter = null)
    {
        parent::__construct($client, $emitter);
        $this->name = is_null($this->getRoom()) ? ServerEventNames::ClientDidLeaveServer : ServerEventNames::ClientDidLeaveRoom;
    }
}