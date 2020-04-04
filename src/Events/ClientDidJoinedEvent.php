<?php namespace HappyMonkey\WebSocket\Events;

use HappyMonkey\WebSocket\ServerEvent;

/**
 * Class UserJoinedEvent
 * @package HappyMonkey\WebSocket\Events
 */
class ClientDidJoinedEvent extends ServerEvent
{
    const NAME = 'client_did_joined';

    protected $name = self::NAME;
}