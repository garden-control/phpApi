<?php

require_once "environments.inc.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    echo "Conexão com banco de dados falhou: " . $conn->connect_error;
    die();
}