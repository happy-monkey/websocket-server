<?php namespace HappyMonkey\WebSocket\Events;

use HappyMonkey\WebSocket\ServerEvent;

/**
 * Class UserWillJoinEvent
 * @package HappyMonkey\WebSocket\Events
 */
class ClientWillJoinEvent extends ServerEvent
{
    const NAME = 'client_will_join';

    protected $name = self::NAME;
}