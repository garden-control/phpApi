<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    require_once "includes/environments.inc.php";
    
    if (isset($_SESSION[SESSION_KEY_USER_ID])) {
        require_once "includes/dbh.inc.php";
        require_once "includes/model.inc.php";
        require_once "includes/view.inc.php";
        
        $estacoes = get_estacoes($conn, isset($_GET["limite"]) ? $_GET["limite"] : 10);
        
        echo '{"estacoes": ';
        jsonEstacoes($estacoes);
        echo '}';      
    }
    else {
        echo "Sessão não iniciada";
    }
}
else {
    echo "Método HTTP deve ser GET";
}