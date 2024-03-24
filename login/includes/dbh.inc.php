<?php

require_once "environments.inc.php";

$conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    echo "Conexão com banco de dados falhou: " . $conn->connect_error;
    die();
}