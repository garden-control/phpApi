<?php
    //retornar json no formato {"sucesso": bool, "username": "", "erros": ""[]}
    session_start();

    require_once "includes/environments.inc.php";

    header("Content-Type: application/json");
?>
{
    "sucesso": <?php echo isset($_SESSION[SESSION_KEY_USER_ID]) ? "true" : "false" ?>,
    "username": "<?php isset($_SESSION["login_data"]) ? $_SESSION["login_data"]["username"] : "" ?>",
    "erros": [<?php
            if (isset($_SESSION["login_errors"])) {
                $errors = $_SESSION["login_errors"];
        
                for ($i = 0; $i < count($errors) - 1; $i++) {
                    echo '"'.$errors[$i].'",';
                }
                echo '"'.$errors[count($errors) - 1].'"';
        
                unset($_SESSION["login_errors"]);
            }
        ?>]
}