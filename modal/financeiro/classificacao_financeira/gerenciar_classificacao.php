<?php
if (isset($_GET['form_id'])) {
   $form_id = $_GET['form_id'];
} else {
   $form_id = null;
}
//consultar informações para tabela
if (isset($_GET['consultar_classificacao_financeira'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   $consulta = $_GET['consultar_classificacao_financeira'];
   if ($consulta == "inicial") {
      $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1
      $select = "SELECT * from tb_classificacao_financeiro ";
      $consultar_classificacao = mysqli_query($conecta, $select);
      if (!$consultar_classificacao) {
         die("Erro na consulta" . mysqli_error($conecta));
      } else {
         $qtd = mysqli_num_rows($consultar_classificacao);
      }
   } else {
      $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
      $select = "SELECT * from tb_classificacao_financeiro
      WHERE cl_descricao like '%{$pesquisa}%' or cl_id like '%{$pesquisa}%' order by cl_id";
      $consultar_classificacao = mysqli_query($conecta, $select);
      if (!$consultar_classificacao) {
         die("Erro na consulta" . mysqli_error($conecta));
      } else {
         $qtd = mysqli_num_rows($consultar_classificacao);
      }
   }
}

// //cadastrar formulario
if (isset($_POST['formulario_classificacao_financeira'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";
   $retornar = array();
   $acao = $_POST['acao'];
   if ($acao == "show") {
      $id = $_POST['form_id'];
      $select = "SELECT * from tb_classificacao_financeiro WHERE cl_id = $id";
      $consultar_classificacao = mysqli_query($conecta, $select);
      $linha = mysqli_fetch_assoc($consultar_classificacao);
      $descricao = utf8_encode($linha['cl_descricao']);
      $informacao = array(
         "descricao" => $descricao
      );

      $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
   }


   if ($acao == "create") {
      $usuario_id = verifica_sessao_usuario();
      $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");
      $descricao = utf8_decode($_POST['descricao']);

      if ($descricao == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("descricão"));
      } else {
         $insert = "INSERT INTO `tb_classificacao_financeiro` ( `cl_descricao` )
         VALUES ( '$descricao' )";
         $operacao_insert = mysqli_query($conecta, $insert);
         if ($operacao_insert) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Classificação financeira cadastrada com sucesso");
            $mensagem =  utf8_decode("Usuário $nome_usuario_logado cadastrou á classificação financeira $descricao");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
            $mensagem =  utf8_decode("Usuário $nome_usuario_logado, tentativa de inserir á classificação financeira $descricao sem sucesso");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }


   if ($acao == "update") { // EDITAR
      $usuario_id = verifica_sessao_usuario();
      $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");

      $id = $_POST['id'];
      $descricao = utf8_decode($_POST['descricao']);

      if ($descricao == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("descricão"));
      } else {
         $update = " UPDATE `tb_classificacao_financeiro` SET `cl_descricao` = '$descricao' WHERE `cl_id` = $id ";
         $operacao_update = mysqli_query($conecta, $update);
         if ($operacao_update) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Classificação financeira alterada com sucesso");
            $mensagem =  utf8_decode("Usuário $nome_usuario_logado, alterou a Classificação financeira de código $id");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
            $mensagem =  utf8_decode("Usuário $nome_usuario_logado, tentativa de alterar a Classificação financeira de código $id sem sucesso");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }

   if ($acao == "delete") { // remover
      $usuario_id = verifica_sessao_usuario();
      $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");

      $id = $_POST['form_id'];
      $descricao = consulta_tabela($conecta, "tb_classificacao_financeiro", "cl_id", $id, "cl_descricao");

      /*Verfica se tem algun registros dessa classificação financeira na tabela lançamentos financeiro */
      $select = "SELECT * FROM tb_lancamento_financeiro where cl_classificacao_id=$id";
      $consulta_financeiro_registros = mysqli_query($conecta, $select);
      $qtd_registros = mysqli_num_rows($consulta_financeiro_registros);


      if ($qtd_registros > 0) {
         $retornar["dados"] = array("sucesso" => false, "title" => "Não é possível remover essa classificação,
          pois existem registros financeiros que a estão utilizando");
      } else {
         $delete = "DELETE FROM tb_classificacao_financeiro WHERE `cl_id` = $id ";
         $operacao_delete = mysqli_query($conecta, $delete);
         if ($operacao_delete) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Classificação Financeira Removida com sucesso");
            $mensagem =  utf8_decode("Usuário $nome_usuario_logado, removeu a classificação financeira $descricao ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
            $mensagem =  utf8_decode("Usuário $nome_usuario_logado, tentativa de remover a Classificação financeira $descricao sem sucesso");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }
   echo json_encode($retornar);
}
