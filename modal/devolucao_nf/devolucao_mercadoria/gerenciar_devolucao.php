<?php
if (isset($_GET['devolucao_nf']) or (isset($_GET['estorno_nf']))) {
    $id = null;
    $tipo = null;
    $parceiro = null;
    $titulo = null;
    if (isset($_GET['tipo'])) {
        $tipo = $_GET['tipo'];
        $id = $_GET['form_id'];
        if ($tipo == "saidadev") {
            $parceiro = "Cliente";
            $titulo = "Devolução de Mercadoria";
        } elseif ($tipo == "saidaestorno") {
            $parceiro = "Cliente";
            $titulo = "Estorno de Mercadoria";
        } elseif ($tipo == "entradadev") {
            $parceiro = "Fornecedor";
            $titulo = "Devolução de Mercadoria";
        } else {
            $titulo = "Devolução de Documento";
            $parceiro = "Fornecedor";
        }
    }
}
if (isset($_GET['nf_itens'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";

    $form_id = $_GET['form_id'];
    $tipo = $_GET['tipo'];

    if ($tipo == "saidadev" or $tipo == "saidaestorno") {
        $codigo_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $form_id, "cl_codigo_nf");
        $select = "SELECT * from tb_nf_saida_item where cl_codigo_nf = '$codigo_nf' ";
        $consultar_nf_itens = mysqli_query($conecta, $select);
    }
    if ($tipo == "entradadev") {
        $codigo_nf = consulta_tabela($conecta, "tb_nf_entrada", "cl_id", $form_id, "cl_codigo_nf");
        $select = "SELECT * from tb_nf_entrada_item where cl_codigo_nf = '$codigo_nf' ";
        $consultar_nf_itens = mysqli_query($conecta, $select);
    }
}


if (isset($_POST['devolucao_nf'])) { //devolucao, estorno
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";

    $tipo = $_POST['tipo'];
    $acao = $_POST['acao'];

    $id_user_logado = verifica_sessao_usuario();
    $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $id_user_logado, "cl_usuario");
    $estado_empresa = consulta_tabela($conecta, "tb_empresa", "cl_id", "1", "cl_estado"); //verificar o estado do emitente
    $estado_id_empresa = consulta_tabela($conecta, "tb_estados", "cl_uf", $estado_empresa, "cl_id"); //verificar o estado do emitente


    if ($tipo == "saidadev" or $tipo == "saidaestorno") { //notas de devoluao de saida saida
        if ($acao == "create") {
            // Iniciar uma transação MySQL
            mysqli_begin_transaction($conecta);
            foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
                ${$name} = $value;
            }
            $form_id = $id;

            $codigo_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $form_id, "cl_codigo_nf");
            $numero_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $form_id, "cl_numero_nf");
            $serie_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $form_id, "cl_serie_nf");
            $parceiro_id = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $form_id, "cl_parceiro_id");
            $desconto_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $form_id, "cl_valor_desconto");
            $parceiro_estado_id = consulta_tabela($conecta, "tb_parceiros", "cl_id", $parceiro_id, "cl_estado_id"); //estado do cliente

            $forma_pagamento_id = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $form_id, "cl_forma_pagamento_id");
            $select = "SELECT * from tb_nf_saida_item where cl_codigo_nf = '$codigo_nf' ";
            $consultar_nf_itens = mysqli_query($conecta, $select);
            $check_item = 0; //verificar se foi preenchido pelo menos um campo para devolução
            $erro_bd_nf = false; //erro no banco de dados no insert do cabeçalho
            $erro_bd_item = false; //erro no banco de dados no insert dos itens
            $erro_qtd_maxima = false; //alert para quantidade maxima devolvida
            $valor_credito = 0;
            $valor_total_nf_dev = 0;
            $codigo_nf_novo = md5(uniqid(time())); //gerar um novo codigo para nf
            $numero_nf_novo = consulta_tabela($conecta, 'tb_serie', 'cl_descricao', $serie, "cl_valor");
            if ($numero_nf_novo != "") {
                $numero_nf_novo =  $numero_nf_novo + 1;
            } else {
                $numero_nf_novo = 0;
            }
            if ($tipo == "saidadev") {
                $opcao = "Devolução";
            } else {
                $opcao = "Estorno";
            }

            if ($tipo == "saidadev" and $estado_id_empresa == $parceiro_estado_id) { //devolucao de venda dentro do estado
                $cfop = 1202;
                $operacao = "DEVVENDA";
                $tipo_documento_nf = 0; //0-entrada 1 - saida
                $finalidade = 4; //devolucao
            } elseif ($tipo == "saidadev" and $estado_id_empresa != $parceiro_estado_id) { //devolucao de venda fora do estado
                $cfop = 2202;
                $operacao = "DEVVENDA";
                $tipo_documento_nf = 0; //0-entrada 1 - saida
                $finalidade = 4; //devolucao
            } elseif ($tipo == "saidaestorno" and $estado_id_empresa == $parceiro_estado_id) { //estorno de venda dento do estado
                $cfop = 1949;
                $operacao = "ESTORNOVENDA";
                $tipo_documento_nf = 0; //0-entrada 1 - saida
                $finalidade = 3; //ajuste
            } elseif ($tipo == "saidaestorno" and $estado_id_empresa != $parceiro_estado_id) { //estorno de venda fora do estado
                $cfop = 2949;
                $operacao = "ESTORNOVENDA";
                $tipo_documento_nf = 0; //0-entrada 1 - saida
                $finalidade = 3; //ajuste
            }
            // if (isset($_POST['credito'])) { //gerar credito para o cliente
            //     $credito = true;
            // } else {
            //     $credito = false;
            // }
            while ($linha = mysqli_fetch_assoc($consultar_nf_itens)) {
                $nf_id_item = $linha['cl_id'];
                $item_id = $linha['cl_item_id'];
                $quantidade_nf = $linha['cl_quantidade'];
                $descricao_item_nf = utf8_encode($linha['cl_descricao_item']);
                $valor_item_nf = ($linha['cl_valor_unitario']);

                $estoque = consulta_tabela($conecta, "tb_produtos", "cl_id", $item_id, "cl_estoque"); //pegar o estoque do produto 

                if (isset($_POST["$nf_id_item" . "dev"])) {
                    $item_qtd_dev = $_POST["$nf_id_item" . "dev"];
                    if ($item_qtd_dev != 0 or $item_qtd_dev != "") {
                        $check_item = $check_item + $item_qtd_dev;
                        $valor_total_item_dev = $valor_item_nf * $item_qtd_dev;

                        $valor_total_nf_dev += $valor_total_item_dev;
                        // $valor_credito = ($valor_credito + ($valor_item_nf / $item_qtd_dev)); //gerar credito para o cliente

                        $insert = "INSERT INTO `tb_nf_saida_item` ( `cl_data_movimento`, `cl_codigo_nf`, `cl_usuario_id`, `cl_serie_nf`, 
                        `cl_numero_nf`, `cl_item_ordem`, `cl_item_id`, `cl_descricao_item`, `cl_quantidade`, `cl_unidade`, `cl_valor_unitario`, `cl_valor_total`,
                         `cl_desconto`, `cl_referencia`, `cl_cst`, `cl_ncm`, `cl_cest`, `cl_base_icms`, `cl_aliq_icms`, `cl_icms`, `cl_base_icms_sbt`, `cl_icms_sbt`,
                          `cl_aliq_ipi`, `cl_ipi`, `cl_ipi_devolvido`, `cl_base_pis`, `cl_pis`, `cl_cst_pis`, `cl_base_cofins`, `cl_cofins`, `cl_cst_cofins`, `cl_base_iss`,
                           `cl_iss`, `cl_cfop`,  `cl_desconto_rat`, `cl_status`, `cl_id_delivery`, `cl_usuario_id_delivery`, `cl_tipo_item_delivery`, `cl_id_pai_delivery`, `cl_tipo_adicional_delivery`,
                            `cl_observacao_delivery`, `cl_habiltado_add_delivery`,`cl_gtin` ) 

                            SELECT  '$data_lancamento','$codigo_nf_novo', '$id_user_logado', '$serie', 
                        '$numero_nf_novo', cl_item_ordem, cl_item_id, cl_descricao_item, -$item_qtd_dev, cl_unidade, cl_valor_unitario, $valor_total_item_dev,
                         cl_desconto, cl_referencia, cl_cst, cl_ncm, cl_cest, cl_base_icms, cl_aliq_icms, cl_icms, cl_base_icms_sbt, cl_icms_sbt,
                          cl_aliq_ipi, cl_ipi, cl_ipi_devolvido, cl_base_pis, cl_pis, cl_cst_pis, cl_base_cofins, cl_cofins, cl_cst_cofins, cl_base_iss,
                           cl_iss,'$cfop', cl_desconto_rat, cl_status, cl_id_delivery, cl_usuario_id_delivery, cl_tipo_item_delivery, cl_id_pai_delivery, 
                           cl_tipo_adicional_delivery, cl_observacao_delivery, cl_habiltado_add_delivery,cl_gtin FROM tb_nf_saida_item where cl_id =$nf_id_item ";
                        $operacao_insert = mysqli_query($conecta, $insert);
                        if (!$operacao_insert) {
                            $erro_bd_item = true;
                        } else {
                            $motivo = utf8_decode("Devolução referente a $serie_nf $numero_nf");
                            $novo_id_inserido = mysqli_insert_id($conecta);

                            if (ajuste_estoque(
                                $conecta,
                                $data,
                                "$serie-$numero_nf_novo",
                                "ENTRADA",
                                $item_id,
                                $item_qtd_dev,
                                "1",
                                $parceiro_id,
                                $id_user_logado,
                                $forma_pagamento_id,
                                "0",
                                "0",
                                '0',
                                $motivo,
                                $codigo_nf_novo,
                                $novo_id_inserido,
                                ""
                            )) {
                                $novo_estoque = $estoque + $item_qtd_dev; //novo estoque do item
                                update_registro($conecta, "tb_produtos", "cl_id", $item_id, "", "", "cl_estoque", $novo_estoque);     //cancelar ajuste de estoque
                            };
                        }
                    }
                    if ($item_qtd_dev > $quantidade_nf) {
                        $erro_qtd_maxima = true;
                        $mensagem = "Favor, foi informado uma quantidade acima do permitido para a devolução ao item, 
                        $descricao_item_nf, quantidade vendida $quantidade_nf, quantidade devolvida $item_qtd_dev  ";
                        break;
                    }
                }
            }

            $insert = "INSERT INTO `tb_nf_saida` ( `cl_data_movimento`,`cl_finalidade`, `cl_codigo_nf`, 
            `cl_parceiro_id`, `cl_parceiro_avulso`, `cl_forma_pagamento_id`, `cl_numero_nf`, `cl_numero_venda`, `cl_serie_nf`, `cl_status_recebimento`,
             `cl_status_venda`, `cl_data_recebimento`, `cl_valor_bruto`, `cl_valor_liquido`, `cl_valor_desconto`, `cl_valor_frete`, `cl_tipo_frete_id`,
              `cl_valor_seguro`, `cl_valor_outras_despesas`, `cl_transportadora_id`, `cl_veiculo`, `cl_peso_bruto`, `cl_peso_liquido`, 
              `cl_usuario_id`, `cl_usuario_id_recebimento`, `cl_numero_nf_devolucao`,`cl_numero_nf_ref`, `cl_chave_acesso_referencia`,
               `cl_operacao`, `cl_cfop`, `cl_vendedor_id`, `cl_pedido_delivery`, `cl_status_pedido_delivery`, `cl_usuario_id_delivery`, 
               `cl_opcao_delivery`, `cl_valor_entrega_delivery`, `cl_data_pedido_delivery`, `cl_solicitar_cancelamento_delivery`, `cl_motivo_cancelamento_delivery`,
                `cl_endereco_entrega_delivery`, `cl_bairro_entrega_delivery`, `cl_cep_entrega_delivery`, `cl_numero_casa_delivery`, 
                `cl_tempo_entrega_pedido`,`cl_tipo_documento_nf` )


                SELECT  '$data_lancamento','$finalidade','$codigo_nf_novo', 
            cl_parceiro_id, cl_parceiro_avulso, cl_forma_pagamento_id, '$numero_nf_novo', cl_numero_venda, '$serie', '2',
             '1', cl_data_recebimento, cl_valor_bruto, $valor_total_nf_dev, 0, cl_valor_frete, '9',
              cl_valor_seguro, cl_valor_outras_despesas, cl_transportadora_id, cl_veiculo, cl_peso_bruto, cl_peso_liquido, 
             '$id_user_logado', '$id_user_logado',cl_numero_nf, cl_numero_nf, cl_chave_acesso,
                '$operacao','$cfop', '$id_user_logado', cl_pedido_delivery, cl_status_pedido_delivery, cl_usuario_id_delivery, 
               cl_opcao_delivery, cl_valor_entrega_delivery, cl_data_pedido_delivery, cl_solicitar_cancelamento_delivery, cl_motivo_cancelamento_delivery,
                cl_endereco_entrega_delivery, cl_bairro_entrega_delivery, 
                cl_cep_entrega_delivery, cl_numero_casa_delivery, cl_tempo_entrega_pedido, '$tipo_documento_nf' FROM tb_nf_saida where cl_id =$form_id ";
            $operacao_insert = mysqli_query($conecta, $insert);
            if (!$operacao_insert) {
                $erro_bd_nf = true;
            } else {

                update_registro($conecta, "tb_nf_saida", "cl_id", $form_id, "", "", "cl_numero_nf_devolucao", "$serie_nf $numero_nf_novo"); //atualizar na nf o numero da nf nova de devolução
                update_registro($conecta, "tb_serie", "cl_descricao", $serie, "", "", "cl_valor", $numero_nf_novo); //atualizar na nf o numero da nf nova de devolução

                if (isset($_POST['credito'])) { //atualizar o valor de credito ao cliente
                    $credito_atual = consulta_tabela($conecta, "tb_parceiros", "cl_id", $parceiro_id, "cl_valor_credito");
                    $valor_credito = $credito_atual + $valor_total_nf_dev;
                    update_registro($conecta, "tb_parceiros", "cl_id", $parceiro_id, "", "", "cl_valor_credito", $valor_credito);
                    $justificativa = utf8_decode("Crédito concedido atraves do/da $opcao $serie$numero_nf_novo");
                    $adicionar_historico_credito = adicionar_credito_parceiro($data, $parceiro_id, $valor_total_nf_dev, $justificativa, "ENTRADA");
                }
            }

            if ($check_item == 0) {
                $retornar["dados"] =  array("sucesso" => false, "title" => "Favor, informe a quantidade devolvida");
            } elseif ($erro_qtd_maxima == true) {
                $retornar["dados"] =  array("sucesso" => false, "title" => $mensagem);
            } elseif ($serie == "0") {
                $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("série"));
            } elseif ($erro_bd_nf == true or $erro_bd_item == true) {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("tentativa sem sucesso de gerar $opcao para $serie_nf $numero_nf");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                // Se tudo ocorreu bem, confirme a transação
            } else {
                $retornar["dados"] =  array("sucesso" => true, "title" => "$opcao realizada com sucesso, $serie $numero_nf_novo ");
                $mensagem = utf8_decode("$opcao realizada a $serie_nf $numero_nf, $opcao $serie $numero_nf_novo");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                mysqli_commit($conecta);
            }
        }

        if ($acao == "show") { //dados da nf
            $form_id = $_POST['form_id'];
            $select = "SELECT * from tb_nf_saida where cl_id = $form_id ";
            $consultar_nf = mysqli_query($conecta, $select);
            $linha = mysqli_fetch_assoc($consultar_nf);
            $parceiro_id = ($linha['cl_parceiro_id']);
            $data_movimento = formatDateB($linha['cl_data_movimento']);
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

            $parceiro = utf8_encode(consulta_tabela($conecta, "tb_parceiros", "cl_id", $parceiro_id, "cl_razao_social"));
            $descricao_forma_pagamento_venda = utf8_encode(consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $id_forma_pagamento_venda, "cl_descricao"));

            $informacao = array(
                "doc" => $numero_nf,
                "parceiro" => $parceiro,
                "valor_doc" => $valor_liquido_venda,
            );

            $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
        }
    } elseif ($tipo = "entradadev") {

        if ($acao == "create") {
            // Iniciar uma transação MySQL
            mysqli_begin_transaction($conecta);
            foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
                ${$name} = $value;
            }
            $form_id = $id;

            $codigo_nf = consulta_tabela($conecta, "tb_nf_entrada", "cl_id", $form_id, "cl_codigo_nf");
            $numero_nf = consulta_tabela($conecta, "tb_nf_entrada", "cl_id", $form_id, "cl_numero_nf");
            $serie_nf = consulta_tabela($conecta, "tb_nf_entrada", "cl_id", $form_id, "cl_serie_nf");
            $parceiro_id = consulta_tabela($conecta, "tb_nf_entrada", "cl_id", $form_id, "cl_parceiro_id");
            $desconto_nf = consulta_tabela($conecta, "tb_nf_entrada", "cl_id", $form_id, "cl_valor_desconto");
            $status_nf = consulta_tabela($conecta, "tb_nf_entrada", "cl_id", $form_id, "cl_status_nf"); //status da compra
            $parceiro_estado_id = consulta_tabela($conecta, "tb_parceiros", "cl_id", $parceiro_id, "cl_estado_id"); //estado do cliente

            $forma_pagamento_id = consulta_tabela($conecta, "tb_nf_entrada", "cl_id", $form_id, "cl_forma_pagamento_id");
            $select = "SELECT * from tb_nf_entrada_item where cl_codigo_nf = '$codigo_nf' ";
            $consultar_nf_itens = mysqli_query($conecta, $select);
            $check_item = 0; //verificar se foi preenchido pelo menos um campo para devolução
            $erro_bd_nf = false; //erro no banco de dados no insert do cabeçalho
            $erro_bd_item = false; //erro no banco de dados no insert dos itens
            $erro_qtd_maxima = false; //alert para quantidade maxima devolvida
            $valor_credito = 0;
            $valor_total_nf_dev = 0;
            $codigo_nf_novo = md5(uniqid(time())); //gerar um novo codigo para nf
            $numero_nf_novo = consulta_tabela($conecta, 'tb_serie', 'cl_descricao', $serie, "cl_valor");
            if ($numero_nf_novo != "") {
                $numero_nf_novo =  $numero_nf_novo + 1;
            } else {
                $numero_nf_novo = 0;
            }

            $opcao = "Devolução";
            $operacao = "DEVCOMPRA";


            if ($estado_id_empresa == $parceiro_estado_id) { //devolucao de compra dentro do estado
                $cfop = 5202;
                $operacao = "DEVCOMPRA";
                $tipo_documento_nf = 1; //-entrada 1 - saida
                $finalidade = 4; //devolucao
            } else { //fora  do estado
                $cfop = 6202;
                $tipo_documento_nf = 1;
                $finalidade = 4; //devolucao
            }

            while ($linha = mysqli_fetch_assoc($consultar_nf_itens)) {
                $nf_id_item = $linha['cl_id'];
                $item_id = $linha['cl_produto_id'];
                $quantidade_nf = $linha['cl_quantidade'];
                $descricao_item_nf = utf8_encode($linha['cl_descricao']);
                $valor_item_nf = ($linha['cl_valor_unitario']);

                $estoque = consulta_tabela($conecta, "tb_produtos", "cl_id", $item_id, "cl_estoque"); //pegar o estoque do produto 

                if (isset($_POST["$nf_id_item" . "dev"])) {
                    $item_qtd_dev = $_POST["$nf_id_item" . "dev"];
                    if ($item_qtd_dev != 0 or $item_qtd_dev != "") {
                        $check_item = $check_item + $item_qtd_dev;
                        $valor_total_item_dev = $valor_item_nf * $item_qtd_dev;

                        $valor_total_nf_dev += $valor_total_item_dev;
                        // $valor_credito = ($valor_credito + ($valor_item_nf / $item_qtd_dev)); //gerar credito para o cliente

                        $insert = "INSERT INTO `tb_nf_saida_item` ( `cl_data_movimento`, `cl_codigo_nf`, `cl_usuario_id`, `cl_serie_nf`, 
                        `cl_numero_nf`, `cl_item_id`, `cl_descricao_item`, `cl_quantidade`, `cl_unidade`, `cl_valor_unitario`, `cl_valor_total`,
                          `cl_referencia`, `cl_cst`, `cl_ncm`, `cl_cest`, `cl_base_icms`, `cl_aliq_icms`, `cl_icms`, `cl_base_icms_sbt`, `cl_icms_sbt`,
                          `cl_aliq_ipi`, `cl_ipi`, `cl_base_pis`, `cl_pis`, `cl_cst_pis`, `cl_base_cofins`, `cl_cofins`, `cl_cst_cofins`,
                           `cl_cfop`, `cl_status`,`cl_gtin` )

                            SELECT  '$data_lancamento','$codigo_nf_novo', '$id_user_logado', '$serie', 
                        '$numero_nf_novo', cl_produto_id, cl_descricao, $item_qtd_dev, cl_und, cl_valor_unitario, $valor_total_item_dev,
                          cl_referencia, cl_cst_icms, cl_ncm, cl_cest, cl_bc_icms, cl_aliq_icms, cl_valor_icms, cl_bc_icms_sub, cl_valor_icms_sub,
                          cl_aliq_ipi, cl_valor_ipi, cl_bc_pis, cl_valor_pis, cl_cst_pis, cl_bc_cofins, cl_valor_cofins, cl_cst_cofins,
                         '$cfop', '1',cl_gtin FROM tb_nf_entrada_item where cl_id =$nf_id_item ";
                        $operacao_insert = mysqli_query($conecta, $insert);
                        if (!$operacao_insert) {
                            $erro_bd_item = true;
                        } else {
                            $motivo = utf8_decode("Devolução referente a $serie_nf $numero_nf");
                            $novo_id_inserido = mysqli_insert_id($conecta);

                            if (ajuste_estoque(
                                $conecta,
                                $data,
                                "$serie-$numero_nf_novo",
                                "SAIDA",
                                $item_id,
                                $item_qtd_dev,
                                "1",
                                $parceiro_id,
                                $id_user_logado,
                                $forma_pagamento_id,
                                "0",
                                "0",
                                '0',
                                $motivo,
                                $codigo_nf_novo,
                                $novo_id_inserido,
                                ""
                            )) {
                                $novo_estoque = $estoque - $item_qtd_dev; //novo estoque do item
                                update_registro($conecta, "tb_produtos", "cl_id", $item_id, "", "", "cl_estoque", $novo_estoque);     //cancelar ajuste de estoque
                            };
                        }
                    }
                    if ($item_qtd_dev > $quantidade_nf) {
                        $erro_qtd_maxima = true;
                        $mensagem = "Favor, foi informado uma quantidade acima do permitido para a devolução ao item, 
                        $descricao_item_nf, quantidade vendida $quantidade_nf, quantidade devolvida $item_qtd_dev  ";
                        break;
                    }
                }
            }

            $insert = "INSERT INTO `tb_nf_saida` ( `cl_data_movimento`,`cl_finalidade`, `cl_codigo_nf`, 
            `cl_parceiro_id`, `cl_forma_pagamento_id`, `cl_numero_nf`, `cl_serie_nf`, `cl_status_recebimento`,
             `cl_status_venda`,`cl_valor_bruto`, `cl_valor_liquido`, `cl_tipo_frete_id`,`cl_usuario_id`,`cl_numero_nf_devolucao`,`cl_numero_nf_ref`, `cl_chave_acesso_referencia`,
               `cl_operacao`, `cl_cfop`, `cl_vendedor_id`,`cl_tipo_documento_nf` )


                SELECT  '$data_lancamento','$finalidade','$codigo_nf_novo', 
            cl_parceiro_id, cl_forma_pagamento_id, '$numero_nf_novo', '$serie', '2',
             '1','$valor_total_nf_dev','$valor_total_nf_dev','9','$id_user_logado',cl_numero_nf, cl_numero_nf, cl_chave_acesso,
                '$operacao','$cfop', '$id_user_logado', '$tipo_documento_nf' FROM tb_nf_entrada where cl_id =$form_id ";
            $operacao_insert = mysqli_query($conecta, $insert);
            if (!$operacao_insert) {
                $erro_bd_nf = true;
            } else {

                update_registro($conecta, "tb_nf_saida", "cl_id", $form_id, "", "", "cl_numero_nf_devolucao", "$serie_nf $numero_nf_novo"); //atualizar na nf o numero da nf nova de devolução
                update_registro($conecta, "tb_serie", "cl_descricao", $serie, "", "", "cl_valor", $numero_nf_novo); //atualizar na nf o numero da nf nova de devolução
            }

            if ($check_item == 0) {
                $retornar["dados"] =  array("sucesso" => false, "title" => "Favor, informe a quantidade devolvida");
            } elseif ($erro_qtd_maxima == true) {
                $retornar["dados"] =  array("sucesso" => false, "title" => $mensagem);
            } elseif ($serie == "0") {
                $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("série"));
            } elseif ($status_nf != 1) {
                $retornar["dados"] =  array("sucesso" => false, "title" => 'Não é possível gerar uma devolução para esta nota, pois ela precisa estar concluída');
            } elseif ($erro_bd_nf == true or $erro_bd_item == true) {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("tentativa sem sucesso de gerar $opcao para $serie_nf $numero_nf");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                // Se tudo ocorreu bem, confirme a transação
            } else {
                $retornar["dados"] =  array("sucesso" => true, "title" => "$opcao realizada com sucesso, $serie $numero_nf_novo ");
                $mensagem = utf8_decode("$opcao realizada a $serie_nf $numero_nf, $opcao $serie $numero_nf_novo");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                mysqli_commit($conecta);
            }
        }

        if ($acao == "show") { //dados da nf
            $form_id = $_POST['form_id'];
            $select = "SELECT * from tb_nf_entrada where cl_id = $form_id ";
            $consultar_nf = mysqli_query($conecta, $select);
            $linha = mysqli_fetch_assoc($consultar_nf);
            $parceiro_id = ($linha['cl_parceiro_id']);
            $data_entrada = formatDateB($linha['cl_data_entrada']);
            $data_emissao = formatDateB($linha['cl_data_emissao']);
            $numero_nf = ($linha['cl_numero_nf']);
            $valor_total_nota = ($linha['cl_valor_total_nota']);
            // $observacao = utf8_encode($linha['cl_observacao']);
            // $id_forma_pagamento_venda = ($linha['cl_forma_pagamento_id']);
            // $vendedor_id_venda = ($linha['cl_vendedor_id']);
            // $desconto_venda_real = ($linha['cl_valor_desconto']);
            // 
            // $sub_total_venda = ($linha['cl_valor_bruto']);

            // $serie_nf = ($linha['cl_serie_nf']);
            // $status_venda = ($linha['cl_status_venda']);
            // $status_recebimento = ($linha['cl_status_recebimento']);

            $parceiro = utf8_encode(consulta_tabela($conecta, "tb_parceiros", "cl_id", $parceiro_id, "cl_razao_social"));
            //  $descricao_forma_pagamento_venda = utf8_encode(consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $id_forma_pagamento_venda, "cl_descricao"));

            $informacao = array(
                "doc" => $numero_nf,
                "parceiro" => $parceiro,
                "valor_doc" => $valor_total_nota,
            );

            $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
        }
    }
    echo json_encode($retornar);
}
