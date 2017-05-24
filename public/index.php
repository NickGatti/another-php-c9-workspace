<?php
    if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js)$/', $_SERVER["REQUEST_URI"]))
        return false;
        
    require_once(__DIR__ . '/../app/Router.php');
    $router = new Router();
    $router->execute()
           ->render();
