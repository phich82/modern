<?php

class DIContainer 
{
    private $services = [];

    // $name: interface
    public function register(string $name, callable $callable)
    {
        if (array_key_exists($name, $this->services)) throw new Error('Service ['.$name.'] has already been existed!');       
        // $this->services[$name] = $callable;
        $this->services[$name] = ['callable' => $callable, 'service' => $name];
    }

    public function getService(string $name, $args = null)
    {
        if (!array_key_exists($name, $this->services)) throw new Error('Service ['.$name.'] does not exist!');
        // return $this->services[$name]();
        // return $this->services[$name]['callable']();
        if (!empty($args)) {
            if (is_string($args))     $args = [$this->services[$name]['service'], $args];
            else if (is_array($args)) $args = array_merge([$this->services[$name]['service']], $args);
            else                      $args = [$this->services[$name]['service']];
        } else                        $args = [$this->services[$name]['service']];

        // call the specified method with its arguments
        return call_user_func_array($this->services[$name]['callable'], $args);
    }

    public function __set(string $name, callable $callable)
    {
        $this->register($name, $callable);
    }

    public function __get(string $name)
    {
        return $this->getService($name);
    }
   
    // get all service names available/defined
    public function list($type = false)
    {
        $keys = array_keys($this->services);
        if ($type === true) return implode(',', $keys); // a string
        return $keys;                                   // an array
    }
}

/** config */
$config = [
    'aws' => [
        'key'         => '123',
        'private_key' => 'abc'
    ],
    'db' => [
        'username' => '456',
        'password' => 'def'
    ]
];

/** register the new services */
$container = new DIContainer;
$container->register('aws', function ($service) use($config) {
    $o = new stdClass;
    $o->name = strtoupper($service);

    // create dynamic variables from config
    $allowed = ['key', 'private_key'];
    $params  = $config[$service];
    if (is_array($params)) {
        foreach($params as $k => $v) {
            if (in_array($k, $allowed)) $o->{$k} = $v;
        }
    }

    return $o;
});

$container->register('db', function ($service, $arg1 = null, $arg2 = null) use($config) {
    $o = new stdClass;
    $o->name = strtoupper($service);
    $o->arg1 = $arg1;
    $o->arg2 = $arg2;

    // create dynamic variables from config
    $allowed = ['username', 'password'];
    $params  = $config[$service];
    if (is_array($params)) {
        foreach($params as $k => $v) {
            if (in_array($k, $allowed)) $o->{$k} = $v;
        }
    }
    
    return $o;
});

$container->email = function ($service) {
    return $service;
};

/** call service where you need it */
echo PHP_EOL;
echo $container->getService('aws')->name.PHP_EOL;
echo $container->getService('aws')->key;
echo PHP_EOL;

echo PHP_EOL;
$db = $container->getService('db', ['hi', 'hey']);
echo $db->username.PHP_EOL;
echo $db->password.PHP_EOL;
echo $db->arg1.PHP_EOL;
echo $db->arg2;
echo PHP_EOL;

echo PHP_EOL;
echo $container->email;
echo PHP_EOL;