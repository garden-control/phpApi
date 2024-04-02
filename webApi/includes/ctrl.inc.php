<?php

declare(strict_types=1);

require_once "model.inc.php";
require_once "environments.inc.php";

class UserControl {
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

function add_estacao(mysqli $conn, string $id_estacao, string $nome): bool {
    if (empty(get_usuario_estacao($conn, $id_estacao))) {
        set_usuario_estacao($conn, $id_estacao, $nome);
        return true;
    }
    return false;
}

//retorna um novo id válido de estação
function get_novo_id_estacao(mysqli $conn): string {
    $id_estacao;
    //procurar um id válido
    do {
        $id_estacao = bin2hex(random_bytes(8));
    } while (!empty(get_estacao($conn, $id_estacao)));

    return $id_estacao;
}

//retorna o id da nova estação
function add_nova_estacao(mysqli $conn, string $localizacao, string $nome): string {
    
    $id_estacao = get_novo_id_estacao($conn);

    //criar nova estacao
    set_estacao($conn, $id_estacao, $localizacao);
    
    //associa-la ao usuario
    set_usuario_estacao($conn, $id_estacao, $nome);

    return $id_estacao;
}