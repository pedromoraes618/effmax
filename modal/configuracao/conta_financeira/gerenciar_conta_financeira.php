<?php

//consultar informações para tabela
if (isset($_GET['consultar_conta_financeira'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   $consulta = $_GET['consultar_conta_financeira'];
   if ($consulta == "inicial") {
      $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1
      $select = "SELECT * from tb_conta_financeira ";
      $consultar_conta_financeira = mysqli_query($conecta, $select);
      if (!$consultar_conta_financeira) {
         die("Falha no banco de dados");
      } else {
         $qtd = mysqli_num_rows($consultar_conta_financeira);
      }
   } else {
      $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
      $select = "SELECT * from tb_conta_financeira  WHERE cl_banco like '%{$pesquisa}%' or cl_id like '%{$pesquisa}%' ";
      $consultar_conta_financeira = mysqli_query($conecta, $select);
      if (!$consultar_conta_financeira) {
         die("Falha no banco de dados");
      } else {
         $qtd = mysqli_num_rows($consultar_conta_financeira);
      }
   }
}

// //cadastrar formulario
if (isset($_POST['formulario_conta_financeira'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";
   $retornar = array();
   $acao = $_POST['acao'];
   if ($acao == "show") {
      $id_conta_financeira = $_POST['conta_financeira_id'];
      $select = "SELECT * from tb_conta_financeira WHERE cl_id = $id_conta_financeira";
      $consultar_forma_pagamento = mysqli_query($conecta, $select);
      $linha = mysqli_fetch_assoc($consultar_forma_pagamento);
      $descricao = utf8_encode($linha['cl_banco']);
      $conta = $linha['cl_conta'];
      $digito_conta = $linha['cl_digito_conta'];
      $agencia = $linha['cl_agencia'];
      $numero_banco = $linha['cl_numero_banco'];

      $informacao = array(
         "descricao" => $descricao,
         "conta" => $conta,
         "digito_conta" => $digito_conta,
         "agencia" => $agencia,
         "numero_banco" => $numero_banco,
      );

      $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
   }


   if ($acao == "create") {
      $nome_usuario_logado = $_POST["nome_usuario_logado"];
      $id_usuario_logado = $_POST["id_usuario_logado"];
      $perfil_usuario_logado = $_POST['perfil_usuario_logado'];

      $descricao = utf8_decode($_POST['descricao']);
      $conta = ($_POST['conta']);
      $digito_conta = ($_POST['digito_conta']);
      $agencia = ($_POST['agencia']);
      $numero_banco = ($_POST['numero_banco']);

      if ($descricao == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("descricão"));
      }elseif($conta ==""){
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("conta"));
      } else {
         $insert = "INSERT INTO `tb_conta_financeira` ( `cl_banco`, `cl_conta`, `cl_digito_conta`, `cl_agencia`, `cl_numero_banco`) 
         VALUES ( '$descricao', '$conta', '$digito_conta', '$agencia', '$numero_banco')";
         $operacao_insert = mysqli_query($conecta, $insert);
         if ($operacao_insert) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Conta fiananceira cadastrada com sucesso");
            $mensagem =  (utf8_decode("Usúario") . " $nome_usuario_logado Adicionou a conta financeira $descricao");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro insert banco de dados, favor contatar o suporte");
            $mensagem =  (utf8_decode("Usúario") . " $nome_usuario_logado tentativa de inserir conta financeira  sem sucesso");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }


   if ($acao == "update") { // EDITAR
      $nome_usuario_logado = $_POST["nome_usuario_logado"];
      $id_usuario_logado = $_POST["id_usuario_logado"];
      $perfil_usuario_logado = $_POST['perfil_usuario_logado'];

      $id = $_POST['id'];
      $descricao = utf8_decode($_POST['descricao']);
      // $conta = ($_POST['conta']);
      $digito_conta = ($_POST['digito_conta']);
      $agencia = ($_POST['agencia']);
      $numero_banco = ($_POST['numero_banco']);


      // $ativo = $_POST['ativo'];
      if ($descricao == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("descricão"));
      }  else {

         $update = "UPDATE `tb_conta_financeira` SET `cl_banco` = '$descricao', `cl_digito_conta` = '$digito_conta', `cl_agencia` = '$agencia',
          `cl_numero_banco` = '$numero_banco' WHERE `tb_conta_financeira`.`cl_id` = $id ";
         $operacao_update = mysqli_query($conecta, $update);
         if ($operacao_update) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Conta financeira alterada com sucesso");
            $mensagem =  (utf8_decode("Usúario") . " $nome_usuario_logado alterou a conta financeira codigo $id");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro update banco de dados, favor contatar o suporte");
            $mensagem =  (utf8_decode("Usúario") . " $nome_usuario_logado tentativa de alterar a conta financeira $descricao sem sucesso");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }

   echo json_encode($retornar);
}

