<?php

class LoginUser
{
    private $username;
    private $userid;
    private $password;
    public $error;
    public $success;
    private $storage = "json/LOGINdata.json";
    private $stored_users;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;

        $this->stored_users = json_decode(file_get_contents($this->storage), true);
        //call login function
        $this->login();
    }

    private function login()
    {
        foreach ($this->stored_users as $user) {
            if ($user['username'] == $this->username) {
                if (password_verify($this->password, $user['password'])) {
                    session_start();
                    $_SESSION['user'] = $user['user id'];
                    header("location: activitylist.php");
                    exit();
                }
            }
        }
        return $this->error = "Wrong username or password.";
    }
}
