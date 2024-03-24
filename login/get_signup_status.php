<?php
    //retornar json no formato {"sucesso": bool, "username": "", "erros": ""[]}
    session_start();

    require_once "includes/environments.inc.php";

    header("Content-Type: application/json");
?>
{
    "sucesso": <?php echo isset($_SESSION[SESSION_KEY_USER_ID]) ? "true" : "false" ?>,
    "username": "<?php isset($_SESSION["signup_data"]) ? $_SESSION["signup_data"]["username"] : '""' ?>",
    "erros": [<?php
            if (isset($_SESSION["signup_errors"])) {
                $errors = $_SESSION["signup_errors"];
        
                for ($i = 0; $i < count($errors) - 1; $i++) {
                    echo '"'.$errors[$i].'",';
                }
                echo '"'.$errors[count($errors) - 1].'"';
        
                unset($_SESSION["signup_errors"]);
            }
        ?>]
}