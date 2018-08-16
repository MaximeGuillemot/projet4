<?php

namespace App;

class Autoloader // Loads other classes automatically (no need to require each class when needed)
{
    static function initiateAutoloader() // Adds the "autoload" method in the execution pile so that unknown methods are found through that process automatically
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }
    
    static function autoload($class) // Method called everytime a method is not found to help find it (through magic method __autoload())
    {
        if(strpos($class, __NAMESPACE__ . '\\') === 0) // Only loads the project's methods (in that namespace); other methods from other sources can use their own autoloaders
        {                                               
            $class = str_replace(__NAMESPACE__ . '\\', '', $class); // Removes namespace from class name to avoid problems with path
            $class = str_replace('\\', '/', $class); // Replaces anti-slahes by slahes to conform with require path
            require __DIR__ . '/' . $class . '.php'; // Dynamic absolute path used for reusability of the class for other projects
        }
    }
}