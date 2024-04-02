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
        $nome = "";
        $localizacao = "";

        if (!isset($_GET["id_estacao"])) {
            $errors[] = "ID de estação não submetido";
        }
        if (!isset($_GET["nome"])) {
            $errors[] = "Nome de estação não submetido";
        }

        if (empty($errors)) {
            $id_estacao = $_GET["id_estacao"];
            $nome = $_GET["nome"];
            
            if (!add_estacao($conn, $id_estacao, $nome)) {
                $errors[] = "ID de estação já adicionado";
                $nome = get_usuario_estacao($conn, $id_estacao)["nome"];
            }
            $localizacao = get_estacao($conn, $id_estacao)["localizacao"];
        }

        ?>
        {
            "erros": <?php jsonErrors($errors) ?>,
            "id": <?php echo '"'.$id_estacao.'"' ?>,
            "nome": <?php echo '"'.$nome.'"' ?>,
            "localizacao": <?php echo '"'.$localizacao.'"' ?>
        }
        <?php 
    }
    else {
        echo "Sessão não iniciada";
    }
}
else {
    echo "Método HTTP deve ser GET";
}