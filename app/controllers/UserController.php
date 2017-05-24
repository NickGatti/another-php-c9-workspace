<?php

class UserController extends IndexController {
    
    public function loginAction() {
        $this->view->setVar('title', self::TITLE . ' - Login');
        $this->view->setVars([
            'link' => '/user/register',
            'linkText' => 'Create an Account',
            'header' => 'Login'
        ]);
        
        if ($_POST) {
            if($this->user->login($_POST['username'], $_POST['password']))
                header("Location: https://{$_SERVER['HTTP_HOST']}/");
        }
    }
    
    public function logoutAction() {
        if($this->authenticated())
            $this->user->logout();
            
        $this->view->setVars([
            'link' => '/user/login',
            'linkText' => 'Already have an account? Login.',
            'header' => 'Register'
        ]);
    }
    
    public function registerAction() {
        $this->view->setVar('title', self::TITLE . ' - Register');
        $this->view->setVars([
            'link' => '/user/login',
            'linkText' => 'Already have an account? Login.',
            'header' => 'Register'
        ]);
        
        $create = self::USER_MODEL . "::create";
        
        if ($_POST) {
            $create(
                $_POST['username'],
                $_POST['password'],
                ['email' => $_POST['email']]
            );
        }
    }
}
