<?php

namespace App;

class Config
{
    private $settings = [];
    private static $_instance;

    public static function getInstance() // Allows the class to be instantiated but only once so we deal with an object but always the same one (singleton)
    {
        if(is_null(self::$_instance))
        {
            self::$_instance = new Config();
        }

        return self::$_instance;
    }

    public function __construct()
    {
        require dirname(__DIR__) . '/config/config.php';
        $this->settings = $dbconfig;
    }

    public function getSetting($key)
    {
        if(!isset($this->settings[$key]))
        {
            return null;
        }

        return $this->settings[$key];
    }
}