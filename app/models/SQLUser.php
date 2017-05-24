<?php

class SQLUser extends BasicUser {
    
    const DATA_DIR = __DIR__ . '/../data/';
    
    protected static $type;
    protected static $dsn;
    protected static $user;
    protected static $pass;
    
    public function __construct(string $type = 'SQLite3', $info = 'users.db') {
        self::$type = $type;
        
        switch($type) {
            case 'SQLite3':
                self::$dsn = self::DATA_DIR . $info;
                break;
            default:
                self::$dsn = "{$info['type']}:dbname={$info['database']};host={$info['server']}";
                self::$user = $info['username'];
                self::$pass = $info['password'];
                break;
        }
        
        parent::__construct();
    }
    
    protected static function getDatabase() {
        if (self::$type === 'SQLite3')
            return new self::$type(self::$dsn);
        return new self::$type(self::$dsn, self::$user, self::$pass);
    }
    
    protected function getUsers() {
        $database = self::getDatabase();
        $userQuery = $database->query(
            'SELECT * FROM users JOIN details ON users.detail_id = details.id'
        );
        $result = [];
        $fetch = 'fetchObject';
        $fetchArg = 'stdClass';
        
        if (self::$type === 'SQLite3') {
            $fetch = 'fetchArray';
            $fetchArg = SQLITE3_ASSOC;
        }
        
        while($userRow = $userQuery->$fetch($fetchArg)) {
            $userRow = json_decode(json_encode($userRow), true);
            $result[$userRow['username']] = $userRow;
        }
        
        if (self::$type === 'SQLite3') {
            $database->close();
        }
        
        unset($database);
        return $result;
    }
    
    public static function create(string $username, string $password, array $optional = []) {
        $database = self::getDatabase();
        
        $addDetail = $database->prepare('INSERT INTO details (email) VALUES (:email)');
        $addUser = $database->prepare('INSERT INTO users (username, password, detail_id) VALUES (:un, :pw, :did)');
        
        if (self::$type === 'SQLite3') {
            $strType = SQLITE3_TEXT;
            $intType = SQLITE3_INTEGER;
            $email = $database->escapeString($optional['email'] ?: '');
            $user = $database->escapeString(htmlentities($username));
            $lastId = 'lastInsertRowID';
        } else {
            $strType = PDO::PARAM_STR;
            $intType = PDO::PARAM_INT;
            $email = $optional['email'] ?: '';
            $user = htmlentities($username);
            $lastId = 'lastInsertId';
        }
        
        $addDetail->bindValue(':email', $email, $strType);
        $addDetail->execute();
        
        $addUser->bindValue(':un', $user, $strType);
        $addUser->bindValue(':pw', md5($password), $strType);
        $addUser->bindValue(':did', $database->$lastId(), $intType);
        $addUser->execute();
        
        if (self::$type === 'SQLite3') {
            $database->close();
        }
        unset($database);
    }
    
}
