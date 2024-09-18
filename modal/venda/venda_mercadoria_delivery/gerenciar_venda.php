<?php

if (isset($_GET['form_id'])) {
   $id_nf = $_GET['form_id'];
   $tipo = $_GET['tipo'];
   $codigo_nf = $_GET['codigo_nf'];
} else {
   $id_nf = "";
   $tipo = "";
   $codigo_nf = "";
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
      $select = "SELECT nf.cl_parceiro_avulso, nf.cl_codigo_nf, nf.cl_status_venda, fpg.cl_tipo_pagamento_id as tipopg, nf.cl_id as id,nf.cl_data_movimento,nf.cl_numero_nf,nf.cl_serie_nf,nf.cl_status_recebimento,user.cl_usuario as vendedor,
      nf.cl_valor_desconto,nf.cl_valor_liquido,prc.cl_razao_social,prc.cl_nome_fantasia,fpg.cl_descricao as formapgt from tb_nf_saida as nf inner join tb_parceiros as prc on prc.cl_id = nf.cl_parceiro_id inner join
       tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id inner join tb_users as user on user.cl_id = nf.cl_vendedor_id WHERE
        nf.cl_data_movimento between '$data_inicial' and '$data_final' and nf.cl_status_venda !='2' order by nf.cl_id desc";
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

      $select = "SELECT nf.cl_parceiro_avulso, nf.cl_codigo_nf, nf.cl_status_venda, fpg.cl_tipo_pagamento_id as tipopg, nf.cl_id as id,nf.cl_data_movimento,nf.cl_numero_nf,nf.cl_serie_nf,nf.cl_status_recebimento,user.cl_usuario as vendedor,
      nf.cl_valor_desconto,nf.cl_valor_liquido,prc.cl_razao_social,prc.cl_nome_fantasia,fpg.cl_descricao as formapgt from tb_nf_saida as nf inner join
       tb_parceiros as prc on prc.cl_id = nf.cl_parceiro_id inner join
       tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id inner join tb_users as user on user.cl_id = nf.cl_vendedor_id WHERE nf.cl_data_movimento between '$data_inicial' and '$data_final' and 
      ( nf.cl_numero_nf  like '%$pesquisa%' or prc.cl_razao_social  like '%$pesquisa%' or prc.cl_nome_fantasia  like '%$pesquisa%' )    ";

      if ($status_recebimento != "0") {
         $select .= " and nf.cl_status_recebimento = '$status_recebimento'  ";
      }

      if ($forma_pgt != "0") {
         $select .= " and nf.cl_forma_pagamento_id = '$forma_pgt' ";
      }
      $select .= " and nf.cl_status_venda !='2'  order by nf.cl_id desc";
      $consultar_venda_mercadoria = mysqli_query($conecta, $select);
      if (!$consultar_venda_mercadoria) {
         die("Falha no banco de dados" . mysqli_error($conecta));
      } else {
         $qtd = mysqli_num_rows($consultar_venda_mercadoria);
      }
   }
}

