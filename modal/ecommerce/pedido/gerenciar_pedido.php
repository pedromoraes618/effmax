<?php
if (isset($_GET['pedido'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $usuario_id = verifica_sessao_usuario();
    $pedido = "";
    if (isset($_GET['form_id'])) {
        $form_id = $_GET['form_id'];
        $pedido = consulta_tabela($conecta, 'tb_pedido_loja', 'cl_id', $form_id, 'cl_pedido');
        $empresa = consulta_tabela($conecta, 'tb_empresa', 'cl_id', '1', 'cl_empresa'); //diretorio raiz sistema

        $query = "SELECT pd.*,fpg.cl_descricao as formapagamento FROM tb_pedido_loja as pd 
        left join tb_forma_pagamento as fpg on fpg.cL_id = pd.cl_pagamento_id_interno WHERE pd.cl_id ='$form_id'  ";
        $consulta = mysqli_query($conecta, $query);
        if ($consulta) {
            $qtd_registro = mysqli_num_rows($consulta);
            $linha = mysqli_fetch_assoc($consulta);
            $codigo_nf = ($linha['cl_codigo_nf']);
            $nome = utf8_encode($linha['cl_nome']);
            $data_pedido = ($linha['cl_data']);
            $pedido = ($linha['cl_pedido']);
            $email = ($linha['cl_email']);
            $cpfcnpj = ($linha['cl_cpf_cnpj']);
            $telefone = ($linha['cl_telefone']);
            $endereco = utf8_encode($linha['cl_endereco']);
            $bairro = utf8_encode($linha['cl_bairro']);
            $numero = utf8_encode($linha['cl_numero']);
            $complemento = utf8_encode($linha['cl_complemento']);
            $cep = utf8_encode($linha['cl_cep']);
            $cidade = utf8_encode($linha['cl_cidade']);
            $estado = utf8_encode($linha['cl_estado']);
            $transportadora = utf8_encode($linha['cl_transportadora']);
            $formapagamento = utf8_encode($linha['formapagamento']);
            $status_pagamento = utf8_encode($linha['cl_status_pagamento']);
            $status_compra = utf8_encode($linha['cl_status_compra']);
            $codigo_rastreio = ($linha['cl_codigo_rastreio']);
            $cupom = ($linha['cl_cupom']);

            $valor_frete = ($linha['cl_valor_frete']);
            $valor_produto = ($linha['cl_valor_produto']);
            $valor_desconto = ($linha['cl_desconto']);
            $valor_cupom = ($linha['cl_valor_cupom']);
            $valor_liquido = ($linha['cl_valor_liquido']);

            $query = "SELECT pdl.*,prd.* FROM tb_produto_pedido_loja as pdl 
        inner join tb_produtos as prd on prd.cl_id = pdl.cl_produto_id where pdl.cl_codigo_nf = '$codigo_nf' ";
            $consultaProdutos = mysqli_query($conecta, $query);
        }
    } else {
        $form_id = null;
    }
}



//consultar informações para tabela devolucao
if (isset($_GET['consultar_pedido'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $consulta = $_GET['consultar_pedido'];

    $data_inicial = $_GET['data_inicial'];
    $data_final = $_GET['data_final'];
    $data_inicial = ($data_inicial . ' 01:01:01');
    $data_final = ($data_final . ' 23:59:59');

    $ambiente = verficar_paramentro($conecta, "tb_parametros", "cl_id", "35"); // 1 - homologacao 2 - producao
    if ($ambiente == "1") { //consultar o pdf da nota fiscal
        $server = verficar_paramentro($conecta, "tb_parametros", "cl_id", "60");
    } elseif ($ambiente == "2") {
        $server =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "61");
    }

    if ($consulta == "inicial") {
        $consultar_tabela_inicialmente =  verficar_paramentro(
            $conecta,
            "tb_parametros",
            "cl_id",
            "1"
        ); //VERIFICAR PARAMETRO ID - 1
        $select = "SELECT pd.*,fpg.cl_descricao as formapagamento FROM tb_pedido_loja as pd 
        left join tb_forma_pagamento as fpg on fpg.cL_id = 
        pd.cl_pagamento_id_interno order by cl_id desc";
        $consultar_pedido = mysqli_query($conecta, $select);
        if (!$consultar_pedido) {
            die("Falha no banco de dados " . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar_pedido); //quantidade de registros
        }
    } else {
        $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
        $status_pagamento = $_GET['status_pagamento'];
        $forma_pagamento = $_GET['fpg'];
        $status_pedido = $_GET['status_pedido'];


        $select = "SELECT pd.*,fpg.cl_descricao as formapagamento FROM tb_pedido_loja as pd 
        left join tb_forma_pagamento as fpg on fpg.cL_id = 
        pd.cl_pagamento_id_interno  where pd.cl_data between '$data_inicial' and '$data_final' and ( pd.cl_nome  like '%$pesquisa%' or cl_pedido
           like '%$pesquisa%' or pd.cl_cpf_cnpj like '%$pesquisa%' or  pd.cl_pagamento_id_interno  like '%$pesquisa%' or pd.cl_email like '%$pesquisa%' ) ";

        if ($status_pagamento != "0") {
            $select .= " and pd.cl_status_pagamento = '$status_pagamento' ";
        }

        if ($forma_pagamento != "0") {
            $select .= " and pd.cl_pagamento_id_interno = '$forma_pagamento' ";
        }
        if ($status_pedido != "0") {
            $select .= " and pd.cl_status_compra = '$status_pedido' ";
        }

        $select .= " order by cl_id desc";

        $consultar_pedido = mysqli_query($conecta, $select);
        if (!$consultar_pedido) {
            die("Falha no banco de dados " . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar_pedido);
        }
    }
}



// //cadastrar formulario
if (isset($_POST['formulario_ecommerce'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $acao = $_POST['acao'];
    $usuario_id = verifica_sessao_usuario();
    $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");
    $autorizar_alterar_dados = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_autorizar_dados_pedido_loja");
    $tipo_usuario = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_tipo");
    $produto_id_default = consulta_tabela($conecta, "tb_parametros", "cl_id", '78', "cl_valor");

    $accesstokenKangu = consulta_tabela($conecta, "tb_parametros", "cl_id", '67', "cl_valor");
    $apiFrete = consulta_tabela($conecta, "tb_parametros", "cl_id", '86', "cl_valor");

    $serie_venda = verifcar_descricao_serie($conecta, "12"); //verificar qual seria usado na venda
    $numero_venda_atual = consultar_valor_serie($conecta, "12"); //verificar a numeração da venda atual

    $msgRastreio = "";
    if ($acao == "show") {
        $id = $_POST['form_id'];
        $query = "SELECT pd.*,fpg.cl_descricao as formapagamento FROM tb_pedido_loja as pd 
        left join tb_forma_pagamento as fpg on fpg.cL_id = pd.cl_pagamento_id_interno WHERE pd.cl_id ='$id'  ";
        $consulta = mysqli_query($conecta, $query);
        if ($consulta) {

            $qtd_registro = mysqli_num_rows($consulta);
            $linha = mysqli_fetch_assoc($consulta);
            $codigo_nf = ($linha['cl_codigo_nf']);
            $nome = utf8_encode($linha['cl_nome']);
            $data_pedido = ($linha['cl_data']);
            $pedido = ($linha['cl_pedido']);
            $email = ($linha['cl_email']);
            $cpfcnpj = ($linha['cl_cpf_cnpj']);
            $telefone = formatarNumeroTelefone($linha['cl_telefone']);
            $endereco = utf8_encode($linha['cl_endereco']);
            $bairro = utf8_encode($linha['cl_bairro']);
            $numero = utf8_encode($linha['cl_numero']);
            $complemento = utf8_encode($linha['cl_complemento']);
            $cep = utf8_encode($linha['cl_cep']);
            $cidade = utf8_encode($linha['cl_cidade']);
            $estado = utf8_encode($linha['cl_estado']);
            $transportadora = utf8_encode($linha['cl_transportadora']);
            $forma_pagamento_id = ($linha['cl_pagamento_id_interno']);
            $formapagamento = utf8_encode($linha['formapagamento']);
            $status_pagamento = utf8_encode($linha['cl_status_pagamento']);
            $status_compra = utf8_encode($linha['cl_status_compra']);
            $codigo_rastreio = ($linha['cl_codigo_rastreio']);
            $observacao = utf8_encode($linha['cl_observacao']);
            $data_entrega = ($linha['cl_data_entrega']);

            $valor_frete = ($linha['cl_valor_frete']);
            $valor_produto = ($linha['cl_valor_produto']);
            $valor_desconto = ($linha['cl_desconto']);
            $valor_liquido = ($linha['cl_valor_liquido']);
            $status_pagamento = $status_pagamento == "" ? 'naorealizado' : $status_pagamento;

            if (!empty($codigo_rastreio) and $apiFrete == "kangu") {
                $status_rastrear =  rastrearObjetoKangu($codigo_rastreio, $accesstokenKangu)['data'];
                if ($status_rastrear['status'] == false) {
                    $msgRastreio = $status_rastrear['message'];
                } else {
                    $msgRastreio = "<span class='text-success'>Status atual: " .
                        $status_rastrear["response"]["situacao"]["ocorrencia"] . " - " .
                        ($status_rastrear["response"]["situacao"]["data"] . "</span>");
                }
            }
        }

        $informacao = array(
            "data_pedido" => $data_pedido,
            "pedido" => $pedido,
            "nome" => $nome,
            "email" => $email,
            "cpfcnpj" => $cpfcnpj,
            "telefone" => $telefone,

            "status_pagamento" => $status_pagamento,
            "status_compra" => $status_compra,
            "transportadora" => $transportadora,
            "codigo_rastreio" => $codigo_rastreio,
            "forma_pagamento_id" => $forma_pagamento_id,

            "endereco" => $endereco,
            "bairro" => $bairro,
            "complemento" => $complemento,
            "numero" => $numero,
            "data_entrega" => $data_entrega,

            "cep" => $cep,
            "cidade" => $cidade,
            "estado" => $estado,
            "formapagamento" => $formapagamento,
            "codigo_rastreio" => $codigo_rastreio,
            "valor_frete" => $valor_frete,
            "valor_produto" => $valor_produto,
            "valor_desconto" => $valor_desconto,
            "valor_liquido" => $valor_liquido,

            "observacao" => $observacao,
            "msgRastreio" => $msgRastreio,
        );

        $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
    }



    if ($acao == "update") { // EDITAR

        mysqli_begin_transaction($conecta);
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples

        }

        if (empty($id)) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Pedido não encontrada, favor, verifique");
        } elseif ($autorizar_alterar_dados != "SIM" and $tipo_usuario != "suporte") {
            $retornar["dados"] = array("sucesso" => false, "title" =>  "O seu usuário não tem permissão para editar esses dados");
        } elseif (empty($cep)) {
            $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro("cep"));
        } elseif (empty($endereco)) {
            $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro("endereço"));
        } elseif (empty($bairro)) {
            $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro("bairro"));
        } elseif (empty($cidade)) {
            $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro("cidade"));
        } elseif (empty($estado)) {
            $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro("estado"));
        } elseif (empty($numero)) {
            $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro("nº"));
        } else {

            if (!empty($codigo_rastreio) and $apiFrete == "kangu") {
                $status_rastrear =  rastrearObjetoKangu($codigo_rastreio, $accesstokenKangu)['data'];
                if ($status_rastrear['status'] == false) {
                    $msgRastreio = $status_rastrear['message'];
                } else {
                    $msgRastreio = "<span class='text-success'>Status atual: " .
                        $status_rastrear["response"]["situacao"]["ocorrencia"] . " - " .
                        ($status_rastrear["response"]["situacao"]["data"] . "</span>");
                }
            }
            // Remover caracteres especiais, deixando apenas os números
            $cep = preg_replace("/[^0-9]/", "", $cep);
            $pedido = consulta_tabela($conecta, 'tb_pedido_loja', 'cl_id', $id, 'cl_pedido');

            $update = "UPDATE `tb_pedido_loja` SET  `cl_cep` = '$cep', `cl_cidade` = '$cidade',  `cl_estado` = '$estado', `cl_endereco` = '$endereco',
              `cl_numero` = '$numero', `cl_complemento` = '$complemento',
              `cl_observacao` = '$observacao',`cl_data_entrega` = '$data_entrega',
              `cl_codigo_rastreio` = '$codigo_rastreio'
               WHERE `cl_id` = $id ";
            $operacao_update = mysqli_query($conecta, $update);
            if ($operacao_update) {
                $retornar["dados"] =  array("sucesso" => true, "title" => "Pedido alterada com sucesso", "msgRastreio" => $msgRastreio);
                $mensagem = utf8_decode("Alterou o pedido #$pedido");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de alterar o pedido #$pedido ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }



    if ($acao == "gerar_venda") { // gerar venda
        $parceiro_avulso_id = verficar_paramentro($conecta, "tb_parametros", "cl_id", "8"); //verificar o id do cliente avulso

        mysqli_begin_transaction($conecta);
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
        }

        $codigo_nf = !empty($id) ? consulta_tabela($conecta, "tb_pedido_loja", "cl_id", $id, "cl_codigo_nf") : '';
        $valida_venda = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_numero_nf");
        $serie_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_serie_nf");
        $estado_empresa = consulta_tabela($conecta, 'tb_empresa', 'cl_id', 1, 'cl_estado');

        if (empty($id)) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Pedido não encontrada, favor, verifique");
        } elseif (!empty($valida_venda)) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel gerar a venda, pois a venda $serie_nf$valida_venda está registrada para essa pedido");
        } else {
            $select = "SELECT * FROM tb_pedido_loja where cl_id = $id ";
            $operacao_consulta = mysqli_query($conecta, $select);
            $linha = mysqli_fetch_assoc($operacao_consulta);
            $codigo_nf = ($linha['cl_codigo_nf']);
            $nome = ($linha['cl_nome']);
            $data_pedido = ($linha['cl_data']);
            $pedido = ($linha['cl_pedido']);
            $email = ($linha['cl_email']);
            $cpfcnpj = ($linha['cl_cpf_cnpj']);
            $telefone = ($linha['cl_telefone']);
            $endereco = ($linha['cl_endereco']);
            $bairro = ($linha['cl_bairro']);
            $numero = ($linha['cl_numero']);
            $complemento = ($linha['cl_complemento']);
            $cep = ($linha['cl_cep']);
            $cidade = ($linha['cl_cidade']);
            $estado = ($linha['cl_estado']);
            $transportadora = ($linha['cl_transportadora']);
            $forma_pagamento_id = ($linha['cl_pagamento_id_interno']);
            $status_pagamento = ($linha['cl_status_pagamento']);
            $status_compra = ($linha['cl_status_compra']);
            $codigo_rastreio = ($linha['cl_codigo_rastreio']);
            $observacao = ($linha['cl_observacao']);
            $data_entrega = ($linha['cl_data_entrega']);
            $valor_cupom = ($linha['cl_valor_cupom']);

            $valor_frete = ($linha['cl_valor_frete']);
            $valor_produto = ($linha['cl_valor_produto']);
            $valor_desconto = ($linha['cl_desconto']);
            $valor_desconto_liquido = $valor_desconto + $valor_cupom;
            $valor_liquido = ($linha['cl_valor_liquido']);
            $valor_bruto = $valor_frete + $valor_produto;

            $resultado_cep = buscar_cep($cep);
            $api_cep = $resultado_cep["dados"]["sucesso"];

            if ($api_cep == false) {
                $retornar["dados"] =  array("sucesso" => false, "title" => $resultado_cep["dados"]["sucesso"]);
                echo json_encode($retornar);
                exit;
            }

            $cidade_ibge =  $resultado_cep["dados"]["valores"]['ibge'];
            if ($estado == $estado_empresa) {
                $tipo_frete = 3;
            } else {
                $tipo_frete = 0;
            }
            $cidade_id = consulta_tabela($conecta, "tb_cidades", "cl_ibge ", $cidade_ibge, "cl_id");
            $estado_id = consulta_tabela($conecta, "tb_cidades", "cl_ibge ", $cidade_ibge, "cl_estado_id");
            $valida_cliente = consulta_tabela($conecta, "tb_parceiros", "cl_cnpj_cpf ", $cpfcnpj, "cl_id");

            if (empty($valida_cliente)) { //cliente ainda não foi cadastrado
                $insert = "INSERT INTO `tb_parceiros` (
            `cl_data_cadastro`, `cl_usuario_id`, `cl_razao_social`, 
             `cl_cnpj_cpf`, `cl_cep`, `cl_bairro`, `cl_endereco`, `cl_numero`, `cl_cidade_id`,
              `cl_estado_id`, `cl_pais`, `cl_telefone`, `cl_email`,`cl_situacao_ativo` )
                VALUES ('$data_lancamento', '$usuario_id', '$nome', 
                 '$cpfcnpj', '$cep', '$bairro', '$endereco', '$numero','$cidade_id', '$estado_id', 'BRASIL', '$telefone', '$email', 'SIM' )";
                $operacao_insert = mysqli_query($conecta, $insert);
                if (!$operacao_insert) {
                    $retornar["dados"] =  array("sucesso" => false, "title" => "Erro ao cadastrar o cliente");
                    echo json_encode($retornar);
                    exit;
                } else {
                    $cliente_id = mysqli_insert_id($conecta);
                }
            } else { //cliente já cadstrado
                $cliente_id = $valida_cliente;
            }
            //  $retornar["dados"] =  array("sucesso" => false, "title" => "$cliente_id, favor contatar o suporte");

            $numero_nf = $numero_venda_atual + 1;

            $insert = "INSERT INTO `tb_nf_saida`
                (`cl_data_movimento`, `cl_codigo_nf`, `cl_parceiro_id`,
                 `cl_forma_pagamento_id`, `cl_numero_nf`, `cl_numero_venda`, `cl_serie_nf`, `cl_operacao`, 
                 `cl_valor_bruto`, `cl_valor_frete`, `cl_valor_desconto`, `cl_valor_liquido`,`cl_status_recebimento`,
                 `cl_status_venda`, `cl_vendedor_id`, `cl_usuario_id`,`cl_usuario_id_recebimento`,`cl_data_recebimento`,`cl_tipo_frete_id` )
            VALUES
                ('$data_lancamento', '$codigo_nf', '$cliente_id', '$forma_pagamento_id', '$numero_nf', '$numero_nf', '$serie_venda', 
                'VENDA', '$valor_bruto','$valor_frete','$valor_desconto_liquido', '$valor_liquido','2','1','$usuario_id',
                '$usuario_id','$usuario_id','$data_lancamento', '$tipo_frete' ) ";
            $operacao_insert_dados = mysqli_query($conecta, $insert); //adicionar os dados basicos da venda



            $query = "SELECT pdl.*,prd.*,prd.cl_id as produtoID,und.cl_sigla as unidade FROM tb_produto_pedido_loja as pdl 
            inner join tb_produtos as prd on prd.cl_id = pdl.cl_produto_id left join tb_unidade_medida as und on und.cl_id = prd.cl_und_id
            where pdl.cl_codigo_nf = '$codigo_nf' ";
            $consultaProdutos = mysqli_query($conecta, $query);
            $qtdProdutos = mysqli_num_rows($consultaProdutos);
            if ($qtdProdutos > 0) {
                while ($linha = mysqli_fetch_assoc($consultaProdutos)) {
                    $produtoID = ($linha['produtoID']);
                    $descricao = ($linha['cl_descricao']);
                    $referencia = ($linha['cl_referencia']);
                    $unidade = ($linha['unidade']);
                    $quantidade = $linha['cl_quantidade'];
                    $valor = ($linha['cl_valor']);
                    $total = $valor * $quantidade;



                    $insert = "INSERT INTO `tb_nf_saida_item` ( `cl_data_movimento`, `cl_codigo_nf`, `cl_usuario_id`, `cl_serie_nf`,
                    `cl_numero_nf`, `cl_item_id`, `cl_descricao_item`,`cl_referencia`, `cl_quantidade`, `cl_unidade`, `cl_valor_unitario`,
                     `cl_valor_total`,`cl_status` ) VALUES ( '$data_lancamento', '$codigo_nf', '$usuario_id', '$serie_venda', 
                      '$numero_nf','$produtoID','$descricao','$referencia', '$quantidade', '$unidade', '$valor', '$total','1') ";
                    $operacao_insert_item = mysqli_query($conecta, $insert); //adicionar os dados basicos da venda
                }
            }

            $descricao = utf8_decode("Recebimento referente a $serie_venda $numero_nf");
            $recebimento_nf =  (recebimento_nf(
                $conecta,
                $data_lancamento,
                $data_lancamento,
                $data_lancamento,
                '0',
                $forma_pagamento_id,
                $cliente_id,
                "RECEITA",
                "2",
                $valor_liquido,
                $valor_liquido,
                0,
                0,
                0,
                0,
                $numero_nf,
                0,
                $descricao,
                "",
                $numero_nf,
                $serie_venda,
                $codigo_nf,
                "",
                ""
            ));
            // $status_recebimento = update_registro($conecta, 'tb_nf_saida', 'cl_codigo_nf', $codigo_nf, '', '', 'cl_status_recebimento', 2); //status recebido

            if ($operacao_insert_dados and $operacao_insert_item and $recebimento_nf) {
                $retornar["dados"] =  array("sucesso" => true, "title" => "$serie_venda $numero_nf gerada com sucesso");
                $mensagem = utf8_decode("Gerou a venda $serie_venda $numero_nf do pedido loja nº $pedido");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                $atualizar_serie_venda = update_registro($conecta, "tb_serie", "cl_id", "12", '', '', 'cl_valor', $numero_nf);
                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de gerar uma venda do pedido loja nº $pedido ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }


    echo json_encode($retornar);
}
