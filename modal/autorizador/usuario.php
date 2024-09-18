<?php


//cadastrar usuario
if (isset($_POST['autorizar_acao'])) {
    include "../../conexao/conexao.php";
    include "../../funcao/funcao.php";
    $retornar = array();
    $acao = $_POST['acao'];
    // $tela = $_POST['tela'];

    $id_usuario = $_POST['usuario_id'];
    $senha = $_POST['senha'];

    if ($acao == "validar_usuario") {
        if (validar_usuario($conecta, $id_usuario, $senha) == false) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Senha incorreta, autorização não permitida"); //alertar o usuario que o caixa está fechado
        } else {
            $retornar["dados"] = array("sucesso" => true); //alertar o usuario que o caixa está fechado
        }
    }

    echo json_encode($retornar);
}


if (isset($_GET['autorizar_acao']) and isset($_GET['acao'])) {
    $acao = $_GET['acao'];
    if ($acao == "cancelar_pedido_delivery") { //caneclamento de pedido delivery
        $select = "SELECT * from tb_users where cl_cancelar_pedido_delivery ='SIM' and cl_ativo ='1'  ";
        $consultar_usuarios_autorizados = mysqli_query($conecta, $select);
    } elseif ($acao == "cancelar_nf" or $acao == "cancelar_os" or $acao == "cancelar_pedido" or $acao == "cancelar_cotacao") { //cancelar nf
        $select = "SELECT * from tb_users where cl_cancelar_venda ='SIM' and cl_ativo ='1'  ";
        $consultar_usuarios_autorizados = mysqli_query($conecta, $select);
    } elseif ($acao == "autorizar_cancelamento_financeiro") { //cancelar lancamento_financeiro
        $select = "SELECT * from tb_users where cl_cancelar_financeiro ='SIM' and cl_ativo ='1'  ";
        $consultar_usuarios_autorizados = mysqli_query($conecta, $select);
    } elseif ($acao == "autorizar_remover_faturamento_nf") { //cancelar lancamento_financeiro
        $select = "SELECT * from tb_users where cl_remover_faturamento ='SIM' and cl_ativo ='1'  ";
        $consultar_usuarios_autorizados = mysqli_query($conecta, $select);
    }
} else { //autorização de desconto
    $select = "SELECT * from tb_users where cl_autorizar_desconto ='SIM' and cl_ativo ='1' ";
    $consultar_usuarios_autorizados = mysqli_query($conecta, $select);
}
