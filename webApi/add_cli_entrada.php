<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "includes/environments.inc.php";

    if (isset($_SESSION[SESSION_KEY_USER_ID])) {
        require_once "includes/dbh.inc.php";
        require_once "includes/model.inc.php";
        require_once "includes/view.inc.php";
        require_once "includes/ctrl.inc.php";

        $errors = [];
        $id_estacao = "";
        $mensagem = "";

        if (!isset($_POST["id_estacao"])) {
            $errors[] = "ID da estação não submetido";
        }
        if (!isset($_POST["mensagem"])) {
            $errors[] = "Mensagem não submetida";
        }

        if (empty($errors)) {
            $id_estacao = $_POST["id_estacao"];
            $mensagem = $_POST["mensagem"];

            try {
                add_cli_entrada($conn, $id_estacao, $mensagem);
            }
            catch (mysqli_sql_exception $e) {
                $erros[] = $e->getMessage();
            }
        }
        echo json_encode(array('erros' => $errors));
    }
    else {
        echo "Sessão não iniciada";
    }
}
else {
    echo "Método HTTP deve ser POST";
}