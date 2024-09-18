<?php

if (isset($_GET['pedido_tela'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";

    $id_nf = isset($_GET['form_id']) ? $_GET['form_id'] : '';
    $codigo_nf = isset($_GET['codigo_nf']) ? $_GET['codigo_nf'] : ''; //gerar um novo codigo para nf;
    $usuario_id = verifica_sessao_usuario();
}

if (isset($_GET['gerenciar_pedido'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
}


if (isset($_GET['tabela_produto'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $codigo_nf = isset($_GET['codigo_nf']) ? $_GET['codigo_nf'] : '';
    $query  = "SELECT pdci.cl_unidade as unidade,pdci.cl_referencia as referencia, pdci.*, prd.*,pdci.cl_id as itemid,prd.cl_id as produtoid from tb_pedido_compra_item as pdci
     left join tb_produtos as prd on prd.cl_id = pdci.cl_item_id where pdci.cl_codigo_nf = '$codigo_nf' ";
    $consultar_produtos = mysqli_query($conecta, $query);

    $valor_desconto = consulta_tabela($conecta, 'tb_pedido_compra', 'cl_codigo_nf', $codigo_nf, 'cl_valor_desconto');
    $valor_liquido = consulta_tabela($conecta, 'tb_pedido_compra', 'cl_codigo_nf', $codigo_nf, 'cl_valor_liquido');
}


//consultar informações para tabela
if (isset($_GET['consultar_pedido'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $consulta = $_GET['consultar_pedido'];
    $data_inicial = isset($_GET['data_inicial']) ? $_GET['data_inicial'] : $data_inicial_mes_bd;
    $data_final = $_GET['data_final'] ? $_GET['data_final'] : $data_final_mes_bd;

    if ($consulta == "inicial") {
        $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1
        $select = "SELECT pdc.cl_id as idpedido,prc.*, pdc.*, stpdc.cl_descricao as statuspedido, fpg.cl_descricao as formapgt from tb_pedido_compra as pdc
        left join tb_parceiros as prc on prc.cl_id = pdc.cl_parceiro_id
        left join tb_status_pedido_compra as stpdc on stpdc.cl_id = pdc.cl_status_id
        left join tb_forma_pagamento as fpg on fpg.cl_id = pdc.cl_forma_pagamento_id
        where pdc.cl_data_movimento between '$data_inicial' and '$data_final' and pdc.cl_status_ativo ='1' order by pdc.cl_id desc  ";
        $consultar_pedido_compra = mysqli_query($conecta, $select);
        if (!$consultar_pedido_compra) {
            die("Falha no banco de dados: " . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar_pedido_compra); //quantidade de registros
        }
    } else {
        $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
        $status = $_GET['status_pedido'];
        $select = "SELECT pdc.cl_id as idpedido,prc.*, pdc.*, stpdc.cl_descricao as statuspedido, fpg.cl_descricao as formapgt from tb_pedido_compra as pdc
        left join tb_parceiros as prc on prc.cl_id = pdc.cl_parceiro_id
        left join tb_status_pedido_compra as stpdc on stpdc.cl_id = pdc.cl_status_id
        left join tb_forma_pagamento as fpg on fpg.cl_id = pdc.cl_forma_pagamento_id
        WHERE  (pdc.cl_numero_nf  like '%$pesquisa%' or prc.cl_razao_social  like '%$pesquisa%' or prc.cl_nome_fantasia like '%$pesquisa%' or prc.cl_cnpj_cpf like '%$pesquisa%') 
        and pdc.cl_data_movimento between '$data_inicial' and '$data_final' and pdc.cl_status_ativo ='1'  ";
        if ($status != "0") {
            $select .= " and pdc.cl_status_id = '$status' ";
        }
        $select .= " order by pdc.cl_id desc ";
        $consultar_pedido_compra = mysqli_query($conecta, $select);
        if (!$consultar_pedido_compra) {
            die("Falha no banco de dados " . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar_pedido_compra); //quantidade de registros
        }
    }
}


if (isset($_POST['formulario_pedido_compra'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $acao = $_POST['acao'];

    $usuario_id = verifica_sessao_usuario();
    $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");

    $prazo_entrega_padrao = verficar_paramentro($conecta, "tb_parametros", "cl_id", 29); //verificar no paramentro se pode adicionar o produto sem estoque
    $produto_default_id = consulta_tabela($conecta, "tb_parametros", "cl_id", 127, 'cl_valor'); //produto default

    $serie_nf = consulta_tabela($conecta, "tb_serie", 'cl_id', 20, 'cl_descricao');
    $numero_nf = consulta_tabela($conecta, "tb_serie", 'cl_id', 20, 'cl_valor');
    $numero_nf_novo = $numero_nf + 1;
    $cliente_avulso_id = verficar_paramentro($conecta, "tb_parametros", "cl_id", "8"); //verificar o id do cliente avulso

    if ($acao == "show") {
        $form_id = isset($_POST['form_id']) ? $_POST['form_id'] : '';
        $select = "SELECT  pdc.cl_observacao as observacao, pdc.*,prc.cl_id as parceiroid, prc.cl_razao_social as parceirorazao,
         transp.cl_id as transportadoraid, transp.cl_razao_social as transportadorarazao 
         from tb_pedido_compra as pdc
         left join tb_parceiros as prc on prc.cl_id = pdc.cl_parceiro_id
         left join tb_parceiros as transp on transp.cl_id = pdc.cl_transportadora_id
         WHERE pdc.cl_id = $form_id ";
        $consultar = mysqli_query($conecta, $select);

        $linha = mysqli_fetch_assoc($consultar);
        $data_movimento = ($linha['cl_data_movimento']);
        $data_aprovacao = ($linha['cl_data_aprovacao']);
        $codigo_nf = ($linha['cl_codigo_nf']);
        $serie_nf = ($linha['cl_serie_nf']);
        $numero_nf = ($linha['cl_numero_nf']);
        $numero_solicitacao = ($linha['cl_numero_solicitacao']);

        $parceiro_id = ($linha['parceiroid']);
        $parceiro_descricao = utf8_encode($linha['parceirorazao']);

        $transportadora_id = ($linha['transportadoraid']);
        $transportadora_descricao = utf8_encode($linha['transportadorarazao']);

        $forma_pagamento_id = ($linha['cl_forma_pagamento_id']);
        $usuario_id = ($linha['cl_usuario_id']);
        $status_id = ($linha['cl_status_id']);
        $frete_id = ($linha['cl_frete_id']);
        $operacao = ($linha['cl_operacao']);
        $valor_bruto = ($linha['cl_valor_bruto']);
        $valor_liquido = ($linha['cl_valor_liquido']);
        $valor_desconto = ($linha['cl_valor_desconto']);
        $observacao = utf8_encode($linha['observacao']);

        $informacao = array(
            "data_movimento" => $data_movimento,
            "data_aprovacao" => $data_aprovacao,
            "codigo_nf" => $codigo_nf,
            "serie_nf" => $serie_nf,
            "numero_nf" => $numero_nf,
            "numero_solicitacao" => $numero_solicitacao,
            "documento" => $serie_nf . $numero_nf,
            "parceiro_id" => $parceiro_id,
            "parceiro_descricao" => $parceiro_descricao,
            "transportadora_id" => $transportadora_id,
            "transportadora_descricao" => $transportadora_descricao,

            "forma_pagamento_id" => $forma_pagamento_id,
            "usuario_id" => $usuario_id,
            "status_id" => $status_id,
            "frete_id" => $frete_id,
            "operacao" => $operacao,
            "valor_bruto" => $valor_bruto,
            "valor_liquido" => $valor_liquido,
            "valor_desconto" => $valor_desconto,
            "observacao" => $observacao,
        );

        $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
    }

    if ($acao == "show_item") { //detalhe do item
        $form_id = isset($_POST['form_id']) ? $_POST['form_id'] : 0;
        $select = "SELECT * FROM tb_pedido_compra_item WHERE cl_id = $form_id ";
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
        );

        $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
    }

    if ($acao == "show_valores") { //resumo de valores
        $codigo_nf = isset($_POST['codigo_nf']) ? $_POST['codigo_nf'] : 0;
        $valor_produtos = consulta_tabela_query($conecta, "SELECT sum(cl_valor_total) as total FROM tb_pedido_compra_item WHERE cl_codigo_nf='$codigo_nf' ", 'total');
        $valor_desconto = consulta_tabela($conecta, 'tb_pedido_compra', 'cl_codigo_nf', $codigo_nf, 'cl_valor_desconto');
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

        if ($status == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("status"));
        } elseif ($usuario_id == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("usuário"));
        } elseif ($parceiro_id == "") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("cliente/fornecedor"));
        } elseif ($operacao == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("operação"));
        } elseif ($forma_pagamento_id == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("forma de pagamento"));
        } else {
            $valor_produtos = consulta_tabela_query($conecta, "SELECT SUM(cl_valor_total) as total from tb_pedido_compra_item where cl_codigo_nf ='$codigo_nf'", 'total');
            $valor_liquido = $valor_produtos - $valor_desconto;

            $query = "INSERT INTO `tb_pedido_compra` (`cl_data_movimento`, `cl_data_aprovacao`, `cl_codigo_nf`, `cl_serie_nf`,
             `cl_numero_nf`, `cl_numero_solicitacao`, `cl_parceiro_id`, `cl_transportadora_id`, 
             `cl_forma_pagamento_id`, `cl_usuario_id`,
              `cl_status_id`, `cl_frete_id`, `cl_operacao`, `cl_valor_bruto`, `cl_valor_liquido`, `cl_valor_desconto`, `cl_observacao` )
               VALUES ( '$data_lancamento', '$data_aprovacao', '$codigo_nf', '$serie_nf', '$numero_nf_novo', '$solicitacao', '$parceiro_id', '$transportadora_id',
                '$forma_pagamento_id', '$usuario_id', '$status', '$frete', '$operacao', '$valor_produtos', '$valor_liquido', '$valor_desconto', '$observacao' ) ";
            $operacao_insert = mysqli_query($conecta, $query);
            if ($operacao_insert) {
                $form_id = mysqli_insert_id($conecta);
                update_registro($conecta, 'tb_pedido_compra_item', 'cl_codigo_nf', $codigo_nf, '', '', 'cl_serie_nf', $serie_nf); //atualizar a serie no item
                update_registro($conecta, 'tb_pedido_compra_item', 'cl_codigo_nf', $codigo_nf, '', '', 'cl_numero_nf', $numero_nf_novo); //atualizar o numero_nf no item

                update_registro($conecta, 'tb_serie', 'cl_id', 20, '', '', 'cl_valor', $numero_nf_novo); //atualizar a serie

                $retornar["dados"] =  array(
                    "sucesso" => true,
                    "title" => "Pedido de compra $serie_nf$numero_nf_novo adicionado com sucesso",
                    "response" => array("documento" => "$serie_nf$numero_nf_novo", "form_id" => $form_id)
                );

                $mensagem = utf8_decode("Adicionou o pedido de compra $serie_nf$numero_nf_novo");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de adicionar o pedido de compra $serie_nf$numero_nf_novo  ");
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

        if ($status == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("status"));
        } elseif ($usuario_id == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("usuário"));
        } elseif ($parceiro_id == "") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("cliente/fornecedor"));
        } elseif ($operacao == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("operação"));
        } elseif ($forma_pagamento_id == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("forma de pagamento"));
        } else {
            $valor_produtos = consulta_tabela_query($conecta, "SELECT SUM(cl_valor_total) as total from tb_pedido_compra_item where cl_codigo_nf ='$codigo_nf'", 'total');
            $valor_liquido = $valor_produtos - $valor_desconto;

            $numero_nf = consulta_tabela($conecta, 'tb_pedido_compra', 'cl_codigo_nf', $codigo_nf, 'cl_numero_nf');
            $serie_nf = consulta_tabela($conecta, 'tb_pedido_compra', 'cl_codigo_nf', $codigo_nf, 'cl_serie_nf');

            $query = "UPDATE `tb_pedido_compra`
            SET 
              `cl_data_aprovacao` = '$data_aprovacao',
              `cl_numero_solicitacao` = '$solicitacao',
              `cl_parceiro_id` = '$parceiro_id',
              `cl_transportadora_id` = '$transportadora_id',
              `cl_forma_pagamento_id` = '$forma_pagamento_id',
              `cl_usuario_id` = '$usuario_id',
              `cl_status_id` = '$status',
              `cl_frete_id` = '$frete',
              `cl_operacao` = '$operacao',
              `cl_valor_bruto` = '$valor_produtos', 
              `cl_valor_liquido` = '$valor_liquido',
              `cl_valor_desconto` = '$valor_desconto',
              `cl_observacao` = '$observacao'
            WHERE `cl_id` = '$id'";

            $operacao_update = mysqli_query($conecta, $query);
            if ($operacao_update) {
                $retornar["dados"] =  array(
                    "sucesso" => true,
                    "title" => "Pedido de compra alterado com sucesso"
                );

                $mensagem = utf8_decode("Alterou o pedido de compra $serie_nf$numero_nf");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de adicionar o pedido de compra $serie_nf$numero_nf_novo  ");
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
            $retornar["dados"] = array("sucesso" => false, "title" => "O pedido não foi iniciado, favor, inicie o pedido para continuar com a ação");
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
        $serie_nf = consulta_tabela($conecta, 'tb_pedido_compra', 'cl_codigo_nf', $codigo_nf, 'cl_serie_nf'); //serie que está no pedido de compra
        $numero_nf = consulta_tabela($conecta, 'tb_pedido_compra', 'cl_codigo_nf', $codigo_nf, 'cl_numero_nf'); //validar se o pedido de compra já está inserido

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

                if (($valor_total_item) == 0) {
                    $retornar["dados"] = array("sucesso" => false, "title" => "Valor total do item não pode ser 0, favor, verifique");
                    echo json_encode($retornar);
                }


                $prazo_entrega = empty($prazo_entrega) ? $prazo_entrega_padrao : $prazo_entrega; //definir o prazo de entrega, se não for informado assumir a entrega padrão

                $query = "INSERT INTO `tb_pedido_compra_item` (`cl_data_movimento`, `cl_serie_nf`, `cl_numero_nf`,
            `cl_codigo_nf`, `cl_usuario_id`, `cl_item_id`, `cl_descricao_item`, `cl_referencia`, `cl_quantidade`, `cl_unidade`, 
            `cl_valor_unitario`, `cl_valor_total`, `cl_prazo_entrega`) 
           VALUES ( '$data_lancamento', '$serie_nf', '$numero_nf', '$codigo_nf', '$usuario_id', '$produto_id', '$descricao_produto', '$referencia', 
           '$quantidade', '$unidade', '$preco_venda', '$valor_total_item', '$prazo_entrega' ) ";
                $operacao_insert = mysqli_query($conecta, $query);
                if ($operacao_insert) {
                    $retornar["dados"] =  array("sucesso" => true, "title" => "Item adicionado com sucesso");

                    if (!empty($numero_nf)) { //se já existir o pedido, atualizar os valores
                        $mensagem = utf8_decode("Adicionou o item $descricao_produto no pedido de compra $serie_nf$numero_nf");
                        registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                    }

                    // Se tudo ocorreu bem, confirme a transação
                    mysqli_commit($conecta);
                } else {
                    // Se ocorrer um erro, reverta a transação
                    mysqli_rollback($conecta);
                    $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                    $mensagem = utf8_decode("Tentativa sem sucesso de adicionar o item $descricao_produto em um pedido de compra");
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

                $query = "UPDATE `tb_pedido_compra_item` SET 
              `cl_item_id` = '$produto_id', 
              `cl_descricao_item` = '$descricao_produto', 
              `cl_referencia` = '$referencia', 
              `cl_quantidade` = '$quantidade', 
              `cl_unidade` = '$unidade', 
              `cl_valor_unitario` = '$preco_venda', 
              `cl_valor_total` = '$valor_total_item', 
              `cl_prazo_entrega` = '$prazo_entrega'
          WHERE `cl_id` = '$item_id'"; // Substitua `id` pela coluna que identifica o registro único
                $operacao_update = mysqli_query($conecta, $query);
                if ($operacao_update) {
                    $retornar["dados"] =  array("sucesso" => true, "title" => "Item alterado com sucesso");

                    if (!empty($numero_nf)) { //se já existir o pedido, atualizar os valores
                        $mensagem = utf8_decode("Alterou o item $descricao_produto no pedido de compra $serie_nf$numero_nf");
                        registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                    }

                    // Se tudo ocorreu bem, confirme a transação
                    mysqli_commit($conecta);
                } else {
                    // Se ocorrer um erro, reverta a transação
                    mysqli_rollback($conecta);
                    $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");

                    if (!empty($numero_nf)) { //se já existir o pedido, atualizar os valores
                        $mensagem = utf8_decode("Tentativa sem sucesso de alterar o item $descricao_produto do pedido de compra $numero_nf");
                        registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                    }
                }
            }
        }

        if (!empty($numero_nf)) { //atualizar valores do pedido
            $valor_produtos = consulta_tabela_query($conecta, "SELECT SUM(cl_valor_total) as total from tb_pedido_compra_item where cl_codigo_nf ='$codigo_nf'", 'total');
            $valor_desconto = consulta_tabela($conecta, 'tb_pedido_compra', 'cl_codigo_nf', $codigo_nf, 'cl_valor_desconto');
            $valor_liquido = $valor_produtos - $valor_desconto;

            update_registro($conecta, 'tb_pedido_compra', 'cl_codigo_nf', $codigo_nf, '', '', 'cl_valor_liquido', $valor_liquido);
            update_registro($conecta, 'tb_pedido_compra', 'cl_codigo_nf', $codigo_nf, '', '', 'cl_valor_bruto', $valor_produtos);
            update_registro($conecta, 'tb_pedido_compra', 'cl_codigo_nf', $codigo_nf, '', '', 'cl_valor_desconto', $valor_desconto);
        }
    }
    if ($acao == "imprimir") {

        $codigo_nf = isset($_POST['codigo_nf']) ? $_POST['codigo_nf'] : '';
        $valida_pedido = consulta_tabela($conecta, 'tb_pedido_compra', 'cl_codigo_nf', $codigo_nf, 'cl_id');
        $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : ''; //tipo do documento


        if ($valida_pedido == "") {
            $retornar["dados"] = array("sucesso" => false, "title" => "Pedido de compra não encontrado, verifique se o pedido foi salvo corretamente");
        } elseif ($tipo == "") {
            $retornar["dados"] = array("sucesso" => false, "title" => "Tipo de documento não identificado");
        } else {
            $caminho =  "view/compra/pedido_compra/documento/$tipo.php?gerar_documento=true&tipo=$tipo&codigo_nf=$codigo_nf";
            $retornar["dados"] = array("sucesso" => true, "title" => $caminho);
        }
    }
    if ($acao == "cancelar_pedido") {
        // Iniciar uma transação MySQL
        mysqli_begin_transaction($conecta);

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            // Remover aspas simples usando str_replace
            ${$name} = str_replace("'", "", ${$name});
        }

        if ($form_id == "") {
            $retornar["dados"] = array("sucesso" => false, "title" => "Pedido de compra não encontrado, verifique se o pedido foi salvo corretamente");
        } elseif ($check_autorizador == "false") {
            $retornar["dados"] =  array("sucesso" => "autorizar", "title" =>  "Continue com a operação autorizando com a senha");
        } else {

            $usuario_id  = $_POST['usuario_id'] != '' ? $_POST['usuario_id'] : verifica_sessao_usuario();
            $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");

            $numero_nf = consulta_tabela($conecta, 'tb_pedido_compra', 'cl_id', $form_id, 'cl_numero_nf');
            $serie_nf = consulta_tabela($conecta, 'tb_pedido_compra', 'cl_id', $form_id, 'cl_serie_nf');
            $update =  update_registro($conecta, 'tb_pedido_compra', 'cl_id', $form_id, '', '', 'cl_status_ativo', '0');
            if ($update) {
                $mensagem = utf8_decode("Cancelou o pedido de compra $serie_nf$numero_nf");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                $retornar["dados"] = array("sucesso" => true, "title" => "Pedido de compra $serie_nf$numero_nf cancelado com sucesso");

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor, contatar o suporte");
                mysqli_rollback($conecta);

                $mensagem = utf8_decode("Tentativa sem sucesso de cancelar o pedido de compra $serie_nf$numero_nf");
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
        $codigo_nf = consulta_tabela($conecta, 'tb_pedido_compra_item', 'cl_id', $form_id, 'cl_codigo_nf');
        $descricao_item = consulta_tabela($conecta, 'tb_pedido_compra_item', 'cl_id', $form_id, 'cl_descricao_item');
        $numero_nf = consulta_tabela($conecta, 'tb_pedido_compra', 'cl_codigo_nf', $codigo_nf, 'cl_numero_nf');
        $serie_nf = consulta_tabela($conecta, 'tb_pedido_compra', 'cl_codigo_nf', $codigo_nf, 'cl_serie_nf');

        if ($form_id == "") {
            $retornar["dados"] = array("sucesso" => false, "title" => "Item não encontrado, verifique se o item foi salvo corretamente");
        } else {
            $query = "DELETE FROM tb_pedido_compra_item where cl_id = $form_id";
            $delete = mysqli_query($conecta, $query);
            if ($delete) {


                if (!empty($numero_nf)) { //atualizar valores do pedido
                    $valor_produtos = consulta_tabela_query($conecta, "SELECT SUM(cl_valor_total) as total from tb_pedido_compra_item where cl_codigo_nf ='$codigo_nf'", 'total');
                    $valor_desconto = consulta_tabela($conecta, 'tb_pedido_compra', 'cl_codigo_nf', $codigo_nf, 'cl_valor_desconto');
                    $valor_liquido = $valor_produtos - $valor_desconto;

                    update_registro($conecta, 'tb_pedido_compra', 'cl_codigo_nf', $codigo_nf, '', '', 'cl_valor_liquido', $valor_liquido);
                    update_registro($conecta, 'tb_pedido_compra', 'cl_codigo_nf', $codigo_nf, '', '', 'cl_valor_bruto', $valor_produtos);
                    update_registro($conecta, 'tb_pedido_compra', 'cl_codigo_nf', $codigo_nf, '', '', 'cl_valor_desconto', $valor_desconto);

                    $mensagem = utf8_decode("Removeu o item $descricao_item do pedido de compra $serie_nf$numero_nf");
                    registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                }
                $retornar["dados"] = array("sucesso" => true, "title" => "Item removido com sucesso");
                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                mysqli_rollback($conecta);
                $mensagem = utf8_decode("Tentativa sem sucesso de removeu o item $descricao_item do pedido de compra $serie_nf$numero_nf");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }

    echo json_encode($retornar);
}


if (isset($_GET['gerar_documento'])) { ///gerar documentos 
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    include '../../../../biblioteca/phpqrcode/qrlib.php';

    $diretorio_logo =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "31"); //VERIFICAR PARAMETRO ID - 1

    $codigo_nf = isset($_GET['codigo_nf']) ? $_GET['codigo_nf'] : '';
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

    $tamanho = 2;
    $margem = 3;


    /*dados da empresa */
    $select = "SELECT * from tb_empresa where cl_id ='1' ";
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

    $url_qrdcode = "$url_init/$empresa/view/compra/pedido_compra/documento/$tipo.php?gerar_documento=true&tipo=$tipo&codigo_nf=$codigo_nf";
    // Renderize o QR Code em um buffer de saída
    ob_start();
    QRcode::png($url_qrdcode, null, QR_ECLEVEL_L, $tamanho, $margem);
    $imageData = ob_get_contents();
    ob_end_clean();


    $select = "SELECT fpg.cl_descricao as formapgto,frt.cl_descricao as frete, user.cl_nome as comprador, pdc.*,prc.cl_id as parceiroid, prc.cl_razao_social
         as parceirorazao,prc.cl_cnpj_cpf as prccnpjcpf, prc.cl_telefone as prctelefone,
          prc.cl_email as prcemail,  prc.cl_endereco as prcendereco, prc.cl_bairro as prcbairro,cd.cl_nome as prccidade,
         transp.cl_id as transportadoraid, transp.cl_razao_social as transprazaosocial,transp.cl_cnpj_cpf as transpcnpjcpf 
         from tb_pedido_compra as pdc
         left join tb_parceiros as prc on prc.cl_id = pdc.cl_parceiro_id
         left join tb_parceiros as transp on transp.cl_id = pdc.cl_transportadora_id
         left join tb_cidades as cd on cd.cl_id = prc.cl_cidade_id
         left join tb_users as user on user.cl_id = pdc.cl_usuario_id
         left join tb_frete as frt on frt.cl_id = pdc.cl_frete_id
         left join tb_forma_pagamento as fpg on fpg.cl_id = pdc.cl_forma_pagamento_id

         WHERE pdc.cl_codigo_nf = '$codigo_nf' ";
    $consultar_cotacao_mercadoria = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consultar_cotacao_mercadoria);
    $data_aprovacao = ($linha['cl_data_aprovacao']);
    $codigo_nf = ($linha['cl_codigo_nf']);
    $serie_nf = ($linha['cl_serie_nf']);
    $numero_nf = ($linha['cl_numero_nf']);
    $numero_solicitacao = ($linha['cl_numero_solicitacao']);

    $parceiro_id = ($linha['parceiroid']);
    $endereco_parceiro = utf8_encode($linha['prcendereco']);
    $bairro_parceiro = utf8_encode($linha['prcbairro']);
    $cidade_parceiro = utf8_encode($linha['prccidade']);
    $razao_social_parceiro = utf8_encode($linha['parceirorazao']);
    $cpf_cnpj_parceiro = formatCNPJCPF($linha['prccnpjcpf']);
    $telefone_parceiro = ($linha['prctelefone']);
    $prcemail = ($linha['prcemail']);

    $razao_social_transp = utf8_encode($linha['transprazaosocial']);
    $cnpj_cpf_transp = formatCNPJCPF($linha['transpcnpjcpf']);

    $formapgto = utf8_encode($linha['formapgto']);
    $usuario_id = ($linha['cl_usuario_id']);
    $status_id = ($linha['cl_status_id']);

    $frete = utf8_encode($linha['frete']);

    $operacao = ($linha['cl_operacao']);
    $valor_bruto = ($linha['cl_valor_bruto']);
    $valor_liquido = ($linha['cl_valor_liquido']);
    $valor_desconto = ($linha['cl_valor_desconto']);
    $observacao = utf8_encode($linha['cl_observacao']);
    $comprador = utf8_encode($linha['comprador']);
    $operacao = ($linha['cl_operacao']);

    $select  = "SELECT pdci.cl_unidade as unidade,pdci.cl_referencia as referencia, pdci.*,
    prd.*,pdci.cl_id as itemid,prd.cl_id as produtoid from tb_pedido_compra_item as pdci
     left join tb_produtos as prd on prd.cl_id = pdci.cl_item_id where pdci.cl_codigo_nf = '$codigo_nf' ";
    $consultar_produtos = mysqli_query($conecta, $select);
}
