<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    $username = $_POST["username"];
    $password = $_POST["password"];

    require_once "includes/dbh.inc.php";
    require_once "includes/model.inc.php";
    require_once "includes/view.inc.php";
    require_once "includes/ctrl.inc.php";
    
    $errors = [];
    
    $control = new UserControl($conn, $username, $password);

    if ($control->is_input_empty()) {
        $errors[] = "Preencha todos os campos";
    }
    if ($control->is_username_nonexistent()) {
        $errors[] = "Usuário não existe";
    }
    else if ($control->is_password_incorret()) {
        $errors[] = "Senha incorreta";
    }

    if (empty($errors)) {
        $control->login_user();
    }

    echo '{"erros": ';
    jsonErrors($errors);
    echo '}';
    
    $conn = null;
    
    die();
}
?>