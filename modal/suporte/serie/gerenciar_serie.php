<?php
//consultar informações para tabela
if (isset($_GET['consultar_serie'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $consulta = $_GET['consultar_serie'];
    if ($consulta == "inicial") {
        $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1

        $select = "SELECT * from tb_serie order by cl_id";
        $consultar_serie = mysqli_query($conecta, $select);
        if (!$consultar_serie) {
            die("Falha no banco de dados");
        }
    } else {
        $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
        $select = "SELECT * from tb_serie where cl_descricao like '%{$pesquisa}%'  order by cl_id";
        $consultar_serie = mysqli_query($conecta, $select);
        if (!$consultar_serie) {
            die("Falha no banco de dados");
        }
    }
}

//cadastrar formulario
if (isset($_POST['formulario_cadastrar_serie'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $nome_usuario_logado = $_POST["nome_usuario_logado"];
    $id_usuario_logado = $_POST["id_usuario_logado"];
    $perfil_usuario_logado = $_POST['perfil_usuario_logado'];

    $descricao = utf8_decode($_POST["descricao"]);
    $valor = utf8_decode($_POST["valor"]);
    $informacao = utf8_decode($_POST["informacao"]);
    $serie_fiscal = ($_POST['serie_fiscal']);
    $serie_recibo = ($_POST['serie_recibo']);

    if ($descricao == "") {

        $retornar["dados"] = array("sucesso" => "false", "title" => mensagem_alerta_cadastro("descricão"));
    } elseif ($valor == "") {
        $retornar["dados"] = array("sucesso" => "false", "title" => mensagem_alerta_cadastro("valor"));
    } elseif ($perfil_usuario_logado != "suporte") {
        $retornar["dados"] = array("sucesso" => "false", "title" => mensagem_alerta_permissao());
    } elseif ($serie_fiscal == "0") {
        $retornar["dados"] = array("sucesso" => "false", "title" => mensagem_alerta_cadastro("série fiscal"));
    } 
    // elseif ($serie_recibo == "SIM" and $serie_fiscal == "SIM") {
    //     $retornar["dados"] = array("sucesso" => "false", "title" => "Não é possivel definir a série como fiscal e recibo, favor verifique");
    // } 
    else {
        $inset = "INSERT INTO tb_serie (cl_descricao,cl_valor,cl_informacao,cl_serie_fiscal,cl_serie_recibo )
         VALUES ( '$descricao','$valor','$informacao','$serie_fiscal','$serie_recibo' )";
        $operacao_inserir = mysqli_query($conecta, $inset);
        if ($operacao_inserir) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Cadastrado realizado com sucesso");

            //registrar no log
            $mensagem =  (utf8_decode("Usúario") . " $nome_usuario_logado cadastrou a serie $descricao ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }

    echo json_encode($retornar);
}


// //Editar formulario
if (isset($_POST['formulario_editar_serie'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();

    $nome_usuario_logado = $_POST["nome_usuario_logado"];
    $id_usuario_logado = $_POST["id_usuario_logado"];
    $perfil_usuario_logado = $_POST['perfil_usuario_logado'];

    $id_serie = $_POST["id_serie"];
    // $descricao = utf8_decode($_POST["descricao"]);
    $valor = utf8_decode($_POST["valor"]);
    $informacao = utf8_decode($_POST["informacao"]);
    $serie_fiscal = ($_POST['serie_fiscal']);
    $serie_recibo = ($_POST['serie_recibo']);

    if ($valor == "") {
        $retornar["dados"] = array("sucesso" => "false", "title" => mensagem_alerta_cadastro("valor"));
    } elseif ($perfil_usuario_logado != "suporte") {
        $retornar["dados"] = array("sucesso" => "false", "title" => mensagem_alerta_permissao());
    } elseif ($serie_fiscal == "0") {
        $retornar["dados"] = array("sucesso" => "false", "title" => mensagem_alerta_cadastro("serie fiscal"));
    }
    //  elseif ($serie_recibo == "SIM" and $serie_fiscal == "SIM") {
    //     $retornar["dados"] = array("sucesso" => "false", "title" => "Não é possivel definir a série como fiscal e recibo, favor verifique");
    // }
     else {

        $update = "UPDATE tb_serie set cl_informacao = '$informacao',
        cl_valor='$valor',cl_serie_fiscal='$serie_fiscal',cl_serie_recibo='$serie_recibo' where cl_id = $id_serie";
        $operacao_update = mysqli_query($conecta, $update);
        if ($operacao_update) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Serie alterada com sucesso");
            //registrar no log
            $mensagem =  (utf8_decode("Usúario") . " $nome_usuario_logado alterou dados da serie $id_serie");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }
    echo json_encode($retornar);
}

//trazer informaçãoes
if (isset($_GET['editar_serie']) == true) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $id_serie = $_GET['id_serie'];
    $select = "SELECT * from tb_serie where cl_id = $id_serie";
    $consultar_serie = mysqli_query($conecta, $select);
    $linha  = mysqli_fetch_assoc($consultar_serie);
    $descricao_b = utf8_encode($linha['cl_descricao']);
    $valor_b = ($linha['cl_valor']);
    $informacao_b = utf8_encode($linha['cl_informacao']);
    $serie_fiscal = ($linha['cl_serie_fiscal']);
    $serie_recibo = ($linha['cl_serie_recibo']);
}
