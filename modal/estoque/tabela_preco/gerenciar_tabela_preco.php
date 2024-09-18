<?php


// //cadastrar formulario
if (isset($_POST['formulario_tabela_preco'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";
   $retornar = array();
   $acao = $_POST['acao'];
   $id_user_logado = verifica_sessao_usuario();
   $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $id_user_logado, "cl_usuario");




   if ($acao == "update") { // EDITAR
      // Inicia uma transação
      mysqli_begin_transaction($conecta);
      $erro = false;

      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = $value;
      }
      if ($item_id == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "O produto não foi encontrado, favor, verifique");
      } else {

         $resultados = consulta_linhas_tb_filtro($conecta, 'tb_forma_pagamento', "cl_ativo", "S");
         if ($resultados) {
            foreach ($resultados as $linha) {
               $id = $linha['cl_id']; //id da forma de pagamento
               if (isset($_POST["fpg$id"])) { //verifica se 
                  $valor = $_POST["fpg$id"];
                  if ($valor != "") {
                     if (verificaVirgula($valor)) { //verificar se tem virgula
                        $valor = formatDecimal($valor); // formatar virgula para ponto
                     }
                     $id_tabela_preco = consulta_tabela_2_filtro($conecta, "tb_tabela_preco", "cl_produto_id", $item_id, 'cl_forma_pagamento_id', $id, "cl_id");
                     if ($id_tabela_preco != "") { //já existe uma tabela de preço para essa forma de pagamento //update
                        $update = "UPDATE `tb_tabela_preco` SET `cl_valor` = '$valor',
                      `cl_ultima_atualizacao` = '$data' WHERE `cl_id` = $id_tabela_preco ";
                        $operacao_update = mysqli_query($conecta, $update);
                        if (!$operacao_update) {
                           break;
                           $erro = true;
                        }
                     } else { //insert
                        $insert = "INSERT INTO `tb_tabela_preco` ( `cl_forma_pagamento_id`, `cl_produto_id`, `cl_valor`, `cl_ultima_atualizacao`) 
                     VALUES ('$id','$item_id', '$valor', '$data')";
                        $operacao_insert = mysqli_query($conecta, $insert);
                        if (!$operacao_insert) {
                           break;
                           $erro = true;
                        }
                     }
                  }
               }
            }
         }



         if ($erro) {
            mysqli_rollback($conecta); // Desfaz a transação em caso de erro
            $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor, contatar o suporte");
            //registrar no log
            $mensagem =  utf8_decode("Erro, tentativa sem sucesso de atualizar a tabela de preço do produto de código $item_id");
         } else {

            mysqli_commit($conecta); // Confirma a transação se tudo ocorreu bem
            $retornar["dados"] =  array("sucesso" => true, "title" => "Tabela de preço alterada com sucesso");
            $mensagem =  utf8_decode("Usuário $nome_usuario_logado alterou a tabela de preço do produto de código $item_id");
         }
         registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
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
