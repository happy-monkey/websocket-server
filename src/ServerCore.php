<?php namespace HappyMonkey\WebSocket;

use Exception;
use League\Event\ListenerInterface;
use SplObjectStorage;

trait ServerCore
{
    use ClientCollection {
        detachClient as _detachClient;
    }

    /**
     * @var SplObjectStorage $rooms
     */
    protected SplObjectStorage $rooms;

    /**
     * @var ServerEventListener
     */
    protected ServerEventListener $eventListener;

    public static function create()
    {
        return new static();
    }

    protected final function __construct()
    {
        $this->rooms = new SplObjectStorage();

        $this->initCollection();
        $this->setEventListener($this);
        $this->init();
    }

    /**
     * @param mixed $eventListener
     */
    public function setEventListener( $eventListener ): void
    {
        $this->eventListener = $eventListener instanceof ServerEventListener ? $eventListener : new ServerEventListener();
        $this->events->addListener('*', $this->eventListener);
    }

    /**
     * @inheritDoc
     */
    public function detachClient( Client &$client )
    {
        $this->_detachClient($client);

        foreach( $this->rooms as $room )
        {
            if( $room instanceof Room )
            {
                $room->detachClient($client);
            }
        }
    }

    /**
     * @param Room $room
     */
    public function attachRoom( Room &$room ): void
    {
        // Catch room events
        $room->events->addListener('*', $this->eventListener);

        // Attach room to current server
        $this->rooms->attach($room);
    }

    /**
     * @param string|Room $room
     */
    public function detachRoom( $room ): void
    {
        if( is_string($room) )
        {
            $room = $this->findRoom($room);
        }

        if( $room )
        {
            $this->rooms->detach($room);
        }
    }

    /**
     * @param $uid
     * @return Room|null
     */
    public function findRoom( $uid ): ?Room
    {
        if( !$uid ) return null;

        foreach( $this->rooms as $room )
        {
            if( $room instanceof Room && $room->getUid() == $uid )
                return $room;
        }

        return null;
    }

    /**
     * @param $message
     * @param $type
     */
    public function log( $message, $type = null ): void
    {
        error_log($message);
    }

    abstract public function init();
    abstract public function preventMysqlGoneAway();
    abstract public function canClientJoin(Client &$client, $path, $query );
    abstract public function catchClientError(Client &$client, Exception $exception );
}