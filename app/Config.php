<?php

namespace App;

class Config
{
    private $_settings = [];
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
        $this->_settings = require dirname(__DIR__) . '/config/config.php';
    }

    public function getSetting($key)
    {
        if(!isset($this->_settings[$key]))
        {
            return null;
        }

        return $this->_settings[$key];
    }
}