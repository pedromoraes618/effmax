<?php
include "../conexao/conexao.php";
include "../funcao/funcao.php";

$data_inicial = ($data_inicial_mes_bd . ' 01:01:01');
$data_final = ($data_final_mes_bd . ' 23:59:59');

$usuario_id = 3;
$estado_empresa = consulta_tabela($conecta, 'tb_empresa', 'cl_id', 1, 'cl_estado');
$status_rotina = consulta_tabela($conecta, 'tb_parametros', 'cl_id', 137, 'cl_valor'); //S- SIM OU N - NÃO

if ($status_rotina == "S") {
    $select = "SELECT * FROM tb_pedido_loja where cl_data between '$data_inicial' and '$data_final' and cl_status_pagamento='approved' ";
    // $select = "SELECT * FROM tb_pedido_loja where cl_pedido='758870727244' ";
    $consultar_pedido = mysqli_query($conecta, $select);
    if (!$consultar_pedido) {
        die("Falha no banco de dados " . mysqli_error($conecta));
    } else {
        if (mysqli_num_rows($consultar_pedido) > 0) {
            while ($linha = mysqli_fetch_assoc($consultar_pedido)) {
                $codigo_nf = $linha['cl_codigo_nf'];
                $status_pagamento = $linha['cl_status_pagamento'];
                $valida_existe_venda = consulta_tabela($conecta, 'tb_nf_saida', 'cl_codigo_nf', $codigo_nf, 'cl_id');

                $serie_nf = consulta_tabela($conecta, 'tb_serie', 'cl_id', 12, 'cl_descricao'); //venda
                $numero_nf_atual = consulta_tabela($conecta, "tb_serie", 'cl_id', 12, 'cl_valor'); //venda

                if (empty($valida_existe_venda)) { //pedido foi aprovado e não existe venda no momento para o pedido
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

                    
                    $numero_nf_novo = $numero_nf_atual + 1;
                    $insert = "INSERT INTO `tb_nf_saida`
                    (`cl_data_movimento`, `cl_codigo_nf`, `cl_parceiro_id`,
                     `cl_forma_pagamento_id`, `cl_numero_nf`, `cl_numero_venda`, `cl_serie_nf`, `cl_operacao`, 
                     `cl_valor_bruto`, `cl_valor_frete`, `cl_valor_desconto`, `cl_valor_liquido`,`cl_status_recebimento`,
                     `cl_status_venda`, `cl_vendedor_id`, `cl_usuario_id`,`cl_usuario_id_recebimento`,`cl_data_recebimento`,`cl_tipo_frete_id` )
                VALUES
                    ('$data_lancamento', '$codigo_nf', '$cliente_id','$forma_pagamento_id', '$numero_nf_novo', '$numero_nf_novo', '$serie_nf', 
                    'VENDA', '$valor_bruto','$valor_frete','$valor_desconto_liquido', '$valor_liquido','2','1','$usuario_id',
                    '$usuario_id','$usuario_id','$data_lancamento', '$tipo_frete' ) ";
                    $operacao_insert_dados = mysqli_query($conecta, $insert); //adicionar os dados basicos da venda
                    $nf_id = mysqli_insert_id($conecta);
                    update_registro($conecta, 'tb_serie', 'cl_id', 12, '', '', 'cl_valor', $numero_nf_novo); //atualizar a serie da venda




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
                     `cl_valor_total`,`cl_status` ) VALUES ( '$data_lancamento', '$codigo_nf', '$usuario_id', '$serie_nf', 
                      '$numero_nf_novo','$produtoID','$descricao','$referencia', '$quantidade', '$unidade', '$valor', '$total','1') ";
                            $operacao_insert_item = mysqli_query($conecta, $insert); //adicionar os dados basicos da venda
                        }
                        $nf = ["$nf_id"];
                        gerar_nf($conecta, $nf, '5102', "NFE");
                        // recalcular_valor_nf_saida($conecta, $codigo_nf);
                    }

                    $descricao = utf8_decode("Recebimento referente a $serie_nf $numero_nf_novo");
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
                        $numero_nf_novo,
                        0,
                        $descricao,
                        "",
                        $numero_nf_novo,
                        $serie_nf,
                        $codigo_nf,
                        "",
                        ""
                    ));


                    /*enviar a nota fiscal */

                    enviar_nf_loja($conecta, $nf_id);
                }
            }
        };
    }

    $mensagem = utf8_decode("Script gerar_enviar_nf_loja executado"); //registrar no log o evento de envio
    registrar_log($conecta, "Loja", $data, $mensagem);
}
