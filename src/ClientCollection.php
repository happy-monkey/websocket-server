<?php namespace HappyMonkey\WebSocket;

use HappyMonkey\WebSocket\Events\ClientDidJoinedEvent;
use HappyMonkey\WebSocket\Events\ClientDidLeftEvent;
use HappyMonkey\WebSocket\Events\ClientWillJoinEvent;
use HappyMonkey\WebSocket\Events\ClientWillLeaveEvent;
use League\Event\Emitter;
use Ratchet\ConnectionInterface;
use SplObjectStorage;

trait ClientCollection
{
    /**
     * @var SplObjectStorage $clients
     */
    protected $clients;

    /**
     * @var Emitter $events
     */
    public $events;

    public final function initCollection()
    {
        $this->clients = new SplObjectStorage();
        $this->events = new Emitter();
    }

    /**
     * @param ConnectionInterface $conn
     * @return Client|object|null
     */
    public function getClientFromConn( ConnectionInterface $conn )
    {
        return $this->clients->contains($conn) ? $this->clients[$conn] : null;
    }

    /**
     * @param Client $client
     */
    public function attachClient( Client &$client )
    {
        if( !$this->clients->contains($client->getConn()) )
        {
            $this->events->emit(new ClientWillJoinEvent($client, $this));
            $this->clients->attach($client->getConn(), $client);
            $this->events->emit(new ClientDidJoinedEvent($client, $this));
        }
    }

    /**
     * @return int
     */
    public function getClientsCount()
    {
        return $this->clients->count();
    }

    /**
     * @param Client $client
     */
    public function detachClient( Client &$client )
    {
        if( $this->clients->contains($client->getConn()) )
        {
            $this->events->emit(new ClientWillLeaveEvent($client, $this));
            $this->clients->detach($client->getConn());
            $this->events->emit(new ClientDidLeftEvent($client, $this));
        }
    }

    /**
     * @param Message|string $message
     */
    public function send( $message )
    {
        foreach( $this->clients as $conn )
        {
            $client = $this->clients[$conn];
            if( $client instanceof Client )
            {
                $client->send($message);
            }
        }
    }

}