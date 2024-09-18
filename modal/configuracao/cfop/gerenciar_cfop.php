<?php

//consultar informações para tabela
if (isset($_GET['consultar_cfop'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   $consulta = $_GET['consultar_cfop'];
   if ($consulta == "inicial") {
      $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1
      $select = "SELECT * from tb_cfop ";
      $consultar_cfop = mysqli_query($conecta, $select);
      if (!$consultar_cfop) {
         die("Falha no banco de dados");
      } else {
         $qtd = mysqli_num_rows($consultar_cfop);
      }
   } else {
      $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
      $select = "SELECT * from tb_cfop
      WHERE cl_desc_cfop like '%{$pesquisa}%' or cl_codigo_cfop like '%{$pesquisa}%' ";
      $consultar_cfop = mysqli_query($conecta, $select);
      if (!$consultar_cfop) {
         die("Falha no banco de dados");
      } else {
         $qtd = mysqli_num_rows($consultar_cfop);
      }
   }
}

// //cadastrar formulario
if (isset($_POST['formulario_cfop'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";
   $retornar = array();
   $acao = $_POST['acao'];
   $id_user_logado = verifica_sessao_usuario();
   $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $id_user_logado, "cl_usuario");



   if ($acao == "create") {

      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = $value;
      }

      if ($cfop_saida == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("cfop"));
      } elseif ($descricao == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("descricao"));
      } else {
         $descricao = utf8_decode($descricao);
         $insert = "INSERT INTO `tb_cfop` (`cl_codigo_cfop`, `cl_desc_cfop`, `cl_cfop_entrada` )
         VALUES ( '$cfop_saida', '$descricao', '$cfop_entrada' )";
         $operacao_insert = mysqli_query($conecta, $insert);
         if ($operacao_insert) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Cfop cadastrado com sucesso");
            $mensagem =  utf8_decode("Usuário $nome_usuario_logado Adicionou o cfop $cfop_saida");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
            $mensagem =  utf8_decode("Usuário $nome_usuario_logado tentativa de cadastrar o cfop $cfop_saida sem sucesso");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }


   if ($acao == "update") { // EDITAR
      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = $value;
      }
      if ($cfop_saida == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("cfop"));
      } elseif ($descricao == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("descricao"));
      } else {
         $descricao = utf8_decode($descricao);

         $update = " UPDATE `tb_cfop` SET `cl_desc_cfop` = '$descricao', `cl_cfop_entrada` = '$cfop_entrada'
          WHERE `cl_id` = $id ";
         $operacao_update = mysqli_query($conecta, $update);
         if ($operacao_update) {
            $retornar["dados"] = array("sucesso" => true, "title" => "cfop alterada com sucesso");
            $mensagem =  utf8_decode("Usuário $nome_usuario_logado, alterou o cfop  $cfop_saida");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
            $mensagem =  utf8_decode("Usuário $nome_usuario_logado, tentativa de alterar o cfop $cfop_saida");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }
   if ($acao == "show") {
      $form_id = $_POST['form_id'];
      $select = "SELECT * from tb_cfop WHERE cl_id = $form_id ";
      $consultar_cfop = mysqli_query($conecta, $select);
      $linha = mysqli_fetch_assoc($consultar_cfop);
      $cfop_saida = ($linha['cl_codigo_cfop']);
      $cfop_entrada = ($linha['cl_cfop_entrada']);
      $descricao = utf8_encode($linha['cl_desc_cfop']);

      $informacao = array(
         "cfop_saida" => $cfop_saida,
         "cfop_entrada" => $cfop_entrada,
         "descricao" => $descricao,
      );

      $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
   }

   echo json_encode($retornar);
}




//consultar conta financeira
$select = "SELECT * from tb_conta_financeira ";
$consultar_conta_financeira = mysqli_query($conecta, $select);


//consultar conta financeira
$select = "SELECT * from tb_status_recebimento ";
$consultar_status_recebimento = mysqli_query($conecta, $select);

//consultar conta financeira
$select = "SELECT * from tb_classificacao_fpg ";
$consultar_classificao_fpg = mysqli_query($conecta, $select);

//consultar conta financeira
$select = "SELECT * from tb_tipo_pagamento ";
$consultar_tipo_pagamento = mysqli_query($conecta, $select);
