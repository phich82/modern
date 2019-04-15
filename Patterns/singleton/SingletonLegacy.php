<?php 

/**
 * SingletonLegacy
 * 
 * @desc
 *      Allow only one instance of the class
 * 
 * @example
 *      You only want one Database object
 *      You only want one Front Controller (MVC)
 */
class SingletonLegacy
{
    /**
     * This store the only instance of the class
     * 
     * @var {null|object}
     */
    private static $instance = null;

    protected $dsn;

    /**
     * This is how we get our single instance
     * 
     * @return {object}
     */
    public static function getInstance()
    {
        /** if no instance, create one */
        if (empty(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class;
        }
        return self::$instance;
    }

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

    /**
     * call internally, but must be private or protected
     */
    private function __construct()
    {
        return 'Class created.';
    }

    /**
     * Do not allow the cloning of this object
     */
    private function __clone() { }

    /**
     * Do not allow serialization of this object
     */
    private function __wakeup() { }
}

$db = SingletonLegacy::getInstance();
print_r($db);

$db->setDsn('mysql://');
print_r($db->getDsn().PHP_EOL);


// get the instance again, but it will still use the same instance
$db2 = SingletonLegacy::getInstance();
$db2->setDsn('postgress://');

print_r($db2->getDsn().PHP_EOL);
print_r($db->getDsn().PHP_EOL);