<?php

namespace App;

/**
 * Wrapper around a status code
 */
class Status
{
    const UNKNOWN = -1;
    const OK = 0;
    const WARNING = 10;
    const ERROR = 20;

    private $code;

    public function __construct(int $code)
    {
        $this->code = $code;
    }

    public function name() : string
    {
        switch ($this->code) {
            case 0:
                return "OK";
            case 10:
                return "WARNING";
            case 20:
                return "ERROR";
            default:
                return "Unknown";
        }
    }

    public function code() : int
    {
        return $this->code;
    }

    public function badge() : string
    {
        switch ($this->code) {
            case 0:
                return '<span class="badge badge-success">OK</span>';
            case 10:
                return '<span class="badge badge-warning">WARNING</span>';
            case 20:
                return '<span class="badge badge-danger">ERROR</span>';
            default:
                return '<span class="badge badge-secondary">Unknown</span>';
        }
    }

    public static function max(array $statuses) : Status
    {
        $max = new Status(self::UNKNOWN);
        foreach ($statuses as $status) {
            if ($status->code() > $max->code()) {
                $max = $status;
            }
            
        }
       
        return $max;
    }

    public function color() : string
    {
        switch ($this->code) {
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
