<?php namespace HappyMonkey\WebSocket;

class Room extends BaseObject
{
    use ClientCollection;

    protected string $uid_prefix = 'room_';

    public function __construct($uid = null)
    {
        parent::__construct($uid);
        $this->initCollection();
    }

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        $properties = parent::jsonSerialize();
        $properties['clients'] = [];
        foreach( $this->clients as $conn )
        {
            $properties['clients'][] = $this->clients[$conn];
        }
        return $properties;
    }
}