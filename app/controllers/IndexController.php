<?php

class IndexController {
    
    const TITLE = "My Site";
    const USER_MODEL = "SQLUser";
    
    public function __construct($view) {
        $this->view = $view;
        $class = self::USER_MODEL;
        $this->user = new $class('PDO', [
            'type' => 'mysql',
            'server' => getenv('IP'),
            'username' => getenv('C9_USER'),
            'password' => '',
            'database' => 'app',
            'port' => 3306
        ]);
        $this->view->setVar('title', self::TITLE);
        $this->view->setVar('user', $this->user);
        $this->view->setVars($_GET ?: []);
    }
    
    public function indexAction() {
        if ($this->authenticated())
            $this->view->setVar('title', self::TITLE . ' - Welcome');
    }
    
    protected function authenticated() {
        if(!$this->user->name) {
            header("Location: https://{$_SERVER['HTTP_HOST']}/user/login");
            return false;
        }
        
        return true;
    }
    
}
