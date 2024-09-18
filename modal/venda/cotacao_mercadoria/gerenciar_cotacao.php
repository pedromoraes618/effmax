<?php

if (isset($_GET['cotacao_tela'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";

   $id_nf = isset($_GET['form_id']) ? $_GET['form_id'] : '';
   $codigo_nf = isset($_GET['codigo_nf']) ? $_GET['codigo_nf'] : ''; //gerar um novo codigo para nf;
   $usuario_id = verifica_sessao_usuario();
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
   $select  = "SELECT ct.*, prd.cl_ncm as ncm from tb_cotacao_item as ct 
   left join tb_produtos as prd on prd.cl_id = ct.cl_item_id
   where ct.cl_codigo_nf = '$codigo_nf'";
   $consultar_produtos = mysqli_query($conecta, $select);
}



if (isset($_POST['formulario_cotacao'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";
   $acao = $_POST['acao'];

   $usuario_id = verifica_sessao_usuario();
   $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");

   $validade_padrao = verficar_paramentro($conecta, "tb_parametros", "cl_id", 30); //validade padrão 
   $prazo_entrega_padrao = verficar_paramentro($conecta, "tb_parametros", "cl_id", 29); //verificar no paramentro se pode adicionar o produto sem estoque
   $produto_default_id = consulta_tabela($conecta, "tb_parametros", "cl_id", 127, 'cl_valor'); //produto default

   $serie_nf = consulta_tabela($conecta, "tb_serie", 'cl_id', 19, 'cl_descricao'); //serie para cotação
   $numero_nf = consulta_tabela($conecta, "tb_serie", 'cl_id', 19, 'cl_valor'); //numero de serie para cotação
   $numero_nf_novo = $numero_nf + 1;
   $cliente_avulso_id = verficar_paramentro($conecta, "tb_parametros", "cl_id", "8"); //verificar o id do cliente avulso

   if ($acao == "show") {
      $form_id = isset($_POST['form_id']) ? $_POST['form_id'] : '';
      $select = "SELECT ctc.cl_observacao as observacao, ctc.*,prc.* from tb_cotacao as ctc left join tb_parceiros as prc on prc.cl_id = ctc.cl_parceiro_id where ctc.cl_id = $form_id ";
      $consultar = mysqli_query($conecta, $select);

      $linha = mysqli_fetch_assoc($consultar);
      $data_movimento = ($linha['cl_data_movimento']);
      $data_fechamento = ($linha['cl_data_fechamento']);
      $codigo_nf = ($linha['cl_codigo_nf']);
      $serie_nf = ($linha['cl_serie_nf']);
      $numero_nf = ($linha['cl_numero_nf']);
      $numero_solicitacao = utf8_encode($linha['cl_numero_solicitacao']);
      $parceiro_avulso = utf8_encode($linha['cl_parceiro_avulso']);
      $validade = ($linha['cl_validade']);
      $status_cotacao_id = ($linha['cl_status_cotacao_id']);

      $parceiro_id = ($linha['cl_parceiro_id']);
      $razao_social = utf8_encode($linha['cl_razao_social']);
      $razao_social = !empty($parceiro_avulso) ? $parceiro_avulso : $razao_social; //se não for definido um cliente para a cotação, e foi utilizado o nome avulso, a razão soscial será o nome avulso

      $vendedor_id = utf8_encode($linha['cl_vendedor_id']);
      $valor_bruto = ($linha['cl_valor_bruto']);
      $valor_liquido = ($linha['cl_valor_liquido']);
      $valor_desconto = ($linha['cl_valor_desconto']);
      $observacao = utf8_encode($linha['observacao']);

      $informacao = array(
         "codigo_nf" => $codigo_nf,
         "data_movimento" => $data_movimento,
         "data_fechamento" => $data_fechamento,
         "serie_nf" => $serie_nf,
         "numero_nf" => $numero_nf,
         "numero_solicitacao" => $numero_solicitacao,
         "documento" => $serie_nf . $numero_nf,

         "parceiro_id" => $parceiro_id,
         "parceiro_descricao" => $razao_social,

         "vendedor_id" => $vendedor_id,
         "status_cotacao_id" => $status_cotacao_id,
         "validade" => $validade,

         "valor_bruto" => $valor_bruto,
         "valor_liquido" => $valor_liquido,
         "valor_desconto" => $valor_desconto,
         "observacao" => $observacao,
      );

      $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
   }

   if ($acao == "show_item") { //detalhe do item
      $form_id = isset($_POST['form_id']) ? $_POST['form_id'] : 0;
      $select = "SELECT * FROM tb_cotacao_item WHERE cl_id = $form_id ";
      $consultar = mysqli_query($conecta, $select);
      $linha = mysqli_fetch_assoc($consultar);
      $id = ($linha['cl_id']);
      $item_id = ($linha['cl_item_id']);
      $descricao_produto = utf8_encode($linha['cl_descricao_item']);
      $referencia = utf8_encode($linha['cl_referencia']);
      $quantidade = ($linha['cl_quantidade']);
      $unidade = ($linha['cl_unidade']);
      $valor_unitario = ($linha['cl_valor_unitario']);
      $valor_total = ($linha['cl_valor_total']);
      $prazo_entrega = ($linha['cl_prazo_entrega']);
      $status_item = ($linha['cl_status_item']);


      $informacao = array(
         "id" => $form_id,
         "produto_id" => $item_id,
         "descricao_produto" => $descricao_produto,
         "prazo_entrega" => $prazo_entrega,
         "referencia" => $referencia,
         "quantidade" => $quantidade,
         "unidade" => $unidade,
         "valor_unitario" => $valor_unitario,
         "valor_total" => $valor_total,
         "valor_total" => $valor_total,
         "situacao_produto" => $status_item,
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


   if ($acao == "create") {
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);
      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = utf8_decode($value);
      }

      if (empty($codigo_nf)) {
         $retornar["dados"] = array("sucesso" => false, "title" => "O pedido não foi iniciado, favor, inicie o pedido para continuar com a ação");
         echo json_encode($conecta);
      }

      if ($status_cotacao == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("status"));
      } elseif ($vendedor_id == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("vendedor"));
      } else {
         $valor_produtos = consulta_tabela_query($conecta, "SELECT SUM(cl_valor_total) as total from tb_cotacao_item where cl_codigo_nf ='$codigo_nf'", 'total');
         $valor_liquido = $valor_produtos - $valor_desconto;


         $validade = !empty($validade) ? $validade : $validade_padrao;
         if ($parceiro_id == "") { //se a venda não possuir cliente será colocado o cliente padrão que está setado no parametro
            $parceiro_id = $cliente_avulso_id;
            $parceiro_avulso = $parceiro_descricao;
         } else {
            $parceiro_avulso = "";
         }


         $query = "INSERT INTO `tb_cotacao` (`cl_data_movimento`, `cl_codigo_nf`, `cl_serie_nf`, `cl_numero_nf`, `cl_parceiro_id`,
          `cl_parceiro_avulso`, `cl_vendedor_id`, `cl_status_cotacao_id`, `cl_validade`, `cl_valor_bruto`, 
          `cl_valor_liquido`, `cl_valor_desconto`, `cl_data_fechamento`, `cl_observacao`, `cl_numero_solicitacao`  )
           VALUES ('$data_lancamento', '$codigo_nf', '$serie_nf', '$numero_nf_novo', '$parceiro_id', 
           '$parceiro_avulso', '$vendedor_id', '$status_cotacao', '$validade', '$valor_produtos',
            '$valor_liquido', '$valor_desconto', '$data_fechamento', '$observacao' , '$numero_solicitacao'  )";
         $operacao_insert = mysqli_query($conecta, $query);
         if ($operacao_insert) {
            $form_id = mysqli_insert_id($conecta);
            update_registro($conecta, 'tb_cotacao_item', 'cl_codigo_nf', $codigo_nf, '', '', 'cl_serie_nf', $serie_nf); //atualizar a serie no item
            update_registro($conecta, 'tb_cotacao_item', 'cl_codigo_nf', $codigo_nf, '', '', 'cl_numero_nf', $numero_nf_novo); //atualizar o numero_nf no item

            update_registro($conecta, 'tb_serie', 'cl_id', 19, '', '', 'cl_valor', $numero_nf_novo); //atualizar a serie

            $retornar["dados"] =  array(
               "sucesso" => true,
               "title" => "Cotação $serie_nf$numero_nf_novo adicionado com sucesso",
               "response" => array("documento" => "$serie_nf$numero_nf_novo", "form_id" => $form_id)
            );

            $mensagem = utf8_decode("Adicionou a cotação $serie_nf$numero_nf_novo");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

            // Se tudo ocorreu bem, confirme a transação
            mysqli_commit($conecta);
         } else {
            // Se ocorrer um erro, reverta a transação
            mysqli_rollback($conecta);
            $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
            $mensagem = utf8_decode("Tentativa sem sucesso de adicionar a cotação $serie_nf$numero_nf_novo  ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }



   if ($acao == "update") {
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);
      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = utf8_decode($value);
      }

      if (empty($codigo_nf)) {
         $retornar["dados"] = array("sucesso" => false, "title" => "Pedido não encontrado, favor, veja se o pedido está selecionado corretamente");
         echo json_encode($conecta);
      }

      if ($status_cotacao == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("status"));
      } elseif ($vendedor_id == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("vendedor"));
      } else {
         $valor_produtos = consulta_tabela_query($conecta, "SELECT SUM(cl_valor_total) as total from tb_cotacao_item where cl_codigo_nf ='$codigo_nf'", 'total');
         $valor_liquido = $valor_produtos - $valor_desconto;

         $numero_nf = consulta_tabela($conecta, 'tb_cotacao', 'cl_codigo_nf', $codigo_nf, 'cl_numero_nf');
         $serie_nf = consulta_tabela($conecta, 'tb_cotacao', 'cl_codigo_nf', $codigo_nf, 'cl_serie_nf');

         $validade = !empty($validade) ? $validade : $validade_padrao;
         if ($parceiro_id == "") { //se a venda não possuir cliente será colocado o cliente padrão que está setado no parametro
            $parceiro_id = $cliente_avulso_id;
            $parceiro_avulso = $parceiro_descricao;
         } else {
            $parceiro_avulso = "";
         }

         $query = "UPDATE `tb_cotacao` SET 
                  `cl_parceiro_id` = '$parceiro_id', 
                  `cl_parceiro_avulso` = '$parceiro_avulso', 
                  `cl_vendedor_id` = '$vendedor_id', 
                  `cl_status_cotacao_id` = '$status_cotacao', 
                  `cl_validade` = '$validade', 
                  `cl_valor_bruto` = '$valor_produtos', 
                  `cl_valor_liquido` = '$valor_liquido', 
                  `cl_valor_desconto` = '$valor_desconto', 
                  `cl_data_fechamento` = '$data_fechamento', 
                  `cl_observacao` = '$observacao',
                  `cl_numero_solicitacao` =  '$numero_solicitacao'
               WHERE `cl_id` = '$id'";

         $operacao_update = mysqli_query($conecta, $query);
         if ($operacao_update) {
            $retornar["dados"] =  array(
               "sucesso" => true,
               "title" => "Cotação alterado com sucesso"
            );

            $mensagem = utf8_decode("Alterou a cotação $serie_nf$numero_nf");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

            // Se tudo ocorreu bem, confirme a transação
            mysqli_commit($conecta);
         } else {
            // Se ocorrer um erro, reverta a transação
            mysqli_rollback($conecta);
            $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
            $mensagem = utf8_decode("Tentativa sem sucesso de adicionar a cotação $serie_nf$numero_nf_novo  ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }


   if ($acao == "item") {
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);
      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = utf8_decode($value);
      }

      if (empty($codigo_nf)) {
         $retornar["dados"] = array("sucesso" => false, "title" => "A cotação não foi iniciado, favor, inicie a cotação para continuar com a ação");
         echo json_encode($conecta);
         exit;
      }

      if (verificaVirgula($quantidade)) { //verificar se tem virgula
         $quantidade = formatDecimal($quantidade); // formatar virgula para ponto
      }
      if (verificaVirgula($preco_venda)) { //verificar se tem virgula
         $preco_venda = formatDecimal($preco_venda); // formatar virgula para ponto
      }
      $valor_total_item = $quantidade * $preco_venda; //total do produto

      /*detalhes do pedido de compra já incluido*/
      $serie_nf = consulta_tabela($conecta, 'tb_cotacao', 'cl_codigo_nf', $codigo_nf, 'cl_serie_nf'); //serie que está no pedido de compra
      $numero_nf = consulta_tabela($conecta, 'tb_cotacao', 'cl_codigo_nf', $codigo_nf, 'cl_numero_nf'); //validar se o pedido de compra já está inserido

      if (empty($item_id)) { //inserir o produto
         if (empty($descricao_produto) and empty($produto_id)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("descrição"));
         } elseif (empty($unidade) and empty($produto_id)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("unidade"));
         } elseif (empty($quantidade)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("quantidade"));
         } elseif (empty($preco_venda)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("preço unitário"));
         } else {

            if ($produto_id == "") { //material avulso não tem no estoque
               if ($produto_default_id == "") { //parametro não informado
                  $retornar["dados"] = array("sucesso" => false, "title" => "Para prosseguir, é necessário definir um produto padrão. Por favor, entre em contato com o suporte.");
                  echo json_encode($retornar);
                  exit;
               } else {
                  $valida_produto_default = consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_default_id, "cl_id"); //unidade_id
                  if ($valida_produto_default == "") { //produto não encontrado
                     $retornar["dados"] = array("sucesso" => false, "title" => "Produto padrão não está cadastrado, Por favor, entre em contato com o suporte.");
                     echo json_encode($retornar);
                     exit;
                  }
               }

               $produto_id = $produto_default_id;
               $referencia = "";
               $descricao_produto = ($descricao_produto);
               $unidade = ($unidade);
            } else {
               $unidade_id = consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_und_id"); //unidade_id
               $unidade = (consulta_tabela($conecta, "tb_unidade_medida", "cl_id", $unidade_id, "cl_sigla"));
               $descricao_produto = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_descricao"));
               $referencia = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_referencia"));
            }

            if ($valor_total_item < 1) {
               $retornar["dados"] = array("sucesso" => false, "title" => "Valor total do item deve ser maior que 0, favor, verifique");
               echo json_encode($retornar);
               exit;
            }


            $prazo_entrega = empty($prazo_entrega) ? $prazo_entrega_padrao : $prazo_entrega; //definir o prazo de entrega, se não for informado assumir a entrega padrão

            $query = "INSERT INTO `tb_cotacao_item` (`cl_data_movimento`, 
            `cl_serie_nf`, `cl_numero_nf`, `cl_codigo_nf`, `cl_vendedor_id`, `cl_item_id`,
             `cl_descricao_item`, `cl_referencia`, `cl_quantidade`, `cl_unidade`, `cl_valor_unitario`, `cl_valor_total`, `cl_prazo_entrega`, `cl_status_item`) VALUES 
              ('$data_lancamento', '$serie_nf', '$numero_nf', '$codigo_nf', '$usuario_id', '$produto_id', '$descricao_produto',
               '$referencia', '$quantidade', '$unidade', '$preco_venda',  '$valor_total_item', '$prazo_entrega', '$situacao_produto' ) ";

            $operacao_insert = mysqli_query($conecta, $query);
            if ($operacao_insert) {
               $retornar["dados"] =  array("sucesso" => true, "title" => "Item adicionado com sucesso");

               if (!empty($numero_nf)) { //se já existir o pedido, atualizar os valores



                  $mensagem = utf8_decode("Adicionou o item $descricao_produto na cotação $serie_nf$numero_nf");
                  registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
               }

               // Se tudo ocorreu bem, confirme a transação
               mysqli_commit($conecta);
            } else {
               // Se ocorrer um erro, reverta a transação
               mysqli_rollback($conecta);
               $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
               $mensagem = utf8_decode("Tentativa sem sucesso de adicionar o item $descricao_produto em um uma cotação");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
         }
      } else { //alterar o produto
         if (empty($descricao_produto) and $produto_id == $produto_default_id) { //verificar se o campo está vazio com a condição tambem de verifiar se o produto é um produto padrão
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("descrição"));
         } elseif (empty($unidade) and $produto_id == $produto_default_id) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("unidade"));
         } elseif (empty($quantidade)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("quantidade"));
         } elseif (empty($preco_venda)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("preço unitário"));
         } else {

            if ($produto_id == $produto_default_id) { //material avulso não tem no estoque
               $produto_id = $produto_default_id;
               $referencia = "";
               $descricao_produto = ($descricao_produto);
               $unidade = ($unidade);
            } else {
               $unidade_id = consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_und_id"); //unidade_id
               $unidade = (consulta_tabela($conecta, "tb_unidade_medida", "cl_id", $unidade_id, "cl_sigla"));
               $descricao_produto = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_descricao"));
               $referencia = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_referencia"));
            }


            if ($valor_total_item < 1) {
               $retornar["dados"] = array("sucesso" => false, "title" => "Valor total do item deve ser maior que 0, favor, verifique");
               echo json_encode($retornar);
               exit;
            }
            $query = "UPDATE `tb_cotacao_item` SET 
             `cl_item_id` = '$produto_id', 
             `cl_descricao_item` = '$descricao_produto', 
             `cl_referencia` = '$referencia', 
             `cl_quantidade` = '$quantidade', 
             `cl_unidade` = '$unidade', 
             `cl_valor_unitario` = '$preco_venda', 
             `cl_valor_total` = '$valor_total_item', 
             `cl_prazo_entrega` = '$prazo_entrega',
             `cl_status_item` = '$situacao_produto'
         WHERE `cl_id` = '$item_id'"; // Substitua `id` pela coluna que identifica o registro único
            $operacao_update = mysqli_query($conecta, $query);
            if ($operacao_update) {
               $retornar["dados"] =  array("sucesso" => true, "title" => "Item alterado com sucesso");

               if (!empty($numero_nf)) { //se já existir o pedido, atualizar os valores
                  $mensagem = utf8_decode("Alterou o item $descricao_produto na cotação  $serie_nf$numero_nf");
                  registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
               }

               // Se tudo ocorreu bem, confirme a transação
               mysqli_commit($conecta);
            } else {
               // Se ocorrer um erro, reverta a transação
               mysqli_rollback($conecta);
               $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");

               if (!empty($numero_nf)) { //se já existir o pedido, atualizar os valores
                  $mensagem = utf8_decode("Tentativa sem sucesso de alterar o item $descricao_produto na cotação $serie_nf$numero_nf");
                  registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
               }
            }
         }
      }

      if (!empty($numero_nf)) { //atualizar valores do pedido
         $valor_produtos = consulta_tabela_query($conecta, "SELECT SUM(cl_valor_total) as total from tb_cotacao_item where cl_codigo_nf ='$codigo_nf'", 'total');
         $valor_desconto = consulta_tabela($conecta, 'tb_cotacao', 'cl_codigo_nf', $codigo_nf, 'cl_valor_desconto');
         $valor_liquido = $valor_produtos - $valor_desconto;

         update_registro($conecta, 'tb_cotacao', 'cl_codigo_nf', $codigo_nf, '', '', 'cl_valor_liquido', $valor_liquido);
         update_registro($conecta, 'tb_cotacao', 'cl_codigo_nf', $codigo_nf, '', '', 'cl_valor_bruto', $valor_produtos);
         update_registro($conecta, 'tb_cotacao', 'cl_codigo_nf', $codigo_nf, '', '', 'cl_valor_desconto', $valor_desconto);
      }
   }


   if ($acao == "cancelar_cotacao") {
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);

      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = utf8_decode($value);
         // Remover aspas simples usando str_replace
         ${$name} = str_replace("'", "", ${$name});
      }

      if ($form_id == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Cotação não encontrada, verifique se a cotação foi salvo corretamente");
      } elseif ($check_autorizador == "false") {
         $retornar["dados"] =  array("sucesso" => "autorizar", "title" =>  "Continue com a operação autorizando com a senha");
      } else {

         $usuario_id  = $_POST['usuario_id'] != '' ? $_POST['usuario_id'] : verifica_sessao_usuario();
         $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");

         $numero_nf = consulta_tabela($conecta, 'tb_cotacao', 'cl_id', $form_id, 'cl_numero_nf');
         $serie_nf = consulta_tabela($conecta, 'tb_cotacao', 'cl_id', $form_id, 'cl_serie_nf');
         $update =  update_registro($conecta, 'tb_cotacao', 'cl_id', $form_id, '', '', 'cl_status_ativo', '0');
         if ($update) {
            $mensagem = utf8_decode("Cancelou a cotação $serie_nf$numero_nf");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

            $retornar["dados"] = array("sucesso" => true, "title" => "Cotação $serie_nf$numero_nf cancelada com sucesso");

            // Se tudo ocorreu bem, confirme a transação
            mysqli_commit($conecta);
         } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor, contatar o suporte");
            mysqli_rollback($conecta);

            $mensagem = utf8_decode("Tentativa sem sucesso de cancelar a cotação $serie_nf$numero_nf");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }


   if ($acao == "destroy_item") {
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);

      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = utf8_decode($value);
         // Remover aspas simples usando str_replace
         ${$name} = str_replace("'", "", ${$name});
      }
      $codigo_nf = consulta_tabela($conecta, 'tb_cotacao_item', 'cl_id', $form_id, 'cl_codigo_nf');
      $descricao_item = consulta_tabela($conecta, 'tb_cotacao_item', 'cl_id', $form_id, 'cl_descricao_item');
      $numero_nf = consulta_tabela($conecta, 'tb_cotacao', 'cl_codigo_nf', $codigo_nf, 'cl_numero_nf');
      $serie_nf = consulta_tabela($conecta, 'tb_cotacao', 'cl_codigo_nf', $codigo_nf, 'cl_serie_nf');

      if ($form_id == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Item não encontrado, verifique se o item foi salvo corretamente");
      } else {
         $query = "DELETE FROM tb_cotacao_item where cl_id = $form_id";
         $delete = mysqli_query($conecta, $query);
         if ($delete) {
            if (!empty($numero_nf)) { //verificar se a cotação já foi inserida
               $valor_produtos = consulta_tabela_query($conecta, "SELECT SUM(cl_valor_total) as total from tb_cotacao_item where cl_codigo_nf ='$codigo_nf'", 'total');
               $valor_desconto = consulta_tabela($conecta, 'tb_cotacao', 'cl_codigo_nf', $codigo_nf, 'cl_valor_desconto');
               $valor_liquido = $valor_produtos - $valor_desconto;

               update_registro($conecta, 'tb_cotacao', 'cl_codigo_nf', $codigo_nf, '', '', 'cl_valor_liquido', $valor_liquido);
               update_registro($conecta, 'tb_cotacao', 'cl_codigo_nf', $codigo_nf, '', '', 'cl_valor_bruto', $valor_produtos);
               update_registro($conecta, 'tb_cotacao', 'cl_codigo_nf', $codigo_nf, '', '', 'cl_valor_desconto', $valor_desconto);

               $mensagem = utf8_decode("Removeu o item $descricao_item da cotação $serie_nf$numero_nf");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
            $retornar["dados"] = array("sucesso" => true, "title" => "Item removido com sucesso");
            // Se tudo ocorreu bem, confirme a transação
            mysqli_commit($conecta);
         } else {
            mysqli_rollback($conecta);
            $mensagem = utf8_decode("Tentativa sem sucesso de removeu o item $descricao_item da cotaçãos $serie_nf$numero_nf");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }
   if ($acao == "gerar_venda") {
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);

      $codigo_nf = isset($_POST['codigo_nf']) ? $_POST['codigo_nf'] : '';
      $opcao_item = isset($_POST['opcao_item']) ? $_POST['opcao_item'] : '';
      $serie_nf = consulta_tabela($conecta, "tb_serie", "cl_id", 12, "cl_descricao");
      $numero_nf_novo = consulta_tabela($conecta, 'tb_serie', 'cl_id', 12, "cl_valor") + 1;
      $numero_cotacao = consulta_tabela($conecta, 'tb_cotacao', 'cl_codigo_nf', $codigo_nf, "cl_id");
      $codigo_nf_novo = md5(uniqid(time())); //gerar um novo codigo para nf

      $valida_cotacao = consulta_tabela($conecta, 'tb_cotacao', 'cl_codigo_nf', $codigo_nf, 'cl_id');


      $erro = false;
      $mensagem_erro = ("Erro, favor, contatar o suporte");

      if ($valida_cotacao == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Cotação não encontrada, verifique se a cotação foi salvo corretamente");
      } elseif ($numero_cotacao == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Por favor, adicione itens à cotação para continuar");
      } elseif (empty($opcao_item)) {
         $retornar["dados"] = array("sucesso" => false, "title" => "É necessario escolher uma opção dos itens que você quer gerar para a venda");
      } else {
         if ($opcao_item == 'todos') { //todos os produto
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
             SELECT  '$data_lancamento','$codigo_nf_novo', '$usuario_id', '$serie_nf', 
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
  cl_valor_desconto, '$usuario_id','VENDA',cl_vendedor_id,cl_observacao,'$data' FROM tb_cotacao where cl_codigo_nf = $codigo_nf  ";

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

   if ($acao == "imprimir") { //gerar o pdf

      $codigo_nf = isset($_POST['codigo_nf']) ? $_POST['codigo_nf'] : '';
      $valida_cotacao = consulta_tabela($conecta, 'tb_cotacao', 'cl_codigo_nf', $codigo_nf, 'cl_id');
      // $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : ''; //tipo do documento


      if ($valida_cotacao == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => "Cotação não encontrada, verifique se a cotação foi salvo corretamente");
      } else {
         $caminho =  "view/venda/cotacao_mercadoria/pdf/pdf_cotacao.php?pdf_cotacao=true&codigo_nf=$codigo_nf";
         $retornar["dados"] = array("sucesso" => true, "title" => $caminho);
      }
   }
   echo json_encode($retornar);
}








/*imprimri */
if (isset($_GET['pdf_cotacao'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   include '../../../../biblioteca/phpqrcode/qrlib.php';

   $codigo_nf = isset($_GET['codigo_nf']) ? $_GET['codigo_nf'] : 0;

   $diretorio_logo =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "31"); //VERIFICAR PARAMETRO ID - 1
   $condicao_cotacao = utf8_encode(consulta_tabela($conecta, "tb_parametros", "cl_id", "76", "cl_valor"));

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

   $url_qrdcode = "$url_init/$empresa/view/venda/cotacao_mercadoria/pdf/pdf_cotacao.php?pdf_cotacao=true&codigo_nf=$codigo_nf";
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
