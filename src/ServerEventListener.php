<?php namespace HappyMonkey\WebSocket;

use League\Event\AbstractListener;
use League\Event\EventInterface;

class ServerEventListener extends AbstractListener
{
    /**
     * @param ServerEvent $event
     * @param Client $client
     * @param Room|null $room
     */
    public function handleServerEvent(ServerEvent $event, $client, $room )
    {
    }

    public final function handle(EventInterface $event)
    {
        if( $event instanceof ServerEvent )
        {
            $this->handleServerEvent($event, $event->getClient(), $event->getRoom());
        }
    }
}