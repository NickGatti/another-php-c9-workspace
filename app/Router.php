<?php

class Router {
    
    protected $defaults = [
        'controller' => 'Index',
        'action' => 'index',
    ];
    
    public function __construct() {
        spl_autoload_register([$this, 'autoloadControllers']);
        spl_autoload_register([$this, 'autoloadModels']);
        spl_autoload_register([$this, 'autoloadView']);
    }
    
    public function autoloadControllers($class) {
        $file = __DIR__ . "/controllers/$class.php";
        return $this->includeFile($file);
    }
    
    public function autoloadModels($class) {
        $file = __DIR__ . "/models/$class.php";
        return $this->includeFile($file);
    }
    
    public function autoloadView($class) {
        $file = __DIR__ . "/$class.php";
        return $this->includeFile($file);
    }
    
    protected function includeFile($file) {
        if (file_exists($file)) {
            include $file;
            return true;
        }
        return false;
    }
    
    public function execute() {
        $path = explode('?', $_SERVER['REQUEST_URI']);
        $routing = isset($path[0]) ? explode('/', ltrim($path[0], '/')) : [];
        
        $load = [
            'controller' => $this->defaults['controller'],
            'action' => $this->defaults['action'],
            'params' => []
        ];
        
        if (count($routing) > 0) {
            $load['controller'] = $routing[0] ?: $this->defaults['controller'];
        }
        
        if (count($routing) > 1) {
            $load['action'] = $routing[1] ?: $this->defaults['action'];
        }
        
        if (count($routing) > 2) {
            $load['params'] = array_slice($routing, 2) ?: [];
        }
        
        $controller = ucfirst($load['controller']);
        $action = strtolower($load['action']);
        
        $routeAction = "{$action}Action";
        $routeController = "{$controller}Controller";
        
        $view = new View($controller, $action);
        
        $route = new $routeController($view);
        $route->$routeAction(...$load['params']);
        
        return $view;
    }
    
}