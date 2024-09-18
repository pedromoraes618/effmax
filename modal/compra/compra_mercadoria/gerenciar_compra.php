<?php

if (isset($_GET['adicicionar_nf_entrada']) or isset($_GET['editar_nf_entrada'])) { //modal tela de compra
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";
   if (isset($_GET['form_id'])) {
      $form_id = $_GET['form_id'];
      $codigo_nf = $_GET['codigo_nf'];
   } else {
      $form_id = null;
      $codigo_nf = null;
   }
}

if (isset($_GET['produto_nf_entrada'])) { //modal de produto
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";

   if (isset($_GET['item_id'])) {
      $item_id = $_GET['item_id'];
   } else {
      $item_id = "";
   }

   if (isset($_GET['codigo_nf'])) {
      $codigo_nf = $_GET['codigo_nf'];
   } else {
      $codigo_nf = "";
   }
   if (isset($_GET['acao'])) {
      $acao = $_GET['acao'];
   } else {
      $acao = "";
   }
}

//consultar informações para tabela devolucao
if (isset($_GET['consultar_devolucao_compra'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   $consulta = $_GET['consultar_devolucao_compra'];
   $data_inicial = $_GET['data_inicial'];
   $data_final = $_GET['data_final'];

   // //formatar data para o banco de dados
   // $data_inicial =  formatarDataParaBancoDeDados($data_inicial);
   // $data_final =  formatarDataParaBancoDeDados($data_final);

   if ($consulta == "inicial") {
      $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1
      $select = "SELECT nf.cl_cpf_cnpj_avulso_nf,nf.cl_parceiro_id, nf.cl_numero_protocolo, nf.cl_pdf_nf,  nf.cl_numero_nf_devolucao, nf.cl_codigo_nf, nf.cl_status_venda, fpg.cl_tipo_pagamento_id as tipopg, nf.cl_id as id,nf.cl_data_movimento,nf.cl_numero_nf,nf.cl_serie_nf,nf.cl_status_recebimento,user.cl_usuario as vendedor,
      nf.cl_valor_desconto,nf.cl_valor_liquido,prc.cl_razao_social,prc.cl_nome_fantasia,fpg.cl_descricao as formapgt from tb_nf_saida as nf inner join tb_parceiros as prc on prc.cl_id = nf.cl_parceiro_id inner join
       tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id inner join tb_users as user on user.cl_id = nf.cl_vendedor_id WHERE
        nf.cl_data_movimento between '$data_inicial' and '$data_final' and nf.cl_operacao='DEVCOMPRA' order by nf.cl_id desc";
      $consultar_venda_mercadoria = mysqli_query($conecta, $select);
      if (!$consultar_venda_mercadoria) {
         die("Falha no banco de dados");
      } else {
         $qtd = mysqli_num_rows($consultar_venda_mercadoria); //quantidade de registros
      }
   } else {
      $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
      $status_recebimento = $_GET['status_recebimento'];

      $select = "SELECT nf.cl_cpf_cnpj_avulso_nf,nf.cl_parceiro_id,nf.cl_numero_protocolo,nf.cl_pdf_nf,  nf.cl_numero_nf_devolucao, nf.cl_codigo_nf, nf.cl_status_venda, fpg.cl_tipo_pagamento_id as tipopg, nf.cl_id as id,nf.cl_data_movimento,nf.cl_numero_nf,nf.cl_serie_nf,nf.cl_status_recebimento,user.cl_usuario as vendedor,
      nf.cl_valor_desconto,nf.cl_valor_liquido,prc.cl_razao_social,prc.cl_nome_fantasia,fpg.cl_descricao as formapgt from tb_nf_saida as nf inner join
       tb_parceiros as prc on prc.cl_id = nf.cl_parceiro_id inner join
       tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id inner join tb_users as user on user.cl_id = nf.cl_vendedor_id WHERE nf.cl_data_movimento between '$data_inicial' and '$data_final' and 
      ( nf.cl_numero_nf  like '%$pesquisa%' or prc.cl_razao_social  like '%$pesquisa%'
      or prc.cl_nome_fantasia  like '%$pesquisa%' ) and nf.cl_operacao='DEVCOMPRA' ";

      if ($status_recebimento != "0") {
         $select .= " and nf.cl_status_recebimento = '$status_recebimento' and nf.cl_status_venda ='1' ";
      }

      $select .= " order by nf.cl_id desc";
      $consultar_venda_mercadoria = mysqli_query($conecta, $select);
      if (!$consultar_venda_mercadoria) {
         die("Falha no banco de dados");
      } else {
         $qtd = mysqli_num_rows($consultar_venda_mercadoria);
      }
   }
}


//consultar informações para tabela
if (isset($_GET['consultar_compra'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   $consulta = $_GET['consultar_compra'];

   if ($consulta == "inicial") {

      $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1
      $select = "SELECT nf.cl_codigo_nf,nf.cl_parceiro_id, nf.cl_status_provisionamento, nf.cl_status_nf, nf.cl_id as idnota,
       prc.cl_razao_social as fornecedor,fpg.cl_descricao as formapgt, nf.cl_data_entrada,
      nf.cl_data_emissao,nf.cl_numero_nf,nf.cl_serie_nf,nf.cl_forma_pagamento_id,nf.cl_valor_total_nota
      from tb_nf_entrada as nf inner join tb_parceiros as prc on prc.cl_id = nf.cl_parceiro_id 
      left join tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id where 
      nf.cl_data_entrada between '$data_inicial_mes_bd' and '$data_final_mes_bd' order by nf.cl_data_entrada desc ";
      $consultar_compra = mysqli_query($conecta, $select);
      if (!$consultar_compra) {
         die("Erro na consulta" . mysqli_error($conecta));
      } else {
         $qtd = mysqli_num_rows($consultar_compra);
      }
   } else {
      $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
      $data_inicial = $_GET['data_inicial'];
      $data_final = $_GET['data_final'];
      $tipo_dt = $_GET['tipo_dt'];

      if ($tipo_dt == "dt_emissao") {
         $tipo_dt = "nf.cl_data_emissao";
      } else {
         $tipo_dt = "nf.cl_data_entrada";
      }

      $select = "SELECT  nf.cl_codigo_nf,nf.cl_parceiro_id, nf.cl_status_provisionamento, nf.cl_status_nf,nf.cl_id as idnota, prc.cl_razao_social as fornecedor,fpg.cl_descricao as formapgt, nf.cl_data_entrada,nf.cl_data_emissao,nf.cl_numero_nf,nf.cl_serie_nf,nf.cl_forma_pagamento_id,nf.cl_valor_total_nota
      from tb_nf_entrada as nf inner join tb_parceiros as prc on prc.cl_id = nf.cl_parceiro_id left join tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id
      WHERE $tipo_dt between '$data_inicial' and '$data_final' and ( nf.cl_numero_nf like '%{$pesquisa}%' or prc.cl_razao_social like '%{$pesquisa}%' 
       or prc.cl_nome_fantasia like '%{$pesquisa}%' 
       or prc.cl_cnpj_cpf like '%{$pesquisa}%' ) order by $tipo_dt desc ";
      $consultar_compra = mysqli_query($conecta, $select);
      if (!$consultar_compra) {
         die("Erro na consulta" . mysqli_error($conecta));
      } else {
         $qtd = mysqli_num_rows($consultar_compra);
      }
   }
}

if (isset($_GET['consultar_tabela_item_nf_entrada'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   $codigo_nf = $_GET['codigo_nf'];

   $select = "SELECT * FROM tb_nf_entrada_item where cl_codigo_nf ='$codigo_nf' ";
   $consultar_compra_item = mysqli_query($conecta, $select);
   if (!$consultar_compra_item) {
      die("Erro na consulta" . mysqli_error($conecta));
   } else {
      $qtd = mysqli_num_rows($consultar_compra_item);
   }
}

if (isset($_POST['formulario_nf_entrada'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";
   $retornar = array();
   $acao = $_POST['acao'];

   $id_user_logado = verifica_sessao_usuario();
   $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $id_user_logado, "cl_usuario");

   if ($acao == "alterar") { //insert da nf
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);

      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = utf8_decode($value);
         // Remover aspas simples usando str_replace
         ${$name} = str_replace("'", "", ${$name});
      }
      
      $informacoes_adicionais = utf8_decode($informacoes_adicionais);

      $valida_codigo_nf = consulta_tabela($conecta, "tb_nf_entrada", "cl_codigo_nf", $codigo_nf, "cl_id");
      if ($valida_codigo_nf == "") {
         $totais = resumo_valor_nf_entrada($conecta, $codigo_nf); //informações sobre os itens na nota
         $vlr_total_produtos = $totais['total_valor_produtos'];
         $icms_sub_nota = $totais['total_valor_icms_sub'];
         $ipi_nota = $totais['total_valor_ipi'];
         if ($codigo_nf == "") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('Favor, feche a tela e adicione novamente'));
         } elseif ($data_entrada == "") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('data entrada'));
         } elseif ($data_emissao == "") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('data emissao'));
         } elseif ($numero_nf == "") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('Nº NF'));
         } elseif ($fpagamento == "0") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('forma de pagamento'));
         } elseif ($cfop == "0") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('cfop'));
         } elseif ($serie == "0") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('série'));
         } elseif ($frete == "SN") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('frete'));
         } elseif ($chave_acesso == "") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('chave de acesso'));
         } elseif ($protocolo == "") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('protocolo'));
         } elseif ($parceiro_id == "") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('fornecedor'));
         } elseif (consulta_tabela($conecta, "tb_nf_entrada", "cl_chave_acesso", $chave_acesso, "cl_id") != "") {
            $retornar["dados"] =  array("sucesso" => false, "title" => 'Já exite uma nota com essa chave de acesso no sistema, favor, verifique');
         } elseif (consulta_tabela($conecta, "tb_nf_entrada", "cl_prot_autorizacao", $protocolo, "cl_id") != "") {
            $retornar["dados"] =  array("sucesso" => false, "title" => 'Já exite uma nota com esse numero de protocolo, favor, verifique');
         } elseif ($vlr_total_produtos == 0) {
            $retornar["dados"] =  array("sucesso" => false, "title" => 'Não é poossivel adicionar essa compra, é necessario incluir os itens, favor, verifique');
         } else {


            // Verifique e formate os campos que requerem substituição de vírgula por ponto
            $campos_com_virgula = array(
               'vfrete',
               'vfrete_conhecimento',
               'vseguro',
               'outras_despesas',
               'desconto_nota',
            );

            foreach ($campos_com_virgula as $campo) {
               if (verificaVirgula($$campo)) {
                  $$campo = formatDecimal($$campo);
               }
            }

            $valida_item = consulta_tabela_2_filtro($conecta, "tb_nf_entrada_item", "cl_codigo_nf", $codigo_nf, "cl_produto_id", " ", "cl_id"); //verificar se todos os itens foram selecionados
            $valor_total_nota = $icms_sub_nota + $ipi_nota + $vlr_total_produtos + $vfrete + $outras_despesas + $vseguro - $desconto_nota;

            $insert = "INSERT INTO `tb_nf_entrada` ( `cl_codigo_nf`, `cl_data_entrada`, `cl_data_emissao`, `cl_chave_acesso`,
          `cl_prot_autorizacao`, `cl_numero_nf`, `cl_serie_nf`, `cl_parceiro_id`, `cl_transportadora_id`, `cl_frete_id`, `cl_forma_pagamento_id`, 
          `cl_cfop`, `cl_valor_frete`, `cl_valor_frete_conhecimento`, `cl_valor_outras_despesas`, `cl_valor_seguro`, `cl_valor_desconto`,
           `cl_valor_total_produtos`, `cl_valor_total_nota`, `cl_status_nf`, `cl_usuario_id`, `cl_status_provisionamento`, `cl_observacao` ) 
         VALUES ('$codigo_nf', '$data_entrada', '$data_emissao', '$chave_acesso', '$protocolo', '$numero_nf', '$serie', '$parceiro_id', '$transportadora_id', 
         '$frete', '$fpagamento', '$cfop', '$vfrete', '$vfrete_conhecimento', '$outras_despesas', '$vseguro', '$desconto_nota', '$vlr_total_produtos',
          '$valor_total_nota','2', '$id_user_logado', '1','$informacoes_adicionais' ) ";
            $operacao_insert = mysqli_query($conecta, $insert);
            if ($operacao_insert) {
               $novo_id_inserido = mysqli_insert_id($conecta);

               $retornar["dados"] =  array("sucesso" => true, "title" => "Compra lançada com sucesso", "nf_id" => $novo_id_inserido);
               $mensagem = utf8_decode("Adicionou a compra $serie $numero_nf");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

               // Se tudo ocorreu bem, confirme a transação
               mysqli_commit($conecta);
            } else {
               // Se ocorrer um erro, reverta a transação
               mysqli_rollback($conecta);
               $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
               $mensagem = utf8_decode("tentativa sem sucesso de adicionou a compra $serie $numero_nf ");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
         }
      } else { //update
         $totais = resumo_valor_nf_entrada($conecta, $codigo_nf); //informações sobre os itens na nota
         $vlr_total_produtos = $totais['total_valor_produtos'];
         $icms_sub_nota = $totais['total_valor_icms_sub'];
         $ipi_nota = $totais['total_valor_ipi'];

         $provisionamento = consulta_tabela($conecta, "tb_nf_entrada", "cl_codigo_nf", $codigo_nf, "cl_status_provisionamento");
         $status_nf = consulta_tabela($conecta, "tb_nf_entrada", "cl_codigo_nf", $codigo_nf, "cl_status_nf");

         if ($codigo_nf == "") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('Favor, feche a tela e adicione novamente'));
         } elseif ($data_entrada == "") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('data entrada'));
         } elseif ($data_emissao == "") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('data emissao'));
         } elseif ($numero_nf == "") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('Nº NF'));
         } elseif ($fpagamento == "0") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('forma de pagamento'));
         } elseif ($cfop == "0") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('cfop'));
         } elseif ($serie == "0") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('série'));
         } elseif ($frete == "SN") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('frete'));
         } elseif ($chave_acesso == "") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('chave de acesso'));
         } elseif ($protocolo == "") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('protocolo'));
         } elseif ($parceiro_id == "") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('fornecedor'));
         } elseif ($vlr_total_produtos == 0) {
            $retornar["dados"] =  array("sucesso" => false, "title" => 'Não é poossivel alterar essa compra, é necessario incluir os itens, favor, verifique');
         } elseif (verifica_dupliidade_de_dados_outros($conecta, "tb_nf_entrada", "cl_chave_acesso", $chave_acesso, "cl_id", $id) > 0) {
            $retornar["dados"] =  array("sucesso" => false, "title" => 'Já exite uma nota com essa chave de acesso no sistema, favor, verifique');
         } elseif (verifica_dupliidade_de_dados_outros($conecta, "tb_nf_entrada", "cl_prot_autorizacao", $protocolo, "cl_id", $id) > 0) {
            $retornar["dados"] =  array("sucesso" => false, "title" => 'Já exite uma nota com esse numero de protocolo, favor, verifique');
         } elseif ($provisionamento == "2") {
            $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel alterar essa compra, a compra está provisionada, favor, verifique");
         } elseif ($status_nf == "3") {
            $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel alterar essa compra, a compra está cancelada, favor, verifique");
         } else {
            // Verifique e formate os campos que requerem substituição de vírgula por ponto
            $campos_com_virgula = array(
               'vfrete',
               'vfrete_conhecimento',
               'vseguro',
               'outras_despesas',
               'desconto_nota',
            );

            foreach ($campos_com_virgula as $campo) {
               if (verificaVirgula($$campo)) {
                  $$campo = formatDecimal($$campo);
               }
            }

            $valida_item = consulta_tabela_2_filtro($conecta, "tb_nf_entrada_item", "cl_codigo_nf", $codigo_nf, "cl_produto_id", " ", "cl_id"); //verificar se todos os itens foram selecionados

            $valor_total_nota = $icms_sub_nota + $ipi_nota + $vlr_total_produtos + $vfrete + $outras_despesas + $vseguro - $desconto_nota;

            $update = "UPDATE `tb_nf_entrada` SET `cl_data_entrada` = '$data_entrada', `cl_data_emissao` = '$data_emissao', `cl_chave_acesso` = '$chave_acesso',
          `cl_prot_autorizacao` = '$protocolo', `cl_numero_nf` = '$numero_nf', `cl_serie_nf` = '$serie', `cl_parceiro_id` = '$parceiro_id', `cl_transportadora_id` = '$transportadora_id',
           `cl_frete_id` = '$frete', `cl_forma_pagamento_id` = '$fpagamento', `cl_cfop` = '$cfop', `cl_valor_frete` = '$vfrete', `cl_valor_frete_conhecimento` = '$vfrete_conhecimento',
            `cl_valor_outras_despesas` = '$outras_despesas', `cl_valor_seguro` = '$vseguro', `cl_valor_desconto` = '$desconto_nota', 
            `cl_valor_total_produtos` = '$vlr_total_produtos',`cl_status_nf` = '2', `cl_valor_total_nota` = '$valor_total_nota', `cl_observacao` = '$informacoes_adicionais',
             `cl_usuario_id` = '$id_user_logado' WHERE `cl_id` = $id ";
            $operacao_update = mysqli_query($conecta, $update);
            if ($operacao_update) {
               $retornar["dados"] =  array("sucesso" => true, "title" => "Compra alterada com sucesso", "nf_id" => "");
               $mensagem = utf8_decode("Alterou a compra $serie $numero_nf");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

               // Se tudo ocorreu bem, confirme a transação
               mysqli_commit($conecta);
            } else {
               // Se ocorrer um erro, reverta a transação
               mysqli_rollback($conecta);
               $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
               $mensagem = utf8_decode("tentativa sem sucesso de alterar a compra $serie $numero_nf ");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
         }
      }
   } elseif ($acao == "finalizar_nf") {
      //insert da nf
      $dadosJSON = $_POST['dados']; //array de produtos
      $dados = json_decode($dadosJSON, true); //recuperar valor do array javascript decodificando o json
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);

      $id = $dados['id'];
      $codigo_nf = $dados['codigo_nf'];
      $data_entrada = $dados['data_entrada'];
      $data_emissao = $dados['data_emissao'];
      $numero_nf = $dados['numero_nf'];
      $fpagamento = $dados['fpagamento'];
      $cfop = $dados['cfop'];
      $serie = $dados['serie'];
      $frete = $dados['frete'];
      $chave_acesso = $dados['chave_acesso'];
      $protocolo = $dados['protocolo'];
      $parceiro_id = $dados['parceiro_id'];
      $transportadora_id = $dados['transportadora_id'];
      $vfrete = $dados['vfrete'];
      $vfrete_conhecimento = $dados['vfrete_conhecimento'];
      $vseguro = $dados['vseguro'];
      $outras_despesas = $dados['outras_despesas'];
      $desconto_nota = $dados['desconto_nota'];
      $informacoes_adicionais = utf8_decode($dados['informacoes_adicionais']);


      $totais = resumo_valor_nf_entrada($conecta, $codigo_nf); //informações sobre os itens na nota
      $vlr_total_produtos = $totais['total_valor_produtos'];
      $icms_sub_nota = $totais['total_valor_icms_sub'];
      $ipi_nota = $totais['total_valor_ipi'];

      $valida_associar_produto = consulta_tabela_2_filtro($conecta, "tb_nf_entrada_item", "cl_codigo_nf", $codigo_nf, "cl_produto_id", "", "cl_id");

      if ($codigo_nf == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('Favor, feche a tela e adicione novamente'));
      } elseif ($data_entrada == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('data entrada'));
      } elseif ($data_emissao == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('data emissao'));
      } elseif ($numero_nf == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('Nº NF'));
      } elseif ($fpagamento == "0") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('forma de pagamento'));
      } elseif ($cfop == "0") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('cfop'));
      } elseif ($serie == "0") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('série'));
      } elseif ($frete == "SN") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('frete'));
      } elseif ($chave_acesso == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('chave de acesso'));
      } elseif ($protocolo == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('protocolo'));
      } elseif ($parceiro_id == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('fornecedor'));
      } elseif (verifica_dupliidade_de_dados_outros($conecta, "tb_nf_entrada", "cl_chave_acesso", $chave_acesso, "cl_id", $id) > 0) {
         $retornar["dados"] =  array("sucesso" => false, "title" => 'Já exite uma nota com essa chave de acesso no sistema, favor, verifique');
      } elseif (verifica_dupliidade_de_dados_outros($conecta, "tb_nf_entrada", "cl_prot_autorizacao", $protocolo, "cl_id", $id) > 0) {
         $retornar["dados"] =  array("sucesso" => false, "title" => 'Já exite uma nota com esse numero de protocolo, favor, verifique');
      } elseif ($vlr_total_produtos == 0) {
         $retornar["dados"] =  array("sucesso" => false, "title" => 'Não é poossivel finalizar essa compra, é necessario incluir os itens, favor, verifique');
      } elseif ($valida_associar_produto != "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => 'Não é poossivel adicionar essa compra, é necessario associar os itens, favor, verifique, os itens que não foram associados estão em vermelho');
      } else {


         // Verifique e formate os campos que requerem substituição de vírgula por ponto
         $campos_com_virgula = array(
            'vfrete',
            'vfrete_conhecimento',
            'vseguro',
            'outras_despesas',
            'desconto_nota',
         );

         foreach ($campos_com_virgula as $campo) {
            if (verificaVirgula($$campo)) {
               $$campo = formatDecimal($$campo);
            }
         }

         $valida_item = consulta_tabela_2_filtro($conecta, "tb_nf_entrada_item", "cl_codigo_nf", $codigo_nf, "cl_produto_id", " ", "cl_id"); //verificar se todos os itens foram selecionados
         if ($valida_item == "") {
            $status_nf = 1; //todos os itens foram selecionados
         } else {
            $status_nf = 2; //faltam itens para selecionar status em andamento
         }

         $valor_total_nota = $icms_sub_nota + $ipi_nota + $vlr_total_produtos + $vfrete + $outras_despesas + $vseguro - $desconto_nota;

         if ($id == "") { //insert
            $insert = "INSERT INTO `tb_nf_entrada` ( `cl_codigo_nf`, `cl_data_entrada`, `cl_data_emissao`, `cl_chave_acesso`,
            `cl_prot_autorizacao`, `cl_numero_nf`, `cl_serie_nf`, `cl_parceiro_id`, `cl_transportadora_id`, `cl_frete_id`, `cl_forma_pagamento_id`, 
            `cl_cfop`, `cl_valor_frete`, `cl_valor_frete_conhecimento`, `cl_valor_outras_despesas`, `cl_valor_seguro`, `cl_valor_desconto`,
             `cl_valor_total_produtos`, `cl_valor_total_nota`, `cl_status_nf`, `cl_usuario_id`, `cl_status_provisionamento`,`cl_observacao` ) 
           VALUES ('$codigo_nf', '$data_entrada', '$data_emissao', '$chave_acesso', '$protocolo', '$numero_nf', '$serie', '$parceiro_id', '$transportadora_id', 
           '$frete', '$fpagamento', '$cfop', '$vfrete', '$vfrete_conhecimento', '$outras_despesas', '$vseguro', '$desconto_nota', '$vlr_total_produtos', '$valor_total_nota',
            '$status_nf', '$id_user_logado', '1','$informacoes_adicionais' ) ";
            $operacao_insert = mysqli_query($conecta, $insert);
            if ($operacao_insert) {
               $novo_id_inserido = mysqli_insert_id($conecta);
               finalizar_produtos_nf_entrada($conecta, $codigo_nf, $data_lancamento, $id_user_logado); //atualizar o estoque e o historico do produto
               $retornar["dados"] =  array("sucesso" => true, "title" => "Compra finalizada com sucesso", "nf_id" => $novo_id_inserido);
               $mensagem = utf8_decode("Finalizou a compra $serie $numero_nf");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
               // Se tudo ocorreu bem, confirme a transação
               mysqli_commit($conecta);
            } else {
               // Se ocorrer um erro, reverta a transação
               mysqli_rollback($conecta);
               $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
               $mensagem = utf8_decode("tentativa sem sucesso de finalizar a compra $serie $numero_nf ");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
         } else {
            $update = "UPDATE `tb_nf_entrada` SET `cl_data_entrada` = '$data_entrada', `cl_data_emissao` = '$data_emissao', `cl_chave_acesso` = '$chave_acesso',
         `cl_prot_autorizacao` = '$protocolo', `cl_numero_nf` = '$numero_nf', `cl_serie_nf` = '$serie', `cl_parceiro_id` = '$parceiro_id', `cl_transportadora_id` = '$transportadora_id',
          `cl_frete_id` = '$frete', `cl_forma_pagamento_id` = '$fpagamento', `cl_cfop` = '$cfop', `cl_valor_frete` = '$vfrete', `cl_valor_frete_conhecimento` = '$vfrete_conhecimento',
           `cl_valor_outras_despesas` = '$outras_despesas', `cl_valor_seguro` = '$vseguro', `cl_valor_desconto` = '$desconto_nota', 
        `cl_valor_total_produtos` = '$vlr_total_produtos', `cl_valor_total_nota` = '$valor_total_nota',
         `cl_usuario_id` = '$id_user_logado',`cl_observacao` = '$informacoes_adicionais',
         `cl_status_nf` = '1'  WHERE `cl_id` = $id ";
            $operacao_update = mysqli_query($conecta, $update);
            if ($operacao_update) {
               finalizar_produtos_nf_entrada($conecta, $codigo_nf, $data_lancamento, $id_user_logado); //atualizar o estoque e o historico do produto
               $retornar["dados"] =  array("sucesso" => true, "title" => "Compra finalizada com sucesso", "nf_id" => "$id");
               $mensagem = utf8_decode("Finalizou a compra $serie $numero_nf");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
               // Se tudo ocorreu bem, confirme a transação
               mysqli_commit($conecta);
            } else {

               // Se ocorrer um erro, reverta a transação
               mysqli_rollback($conecta);
               $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
               $mensagem = utf8_decode("tentativa sem sucesso de finalizar a compra $serie $numero_nf ");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
         }
      }
   } elseif ($acao == 'insert_item') {

      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);


      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = utf8_decode($value);
         // Remover aspas simples usando str_replace
         ${$name} = str_replace("'", "", ${$name});
      }

      $descricao_produto = consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_descricao");
      $provisionamento = consulta_tabela($conecta, "tb_nf_entrada", "cl_codigo_nf", $codigo_nf, "cl_status_provisionamento");
      $status_nf = consulta_tabela($conecta, "tb_nf_entrada", "cl_codigo_nf", $codigo_nf, "cl_status_nf");

      if ($produto_id == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => 'Favor, Selecione o Item');
      } elseif ($codigo_nf == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => 'Favor, Feche a tela e recomeçe a incluir os itens');
      } elseif ($unidade == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("unidade"));
      } elseif ($quantidade == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("quantidade"));
      } elseif ($preco_compra_unitario == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("valor unitário"));
      } elseif ($cfop_prod == "0") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("Cfop"));
      } elseif ($provisionamento == "2") {
         $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel adicionar esse item, a compra está provisionada, favor, verifique");
      } elseif ($status_nf == "3") {
         $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel adicionar esse item, a compra está cancelada, favor, verifique");
      } else {

         $valor_total = $quantidade * $preco_compra_unitario;

         // Verifique e formate os campos que requerem substituição de vírgula por ponto
         $campos_com_virgula = array(
            'quantidade',
            'preco_compra_unitario',
            'base_icms_prod',
            'vlr_icms_prod',
            'aliq_icms_prod',
            'ipi_prod',
            'aliq_ipi_prod', 'base_pis_prod', 'pis_prod', 'base_cofins_prod', 'cofins_prod', 'base_icms_sub_prod', 'vlr_icms_sub_prod',
         );

         foreach ($campos_com_virgula as $campo) {
            if (verificaVirgula($$campo)) {
               $$campo = formatDecimal($$campo);
            }
         }


         $insert = "INSERT INTO `tb_nf_entrada_item` (`cl_codigo_nf`, 
         `cl_produto_id`, `cl_descricao`, `cl_ncm`, `cl_cest`, `cl_cfop`, `cl_und`, `cl_quantidade`,
          `cl_valor_unitario`, `cl_valor_total`, `cl_bc_icms`, `cl_valor_icms`, `cl_aliq_icms`, `cl_cst_icms`,
         `cl_valor_ipi`, `cl_aliq_ipi`, `cl_bc_pis`, `cl_valor_pis`,
            `cl_cst_pis`, `cl_bc_cofins`, `cl_valor_cofins`, `cl_cst_cofins`, `cl_gtin`, 
            `cl_bc_icms_sub`,`cl_valor_icms_sub`, `cl_numero_pedido`, `cl_item_pedido`, `cl_referencia`, `cl_fabricante` ) VALUES
             ('$codigo_nf', '$produto_id', '$descricao_produto', '$ncm_prod', '$cest_prod', '$cfop_prod', '$unidade', 
             '$quantidade', '$preco_compra_unitario', '$valor_total',
              '$base_icms_prod', '$vlr_icms_prod',
          '$aliq_icms_prod', '$cst_icms_prod', '$ipi_prod', '$aliq_ipi_prod', '$base_pis_prod',
           '$pis_prod', '$cst_pis_prod', '$base_cofins_prod', '$cofins_prod', '$cst_cofins_prod',
            '$gtin', '$base_icms_sub_prod', '$vlr_icms_sub_prod','$numero_pedido','$item_pedido','$referencia_prod','$fabricante_prod' ) ";
         $operacao_insert = mysqli_query($conecta, $insert);
         if ($operacao_insert) {
            recalcular_valor_nf_entrada($conecta, $codigo_nf); //recalcular o valor da nota
            $retornar["dados"] =  array("sucesso" => true, "title" => "Item Incluido com sucesso");
            // Se tudo ocorreu bem, confirme a transação
            mysqli_commit($conecta);
         } else {

            // Se ocorrer um erro, reverta a transação
            mysqli_rollback($conecta);
            $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
         }
      }
   } elseif ($acao == 'update_item') {
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);

      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = utf8_decode($value);
         // Remover aspas simples usando str_replace
         ${$name} = str_replace("'", "", ${$name});
      }

      $descricao_produto = consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_descricao");
      $provisionamento = consulta_tabela($conecta, "tb_nf_entrada", "cl_codigo_nf", $codigo_nf, "cl_status_provisionamento");
      $status_nf = consulta_tabela($conecta, "tb_nf_entrada", "cl_codigo_nf", $codigo_nf, "cl_status_nf");

      if ($produto_id == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => 'Favor, Selecione o Item');
      } elseif ($codigo_nf == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => 'Favor, o código interno não foi informado, favor, contate o tecnico');
      } elseif ($unidade == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("unidade"));
      } elseif ($quantidade == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("quantidade"));
      } elseif ($preco_compra_unitario == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("valor unitário"));
      } elseif ($cfop_prod == "0") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("Cfop"));
      } elseif ($provisionamento == "2") {
         $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel alterar esse item, a compra está provisionada, favor, verifique");
      } elseif ($status_nf == "1") {
         $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel alterar esse item, a compra está finalizada, favor, verifique");
      } elseif ($status_nf == "3") {
         $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel alterar esse item, a compra está cancelada, favor, verifique");
      } else {
         $fabricante_prod = utf8_decode($fabricante_prod);
         $referencia_prod = utf8_decode($referencia_prod);
         $valor_total = $quantidade * $preco_compra_unitario;

         // Verifique e formate os campos que requerem substituição de vírgula por ponto
         $campos_com_virgula = array(
            'quantidade',
            'preco_compra_unitario',
            'base_icms_prod',
            'vlr_icms_prod',
            'aliq_icms_prod',
            'ipi_prod',
            'aliq_ipi_prod', 'base_pis_prod', 'pis_prod', 'base_cofins_prod', 'cofins_prod', 'base_icms_sub_prod', 'vlr_icms_sub_prod',
         );

         foreach ($campos_com_virgula as $campo) {
            if (verificaVirgula($$campo)) {
               $$campo = formatDecimal($$campo);
            }
         }

         $update = "UPDATE `tb_nf_entrada_item` SET `cl_produto_id` = '$produto_id', `cl_descricao` = '$descricao_produto', `cl_ncm` = '$ncm_prod',
          `cl_cest` = '$cest_prod', `cl_cfop` = '$cfop_prod',
          `cl_und` = '$unidade', `cl_quantidade` = '$quantidade', `cl_valor_unitario` = '$preco_compra_unitario', `cl_valor_total` = '$valor_total', 
          `cl_bc_icms` = '$base_icms_prod', `cl_valor_icms` = '$vlr_icms_prod',
           `cl_aliq_icms` = '$aliq_icms_prod', `cl_cst_icms` = '$cst_icms_prod', `cl_bc_icms_sub` = '$base_icms_sub_prod', `cl_valor_icms_sub` = '$vlr_icms_sub_prod',
            `cl_valor_ipi` = '$ipi_prod', `cl_aliq_ipi` = '$aliq_ipi_prod',
            `cl_bc_pis` = '$base_pis_prod', `cl_valor_pis` = '$pis_prod', `cl_cst_pis` = '$cst_pis_prod', `cl_bc_cofins` = '$base_cofins_prod',
             `cl_valor_cofins`  = '$cofins_prod', `cl_cst_cofins` = '$cst_cofins_prod', 
            `cl_gtin` = '$gtin', `cl_numero_pedido` = '$numero_pedido', 
            `cl_item_pedido` = '$item_pedido', `cl_referencia` = '$referencia_prod', `cl_fabricante` = '$fabricante_prod'   WHERE `cl_id` = $item_id ";
         $operacao_update = mysqli_query($conecta, $update);
         if ($operacao_update) {
            recalcular_valor_nf_entrada($conecta, $codigo_nf); //recalcular o valor da nota
            $retornar["dados"] =  array("sucesso" => true, "title" => "Item alterado com sucesso");

            // Se tudo ocorreu bem, confirme a transação
            mysqli_commit($conecta);
         } else {
            // Se ocorrer um erro, reverta a transação
            mysqli_rollback($conecta);
            $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
         }
      }
   } elseif ($acao == 'delete_item') {
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);

      $nf_id = $_POST['nf_id'];
      $codigo_nf = consulta_tabela($conecta, "tb_nf_entrada_item", "cl_id", $nf_id, "cl_codigo_nf");
      $status_nf = consulta_tabela($conecta, "tb_nf_entrada", "cl_codigo_nf", $codigo_nf, "cl_status_nf");
      $provisionamento = consulta_tabela($conecta, "tb_nf_entrada", "cl_codigo_nf", $codigo_nf, "cl_status_provisionamento");


      if ($nf_id == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => 'Favor, esse item não existe ');
      } elseif ($provisionamento == "2") {
         $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel remover esse item, a compra está provisionada, favor, verifique");
      } elseif ($status_nf == "1") {
         $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel remover esse item, a compra está finalizada, favor, verifique");
      } elseif ($status_nf == "3") {
         $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel remover esse item, a compra está cancelada, favor, verifique");
      } else {

         $delete = "DELETE FROM `tb_nf_entrada_item` WHERE `cl_id` = $nf_id ";
         $operacao_delete = mysqli_query($conecta, $delete);
         if ($operacao_delete) {
            recalcular_valor_nf_entrada($conecta, $codigo_nf); //recalcular o valor da nota
            $retornar["dados"] =  array("sucesso" => true, "title" => "Item removido com sucesso");

            // Se tudo ocorreu bem, confirme a transação
            mysqli_commit($conecta);
         } else {
            // Se ocorrer um erro, reverta a transação
            mysqli_rollback($conecta);
            $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
         }
      }
   } elseif ($acao == 'cadastrar_item') {
      //insert da nf
      $dadosJSON = $_POST['dados']; //array de produtos
      $dados = json_decode($dadosJSON, true); //recuperar valor do array javascript decodificando o json

      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);


      $codigo_nf = utf8_decode($dados['codigo_nf']);
      $numero_nf = consulta_tabela($conecta, "tb_nf_entrada", "cl_codigo_nf", $codigo_nf, "cl_numero_nf");
      $serie_nf = consulta_tabela($conecta, "tb_nf_entrada", "cl_codigo_nf", $codigo_nf, "cl_serie_nf");

      $item_id = utf8_decode($dados['item_id']);
      $descricao_produto = utf8_decode($dados['descricao_produto']);
      $unidade = utf8_decode($dados['unidade']);
      $ncm_prod = ($dados['ncm_prod']);
      $cest_prod = ($dados['cest_prod']);
      $cst_icms_prod = ($dados['cst_icms_prod']);
      $gtin = ($dados['gtin']);
      $cst_pis_prod = ($dados['cst_pis_prod']);
      $cst_cofins_prod = ($dados['cst_cofins_prod']);
      $referencia_prod = utf8_decode($dados['referencia_prod']);
      $fabricante_prod = utf8_decode($dados['fabricante_prod']);

      $subgrupo_default_id =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "54");
      $unidade_m_default_id =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "55");

      $custo = calcular_custo_item_nf($item_id);
      $valor_custo = ($custo['valor_custo']);

      $valida_unidade = consulta_tabela($conecta, "tb_unidade_medida", "cl_sigla", $unidade, "cl_id"); //veriofiar se existe a unidade de medida cadastrada no sistema
      if ($valida_unidade != "") {
         $unidade_m_default_id = $valida_unidade;
      }

      $insert = "INSERT INTO `tb_produtos` (`cl_data_cadastro`, 
      `cl_descricao`, `cl_referencia`,  `cl_cest`, `cl_ncm`, `cl_cst_icms`, `cl_cst_pis_e`, 
        `cl_cst_cofins_e`,`cl_preco_custo`,`cl_estoque`,`cl_estoque_minimo`,`cl_estoque_maximo`,
        `cl_cfop_interno`, `cl_cfop_externo`, `cl_grupo_id`, `cl_und_id`, `cl_tipo_id`, 
        `cl_status_ativo`,`cl_gtin`,`cl_fabricante`) VALUES ( '$data_lancamento', '$descricao_produto', '$referencia_prod', '$cest_prod',
         '$ncm_prod', '$cst_icms_prod', '$cst_pis_prod', '$cst_cofins_prod','$valor_custo',
         '0','0','0','5102', '6102', '$subgrupo_default_id', '$unidade_m_default_id', '1', 'SIM','$gtin','$fabricante_prod' ) ";
      $operacao_insert = mysqli_query($conecta, $insert);
      if ($operacao_insert) {
         $novo_id_inserido = mysqli_insert_id($conecta);

         update_registro($conecta, 'tb_nf_entrada_item', "cl_id", $item_id, "", "", "cl_produto_id", "$novo_id_inserido"); //atualizar o codigo de associação ao estoque
         update_registro($conecta, 'tb_nf_entrada_item', "cl_id", $item_id, "", "", "cl_fabricante", "$fabricante_prod"); //atualizar o fabricante
         update_registro($conecta, 'tb_nf_entrada_item', "cl_id", $item_id, "", "", "cl_referencia", "$referencia_prod"); //atualizar a referencia
         $retornar["dados"] =  array("sucesso" => true, "title" => "Item Incluido ao seu estoque sucesso");
         // Se tudo ocorreu bem, confirme a transação
         mysqli_commit($conecta);

         $mensagem = utf8_decode("Cadastrou o produto de código $novo_id_inserido por meio da nota fiscal $serie_nf $numero_nf");
         registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
      } else {
         // Se ocorrer um erro, reverta a transação
         mysqli_rollback($conecta);
         $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
      }
   } elseif ($acao == "show") {
      $nf_id = $_POST['form_id'];
      $select = "SELECT * FROM tb_nf_entrada where cl_id ='$nf_id' ";
      $consultar_nf_entrada = mysqli_query($conecta, $select);
      $linha = mysqli_fetch_assoc($consultar_nf_entrada);
      $numero_nf = $linha['cl_numero_nf'];
      $data_entrada = $linha['cl_data_entrada'];
      $data_emissao = $linha['cl_data_emissao'];
      $fpagamento = $linha['cl_forma_pagamento_id'];
      $cfop = $linha['cl_cfop'];
      $serie = $linha['cl_serie_nf'];
      $forma_pagamento_id = $linha['cl_forma_pagamento_id'];
      $frete = $linha['cl_frete_id'];
      $chave_acesso = $linha['cl_chave_acesso'];
      $protocolo = $linha['cl_prot_autorizacao'];
      $vfrete = $linha['cl_valor_frete'];
      $vfrete_conhecimento = $linha['cl_valor_frete_conhecimento'];
      $vseguro = $linha['cl_valor_seguro'];
      $outras_despesas = $linha['cl_valor_outras_despesas'];
      $desconto_nota = $linha['cl_valor_desconto'];
      $vlr_total_nota = $linha['cl_valor_total_nota'];
      $observacao = utf8_encode($linha['cl_observacao']);


      $status_nf = $linha['cl_status_nf'];
      $status_provisionamento = $linha['cl_status_provisionamento'];


      $parceiro_id = $linha['cl_parceiro_id'];
      $parceiro_descricao = utf8_encode(consulta_tabela($conecta, "tb_parceiros", "cl_id", $parceiro_id, "cl_razao_social"));

      $transportadora_id = $linha['cl_transportadora_id'];
      $transportadora_descricao = utf8_encode(consulta_tabela($conecta, "tb_parceiros", "cl_id", $transportadora_id, "cl_razao_social"));

      $informacao = array(
         "numero_nf" => $numero_nf,
         "data_entrada" => $data_entrada,
         "data_emissao" => $data_emissao,
         "fpagamento" => $fpagamento,
         "cfop" => $cfop,
         "serie" => $serie,
         "frete" => $frete,
         "chave_acesso" => $chave_acesso,
         "protocolo" => $protocolo,
         "vfrete" => $vfrete,
         "vfrete_conhecimento" => $vfrete_conhecimento,
         "vseguro" => $vseguro,
         "outras_despesas" => $outras_despesas,
         "desconto_nota" => $desconto_nota,
         "vlr_total_nota" => $vlr_total_nota,

         "status_nf" => $status_nf,
         "status_provisionamento" => $status_provisionamento,

         "parceiro_id" => $parceiro_id,
         "parceiro_descricao" => $parceiro_descricao,

         "transportadora_id" => $transportadora_id,
         "transportadora_descricao" => $transportadora_descricao,
         "observacao" => $observacao,



      );

      $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
   } elseif ($acao == "show_item") {
      $nf_id = $_POST['form_id'];
      $select = "SELECT * FROM tb_nf_entrada_item where cl_id ='$nf_id' ";
      $consultar_nf_entrada_item = mysqli_query($conecta, $select);
      $linha = mysqli_fetch_assoc($consultar_nf_entrada_item);
      $descricao = utf8_encode($linha['cl_descricao']);
      $produto_id = $linha['cl_produto_id'];
      $quantidade = $linha['cl_quantidade'];
      $valor_unitario = $linha['cl_valor_unitario'];
      $valor_total  = $valor_unitario * $quantidade;

      $ncm_prod = $linha['cl_ncm'];
      $cest_prod = $linha['cl_cest'];
      $cfop_prod = $linha['cl_cfop'];
      $und = utf8_encode($linha['cl_und']);

      $base_icms_prod = $linha['cl_bc_icms'];
      $vlr_icms_prod = $linha['cl_valor_icms'];
      $aliq_icms_prod = $linha['cl_aliq_icms'];
      $cst_icms_prod = $linha['cl_cst_icms'];
      $base_icms_sub_prod = $linha['cl_bc_icms_sub'];
      $vlr_icms_sub_prod = $linha['cl_valor_icms_sub'];
      $ipi_prod = $linha['cl_valor_ipi'];
      $aliq_ipi_prod = $linha['cl_aliq_ipi'];
      $base_pis_prod = $linha['cl_bc_pis'];
      $pis_prod = $linha['cl_valor_pis'];
      $cst_pis_prod = $linha['cl_cst_pis'];
      $base_cofins_prod = $linha['cl_bc_cofins'];
      $cofins_prod = $linha['cl_valor_cofins'];
      $cst_cofins_prod = $linha['cl_cst_cofins'];
      $gtin = $linha['cl_gtin'];
      $numero_pedido = $linha['cl_numero_pedido'];
      $item_pedido = $linha['cl_item_pedido'];
      $referencia_prod = utf8_encode($linha['cl_referencia']);
      $fabricante = utf8_encode($linha['cl_fabricante']);

      $informacao = array(
         "produto_id" => $produto_id,
         "descricao" => $descricao,
         "quantidade" => $quantidade,
         "preco_compra_unitario" => $valor_unitario,
         "unidade" => $und,

         "cfop_prod" => $cfop_prod,
         "ncm_prod" => $ncm_prod,
         "cest_prod" => $cest_prod,
         "cst_icms_prod" => $cst_icms_prod,
         "base_icms_prod" => $base_icms_prod,
         "aliq_icms_prod" => $aliq_icms_prod,
         "vlr_icms_prod" => $vlr_icms_prod,
         "base_icms_sub_prod" => $base_icms_sub_prod,
         "vlr_icms_sub_prod" => $vlr_icms_sub_prod,
         "aliq_ipi_prod" => $aliq_ipi_prod,
         "ipi_prod" => $ipi_prod,
         "base_pis_prod" => $base_pis_prod,
         "pis_prod" => $pis_prod,
         "cst_pis_prod" => $cst_pis_prod,
         "base_cofins_prod" => $base_cofins_prod,
         "cofins_prod" => $cofins_prod,
         "cst_cofins_prod" => $cst_cofins_prod,
         "numero_pedido" => $numero_pedido,
         "item_pedido" => $item_pedido,
         "gtin" => $gtin,
         "valor_total_compra" => $valor_total,
         "referencia_prod" => $referencia_prod,
         "fabricante_prod" => $fabricante,
      );

      $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
   } elseif ($acao == "resumo_valores") {
      $codigo_nf = $_POST['codigo_nf'];
      $totais = resumo_valor_nf_entrada($conecta, $codigo_nf);
      $bcicms_nota = $totais['total_base_icms'];
      $icms_nota = $totais['total_valor_icms'];
      $bcicms_sub_nota = $totais['total_bc_icms_sub'];
      $icms_sub_nota = $totais['total_valor_icms_sub'];
      $ipi_nota = $totais['total_valor_ipi'];
      $vlr_total_produtos = $totais['total_valor_produtos'];


      $informacao = array(
         "bcicms_nota" => $bcicms_nota,
         "icms_nota" => $icms_nota,
         "bcicms_sub_nota" => $bcicms_sub_nota,
         "icms_sub_nota" => $icms_sub_nota,
         "ipi_nota" => $ipi_nota,
         "vlr_total_produtos" => $vlr_total_produtos,
      );

      $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
   } elseif ($acao == "remover_nf") {

      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);

      $nf_id = $_POST['nf_id'];
      $codigo_nf = consulta_tabela($conecta, "tb_nf_entrada", "cl_id", $nf_id, "cl_codigo_nf");
      $serie = consulta_tabela($conecta, "tb_nf_entrada", "cl_id", $nf_id, "cl_serie_nf");
      $numero_nf = consulta_tabela($conecta, "tb_nf_entrada", "cl_id", $nf_id, "cl_numero_nf");


      if (
         remover_linha($conecta, "tb_nf_entrada", "cl_id", $nf_id) and
         remover_linha($conecta, "tb_lancamento_financeiro", "cl_codigo_nf", $codigo_nf)  and cancelar_ajuste_estoque($conecta, $codigo_nf, "3")
      ) {      //remover o fianceiro da nota) {//remover a nota
         $retornar["dados"] = array("sucesso" => true, "title" => "Compra removida com sucesso");

         $mensagem = utf8_decode("Removeu a compra $serie $numero_nf");
         registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

         // Se tudo ocorreu bem, confirme a transação
         mysqli_commit($conecta);
      } else {
         // Se ocorrer um erro, reverta a transação
         mysqli_rollback($conecta);
         $retornar["dados"] = array("sucesso" => false, "title" => "Erro, comunique o suporte");
         $mensagem = utf8_decode("tentativa sem sucesso de remover a compra $serie $numero_nf");
         registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
      }
   } elseif ($acao == "cancelar_nf") {
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);

      $nf_id = $_POST['nf_id'];
      $codigo_nf = consulta_tabela($conecta, "tb_nf_entrada", "cl_id", $nf_id, "cl_codigo_nf");
      $serie = consulta_tabela($conecta, "tb_nf_entrada", "cl_id", $nf_id, "cl_serie_nf");
      $numero_nf = consulta_tabela($conecta, "tb_nf_entrada", "cl_id", $nf_id, "cl_numero_nf");

      if (
         update_registro($conecta, "tb_nf_entrada", "cl_id", $nf_id, "", "", "cl_status_nf", "3") and update_registro($conecta, "tb_nf_entrada", "cl_id", $nf_id, "", "", "cl_status_provisionamento", "1") and
         remover_linha($conecta, "tb_lancamento_financeiro", "cl_codigo_nf", $codigo_nf) and cancelar_ajuste_estoque($conecta, $codigo_nf, "1")
      ) { //remover o fianceiro da nota) {//remover a nota //remover o ajuste de estoque
         $retornar["dados"] = array("sucesso" => true, "title" => "Compra cancelada com sucesso");

         $mensagem = utf8_decode("Cancelou a compra $serie $numero_nf");
         registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         // Se tudo ocorreu bem, confirme a transação
         mysqli_commit($conecta);
      } else {
         // Se ocorrer um erro, reverta a transação
         mysqli_rollback($conecta);
         $retornar["dados"] = array("sucesso" => false, "title" => "Erro, comunique o suporte");
         $mensagem = utf8_decode("tentativa sem sucesso de cancelar a compra $serie $numero_nf");
         registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
      }
   } elseif ($acao == "cancelar_provisonamento") {

      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);

      $nf_id = $_POST['nf_id'];
      $codigo_nf = consulta_tabela($conecta, "tb_nf_entrada", "cl_id", $nf_id, "cl_codigo_nf");
      $serie = consulta_tabela($conecta, "tb_nf_entrada", "cl_id", $nf_id, "cl_serie_nf");
      $numero_nf = consulta_tabela($conecta, "tb_nf_entrada", "cl_id", $nf_id, "cl_numero_nf");

      if (update_registro($conecta, "tb_nf_entrada", "cl_id", $nf_id, "", "", "cl_status_provisionamento", "1") and remover_linha($conecta, "tb_lancamento_financeiro", "cl_codigo_nf", $codigo_nf)) { //remover o fianceiro da nota) {//remover a nota
         $retornar["dados"] = array("sucesso" => true, "title" => "Provisionamento cancelada com sucesso");

         $mensagem = utf8_decode("Cancelou o provisionamento da compra $serie $numero_nf");
         registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         // Se tudo ocorreu bem, confirme a transação
         mysqli_commit($conecta);
      } else {
         // Se ocorrer um erro, reverta a transação
         mysqli_rollback($conecta);
         $retornar["dados"] = array("sucesso" => false, "title" => "Erro, comunique o suporte");
         $mensagem = utf8_decode("tentativa sem sucesso de cancelar o provisionamento da compra $serie $numero_nf");
         registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
      }
   }

   // Encerre a conexão com o banco de dados
   mysqli_close($conecta);

   echo json_encode($retornar);
}