if (isset($_GET['tabela_produto'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   $codigo_nf = $_GET['codigo_nf'];
   $select  = "SELECT * from tb_nf_saida_item where cl_codigo_nf = '$codigo_nf'";
   $consultar_produtos = mysqli_query($conecta, $select);
   $qtd_consultar_produtos = mysqli_num_rows($consultar_produtos);
}

if (isset($_GET['adicionar_observacao_prd_delivery'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";

   $id_item_nf = $_GET['id_item_nf'];
   $select  = "SELECT * from tb_nf_saida_item where cl_id = $id_item_nf";
   $consultar = mysqli_query($conecta, $select);
   $linha = mysqli_fetch_assoc($consultar);
   $observacao_produto = utf8_encode($linha['cl_observacao_delivery']);
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
   // $classficacao_financeiro_id = verficar_paramentro($conecta, "tb_parametros", "cl_id", "11"); //verificar o id do cliente avulso
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
      $descricao_produto = utf8_decode($itens['descricao_produto']);
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
         } else {
            $desconto_real = 0;
            $calula_desconto = 0;
         }

         if ($preco_venda == $preco_venda_promocao) { //se o preço de venda for o mesmo que está no preço promocional
            $preco_venda = $preco_venda_promocao;
            $desconto_real = 0;
            $calula_desconto = 0;
         }

         if ($descricao_produto == "") {
            $retornar["dados"] =  array("sucesso" => false, "title" => "Favor informe todas as informações do produto");
         } elseif ($venda_prd_vlr_zerado != "S" and ($preco_venda == 0)) {
            $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel adicionar o produto, o valor total do produto não pode ser 0");
         } elseif ($validar_venda_sem_estoque == "N" and (validar_qtd_prod_venda($conecta, $id_produto, $codigo_nf, $quantidade) > $estoque)) {
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
                        "$novo_id_inserido",
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

      $id_produto = $itens['id_produto']; //id do produto que está cadastrado no sistema
      $id_item_nf = $itens['id_item_nf']; //id do produto na tabela nfe_saida_item
      $descricao_produto = utf8_decode($itens['descricao_produto']);
      $preco_venda = $itens['preco_venda'];
      $quantidade = $itens['quantidade'];
      $unidade = utf8_decode($itens['unidade']);

      // Acessar os arrays de adicionaisGratuitos, adicionais e complementos
      $adicionaisGratuitos = $itens['adicionaisGratuitos'];
      $adicionais = $itens['adicionais'];
      $complementos = $itens['complementos'];


      // Agora você pode iterar pelos arrays e acessar os IDs e valores dos adicionais e complementos
      // $retornar["dados"] =  array("sucesso" => true, "title" => "ok");


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
         }

         if ($preco_venda == $preco_venda_promocao) {
            $preco_venda = $preco_venda_promocao;
            $desconto_real = 0;
            $calula_desconto = 0;
         }


         if ($status_venda == "3") { //venda cancelada
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possível alterar o produto, pois a venda foi cancelada");
         } elseif ($status_recebimento == "2") { //a venda está recebida
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possível alterar o produto, pois a venda já foi recebida, favor, remova-o do faturamento antes de prosseguir com a ação");
         } elseif ($descricao_produto == "") {
            $retornar["dados"] =  array("sucesso" => false, "title" => "Favor informe todas as informações do produto");
         } elseif ($venda_prd_vlr_zerado != "S" and ($preco_venda == 0)) {
            $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel adicionar o produto, o valor total do produto não pode ser 0");
         } elseif (($desconto_maximo_produto < $calula_desconto and ($desconto_maximo_produto != "") and ($check_autorizador != "true"))) {
            $retornar["dados"] =  array("sucesso" => "autorizar", "title" => "Não é possivel alterar o produto, o desconto está acima do permitido, continue com a operação autorizando com a senha");
         } elseif ((validar_qtd_prod_venda($conecta, $id_produto, $codigo_nf, $quantidade) > $estoque and $validar_venda_sem_estoque == "N")) { //validar se a quantidade adicionado mais o mesmo produto que esta na venda atende o estoque
            $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel adicionar o produto, a demanda no estoque não atende");
         } else { //alterar o produto com a venda já finalizada

            $delete = "DELETE FROM tb_nf_saida_item where cl_id_pai_delivery ='$id_item_nf' and cl_codigo_nf= '$codigo_nf'"; //deletar os produtos adicionais e complementos para adicionar novamente
            $operacao_delete = mysqli_query($conecta, $delete);
            if (!$operacao_delete) {
               $erro = false;
            }

            if ($nf != "") { //Adicionando um prduto a uma venda já finalizada

               $update = "UPDATE `tb_nf_saida_item` SET `cl_descricao_item` = '$descricao_produto', `cl_quantidade` = '$quantidade',
               `cl_valor_unitario` = '$preco_venda', `cl_valor_total` = '$valor_total', `cl_desconto` = '$desconto_real',`cl_id_delivery` = '$id_item_nf' WHERE `tb_nf_saida_item`.`cl_id` = $id_item_nf  ";
               $operacao_update = mysqli_query($conecta, $update);
               if ($operacao_update) {

                  $mensagem = 'Produto alterado com sucesso';
                  if (count($adicionaisGratuitos) > 0) {
                     if (!insertAcompanhamentoNf($conecta, $adicionaisGratuitos, "1", $usuario_id, $quantidade, $id_item_nf, "ADICIONAL", "GRATIS", $data_lancamento, "")) {
                        $erro = true;
                     }
                  }
                  if (count($adicionais) > 0) {
                     if (!insertAcompanhamentoNf($conecta, $adicionais, "1", $usuario_id, $quantidade, $id_item_nf, "ADICIONAL", "PAGO", $data_lancamento, "")) {
                        $erro = true;
                     }
                  }
                  if (count($complementos) > 0) {
                     if (!insertAcompanhamentoNf($conecta, $complementos, "1", $usuario_id, $quantidade, $id_item_nf, "COMPLEMENTO", "PAGO", $data_lancamento, "")) {
                        $erro = true;
                     }
                  }

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
             `cl_valor_unitario` = '$preco_venda', `cl_valor_total` = '$valor_total', `cl_desconto` = '$desconto_real',`cl_id_delivery` = '$id_item_nf'  WHERE `tb_nf_saida_item`.`cl_id` = $id_item_nf  ";
               $operacao_update = mysqli_query($conecta, $update);
               if ($operacao_update) {
                  $mensagem = 'Produto alterado com sucesso';
                  if (count($adicionaisGratuitos) > 0) {
                     if (!insertAcompanhamentoNf($conecta, $adicionaisGratuitos, "2", $usuario_id, $quantidade, $id_item_nf, "ADICIONAL", "GRATIS", $data_lancamento, "")) {
                        $erro = true;
                     }
                  }
                  if (count($adicionais) > 0) {
                     if (!insertAcompanhamentoNf($conecta, $adicionais, "2", $usuario_id, $quantidade, $id_item_nf, "ADICIONAL", "PAGO", $data_lancamento, "")) {
                        $erro = true;
                     }
                  }
                  if (count($complementos) > 0) {
                     if (!insertAcompanhamentoNf($conecta, $complementos, "2", $usuario_id, $quantidade, $id_item_nf, "COMPLEMENTO", "PAGO", $data_lancamento, "")) {
                        $erro = true;
                     }
                  }
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
      ///  $momento_venda = $_POST['momento_venda'];
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

      $opcao_delivery = $_POST['opcao_delivery'];
      $endereco_delivery = utf8_decode($_POST['endereco_delivery']);
      $valor_entrega_delivery = $_POST['valor_entrega_delivery'];

      $valor_total_bruto = resumo_valor_nf_saida($conecta, $codigo_nf)['total_valor_bruto'];
      // $valor_total_bruto = consulta_tabela_query($conecta, "SELECT sum(cl_valor_total) as total from tb_nf_saida_item where cl_codigo_nf ='$codigo_nf' ", 'total'); //valores total dos produtos
      $data_lancamento = verficar_paramentro($conecta, "tb_parametros", "cl_id", "15") == "S" ? $_POST['data_movimento'] : $data_lancamento; //assumir a data que está no campo data movimento na venda



      if (verificaVirgula($desconto_venda_real)) { //verificar se tem virgula
         $desconto_venda_real = formatDecimal($desconto_venda_real); // formatar virgula para ponto
      }
      if (verificaVirgula($valor_entrega_delivery)) { //verificar se tem virgula
         $valor_entrega_delivery = formatDecimal($valor_entrega_delivery); // formatar virgula para ponto
      }

      $desconto_percente = ($desconto_venda_real / $valor_total_bruto) * 100; //desconto convertido em porcentagem

      if ($parceiro_id == "") { //se a venda não possuir cliente será colocado o cliente padrão que está setado no parametro
         $parceiro_id = $cliente_avulso_id;
      }

      $parceiro_avulso = $parceiro_descricao;

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

         $valor_liquido_venda = $valor_entrega_delivery + $valor_total_bruto - $desconto_venda_real; //valor liquido da venda

         $nf = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_numero_nf"); //verificar se essa venda já foi finalizada

         if ($nf != "") { //editar venda
            $status_recebimento = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_status_recebimento"); //verificar se essa venda já foi finalizada
            $status_venda = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_status_venda"); //verificar se essa venda já foi finalizada
            if ($status_venda == "3") { //venda cancelada
               $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel alterar a venda, a venda está cancelada");
            } elseif ($status_recebimento == "2") { //venda recebida
               $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel alterar a venda, é necessario remover a venda do faturamento");
            } else {

               $update = "UPDATE `tb_nf_saida` SET `cl_parceiro_id` = '$parceiro_id', `cl_parceiro_avulso` = '$parceiro_avulso', `cl_forma_pagamento_id` = '$forma_pagamento_id_venda',
                `cl_valor_liquido` = '$valor_liquido_venda', `cl_valor_desconto` = '$desconto_venda_real', `cl_observacao` = '$observacao',
                 `cl_vendedor_id` = '$vendedor_id_venda',`cl_endereco_entrega_delivery` = '$endereco_delivery',`cl_opcao_delivery` = '$opcao_delivery',
                  `cl_valor_entrega_delivery` = '$valor_entrega_delivery'  WHERE `tb_nf_saida`.`cl_codigo_nf` ='$codigo_nf' ";
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
         } else { //criar venda
            /*recebimento da venda automaticamente de acordo com a configuracao da forma de pagamento*/
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
                  $valor_liquido_venda,
                  $valor_liquido_venda,
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
            $habilitar_automatizacao_tempo = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "45");
            $qtd_minima_pedidos = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "46");
            $tempo_pouca_demanda_pd = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "47");
            $tempo_alta_demanda_pd = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "48");
            $demanda_atual_pedido = verifica_demanda_pedidos($conecta, $data_lancamento) + 1;
            if ($demanda_atual_pedido > $qtd_minima_pedidos) { //o estabelcimento está com alta demanda de pedidos
               $tempo_entrega = $tempo_alta_demanda_pd;
            } else {
               $tempo_entrega = $tempo_pouca_demanda_pd;
            }

            $insert = "INSERT INTO `tb_nf_saida` ( `cl_data_movimento`, `cl_codigo_nf`,  `cl_parceiro_id`, `cl_parceiro_avulso`, 
         `cl_forma_pagamento_id`, `cl_numero_nf`, `cl_numero_venda`, `cl_serie_nf`, `cl_status_recebimento`, `cl_valor_bruto`, 
         `cl_valor_liquido`, `cl_valor_desconto`,`cl_usuario_id`,`cl_observacao`,`cl_data_recebimento`,`cl_usuario_id_recebimento`,
         `cl_operacao`,`cl_vendedor_id`,`cl_status_venda`,`cl_opcao_delivery`,`cl_valor_entrega_delivery`,`cl_endereco_entrega_delivery`,`cl_data_pedido_delivery`,`cl_tempo_entrega_pedido` ) VALUES
            ( '$data_lancamento','$codigo_nf', '$parceiro_id', '$parceiro_avulso', '$forma_pagamento_id_venda', '$nf_novo', '$nf_novo', '$serie_venda', '$status_recebimento',
            '$valor_total_bruto', '$valor_liquido_venda', '$desconto_venda_real','$id_usuario_logado','$observacao','$data_recebimento',
            '$usuario_id_recebimento','VENDA', '$vendedor_id_venda','1','$opcao_delivery','$valor_entrega_delivery','$endereco_delivery','$data','$tempo_entrega' )"; //STATUS 1 PARA VENDA FINALIZADA
            $operacao_insert = mysqli_query($conecta, $insert); //inserindo os dados basicos da venda
            if ($operacao_insert) {
               $novo_id_inserido = mysqli_insert_id($conecta);
               finalizar_produtos_nf($conecta, $codigo_nf, $serie_venda, $nf_novo, $desconto_venda_real, $data_lancamento, $parceiro_id, $id_usuario_logado, $forma_pagamento_id_venda); //atualizando os produtos da venda com valores corretos

               //atualizar valor em serie de venda
               adicionar_valor_serie($conecta, "12", $nf_novo);
               $mensagem = utf8_decode("Usuário $nome_usuario_logado realizou a venda Nº $nf_novo");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);


               $retornar["dados"] = array("sucesso" => true, "title" => "Venda  Nº $nf_novo finalizada com sucesso ", "pedido_id" => $novo_id_inserido, "recibo" => $abrir_recibo, "acao" => "finalizar_venda");
            } else {
               $retornar["dados"] = array("sucesso" => false, "title" => "Erro ao finalizar a venda Nº $nf_novo, favor comunique o suporte do sistema");
               $mensagem = utf8_decode("Tentativa sem sucesso de finalizar a venda Nº $nf_novo");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
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
      $observacao = utf8_encode($linha['cl_observacao']);
      $id_forma_pagamento_venda = ($linha['cl_forma_pagamento_id']);
      $vendedor_id_venda = ($linha['cl_vendedor_id']);
      $desconto_venda_real = ($linha['cl_valor_desconto']);
      $valor_liquido_venda = ($linha['cl_valor_liquido']);
      $sub_total_venda = ($linha['cl_valor_bruto']);
      $numero_nf = ($linha['cl_numero_nf']);
      $serie_nf = ($linha['cl_serie_nf']);
      $status_venda = ($linha['cl_status_venda']);
      $status_recebimento = ($linha['cl_status_recebimento']);
      $parceiro_avulso = ($linha['cl_parceiro_avulso']);

      $endereco_cliente_delivery = utf8_encode($linha['cl_endereco_entrega_delivery']);
      $opcao_delivery = ($linha['cl_opcao_delivery']);
      $valor_entrega_delivery = ($linha['cl_valor_entrega_delivery']);


      $parceiro_descricao = utf8_encode(consulta_tabela($conecta, "tb_parceiros", "cl_id", $parceiro_id, "cl_razao_social"));
      $descricao_forma_pagamento_venda = utf8_encode(consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $id_forma_pagamento_venda, "cl_descricao"));

      if ($parceiro_avulso != "") { //verificar se a venda tem cliente avulso
         $parceiro_descricao = $parceiro_avulso;
      }

      $informacao = array(
         "data_movimento" => $data_movimento,
         "parceiro_descricao" => $parceiro_descricao,
         "parceiro_id" => $parceiro_id,
         "observacao" => $observacao,
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

         "endereco_delivery" => $endereco_cliente_delivery,
         "opcao_delivery" => $opcao_delivery,
         "valor_entrega_delivery" => $valor_entrega_delivery,

      );

      $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
   }

   if ($acao == "adicionar_observacao_prd_delivery") {
      $id_item_nf = $_POST['id_item_nf'];
      $observacao = utf8_decode($_POST['observacao']);
      if (!update_registro($conecta, "tb_nf_saida_item", "cl_id", $id_item_nf, "", "", "cl_observacao_delivery", $observacao)) {
         $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor comunique o suporte");
      } else {
         $retornar["dados"] = array("sucesso" => true, "title" => "Observação incluida com sucesso");
      }
   }

   if ($acao == "cancelar_nf") {
      $id_nf = $_POST['id_nf'];
      $codigo_nf = $_POST['codigo_nf'];
      $id_user_logado = verifica_sessao_usuario();
      $status_venda = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_status_venda");
      $autorizado_cancelar_venda = consulta_tabela($conecta, "tb_users", "cl_id", $id_user_logado, "cl_cancelar_venda");

      if ($id_nf == "" or $codigo_nf == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel cancelar a venda, venda não encontrada, favor verifique");
      } elseif ($autorizado_cancelar_venda != "SIM") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel cancelar a venda, o seu usuário não tem autorização");
      } elseif ($status_venda == "3") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Não é possível cancelar a venda, pois ela já está cancelada");
      } else {


         if (cancelar_nf($conecta, $id_nf, $codigo_nf, $id_user_logado, $data)) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Venda cancelada com sucesso");
         } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor comunique o suporte");
         }
      }
   }
   if ($acao == "remover_nf_faturamento") {
      $id_nf = $_POST['id_nf'];
      $codigo_nf = $_POST['codigo_nf'];
      $id_user_logado = $_POST['id_user_logado'];
      $status_venda = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_status_venda");


      if ($id_nf == "" or $codigo_nf == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel cancelar a venda, venda não encontrada, favor verifique");
      } elseif ($status_venda == "3") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Não é possível remover a venda do faturamento, pois ela está cancelada");
      } else {
         if (remover_nf_faturamento($conecta, $id_nf, $codigo_nf, $id_user_logado, $data)) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Venda Removida do Faturamento com Sucesso");
         } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor comunique o suporte");
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
      $valor_total = $linha['cl_valor_total'];
      $unidade = utf8_encode($linha['cl_unidade']);
      $item_id = ($linha['cl_item_id']);
      $preco_venda_atual =  validar_prod_venda($conecta, $item_id, "cl_preco_venda"); //preco de venda do produto no cadastro

      $desconto_percente = calcularPorcentagemDesconto($valor_unitario, $preco_venda_atual);
      $informacao = array(
         "descricao" => $descricao,
         "quantidade" => $quantidade,
         "preco_venda" => $valor_unitario,
         "unidade" => $unidade,
         "valor_total" => $valor_total,
         "preco_venda_atual" => $preco_venda_atual,
         "desconto" => $desconto_percente,
         "id_produto" => $item_id,
      );

      $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
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
            $retornar["dados"] = array("sucesso" => true, "title" => "Produto removido com sucesso");
         } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor comunique o suporte");
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

