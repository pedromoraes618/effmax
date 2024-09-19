<?php
if (isset($_GET['produto_nf_saida'])) { //modal de produto nf saida
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


if (isset($_GET['form_id'])) {
   $id_nf = $_GET['form_id'];
   $tipo = $_GET['tipo'];
   $codigo_nf = $_GET['codigo_nf'];
} else {
   $id_nf = "";
   $tipo = "";
   $codigo_nf = "";
}

if (isset($_GET['concluir_venda'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";

   $serie_default = strtoupper(consulta_tabela($conecta, 'tb_parametros', 'cl_id', 136, 'cl_valor')); //verificar qual serie sela setado com default para ser finalizado junto com a venda
}


//consultar informações para tabela devolucao
if (isset($_GET['consultar_documento_os'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   $consulta = $_GET['consultar_documento_os'];
   $data_inicial = $_GET['data_inicial'];
   $data_final = $_GET['data_final'];

   // //formatar data para o banco de dados
   // $data_inicial =  formatarDataParaBancoDeDados($data_inicial);
   // $data_final =  formatarDataParaBancoDeDados($data_final);

   if ($consulta == "inicial") {
      $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1
      $select = "SELECT fpg.cl_tipo_pagamento_id as pagamentoid, uf.cl_uf as ufestado, nf.cl_cpf_cnpj_avulso_nf,nf.cl_parceiro_id, nf.cl_numero_protocolo, nf.cl_pdf_nf,  nf.cl_numero_nf_devolucao, nf.cl_codigo_nf, nf.cl_status_venda, fpg.cl_tipo_pagamento_id as tipopg, nf.cl_id as id,nf.cl_data_movimento,nf.cl_numero_nf,nf.cl_serie_nf,nf.cl_status_recebimento,user.cl_usuario as vendedor,
      nf.cl_valor_desconto,nf.cl_valor_liquido,prc.cl_razao_social,prc.cl_nome_fantasia,fpg.cl_descricao as formapgt from tb_nf_saida as nf inner join tb_parceiros as prc on prc.cl_id = nf.cl_parceiro_id inner join
       tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id inner join tb_users as user on user.cl_id = nf.cl_vendedor_id
        left join tb_estados as uf on uf.cl_id = prc.cl_estado_id
         WHERE
        nf.cl_data_movimento between '$data_inicial' and '$data_final' 
        and (nf.cl_operacao ='SERVICO' ) order by nf.cl_id desc ";
      $consultar_documento_os = mysqli_query($conecta, $select);
      if (!$consultar_documento_os) {
         die("Falha no banco de dados: " . mysqli_error($conecta));
      } else {
         $qtd = mysqli_num_rows($consultar_documento_os); //quantidade de registros
      }
   } else {
      $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
      $status_recebimento = isset($_GET['status_recebimento']) ? $_GET['status_recebimento'] : '0';

      $select = "SELECT fpg.cl_tipo_pagamento_id as pagamentoid, uf.cl_uf as ufestado,nf.cl_cpf_cnpj_avulso_nf,nf.cl_parceiro_id,nf.cl_numero_protocolo,nf.cl_pdf_nf,  nf.cl_numero_nf_devolucao, nf.cl_codigo_nf, nf.cl_status_venda, fpg.cl_tipo_pagamento_id as tipopg, nf.cl_id as id,nf.cl_data_movimento,nf.cl_numero_nf,nf.cl_serie_nf,nf.cl_status_recebimento,user.cl_usuario as vendedor,
      nf.cl_valor_desconto,nf.cl_valor_liquido,prc.cl_razao_social,prc.cl_nome_fantasia,fpg.cl_descricao as formapgt from tb_nf_saida as nf inner join
       tb_parceiros as prc on prc.cl_id = nf.cl_parceiro_id inner join
       tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id 
       inner join tb_users as user on user.cl_id = nf.cl_vendedor_id 
       left join tb_estados as uf on uf.cl_id = prc.cl_estado_id
   
        WHERE nf.cl_data_movimento between '$data_inicial' and '$data_final' and 
      ( nf.cl_numero_nf  like '%$pesquisa%' or prc.cl_razao_social  like '%$pesquisa%'
      or prc.cl_nome_fantasia  like '%$pesquisa%' ) and (nf.cl_operacao ='SERVICO')   ";

      if ($status_recebimento != "0") {
         $select .= " and nf.cl_status_recebimento = '$status_recebimento' and nf.cl_status_venda ='1' ";
      }

      $select .= " order by nf.cl_id desc";
      $consultar_documento_os = mysqli_query($conecta, $select);
      if (!$consultar_documento_os) {
         die("Falha no banco de dados: " . mysqli_error($conecta));
      } else {
         $qtd = mysqli_num_rows($consultar_documento_os);
      }
   }
}



//consultar informações para tabela devolucao
if (isset($_GET['consultar_devolucao_venda'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   $consulta = $_GET['consultar_devolucao_venda'];
   $data_inicial = $_GET['data_inicial'];
   $data_final = $_GET['data_final'];

   // //formatar data para o banco de dados
   // $data_inicial =  formatarDataParaBancoDeDados($data_inicial);
   // $data_final =  formatarDataParaBancoDeDados($data_final);

   if ($consulta == "inicial") {
      $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1
      $select = "SELECT nf.cl_valor_adto, uf.cl_uf as ufestado, nf.cl_cpf_cnpj_avulso_nf,nf.cl_parceiro_id, nf.cl_numero_protocolo, nf.cl_pdf_nf,  nf.cl_numero_nf_devolucao, nf.cl_codigo_nf, nf.cl_status_venda, fpg.cl_tipo_pagamento_id as tipopg, nf.cl_id as id,nf.cl_data_movimento,nf.cl_numero_nf,nf.cl_serie_nf,nf.cl_status_recebimento,user.cl_usuario as vendedor,
      nf.cl_valor_desconto,nf.cl_valor_liquido,prc.cl_razao_social,prc.cl_nome_fantasia,fpg.cl_descricao as formapgt from tb_nf_saida as nf inner join tb_parceiros as prc on prc.cl_id = nf.cl_parceiro_id inner join
       tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id inner join tb_users as user on user.cl_id = nf.cl_vendedor_id
        left join tb_estados as uf on uf.cl_id = prc.cl_estado_id WHERE
        nf.cl_data_movimento between '$data_inicial' and '$data_final' and (nf.cl_operacao ='DEVVENDA' or nf.cl_operacao='ESTORNOVENDA' ) order by nf.cl_id desc";
      $consultar_venda_mercadoria = mysqli_query($conecta, $select);
      if (!$consultar_venda_mercadoria) {
         die("Falha no banco de dados");
      } else {
         $qtd = mysqli_num_rows($consultar_venda_mercadoria); //quantidade de registros
      }
   } else {
      $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
      $status_recebimento = $_GET['status_recebimento'];

      $select = "SELECT  nf.cl_valor_adto, uf.cl_uf as ufestado,nf.cl_cpf_cnpj_avulso_nf,nf.cl_parceiro_id,nf.cl_numero_protocolo,nf.cl_pdf_nf,  nf.cl_numero_nf_devolucao, nf.cl_codigo_nf, nf.cl_status_venda, fpg.cl_tipo_pagamento_id as tipopg, nf.cl_id as id,nf.cl_data_movimento,nf.cl_numero_nf,nf.cl_serie_nf,nf.cl_status_recebimento,user.cl_usuario as vendedor,
      nf.cl_valor_desconto,nf.cl_valor_liquido,prc.cl_razao_social,prc.cl_nome_fantasia,fpg.cl_descricao as formapgt from tb_nf_saida as nf inner join
       tb_parceiros as prc on prc.cl_id = nf.cl_parceiro_id inner join
       tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id inner join tb_users as user on user.cl_id = nf.cl_vendedor_id 
       left join tb_estados as uf on uf.cl_id = prc.cl_estado_id
        WHERE nf.cl_data_movimento between '$data_inicial' and '$data_final' and 
      ( nf.cl_numero_nf  like '%$pesquisa%' or prc.cl_razao_social  like '%$pesquisa%'
      or prc.cl_nome_fantasia  like '%$pesquisa%' ) and (nf.cl_operacao ='DEVVENDA' or nf.cl_operacao='ESTORNOVENDA')   ";

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
if (isset($_GET['consultar_venda'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   $consulta = $_GET['consultar_venda'];
   $data_inicial = $_GET['data_inicial'];
   $data_final = $_GET['data_final'];

   // //formatar data para o banco de dados
   // $data_inicial =  formatarDataParaBancoDeDados($data_inicial);
   // $data_final =  formatarDataParaBancoDeDados($data_final);

   if ($consulta == "inicial") {
      $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1
      $select = "SELECT nf.cl_valor_adto, fpg.cl_tipo_pagamento_id as pagamentoid, uf.cl_uf as ufestado, nf.cl_cpf_cnpj_avulso_nf,nf.cl_parceiro_id, nf.cl_numero_protocolo,nf.cl_pdf_nf,  nf.cl_numero_nf_devolucao, nf.cl_codigo_nf, nf.cl_status_venda, fpg.cl_tipo_pagamento_id as tipopg, nf.cl_id as id,nf.cl_data_movimento,nf.cl_numero_nf,nf.cl_serie_nf,nf.cl_status_recebimento,user.cl_usuario as vendedor,
      nf.cl_valor_desconto,nf.cl_valor_liquido,prc.cl_razao_social,prc.cl_nome_fantasia,fpg.cl_descricao as formapgt from tb_nf_saida as nf 
      left join tb_parceiros as prc on prc.cl_id = nf.cl_parceiro_id 
      left join tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id 
       left join tb_users as user on user.cl_id = nf.cl_vendedor_id 
       left join tb_estados as uf on uf.cl_id = prc.cl_estado_id WHERE 
        nf.cl_data_movimento between '$data_inicial' and '$data_final' and nf.cl_operacao ='VENDA'  order by nf.cl_id desc";
      $consultar_venda_mercadoria = mysqli_query($conecta, $select);
      if (!$consultar_venda_mercadoria) {
         die("Falha no banco de dados");
      } else {
         $qtd = mysqli_num_rows($consultar_venda_mercadoria); //quantidade de registros
      }
   } else {
      $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
      $status_recebimento = $_GET['status_recebimento'];
      $forma_pgt = $_GET['forma_pgt'];

      $select = "SELECT nf.cl_valor_adto, fpg.cl_tipo_pagamento_id as pagamentoid, uf.cl_uf as ufestado, nf.cl_cpf_cnpj_avulso_nf,nf.cl_parceiro_id, nf.cl_numero_protocolo,nf.cl_pdf_nf, nf.cl_numero_nf_devolucao, nf.cl_codigo_nf, nf.cl_status_venda, fpg.cl_tipo_pagamento_id as tipopg, nf.cl_id as id,nf.cl_data_movimento,nf.cl_numero_nf,nf.cl_serie_nf,nf.cl_status_recebimento,user.cl_usuario as vendedor,
      nf.cl_valor_desconto,nf.cl_valor_liquido,prc.cl_razao_social,prc.cl_nome_fantasia,fpg.cl_descricao as formapgt from tb_nf_saida as nf 
      left join tb_parceiros as prc on prc.cl_id = nf.cl_parceiro_id 
      left join tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id left join tb_users as user on user.cl_id = nf.cl_vendedor_id 
       left join tb_estados as uf on uf.cl_id = prc.cl_estado_id  WHERE nf.cl_data_movimento between '$data_inicial' and '$data_final' and 
      ( nf.cl_numero_nf  like '%$pesquisa%' or prc.cl_razao_social  like '%$pesquisa%' or prc.cl_nome_fantasia 
       like '%$pesquisa%' ) AND nf.cl_operacao ='VENDA'";

      if ($status_recebimento != "0") {
         $select .= " and nf.cl_status_recebimento = '$status_recebimento' and nf.cl_status_venda ='1' ";
      }
      if ($forma_pgt != "0") {
         $select .= " and nf.cl_forma_pagamento_id = '$forma_pgt' ";
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



if (isset($_GET['tabela_produto'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   $codigo_nf = $_GET['codigo_nf'];
   $obs = isset($_GET['obs']) ? $_GET['obs'] : "";

   $select  = "SELECT * from tb_nf_saida_item where cl_codigo_nf = '$codigo_nf'";
   $consultar_produtos = mysqli_query($conecta, $select);
   $qtd_consultar_produtos = mysqli_num_rows($consultar_produtos);
}

if (isset($_POST['venda_mercadoria'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";
   $acao = $_POST['acao'];
   $validar_venda_sem_estoque = verficar_paramentro($conecta, "tb_parametros", "cl_id", "9"); //verificar no paramentro se pode adicionar o produto sem estoque
   $desconto_maximo_produto = verficar_paramentro($conecta, "tb_parametros", "cl_id", "10"); //verificar o desconto maimo para o produto na venda
   $serie_venda = verifcar_descricao_serie($conecta, "12"); //verificar qual seria usado na venda
   $nf_atual = consultar_valor_serie($conecta, "12"); //verificar a numeração da venda atual
   $cliente_avulso_id = verficar_paramentro($conecta, "tb_parametros", "cl_id", "8"); //verificar o id do cliente avulso
   //$classficacao_financeiro_id = verficar_paramentro($conecta, "tb_parametros", "cl_id", "11"); //verificar o id do cliente avulso
   $abrir_recibo = verficar_paramentro($conecta, "tb_parametros", "cl_id", "17"); //verificar o id do cliente avulso
   $venda_prd_vlr_zerado = verficar_paramentro($conecta, "tb_parametros", "cl_id", "27"); //verificar o id do cliente avulso
   $nf_novo = $nf_atual + 1;

   if ($acao == "validar_produto") { //validar dados do produto
      $usuario_id = verifica_sessao_usuario();
      $usuario = (consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario"));


      $preco_venda = 0;
      $desconto_real = 0;
      $calula_desconto = 0;

      // $registro = $_POST['resgistro'];
      $codigo_nf = $_POST['cd_nf'];
      $id_user_logado = $_POST['id_user'];
      $nome_usuario_logado = $_POST['user_nome'];
      $check_autorizador = $_POST['check_autorizador'];

      $itensJSON = $_POST['itens']; //array de produtos
      $itens = json_decode($itensJSON, true); //recuperar valor do array javascript decodificando o json

      $id_produto = $itens['id_produto'];
      $descricao_produto = (consulta_tabela($conecta, "tb_produtos", "cl_id", $id_produto, "cl_descricao"));


      $preco_venda = $itens['preco_venda'];
      $quantidade = $itens['quantidade'];

      if ($preco_venda != "") {
         if (verificaVirgula($preco_venda)) { //verificar se tem virgula
            $preco_venda = formatDecimal($preco_venda); // formatar virgula para ponto
         }
      }
      if ($quantidade != "") {
         if (verificaVirgula($quantidade)) { //verificar se tem virgula
            $quantidade = formatDecimal($quantidade); // formatar virgula para ponto
         }
      }


      if ($id_produto == "") { //validar se algum produto já foi selecionado
         $retornar["dados"] =  array("sucesso" => false, "title" => "Favor, é necessario selecionar o produto");
      } else {
         $unidade_prod_id = consulta_tabela($conecta, "tb_produtos", "cl_id", $id_produto, "cl_und_id");
         $descricao_unidade_prod = consulta_tabela($conecta, "tb_unidade_medida", "cl_id", $unidade_prod_id, "cl_sigla");
         //$valor_total = $itens['valor_total'];
         $estoque =  validar_prod_venda($conecta, $id_produto, "cl_estoque"); //estoque disponivel do produto
         $preco_venda_atual =  validar_prod_venda($conecta, $id_produto, "cl_preco_venda"); //preco de venda do produto no cadastro
         $referencia = utf8_decode(validar_prod_venda($conecta, $id_produto, "cl_referencia")); //preco de venda do produto no cadastro
         $preco_venda_promocao =  validar_prod_venda($conecta, $id_produto, "cl_preco_promocao"); //preco de venda do produto no cadastro

         $valor_total = $preco_venda * $quantidade;


         if ($preco_venda != 0 and $preco_venda_atual != 0) {
            $calula_desconto = (($preco_venda * 100) / $preco_venda_atual);
            $calula_desconto = (100 - $calula_desconto); //desconto em porcentagem

            $desconto_real = $preco_venda_atual - $preco_venda; //desconto em real
         }

         if ($preco_venda == $preco_venda_promocao) {
            $preco_venda = $preco_venda_promocao;
            $desconto_real = 0;
            $calula_desconto = 0;
         }

         if ($descricao_produto == "") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('descrição'));
         } elseif ($venda_prd_vlr_zerado != "S" and ($preco_venda == 0)) {
            $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel adicionar o produto, o Valor Total do produto não pode ser 0");
         } elseif ($validar_venda_sem_estoque == "N"  and validar_qtd_prod_venda($conecta, $id_produto, $codigo_nf, $quantidade) > $estoque) {
            $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel adicionar o produto, o estoque do produto está zerado");
         } elseif (($desconto_maximo_produto < $calula_desconto and ($desconto_maximo_produto != "") and ($check_autorizador != "true"))) {
            $retornar["dados"] =  array("sucesso" => "autorizar", "title" => "Não é possivel adicionar o produto, o desconto está acima do permitido, continue com a operação autorizando com a senha");
         } else {
            $nf = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_numero_nf");
            if ($nf != "") { //Adicionando um prduto a uma venda já finalizada
               $status_recebimento = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_status_recebimento");
               $status_venda = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_status_venda");
               $parceiro_id = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_parceiro_id");
               $forma_pagamento_id = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_forma_pagamento_id");

               if ($status_venda == "3") { //venda cancelada
                  $retornar["dados"] = array("sucesso" => false, "title" => "Não é possível adicionar o produto, pois a venda foi cancelada");
               } elseif ($status_recebimento == "2") { //a venda está recebida
                  $retornar["dados"] = array("sucesso" => false, "title" => "Não é possível adicionar o produto, pois a venda já foi recebida, favor, remova-o do faturamento antes de prosseguir com a ação");
               } else {
                  $insert = "INSERT INTO `tb_nf_saida_item` ( `cl_data_movimento`, `cl_codigo_nf`,`cl_numero_nf`, `cl_usuario_id`, `cl_serie_nf`, 
               `cl_item_id`, `cl_descricao_item`, `cl_quantidade`, `cl_unidade`, `cl_valor_unitario`, `cl_valor_total`,
              `cl_desconto`, `cl_referencia`,`cl_status`,`cl_tipo_item_delivery` ) VALUES ( '$data_lancamento', '$codigo_nf','$nf', '$id_user_logado', '$serie_venda',
                   '$id_produto', '$descricao_produto', '$quantidade', '$descricao_unidade_prod', '$preco_venda', '$valor_total', '$desconto_real',
              '$referencia','1','PRODUTO' ) ";
                  $operacao_insert = mysqli_query($conecta, $insert);
                  if ($operacao_insert) {
                     $novo_id_inserido = mysqli_insert_id($conecta);

                     // $novo_id_inserido = mysqli_insert_id($conecta);

                     //    if (update_registro($conecta, "tb_nf_saida_item", "cl_codigo_nf", $codigo_nf, "cl_id", "$nf_saida_item", "cl_status", "3")) { //alterar status do produto para cancelado
                     //       if (update_registro($conecta, "tb_ajuste_estoque", "cl_codigo_nf", $codigo_nf, "cl_produto_id", "$produto_id", "cl_status", "cancelado")) { //cancelar ajuste de estoque
                     //           update_registro($conecta, "tb_produtos", "cl_id", $produto_id, "", "", "cl_estoque", "$quantidade_atual");     //cancelar ajuste de estoque
                     //       }
                     //   }

                     if (ajuste_estoque(
                        $conecta,
                        $data,
                        "$serie_venda-$nf",
                        "SAIDA",
                        $id_produto,
                        $quantidade,
                        "1",
                        $parceiro_id,
                        $usuario_id,
                        $forma_pagamento_id,
                        $preco_venda,
                        "0",
                        '0',
                        '',
                        "$codigo_nf",
                        $novo_id_inserido,
                        ""
                     )) {
                        $novo_estoque = $estoque - $quantidade;
                        update_registro($conecta, "tb_produtos", "cl_id", $id_produto, "", "", "cl_estoque", "$novo_estoque");     //cancelar ajuste de estoque
                     };

                     recalcular_valor_nf_saida($conecta, $codigo_nf);

                     $retornar["dados"] =  array("sucesso" => true);

                     $mensagem = utf8_decode("Adicionou o produto de código $id_produto na $serie_venda $nf já finalizada");
                     registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                  } else {
                     $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, não foi possivel adicionar o produto, favor verifique com o suporte");

                     $mensagem = utf8_decode("Tentativa do usuário $nome_usuario_logado adicionar um produto em uma venda com já finalizada, sem sucesso");
                     registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                  }
               }
            } else { //adicionar um produto em uma nova venda
               $insert = "INSERT INTO `tb_nf_saida_item` ( `cl_data_movimento`, `cl_codigo_nf`, `cl_usuario_id`, `cl_serie_nf`, 
               `cl_item_id`, `cl_descricao_item`, `cl_quantidade`, `cl_unidade`, `cl_valor_unitario`, `cl_valor_total`,
              `cl_desconto`, `cl_referencia`,`cl_status`,`cl_tipo_item_delivery`) VALUES ( '$data_lancamento', '$codigo_nf', '$id_user_logado', '$serie_venda',
                   '$id_produto', '$descricao_produto', '$quantidade', '$descricao_unidade_prod', '$preco_venda', '$valor_total', '$desconto_real',
              '$referencia','2','PRODUTO' ) ";
               $operacao_insert = mysqli_query($conecta, $insert);
               if ($operacao_insert) {
                  $retornar["dados"] =  array("sucesso" => true);
               } else {
                  $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, não foi possivel adicionar o produto, favor verifique com o suporte");
                  $mensagem = utf8_decode("Tentativa do usuário $nome_usuario_logado adicionar um produto em uma venda com sem sucesso");
                  registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
               }
            }
         }
      }
   }
   if ($acao == "validar_alteracao_produto") { //validar dados do produto
      // $registro = $_POST['resgistro'];
      // Inicia uma transação
      mysqli_begin_transaction($conecta);
      $erro = false;

      $usuario_id = verifica_sessao_usuario();
      $nome_usuario_logado = (consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario"));
      $check_autorizador = $_POST['check_autorizador'];
      $itensJSON = $_POST['itens']; //array de produtos
      $itens = json_decode($itensJSON, true); //recuperar valor do array javascript decodificando o json

      $id_item_nf = $itens['id_item_nf']; //id do produto na tabela nfe_saida_item
      $preco_venda = $itens['preco_venda'];
      $quantidade = $itens['quantidade'];

      $id_produto = (consulta_tabela($conecta, "tb_nf_saida_item", "cl_id", $id_item_nf, "cl_item_id"));
      $quantidade_nf_item = (consulta_tabela($conecta, "tb_nf_saida_item", "cl_id", $id_item_nf, "cl_quantidade")); //quantidade registrada na coluna tb_nf_saida_item
      $descricao_produto = (consulta_tabela($conecta, "tb_produtos", "cl_id", $id_produto, "cl_descricao"));
      $codigo_nf = consulta_tabela($conecta, "tb_nf_saida_item", "cl_id", $id_item_nf, "cl_codigo_nf"); //codigo da nfe
      $nf = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_id"); //verificar se já tem uma venda finalizada com esse codio
      $status_recebimento = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_status_recebimento");
      $status_venda = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_status_venda");



      if ($quantidade != "") {
         if (verificaVirgula($quantidade)) { //verificar se tem virgula
            $quantidade = formatDecimal($quantidade); // formatar virgula para ponto
         }
      }
      if ($preco_venda != "") {
         if (verificaVirgula($preco_venda)) { //verificar se tem virgula
            $preco_venda = formatDecimal($preco_venda); // formatar virgula para ponto
         }
      }



      $preco_venda_promocao =  validar_prod_venda($conecta, $id_produto, "cl_preco_promocao"); //preco de venda do produto no cadastro
      //       $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel adicionar o produto, a demanda no estoque não atende");


      if ($id_item_nf == "") { //validar se algum produto já foi selecionado
         $retornar["dados"] =  array("sucesso" => false, "title" => "Favor Selecione um produto");
      } else {
         //$valor_total = $itens['valor_total'];
         $estoque =  validar_prod_venda($conecta, $id_produto, "cl_estoque"); //estoque disponivel do produto
         $preco_venda_atual =  validar_prod_venda($conecta, $id_produto, "cl_preco_venda"); //preco de venda do produto no cadastro
         $referencia =  validar_prod_venda($conecta, $id_produto, "cl_referencia"); //preco de venda do produto no cadastro
         $valor_total = $preco_venda * $quantidade;

         if ($preco_venda != 0 and $preco_venda_atual != 0) {
            $calula_desconto = (($preco_venda * 100) / $preco_venda_atual);
            $calula_desconto = (100 - $calula_desconto); //desconto em porcentagem

            $desconto_real = $preco_venda_atual - $preco_venda; //desconto em real
         } else {
            $desconto_real = 0;
            $calula_desconto = 0;
         }

         if ($preco_venda == $preco_venda_promocao) { //se o preço de venda for o mesmo que está no preço promocional
            $preco_venda = $preco_venda_promocao;
            $desconto_real = 0;
            $calula_desconto = 0;
         }


         if ($status_venda == "3") { //venda cancelada
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possível alterar o produto, pois a venda foi cancelada");
         } elseif ($status_recebimento == "2") { //a venda está recebida
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possível alterar o produto, pois a venda já foi recebida, favor, remova-o do faturamento antes de prosseguir com a ação");
         } elseif ($descricao_produto == "") {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('descrição'));
         } elseif ($quantidade == 0) { //quantidade não pode ser zero
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('quantidade'));
         } elseif ($venda_prd_vlr_zerado != "S" and ($valor_total == 0)) {
            $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel adicionar o produto, o valor total do Produto não pode ser 0");
         } elseif (($desconto_maximo_produto < $calula_desconto and ($desconto_maximo_produto != "") and ($check_autorizador != "true"))) {
            $retornar["dados"] =  array("sucesso" => "autorizar", "title" => "Não é possivel alterar o produto, o desconto está acima do permitido, continue com a operação autorizando com a senha");
         } elseif (((validar_qtd_prod_venda($conecta, $id_produto, $codigo_nf, $quantidade) - $quantidade_nf_item) >  $estoque and
            validar_qtd_prod_venda($conecta, $id_produto, $codigo_nf, $quantidade) != $quantidade_nf_item)  and $validar_venda_sem_estoque == "N") { //validar se a quantidade adicionado mais o mesmo produto que esta na venda atende o estoque
            $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel alterar o produto, a demanda no estoque não atende");
         } else { //alterar o produto com a venda já finalizada

            if ($nf != "") { //Adicionando um prduto a uma venda já finalizada
               $update = "UPDATE `tb_nf_saida_item` SET `cl_descricao_item` = '$descricao_produto', `cl_quantidade` = '$quantidade',
               `cl_valor_unitario` = '$preco_venda', `cl_valor_total` = '$valor_total', `cl_desconto` = '$desconto_real',`cl_id_delivery` = '$id_item_nf' WHERE `tb_nf_saida_item`.`cl_id` = $id_item_nf  ";
               $operacao_update = mysqli_query($conecta, $update);
               if ($operacao_update) {

                  $mensagem = 'Produto alterado com sucesso';
                  recalcular_valor_nf_saida($conecta, $codigo_nf);
               } else {
                  $mensagem = 'Erro, tentativa de alterar o produto sem sucesso';
                  $erro = true;
                  // $mensagem = utf8_decode("Tentativa do usuário $nome_usuario_logado alterar o produto de id $id_produto da venda sem sucesso");
                  // registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                  // $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, não foi possivel alterar o produto, favor verifique com o suporte");
               }
            } else { //alterar o produto com a venda em andamento



               $update = "UPDATE `tb_nf_saida_item` SET `cl_descricao_item` = '$descricao_produto', `cl_quantidade` = '$quantidade',
             `cl_valor_unitario` = '$preco_venda', `cl_valor_total` = '$valor_total', `cl_desconto` = '$desconto_real' WHERE `tb_nf_saida_item`.`cl_id` = $id_item_nf  ";
               $operacao_update = mysqli_query($conecta, $update);
               if ($operacao_update) {
                  $mensagem = 'Produto alterado com sucesso';
               } else {
                  $mensagem = 'Erro, tentativa de alterar o produto sem sucesso';
                  $erro = true;
               }
            }

            if (!$erro) {
               mysqli_commit($conecta); // Confirma a transação se tudo ocorreu bem
               $retornar["dados"] = array("sucesso" => true, "title" => "$mensagem");
               //registrar no log
               // $mensagem =  utf8_decode("Erro, ");
            } else {
               mysqli_rollback($conecta); // Desfaz a transação em caso de erro
               $retornar["dados"] = array("sucesso" => false, "title" => "$mensagem");

               $mensagem = utf8_decode("Tentativa do usuário $nome_usuario_logado alterar o produto de id $id_produto da venda sem sucesso");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
         }
      }
   }




   if ($acao == "create") { //criar a venda
      //$momento_venda = $_POST['momento_venda'];
      $ordem_item = 0;
      $produtosJSON = $_POST['produtos'];
      $produtos = json_decode($produtosJSON, true); //recuperar valor do array javascript decodificando o json

      $nome_usuario_logado = $_POST["nome_usuario_logado"];
      $id_usuario_logado = $_POST["id_usuario_logado"];
      $perfil_usuario_logado = $_POST['perfil_usuario_logado'];
      $id_venda = $_POST['id'];
      $codigo_nf = $_POST['codigo_nf'];
      $vendedor_id_venda = $_POST['vendedor_id_venda'];
      $parceiro_id = $_POST['parceiro_id'];
      $parceiro_descricao = $_POST['parceiro_descricao'];
      $desconto_venda_real = $_POST['desconto_venda_real'];
      $forma_pagamento_id_venda = $_POST['forma_pagamento_id_venda'];
      $observacao = utf8_decode($_POST['observacao']);
      $autorizador_id = $_POST['autorizador_id'];
      $senha_autorizador = $_POST['senha_autorizador'];
      $valor_credito = $_POST['valor_credito'];
      $observacao = str_replace("'", "", $observacao);
      $conta_financeira = consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $forma_pagamento_id_venda, "cl_conta_financeira");

      // $valor_total_bruto = valores_prod_nf($conecta, $codigo_nf); //valores total dos produtos //valores total dos produtos

      $valor_total_bruto = resumo_valor_nf_saida($conecta, $codigo_nf)['total_valor_bruto'];

      // $valor_total_bruto = consulta_tabela_query($conecta, "SELECT sum(cl_valor_total) as total from tb_nf_saida_item where cl_codigo_nf ='$codigo_nf' ", 'total'); //valores total dos produtos
      $data_lancamento = verficar_paramentro($conecta, "tb_parametros", "cl_id", "15") == "S" ? $_POST['data_movimento'] : $data_lancamento; //assumir a data que está no campo data movimento na venda



      if (verificaVirgula($desconto_venda_real)) { //verificar se tem virgula
         $desconto_venda_real = formatDecimal($desconto_venda_real); // formatar virgula para ponto
      }

      $desconto_percente = ($desconto_venda_real / $valor_total_bruto) * 100; //desconto convertido em porcentagem


      if ($parceiro_id == "") { //se a venda não possuir cliente será colocado o cliente padrão que está setado no parametro
         $parceiro_id = $cliente_avulso_id;
         $parceiro_avulso = $parceiro_descricao;
         $valor_credito_atual = 0;
      } else {
         $parceiro_avulso = "";
         $valor_credito_atual = (consulta_tabela($conecta, "tb_parceiros", "cl_id", $parceiro_id, "cl_valor_credito")); //valor de credito disponivel
      }

      $desconto_venda_finalizada = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_valor_desconto"); //verificar se essa venda já foi finalizada
      $desconto_maximo_forma_pgt = verifica_desconto_fpg($conecta, $forma_pagamento_id_venda);

      if ($codigo_nf == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Não é possível concluir a venda, pois a venda não foi iniciada");
      } elseif ($valor_total_bruto == 0) {
         $retornar["dados"] = array("sucesso" => false, "title" => "Não é possível concluir a venda, o valor total está zerado");
      } elseif ($vendedor_id_venda == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("vendedor"));
      } elseif ($forma_pagamento_id_venda == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("forma de pagamento"));
      } elseif ($desconto_venda_real > $valor_total_bruto) {
         $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel finalizar a venda, o desconto não pode ser maior que o valor bruto da venda");
      } elseif ($desconto_venda_real < 0) {
         $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel finalizar essa venda, o desconto não pode ser negativo");
      } elseif (($desconto_percente > $desconto_maximo_forma_pgt) and ($desconto_venda_real != $desconto_venda_finalizada) and ($autorizador_id == "0" or $senha_autorizador == "")) {
         $retornar["dados"] =  array("sucesso" => "autorizar", "title" => "Não é possivel finalizar a venda, o desconto está acima do permitido, continue com a operação autorizando com a senha");
      } elseif (($desconto_percente > $desconto_maximo_forma_pgt) and ($desconto_venda_real != $desconto_venda_finalizada) and (validar_usuario($conecta, $autorizador_id, $senha_autorizador) == false)) {
         $retornar["dados"] =  array("sucesso" => "autorizar", "title" => "A venda não pode ser finalizada: senha incorreta, autorização negada");
      } elseif (verifica_repeticao_doc($conecta, "tb_nf_saida", "cl_serie_nf", "cl_numero_nf", $serie_venda, $nf_novo)) { //verificar se já existe essa venda se sim, não realizar a venda
         $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel finalizar essa venda, o número de venda $nf_novo já está registrado no sistema, favor verifique");
      } else {

         $valor_liquido_venda = $valor_total_bruto - $desconto_venda_real; //valor liquido da venda
         $valor_a_receber = $valor_liquido_venda - $valor_credito; //valor a receber
         $nf = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_numero_nf"); //verificar se essa venda já foi finalizada

         if ($nf != "") { //editar venda
            $status_recebimento = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_status_recebimento"); //verificar se essa venda já foi finalizada
            $status_venda = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_status_venda"); //verificar se essa venda já foi finalizada
            $numero_nf_atual = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_numero_nf"); //numero atual da nf
            $serie_nf_atual = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_serie_nf"); //serie atual na nf
            $valor_credito_registrado_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_valor_credito"); //valopr de credito já utilizado na venda
            $credito_atual = consulta_tabela($conecta, "tb_parceiros", "cl_id", $parceiro_id, "cl_valor_credito"); //valor de credito disponivel para o cliente

            // if (true) {
            //    $retornar["dados"] = array("sucesso" => false, "title" => "Não $forma_pagamento_id_venda $desconto_maximo_forma_pgt é possivel $desconto_venda_real finalizar $desconto_venda_finalizada essa venda, o número de venda $nf_novo já está registrado no sistema, favor verifique");
            //    echo json_encode($retornar);
            //    exit;
            // }

            if ($status_venda == "3") { //venda cancelada
               $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel alterar a venda, a $serie_nf_atual$numero_nf_atual está cancelada");
            } elseif ($status_recebimento == "2") { //venda recebida
               $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel alterar a venda, é necessario remover a $serie_nf_atual$numero_nf_atual do faturamento");
            } elseif ($valor_credito_registrado_nf > 0) { //venda com valor de credito
               $retornar["dados"] = array("sucesso" => false, "title" => "Não é possível modificar a venda. O valor de crédito informado na transação precisa permanecer inalterado. Recomendamos o
                cancelamento da venda atual para realizar uma nova transação com os ajustes necessários");
            } else {
               if ($status_venda == "2") { //venda em andamento
                  $update_nf = update_registro($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "", "", "cl_status_venda", '1'); //atualizar estatus da venda
                  $update_finalizar_itens_nf = finalizar_produtos_nf($conecta, $codigo_nf, $serie_nf_atual, $numero_nf_atual, $desconto_venda_real, $data_lancamento, $parceiro_id, $id_usuario_logado, $forma_pagamento_id_venda); //atualizando os produtos da venda com valores corretos
                  if ($update_nf and $update_finalizar_itens_nf) {
                     $retornar["dados"] = array("sucesso" => true, "title" => "$serie_nf_atual $numero_nf_atual finalizada com sucesso ", "recibo" => $abrir_recibo, "acao" => "finalizar_venda");
                     $mensagem = utf8_decode("Usuário $nome_usuario_logado alterou a $serie_nf_atual $numero_nf_atual");
                     registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                  } else {
                     $retornar["dados"] = array("sucesso" => false, "title" => "Erro ao finalizar a $serie_nf_atual $numero_nf_atual, status em andamento, favor, entre em contato com o suporte ");
                     $mensagem = utf8_decode("Tentativa sem sucesso de finalizar a venda em andamento $serie_nf_atual $numero_nf_atual");
                     registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                  }
               } else { //venda já finalizada
                  $update = "UPDATE `tb_nf_saida` SET `cl_parceiro_id` = '$parceiro_id', `cl_parceiro_avulso` = '$parceiro_avulso', `cl_forma_pagamento_id` = '$forma_pagamento_id_venda',
                  `cl_valor_liquido` = '$valor_liquido_venda', `cl_valor_desconto` = '$desconto_venda_real', `cl_observacao` = '$observacao',
                   `cl_vendedor_id` = '$vendedor_id_venda',`cl_valor_credito` = '$valor_credito' WHERE `tb_nf_saida`.`cl_codigo_nf` ='$codigo_nf' ";
                  $operacao_update = mysqli_query($conecta, $update); //inserindo os dados basicos da venda
                  if ($operacao_update) {

                     $retornar["dados"] = array("sucesso" => true, "title" => "Venda alterada com sucesso ", "recibo" => "N", "acao" => "alterar_venda");
                     $mensagem = utf8_decode("Usuário $nome_usuario_logado alterou a $serie_venda $nf_novo");
                     registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                  } else {

                     $retornar["dados"] = array("sucesso" => false, "title" => "Erro ao alterar a $serie_venda $nf_novo, favor comunique o suporte do sistema");
                     $mensagem = utf8_decode("Tentativa sem sucesso de alterar a venda $serie_venda $nf_novo");
                     registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                  }
               }
            }
         } else { //criar venda

            $caixa =  verifica_caixa_financeiro($conecta, $data_lancamento, $conta_financeira);
            if (($caixa['resultado']) == "") { //verificar se o caixa já foi aberto
               $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("VAZIO")); //alertar o usuario que o caixa ainda não foi aberto
            } elseif ($caixa['status'] == "fechado") {
               $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("FECHADO")); //alertar o usuario que o caixa está fechado
            } elseif ($valor_credito > $valor_credito_atual) {
               $retornar["dados"] = array("sucesso" => false, "title" => "Valor de Crédito insuficiente, favor, verifique");
            } else {
               $descricao = utf8_decode("Recebimento referente a $serie_venda $nf_novo");
               $status_fpg = consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $forma_pagamento_id_venda, "cl_status_id"); //conta financeira que está no cadastro da forma de pagamento
               $classficacao_financeiro_id = consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $forma_pagamento_id_venda, "cl_classificao_id"); //classifiacação financecira da  forma de pagamento

               if ($status_fpg == "2") { //venda deve ser recebida automaticamente
                  if (recebimento_nf(
                     $conecta,
                     $data_lancamento,
                     $data_lancamento,
                     $data_lancamento,
                     '0',
                     $forma_pagamento_id_venda,
                     $parceiro_id,
                     "RECEITA",
                     "2",
                     $valor_a_receber,
                     $valor_a_receber,
                     0,
                     0,
                     0,
                     0,
                     $nf_novo,
                     $classficacao_financeiro_id,
                     $descricao,
                     "",
                     $nf_novo,
                     $serie_venda,
                     $codigo_nf,
                     "",
                     ""
                  )) {
                     $status_recebimento = "2";
                     $data_recebimento = $data_lancamento;
                     $usuario_id_recebimento = $id_usuario_logado;
                  }
               } else {
                  $status_recebimento = "1";
                  $data_recebimento = "";
                  $usuario_id_recebimento = "";
               }

               $gerar_documento = isset($_POST['gerar_documento']) ? $_POST['gerar_documento'] : '';

               $insert = "INSERT INTO `tb_nf_saida` ( `cl_data_movimento`, `cl_codigo_nf`,  `cl_parceiro_id`, `cl_parceiro_avulso`, 
         `cl_forma_pagamento_id`, `cl_numero_nf`, `cl_numero_venda`, `cl_serie_nf`, `cl_status_recebimento`, `cl_valor_bruto`, 
         `cl_valor_liquido`, `cl_valor_desconto`,`cl_usuario_id`,`cl_observacao`,`cl_data_recebimento`,`cl_usuario_id_recebimento`,`cl_operacao`,`cl_vendedor_id`,
         `cl_status_venda`,`cl_valor_credito`, `cl_data_pedido_delivery` ) VALUES
            ( '$data_lancamento','$codigo_nf', '$parceiro_id', '$parceiro_avulso', '$forma_pagamento_id_venda', '$nf_novo', '$nf_novo', '$serie_venda', '$status_recebimento',
            '$valor_total_bruto', '$valor_liquido_venda', '$desconto_venda_real','$id_usuario_logado','$observacao','$data_recebimento',
            '$usuario_id_recebimento','VENDA', '$vendedor_id_venda','1','$valor_credito','$data' )"; //STATUS 1 PARA VENDA FINALIZADA
               $operacao_insert = mysqli_query($conecta, $insert); //inserindo os dados basicos da venda
               if ($operacao_insert) {
                  $nf_id = mysqli_insert_id($conecta);
                  finalizar_produtos_nf($conecta, $codigo_nf, $serie_venda, $nf_novo, $desconto_venda_real, $data_lancamento, $parceiro_id, $id_usuario_logado, $forma_pagamento_id_venda); //atualizando os produtos da venda com valores corretos


                  if ($gerar_documento == "recibo" and $abrir_recibo == "S") {
                     $abrir_recibo = 'S';
                     $abrir_nfc = 'N';
                     $abrir_nfe = 'N';
                  } elseif ($gerar_documento == "nfc") {
                     $abrir_recibo = 'N';
                     $abrir_nfc = 'S';
                     $abrir_nfe = 'N';
                  } elseif ($gerar_documento == "nfe") {
                     $abrir_recibo = 'N';
                     $abrir_nfc = 'N';
                     $abrir_nfe = 'S';
                  } else {
                     $abrir_recibo = 'N';
                     $abrir_nfc = 'N';
                     $abrir_nfe = 'N';
                  }

                  $retornar["dados"] = array(
                     "sucesso" => true,
                     "title" => "$serie_venda$nf_novo finalizada com sucesso ",
                     "recibo" => $abrir_recibo,
                     "nfc" => $abrir_nfc, //opção para enviar nf 
                     "nfe" => $abrir_nfe, //opção para enviar nf 
                     "acao" => "finalizar_venda",
                     "nf_id" => $nf_id
                  );

                  //atualizar valor em serie de venda
                  adicionar_valor_serie($conecta, "12", $nf_novo);

                  $mensagem = utf8_decode("$nome_usuario_logado finalizou a $serie_venda$nf_novo com sucesso");
                  registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                  // if ($valor_credito > 0 and $parceiro_id != "") { //atualizar o valor do credito no cadastro do cliente
                  //    $credito_atual = consulta_tabela($conecta, "tb_parceiros", "cl_id", $parceiro_id, "cl_valor_credito");

                  //    // $justificativa = utf8_decode("Crédito no valor de $valor_credito utilizado na $serie_venda $nf_novo");
                  //    // adicionar_credito_parceiro($data, $parceiro_id, -$valor_credito, $justificativa); //incluir no historico de credit

                  //    // $valor_credito = $credito_atual - $valor_credito;
                  //    // update_registro($conecta, "tb_parceiros", "cl_id", $parceiro_id, "", "", "cl_valor_credito", $valor_credito); //adicionar o novo valor de credito na tabela de parceiro apos a venda
                  // }

               } else {
                  $retornar["dados"] = array("sucesso" => false, "title" => "Erro ao finalizar a venda $serie_venda$nf_novo, favor comunique o suporte");
                  $mensagem = utf8_decode("Tentativa sem sucesso de finalizar a $serie_venda$nf_novo");
                  registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
               }
            }
         }
      }
   }


   if ($acao == "show") { //dados da nf
      $nf_id = $_POST['nf_id'];
      $codigo_nf = $_POST['codigo_nf'];


      $select = "SELECT * from tb_nf_saida where cl_id = $nf_id and cl_codigo_nf ='$codigo_nf'";
      $consultar_nf = mysqli_query($conecta, $select);
      $linha = mysqli_fetch_assoc($consultar_nf);
      $parceiro_id = ($linha['cl_parceiro_id']);
      $data_movimento = ($linha['cl_data_movimento']);
      $data_pedido = formatarTimeStamp($linha['cl_data_pedido_delivery']);
      $observacao = utf8_encode($linha['cl_observacao']);
      $id_forma_pagamento_venda = ($linha['cl_forma_pagamento_id']);
      $vendedor_id_venda = ($linha['cl_vendedor_id']);
      $desconto_venda_real = ($linha['cl_valor_desconto']);
      $valor_liquido_venda = ($linha['cl_valor_liquido']);
      $sub_total_venda = ($linha['cl_valor_bruto']);
      $numero_nf = ($linha['cl_numero_nf']);
      $serie_nf = ($linha['cl_serie_nf']);
      $status_venda = ($linha['cl_status_venda']);
      $valor_credito_nf = ($linha['cl_valor_credito']);
      $status_recebimento = ($linha['cl_status_recebimento']);
      $data_emisao = formatDateB($linha['cl_data_emisao_nf']);
      $cfop = ($linha['cl_cfop']);
      $finalidade = ($linha['cl_finalidade']);
      $numero_pedido = ($linha['cl_numero_pedido']);
      $tipo_documento_nf = ($linha['cl_tipo_documento_nf']);

      $frete_id = ($linha['cl_tipo_frete_id']);
      $valor_frete = ($linha['cl_valor_frete']);
      $outras_despesas = ($linha['cl_valor_outras_despesas']);
      $seguro = ($linha['cl_valor_seguro']);

      $chave_acesso = ($linha['cl_chave_acesso']);
      $numero_protocolo = ($linha['cl_numero_protocolo']);
      $transportadora_id = ($linha['cl_transportadora_id']);
      $veiculo = ($linha['cl_veiculo']);
      $placa_trans = ($linha['cl_placa_trans']);
      $uf_veiculo_trans = ($linha['cl_uf_veiculo_trans']);
      $quantidade_trans = ($linha['cl_quantidade_trans']);
      $especie_trans = ($linha['cl_especie_trans']);
      $peso_bruto = ($linha['cl_peso_bruto']);
      $peso_liquido = ($linha['cl_peso_liquido']);
      $chave_acesso_referencia = ($linha['cl_chave_acesso_referencia']);
      $numero_nf_ref = ($linha['cl_numero_nf_ref']);
      $visualizar_duplicata = ($linha['cl_visualizar_duplicata']);
      $carta_correcao_nf = ($linha['cl_carta_correcao_nf']);
      $cpf_cnpj_avulso_nf = ($linha['cl_cpf_cnpj_avulso_nf']);
      $retem_iss = ($linha['cl_retem_iss']);

      $pdf_nf = urldecode($linha['cl_pdf_nf']);
      $caminho_xml_nf = ($linha['cl_caminho_xml_nf']);

      $parceiro_descricao = utf8_encode(consulta_tabela($conecta, "tb_parceiros", "cl_id", $parceiro_id, "cl_razao_social"));
      $descricao_forma_pagamento_venda = utf8_encode(consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $id_forma_pagamento_venda, "cl_descricao"));

      /* Serviço */
      $valor_pis_servico = ($linha['cl_valor_pis_servico']);
      $valor_cofins_servico = ($linha['cl_valor_cofins_servico']);
      $valor_deducoes = ($linha['cl_valor_deducoes']);
      $valor_inss = ($linha['cl_valor_inss']);
      $valor_ir = ($linha['cl_valor_ir']);
      $valor_csll = ($linha['cl_valor_csll']);
      $valor_iss = ($linha['cl_valor_iss']);
      $valor_outras_retencoes = ($linha['cl_valor_outras_retencoes']);
      $valor_base_calculo = ($linha['cl_valor_base_calculo']);
      $valor_aliquota = ($linha['cl_valor_aliquota']);
      $valor_desconto_condicionado = ($linha['cl_valor_desconto_condicionado']);
      $valor_desconto_incondicionado = ($linha['cl_valor_desconto_incondicionado']);
      $atividade_id = ($linha['cl_atividade_id']);
      $natureza_operacao_servico = ($linha['cl_natureza_operacao_servico']);
      $intermediario_id = ($linha['cl_intermediario_id']);


      $parceiro_id = $linha['cl_parceiro_id'];
      $parceiro_descricao = utf8_encode(consulta_tabela($conecta, "tb_parceiros", "cl_id", $parceiro_id, "cl_razao_social"));

      $transportadora_descricao = utf8_encode(consulta_tabela($conecta, "tb_parceiros", "cl_id", $transportadora_id, "cl_razao_social"));

      if ($parceiro_id == "") {
         $valor_credito_atual = 0;
      } else {
         $valor_credito_atual = (consulta_tabela($conecta, "tb_parceiros", "cl_id", $parceiro_id, "cl_valor_credito"));
      }

      $ambiente = verficar_paramentro($conecta, "tb_parametros", "cl_id", "35"); // 1 - homologacao 2 - producao
      if ($ambiente == "1") { //consultar o pdf da nota fiscal
         $server = verficar_paramentro($conecta, "tb_parametros", "cl_id", "60");
      } elseif ($ambiente == "2") {
         $server =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "61");
      }

      /*duplicatas */
      $select = "SELECT lcf.* from tb_lancamento_financeiro as lcf inner join tb_forma_pagamento as fpg on fpg.cl_id = lcf.cl_forma_pagamento_id 
      where lcf.cl_codigo_nf = '$codigo_nf' and fpg.cl_tipo_pagamento_id ='3'"; //apenas as forma de pagamento que tiver o tipo faturado
      $consultar_duplicatas = mysqli_query($conecta, $select);
      $qtd_duplicatas = mysqli_num_rows($consultar_duplicatas);
      $duplicatas = array(); // Crie um array para armazenar as duplicatas

      if ($qtd_duplicatas > 0) {
         $numero_duplicata = 0;
         while ($linha = mysqli_fetch_assoc($consultar_duplicatas)) {
            $data_vencimento = $linha['cl_data_vencimento'];
            $numero_duplicata = $numero_duplicata + 1;
            $valor_liquido = $linha['cl_valor_liquido'];

            // Adicione os campos da duplicata ao array $duplicata
            $duplicata = array(
               "data_vencimento" => $data_vencimento,
               "numero_duplicata" => "00" . $numero_duplicata,
               "valor_liquido" => $valor_liquido,
               // Adicione outros campos da duplicata, se houver
            );

            // Adicione o array da duplicata ao array de duplicatas
            $duplicatas[] = $duplicata;
         }
      }

      if ($data_pedido == null or empty($data_pedido)) {
         $data_pedido = $data_movimento;
      }
      $caminho_pdf_nf = $serie_nf == "NFS" ? $pdf_nf : $server . $pdf_nf;
      $caminho_xml_nf = $server . $caminho_xml_nf;

      $informacao = array(
         "data_movimento" => $data_movimento,
         "data_pedido" => $data_pedido,
         "parceiro_descricao" => $parceiro_descricao,
         "transportadora_descricao" => $transportadora_descricao,
         "parceiro_id" => $parceiro_id,
         "observacao" => $observacao,
         "cpf_cnpj_avulso_nf" => $cpf_cnpj_avulso_nf,
         "valor_liquido_venda" => $valor_liquido_venda,
         "sub_total_venda" => $sub_total_venda,
         "desconto_venda_real" => $desconto_venda_real,
         "vendedor_id_venda" => $vendedor_id_venda,
         "id_forma_pagamento_venda" => $id_forma_pagamento_venda,
         "descricao_forma_pagamento_venda" => $descricao_forma_pagamento_venda,
         "numero_nf" => $numero_nf,
         "serie_nf" => $serie_nf,
         "status_venda" => $status_venda,
         "status_recebimento" => $status_recebimento,
         "credito_atual" => $valor_credito_atual, //valor de credito disponivel para o cliente
         "valor_credito" => $valor_credito_nf, //valor de credito disponivel para o cliente
         "duplicatas" => $duplicatas, //array
         "visualizar_duplicata" => $visualizar_duplicata,
         "tipo_documento_nf" => $tipo_documento_nf,
         "carta_correcao_nf" => $server . $carta_correcao_nf,

         "pdf_nf" => $caminho_pdf_nf,
         "caminho_xml_nf" => $caminho_xml_nf,



         "data_emisao" => $data_emisao,
         "finalidade" => $finalidade,
         "cfop" => $cfop,
         "numero_pedido" => $numero_pedido,
         "frete" => $frete_id,
         "chave_acesso" => $chave_acesso,
         "protocolo" => $numero_protocolo,
         "transportadora_id" => $transportadora_id,
         "placa" => $placa_trans,
         "uf_veiculo" => $uf_veiculo_trans,
         "quantidade_trp" => $quantidade_trans,
         "especie_trans" => $especie_trans,
         "peso_bruto" => $peso_bruto,
         "peso_liquido" => $peso_liquido,
         "valor_frete" => $valor_frete,
         "outras_despesas" => $outras_despesas,
         "seguro" => $seguro,
         "chave_acesso_referencia" => $chave_acesso_referencia,
         "numero_nf_ref" => $numero_nf_ref,
         "retem_iss" => $retem_iss,

         "valor_pis_servico" => $valor_pis_servico,
         "valor_cofins_servico" => $valor_cofins_servico,
         "valor_deducoes" => $valor_deducoes,
         "valor_inss" => $valor_inss,
         "valor_ir" => $valor_ir,
         "valor_csll" => $valor_csll,
         "valor_iss" => $valor_iss,
         "valor_outras_retencoes" => $valor_outras_retencoes,
         "valor_base_calculo" => $valor_base_calculo,
         "valor_aliquota" => $valor_aliquota,
         "valor_desconto_condicionado" => $valor_desconto_condicionado,
         "valor_desconto_incondicionado" => $valor_desconto_incondicionado,
         "atividade_id" => $atividade_id,
         "natureza_operacao_servico" => $natureza_operacao_servico,
         "intermediario_id" => $intermediario_id,

      );

      $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
   }

   if ($acao == "resumo_valores") {

      $codigo_nf = $_POST['codigo_nf'];
      $totais = resumo_valor_nf_saida($conecta, $codigo_nf);
      $bcicms_nota = $totais['total_base_icms'];
      $icms_nota = $totais['total_valor_icms'];
      $bcicms_sub_nota = $totais['total_bc_icms_sub'];
      $icms_sub_nota = $totais['total_valor_icms_sub'];
      $ipi_nota = $totais['total_valor_ipi'];

      $bciss_nota = $totais['total_base_iss'];
      $iss_nota = $totais['total_valor_iss'];
      $bccofins_nota = $totais['total_base_cofins'];
      $cofins_nota = $totais['total_valor_cofins'];
      $bcpis_nota = $totais['total_base_pis'];
      $pis_nota = $totais['total_valor_pis'];

      $total_valor_bruto = $totais['total_valor_bruto'];
      $total_valor_liquido = $totais['total_valor_liquido'];
      $total_desconto = $totais['total_desconto'];

      $vlr_total_produtos = $totais['total_valor_produtos'];


      $valor_iss = $totais['valor_iss'];
      $valor_bruto_servico = $totais['valor_bruto_servico'];


      $informacao = array(
         "bcicms_nota" => $bcicms_nota,
         "icms_nota" => $icms_nota,
         "bcicms_sub_nota" => $bcicms_sub_nota,
         "icms_sub_nota" => $icms_sub_nota,
         "ipi_nota" => $ipi_nota,

         "bciss_nota" => $bciss_nota,
         "iss_nota" => $iss_nota,
         "bccofins_nota" => $bccofins_nota,
         "cofins_nota" => $cofins_nota,
         "bcpis_nota" => $bcpis_nota,
         "pis_nota" => $pis_nota,

         "valor_bruto" => $total_valor_bruto,
         "valor_liquido" => $total_valor_liquido,
         "desconto" => $total_desconto,

         "vlr_total_produtos" => $vlr_total_produtos,

         "base_calculo_servico" => $valor_bruto_servico,
         "valor_iss" => $valor_iss,
      );

      $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
   }

   if ($acao == "show_preview_nf") { //nf não finalizada, apenas um preview
      $parceiro_id = $_POST['parceiro_id'];
      if ($parceiro_id == "") {
         $valor_credito_atual = 0;
      } else {
         $valor_credito_atual = (consulta_tabela($conecta, "tb_parceiros", "cl_id", $parceiro_id, "cl_valor_credito"));
      }
      $informacao = array(
         "credito_atual" => $valor_credito_atual,
      );

      $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
   }

   if ($acao == "cancelar_nf") {
      $id_nf = $_POST['id_nf'];
      $codigo_nf = $_POST['codigo_nf'];
      $id_user_logado = verifica_sessao_usuario();
      $status_venda = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_status_venda");
      $autorizado_cancelar_venda = consulta_tabela($conecta, "tb_users", "cl_id", $id_user_logado, "cl_cancelar_venda");

      $parceiro_id = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_parceiro_id");
      $valor_credito_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_valor_credito");

      $serie_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_serie_nf");
      $numero_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_numero_nf");
      $valor_credito_parceiro = consulta_tabela($conecta, "tb_parceiros", "cl_id", $parceiro_id, "cl_valor_credito");

      if ($id_nf == "" or $codigo_nf == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel cancelar a venda, venda não encontrada, favor verifique");
      } elseif ($autorizado_cancelar_venda != "SIM") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel cancelar a venda, o seu usuário não tem autorização");
      } elseif ($status_venda == "3") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Não é possível cancelar a venda, pois ela já está cancelada");
      } else {
         if (cancelar_nf($conecta, $id_nf, $codigo_nf, $id_user_logado, $data)) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Venda cancelada com sucesso");
            // if ($valor_credito_nf > 0) {
            //    // $justificativa = utf8_decode("Crédito revogado após o cancelamento da $serie_nf$numero_nf, valor de $valor_credito_nf ");
            //    // adicionar_credito_parceiro($data, $parceiro_id, $valor_credito_nf, $justificativa); //incluir no historico de credito
            //    // $novo_valor_credito = $valor_credito_parceiro + $valor_credito_nf;
            //    // update_registro($conecta, "tb_parceiros", "cl_id", $parceiro_id, "", "", "cl_valor_credito", $novo_valor_credito); //adicionar o novo valor de credito na tabela de parceiro apos a venda
            // }
         } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor comunique o suporte");
         }
      }
   }
   if ($acao == "remover_nf_faturamento") {
      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = utf8_decode($value);
         // Remover aspas simples usando str_replace
         ${$name} = str_replace("'", "", ${$name});
      }




      $codigo_nf = isset($_POST['codigo_nf']) ? $_POST['codigo_nf'] : '';
      $id_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_id");
      $usuario_id = $_POST['usuario_id'];

      $status_venda = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_status_venda");
      $serie_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_serie_nf");
      $numero_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_numero_nf");

      if ($codigo_nf == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Documento não encontrado, favor verifique");
      } elseif ($status_venda == "3") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Não é possível remover a $serie_nf do faturamento, pois ela está cancelada");
      } elseif ($check_autorizador == "false") {
         $retornar["dados"] =  array("sucesso" => "autorizar", "title" =>  "Continue com a operação autorizando com a senha");
      } else {
         if (remover_nf_faturamento($conecta, $id_nf, $codigo_nf, $usuario_id, $data)) {
            $retornar["dados"] = array("sucesso" => true, "title" => "$serie_nf $numero_nf removida do faturamento com sucesso");
         } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor comunique o suporte");
         }
      }
   }
   if ($acao == "update_item_nf") {
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);
      $id_user_logado = verifica_sessao_usuario();
      $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $id_user_logado, "cl_usuario");
      $erro = false; //validador

      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = utf8_decode($value);
         // Remover aspas simples usando str_replace
         ${$name} = str_replace("'", "", ${$name});
      }

      $codigo_nf = consulta_tabela($conecta, "tb_nf_saida_item", "cl_id", $item_id, "cl_codigo_nf");
      $produto_id = consulta_tabela($conecta, "tb_nf_saida_item", "cl_id", $item_id, "cl_item_id");

      $numero_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_numero_nf");
      $serie_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_serie_nf");
      $status_venda = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_status_venda"));

      if ($codigo_nf == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('Item não encontrado, verifique com o suporte'));
      } elseif ($cfop_prod == "0") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('cfop'));
      } elseif ($ncm_prod == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro('ncm'));
      } elseif ($status_venda == 3) {
         $retornar["dados"] = array("sucesso" => false, "title" =>  "Não é possivel realizar alterações, pois a nota está cancelada");
      } else {


         // Verifique e formate os campos que requerem substituição de vírgula por ponto
         $campos_com_virgula = array(
            'base_icms_prod',
            'vlr_icms_prod',
            'base_icms_sub_prod',
            'vlr_icms_sub_prod',
            'ipi_prod',
            'ipi_devolvido_prod',
            'base_pis_prod',
            'pis_prod',
            'base_cofins_prod',
            'cofins_prod',
         );

         foreach ($campos_com_virgula as $campo) {
            if (verificaVirgula($$campo)) {
               $$campo = formatDecimal($$campo);
            }
         }

         $update = "UPDATE `tb_nf_saida_item` SET `cl_referencia` = '$referencia_prod', `cl_cst` = '$cst_icms_prod', `cl_ncm` = '$ncm_prod', 
         `cl_cest` = '$cest_prod', `cl_base_icms` = '$base_icms_prod', `cl_aliq_icms` = '$aliq_icms_prod', 
         `cl_icms` = '$vlr_icms_prod', `cl_base_icms_sbt` = '$base_icms_sub_prod', 
         `cl_icms_sbt` = '$vlr_icms_sub_prod', `cl_aliq_ipi` = '$aliq_ipi_prod', `cl_ipi` = '$ipi_prod',
          `cl_ipi_devolvido` = '$ipi_devolvido_prod', `cl_base_pis` = '$base_pis_prod', `cl_pis` = '$pis_prod', 
         `cl_cst_pis` = '$cst_pis_prod', `cl_base_cofins` = '$base_cofins_prod',`cl_cofins` = '$cofins_prod',
          `cl_cst_cofins` = '$cst_cofins_prod', `cl_base_iss` = '$base_iss_prod', 
         `cl_iss` = '$iss_prod', `cl_cfop` = '$cfop_prod', `cl_gtin` = '$gtin', `cl_numero_pedido` = '$numero_pedido_prod',
          `cl_item_pedido` = '$item_pedido_prod'  WHERE `cl_id` = $item_id";
         $operacao_update = mysqli_query($conecta, $update);
         if (!$operacao_update) {
            $erro = true;
            $mensagem = "tentativa sem sucesso de alterar o item de código $produto_id da $serie_nf $numero_nf, Erro ao atualizar a query, atualizar item nf";
         }
         if ($erro == false and !recalcular_valor_nf_saida($conecta, $codigo_nf)) {
            $erro = true;
            $mensagem = "tentativa sem sucesso de alterar o item de código $produto_id da $serie_nf $numero_nf, Erro ao recalcular a nf";
         }


         if ($erro == false) {
            $retornar["dados"] =  array("sucesso" => true, "title" => "Item alterado com sucesso");
            $mensagem = utf8_decode("Alterou o item código $produto_id da $serie_nf $numero_nf");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

            // Se tudo ocorreu bem, confirme a transação
            mysqli_commit($conecta);
         } else {
            // Se ocorrer um erro, reverta a transação
            mysqli_rollback($conecta);
            $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
            $mensagem = utf8_decode($mensagem);
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }
   if ($acao == "show_det_produto") {
      $id_produto = $_POST['produto_id'];
      $select = "SELECT * from tb_nf_saida_item where cl_id = $id_produto";
      $consultar_produtos = mysqli_query($conecta, $select);
      $linha = mysqli_fetch_assoc($consultar_produtos);
      $descricao = utf8_encode($linha['cl_descricao_item']);
      $quantidade = $linha['cl_quantidade'];
      $valor_unitario = $linha['cl_valor_unitario'];
      // $valor_total = $linha['cl_valor_total'];
      $unidade = utf8_encode($linha['cl_unidade']);
      $base_cofins = $linha['cl_base_cofins'];
      $referencia = $linha['cl_referencia'];
      $cst = $linha['cl_cst'];
      $ncm = $linha['cl_ncm'];
      $cest = $linha['cl_cest'];
      $base_icms = $linha['cl_base_icms'];
      $aliq_icms = $linha['cl_aliq_icms'];
      $icms = $linha['cl_icms'];
      $base_icms_sbt = $linha['cl_base_icms_sbt'];
      $icms_sbt = $linha['cl_icms_sbt'];
      $aliq_ipi = $linha['cl_aliq_ipi'];
      $ipi = $linha['cl_ipi'];
      $ipi_devolvido = $linha['cl_ipi_devolvido'];
      $base_pis = $linha['cl_base_pis'];
      $pis = $linha['cl_pis'];
      $cst_pis = $linha['cl_cst_pis'];
      $base_cofins = $linha['cl_base_cofins'];
      $cofins = $linha['cl_cofins'];
      $cst_cofins = $linha['cl_cst_cofins'];
      $base_iss = $linha['cl_base_iss'];
      $iss = $linha['cl_iss'];
      $cfop = $linha['cl_cfop'];
      $gtin = $linha['cl_gtin'];
      $item_pedido = $linha['cl_item_pedido'];
      $numero_pedido = $linha['cl_numero_pedido'];
      $atividade_id = $linha['cl_atividade_id'];

      $item_id = ($linha['cl_item_id']);
      $valor_total = $valor_unitario * $quantidade;

      $preco_venda_atual =  validar_prod_venda($conecta, $item_id, "cl_preco_venda"); //preco de venda do produto no cadastro
      if ($preco_venda_atual == null or $preco_venda_atual == 0) {
         $desconto_percente = 0;
      } else {
         $desconto_percente = calcularPorcentagemDesconto($valor_unitario, $preco_venda_atual);
      }


      $informacao = array(
         "descricao" => $descricao,
         "quantidade" => $quantidade,
         "preco_venda" => $valor_unitario,
         "unidade" => $unidade,
         "valor_total" => $valor_total,
         "preco_venda_atual" => $preco_venda_atual,
         "desconto" => $desconto_percente,
         "id_produto" => $item_id,

         "referencia" => $referencia,
         "cst" => $cst,
         "ncm" => $ncm,
         "cest" => $cest,
         "base_icms" => $base_icms,
         "aliq_icms" => $aliq_icms,
         "icms" => $icms,
         "base_icms_sbt" => $base_icms_sbt,
         "icms_sbt" => $icms_sbt,
         "aliq_ipi" => $aliq_ipi,
         "ipi" => $ipi,
         "ipi_devolvido" => $ipi_devolvido,
         "base_pis" => $base_pis,
         "pis" => $pis,
         "cst_pis" => $cst_pis,
         "base_cofins" => $base_cofins,
         "cofins" => $cofins,
         "cst_cofins" => $cst_cofins,
         "base_iss" => $base_iss,
         "iss" => $iss,
         "cfop" => $cfop,
         "gtin" => $gtin,
         "item_pedido" => $item_pedido,
         "numero_pedido" => $numero_pedido,
         "atividade_id" => $atividade_id,

      );

      $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
   }
   if ($acao == "update_nf_saida") {
      $id_user_logado = verifica_sessao_usuario();
      $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $id_user_logado, "cl_usuario");

      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = utf8_decode($value);
         // Remover aspas simples usando str_replace
         ${$name} = str_replace("'", "", ${$name});
      }

      if (isset($_POST['visualzar_duplicatas'])) {
         $visualzar_duplicatas = 1; //visualizar duplicatas na nota
      } else {
         $visualzar_duplicatas = 0;
      }
      if (isset($_POST['retem_iss'])) {
         $retem_iss = 1; //serviço
      } else {
         $retem_iss = 0;
      }

      $valida_desconto = (consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id, "cl_valor_desconto")); //consultar valor do desconto
      $valida_outras_despesas = (consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id, "cl_valor_outras_despesas"));
      $valida_valor_seguro = (consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id, "cl_valor_seguro"));
      $valida_valor_frete = (consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id, "cl_valor_frete"));
      $status_recebimento = (consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id, "cl_status_recebimento"));
      $status_venda = (consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id, "cl_status_venda"));

      if ($id == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Nota não encontrada, não foi possivel alterar, favor verifique");
      } elseif ($finalidade == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro('finalidade'));
      } elseif ($fpagamento == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro('forma pagamento'));
      } elseif ($cfop == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro('cfop'));
      } elseif ($frete == "SN") {
         $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro('frete'));
      } elseif ($parceiro_id == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro('cliente'));
      } elseif ($tipo_nota == "SN") {
         $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro('tipo nota'));
      } elseif ($status_venda == 3) {
         $retornar["dados"] = array("sucesso" => false, "title" =>  "Não é possivel realizar alterações, pois a nota está cancelada");
      } elseif ($status_recebimento == "2" and (($valida_desconto != $desconto_nota) or ($valida_outras_despesas != $outras_despesas) or ($valida_valor_seguro != $vseguro) or ($valida_valor_frete != $vfrete))) {
         $retornar["dados"] = array("sucesso" => false, "title" =>  "Não é possivel realizar alterações no valor de uma nota já recebida, favor, verifique");
      } else {

         $codigo_nf = (consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id, "cl_codigo_nf"));
         $valor_entrega_delivery = (consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id, "cl_valor_entrega_delivery"));
         $valor_credito = (consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id, "cl_valor_credito"));
         $serie_nf = (consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id, "cl_serie_nf"));
         $numero_nf = (consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id, "cl_numero_nf"));
         $operacao = (consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id, "cl_operacao"));

         $totais = resumo_valor_nf_saida($conecta, $codigo_nf); //informações sobre os itens na nota
         $vlr_total_produtos = $totais['total_valor_produtos'];
         $icms_sub_nota = $totais['total_valor_icms_sub'];
         $ipi_nota = $totais['total_valor_ipi'];


         $valor_bruto =  $vlr_total_produtos + $icms_sub_nota + $ipi_nota + $vfrete + $outras_despesas + $vseguro + $valor_entrega_delivery;
         $valor_total_nota = $vlr_total_produtos + $icms_sub_nota + $ipi_nota + $vfrete + $outras_despesas + $vseguro + $valor_entrega_delivery -  $desconto_nota - $valor_credito;
         // if ($operacao == "DEVCOMPRA") {
         //    $valor_total_nota = 0;
         // }
         // $observacao = utf8_decode($observacao);

         /*servico */
         $valor_iss_retido = 0;

         $valor_iss = (($vlr_total_produtos * $valor_aliquota) / 100);
         if ($retem_iss == 1) {
            $valor_iss_retido = $valor_iss;
         }

         $update = "UPDATE `tb_nf_saida` SET `cl_finalidade` = '$finalidade', `cl_parceiro_id` = '$parceiro_id',
          `cl_forma_pagamento_id` = '$fpagamento', `cl_valor_bruto` = '$valor_bruto', `cl_valor_liquido` = '$valor_total_nota',
           `cl_valor_desconto` = '$desconto_nota', `cl_valor_frete` = '$vfrete', `cl_tipo_frete_id` = '$frete',
            `cl_valor_seguro` = '$vseguro', `cl_numero_pedido` = '$numero_pedido', `cl_valor_outras_despesas` = '$outras_despesas',
             `cl_transportadora_id` = '$transportadora_id',
              `cl_placa_trans` = '$placa', `cl_uf_veiculo_trans` = '$uf_veiculo', `cl_quantidade_trans` = '$quantidade_trp',
               `cl_especie_trans` = '$especie', `cl_numero_nf_ref` = '$numero_nf_ref',`cl_chave_acesso_referencia` = '$chave_acesso_ref', 
                `cl_peso_bruto` = '$peso_bruto',
          `cl_peso_liquido` = '$peso_liquido', `cl_observacao` = '$observacao', 
          `cl_cfop` = '$cfop',`cl_visualizar_duplicata` = '$visualzar_duplicatas',`cl_tipo_documento_nf` = '$tipo_nota',
          `cl_retem_iss` = '$retem_iss',
          
         `cl_valor_pis_servico` = '$valor_pis_servico' , `cl_valor_cofins_servico` = '$valor_cofins_servico' , `cl_valor_deducoes` = '$valor_deducoes' , 
          `cl_valor_inss` = '$valor_inss' ,  `cl_valor_ir` = '$valor_ir' , `cl_valor_csll` = '$valor_csll',
          `cl_valor_iss` = '$valor_iss' , `cl_valor_iss_retido` = '$valor_iss_retido' , `cl_valor_outras_retencoes` = '$valor_outras_retencoes' , 
          `cl_valor_base_calculo` = '$vlr_total_produtos', `cl_valor_aliquota` = '$valor_aliquota' ,
           `cl_valor_desconto_condicionado` = '$valor_desconto_condicionado' ,
           `cl_valor_desconto_incondicionado` = '$valor_desconto_incondicionado' ,
            `cl_atividade_id` = '$atividade_id', `cl_natureza_operacao_servico` = '$natureza_operacao_servico',
        `cl_intermediario_id` = '$parceiro_terceirizado_id'
          WHERE `cl_id` = $id";
         $operacao_update = mysqli_query($conecta, $update);
         if ($operacao_update) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Nota alterada com sucesso");

            $mensagem = utf8_decode("Alterou a nota $serie_nf $numero_nf");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor comunique o suporte");
            $mensagem = utf8_decode("Tentativa sem sucesso de alterar a nota $serie_nf $numero_nf");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }

   if ($acao == "update_cpf") { //incluir oualterar o cfop nfc
      $id_user_logado = verifica_sessao_usuario();
      $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $id_user_logado, "cl_usuario");
      $id = $_POST['nf_id'];
      $cpf = $_POST['cpf'];
      $valida_envio_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id, "cl_numero_protocolo");
      $cpf = preg_replace('/[^0-9]/', '', $cpf); // remover caracteres especias

      if (empty($id)) {
         $retornar["dados"] = array("sucesso" => false, "title" => "Nota não encontrada!");
      } elseif (!empty($valida_envio_nf)) {
         $retornar["dados"] = array("sucesso" => false, "title" => "Não é possível realizar mais alterações na nota, pois ela já foi enviada");
      } else {
         $serie_nf = (consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id, "cl_serie_nf"));
         $numero_nf = (consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id, "cl_numero_nf"));

         $update = "UPDATE `tb_nf_saida` SET `cl_cpf_cnpj_avulso_nf` = '$cpf' WHERE `cl_id` = $id";
         $operacao_update = mysqli_query($conecta, $update);
         if ($operacao_update) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Cpf alterado com sucesso");
            $mensagem = utf8_decode("Alterou o cpf da nota  $serie_nf $numero_nf");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor comunique o suporte");
            $mensagem = utf8_decode("Tentativa sem sucesso de alterar o cpf da nota $serie_nf $numero_nf");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }
   if ($acao == "delete_item") {

      $id_item_nf = $_POST['id_item_nf'];

      $produto_id = consulta_tabela($conecta, "tb_nf_saida_item", "cl_id", $id_item_nf, "cl_item_id");
      $codigo_nf = consulta_tabela($conecta, "tb_nf_saida_item", "cl_id", $id_item_nf, "cl_codigo_nf");
      $quantidade = consulta_tabela($conecta, "tb_nf_saida_item", "cl_id", $id_item_nf, "cl_quantidade");
      $id_user_logado = verifica_sessao_usuario();

      $status_recebimento = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_status_recebimento");
      $status_venda = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_status_venda");

      if ($id_item_nf == "" or $codigo_nf == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel remover o produto, produto não encontrado, favor verifique");
      } elseif ($status_venda == "3") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel remover o produto, a venda está cancelada");
      } elseif ($status_recebimento == "2") { //a venda está recebida
         $retornar["dados"] = array("sucesso" => false, "title" => "Não é possível remover o produto, pois a venda já foi recebida, favor, remova-o do faturamento antes de prosseguir com a ação");
      } else {
         if (delete_item_nf($conecta, $id_item_nf, $produto_id, $codigo_nf, $quantidade, $id_user_logado, $data)) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Item removido com sucesso");
         } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
         }
      }
   }
   if ($acao == "recalcular_nf") { //recalcular valores dos itens da nf
      $nf_id = $_POST['nf_id'];
      $codigo_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $nf_id, "cl_codigo_nf");
      $serie_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $nf_id, "cl_serie_nf");
      $status_venda = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $nf_id, "cl_status_venda");

      if ($codigo_nf == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Não foi possivel encontrar a nota, favor verifique");
      } elseif ($status_venda == 3) {
         $retornar["dados"] = array("sucesso" => false, "title" =>  "Não é recalcular a nota, pois a nota está cancelada");
      } else {
         if (gerar_nf_item($codigo_nf, $serie_nf) and recalcular_valor_nf_saida($conecta, $codigo_nf)) {
            $retornar["dados"] = array("sucesso" => true, "title" => "A nota foi recalculada com sucesso");
         } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
         }
      }
   }
   echo json_encode($retornar);
}


