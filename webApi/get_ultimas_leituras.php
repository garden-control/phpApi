<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    require_once "includes/environments.inc.php";
    if (!isset($_SESSION[SESSION_KEY_USER_ID])) {
        echo "Sessão não iniciada";
        die();
    }

    $limite = $_GET["limite"];
    $id_estacao = $_GET["id_estacao"];

    require_once "includes/dbh.inc.php";
    require_once "includes/model.inc.php";
    require_once "includes/view.inc.php";
    require_once "includes/ctrl.inc.php";
    
    $leituras = get_ultimas_leituras($conn, $limite, $id_estacao);
    
    echo '{"leituras": ';
    jsonLeituras($leituras);
    echo '}';
}
else {
    echo "Método HTTP deve ser GET";
}