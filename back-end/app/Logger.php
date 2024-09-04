<?php
declare(strict_types=1);

namespace App;

use Monolog\Handler\StreamHandler;
use Monolog\Level;

class Logger
{
    private static \Monolog\Logger $instance;

    public static function getInstance(): \Monolog\Logger
    {
        if (!isset(self::$instance)) {
            self::$instance = new \Monolog\Logger('log');
            self::$instance->pushHandler(new StreamHandler('log.log', Level::Debug));
        }
        return self::$instance;
    }
}