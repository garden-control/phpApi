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
        "id": <?php echo $estacoes->id ?>,
        "nome": <?php echo $estacoes->nome ?>,
        "localizacao": "<?php echo $estacoes->localizacao ?>"
    }
<?php
}

function jsonEstacoes(array $estacoes): void {
    echo '[';
    $estacoes = get_estacoes($conn, isset($_GET["limite"]) ? $_GET["limite"] : 10);
    for ($i = 0; $i < count($estacoes); $i++) {
        jsonEstacao($estacoes[i]);
        echo ($i == count($estacoes) - 1 ? "" : ",");
    }
    echo ']';
}

function jsonLeitura(Leitura $leitura): void {
    ?>{
        "id": <?php echo $leituras[$i]->id ?>,
        "id_estacao": <?php echo $leituras[$i]->id_estacao ?>,
        "umidade_ar": <?php echo $leituras[$i]->umidade_ar ?>,
        "temperatura": <?php echo $leituras[$i]->temperatura ?>,
        "umidade_solo": <?php echo $leituras[$i]->umidade_solo ?>,
        "pluv_indice": <?php echo $leituras[$i]->pluv_indice ?>,
        "horario": <?php echo '"'.$leituras[$i]->horario->format("H:i:s d-m-y").'"' ?>
    }<?php
}

function jsonLeituras(array $leituras): void {
    echo '[';     
    for ($i = 0; $i < count($leituras); $i++) {
        jsonLeitura($leituras[i]);

        echo $i === count($leituras) - 1 ? "" : ",";
    }
    echo ']';
}