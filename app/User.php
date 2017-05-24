<?php

class User {
    
    protected $__password;
    
    public function __construct() {
        session_start();
        if(isset($_SESSION['user'])) {
            $this->name = $_SESSION['user'];
            $this->__password = $_SESSION['pass'];
        }
    }
    
    public function __destruct() {
        session_write_close();
    }
    
    public function login(string $username, string $password) {
        $this->name = htmlentities($username);
        $this->__password = md5($password);
        return $this->validate();
    }
    
    public function logout() {
        setcookie(session_name());
        session_destroy();
        session_write_close();
        session_start();
        session_regenerate_id();
        header("Location: https://{$_SERVER['HTTP_HOST']}/");
    }
    
    protected function validate() {
        $_SESSION['user'] = $this->name;
        $_SESSION['pass'] = $this->__password;
        return true;
    }
    
    public function __get($var) {
        if (isset($this->{"__$var"})) {
            return $this->{"__$var"};
        }
    }
    
}
