<?php 

/** ====== config for whole app ===== */
function app() {
    return new class {
        public $namespace       = 'src';
        public $alias_namespace = 'Src';
    };
}

/** ====== autoload classes when called ===== */
spl_autoload_register(function ($class) {
    $ns = str_replace("\\", "/", __NAMESPACE__);
    // mapping alias namespace if has
    if (!empty($ns) && !empty(app()->alias_namespace)) {
        $ns = strpos($ns, app()->alias_namespace) !== false ? str_replace(app()->alias_namespace, app()->namespace, $ns) : $ns;
    }

    $location = __DIR__.'/'.(empty($ns) ? "" : $ns."/").str_replace("\\", "/", $class).'.php';

    if (file_exists($location)) require_once $location;
    else throw new Error("Class [$class] not found.");

    require_once 'src/ServiceProvider.php';
});

use \Src\DIContainer;

/** ===== process the current route ===== */
function route($module_A_method, array $args = []) {
    $module_A_method = trim($module_A_method);
    $split  = explode('@', $module_A_method);
    $module = $split[0];
    $method = count($split) === 2 ? $split[1] : 'index'; 
    $ns     = strpos($module, '/') !== false || strpos($module, '\\') !== false ? '' : (app()->alias_namespace ? app()->alias_namespace.'\\' : app()->namespace);
    $module = $ns.$module;

    if (!method_exists(DIContainer::getService($module), $method)) {
        throw new Error("Method [$method] does not exist in class $module");
    }
    call_user_func_array([DIContainer::getService($module), $method], $args);    
}

/** call service where you need it */
// $cart = DIContainer::getService(Cart::class);
// $cart->checkout(1, 1);

route('Cart@checkout', [1, 2]);
// route('Cart@getCart');

