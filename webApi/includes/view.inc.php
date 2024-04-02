<?php

declare(strict_types=1);

require_once "model.inc.php";

function jsonErrors(array $errors): void {
    echo '[';
    if ($errors) {
        for ($i = 0; $i < count($errors) - 1; $i++) {
            echo '"'.$errors[$i].'",';
        }
        echo '"'.$errors[count($errors) - 1].'"';
    }
    echo ']';
}


function jsonEstacao(Estacao $estacao): void {
?>
    {
        "id": "<?php echo $estacao->id ?>",
        "nome": "<?php echo $estacao->nome ?>",
        "localizacao": "<?php echo $estacao->localizacao ?>"
    }
<?php
}

function jsonEstacoes(array $estacoes): void {
    echo '[';
    for ($i = 0; $i < count($estacoes); $i++) {
        jsonEstacao($estacoes[$i]);
        echo ($i == count($estacoes) - 1 ? "" : ",");
    }
    echo ']';
}

function jsonLeitura(Leitura $leitura): void {
    ?>{
        "id": <?php echo $leitura->id ?>,
        "id_estacao": <?php echo $leitura->id_estacao ?>,
        "umidade_ar": <?php echo $leitura->umidade_ar ?>,
        "temperatura": <?php echo $leitura->temperatura ?>,
        "umidade_solo": <?php echo $leitura->umidade_solo ?>,
        "pluv_indice": <?php echo $leitura->pluv_indice ?>,
        "horario": <?php echo '"'.$leitura->horario->format("H:i:s d-m-y").'"' ?>
    }<?php
}

function jsonLeituras(array $leituras): void {
    echo '[';     
    for ($i = 0; $i < count($leituras); $i++) {
        jsonLeitura($leituras[$i]);

        echo $i === count($leituras) - 1 ? "" : ",";
    }
    echo ']';
}