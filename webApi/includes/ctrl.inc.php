<?php

declare(strict_types=1);

require_once "model.inc.php";
require_once "environments.inc.php";

class Control {
    private ?User $user;
    private mysqli $conn;
    private string $username;
    private string $password;
    public function __construct(mysqli $conn, string $username, string $password) {
        $this->user = get_user($conn, $username);
        $this->conn = $conn;
        $this->username = $username;
        $this->password = $password;
    }

    public function is_input_empty(): bool {
        return empty($this->username) || empty($this->password);
    }
    public function is_username_taken(): bool {
        return $this->user ? true : false;
    }    
    public static function min_password_len() {
        return 8;
    }
    public function is_password_invalid() {
        return strlen($this->password) < $this->min_password_len();
    }
    public function create_user() {
        $this->user = set_user($this->conn, $this->username, $this->password);
    }
    public function login_user() {
        if ($this->user !== null) {
            $_SESSION[SESSION_KEY_USER_ID] = $this->user->id;
        }
    }
    public function is_username_nonexistent(): bool {
        return $this->user === null;
    }
    public function is_password_incorret(): bool {
        if ($this->user) {
            return $this->user->password !== $this->password;
        }
        return true;
    }
}

