<?php namespace HappyMonkey\WebSocket;

interface ServerEventNames
{
    const ClientWillJoinServer  = 'client_will_join_server';
    const ClientDidJoinServer   = 'client_did_join_server';

    const ClientWillLeaveServer = 'client_will_leave_server';
    const ClientDidLeaveServer  = 'client_did_leave_server';

    const ClientWillJoinRoom    = 'client_will_join_room';
    const ClientDidJoinRoom     = 'client_did_join_room';

    const ClientWillLeaveRoom   = 'client_will_leave_room';
    const ClientDidLeaveRoom    = 'client_did_leave_room';
}