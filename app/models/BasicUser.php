<?php

class BasicUser extends User {
    
    const DATA_DIR = __DIR__ . '/../data/users';
    
    protected function validate() {
        if (!empty($this->name)) {
            $users = $this->getUsers();
            var_dump($users, $this->__password);
            if ($users[$this->name]['password'] === $this->__password) 
                return parent::validate();
        }
        
        return false;
    }
    
    protected function getUsers() {
        if (!is_dir(__DIR__ . '/../data')) {
            mkdir(__DIR__ . '/../data', 0755);
        }
        
        if (!file_exists(static::DATA_DIR)) {
            file_put_contents(static::DATA_DIR, json_encode([]));
        }
        
        return json_decode(file_get_contents(static::DATA_DIR), true);
    }
    
    public static function create(string $username, string $password, array $optional = []) {
        $user = [
            'username' => htmlentities($username),
            'password' => md5($password),
            'details' => $optional
        ];
        
        $users = self::getUsers();
        $users[$username] = $user;
        
        file_put_contents(static::DATA_DIR, json_encode($users));
        header("Location: https://{$_SERVER['HTTP_HOST']}/");
    }
    
}