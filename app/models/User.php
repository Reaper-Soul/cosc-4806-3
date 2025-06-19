<?php

class User {

    public $username;
    public $password;
    public $auth = false;

    public function __construct() {
        // Nothing needed here unless you want to keep a DB connection
    }

     public function test(): array {
        $db = db_connect(); // Connect to DB
        $statement = $db->query("SELECT * FROM users"); // run the query
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC); // get all rows
        return $rows;
    }

    /**
     * Authenticate user by username and password
     *
     * @param mixed $username
     * @param mixed $password
     * @return void
     */
    public function authenticate($username, $password): void {
        $username = strtolower($username);
        $db = db_connect(); // connect to DB

        $statement = $db->prepare("SELECT * FROM users WHERE username = :name;");
        $statement->bindValue(':name', $username);
        $statement->execute();

        $rows = $statement->fetch(PDO::FETCH_ASSOC);

        if ($rows && password_verify($password, $rows['password'])) {
            $_SESSION['auth'] = 1;
            $_SESSION['username'] = ucwords($username);
            unset($_SESSION['failedAuth']);
            header('Location: /home');
            die;
        } else {
            if (isset($_SESSION['failedAuth'])) {
                $_SESSION['failedAuth']++; // increment
            } else {
                $_SESSION['failedAuth'] = 1;
            }
            header('Location: /login');
            die;
        }
    }
}