$select = "SELECT * from tb_forma_pagamento where cl_ativo ='S' ";
$consultar_forma_pagamento = mysqli_query($conecta, $select);



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

   $url_qrdcode = "http://effmax.com.br/$empresa/view/venda/venda_mercadoria/recibo/recibo_nf.php?recibo=true&codigo_nf=$codigo_nf&serie_nf=$serie_nf";
   /*dados da venda */
   $select = "SELECT  nf.cl_codigo_nf, nf.cl_observacao,nf.cl_status_venda, fpg.cl_tipo_pagamento_id as tipopg, nf.cl_id as id,nf.cl_data_movimento,nf.cl_numero_nf,nf.cl_serie_nf,nf.cl_status_recebimento,user.cl_nome as vendedor,
   nf.cl_valor_desconto,nf.cl_valor_liquido,prc.cl_razao_social,prc.cl_nome_fantasia,fpg.cl_descricao as formapgt from tb_nf_saida as nf inner join tb_parceiros as prc on prc.cl_id = nf.cl_parceiro_id inner join
    tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id inner join tb_users as user on user.cl_id = nf.cl_vendedor_id
     WHERE nf.cl_codigo_nf ='$codigo_nf' and nf.cl_serie_nf='$serie_nf' ";
   $consultar_nf_saida = mysqli_query($conecta, $select);
   $linha = mysqli_fetch_assoc($consultar_nf_saida);
   $data_movimento_b = ($linha['cl_data_movimento']);
   $numero_nf_b = ($linha['cl_numero_nf']);
   $codigo_nf = ($linha['cl_codigo_nf']);
   $serie_nf_b = ($linha['cl_serie_nf']);
   $status_recebmento_b = ($linha['cl_status_recebimento']);
   $status_recebmento_b_2 = ($linha['cl_status_recebimento']);
   $forma_pagamento_b = utf8_encode($linha['formapgt']);
   $razao_social_b = utf8_encode($linha['cl_razao_social']);
   $nome_fantasia_b = utf8_encode($linha['cl_nome_fantasia']);
   $valor_desconto_b = ($linha['cl_valor_desconto']);
   $valor_liquido_b = ($linha['cl_valor_liquido']);
   $vendedor_b = utf8_encode($linha['vendedor']);
   $tipo_pagamento = ($linha['tipopg']);
   $status_venda = ($linha['cl_status_venda']);
   $observacao = utf8_encode($linha['cl_observacao']);

   $select = "SELECT * from tb_nf_saida_item where cl_codigo_nf = '$codigo_nf' and cl_serie_nf='$serie_nf'";
   $consultar_nf_saida_item = mysqli_query($conecta, $select);
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