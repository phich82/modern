<?php 

/**
 * Singleton
 * 
 * @desc
 *      Allow only one instance of the class
 *      The modern way allows you to defined Singleton once
 * 
 * @example
 *      You only want one Database object
 *      You only want one Front Controller (MVC)
 */
class Singleton
{
    /**
     * This store the only instance of the class
     * 
     * @var {null|object}
     */
    private static $instance = null;

    /**
     * This is how we get our single instance
     * 
     * @return {object}
     */
    public static function getInstance()
    {
        /** if no instance, create one */
        if (empty(self::$instance)) { 
            // late static binding => the object created depends on call context
            // => allow Pattern to be re-used
            self::$instance = new static; // modern way: use 'static' keyword
        }
        return self::$instance;
    }

    /**
     * do not initiate the object with 'new class'
     */
    protected function __construct() { }

    /**
     * Do not allow the cloning of this object
     */
    private function __clone() { }

    /**
     * Do not allow serialization of this object
     */
    private function __wakeup() { }
}

class Database extends Singleton
{
    protected $dsn;

    /**
     * Example method (not part of the pattern)
     * 
     * @param {string} $dsn
     */
    public function setDsn(string $dsn)
    {
        $this->dsn = $dsn;
    }

    /**
     * Example method (not part of the pattern)
     * 
     * @return {string}
     */
    public function getDsn()
    {
        return $this->dsn;
    }
}

$db = Database::getInstance();
$db->setDsn('mysql://');

print_r($db->getDsn().PHP_EOL);

$db2 = Database::getInstance();
$db2->setDsn('postgres://');

print_r($db2->getDsn().PHP_EOL);
print_r($db->getDsn().PHP_EOL);