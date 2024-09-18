<?php
if (isset($_GET['consultar_log'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $consulta = $_GET['consultar_log'];
    if ($consulta == "inical") {
        $data_incial  = date('Y-m-01 01:01:01');
        $data_final = date('Y-m-t 23:59:59');
        $select = "SELECT log.cl_data_modificacao , log.cl_usuario,log.cl_descricao from tb_log as log where log.cl_data_modificacao 
    between '$data_incial' and '$data_final' ORDER BY log.cl_data_modificacao desc";
        $consultar_log = mysqli_query($conecta, $select);
        if (!$consultar_log) {
            die("Falha no banco de dados"); // colocar o svg do erro
        }
    } elseif ($consulta == "detelhado") {
        //pegar as data pelo filtro // FILTRO PELA DATA USUARIO E DESCRICAO
        $data_incial = $_GET['data_inicial'];
        $data_final = $_GET['data_final'];


        $data_incial = ($data_incial . ' 01:01:01');
        $data_final = ($data_final . ' 23:59:59');

        $usuario = $_GET['usuario'];
        $conteudo = utf8_decode($_GET['conteudo']);

        $select = "SELECT log.cl_data_modificacao , log.cl_usuario,log.cl_descricao from tb_log as log where 
        log.cl_data_modificacao 
    between '$data_incial' and '$data_final' and log.cl_descricao LIKE '%{$conteudo}%' ";
        if ($usuario != "0") {
            $select .= " and log.cl_usuario = '$usuario'  ORDER BY log.cl_data_modificacao desc ";
        } else {
            $select .= " ORDER BY log.cl_data_modificacao desc ";
        }
        $consultar_log = mysqli_query($conecta, $select);
        if (!$consultar_log) {
            die("Falha no banco de dados"); // colocar o svg do erro
        }
    }
}
