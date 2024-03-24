<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["referer"])) {
        echo "Deve ser informado o url de retorno com o nome \"referer\"";
        die();
    }

    session_start();
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    $login_data = [
        "username" => $username,
        "password" => $password
    ];
    $_SESSION["login_data"] = $login_data;

    require_once "includes/dbh.inc.php";
    require_once "includes/model.inc.php";
    require_once "includes/ctrl.inc.php";
    
    $errors = [];
    
    $control = new Control($conn, $username, $password);

    if ($control->is_input_empty()) {
        $errors[] = "Preencha todos os campos";
    }
    if ($control->is_username_nonexistent()) {
        $errors[] = "Usuário não existe";
    }
    else if ($control->is_password_incorret()) {
        $errors[] = "Senha incorreta";
    }

    header("Location: ".$_POST["referer"]);

    if ($errors) {
        $_SESSION["login_errors"] = $errors;
    }
    else {
        unset($_SESSION["login_data"]);
        $control->login_user();
    }
    $conn = null;
    
    die();
}
?>