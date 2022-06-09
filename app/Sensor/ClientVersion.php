<?php

namespace App\Sensor;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;


class ClientVersion extends \App\AbstractSensor
{

    const MANIFEST = "https://download.cylab.be/monitor-php-client/manifest.json";
    private static $manifest = null;

    public function manifest()
    {
        if (!is_null(self::$manifest)) {
            return self::$manifest;
        }

        $options = [
            'timeout' => 5.0];

        $proxy = config("app.proxy", null);
        if ($proxy != null) {
            $options["proxy"] = $proxy;
        }

        $client = new Client($options);

        try {
            $json = $client->get(self::MANIFEST)->getBody();
            self::$manifest = json_decode($json, true)[0];
        } catch (RequestException $e) {
            self::$manifest = ["version" => "unknown",
                "url" => "??"];
        }

        return self::$manifest;
    }

    /**
     * Fetch the latest available version (e.g. "1.2.3")
     *
     * @throws \RuntimeException if a network problem occurs
     * @return string the latest available version (e.g. "1.2.3")
     */
    public function latestVersion() : string
    {   

        $version = file_get_contents(__DIR__ . "/version");
        return $version ;
    }

    public function latestUrl() : string
    {
        return $this->manifest()["url"];
    }

    public function report(array $records) : string
    {
        return "<p>Installed version: " . $this->installedVersion($records) . "</p>"
        . "<p>Latest client version: " . $this->latestVersion() . "</p>";
    }

    public function installedVersion(array $records)
    {
        $last_record = end($records);
        if ($last_record == null) {
            return "none";
        }

        return $last_record->version;
    }

    public function status(array $records) : int
    {
        $latest_version = "unknown";

        try {
            $latest_version = $this->latestVersion();
        } catch (\ErrorException $ex) {
            return \App\Status::UNKNOWN;
        }

        if ($this->installedVersion($records) === $latest_version) {
            return \App\Status::OK;
        }

        return \App\Status::WARNING;
    }
}
