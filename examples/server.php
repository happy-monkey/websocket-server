<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once dirname(__FILE__) . '/../vendor/autoload.php';

use HappyMonkey\WebSocket\Client;
use HappyMonkey\WebSocket\Events\ClientDidJoinedEvent;
use HappyMonkey\WebSocket\Events\ClientDidLeftEvent;
use HappyMonkey\WebSocket\Message;
use HappyMonkey\WebSocket\Room;
use HappyMonkey\WebSocket\Server;
use HappyMonkey\WebSocket\ServerCore;
use HappyMonkey\WebSocket\ServerEvent;
use HappyMonkey\WebSocket\ServerEventListener;

class GameServer extends ServerEventListener
{
    use ServerCore;

    public function init()
    {
        $this->log('Interface is ready');
    }

    public function preventMysqlGoneAway()
    {
        // $this->log('Implements some code to prevent mysql gone away error');
    }

    public function onStartGame(Client &$client)
    {
        $room = new Room();
        $this->log("Room {$room->getUid()}");
        $this->addRoom($room);
        $room->attachClient($client);
    }

    public function onJoinGame(Client &$client, $uid)
    {
        if( $room = $this->findRoom($uid) )
        {
            $room->attachClient($client);
        }
        else
        {
            $client->send(new Message('error', 'Room not found'));
        }
    }

    public function canClientJoin(Client &$client, $path, $query)
    {
        // TODO: Implement whenClientJoin() method.
        return true;
    }

    public function catchClientError(Client &$client, Exception $exception)
    {
        // TODO: Implement whenClientError() method.
    }

    public function handleServerEvent(ServerEvent $event, $client, $room)
    {
        switch ( $event->getName() )
        {
            case ClientDidJoinedEvent::NAME : {
                if( $room ) {
                    $room->send(new Message('client_join_game', $client));
                    if( $room->getClientsCount()==2 )
                    {
                        $room->send(new Message('success', '2 clients in room !'));
                    }
                }
                break;
            }
            case ClientDidLeftEvent::NAME : {
                if( $room ) {
                    $room->send(new Message('client_leave_game', $client));
                }
                break;
            }
        }
    }
}

try {
    $server = new Server(GameServer::create());
    $server->run('8282');
} catch ( Exception $exception ) {
    echo $exception->getMessage();
}