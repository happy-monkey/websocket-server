# websocket-server

## Install

```
composer require happy-monkey/websocket-server
```

## Messages format

```
{
    "action": "actionName",
    "data": mixed | null,
    "room": "room_id" | null
}
```

If a message is received, server will look for a method called `onActionName` and execute it. Callback will take 3 arguments : 
- Client object of sender
- Data of message
- Room object if a room with `room_id` if exists

## Angular WebSocket client service

See https://www.npmjs.com/package/@happymonkey/websocket-client