if (isset($_POST['consultar_select'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";
   $retornar = array();
   $array_consulta_status_lancamento = array();
   $array_consulta_classificao_financeiro = array();

   $select = "SELECT * from tb_status_recebimento";
   $consultar_status_lancamento = mysqli_query($conecta, $select);

   $select = "SELECT * from tb_classificacao_financeiro";
   $consultar_classificao_financeiro = mysqli_query($conecta, $select);



   if ($consultar_status_lancamento) {
      while ($linha = mysqli_fetch_assoc($consultar_status_lancamento)) {
         $descricao = $linha['cl_descricao'];
         $id = $linha['cl_id'];

         $informacao = array(
            "descricao" => $descricao,
            'id' => $id,

         );
         array_push($array_consulta_status_lancamento, $informacao);
      }
   }

   if ($consultar_classificao_financeiro) {
      while ($linha = mysqli_fetch_assoc($consultar_classificao_financeiro)) {
         $descricao = $linha['cl_descricao'];
         $id = $linha['cl_id'];

         $informacao = array(
            "descricao" => $descricao,
            'id' => $id,
         );
         array_push($array_consulta_classificao_financeiro, $informacao);
      }
   }
   $retornar["dados"] = array("sucesso" => true, "status_lancamento" => $array_consulta_status_lancamento, "classificao_financeiro" => $array_consulta_classificao_financeiro);

   echo json_encode($retornar);
}


//consultar vendedor
$select = "SELECT * from tb_users where cl_vendedor ='SIM' ";
$consultar_vendedor = mysqli_query($conecta, $select);

// $select = "SELECT * from tb_forma_pagamento where cl_ativo ='S' ";
// $consultar_forma_pagamento = mysqli_query($conecta, $select);



if (isset($_GET['recibo'])) {

   $codigo_nf = $_GET['codigo_nf'];
   $serie_nf = $_GET['serie_nf'];

   /*dados da empresa */
   $select = "SELECT  * from tb_empresa where cl_id ='1' ";
   $consultar_empresa = mysqli_query($conecta, $select);
   $linha = mysqli_fetch_assoc($consultar_empresa);
   $nome_fantasia_empresa = utf8_encode($linha['cl_nome_fantasia']);
   $empresa = utf8_encode($linha['cl_empresa']);
   $cnpj_empresa  = ($linha['cl_cnpj']);
   $endereco_empresa = utf8_encode($linha['cl_endereco']);
   $numero_empresa = ($linha['cl_numero']);
   $cep_empresa = ($linha['cl_cep']);
   $telefone_empresa = ($linha['cl_telefone']);
   $cidade_empresa =  utf8_encode($linha['cl_cidade']);
   $estado_empresa = ($linha['cl_estado']);

   $url_qrdcode = "$url_init/$empresa/view/venda/venda_mercadoria/recibo/recibo_nf.php?recibo=true&codigo_nf=$codigo_nf&serie_nf=$serie_nf";

   /*dados da venda */
   $select = "SELECT nf.cl_data_pedido_delivery,nf.cl_valor_bruto, nf.cl_codigo_nf, nf.cl_observacao,nf.cl_status_venda, fpg.cl_tipo_pagamento_id as tipopg, nf.cl_id as id,nf.cl_data_movimento,nf.cl_numero_nf,nf.cl_serie_nf,nf.cl_status_recebimento,user.cl_nome as vendedor,
   nf.cl_valor_desconto,nf.cl_valor_liquido,prc.cl_razao_social,prc.cl_nome_fantasia,fpg.cl_descricao as formapgt from tb_nf_saida as nf inner join tb_parceiros as prc on prc.cl_id = nf.cl_parceiro_id inner join
    tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id inner join tb_users as user on user.cl_id = nf.cl_vendedor_id
     WHERE nf.cl_codigo_nf ='$codigo_nf' and nf.cl_serie_nf='$serie_nf' ";
   $consultar_nf_saida = mysqli_query($conecta, $select);
   $linha = mysqli_fetch_assoc($consultar_nf_saida);
   $data_venda = ($linha['cl_data_pedido_delivery']);
   $data_movimento_b = ($linha['cl_data_movimento']);
   $numero_nf_b = ($linha['cl_numero_nf']);
   $codigo_nf = ($linha['cl_codigo_nf']);
   $serie_nf_b = ($linha['cl_serie_nf']);
   $status_recebmento_b = ($linha['cl_status_recebimento']);
   $status_recebmento_b_2 = ($linha['cl_status_recebimento']);
   $forma_pagamento_b = utf8_encode($linha['formapgt']);
   $razao_social_b = utf8_encode($linha['cl_razao_social']);
   $nome_fantasia_b = utf8_encode($linha['cl_nome_fantasia']);
   $valor_bruto_b = ($linha['cl_valor_bruto']);
   $valor_desconto_b = ($linha['cl_valor_desconto']);
   $valor_liquido_b = ($linha['cl_valor_liquido']);
   $vendedor_b = utf8_encode($linha['vendedor']);
   $tipo_pagamento = ($linha['tipopg']);
   $status_venda = ($linha['cl_status_venda']);
   $observacao = utf8_encode($linha['cl_observacao']);

   $select = "SELECT * from tb_nf_saida_item where cl_codigo_nf = '$codigo_nf' and cl_serie_nf='$serie_nf'";
   $consultar_nf_saida_item = mysqli_query($conecta, $select);
}

if (isset($_GET['carne_venda'])) {

   $codigo_nf = $_GET['codigo_nf'];
   $serie_nf = $_GET['serie_nf'];

   /*dados da empresa */
   $select = "SELECT  * from tb_empresa where cl_id ='1' ";
   $consultar_empresa = mysqli_query($conecta, $select);
   $linha = mysqli_fetch_assoc($consultar_empresa);
   $nome_fantasia_empresa = utf8_encode($linha['cl_nome_fantasia']);
   $empresa = utf8_encode($linha['cl_empresa']);
   $cnpj_empresa  = ($linha['cl_cnpj']);
   $endereco_empresa = utf8_encode($linha['cl_endereco']);
   $numero_empresa = ($linha['cl_numero']);
   $cep_empresa = ($linha['cl_cep']);
   $telefone_empresa = ($linha['cl_telefone']);
   $cidade_empresa =  utf8_encode($linha['cl_cidade']);
   $estado_empresa = ($linha['cl_estado']);

   $url_qrdcode = "$url_init/$empresa/view/venda/venda_mercadoria/recibo/carne_nf.php?carne_venda=true&codigo_nf=$codigo_nf&serie_nf=$serie_nf";
   /*dados da venda */
   $select = "SELECT prc.cl_telefone as telefoneclt, prc.*, nf.cl_data_pedido_delivery,nf.cl_valor_bruto, nf.cl_codigo_nf, nf.cl_observacao,nf.cl_status_venda, fpg.cl_tipo_pagamento_id as tipopg, nf.cl_id as id,nf.cl_data_movimento,nf.cl_numero_nf,nf.cl_serie_nf,nf.cl_status_recebimento,user.cl_nome as vendedor,
   nf.cl_valor_desconto,nf.cl_valor_liquido,prc.cl_razao_social,prc.cl_nome_fantasia,fpg.cl_descricao as formapgt from tb_nf_saida as nf inner join tb_parceiros as prc on prc.cl_id = nf.cl_parceiro_id inner join
    tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id inner join tb_users as user on user.cl_id = nf.cl_vendedor_id
     WHERE nf.cl_codigo_nf ='$codigo_nf' ";
   $consultar_nf_saida = mysqli_query($conecta, $select);
   $linha = mysqli_fetch_assoc($consultar_nf_saida);
   $data_venda = ($linha['cl_data_pedido_delivery']);
   $data_movimento = ($linha['cl_data_movimento']);
   $numero_nf = ($linha['cl_numero_nf']);
   $serie_nf = ($linha['cl_serie_nf']);
   $status_recebmento = ($linha['cl_status_recebimento']);
   $forma_pagamento = utf8_encode($linha['formapgt']);
   $razao_social_cliente = utf8_encode($linha['cl_razao_social']);
   $nome_fantasia = utf8_encode($linha['cl_nome_fantasia']);
   $endereco_cliente = utf8_encode($linha['cl_endereco']);
   $telefone_cliente = utf8_encode($linha['telefoneclt']);

   $valor_bruto = ($linha['cl_valor_bruto']);
   $valor_desconto = ($linha['cl_valor_desconto']);
   $valor_liquido = ($linha['cl_valor_liquido']);
   $vendedor = utf8_encode($linha['vendedor']);
   $tipo_pagamento = ($linha['tipopg']);
   $status_venda = ($linha['cl_status_venda']);
   $observacao = utf8_encode($linha['cl_observacao']);

   $select = "SELECT * from tb_lancamento_financeiro where cl_codigo_nf = '$codigo_nf'";
   $consultar_lancamentos = mysqli_query($conecta, $select);
}







// //consultar status recebimento
// $select = "SELECT * from tb_status_recebimento ";
// $consultar_status_recebimento = mysqli_query($conecta, $select);

// //consultar forma pagamento
// $select = "SELECT * from tb_forma_pagamento ";
// $consultar_forma_pagamento = mysqli_query($conecta, $select);


// //consultar classificacao financeiro
// $select = "SELECT * from tb_classificacao_financeiro";
// $consultar_classificacao_financeiro = mysqli_query($conecta, $select);