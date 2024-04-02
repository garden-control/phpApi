<?php

declare(strict_types=1);

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
            (int)$row[DB_USER_TABLE_FIELD_ID],
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

class Leitura {
    public int $id;
    public int $id_estacao;
    public float $umidade_ar;
    public float $temperatura;
    public float $umidade_solo;
    public float $pluv_indice;
    public DateTime $horario;
}
function get_ultimas_leituras(mysqli $conn, int $limite, string $id_estacao): array {

    $stmt = $conn->prepare("
        SELECT * FROM leitura
        WHERE id_estacao = ?
        ORDER BY id DESC
        LIMIT ?;
    ");
    $stmt->bind_param("si", $id_estacao, $limite);

    $stmt->execute();

    $result = $stmt->get_result();

    $leituras = [];
    if ($result !== false) {
        while ($row = $result->fetch_assoc()) {
            $leitura = new Leitura();
            $leitura->id =  $row["id"];
            $leitura->id_estacao =  $row["id_estacao"];
            $leitura->umidade_ar =  $row["umidade_ar"];
            $leitura->temperatura =  $row["temperatura"];
            $leitura->umidade_solo =  $row["umidade_solo"];
            $leitura->pluv_indice =  $row["pluv_indice"];
            $leitura->horario = new DateTime($row["horario"]);

            $leituras[] = $leitura;
        }
    }
    return $leituras;
}

class Estacao {
    public string $id;
    public string $nome;
    public string $localizacao;
}

function get_estacoes(mysqli $conn, int $limit): array {
    $stmt = $conn->prepare("
        SELECT eu.id_estacao, eu.nome, e.localizacao FROM estacao_usuario AS eu
        JOIN estacao AS e ON e.id = eu.id_estacao
        WHERE id_usuario = ?
        LIMIT ?
    ");
    $stmt->bind_param("ii", $_SESSION[SESSION_KEY_USER_ID], $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $estacoes = [];
    while ($row = $result->fetch_assoc()) {
        $estacao = new Estacao();
        $estacao->id = $row["id_estacao"];
        $estacao->nome = $row["nome"];
        $estacao->localizacao = $row["localizacao"];
        $estacoes[] = $estacao;
    }
    return $estacoes;
}

function get_estacao(mysqli $conn, string $id_estacao): array {
    $stmt = $conn->prepare("SELECT * FROM estacao WHERE id = ?");
    $stmt->bind_param("s", $id_estacao);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result !== false) {
        return $result->fetch_assoc();
    }
    return [];
}

function set_estacao(mysqli $conn, string $id_estacao, string $localizacao): void {

}


function get_usuario_estacao(mysqli $conn, string $id_estacao): array {
    $stmt = $conn->prepare("SELECT * FROM estacao_usuario WHERE id_usuario = ? AND id_estacao = ?");
    $stmt->bind_param("is", $_SESSION[SESSION_KEY_USER_ID], $id_estacao);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result !== false) {
        return $result->fetch_assoc();
    }
    return [];
}

function set_usuario_estacao(mysqli $conn, string $id_estacao, string $nome): bool {
    $stmt = $conn->prepare("
        INSERT INTO estacao_usuario(id_usuario, id_estacao, nome) VALUES 
        (?, ?, ?)
    ");
    $stmt->bind_param("iss", $_SESSION[SESSION_KEY_USER_ID], $id_estacao, $nome);
    $stmt->execute();
    if ($stmt->get_result() === false) {
        return false;
    }
    return true;
}