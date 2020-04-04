<?php namespace HappyMonkey\WebSocket\Events;

use HappyMonkey\WebSocket\ServerEvent;

/**
 * Class UserWillLeaveEvent
 * @package HappyMonkey\WebSocket\Events
 */
class ClientWillLeaveEvent extends ServerEvent
{
    const NAME = 'client_will_leave';

    protected $name = self::NAME;
}