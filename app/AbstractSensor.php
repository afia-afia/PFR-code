<?php

namespace App;


abstract class AbstractSensor implements Sensor
{

    private $server;

    public function __construct(?Server $server = null)
    {
        $this->server = $server;
    }

    protected function server() : Server
    {
        return $this->server;
    }

    public function name() : string
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    public static function getColorForStatus(int $status) : string
    {
        switch ($status) {
            case 0:
                return 'success';
            case 10:
                return 'warning';
            case 20:
                return 'danger';
            default:
                return 'secondary';
        }
    }
}
