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
if (isset($_GET['consultar_cotacao'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   $consulta = $_GET['consultar_cotacao'];
   $data_inicial = $_GET['data_inicial'];
   $data_final = $_GET['data_final'];

   //formatar data para o banco de dados
   // $data_inicial =  formatarDataParaBancoDeDados($data_inicial);
   // $data_final =  formatarDataParaBancoDeDados($data_final);

   if ($consulta == "inicial") {


      $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1
      $select = "SELECT ctc.*, ctc.cl_id as idcotacao,ctc.cl_codigo_nf,ctc.cl_parceiro_avulso,ctc.cl_data_movimento,
      ctc.cl_parceiro_id,prc.cl_razao_social,ctc.cl_status_cotacao_id,user.cl_usuario as vendedor,stc.cl_descricao as statusc,
      ctc.cl_validade,ctc.cl_valor_bruto,ctc.cl_valor_liquido,ctc.cl_valor_desconto from tb_cotacao as ctc inner join
      tb_status_cotacao as stc on ctc.cl_status_cotacao_id = stc.cl_id 
      left join tb_parceiros as prc on prc.cl_id = ctc.cl_parceiro_id left join tb_users as user on user.cl_id = ctc.cl_vendedor_id
      WHERE ctc.cl_data_movimento between '$data_inicial' and '$data_final' and ctc.cl_status_ativo ='1' order by ctc.cl_id desc";
      $consultar_cotacao_mercadoria = mysqli_query($conecta, $select);
      if (!$consultar_cotacao_mercadoria) {
         die("Falha no banco de dados: " . mysqli_error($conecta));
      } else {
         $qtd = mysqli_num_rows($consultar_cotacao_mercadoria); //quantidade de registros
      }
   } else {
      $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
      $status = $_GET['status_cotacao'];
      $select = "SELECT ctc.*, ctc.cl_id as idcotacao,ctc.cl_codigo_nf,ctc.cl_parceiro_avulso,ctc.cl_data_movimento,
      ctc.cl_parceiro_id,prc.cl_razao_social,ctc.cl_status_cotacao_id,user.cl_usuario as vendedor,stc.cl_descricao as statusc,
      ctc.cl_validade,ctc.cl_valor_bruto,ctc.cl_valor_liquido,ctc.cl_valor_desconto from tb_cotacao as ctc left join
      tb_status_cotacao as stc on ctc.cl_status_cotacao_id = stc.cl_id left join tb_parceiros as prc on prc.cl_id = ctc.cl_parceiro_id left join tb_users as user on user.cl_id = ctc.cl_vendedor_id
      WHERE  (ctc.cl_numero_nf  like '%$pesquisa%' or prc.cl_razao_social  like '%$pesquisa%' or prc.cl_nome_fantasia like '%$pesquisa%' or prc.cl_cnpj_cpf like '%$pesquisa%') 
       and ctc.cl_data_movimento between '$data_inicial' and '$data_final' and ctc.cl_status_ativo ='1' ";
      if ($status != "0") {
         $select .= " and ctc.cl_status_cotacao_id = '$status' ";
      }
      $select .= " order by ctc.cl_id desc ";

      $consultar_cotacao_mercadoria = mysqli_query($conecta, $select);
      if (!$consultar_cotacao_mercadoria) {
         die("Falha no banco de dados " . mysqli_error($conecta));
      } else {
         $qtd = mysqli_num_rows($consultar_cotacao_mercadoria); //quantidade de registros
      }
   }
}

if (isset($_GET['tabela_produto'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   $codigo_nf = $_GET['codigo_nf'];
   $select  = "SELECT ct.*, prd.cl_ncm as ncm from tb_cotacao_item as ct left join
    tb_produtos as prd on prd.cl_id = ct.cl_item_id where ct.cl_codigo_nf = '$codigo_nf'";
   $consultar_produtos = mysqli_query($conecta, $select);

   $select  = "SELECT * from tb_cotacao where cl_codigo_nf = '$codigo_nf'";
   $consultar_cotacao_det = mysqli_query($conecta, $select);
   $linha = mysqli_fetch_assoc($consultar_cotacao_det);
   $desconto_cotacao = $linha['cl_valor_desconto'];
}

if (isset($_POST['cotacao_mercadoria'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";
   $acao = $_POST['acao'];
   $validade_padrao = verficar_paramentro($conecta, "tb_parametros", "cl_id", "30"); //verificar no paramentro se pode adicionar o produto sem estoque
   $prazo_entrega_padrao = verficar_paramentro($conecta, "tb_parametros", "cl_id", "29"); //verificar no paramentro se pode adicionar o produto sem estoque
   $validar_venda_sem_estoque = verficar_paramentro($conecta, "tb_parametros", "cl_id", "9"); //verificar no paramentro se pode adicionar o produto sem estoque
   $desconto_maximo_produto = verficar_paramentro($conecta, "tb_parametros", "cl_id", "10"); //verificar o desconto maimo para o produto na venda
   $serie_nf = consulta_tabela($conecta, "tb_serie", 'cl_id', 19, 'cl_descricao'); //verificar qual seria usado na cotacao
   $numero_nf = consulta_tabela($conecta, "tb_serie", 'cl_id', 19, 'cl_valor'); //verificar qual seria usado na cotacao
   $cliente_avulso_id = verficar_paramentro($conecta, "tb_parametros", "cl_id", "8"); //verificar o id do cliente avulso
   $classficacao_financeiro_id = verficar_paramentro($conecta, "tb_parametros", "cl_id", "11"); //verificar o id do cliente avulso
   $abrir_recibo = verficar_paramentro($conecta, "tb_parametros", "cl_id", "17"); //verificar o id do cliente avulso
   $venda_prd_vlr_zerado = verficar_paramentro($conecta, "tb_parametros", "cl_id", "27"); //verificar o id do cliente avulso
   $numero_nf_novo = $numero_nf + 1;





   if ($acao == "create") {
      /*usuario logado */
      $id_usuario_logado = verifica_sessao_usuario(); //pegar a sessão do usuario
      $nome_usuario_logado = (consulta_tabela($conecta, "tb_users", "cl_id", $id_usuario_logado, "cl_usuario"));



      $check_autorizador = $_POST['check_autorizador'];
      /*dados basicos da cotaccaco*/
      $codigo_nf = $_POST['codigo_nf'];
      $vendedor = $_POST['vendedor'];
      $status_cotacao = $_POST['status_cotacao'];
      $validade = $_POST['validade'];
      $att = utf8_decode($_POST['att']);
      $parceiro_descricao = utf8_decode($_POST['parceiro_descricao']);
      $parceiro_id = $_POST['parceiro_id'];
      $observacao = utf8_decode($_POST['observacao']);

      if ($validade == "") {
         $validade = $validade_padrao;
      }

      if ($parceiro_id == "") { //se a venda não possuir cliente será colocado o cliente padrão que está setado no parametro
         $parceiro_id = $cliente_avulso_id;
         $parceiro_avulso = $parceiro_descricao;
      } else {
         $parceiro_avulso = "";
      }



      /*dados do produto*/
      $produto_id = $_POST['produto_id'];
      $descricao_produto = utf8_decode($_POST['descricao_produto']);
      $quantidade = $_POST['quantidade'];
      $preco_venda = $_POST['preco_venda'];
      $prazo_entrega = $_POST['prazo_entrega'];
      $data_fechamento = $_POST['data_fechamento'];



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


      if ($prazo_entrega == "") {
         $prazo_entrega = $prazo_entrega_padrao;
      }

      if ($produto_id != "") {
         $unidade_prod_id = consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_und_id");
         $descricao_unidade_prod = consulta_tabela($conecta, "tb_unidade_medida", "cl_id", $unidade_prod_id, "cl_sigla");
         //$valor_total = $itens['valor_total'];
         $estoque =  validar_prod_venda($conecta, $produto_id, "cl_estoque"); //estoque disponivel do produto
         $preco_venda_atual =  validar_prod_venda($conecta, $produto_id, "cl_preco_venda"); //preco de venda do produto no cadastro
         $referencia = utf8_decode(validar_prod_venda($conecta, $produto_id, "cl_referencia")); //preco de venda do produto no cadastro
         $preco_venda_promocao =  validar_prod_venda($conecta, $produto_id, "cl_preco_promocao"); //preco de venda do produto no cadastro

         $valor_total = $preco_venda * $quantidade;
         $desconto_real = 0;
         $calula_desconto = 0;
         
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
      }



      if ($codigo_nf == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "É necessario iniciar a cotação para prosseguir");
      } else {
         $nf = consulta_tabela($conecta, "tb_cotacao", "cl_codigo_nf", $codigo_nf, "cl_id"); //verificar se já existe a cotação
         if ($nf == "") { //inserir a cotação
            if ($vendedor == "0") {
               $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("Vendedor"));
            } elseif ($status_cotacao == "0") {
               $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("Status cotação"));
            } elseif ($produto_id == "") {
               $retornar["dados"] =  array("sucesso" => false, "title" => "Favor, selecione o produto");
            } elseif ($descricao_produto == "") {
               $retornar["dados"] =  array("sucesso" => false, "title" => "Favor, informe todas as informações do produto");
            } elseif ($venda_prd_vlr_zerado != "S" and ($preco_venda == 0 or $quantidade == 0)) {
               $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel adicionar o produto, o valor total do produto não pode ser 0");
            } elseif ($data_fechamento == "" and ($status_cotacao == 2 or $status_cotacao == 3)) {
               $retornar["dados"] =  array("sucesso" => false, "title" => "Favor, é necessario informar a data de fechamento da cotação");
            } else {
               // if ($data_fechamento != "") {
               //    $data_fechamento = formatarDataParaBancoDeDados($data_fechamento);
               // }

               $insert = "INSERT INTO `tb_cotacao` (`cl_data_movimento`,`cl_serie_nf`,`cl_numero_nf`, `cl_codigo_nf`, `cl_parceiro_id`,
             `cl_parceiro_avulso`, `cl_vendedor_id`, `cl_status_cotacao_id`, `cl_validade`, `cl_att`, `cl_observacao`, `cl_valor_bruto`, `cl_valor_liquido`,
              `cl_valor_desconto`, `cl_data_fechamento` ) VALUES 
              ( '$data_lancamento', '$serie_nf', '$numero_nf_novo','$codigo_nf', '$parceiro_id', '$parceiro_avulso', '$vendedor', '$status_cotacao', '$validade', '$att','$observacao', '0', '0', '0', '$data_fechamento' )";
               $operacao_insert = mysqli_query($conecta, $insert);
               if ($operacao_insert) {
                  insert_produto_cotacao(
                     $conecta,
                     $data_lancamento,
                     $codigo_nf,
                     $vendedor,
                     $produto_id,
                     $descricao_produto,
                     $referencia,
                     $quantidade,
                     $descricao_unidade_prod,
                     $preco_venda,
                     $desconto_real,
                     $valor_total,
                     $prazo_entrega,
                     1 //aberto
                  );

                  $retornar["dados"] = array("sucesso" => true, "title" => "Produto alterado com sucesso");

                  update_registro($conecta, 'tb_serie', 'cl_id', 19, '', '', 'cl_valor', $numero_nf_novo); //atualizar a serie
                  $id_cotacao = retornar_ultimo_id($conecta, "tb_cotacao");
                  $mensagem = utf8_decode("Usuário $nome_usuario_logado adicionou a $serie_nf $numero_nf_novo ");
                  registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
               }
            }
         } else { //inser no produto
            if ($produto_id == "") {
               $retornar["dados"] =  array("sucesso" => false, "title" => "Favor, selecione o produto");
            } elseif ($descricao_produto == "") {
               $retornar["dados"] =  array("sucesso" => false, "title" => "Favor, informe todas as informações do produto");
            } elseif ($venda_prd_vlr_zerado != "S" and ($preco_venda == 0 or $quantidade == 0)) {
               $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel adicionar o produto, o valor total do produto não pode ser 0");
            } elseif (($desconto_maximo_produto < $calula_desconto and ($desconto_maximo_produto != "") and ($check_autorizador != "true"))) {
               $retornar["dados"] =  array("sucesso" => "autorizar", "title" => "Não é possivel adicionar o produto, o desconto está acima do permitido, continue com a operação autorizando com a senha");
            } else {
               insert_produto_cotacao(
                  $conecta,
                  $data_lancamento,
                  $codigo_nf,
                  $vendedor,
                  $produto_id,
                  $descricao_produto,
                  $referencia,
                  $quantidade,
                  $descricao_unidade_prod,
                  $preco_venda,
                  $desconto_real,
                  $valor_total,
                  $prazo_entrega,
                  1 //aberto
               );
               $retornar["dados"] = array("sucesso" => true, "title" => "Produto alterado com sucesso");
            }
         }
      }
   }

   if ($acao == "alterar_produto") {
      $id_user = $_POST['id_user'];
      $check_autorizador = $_POST['check_autorizador'];

      $itensJSON = $_POST['itens']; //array de produtos
      $itens = json_decode($itensJSON, true); //recuperar valor do array javascript decodificando o json
      $id_item_cotacao = $itens['id_item_nf'];
      $produto_id = $itens['produto_id'];
      $descricao_produto = ($itens['descricao_produto']);
      $preco_venda = $itens['preco_venda'];
      $quantidade = $itens['quantidade'];
      $codigo_nf = consulta_tabela($conecta, "tb_cotacao_item", "cl_id", $id_item_cotacao, "cl_codigo_nf");
      $cotacao_id = consulta_tabela($conecta, "tb_cotacao", "cl_codigo_nf", $codigo_nf, "cl_id");

      $status_produto = $itens['status_produto'];
      $prazo_entrega = $itens['prazo_entrega_produto'];

      $estoque =  validar_prod_venda($conecta, $produto_id, "cl_estoque"); //estoque disponivel do produto
      $preco_venda_atual =  validar_prod_venda($conecta, $produto_id, "cl_preco_venda"); //preco de venda do produto no cadastro
      $referencia = utf8_decode(validar_prod_venda($conecta, $produto_id, "cl_referencia")); //preco de venda do produto no cadastro
      $preco_venda_promocao =  validar_prod_venda($conecta, $produto_id, "cl_preco_promocao"); //preco de venda do produto no cadastro
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
         $retornar["dados"] =  array("sucesso" => false, "title" => "Favor informe todas as informações do produto");
      } elseif ($venda_prd_vlr_zerado != "S" and ($preco_venda == 0 or $quantidade == 0)) {
         $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel adicionar o produto, o valor total do produto não pode ser 0");
      } elseif (($desconto_maximo_produto < $calula_desconto and ($desconto_maximo_produto != "") and ($check_autorizador != "true"))) {
         $retornar["dados"] =  array("sucesso" => "autorizar", "title" => "Não é possivel adicionar o produto, o desconto está acima do permitido, continue com a operação autorizando com a senha");
      } elseif ($status_produto == "0") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("Status produto"));
      } else {
         $update = "UPDATE `tb_cotacao_item` SET `cl_quantidade` = '$quantidade', `cl_valor_unitario` = '$preco_venda',
          `cl_desconto_item` = '$desconto_real', `cl_valor_total` = '$valor_total', `cl_prazo_entrega` = '$prazo_entrega', 
          `cl_status_item` = '$status_produto' WHERE `tb_cotacao_item`.`cl_id` = $id_item_cotacao ";
         $operacao_update = mysqli_query($conecta, $update);
         if ($operacao_update) {
            $retornar["dados"] =  array("sucesso" => true, "title" => "Produto alterado com sucesso");

            atualizar_valor_cotacao($conecta, $codigo_nf, "false");
            $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $id_user, "cl_usuario"); //verificar se já foi incluido a cotação
            $mensagem = utf8_decode("Usuário $nome_usuario_logado alterou o produto de código $produto_id  da cotação $cotacao_id ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         } else {
            $retornar["dados"] =  array("sucesso" => false, "title" => "Não foi possivel alterar o produto, favor, comunique o suporte do sistema");
         }
      }
   }


   if ($acao == "validar_usuario_prduto") {
      $usuario_id = $_POST['usuario_id'];
      $senha = $_POST['senha'];
      $tipo = $_POST['tipo'];

      $itensJSON = $_POST['itens']; //array de produtos
      $itens = json_decode($itensJSON, true); //recuperar valor do array javascript decodificando o json

      $vendedor = $itens['vendedor'];
      $codigo_nf = $itens['codigo_nf'];
      $produto_id = $itens['produto_id'];
      $descricao_produto = utf8_decode($itens['descricao_produto']);
      $preco_venda = $itens['preco_venda'];
      $quantidade = $itens['quantidade'];
      $prazo_entrega = $itens['prazo_entrega'];


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


      if ($produto_id != "") {
         $unidade_prod_id = consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_und_id");
         $descricao_unidade_prod = consulta_tabela($conecta, "tb_unidade_medida", "cl_id", $unidade_prod_id, "cl_sigla");
         $estoque =  validar_prod_venda($conecta, $produto_id, "cl_estoque"); //estoque disponivel do produto
         $preco_venda_atual =  validar_prod_venda($conecta, $produto_id, "cl_preco_venda"); //preco de venda do produto no cadastro
         $referencia = utf8_decode(validar_prod_venda($conecta, $produto_id, "cl_referencia")); //preco de venda do produto no cadastro
         $valor_total = $preco_venda * $quantidade;

         if ($preco_venda != "" and $preco_venda_atual != "") {
            $calula_desconto = (($preco_venda * 100) / $preco_venda_atual);
            $calula_desconto = (100 - $calula_desconto); //desconto em porcentagem
            $desconto_real = $preco_venda_atual - $preco_venda; //desconto em real
         }

         if ($prazo_entrega == "") {
            $prazo_entrega = $prazo_entrega_padrao;
         }
      }

      if ($produto_id == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => "Favor, selecione o produto");
      } elseif ($usuario_id == '0') {
         $retornar["dados"] = array("sucesso" => false, "title" => "Favor, selecione o autorizador"); //alertar o usuario que o caixa está fechado
      } elseif (!validar_usuario($conecta, $usuario_id, $senha)) {
         $retornar["dados"] = array("sucesso" => false, "title" => "Senha incorreta, autorização não permitida"); //alertar o usuario que o caixa está fechado
      } elseif ($descricao_produto == "" or ($venda_prd_vlr_zerado != "S" and $valor_total == "0") or $valor_total == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => "Favor informe todas as informações do produto");
      } else {
         if ($tipo == "INCLUIR") {
            insert_produto_cotacao(
               $conecta,
               $data_lancamento,
               $codigo_nf,
               $vendedor,
               $produto_id,
               $descricao_produto,
               $referencia,
               $quantidade,
               $descricao_unidade_prod,
               $preco_venda,
               $desconto_real,
               $valor_total,
               $prazo_entrega,
               1 //aberto
            );
            $retornar["dados"] = array("sucesso" => true, "title" => "Produto incluido com sucesso");
         }
      }
   }


   if ($acao == "update") {
      $dadosJSON = $_POST['dados']; //array de produtos
      $dados = json_decode($dadosJSON, true); //recuperar valor do array javascript decodificando o json
      $id_user_logado = $dados['id_user_logado'];
      $codigo_nf = $dados['codigo_nf'];
      $data_fechamento = $dados['data_fechamento'];
      $vendedor = $dados['vendedor'];
      $status_cotacao = $dados['status_cotacao'];
      $validade = $dados['validade'];
      $att = utf8_decode($dados['att']);
      $parceiro_descricao = utf8_decode($dados['parceiro_descricao']);
      $parceiro_id = $dados['parceiro_id'];
      $observacao = utf8_decode($dados['observacao']);

      $cotacao_id = consulta_tabela($conecta, "tb_cotacao", "cl_codigo_nf", $codigo_nf, "cl_id"); //verificar se já foi incluido a cotação

      if ($validade == "") {
         $validade = $validade_padrao;
      }

      if ($parceiro_id == "") { //se não possuir cliente será colocado o cliente padrão que está setado no parametro
         $parceiro_id = $cliente_avulso_id;
         $parceiro_avulso = $parceiro_descricao;
      } else {
         $parceiro_avulso = "";
      }


      if ($codigo_nf == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "A Cotação não foi iniciada, Favor, clique no botão Iniciar Cotação");
      } elseif ($cotacao_id == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Favor, para realizar a ação, é necessário adicionar produtos à cotação");
      } elseif ($vendedor == "0") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("Vendedor"));
      } elseif ($status_cotacao == "0") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("Status cotação"));
      } elseif ($data_fechamento == "" and ($status_cotacao == 2 or $status_cotacao == 3)) {
         $retornar["dados"] =  array("sucesso" => false, "title" => "Favor, é necessario informar a data de fechamento da cotação");
      } else {
         // if ($data_fechamento != "") {
         //    $data_fechamento = formatarDataParaBancoDeDados($data_fechamento);
         // }
         $numero_nf = consulta_tabela($conecta, "tb_cotacao", "cl_codigo_nf", $codigo_nf, "cl_numero_nf");
         $serie_nf = consulta_tabela($conecta, "tb_cotacao", "cl_codigo_nf", $codigo_nf, "cl_serie_nf");

         $update = "UPDATE `tb_cotacao` SET `cl_parceiro_id` = '$parceiro_id', `cl_parceiro_avulso` = '$parceiro_descricao',
          `cl_vendedor_id` = '$vendedor', `cl_status_cotacao_id` = '$status_cotacao', `cl_validade` = '$validade', `cl_att` = '$att', 
          `cl_data_fechamento` = '$data_fechamento', `cl_observacao` = '$observacao' WHERE `tb_cotacao`.`cl_codigo_nf` = '$codigo_nf'";
         $operacao_update = mysqli_query($conecta, $update);
         if ($operacao_update) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Cotação alterada com sucesso");


            $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $id_user_logado, "cl_usuario"); //verificar se já foi incluido a cotação
            $mensagem = utf8_decode("Realizou alteração na cotação $serie_nf $numero_nf ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }

   if ($acao == "cancelar_cotacao") {
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);

      $codigo_nf = isset($_POST['codigo_nf']) ? $_POST['codigo_nf'] : '';

      $usuario_id = verifica_sessao_usuario();
      $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");

      $form_id = consulta_tabela($conecta, "tb_cotacao", "cl_codigo_nf", $codigo_nf, "cl_id"); //verificar se já foi incluido a cotação
      if ($form_id == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Cotação não encontrado, verifique se a cotação foi salva corretamente");
      } else {
         $numero_nf = consulta_tabela($conecta, "tb_cotacao", "cl_codigo_nf", $codigo_nf, "cl_numero_nf");
         $serie_nf = consulta_tabela($conecta, "tb_cotacao", "cl_codigo_nf", $codigo_nf, "cl_serie_nf");


         $update = update_registro($conecta, 'tb_cotacao', 'cl_id', $form_id, '', '', 'cl_status_ativo', '0');
         if ($update) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Cotação $serie_nf$numero_nf cancelada com sucesso");
            $mensagem = utf8_decode("Usuário $nome_usuario_logado cancelou a cotação $form_id ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

            mysqli_commit($conecta);
         } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor, contatar o suporte");
            $mensagem = utf8_decode("Tentativa sem sucesso de cancelar a cotação $serie_nf$numero_nf ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }

   if ($acao == "imprimir") {

      $codigo_nf = $_POST['codigo_nf'];
      $numero_cotacao = consulta_tabela($conecta, 'tb_cotacao', 'cl_codigo_nf', $codigo_nf, "cl_id");
      if ($codigo_nf == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "A Cotação não foi iniciada, Favor, clique no botão Iniciar Cotação");
      } elseif ($numero_cotacao == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Por favor, adicione itens à cotação para continuar");
      } else {
         $caminho =  "view/venda/cotacao_mercadoria/pdf/pdf_cotacao.php?pdf_cotacao=true&codigo_nf=$codigo_nf";
         $retornar["dados"] = array("sucesso" => true, "title" => $caminho);
      }
   }


   if ($acao == "adicionar_desconto") {
      $desconto = $_POST['desconto'];
      $codigo_nf = $_POST['codigo_nf'];
      $check_autorizador = $_POST['check_autorizador'];
      $valor_bruto = consulta_tabela($conecta, "tb_cotacao", "cl_codigo_nf", $codigo_nf, "cl_valor_bruto");

      if ($desconto != "") {
         if (verificaVirgula($desconto)) { //verificar se tem virgula
            $desconto = formatDecimal($desconto); // formatar virgula para ponto
         }
      }

      if ($codigo_nf == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "A Cotação não foi iniciada, Favor, clique no botão Iniciar Cotação");
      } elseif ($valor_bruto == 0) {
         $retornar["dados"] = array("sucesso" => false, "title" => "Favor, para realizar a ação, é necessário adicionar produtos à cotação");
      } elseif ((descontoAcimaPermitido($valor_bruto, $desconto, $desconto_maximo_produto) == true) and ($check_autorizador != "true") and $desconto != "") {
         $retornar["dados"] =  array("sucesso" => "autorizar", "title" => "O desconto está acima do permitido, continue com a operação autorizando com a senha");
      } else {

         if (atualizar_valor_cotacao($conecta, $codigo_nf, $desconto)) { //atualizar os valores da cotação
            $retornar["dados"] = array("sucesso" => true, "title" => "Desconto alterado com sucesso");
         }
      }
   }

   if ($acao == "gerar_venda") {
      $codigo_nf = $_POST['codigo_nf'];
      $opcao_item = $_POST['opcao_item'];
      $serie_nf = consulta_tabela($conecta, "tb_serie", "cl_id", 12, "cl_descricao");
      $numero_nf_novo = consulta_tabela($conecta, 'tb_serie', 'cl_id', 12, "cl_valor") + 1;
      $numero_cotacao = consulta_tabela($conecta, 'tb_cotacao', 'cl_codigo_nf', $codigo_nf, "cl_id");
      $codigo_nf_novo = md5(uniqid(time())); //gerar um novo codigo para nf

      /*usuario logado */
      $id_usuario_logado = verifica_sessao_usuario(); //pegar a sessão do usuario
      $nome_usuario_logado = (consulta_tabela($conecta, "tb_users", "cl_id", $id_usuario_logado, "cl_usuario"));
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);
      $erro = false;
      $mensagem_erro = ("Erro, favor, contatar o suporte");

      if ($codigo_nf == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "A Cotação não foi iniciada, Favor, clique no botão Iniciar Cotação");
      } elseif ($numero_cotacao == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Por favor, adicione itens à cotação para continuar");
      } else {
         if ($opcao_item == 'todos' or $opcao_item == "") { //todos os produto
            $resultados_itens = consulta_linhas_tb_filtro($conecta, 'tb_cotacao_item', "cl_codigo_nf", $codigo_nf);
         } else { //ganhos
            $filtro = "cl_codigo_nf = '$codigo_nf' and cl_status_item = '2' ";
            $resultados_itens = consulta_linhas_tb_2_filtro($conecta, 'tb_cotacao_item', "cl_codigo_nf", $codigo_nf, "cl_status_item", "2");
         }


         $valida_itens_ganhos = consulta_tabela_2_filtro($conecta, "tb_cotacao_item", 'cl_codigo_nf', $codigo_nf, "cl_status_item", '2', 'cl_id'); //verificar se tem alguma cotação ganha
         if ($valida_itens_ganhos == "" and $opcao_item == 'ganhos') { //gerar apenas com itens ganhos sem nenum item com o status ganho será barrado
            $retornar["dados"] = array("sucesso" => false, "title" => "Favor, altere o status do item para ganho para prosseguir");
         } else {
            $valor_total_prd_cotacao = 0;
            /*inserir dados dos itens */
            foreach ($resultados_itens as $linha) {
               $item_id_cotacao = $linha['cl_id'];
               $item_id_produto = $linha['cl_item_id'];
               $quantidade_item = $linha['cl_quantidade'];
               $valor_unitario_item = $linha['cl_valor_unitario'];
               $descricao_item = utf8_encode($linha['cl_descricao_item']);

               $valor_total_prd_cotacao = ($quantidade_item * $valor_unitario_item) + $valor_total_prd_cotacao;
               $estoque = consulta_tabela($conecta, "tb_produtos", "cl_id", $item_id_produto, "cl_estoque");

               if ($estoque <= 0) {
                  $mensagem_erro = ("O produto $descricao_item possui estoque insuficiente. Estoque atual: $estoque unidades. Quantidade desejada: $quantidade_item unidades.
                   A venda não pode ser gerada.");
                  $erro = true;
                  break;  // Isso interrompe o loop
               }

               if ($erro == false) {
                  $insert = "INSERT INTO `tb_nf_saida_item` ( `cl_data_movimento`, `cl_codigo_nf`, `cl_usuario_id`, `cl_serie_nf`, 
         `cl_numero_nf`, `cl_item_id`, `cl_descricao_item`, `cl_quantidade`, `cl_unidade`, `cl_valor_unitario`, `cl_valor_total`,
          `cl_desconto`, `cl_referencia` ) 
             SELECT  '$data_lancamento','$codigo_nf_novo', '$id_usuario_logado', '$serie_nf', 
         '$numero_nf_novo', cl_item_id, cl_descricao_item, cl_quantidade, cl_unidade, cl_valor_unitario, cl_valor_total,
         cl_desconto_item, cl_referencia FROM tb_cotacao_item where cl_id =$item_id_cotacao ";
                  $operacao_insert = mysqli_query($conecta, $insert);
                  if (!$operacao_insert) {
                     $erro = true;
                     break;
                  }
               }
            }

            if ($erro == false) {
               /*inserir dados basicos */
               $insert = "INSERT INTO `tb_nf_saida` ( `cl_data_movimento`, `cl_codigo_nf`, 
  `cl_parceiro_id`, `cl_parceiro_avulso`, `cl_forma_pagamento_id`, `cl_numero_nf`, `cl_numero_venda`, `cl_serie_nf`, `cl_status_recebimento`,
   `cl_status_venda`, `cl_valor_bruto`, `cl_valor_liquido`, `cl_valor_desconto`,`cl_usuario_id`, `cl_operacao`, `cl_vendedor_id`,`cl_observacao`,`cl_data_pedido_delivery` )

      SELECT  '$data_lancamento','$codigo_nf_novo', 
  cl_parceiro_id, cl_parceiro_avulso, '1', '$numero_nf_novo', '$numero_nf_novo', '$serie_nf', '1', '2' , '$valor_total_prd_cotacao', '$valor_total_prd_cotacao'-cl_valor_desconto, 
  cl_valor_desconto, '$id_usuario_logado','VENDA',cl_vendedor_id,cl_observacao,'$data' FROM tb_cotacao where cl_codigo_nf = $codigo_nf  ";

               $operacao_insert = mysqli_query($conecta, $insert);
               if (!$operacao_insert) {
                  $erro = true;
               }
            }

            if ($erro == true) {
               // Se ocorrer um erro, reverta a transação
               mysqli_rollback($conecta);
               $retornar["dados"] =  array("sucesso" => false, "title" => $mensagem_erro);
               $mensagem = utf8_decode("Tentativa sem sucesso de gerar uma venda da cotação $numero_cotacao");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            } else {
               update_registro($conecta, "tb_serie", "cl_id", 12, "", "", "cl_valor", $numero_nf_novo); //atualizar a serie de venda

               $retornar["dados"] =  array("sucesso" => true, "title" => "$serie_nf $numero_nf_novo gerada com sucesso, favor, finalize a $serie_nf ");
               $mensagem = utf8_decode("Gerou a $serie_nf $numero_nf_novo através da cotação $numero_cotacao ");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
               mysqli_commit($conecta);
            }
         }
      }
   }



   if ($acao == "show") { //dados da nf
      $cotacao_id = $_POST['cotacao_id'];
      $codigo_nf = $_POST['codigo_nf'];
      $select = "SELECT * from tb_cotacao where cl_id=$cotacao_id and cl_codigo_nf = '$codigo_nf'";
      $consultar_cotacao = mysqli_query($conecta, $select);
      $linha = mysqli_fetch_assoc($consultar_cotacao);

      $data_movimento = formatDateB($linha['cl_data_movimento']);
      $data_fechamento = ($linha['cl_data_fechamento']);
      $vendedor_id = ($linha['cl_vendedor_id']);
      $status_cotacao_id = ($linha['cl_status_cotacao_id']);
      $validade = ($linha['cl_validade']);
      $att = utf8_encode($linha['cl_att']);
      $desconto = ($linha['cl_valor_desconto']);
      $observacao = utf8_encode($linha['cl_observacao']);

      $parceiro_id = ($linha['cl_parceiro_id']);
      $parceiro_descricao = utf8_encode(consulta_tabela($conecta, "tb_parceiros", "cl_id", $parceiro_id, "cl_razao_social"));

      $informacao = array(
         "data_movimento" => $data_movimento,
         "vendedor_id" => $vendedor_id,
         "status_cotacao_id" => $status_cotacao_id,
         "validade" => $validade,
         "att" => $att,
         "desconto" => $desconto,
         "observacao" => $observacao,
         "parceiro_id" => $parceiro_id,
         "parceiro_descricao" => $parceiro_descricao,
         "data_fechamento" => $data_fechamento,
      );

      $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
   }

   if ($acao == "show_valores") { //resumo de valores
      $codigo_nf = isset($_POST['codigo_nf']) ? $_POST['codigo_nf'] : 0;
      $valor_produtos = consulta_tabela_query($conecta, "SELECT sum(cl_valor_total) as total FROM tb_cotacao_item WHERE cl_codigo_nf='$codigo_nf' ", 'total');
      $valor_desconto = consulta_tabela($conecta, 'tb_cotacao', 'cl_codigo_nf', $codigo_nf, 'cl_valor_desconto');
      $valor_liquido = $valor_produtos - $valor_desconto;

      $informacao = array(
         "valor_produtos" => $valor_produtos,
         "valor_desconto" => $valor_desconto,
         "valor_liquido" => $valor_liquido,
      );

      $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
   }


   if ($acao == "show_det_produto") {
      $item_cotacao_id = $_POST['item_cotacao_id'];
      $select = "SELECT * from tb_cotacao_item where cl_id = $item_cotacao_id";
      $consultar_item_cotacao = mysqli_query($conecta, $select);
      $linha = mysqli_fetch_assoc($consultar_item_cotacao);
      $produto_id = ($linha['cl_item_id']);
      $descricao = utf8_encode($linha['cl_descricao_item']);
      $quantidade = $linha['cl_quantidade'];
      $unidade = utf8_encode($linha['cl_unidade']);
      $valor_unitario = ($linha['cl_valor_unitario']);
      $valor_total = ($linha['cl_valor_total']);

      $status_item = ($linha['cl_status_item']);
      $prazo_entrega = ($linha['cl_prazo_entrega']);

      $preco_venda_atual =  validar_prod_venda($conecta, $produto_id, "cl_preco_venda"); //preco de venda do produto no cadastro
      $desconto_percente = calcularPorcentagemDesconto($valor_unitario, $preco_venda_atual);


      $informacao = array(
         "descricao" => $descricao,
         "quantidade" => $quantidade,
         "unidade" => $unidade,
         "valor_unitario" => $valor_unitario,
         "quantidade" => $quantidade,
         "valor_total" => $valor_total,
         "desconto_percente" => $desconto_percente,
         "preco_venda_atual" => $preco_venda_atual,
         "produto_id" => $produto_id,
         "status_item" => $status_item,
         "produto_id" => $produto_id,

         "status_item" => $status_item,
         "prazo_entrega" => $prazo_entrega,
      );

      $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
   }


   if ($acao == "delete_item") {
      $id_item_nf = $_POST['id_item_cotacao'];
      $produto_id = $_POST['id_produto'];
      $codigo_nf = $_POST['codigo_nf'];
      $id_user_logado = $_POST['id_user_logado'];
      $cotacao_id = consulta_tabela($conecta, "tb_cotacao", "cl_codigo_nf", $codigo_nf, "cl_id"); //verificar se já foi incluido a cotação

      if ($id_item_nf == "" or $codigo_nf == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel remover o produto, produto não encontrado, favor verifique");
      } else {
         $delete = "DELETE FROM `tb_cotacao_item` WHERE `tb_cotacao_item`.`cl_id` = $id_item_nf";
         $operacao_delete = mysqli_query($conecta, $delete);
         if ($operacao_delete) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Produto removido com sucesso");
            atualizar_valor_cotacao($conecta, $codigo_nf, "false");
            $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $id_user_logado, "cl_usuario"); //verificar se já foi incluido a cotação
            $mensagem = utf8_decode("Usuário $nome_usuario_logado removeu o produto de código $produto_id  da cotação $cotacao_id ");
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
//          $descricao = $linha['cl_descricao'];
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
//          $descricao = $linha['cl_descricao'];
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



// //consultar vendedor
// $select = "SELECT * from tb_users where cl_vendedor ='SIM' ";
// $consultar_vendedor = mysqli_query($conecta, $select);

// //consultar status cotacao
// $select = "SELECT * from tb_status_cotacao";
// $consultar_status_cotacao = mysqli_query($conecta, $select);





if (isset($_GET['pdf_cotacao'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   include '../../../../biblioteca/phpqrcode/qrlib.php';

   $diretorio_logo =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "31"); //VERIFICAR PARAMETRO ID - 1
   $condicao_cotacao = utf8_encode(consulta_tabela($conecta, "tb_parametros", "cl_id", "76", "cl_valor"));

   $codigo_nf = $_GET['codigo_nf'];

   $tamanho = 2;
   $margem = 3;


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
   $email_empresa = ($linha['cl_email']);

   $url_qrdcode = "http://effmax.com.br/$empresa/view/venda/cotacao_mercadoria/pdf/pdf_cotacao.php?pdf_cotacao=true&codigo_nf=$codigo_nf";
   // Renderize o QR Code em um buffer de saída
   ob_start();
   QRcode::png($url_qrdcode, null, QR_ECLEVEL_L, $tamanho, $margem);
   $imageData = ob_get_contents();
   ob_end_clean();


   $select = "SELECT ctc.*, ctc.cl_id as idcotacao,ctc.cl_observacao,ctc.cl_codigo_nf,ctc.cl_parceiro_avulso,ctc.cl_data_movimento,
   ctc.cl_parceiro_id,prc.cl_razao_social,ctc.cl_status_cotacao_id,user.cl_usuario as vendedor,stc.cl_descricao as statusc,
   ctc.cl_validade,ctc.cl_valor_bruto,ctc.cl_valor_liquido,ctc.cl_valor_desconto from tb_cotacao as ctc inner join
   tb_status_cotacao as stc on ctc.cl_status_cotacao_id = stc.cl_id 
   left join tb_parceiros as prc on prc.cl_id = ctc.cl_parceiro_id 
   left join tb_users as user on user.cl_id = ctc.cl_vendedor_id
   WHERE ctc.cl_codigo_nf='$codigo_nf'";
   $consultar_cotacao_mercadoria = mysqli_query($conecta, $select);
   $linha = mysqli_fetch_assoc($consultar_cotacao_mercadoria);
   $data_movimento = $linha['cl_data_movimento'];
   $cotacao_id = $linha['idcotacao'];
   $numero_nf = $linha['cl_numero_nf'];
   $serie_nf = $linha['cl_serie_nf'];
   $parceiro_avulso = utf8_encode($linha['cl_parceiro_avulso']);
   $razao_social = utf8_encode($linha['cl_razao_social']);
   $vendedor = utf8_encode($linha['vendedor']);
   $observacao = utf8_encode($linha['cl_observacao']);

   $valor_bruto = ($linha['cl_valor_bruto']);
   $valor_liquido = ($linha['cl_valor_liquido']);
   $valor_desconto = ($linha['cl_valor_desconto']);

   $validade = ($linha['cl_validade']);
   if ($validade > 0) {
      $validade = $validade . " dias";
   }

   if ($parceiro_avulso != "") {
      $cliente = $parceiro_avulso;
   } else {
      $cliente = $razao_social;
   }

   $select  = "SELECT ct.*, prd.cl_ncm  from tb_cotacao_item as ct left join
   tb_produtos as prd on prd.cl_id = ct.cl_item_id where ct.cl_codigo_nf = '$codigo_nf'";
   $consultar_produtos = mysqli_query($conecta, $select);
}
