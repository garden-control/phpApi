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

        if (!isset($_GET["nome"])) {
            $errors[] = "Nome da estação não submetido";
        }
        if (!isset($_GET["localizacao"])) {
            $errors[] = "Localização da estação não submetida";
        }

        if (empty($errors)) {
            $nome = $_GET["nome"];
            $localizacao = $_GET["localizacao"];
            $id_estacao = criar_nova_estacao($conn, $localizacao, $nome);
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