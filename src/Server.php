<?php namespace HappyMonkey\WebSocket;

use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\MessageComponentInterface;
use Ratchet\Server\IoServer;
use Ratchet\Wamp\Exception;
use Ratchet\WebSocket\WsServer;

class Server implements MessageComponentInterface
{
    /**
     * @var ServerCore $interface
     */
    protected $interface;

    /**
     * Server constructor.
     * @param ServerCore $interface
     */
    public function __construct( $interface )
    {
        $this->interface = $interface;
        $this->interface->preventMysqlGoneAway();
    }

    /**
     * @param string $port
     * @return IoServer
     */
    public function run( $port='8282' )
    {
        $keepAliveInterval = 10;

        $this->interface->log("Launch server on port $port");
        $ws = new WsServer($this);
        $io = IoServer::factory(new HttpServer($ws), $port);
        $ws->enableKeepAlive($io->loop, $keepAliveInterval);
        $this->interface->log("Server is ready");
        $io->run();

        return $io;
    }

    /**
     * @inheritDoc
     */
    function onOpen(ConnectionInterface $conn)
    {
        $this->interface->preventMysqlGoneAway();

        $uri = $conn->httpRequest->getUri();
        $path = trim($uri->getPath(), '/');
        parse_str($uri->getQuery(), $query);
        $client = new Client($this->interface, $conn);

        try {
            if ( $this->interface->canClientJoin($client, $path, $query) !== false )
            {
                $this->interface->attachClient($client);
                $this->interface->log("New client is connected. UID : {$client->getUid()}");
            }
            else
            {
                $conn->close();
            }
        } catch ( \Exception $exception ) {
            $this->interface->log('Error before client connexion : '.$exception->getMessage().' | '.$exception->getFile().':'.$exception->getLine());
            $conn->close();
        }
    }

    /**
     * @inheritDoc
     */
    function onClose(ConnectionInterface $conn)
    {
        $this->interface->preventMysqlGoneAway();

        if( $client = $this->interface->getClientFromConn($conn) )
        {
            $this->interface->detachClient($client);
            $this->interface->log("Client disconnected. UID : {$client->getUid()}");
        }
    }

    /**
     * @inheritDoc
     */
    function onError(ConnectionInterface $conn, \Exception $e)
    {
        $this->interface->preventMysqlGoneAway();

        $client = $this->interface->getClientFromConn($conn);
        $this->interface->catchClientError($client, $e);
    }

    /**
     * @inheritDoc
     */
    function onMessage(ConnectionInterface $from, $msg)
    {
        $this->interface->preventMysqlGoneAway();

        try {
            $client = $this->interface->getClientFromConn($from);
            $message = Message::fromJSON($msg);

            // Prevent call reserved functions
            $method = 'on' . ucfirst($message->getAction());
            if( method_exists($this->interface, $method) )
            {
                @call_user_func_array([$this->interface, $method], [$client, $message->getData()]);
            }
            else
            {
                $this->interface->log(get_class($this->interface) . "::$method not found to handle message ".json_encode($message));
            }

        } catch ( Exception $exception ) {

        }
    }
}