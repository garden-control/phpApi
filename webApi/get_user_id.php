<?php
    
    require_once "includes/environments.inc.php";
    
    session_start();

    if (isset($_SESSION[SESSION_KEY_USER_ID])) {
        
        require_once "includes/dbh.inc.php";
        
        
        $user_id = $_SESSION[SESSION_KEY_USER_ID];
        
        echo '{"erro": "", "user_id": '.$user_id.'}';
    }
    else {
        echo '{ "erro": "Sessão não iniciada" }';
    }