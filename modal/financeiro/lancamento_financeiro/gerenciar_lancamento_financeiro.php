<?php

if (isset($_GET['lancamento_rapido'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";
   if (isset($_GET['tipo'])) {
      $tipo = $_GET['tipo'];
      if ($tipo == "DESPESA") {
         $titulo = 'Lançar Despesa';
      } else {
         $titulo = 'Lançar Receita';
      }
   } else {
      $tipo = "";
   }
   if (isset($_GET['form_id'])) {
      $form_id = $_GET['form_id'];
   } else {
      $form_id = "";
   }
}
if (isset($_GET['adicionar_lancamento_transferencia_valores'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";

   if (isset($_GET['form_id'])) {
      $form_id = $_GET['form_id'];
   } else {
      $form_id = "";
   }
}
//consultar informações para tabela
if (isset($_GET['consultar_lancamento_financeiro'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   $consulta = $_GET['consultar_lancamento_financeiro'];
   $data_inicial = $_GET['data_inicial'];
   $data_final = $_GET['data_final'];



   if ($consulta == "inicial") {
      $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1
      $select = "SELECT lcf.cl_valor_bruto, lcf.cl_id, lcf.cl_data_vencimento,star.cl_descricao as status, lcf.cl_tipo_lancamento, lcf.cl_data_pagamento,lcf.cl_descricao as descricao,fpg.cl_descricao as formapgt,
      lcf.cl_data_lancamento,lcf.cl_documento,lcf.cl_valor_liquido,ctf.cl_banco,parc.cl_razao_social,parc.cl_nome_fantasia,star.cl_descricao 
      FROM tb_lancamento_financeiro as lcf inner join tb_conta_financeira 
      as ctf on ctf.cl_conta = lcf.cl_conta_financeira inner join tb_parceiros as parc on
      parc.cl_id = lcf.cl_parceiro_id inner join tb_status_recebimento as star on star.cl_id = lcf.cl_status_id inner join 
      tb_forma_pagamento as fpg on fpg.cl_id = lcf.cl_forma_pagamento_id 
      WHERE lcf.cl_data_vencimento between '$data_inicial' and '$data_final' order by lcf.cl_data_vencimento,lcf.cl_id desc";
      $consultar_lancamento_financeiro = mysqli_query($conecta, $select);
      if (!$consultar_lancamento_financeiro) {
         die("Falha no banco de dados " . mysqli_error($conecta));
      } else {
         $qtd = mysqli_num_rows($consultar_lancamento_financeiro); //quantidade de registros
      }
   } else {
      $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
      $status_lancamento = $_GET['status_lancamento']; //filtro
      $classificao_financeiro = $_GET['classificao_financeiro']; //v
      $tipo_lancamento = ($_GET['tipo_lancamento']); //filtro
      $forma_pagamento = ($_GET['forma_pagamento']); //filtro
      $conta_financeira = ($_GET['conta_financeira']); //filtro


      $select = "SELECT lcf.cl_conta_financeira, lcf.cl_valor_bruto, lcf.cl_id, lcf.cl_data_vencimento,star.cl_descricao as status, lcf.cl_tipo_lancamento, lcf.cl_data_pagamento,lcf.cl_descricao as descricao,fpg.cl_descricao as formapgt,
      lcf.cl_data_lancamento,lcf.cl_documento,lcf.cl_valor_liquido,ctf.cl_banco,parc.cl_razao_social,parc.cl_nome_fantasia,star.cl_descricao 
      FROM tb_lancamento_financeiro as lcf inner join tb_conta_financeira 
      as ctf on ctf.cl_conta = lcf.cl_conta_financeira inner join tb_parceiros as parc on
      parc.cl_id = lcf.cl_parceiro_id inner join tb_status_recebimento as star on star.cl_id = lcf.cl_status_id inner join
       tb_forma_pagamento as fpg on fpg.cl_id = lcf.cl_forma_pagamento_id 
      WHERE lcf.cl_data_vencimento between '$data_inicial' and '$data_final' and 
       (lcf.cl_descricao  like '%$pesquisa%' or parc.cl_razao_social  like '%$pesquisa%' 
       or parc.cl_nome_fantasia  like '%$pesquisa%' or parc.cl_cnpj_cpf  like '%$pesquisa%' or lcf.cl_documento like '%$pesquisa%') ";

      if ($status_lancamento != "0") {
         $select .= " and lcf.cl_status_id = '$status_lancamento' ";
      }
      if ($classificao_financeiro != "0") {
         $select .= " and lcf.cl_classificacao_id = '$classificao_financeiro' ";
      }
      if ($tipo_lancamento != "0") {
         $select .= " and lcf.cl_tipo_lancamento = '$tipo_lancamento' ";
      }
      if ($forma_pagamento != "0") {
         $select .= " and lcf.cl_forma_pagamento_id = '$forma_pagamento' ";
      }
      if ($conta_financeira != "0") {
         $select .= " and lcf.cl_conta_financeira = '$conta_financeira' ";
      }
      $select .= " order by lcf.cl_data_vencimento,lcf.cl_id desc";

      $consultar_lancamento_financeiro = mysqli_query($conecta, $select);
      if (!$consultar_lancamento_financeiro) {
         die("Falha no banco de dados" . mysqli_error($conecta));
      } else {
         $qtd = mysqli_num_rows($consultar_lancamento_financeiro);
      }
   }
}

// //cadastrar formulario
if (isset($_POST['formulario_lancamento_financeiro'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";
   $retornar = array();
   $acao = $_POST['acao'];

   $id_usuario_logado = verifica_sessao_usuario(); //pegar a sessão do usuario
   $nome_usuario_logado = (consulta_tabela($conecta, "tb_users", "cl_id", $id_usuario_logado, "cl_usuario"));

   if ($acao == "show") {
      $conta_financeira_id = $_POST['conta_financeira_id'];
      $select = "SELECT  lcf.cl_taxa_cartao,DATEDIFF(CURRENT_DATE(),lcf.cl_data_vencimento) as atraso, lcf.cl_data_lancamento,lcf.cl_data_vencimento,lcf.cl_data_pagamento,lcf.cl_conta_financeira,lcf.cl_forma_pagamento_id,lcf.cl_parceiro_id,parc.cl_razao_social,lcf.cl_tipo_lancamento,
      lcf.cl_status_id,lcf.cl_valor_bruto,lcf.cl_valor_liquido,lcf.cl_bx_parcial,lcf.cl_juros,lcf.cl_taxa,lcf.cl_desconto,lcf.cl_documento,
      lcf.cl_classificacao_id,lcf.cl_descricao,lcf.cl_observacao,cl_ordem_servico from tb_lancamento_financeiro as lcf inner join tb_parceiros as parc on parc.cl_id = lcf.cl_parceiro_id WHERE lcf.cl_id = $conta_financeira_id  ";
      $consultar_lancamento_financeiro = mysqli_query($conecta, $select);
      $linha = mysqli_fetch_assoc($consultar_lancamento_financeiro);
      $data_lancamento =  ($linha['cl_data_lancamento']);
      $data_vencimento =  ($linha['cl_data_vencimento']);
      $data_pagamento =  ($linha['cl_data_pagamento']);
      $conta_financeira =  ($linha['cl_conta_financeira']);
      $forma_pagamento =  ($linha['cl_forma_pagamento_id']);
      $parceiro_id =  ($linha['cl_parceiro_id']);
      $parceiro =  utf8_encode($linha['cl_razao_social']);
      $status =  ($linha['cl_status_id']);
      $valor_bruto =  ($linha['cl_valor_bruto']);
      $valor_liquido =  ($linha['cl_valor_liquido']);
      $baixa_parcial =  ($linha['cl_bx_parcial']);
      $juros =  ($linha['cl_juros']);
      $taxa =  ($linha['cl_taxa']);
      $desconto =  ($linha['cl_desconto']);
      $documento =  ($linha['cl_documento']);
      $classificacao =  ($linha['cl_classificacao_id']);
      $observacao =  utf8_encode($linha['cl_observacao']);
      $ordem_servico =  ($linha['cl_ordem_servico']);
      $descricao =  utf8_encode($linha['cl_descricao']);
      $atraso =  ($linha['atraso']);
      $taxa_cartao =  ($linha['cl_taxa_cartao']);

      $previsao_juros = false;
      $qtd_dia_juros = verficar_paramentro($conecta, 'tb_parametros', 'cl_id', '50');
      $taxa_juros = verficar_paramentro($conecta, 'tb_parametros', 'cl_id', '49');

      if ($juros == 0 and $taxa_juros != 0 and ($atraso > $qtd_dia_juros) and $status == "1") {
         $juros = ($taxa_juros / 100) * $valor_bruto; //
         $previsao_juros = true;
      }

      $informacao = array(
         "data_movimento" => ($data_lancamento),
         "data_vencimento" => ($data_vencimento),
         "data_pagamento" => ($data_pagamento),
         "conta_financeira" => $conta_financeira,
         "forma_pagamento" => $forma_pagamento,
         "parceiro_id" => $parceiro_id,
         "parceiro_descricao" => $parceiro,
         "status" => $status,
         "valor_bruto" => $valor_bruto,
         "valor_liquido" => $valor_liquido,
         "baixa_parcial" => $baixa_parcial,
         "juros" => $juros,
         "taxa" => $taxa,
         "desconto" => $desconto,
         "documento" => $documento,
         "classificacao" => $classificacao,
         "observacao" => $observacao,
         "ordem_servico" => $ordem_servico,
         "descricao" => $descricao,
         "previsao_juros" => $previsao_juros,
         "taxa_cartao" => $taxa_cartao,
      );

      $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
   }


   if ($acao == "create") {
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);

      // $data_vencimento = ($_POST['data_vencimento']);
      // $data_pagamento = ($_POST['data_pagamento']);
      // $conta_financeira = utf8_decode($_POST['conta_financeira']);
      // $forma_pagamento = utf8_decode($_POST['forma_pagamento']);
      // $status = utf8_decode($_POST['status']);
      // $parceiro_id = ($_POST['parceiro_id']);
      // $descricao = utf8_decode($_POST['descricao']);
      // $classificacao = ($_POST['classificacao']);
      // $documento = utf8_decode($_POST['documento']);
      // $ordem_servico = ($_POST['ordem_servico']);
      // $valor_bruto = ($_POST['valor_bruto']);
      // //  $valor_liquido = ($_POST['valor_liquido']);
      // // $baixa_parcial = ($_POST['baixa_parcial']);
      // $juros = ($_POST['juros']);
      // $taxa = ($_POST['taxa']);
      // $desconto = ($_POST['desconto']);
      // $observacao = utf8_decode($_POST['observacao']);
      // $tipo_lancamento = $_POST['tipo'];
      // $taxa_cartao = $_POST['taxa_cartao'];

      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = utf8_decode($value);
         // Remover aspas simples usando str_replace
         ${$name} = str_replace("'", "", ${$name});
      }

      if ($data_vencimento == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("data vencimento"));
      } elseif (($data_pagamento) == "" and ($status == "2" or $status == "4")) {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("data pagamento"));
      } elseif (($data_pagamento) != "" and ($status == "1" or $status == "3")) {
         $retornar["dados"] =  array("sucesso" => false, "title" => "Você informou a data de pagamento, mas não atualizou o status, favor, verifique e atualize o status");
      } elseif ($conta_financeira == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("conta financeira"));
      } elseif ($forma_pagamento == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("forma pagamento"));
      } elseif ($status == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("status"));
      } elseif ($classificacao == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("classificação"));
      } else {

         $data_contabilizacao = $data_pagamento == "" ? $data_lancamento : $data_pagamento; //data contabilização do lancamento
         $parceiro_id = $parceiro_id == "" ? verficar_paramentro($conecta, 'tb_parametros', 'cl_id', '8') : $parceiro_id; //assumir o parceiro_id

         $caixa =  verifica_caixa_financeiro($conecta, $data_contabilizacao, $conta_financeira);
         if (($caixa['resultado']) == "" and $data_pagamento != "") { //verificar se o caixa já foi aberto
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("VAZIO")); //alertar o usuario que o caixa ainda não foi aberto
         } elseif ($caixa['status'] == "fechado" and $data_pagamento != "") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("FECHADO")); //alertar o usuario que o caixa está fechado
         } else {



            // Verifique e formate os campos que requerem substituição de vírgula por ponto
            $campos_com_virgula = array(
               'taxa_cartao',
               'desconto',
               'taxa',
               'juros',
               'valor_bruto',
            );

            foreach ($campos_com_virgula as $campo) { //trocar virgula por ponto
               if (verificaVirgula($$campo)) {
                  $$campo = formatDecimal($$campo);
               }
            }

            $valor_liquido = $valor_bruto + $juros + $taxa - $desconto - $taxa_cartao;

            //query
            $insert = "INSERT INTO `tb_lancamento_financeiro` (`cl_data_lancamento`, `cl_data_vencimento`, `cl_data_pagamento`, `cl_conta_financeira`, 
         `cl_forma_pagamento_id`, `cl_parceiro_id`, `cl_tipo_lancamento`, `cl_status_id`, `cl_valor_bruto`, `cl_valor_liquido`, `cl_juros`, `cl_taxa`,
          `cl_desconto`, `cl_documento`, `cl_classificacao_id`, `cl_descricao`, `cl_observacao`,
           `cl_serie_nf`,`cl_ordem_servico`,`cl_taxa_cartao` ) VALUES 
         ( '$data_lancamento', '$data_vencimento', '$data_pagamento', '$conta_financeira', '$forma_pagamento', '$parceiro_id', '$tipo',
          '$status', '$valor_bruto', '$valor_liquido',
          '$juros', '$taxa', '$desconto', '$documento', '$classificacao', '$descricao', '$observacao', 'LCFAVUL','$ordem_servico','$taxa_cartao' )";
            $operacao_insert = mysqli_query($conecta, $insert);
            if ($operacao_insert) {
               $retornar["dados"] = array("sucesso" => true, "title" => "Lançamento realizado com sucesso");
               $mensagem = utf8_decode("Usuário $nome_usuario_logado realizou o lançamento financeiro do tipo $tipo no valor de $valor_liquido");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
               mysqli_commit($conecta);
            } else {
               // Se ocorrer um erro, reverta a transação
               mysqli_rollback($conecta);
               $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
               $mensagem =  utf8_decode("Usuário $nome_usuario_logado, tentativa de adicionar o lançamento financeciro no valor de $valor_liquido sem sucesso");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
         }
      }
   }



   if ($acao == "update") { // EDITAR
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);

      $id = ($_POST['id']);
      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = utf8_decode($value);
         // Remover aspas simples usando str_replace
         ${$name} = str_replace("'", "", ${$name});
      }

      $baixa_parcial = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id, "cl_bx_parcial");
      $ordem_servico_id = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id, "cl_ordem_servico");
      $codigo_nf = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id, "cl_codigo_nf");

      if ($data_vencimento == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("data vencimento"));
      } elseif (($data_pagamento) == "" and ($status == "2" or $status == "4")) {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("data pagamento"));
      } elseif (($data_pagamento) != "" and ($status == "1" or $status == "3")) {
         $retornar["dados"] =  array("sucesso" => false, "title" => "Você informou a data de pagamento, mas não atualizou o status, favor, verifique e atualize o status");
      } elseif ($conta_financeira == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("conta financeira"));
      } elseif ($forma_pagamento == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("forma pagamento"));
      } elseif ($status == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("status"));
      } elseif ($classificacao == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("classificação"));
      } elseif (!empty($ordem_servico) and empty($codigo_nf)) {
         $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel alterar esse lançamento nesta tela, o lançamento está vinculado à ordem de serviço $ordem_servico_id");
      } else {
         // if ($data_pagamento == "") {
         //    $data_contabilizacao = $data_lancamento;
         // } else {
         //    $data_contabilizacao = $data_pagamento;
         // }
         $data_contabilizacao = $data_pagamento == "" ? $data_lancamento : $data_pagamento; //data contabilização do lancamento
         $parceiro_id = $parceiro_id == "" ? verficar_paramentro($conecta, 'tb_parametros', 'cl_id', '8') : $parceiro_id; //assumir o parceiro_id

         $caixa =  verifica_caixa_financeiro($conecta, $data_contabilizacao, $conta_financeira);
         if (($caixa['resultado']) == "" and $data_pagamento != "") { //verificar se o caixa já foi aberto
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("VAZIO")); //alertar o usuario que o caixa ainda não foi aberto
         } elseif ($caixa['status'] == "fechado" and $data_pagamento != "") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("FECHADO")); //alertar o usuario que o caixa está fechado
         } else {


            $campos_com_virgula = array(
               'taxa_cartao',
               'desconto',
               'taxa',
               'juros',
               'valor_bruto',
            );

            foreach ($campos_com_virgula as $campo) { //trocar virgula por ponto
               if (verificaVirgula($$campo)) {
                  $$campo = formatDecimal($$campo);
               }
            }

            $valor_liquido = $valor_bruto + $juros + $taxa - $desconto - $baixa_parcial - $taxa_cartao;

            //query
            $update = "UPDATE `tb_lancamento_financeiro` SET 
          `cl_data_vencimento` = '$data_vencimento', `cl_data_pagamento` = '$data_pagamento', `cl_conta_financeira` = '$conta_financeira',
           `cl_forma_pagamento_id` = '$forma_pagamento', `cl_parceiro_id` = '$parceiro_id', `cl_status_id` = '$status',
            `cl_valor_bruto` = '$valor_bruto', `cl_valor_liquido` = '$valor_liquido', `cl_juros` = '$juros', `cl_taxa` = '$taxa', 
            `cl_desconto` = '$desconto', `cl_documento` = '$documento', `cl_classificacao_id` = '$classificacao', `cl_descricao` = '$descricao', `cl_observacao` = '$observacao',
          `cl_ordem_servico` = '$ordem_servico', `cl_taxa_cartao` = '$taxa_cartao' WHERE `tb_lancamento_financeiro`.`cl_id` = $id ";
            $operacao_update = mysqli_query($conecta, $update);
            if ($operacao_update) {
               if ($status == "5") {
                  $pai_id_lancamento  = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id, "cl_lancamento_pai_id");
                  if ($pai_id_lancamento != "") { //verificar se tem lançamento atrelado a esse lançamento
                     $valor_liquido_lancamento_pai = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $pai_id_lancamento, "cl_valor_liquido"); //valor liquido do lançamento pai
                     if ($valor_liquido_lancamento_pai != "") { //verificar se existe o lançamento pai
                        $valor_bx_parcial = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $pai_id_lancamento, "cl_bx_parcial"); //valor da baixa parcial
                        $valor_liquido_novo = $valor_liquido + $valor_liquido_lancamento_pai;
                        $valor_baixa_parcial_novo = $valor_liquido - $valor_bx_parcial;
                        update_registro($conecta, "tb_lancamento_financeiro", "cl_id", $pai_id_lancamento, "", "", "cl_valor_liquido", $valor_liquido_novo); //atualizr o valor liquido do lançamento pai
                        update_registro($conecta, "tb_lancamento_financeiro", "cl_id", $pai_id_lancamento, "", "", "cl_bx_parcial", $valor_baixa_parcial_novo); //atualizr o valor liquido do lançamento pai
                     }
                  }
               }


               $retornar["dados"] = array("sucesso" => true, "title" => "Lançamento alterado om sucesso");
               $mensagem = utf8_decode("Usuário $nome_usuario_logado alterou o lançamento doc $documento, valor $valor_liquido");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

               // Se tudo ocorreu bem, confirme a transação
               mysqli_commit($conecta);
            } else {
               // Se ocorrer um erro, reverta a transação
               mysqli_rollback($conecta);
               $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
               $mensagem =  utf8_decode("Usuário $nome_usuario_logado, tentativa de alterar o lançamento financeiro doc $documento, valor $valor_liquido sem sucesso");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
         }
      }
   }

   if ($acao == "clonar") { // cclonar lancamento
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);

      $form_id = ($_POST['form_id']);
      $doc = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $form_id, 'cl_documento');

      if (consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $form_id, 'cl_codigo_nf') != "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel clonar lançamentos atrelados a outras operações, apenas lançamentos avulsos ");
      } else {
         $insert = "INSERT INTO `tb_lancamento_financeiro` (`cl_data_lancamento`, `cl_data_vencimento`, `cl_data_pagamento`, `cl_conta_financeira`, 
         `cl_forma_pagamento_id`, `cl_parceiro_id`, `cl_tipo_lancamento`, `cl_status_id`, `cl_valor_bruto`, `cl_valor_liquido`, `cl_bx_parcial`, `cl_juros`, `cl_taxa`,
          `cl_desconto`, `cl_documento`, `cl_classificacao_id`, `cl_descricao`, `cl_observacao`, `cl_serie_nf`,`cl_ordem_servico`)  

         SELECT cl_data_lancamento, cl_data_vencimento, cl_data_pagamento, cl_conta_financeira, 
         cl_forma_pagamento_id, cl_parceiro_id, cl_tipo_lancamento, cl_status_id, cl_valor_bruto, cl_valor_liquido, cl_bx_parcial, cl_juros, cl_taxa,
          cl_desconto, cl_documento, cl_classificacao_id, cl_descricao, cl_observacao, cl_serie_nf,cl_ordem_servico
           from tb_lancamento_financeiro where cl_id ='$form_id' ";
         $operacao_insert = mysqli_query($conecta, $insert);
         if ($operacao_insert) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Lançamento clonado com sucesso");
            $mensagem = utf8_decode("Usuário $nome_usuario_logado clonou o lançamento de doc $doc");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            // Se tudo ocorreu bem, confirme a transação
            mysqli_commit($conecta);
         } else {
            // Se ocorrer um erro, reverta a transação
            mysqli_rollback($conecta);
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, comunique o suporte");
            $mensagem = utf8_decode("Tentativa sem sucesso do usuário $nome_usuario_logado de clonou o lançamento de doc $doc");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }
   if ($acao == "remover") { // remover lancamento

      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);

      $form_id = ($_POST['form_id']);
      $doc = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $form_id, 'cl_documento');

      if (consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $form_id, 'cl_codigo_nf') != "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel remover lançamentos atrelados a outras operações, apenas lançamentos avulsos ");
      } else {
         $delete = "DELETE FROM tb_lancamento_financeiro where cl_id ='$form_id' ";
         $operacao_delete = mysqli_query($conecta, $delete);
         if ($operacao_delete) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Lançamento removido com sucesso");
            $mensagem = utf8_decode("Usuário $nome_usuario_logado removeu o lançamento de doc $doc");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            // Se tudo ocorreu bem, confirme a transação
            mysqli_commit($conecta);
         } else {
            // Se ocorrer um erro, reverta a transação
            mysqli_rollback($conecta);
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, comunique o suporte");
            $mensagem = utf8_decode("Tentativa sem sucesso do usuário $nome_usuario_logado de removeu o lançamento de doc $doc");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }
   if ($acao == "create_lancamento_rapido") { //adicioanr lançamento rapido
      $tipo = $_POST['tipo'];
      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = $value;
         ${$name} = str_replace("'", "", ${$name});
      }

      if ($tipo == "DESPESA") {
         $status = "4";
      } else {
         $status = "2";
      }

      if ($data_pagamento == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("Data"));
      } elseif ($conta_financeira == "0") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("conta financeira"));
      } elseif ($forma_pagamento == "0") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("forma de pagamento"));
      } elseif ($classificacao == "0") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("classificação"));
      } elseif ($valor == 0) {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("valor"));
      } else {
         $caixa =  verifica_caixa_financeiro($conecta, $data_pagamento, $conta_financeira);
         if (($caixa['resultado']) == "" and $data_pagamento != "") { //verificar se o caixa já foi aberto
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("VAZIO")); //alertar o usuario que o caixa ainda não foi aberto
         } elseif ($caixa['status'] == "fechado" and $data_pagamento != "") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("FECHADO")); //alertar o usuario que o caixa está fechado
         } else {
            // $parceiro_id = verficar_paramentro($conecta, 'tb_parametros', 'cl_id', '8');
            if (verificaVirgula($valor)) { //verificar se tem virgula
               $valor = formatDecimal($valor); // formatar virgula para ponto
            }
            if (recebimento_nf( //lancar no financeiro o lancamento
               $conecta,
               $data_lancamento,
               $data_pagamento,
               $data_pagamento,
               $conta_financeira,
               $forma_pagamento,
               $parceiro_id,
               $tipo,
               $status,
               $valor,
               $valor,
               0,
               0,
               0,
               0,
               "",
               $classificacao,
               $observacao,
               "",
               "",
               "",
               "",
               "",
               ""
            )) {
               $retornar["dados"] = array("sucesso" => true, "title" => "Lançamento realizado com sucesso");
               $mensagem = utf8_decode("Usuário $nome_usuario_logado realizou o lançamento do tipo $tipo no valor de $valor");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
               // Se tudo ocorreu bem, confirme a transação
               mysqli_commit($conecta);
            } else {
               // Se ocorrer um erro, reverta a transação
               mysqli_rollback($conecta);
               $retornar["dados"] = array("sucesso" => false, "title" => "Erro, comunique o suporte");
               $mensagem = utf8_decode("Tentativa sem sucesso do usuário $nome_usuario_logado de realizar o lançamento do tipo $tipo no valor de $valor");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
         }
      }
   }

   if ($acao == "create_lancamento_transferencia_valores") { //adicioanr lançamento de transferencia de valores entre conta
      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = $value;
         ${$name} = str_replace("'", "", ${$name});
      }
      if ($data_pagamento == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("Data"));
      } elseif ($conta_financeira_origem == "0") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("conta origem"));
      } elseif ($conta_financeira_destino == "0") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("conta destino"));
      } elseif ($conta_financeira_destino == $conta_financeira_origem) {
         $retornar["dados"] =  array("sucesso" => false, "title" => "A conta origem não pode ser igual a conta destino, favor, verifique");
      } elseif ($forma_pagamento == "0") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("forma de pagamento"));
      } elseif ($classificacao == "0") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("classificação"));
      } elseif ($valor == 0) {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("valor"));
      } else {
         $caixa =  verifica_caixa_financeiro($conecta, $data_pagamento, $conta_financeira_origem);
         if (($caixa['resultado']) == "" and $data_pagamento != "") { //verificar se o caixa já foi aberto
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("VAZIO")); //alertar o usuario que o caixa ainda não foi aberto
         } elseif ($caixa['status'] == "fechado" and $data_pagamento != "") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("FECHADO")); //alertar o usuario que o caixa está fechado
         } else {
            // $parceiro_id = verficar_paramentro($conecta, 'tb_parametros', 'cl_id', '8');
            if (verificaVirgula($valor)) { //verificar se tem virgula
               $valor = formatDecimal($valor); // formatar virgula para ponto
            }
            $recebimento_origem = recebimento_nf( //lancar no financeiro o lancamento
               $conecta,
               $data_lancamento,
               $data_pagamento,
               $data_pagamento,
               $conta_financeira_origem,
               $forma_pagamento,
               "",
               "DESPESA", //TIPO DESPESA
               "4", //STATUS 4 - PAGO
               $valor,
               $valor,
               0,
               0,
               0,
               0,
               "Trf/Saida",
               $classificacao,
               $observacao,
               "",
               "",
               "",
               "",
               "",
               ""
            );
            $recebimento_destino = recebimento_nf( //lancar no financeiro o lancamento
               $conecta,
               $data_lancamento,
               $data_pagamento,
               $data_pagamento,
               $conta_financeira_origem,
               $forma_pagamento,
               "",
               "RECEITA", //TIPO RECEITA
               "2", //STATUS 2 - RECEBIDO
               $valor,
               $valor,
               0,
               0,
               0,
               0,
               "Trf/Entrada",
               $classificacao,
               $observacao,
               "",
               "",
               "",
               "",
               "",
               ""
            );

            if ($recebimento_origem and $recebimento_destino) {
               $retornar["dados"] = array("sucesso" => true, "title" => "Transferência realizado com sucesso");
               $mensagem = utf8_decode("Realizou a transferência de valores entre conta no valor de $valor");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
               // Se tudo ocorreu bem, confirme a transação
               mysqli_commit($conecta);
            } else {
               // Se ocorrer um erro, reverta a transação
               mysqli_rollback($conecta);
               $retornar["dados"] = array("sucesso" => false, "title" => "Erro, comunique o suporte");
               $mensagem = utf8_decode("Tentativa sem sucesso de realizar a transferência de valores entre conta no valor de $valor");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
         }
      }
   }

   if ($acao == 'create_lancamento_multiplo') { //recebimento de venda forma de pagamento tipo faturado

      // Inicia uma transação
      mysqli_begin_transaction($conecta);


      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = utf8_decode($value);
         // Remover aspas simples usando str_replace
         ${$name} = str_replace("'", "", ${$name});
      }
      $valor_total_provisionado = 0;
      $codigo_nf = md5(uniqid(time())); //gerar um novo codigo para nf

      if ($forma_pagamento == "0") { //validação
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("forma pagamento"));
      } elseif ($classificacao == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("classificação"));
      } elseif ($status == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("status"));
      } elseif (empty($descricao)) {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("descrição"));
      } else {

         if ($conta_financeira == "0") { //conta financeira não estiver selecionada, será assumido a conta financeira que está na forma de pagamento
            $conta_financeira = consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $forma_pagamento, "cl_conta_financeira");
         }



         for ($i = 1; $i < 12; $i++) {
            $data_pagamento = "";
            if (isset($_POST["$i" . "valor"])) {
               $valor_parcela = $_POST["$i" . "valor"];
               if ($valor_parcela > 0) {
                  $doc = $_POST["$i" . "doc"];
                  $data_vencimento = $_POST["$i" . "dtvencimento"];

                  if (verificaVirgula($valor_parcela)) { //verificar se tem virgula
                     $valor_parcela = formatDecimal($valor_parcela); // formatar virgula para ponto
                  }


                  if ($data_vencimento == "") { //data de vencimento não informada
                     $retornar["dados"] = array("sucesso" => false, "title" => "Data de vencimento da $i parcela não foi informado");
                     echo json_encode($retornar);
                     exit;
                  }


                  if ($status == "4" or $status == "2") { //se for atribuido o status pago ou recebibo, a data de pagamento será atribuida com o valor da data de vencimento de cada parcela
                     $data_pagamento = $data_vencimento;
                     $caixa =  verifica_caixa_financeiro($conecta, $data_pagamento, $conta_financeira);
                     if (($caixa['resultado']) == "" and $data_pagamento != "") { //verificar se o caixa já foi aberto
                        $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("VAZIO")); //alertar o usuario que o caixa ainda não foi aberto
                        mysqli_rollback($conecta);
                        echo json_encode($retornar);
                        exit;
                     } elseif ($caixa['status'] == "fechado" and $data_pagamento != "") {
                        $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("FECHADO")); //alertar o usuario que o caixa está fechado
                        echo json_encode($retornar);
                        exit;
                     }
                  }
                  $recebimento = recebimento_nf(
                     $conecta,
                     $data_lancamento,
                     $data_vencimento,
                     $data_pagamento,
                     $conta_financeira,
                     $forma_pagamento,
                     $parceiro_id,
                     "",
                     $status,
                     $valor_parcela,
                     $valor_parcela,
                     0,
                     0,
                     0,
                     0,
                     $doc,
                     $classificacao,
                     $descricao,
                     "",
                     "",
                     "",
                     $codigo_nf,
                     "",
                     ""
                  );
                  if (!$recebimento) {
                     $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                     echo json_encode($retornar);
                     exit;
                  }
                  $valor_total_provisionado += $valor_parcela;
               }
            }
         }

         $valor_total_provisionado = number_format($valor_total_provisionado, 2); //arendonar para duas casa decimais
         if ($valor_total_provisionado == 0) { //validar se as porcelas somandas é igual a 0
            mysqli_rollback($conecta); // Desfaz a transação em caso de erro
            $retornar["dados"] = array("sucesso" => false, "title" => "Favor, Informe os valores das parcelas");
         } else {
            mysqli_commit($conecta); // Confirma a transação se tudo ocorreu bem
            $retornar["dados"] = array("sucesso" => true, "title" => "Lançamento realizado com sucesso");
         }
      }
   }


   if ($acao == "previa_parcelas_lancamento_multiplo") { //previo de parcelas em lançamentos multiplos
      $n_pacelas = $_POST['n_pacelas'];
      $primeira_parcela = $_POST['primeira_parcela'];
      $intervalo = $_POST['intervalo'];
      $forma_pgt_id = $_POST['forma_pgt_id'];
      $valor_liquido = $_POST['valor_liquido'];

      $verifica_tipo_fpg = consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $forma_pgt_id, "cl_tipo_pagamento_id");
      $n_pacelas_fpg = consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $forma_pgt_id, "cl_numero_parcela");
      $intervalo_pfg = consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $forma_pgt_id, "cl_intervalo_parcela");



      if (($n_pacelas == "" and $verifica_tipo_fpg != '3') or ($n_pacelas == "" and $verifica_tipo_fpg == '3' and ($n_pacelas_fpg == 0 or $intervalo_pfg == 0))) {
         $retornar["dados"] = array("sucesso" => false, "title" => "Favor, Informe o número de Parcelas");
      } elseif (empty($valor_liquido)) {
         $retornar["dados"] = array("sucesso" => false, "title" => "Favor, Informe o valor total da operação");
      } else {
         if ($intervalo == "") { //se não for preenchido nenuma informação ao campo intervalo será atribudo 30    
            $intervalo = 30;
         }

         if ($n_pacelas == "" and $verifica_tipo_fpg == '3' and $n_pacelas_fpg != 0 and $intervalo_pfg != 0) { //incluso de valores de numero de parcela intervalo avulso
            $n_pacelas = $n_pacelas_fpg;
            $intervalo = $intervalo_pfg;
         }

         if ($primeira_parcela == "") { //se não for definido a data da primeira parcela será atribuido a data atual
            // Obtém a data atual
            $primeira_parcela = new DateTime();

            // Adiciona um mês à data atual
            $primeira_parcela->add(new DateInterval('P1M'));

            // Formata a data no formato desejado (Y-m-d)
            $primeira_parcela = $primeira_parcela->format('Y-m-d');
         }

         // Converte a data da primeira parcela em um objeto DateTime
         $data_parcela = new DateTime($primeira_parcela);

         $valor_liquido_parcela = $valor_liquido;
         // Calcula o valor da parcela dividindo o valor total pela quantidade de parcelas
         $valor_parcela = number_format($valor_liquido_parcela / $n_pacelas, 2, '.', '');


         for ($i = 0; $i < $n_pacelas; $i++) {
            // Adiciona a parcela ao array

            $doc = "Lanc. Avulso " . ($i + 1) . "/" . $n_pacelas;
            $parcela = array(
               'dtvencimento' => $data_parcela->format('Y-m-d'),
               'valor' => $valor_parcela,
               'doc' => $doc
            );

            // Adiciona o array $parcela ao array $informacao
            $informacao[] = $parcela;

            // Adiciona o intervalo de dias à data da parcela para calcular a próxima parcela
            $data_parcela->add(new DateInterval("P" . $intervalo . "D"));
         }

         $total_parcelas = count($informacao);
         $valor_total_parcelas = array_sum(array_column($informacao, 'valor'));

         if ($total_parcelas > 0 && $valor_total_parcelas != $valor_liquido) {
            // Calcula a diferença entre o valor líquido e o total das parcelas
            $diferenca = $valor_liquido - $valor_total_parcelas;

            // Atribui a diferença à última parcela
            $informacao[$total_parcelas - 1]['valor'] += $diferenca;
         }


         $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
      }
   }
   if ($acao == "cancelar_lancamento_nf") { //lançamento financeiro
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);

      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = utf8_decode($value);
         // Remover aspas simples usando str_replace
         ${$name} = str_replace("'", "", ${$name});
      }


      /*dados do lançamento */
      $codigo_nf = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id, 'cl_codigo_nf'); //codigo da os
      $data_pagamento = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id, 'cl_data_pagamento'); //codigo da os
      $valor_lancamento = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id, 'cl_valor_liquido'); //codigo da os
      $conta_financeira = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id, 'cl_conta_financeira'); //
      $documento = utf8_encode(consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id, 'cl_documento')); //

      /*detalhe do caixa */
      $caixa =  verifica_caixa_financeiro($conecta, $data_pagamento, $conta_financeira);
      $retornar["dados"] = array("sucesso" => false, "title" => "Lançamento não identificado, favor, verifique");

      if ($id == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Lançamento não identificado, favor, verifique");
      } elseif (($caixa['resultado']) == "") { //verificar se o caixa já foi aberto
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("VAZIO")); //alertar o usuario que o caixa ainda não foi aberto
      } elseif ($caixa['status'] == "fechado") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("FECHADO")); //alertar o usuario que o caixa está fechado
      } elseif ($check_autorizador == "false") {
         $retornar["dados"] =  array("sucesso" => "autorizar", "title" =>  "Continue com a operação autorizando com a senha");
      } else {
         /*Detalhe da venda */
         if ($codigo_nf != '') {
            $numero_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, 'cl_numero_nf');
            $serie_nf = consulta_tabela($conecta,  "tb_nf_saida", "cl_codigo_nf", $codigo_nf, 'cl_serie_nf');
            $valor_adto = consulta_tabela($conecta,  "tb_nf_saida", "cl_codigo_nf", $codigo_nf, 'cl_valor_adto'); //valor do adiantamento
         }

         /*atualizar valor do adiantmaneot na venda*/
         $novo_valor_adto = $valor_adto - $valor_lancamento;
         $update_valor_adto =  update_registro($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, '', '', "cl_valor_adto", $novo_valor_adto); //atualizar o valor do adiantamento
         $update_status_lancamento =  update_registro($conecta, "tb_lancamento_financeiro", "cl_id", $id, '', '', "cl_status_id", 5); //atualizar o status do lançamento

         if ($update_valor_adto and $update_status_lancamento) {
            $retornar["dados"] =  array("sucesso" => true, "title" => "Cancelamento realizado com sucesso");
            $mensagem = utf8_decode("Cancelou o lançamento $documento no valor de $valor_lancamento referente a $serie_nf $numero_nf");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

            // Se tudo ocorreu bem, confirme a transação
            mysqli_commit($conecta);
         } else {
            // Se ocorrer um erro, reverta a transação
            mysqli_rollback($conecta);
            $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
            $mensagem = utf8_decode("Tentativa sem sucesso de cencelar o lançamento $documento no valor de $valor_lancamento  referente a $serie_nf $numero_nf");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }
   echo json_encode($retornar);
}


