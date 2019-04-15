<?php
namespace Src;

use \Error;

class DIContainer 
{
    private static $services = [];

    // $name: class name | interface name
    public static function register(string $name, callable $callable)
    {
        if (array_key_exists($name, self::$services)) throw new Error('Service ['.$name.'] has already been existed!');       
        self::$services[$name] = ['callable' => $callable, 'service' => $name];
    }

    public static function getService(string $name, $args = null)
    {
        if (!array_key_exists($name, self::$services)) throw new Error('Service ['.$name.'] does not exist!');

        if (!empty($args)) {
            if (is_string($args))     $args = [self::$services[$name]['service'], $args];
            else if (is_array($args)) $args = array_merge([self::$services[$name]['service']], $args);
            else                      $args = [self::$services[$name]['service']];
        } else                        $args = [self::$services[$name]['service']];

        // call the specified method with its arguments
        return call_user_func_array(self::$services[$name]['callable'], $args);
    }

    // get all service names available/defined
    public static function list($type = false)
    {
        $keys = array_keys(self::$services);
        if ($type === true) return implode(',', $keys); // a string
        return $keys;                                   // an array
    }

    public function __set(string $name, callable $callable)
    {
        self::register($name, $callable);
    }

    public function __get(string $name)
    {
        return self::getService($name);
    }
   
    
}