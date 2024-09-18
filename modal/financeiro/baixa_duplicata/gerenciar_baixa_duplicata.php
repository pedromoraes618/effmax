<?php
if (isset($_GET['form_id'])) {
   $form_id = $_GET['form_id'];
} else {
   $form_id = null;
}
//consultar informações para tabela
if (isset($_GET['consultar_baixa_duplicata'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   $consulta = $_GET['consultar_baixa_duplicata'];
   $data_inicial = $_GET['data_inicial'];
   $data_final = $_GET['data_final'];



   if ($consulta == "inicial") {
      $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1
      $select = "SELECT  DATEDIFF(CURRENT_DATE(),lcf.cl_data_vencimento) as atraso, pgt.cl_descricao as pagamento,  cfc.cl_descricao as classificacao, lcf.cl_valor_bruto, lcf.cl_id, lcf.cl_data_vencimento,star.cl_descricao as status, lcf.cl_tipo_lancamento, lcf.cl_data_pagamento,lcf.cl_descricao as descricao,fpg.cl_descricao as formapgt,
      lcf.cl_data_lancamento,lcf.cl_documento,lcf.cl_valor_liquido,ctf.cl_banco,parc.cl_razao_social,parc.cl_nome_fantasia,star.cl_descricao 
      FROM tb_lancamento_financeiro as lcf inner join tb_conta_financeira 
      as ctf on ctf.cl_conta = lcf.cl_conta_financeira inner join tb_parceiros as parc on
      parc.cl_id = lcf.cl_parceiro_id inner join tb_status_recebimento as star on star.cl_id = lcf.cl_status_id inner join 
      tb_forma_pagamento as fpg on fpg.cl_id = lcf.cl_forma_pagamento_id inner join tb_classificacao_financeiro as cfc on cfc.cl_id = lcf.cl_classificacao_id inner join tb_forma_pagamento as pgt on pgt.cl_id = lcf.cl_forma_pagamento_id 
      WHERE lcf.cl_data_vencimento between '$data_inicial' and '$data_final' and lcf.cl_status_id = '1' order by lcf.cl_data_vencimento desc";
      $consultar_baixa_duplicata = mysqli_query($conecta, $select);
      if (!$consultar_baixa_duplicata) {
         die("Falha no banco de dados");
      } else {
         $qtd = mysqli_num_rows($consultar_baixa_duplicata); //quantidade de registros
      }
   } else {
      $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
      $classificao_financeiro = $_GET['classificao_financeiro']; //v
      $status_pagamento = utf8_decode($_GET['status_pagamento']); //filtro
      $pagamento = ($_GET['pagamento']); //filtro

      $select = "SELECT DATEDIFF(CURRENT_DATE(),lcf.cl_data_vencimento) as atraso, pgt.cl_descricao as pagamento, cfc.cl_descricao as classificacao, lcf.cl_valor_bruto, lcf.cl_id, lcf.cl_data_vencimento,star.cl_descricao as status, lcf.cl_tipo_lancamento, lcf.cl_data_pagamento,lcf.cl_descricao as descricao,fpg.cl_descricao as formapgt,
      lcf.cl_data_lancamento,lcf.cl_documento,lcf.cl_valor_liquido,ctf.cl_banco,parc.cl_razao_social,parc.cl_nome_fantasia,star.cl_descricao 
      FROM tb_lancamento_financeiro as lcf inner join tb_conta_financeira 
      as ctf on ctf.cl_conta = lcf.cl_conta_financeira inner join tb_parceiros as parc on
      parc.cl_id = lcf.cl_parceiro_id inner join tb_status_recebimento as star on star.cl_id = lcf.cl_status_id inner join
       tb_forma_pagamento as fpg on fpg.cl_id = lcf.cl_forma_pagamento_id inner join tb_classificacao_financeiro as cfc on cfc.cl_id = lcf.cl_classificacao_id
        inner join tb_forma_pagamento as pgt on pgt.cl_id = lcf.cl_forma_pagamento_id 
      WHERE lcf.cl_data_vencimento between '$data_inicial' and '$data_final' and 
       (lcf.cl_descricao  like '%$pesquisa%' or parc.cl_razao_social  like '%$pesquisa%' or parc.cl_nome_fantasia  like '%$pesquisa%' or parc.cl_cnpj_cpf 
        like '%$pesquisa%' or lcf.cl_documento like '%$pesquisa%') ";
      if ($classificao_financeiro != "0") {
         $select .= " and lcf.cl_classificacao_id = '$classificao_financeiro' ";
      }
      if ($pagamento != "0") {
         $select .= " and lcf.cl_forma_pagamento_id = '$pagamento' ";
      }
      if ($status_pagamento != "0") {
         $select .= " and lcf.cl_status_id = '$status_pagamento' ";
      }
      $select .= " order by lcf.cl_data_vencimento desc";

      $consultar_baixa_duplicata = mysqli_query($conecta, $select);
      if (!$consultar_baixa_duplicata) {
         die("Falha no banco de dados" . mysqli_error($conecta));
      } else {
         $qtd = mysqli_num_rows($consultar_baixa_duplicata);
      }
   }
}

//consultar informações para tabela
if (isset($_GET['tabela_baixa_parcial'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   $form_id = $_GET['form_id'];
   $select = "SELECT lcf.cl_status_id, pgt.cl_descricao as pagamento, cfc.cl_descricao as classificacao, lcf.cl_valor_bruto, lcf.cl_id, lcf.cl_data_vencimento,star.cl_descricao as status, lcf.cl_tipo_lancamento, lcf.cl_data_pagamento,lcf.cl_descricao as descricao,fpg.cl_descricao as formapgt,
      lcf.cl_data_lancamento,lcf.cl_documento,lcf.cl_valor_liquido,ctf.cl_banco,parc.cl_razao_social,parc.cl_nome_fantasia,star.cl_descricao 
      FROM tb_lancamento_financeiro as lcf inner join tb_conta_financeira 
      as ctf on ctf.cl_conta = lcf.cl_conta_financeira inner join tb_parceiros as parc on
      parc.cl_id = lcf.cl_parceiro_id inner join tb_status_recebimento as star on star.cl_id = lcf.cl_status_id inner join
       tb_forma_pagamento as fpg on fpg.cl_id = lcf.cl_forma_pagamento_id inner join tb_classificacao_financeiro as cfc on cfc.cl_id = lcf.cl_classificacao_id
        inner join tb_forma_pagamento as pgt on pgt.cl_id = lcf.cl_forma_pagamento_id 
      where lcf.cl_lancamento_pai_id = $form_id ";
   $consultar_baixa_parcial = mysqli_query($conecta, $select);
   if (!$consultar_baixa_parcial) {
      die("Falha no banco de dados" . mysqli_error($conecta));
   } else {
      $qtd = mysqli_num_rows($consultar_baixa_parcial);
   }
}
// //cadastrar formulario
if (isset($_POST['formulario_baixa_duplicata'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";
   $usuario_id = verifica_sessao_usuario();
   $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");

   $retornar = array();
   $acao = $_POST['acao'];
   if ($acao == "show") {
      $id = $_POST['form_id'];
      $select = "SELECT * from tb_lancamento_financeiro WHERE cl_id = $id";
      $consultar_lancamento = mysqli_query($conecta, $select);
      $linha = mysqli_fetch_assoc($consultar_lancamento);
      $valor_liquido = utf8_encode($linha['cl_valor_liquido']);
      $doc = utf8_encode($linha['cl_documento']);
      $informacao = array(
         "valor_liquido" => $valor_liquido,
         "doc" => $doc,
      );

      $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
   }


   if ($acao == "create_baixa_parcial") {
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);

      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = $value;
      }

      $parceiro_id = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id_lancamento, "cl_parceiro_id");
      $doc_pai = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id_lancamento, "cl_documento");
      $classificao_financeiro = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id_lancamento, "cl_classificacao_id");
      $numero_nf = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id_lancamento, "cl_nf");
      $codigo_nf = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id_lancamento, "cl_codigo_nf");
      $tipo_lancamento = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id_lancamento, "cl_tipo_lancamento");
      $valor_liquido_lancamento = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id_lancamento, "cl_valor_liquido");
      $baixa_parcial_atual = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id_lancamento, "cl_bx_parcial");
      $valor_total_lancamento = $valor_liquido_lancamento - $valor_bx_parcial;

      if ($conta_financeira == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("conta financeira"));
      } elseif ($forma_pagamento == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("forma de pagamento"));
      } elseif ($valor_bx_parcial == 0 or $valor_bx_parcial < 0) {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("valor baixa parcial"));
      } elseif ($valor_total_lancamento < 0 or $valor_total_lancamento == 0) {
         $retornar["dados"] = array("sucesso" => false, "title" => "O valor da baixa parcial não pode ultrapassar ou zerar o valor líquido do lançamento, favor, verifique");
      } else {
         $caixa =  verifica_caixa_financeiro($conecta, $data_pagamento, $conta_financeira); //verificar o status do caixa
         if (($caixa['resultado']) == "") { //verificar se o caixa já foi aberto
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("VAZIO")); //alertar o usuario que o caixa ainda não foi aberto
         } elseif ($caixa['status'] == "fechado") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("FECHADO")); //alertar o usuario que o caixa está fechado
         } else {
            $doc = "$doc_pai/BxP";
            $descricao = "Baixa Parcial Referente ao doc $doc_pai";
            if ($tipo_lancamento == "RECEITA") {
               $status_pagamento = "2"; //recebido
            } else { //despesa
               $status_pagamento = "4"; //pago
            }
            $operacao_insert = recebimento_nf(
               $conecta,
               $data_lancamento,
               $data_lancamento,
               $data_lancamento,
               $conta_financeira,
               $forma_pagamento,
               $parceiro_id,
               $tipo_lancamento,
               $status_pagamento,
               $valor_bx_parcial,
               $valor_bx_parcial,
               0,
               0,
               0,
               0,
               $doc,
               $classificao_financeiro,
               $descricao,
               $observacao,
               $numero_nf,
               "BXP",
               $codigo_nf,
               "",
               $id_lancamento
            ); //lancar no financeiro o recebimento
            if ($operacao_insert) {
               $baixa_parcial_atual += $valor_bx_parcial; //somar as baixa parcias 
               update_registro($conecta, "tb_lancamento_financeiro", "cl_id", $id_lancamento, "", "", "cl_bx_parcial", $baixa_parcial_atual); //atualizar a coluna de baixa parcial do registro pai
               update_registro($conecta, "tb_lancamento_financeiro", "cl_id", $id_lancamento, "", "", "cl_valor_liquido", $valor_total_lancamento); //atualizar a coluna do valor liquido do registro pai

               $retornar["dados"] = array("sucesso" => true, "title" => "Baixa Parcial lançada com sucesso");
               $mensagem =  utf8_decode("Usuário $nome_usuario_logado adicionou a baixa parcial referente ao doc $doc_pai no valor de $valor_bx_parcial ");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

               // Se tudo ocorreu bem, confirme a transação
               mysqli_commit($conecta);
            } else {
               $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
               $mensagem =  utf8_decode("Usuário $nome_usuario_logado, tentativa de adicionar a baixa parcial referente ao doc $doc_pai no valor de $valor_bx_parcialsem sucesso");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
         }
      }
   }


   if ($acao == "update") { // EDITAR


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
         $update = "DELETE FROM tb_classificacao_financeiro WHERE `cl_id` = $id ";
         $operacao_update = mysqli_query($conecta, $update);
         if ($operacao_update) {
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
