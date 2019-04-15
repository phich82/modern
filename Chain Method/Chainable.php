<?php

class Test {
    public function foo() { echo 'FOO'; }
    public function baz() { return 'BAZ'; }
    public function bar() { echo 'BAR'; }
}

/* Wrapping a PHP class for method chaining */
class Chainable {
    
    private $instance = null;
    private $returns  = [];
    
    public function __construct() {
        $args = func_get_args();
        $this->instance = is_object($o = array_shift($args)) ? $o : new $o($args);
    }
    
    // get the returned value from the last chain method
    public function getReturn(&$var) {
        $var = count($this->returns) ? array_pop($this->returns) : null;
        return $this;
    }
    
    // clear the returned values
    public function reset() {
        $this->returns = [];
        return $this;
    }
    
    // redefine call for storing the return values
    public function __call($method, $args) {
        ($r = call_user_func_array([$this->instance, $method], $args)) ? $this->returns[] = $r : null;
        return $this;
    }
}

$test = new Chainable('Test');
$test->foo()->baz()->getReturn($baz)->bar();
echo "\n\nbaz: $baz";

?>