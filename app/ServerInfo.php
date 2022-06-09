<?php

namespace App;

/**
 * Holds info about a server.
 */
class ServerInfo
{
    const REGEX_PRODUCT_NAME = "/^\s*Product Name: (.*)$/m";

    private $record;

    public function __construct($record)
    {
        $this->record = $record;
    }
    /**
     * Human readable uptime.
     *
     * @return string
     */
    public function uptime() : string
    {
        if (! isset($this->record["upaimte"])) {
            return "unknown";
        }

        return $this->parseUptime($this->record->upaimte);
    }

    public function parseUptime(string $string) : string
    {
        $pieces = explode(' ', $string);
        $uptime = \Carbon\Carbon::now()->subSeconds($pieces[0]);
        return $uptime->diffForHumans(null, true);
    }

    public function uuid()
    {
        if (! isset($this->record["system"])) {
            return "";
        }

        return $this->parseUUID($this->record->system);
    }

    const UUID = "/\s*UUID: (.*)/m";

    public function parseUUID(string $string) : string
    {
        $matches = array();
        preg_match(self::UUID, $string, $matches);
        if (! isset($matches[1])) {
            return "unknown";
        }
        return $matches[1];
    }


    public function cpuinfo() : array
    {
        if (! isset($this->record["cpu"])) {
            return ["threads" => 0,
                "cpu" => "unknown"];
        }

        return $this->parseCpuinfo($this->record->cpu);
    }

    const CPU_INFO = "/^model name	: (.+)$/m";
    public function parseCpuinfo($string) : array
    {
        $matches = array();
        preg_match_all(self::CPU_INFO, $string, $matches);

        $result["threads"] = count($matches[0]);
        $result["cpu"] = $matches[1][0];
        return $result;
    }

    public function meminfo()
    {
        return round($this->memoryTotal() / 1000 / 1000) . " GB";
    }

    /**
     *
     * @return int total memory (in KB) or 0 if not found...
     */
    public function memoryTotal()
    {
        if (! isset($this->record["memory"])) {
            return 0;
        }

        return $this->parseMeminfo($this->record->memory);
    }

    const MEMINFO = "/^MemTotal:\\s+([0-9]+) kB$/m";
    public function parseMeminfo($string)
    {
        $matches = array();
        preg_match(self::MEMINFO, $string, $matches);
        $total = $matches[1];
        return $total;
    }

    public function lsb()
    {
        if (! isset($this->record["lsb"])) {
            return "unknown";
        }

        return $this->parseLsb($this->record->lsb);
    }

    const LSB = "/^Description:	(.+)$/m";
    public function parseLsb($string) : string
    {
        $matches = [];
        preg_match(self::LSB, $string, $matches);
        return $matches[1];
    }


    const REGEX_MANUFACTURER = "/^\s*Manufacturer: (.*)$/m";
    public function parseManufacturer(string $string) : string
    {
        $matches = [];
        preg_match(self::REGEX_MANUFACTURER, $string, $matches);

        if (!isset($matches[1])) {
            return "unkwnown";
        }
        return $matches[1];
    }

    public function manufacturer()
    {
        if (! isset($this->record["system"])) {
            return "unknown";
        }

        return $this->parseManufacturer($this->record->system);
    }


    public function parseProductName(string $string) : string
    {
        $matches = [];
        preg_match(self::REGEX_PRODUCT_NAME, $string, $matches);
        if (!isset($matches[1])) {
            return "unkwnown";
        }
        return $matches[1];
    }

    public function productName()
    {
        if (! isset($this->record["system"])) {
            return "unknown";
        }

        return $this->parseProductName($this->record->system);
    }

        /**
     *
     * @return \Carbon\Carbon
     */
    public function lastRecordTime()
    {
        $hearbeat = new \App\Sensor\Heartbeat();
        return $hearbeat->lastRecordTime($this->record);
    }

    public function clientVersion() : string
    {
        $sensor = new \App\Sensor\ClientVersion();
        return $sensor->installedVersion([$this->record]);
    }

    public function lastClientUrl()
    {
        $client_sensor = new \App\Sensor\ClientVersion();
        return $client_sensor->latestUrl();
    }
}
