<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {    
    session_start();
    $username = $_POST["username"];
    $password = $_POST["password"];

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


    if (empty($errors)) {                
        $control->create_user();
        $control->login_user();
    }

    ?>
    {
        "erros": [<?php
            if ($errors) {

                for ($i = 0; $i < count($errors) - 1; $i++) {
                    echo '"'.$errors[$i].'",';
                }
                echo '"'.$errors[count($errors) - 1].'"';
            }
                
            ?>]
    }
    <?php
    
    $conn = null;
    
    die();
}
?>