<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
//consultar cidade

if (isset($_POST['consultar_cidade'])) {
    $estado_id = $_POST['estado_id'];
    $retornar = array();
    $array_dados = array();
    $informacao = array();
    $select = "SELECT * from tb_cidades WHERE cl_estado_id = $estado_id";
    $consultar_cidades = mysqli_query($conecta, $select);

    if ($consultar_cidades) {
        while ($linha  = mysqli_fetch_assoc($consultar_cidades)) {
            $cidade_b = utf8_encode($linha['cl_nome']);
            $cidade_id = ($linha['cl_id']);
            $codigo_ibge = ($linha['cl_ibge']);

            $informacao = array(
                "cidade" => $cidade_b,
                'cidade_id' => $cidade_id,
                'codigo_ibge'=>$codigo_ibge
            );
            array_push($array_dados, $informacao);
        }

        $retornar["dados"] = array("sucesso" => true, "informacao" => $array_dados);
    };


    echo json_encode($retornar);
}
