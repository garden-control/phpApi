<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    require_once "includes/environments.inc.php";

    if (isset($_SESSION[SESSION_KEY_USER_ID])) {
        require_once "includes/dbh.inc.php";
        require_once "includes/model.inc.php";
        require_once "includes/view.inc.php";
        require_once "includes/ctrl.inc.php";

        $errors = [];
        $id_estacao = "";
        $id_msg_maior_q = 0;
        $limite = 20;
        $mensagens = [];

        if (!isset($_GET["id_estacao"])) {
            $errors[] = "ID da estação não submetido";
        }

        if (empty($errors)) {
            $id_estacao = $_GET["id_estacao"];
            
            if (isset($_GET["id_msg_maior_q"])) {
                $id_msg_maior_q = $_GET["id_msg_maior_q"];
            }
            if (isset($_GET["limite"])) {
                $limite = (int)$_GET["limite"];
            }
            
            try {
                $mensagens = get_cli_log($conn, $id_estacao, $id_msg_maior_q, $limite);
            }
            catch (mysqli_sql_exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        echo json_encode(array(
            'erros' => $errors,
            'limite' => $limite,
            'mensagens' => $mensagens
        ));
    }
    else {
        echo "Sessão não iniciada";
    }
}
else {
    echo "Método HTTP deve ser GET";
}