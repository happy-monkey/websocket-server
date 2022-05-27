<?php namespace HappyMonkey\WebSocket;

use HappyMonkey\WebSocket\Events\ClientDidJoinEvent;
use HappyMonkey\WebSocket\Events\ClientDidLeaveEvent;
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
    protected SplObjectStorage $clients;

    /**
     * @var Emitter $events
     */
    public Emitter $events;

    public final function initCollection(): void
    {
        $this->clients = new SplObjectStorage();
        $this->events = new Emitter();
    }

    public function getClients(): SplObjectStorage
    {
        return $this->clients;
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
    public function attachClient( Client &$client ): void
    {
        if( !$this->clients->contains($client->getConn()) )
        {
            $this->events->emit(new ClientWillJoinEvent($client, $this));
            $this->clients->attach($client->getConn(), $client);
            $this->events->emit(new ClientDidJoinEvent($client, $this));
        }
    }

    /**
     * @return int
     */
    public function getClientsCount(): int
    {
        return $this->clients->count();
    }

    /**
     * @param Client $client
     */
    public function detachClient( Client &$client ): void
    {
        if( $this->clients->contains($client->getConn()) )
        {
            $this->events->emit(new ClientWillLeaveEvent($client, $this));
            $this->clients->detach($client->getConn());
            $this->events->emit(new ClientDidLeaveEvent($client, $this));
        }
    }

    /**
     * @param string|Message $message
     */
    public function send( $message ): void
    {
        $message = is_string($message) ? $message : json_encode($message, JSON_UNESCAPED_UNICODE);

        foreach( $this->clients as $conn )
        {
            $client = $this->clients[$conn];

            if( $client instanceof Client )
                $client->send($message);
        }
    }

}