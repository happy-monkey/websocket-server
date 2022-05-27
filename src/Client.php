<?php namespace HappyMonkey\WebSocket;

use Ratchet\ConnectionInterface;

class Client extends BaseObject
{
    protected string $uid_prefix = 'client_';

    /**
     * @var ServerCore $server
     */
    protected $server;

    /**
     * @var ConnectionInterface
     */
    protected $conn;

    /**
     * Client constructor.
     * @param ServerCore $server
     * @param ConnectionInterface $conn
     */
    public function __construct( $server, ConnectionInterface $conn )
    {
        parent::__construct();

        $this->server = $server;
        $this->conn = $conn;
    }

    /**
     * @return ConnectionInterface
     */
    public function getConn(): ConnectionInterface
    {
        return $this->conn;
    }

    /**
     * @param string|Message $message
     */
    public function send( string|Message $message )
    {
        $this->conn->send(is_string($message) ? $message : json_encode($message, JSON_UNESCAPED_UNICODE));
    }
}