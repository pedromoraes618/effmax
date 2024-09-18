<?php
//consultar parametro
if (isset($_GET['consultar_parametro'])) {
    $consulta = $_GET['consultar_parametro'];
    if ($consulta == "inicial") {

        $select = "SELECT * from tb_parametros";
        $consultar_parametros = mysqli_query($conecta, $select);
        if (!$consultar_parametros) {
            die("Falha no banco de dados"); // colocar o svg do erro
        }
    } else {
        $pesquisa = utf8_decode($_GET['conteudo_pesquisa']);
        $configuracao = ($_GET['conteudo_configuracao']);

        $select = "SELECT * from tb_parametros where (cl_descricao like '%{$pesquisa}%' or cl_valor like '%{$pesquisa}%' ) ";
        if ($configuracao != "0") {
            $select .= " and cl_configuracao = '$configuracao'";
        }
        $consultar_parametros = mysqli_query($conecta, $select);
        if (!$consultar_parametros) {
            die("Falha no banco de dados"); // colocar o svg do erro
        }
    }
}

//cadastrar parametro
if (isset($_POST['formulario_cadastrar_parametro'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $nome_usuario_logado = $_POST["nome_usuario_logado"];
    $id_usuario_logado = $_POST["id_usuario_logado"];
    $perfil_usuario_logado = $_POST['perfil_usuario_logado'];

    $descricao = utf8_decode($_POST["descricao"]);
    $valor = utf8_decode($_POST["valor"]);
    $configuracao = $_POST["configuracao"];


    if ($descricao == "") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("descricao");
    } elseif ($configuracao == "0") {
        $retornar["mensagem"] = "Favor selecione a configuração do parâmetro";
    } elseif ($perfil_usuario_logado != "suporte") {
        $retornar["mensagem"] = mensagem_alerta_permissao();
    } else {

        $inset = "INSERT INTO tb_parametros (cl_descricao,cl_valor,cl_configuracao) VALUES ('$descricao','$valor','$configuracao')";
        $operacao_inserir = mysqli_query($conecta, $inset);
        if ($operacao_inserir) {
            $retornar["sucesso"] = true;
            //registrar no log
            $mensagem =  (utf8_decode("Usúario") . " $nome_usuario_logado cadastrou o parametro  $descricao ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }

    echo json_encode($retornar);
}

//Editar parametro
if (isset($_POST['formulario_editar_parametro'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $nome_usuario_logado = $_POST["nome_usuario_logado"];
    $id_usuario_logado = $_POST["id_usuario_logado"];
    $perfil_usuario_logado = $_POST['perfil_usuario_logado'];

    $id_parametro = $_POST["id_parametro"];
    $descricao = utf8_decode($_POST["descricao"]);
    $valor = utf8_decode($_POST["valor"]);
    $configuracao = $_POST["configuracao"];

    if ($descricao == "") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("descricao");
    } elseif ($configuracao == "0") {
        $retornar["mensagem"] = "Favor selecione a configuração do parâmetro";
    } elseif ($perfil_usuario_logado != "suporte") { // verificar se o usuario tem permissão para realizar alção nessa tela
        $retornar["mensagem"] = mensagem_alerta_permissao();;
    } else {
        $update = "UPDATE tb_parametros set cl_descricao = '$descricao', cl_valor = '$valor', cl_configuracao = '$configuracao' where cl_id = $id_parametro";
        $operacao_update = mysqli_query($conecta, $update);
        if ($operacao_update) {
            $retornar["sucesso"] = true;
            //registrar no log
            $mensagem =  utf8_decode("Alterou dados do parâmetro de código $id_parametro");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }
    echo json_encode($retornar);
}

//trazer informaçãoes do parametro
if (isset($_GET['editar_parametro']) == true) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $id_parametro = $_GET['id_parametro'];
    $select = "SELECT * from tb_parametros where cl_id = $id_parametro";
    $consultar_parametros = mysqli_query($conecta, $select);
    $linha  = mysqli_fetch_assoc($consultar_parametros);
    $descricao_b = utf8_encode($linha['cl_descricao']);
    $valor_b = utf8_encode($linha['cl_valor']);
    $configuracao_b = $linha['cl_configuracao'];
}
