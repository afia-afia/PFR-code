<?php

namespace App;

use MongoDB\Client;

/**
 * Description of Mongo
 *
 * @author tibo
 */
class Mongo
{

    private static $mongo;


    public static function get()
    {
        if (self::$mongo == null) {
            $uri = config('services.mongo.uri');
            $uriOptions = config('services.mongo.uriOptions');
            $driverOptions = config('services.mongo.driverOptions');

            self::$mongo = new Client($uri, $uriOptions, $driverOptions);
        }

        return self::$mongo;
    }
}
