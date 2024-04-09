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
        $localizacao = "";
        $nome = "";

        if (!isset($_GET["id_estacao"])) {
            $errors[] = "ID de estação não submetido";
        }

        if (empty($errors)) {
            $id_estacao = $_GET["id_estacao"];
            
            add_usuario_estacao($errors, $conn, $id_estacao);
            
            $estacao = get_estacao($conn, $id_estacao);
            $localizacao = $estacao["localizacao"];
            $nome = $estacao["nome"];
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