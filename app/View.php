<?php

class View {
    
    protected $controller;
    protected $action;
    protected $level = 0;
    protected $levels = [];
    protected $vars = [];
    
    public function __construct(string $controller, string $action) {
        $this->controller = strtolower($controller);
        $this->action = strtolower($action);
        $this->levels[] = __DIR__ . "/views/index.php";
        $this->levels[] = __DIR__ . "/views/layout/{$this->controller}.php";
        $this->levels[] = __DIR__ . "/views/{$this->controller}/{$this->action}.php";
    }
    
    protected function showContent() {
        if(count($this->levels) > $this->level) {
            extract($this->vars);
            include($this->levels[$this->level++]);
        }
    }
    
    public function setVar($name, $value) {
        $this->vars[$name] = $value;
    }
    
    public function setVars(array $vars) {
        $this->vars = array_merge($this->vars, $vars);
    }
    
    public function render() {
        $this->showContent();
    }
    
}
