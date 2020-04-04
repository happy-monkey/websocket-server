<?php namespace HappyMonkey\WebSocket\Events;

use HappyMonkey\WebSocket\ServerEvent;

/**
 * Class ClientDidLeftEvent
 * @package HappyMonkey\WebSocket\Events
 */
class ClientDidLeftEvent extends ServerEvent
{
    const NAME = 'client_did_left';

    protected $name = self::NAME;
}