// if (isset($_POST['consultar_select'])) {
//    include "../../../conexao/conexao.php";
//    include "../../../funcao/funcao.php";
//    $retornar = array();
//    $array_consulta_status_lancamento = array();
//    $array_consulta_classificao_financeiro = array();

//    $select = "SELECT * from tb_status_recebimento";
//    $consultar_status_lancamento = mysqli_query($conecta, $select);

//    $select = "SELECT * from tb_classificacao_financeiro";
//    $consultar_classificao_financeiro = mysqli_query($conecta, $select);



//    if ($consultar_status_lancamento) {
//       while ($linha = mysqli_fetch_assoc($consultar_status_lancamento)) {
//          $descricao = utf8_encode($linha['cl_descricao']);
//          $id = $linha['cl_id'];

//          $informacao = array(
//             "descricao" => $descricao,
//             'id' => $id,

//          );
//          array_push($array_consulta_status_lancamento, $informacao);
//       }
//    }

//    if ($consultar_classificao_financeiro) {
//       while ($linha = mysqli_fetch_assoc($consultar_classificao_financeiro)) {
//          $descricao = utf8_encode($linha['cl_descricao']);
//          $id = $linha['cl_id'];

//          $informacao = array(
//             "descricao" => $descricao,
//             'id' => $id,
//          );
//          array_push($array_consulta_classificao_financeiro, $informacao);
//       }
//    }
//    $retornar["dados"] = array("sucesso" => true, "status_lancamento" => $array_consulta_status_lancamento, "classificao_financeiro" => $array_consulta_classificao_financeiro);

//    echo json_encode($retornar);
// }


//consultar conta financeira
$select = "SELECT * from tb_conta_financeira ";
$consultar_conta_financeira = mysqli_query($conecta, $select);


//consultar status recebimento
$select = "SELECT * from tb_status_recebimento ";
$consultar_status_recebimento = mysqli_query($conecta, $select);

//consultar forma pagamento
$select = "SELECT * from tb_forma_pagamento ";
$consultar_forma_pagamento = mysqli_query($conecta, $select);


//consultar classificacao financeiro
$select = "SELECT * from tb_classificacao_financeiro";
$consultar_classificacao_financeiro = mysqli_query($conecta, $select);
