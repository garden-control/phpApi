<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["referer"])) {
        echo "Deve ser informado o url de retorno com o nome \"referer\"";
        die();
    }
    
    session_start();
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    $signup_data = [
        "username" => $username,
        "password" => $password
    ];
    $_SESSION["signup_data"] = $signup_data;

    require_once "includes/dbh.inc.php";
    require_once "includes/model.inc.php";
    require_once "includes/ctrl.inc.php";
    
    $errors = [];
    
    $control = new Control($conn, $username, $password);

    if ($control->is_input_empty()) {
        $errors[] = "Preencha todos os campos";
    }
    if ($control->is_username_taken()) {
        $errors[] = "Usuário já cadastrado";
    }
    if ($control->is_password_invalid()) {
        $errors[] = "A senha deve ter no mínimo " . $control->min_password_len() . " caracteres";
    }


    header("Location: ".$_POST["referer"]);

    if ($errors) {
        $_SESSION["signup_errors"] = $errors;
    }
    else {        
        unset($_SESSION["signup_data"]);
        
        $control->create_user();
        $control->login_user();
    }
    
    $conn = null;
    
    die();
}
?>