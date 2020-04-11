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
    protected $rooms;

    /**
     * @var ServerEventListener
     */
    protected $eventListener;

    public static function create()
    {
        return new self();
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
    public function setEventListener($eventListener)
    {
        $this->eventListener = $eventListener instanceof ServerEventListener ? $eventListener : new ServerEventListener();
        $this->events->addListener('*', $this->eventListener);
    }

    /**
     * @inheritDoc
     */
    public function detachClient(Client &$client)
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
    public function addRoom( Room &$room )
    {
        // Catch room events
        $room->events->addListener('*', $this->eventListener);

        // Attach room to current server
        $this->rooms->attach($room);
    }

    /**
     * @param $uid
     * @return Room|null
     */
    public function findRoom( $uid )
    {
        if( !$uid ) return null;

        foreach( $this->rooms as $room )
        {
            if( $room instanceof Room && $room->getUid() == $uid )
            {
                return $room;
            }
        }

        return null;
    }

    /**
     * @param $message
     * @param null $type
     */
    public function log( $message, $type=null )
    {
        error_log($message, $type);
    }

    abstract public function init();
    abstract public function preventMysqlGoneAway();
    abstract public function canClientJoin(Client &$client, $path, $query );
    abstract public function catchClientError(Client &$client, Exception $exception );
}