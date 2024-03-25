<?php

require_once "environments.inc.php";

class User {
    public int $id;
    public string $name;
    public string $password;

    public function __construct(int $id, string $name, string $password) {
        $this->id = $id;
        $this->name = $name;
        $this->password = $password;
    }
    public static function make_from_row(array $row): User {
        return new User(
            $row[DB_USER_TABLE_FIELD_ID],
            $row[DB_USER_TABLE_FIELD_USERNAME],
            $row[DB_USER_TABLE_FIELD_PASSWORD]
        );
    }
}

function get_user(mysqli $conn, string $username): ?User {

    $stmt = $conn->prepare("SELECT * FROM ".DB_USER_TABLE_NAME." WHERE ".DB_USER_TABLE_FIELD_USERNAME." = ?");
    $stmt->bind_param('s', $username);

    $stmt->execute();
    
    $row = $stmt->get_result()->fetch_assoc();

    return empty($row) ? null : User::make_from_row($row);
}

function set_user(mysqli $conn, string $username, string $password): ?User {
    $stmt = $conn->prepare(
        "INSERT INTO ".DB_USER_TABLE_NAME." 
        (".DB_USER_TABLE_FIELD_USERNAME.", ".DB_USER_TABLE_FIELD_PASSWORD.")
        VALUES (?, ?)"
    );

    $stmt->bind_param('ss', $username, $password);

    if ($stmt->execute()) {
        $result = $conn->query("
            SELECT * FROM ".DB_USER_TABLE_NAME." 
            WHERE ".DB_USER_TABLE_FIELD_USERNAME." = '".$username."'");
        if ($result !== false) {
            return User::make_from_row($result->fetch_assoc());
        }
    }
    return null;
}