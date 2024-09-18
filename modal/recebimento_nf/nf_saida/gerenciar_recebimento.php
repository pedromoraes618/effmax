<?php

if (isset($_GET['historico_recebimento_nf'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $form_id = isset($_GET['form_id']) ? $_GET['form_id'] : '';
    if ($form_id != '') {
        $codigo_nf = consulta_tabela($conecta, 'tb_nf_saida', 'cl_id', $form_id, 'cl_codigo_nf'); //buscar o codigo nf que está na venda
        $query = "SELECT user.cl_usuario, lcf.*,fpg.cl_descricao as fpagamento,sts.cl_descricao as statusl, lcf.cl_status_id as statusid
         FROM tb_lancamento_financeiro as lcf
    LEFT join tb_forma_pagamento as fpg on fpg.cl_id = lcf.cl_forma_pagamento_id 
    LEFT join tb_status_recebimento as sts on sts.cl_id = lcf.cl_status_id
    LEFT join tb_users as user on user.cl_id = lcf.cl_usuario_id
     where cl_codigo_nf ='$codigo_nf'";
        $consulta  = mysqli_query($conecta, $query);
        if (!$consulta) {
            die("Falha no banco de dados " . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consulta); //quantidade de registros
        }
    } else {
        $qtd = 0;
    }
}
if (isset($_POST['recebimento_nf_saida'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $acao = $_POST['acao'];
    $nf_id = $_POST['nf_id'];

    $serie_venda = verifcar_descricao_serie($conecta, "12"); //verificar qual seria usado na venda
    $nf_atual = consultar_valor_serie($conecta, "12"); //verificar a numeração da venda atual
    $alterar_fpg = consultar_valor_serie($conecta, "16"); //verificar a numeração da venda atual
    $cliente_avulso_id = verficar_paramentro($conecta, "tb_parametros", "cl_id", "8"); //verificar o id do cliente avulso
    //$classficacao_financeiro_id_recebida = verficar_paramentro($conecta, "tb_parametros", "cl_id", "11"); //classificação financeira recebida
    // $classficacao_financeiro_id_a_receber = verficar_paramentro($conecta, "tb_parametros", "cl_id", "51"); //classificação financeira faturada
    $contabilizacao_cx = verficar_paramentro($conecta, "tb_parametros", "cl_id", "56"); //verificar tipo de contabilização se é pela data do caixa ou pela data avulsa

    $id_user_logado = verifica_sessao_usuario();
    $nome_usuario_logado_2 = consulta_tabela($conecta, "tb_users", "cl_id", $id_user_logado, "cl_usuario");

    //consultar vendedor
    $select = "SELECT * from tb_forma_pagamento where cl_ativo ='S' ";
    $consultar_forma_pagamento = mysqli_query($conecta, $select);
    $consultar_forma_pagamento_update = mysqli_query($conecta, $select);

    $select = "SELECT nf.*, cl_serie_nf, cl_status_recebimento,cl_parceiro_id,cl_codigo_nf,
    cl_numero_nf,cl_valor_liquido,cl_forma_pagamento_id,prc.cl_razao_social from tb_nf_saida as nf inner join
     tb_parceiros as prc on prc.cl_id = nf.cl_parceiro_id where nf.cl_id = $nf_id";
    $consultar_nf = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consultar_nf);
    $serie_nf = ($linha['cl_serie_nf']);
    $numero_nf = ($linha['cl_numero_nf']);
    $forma_pagamento_nf = ($linha['cl_forma_pagamento_id']);
    $parceiro_id = ($linha['cl_parceiro_id']);
    $codigo_nf = ($linha['cl_codigo_nf']);
    $cliente = utf8_encode($linha['cl_razao_social']);
    $status_recebiemento = $linha['cl_status_recebimento'];

    $valor_liquido = ($linha['cl_valor_liquido']); //valor liquido
    $valor_adiantamento = $linha['cl_valor_adto']; //valor adiantamento
    $valor_credito = $linha['cl_valor_credito']; //valor credito

    $valor_a_receber = $valor_liquido - $valor_adiantamento - $valor_credito;
    if ($acao == "show") {
        $informacao = array(
            "serie_nf" => $serie_nf,
            "numero_nf" => $numero_nf,
            "cliente" => $cliente,
            "valor_liquido" => $valor_liquido,
            "forma_pagamento" => $forma_pagamento_nf,
            "valor_credito" => $valor_credito,
            "valor_a_receber" => $valor_a_receber,
            "valor_adiantamento" => $valor_adiantamento,
        );
        $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
    }

    if ($acao == "create") {
        mysqli_begin_transaction($conecta);
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            // Remover aspas simples usando str_replace
            ${$name} = str_replace("'", "", ${$name});
        }

        $valor_recebido = 0;
        $status = array("status" => true);
        if ($status_recebiemento == "2") { //RECEBIDO
            $retornar["dados"] = array("sucesso" => false, "title" => "Essa venda já foi recebida, não é possivel realizar o recebimento novamente");
        } elseif ($alterar_fpg == "S" and $forma_pagamento == "0") { //parametro setado para indicar se o usuario pode alterar a forma de pagamento pela tela de recebimento
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("forma de pagamento"));
        } elseif (empty($dt_recebimento)) { //parametro setado para indicar se o usuario pode alterar a forma de pagamento pela tela de recebimento
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("data do recebimento"));
        } else {

            $conta_financeira = consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $forma_pagamento, "cl_conta_financeira");
            $caixa =  verifica_caixa_financeiro($conecta, $dt_recebimento, $conta_financeira);
            if (($caixa['resultado']) == "" and $dt_recebimento != "") { //verificar se o caixa já foi aberto
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("VAZIO")); //alertar o usuario que o caixa ainda não foi aberto
            } elseif ($caixa['status'] == "fechado" and $dt_recebimento != "") {
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("FECHADO")); //alertar o usuario que o caixa está fechado
            } else {
                while ($linha = mysqli_fetch_assoc($consultar_forma_pagamento)) {
                    $id_fpg = ($linha['cl_id']);
                    $valor = isset($_POST["$id_fpg"]) ? $_POST["$id_fpg"] : 0; //valor informado pelo usuario na forma de pagamento
                    if ($valor > 0) {
                        if (verificaVirgula($valor)) { //verificar se tem virgula
                            $valor = formatDecimal($valor); // formatar virgula para ponto
                        }
                        $valor_recebido = $valor + $valor_recebido;
                    }
                }

                if ($valor_recebido > $valor_a_receber or $valor_recebido <= 0) {
                    $retornar["dados"] = array("sucesso" => false, "title" => "O valor informado " . real_format($valor_recebido) . ", difere do valor a receber " . real_format($valor_a_receber) . "
                     o que impossibilita o recebimento, favor, verifique");
                } elseif ($valor_recebido < $valor_a_receber) { //valor adiantamento //valor não integral
                    while ($linha = mysqli_fetch_assoc($consultar_forma_pagamento_update)) {
                        $id_fpg = ($linha['cl_id']);
                        $valor = isset($_POST["$id_fpg"]) ? $_POST["$id_fpg"] : ''; //valor informado pelo usuario na forma de pagamento
                        if ($valor != '') {
                            if (verificaVirgula($valor)) { //verificar se tem virgula
                                $valor = formatDecimal($valor); // formatar virgula para ponto
                            }

                            $descricao = utf8_decode("Adiantamento referente a $serie_nf $numero_nf");
                            $doc = "Adto " . $numero_nf;
                            if ($valor != 0) {
                                $recebimento = recebimento_nf(
                                    $conecta,
                                    $data_lancamento,
                                    $dt_recebimento,
                                    $dt_recebimento,
                                    "0",
                                    $id_fpg,
                                    $parceiro_id,
                                    "RECEITA",
                                    "2",
                                    $valor,
                                    $valor,
                                    0,
                                    0,
                                    0,
                                    0,
                                    $doc,
                                    0,
                                    $descricao,
                                    "",
                                    $numero_nf,
                                    $serie_nf,
                                    $codigo_nf,
                                    "",
                                    ""
                                ); //lancar no financeiro o recebimento
                                if ($recebimento == false) {
                                    $status = array("status" => false, "mensagem" => "Erro ao realizar o recebimento, favor, verifique com o suporte");
                                    break;
                                }
                            }
                        }
                    }
                    if ($status['status'] == true) {
                        $retornar["dados"] = array("sucesso" => true, "title" => "Recebimento do adiantamento no valor de " . real_format($valor_recebido) . " realizado com sucesso");

                        $mensagem = utf8_decode("Recebimento do adiantamento no valor de $valor_recebido realizado com sucesso da $serie_nf $numero_nf");
                        registrar_log($conecta, $nome_usuario_logado_2, $data, $mensagem);

                        $valor_adiantamento_atualizado = $valor_adiantamento + $valor_recebido;
                        update_registro($conecta, 'tb_nf_saida', 'cl_id', $nf_id, '', '', 'cl_valor_adto', $valor_adiantamento_atualizado); //atualizar o valor do adiantamento
                        mysqli_commit($conecta);
                    } else {
                        mysqli_rollback($conecta);
                        $mensagem = utf8_decode("Tentativa sem sucesso de realizar o recebimento do adiantamento no valor de " . real_format($valor_recebido) . " da $serie_nf $numero_nf");
                        registrar_log($conecta, $nome_usuario_logado_2, $data, $mensagem);
                    }
                } else { //adicionar no financeiro // valor integral a receber
                    while ($linha = mysqli_fetch_assoc($consultar_forma_pagamento_update)) {
                        $id_fpg = ($linha['cl_id']);
                        $valor = isset($_POST["$id_fpg"]) ? $_POST["$id_fpg"] : ''; //valor informado pelo usuario na forma de pagamento
                        if ($valor != '') {
                            if (verificaVirgula($valor)) { //verificar se tem virgula
                                $valor = formatDecimal($valor); // formatar virgula para ponto
                            }

                            $descricao = utf8_decode("Recebimento referente a $serie_nf $numero_nf");
                            $doc = $numero_nf;
                            if ($valor != 0) {
                                $recebimento = recebimento_nf(
                                    $conecta,
                                    $data_lancamento,
                                    $dt_recebimento,
                                    $dt_recebimento,
                                    "0",
                                    $id_fpg,
                                    $parceiro_id,
                                    "RECEITA",
                                    "2",
                                    $valor,
                                    $valor,
                                    0,
                                    0,
                                    0,
                                    0,
                                    $doc,
                                    0,
                                    $descricao,
                                    "",
                                    $numero_nf,
                                    $serie_nf,
                                    $codigo_nf,
                                    "",
                                    ""
                                ); //lancar no financeiro o recebimento
                                if ($recebimento == false) {
                                    $status = array("status" => false, "mensagem" => "Erro ao realizar o recebimento, favor, verifique com o suporte");
                                    break;
                                }
                            }
                        }
                    }
                    if ($status['status'] == true) {
                        $retornar["dados"] = array("sucesso" => true, "title" =>  "Recebimento realizado com sucesso");
                        $mensagem = utf8_decode("Recebimento integral do valor de $valor_recebido realizado com sucesso da $serie_nf $numero_nf");
                        registrar_log($conecta, $nome_usuario_logado_2, $data, $mensagem);

                        update_status_nf($conecta, "2", $data_lancamento, $id_user_logado, $nf_id, $forma_pagamento); //atualizar o status da venda para recebido
                        mysqli_commit($conecta);
                    } else {
                        mysqli_rollback($conecta);
                        $mensagem = utf8_decode("Tentativa sem sucesso de realizar o recebimento integral do valor de $valor_recebido da $serie_nf $numero_nf");
                        registrar_log($conecta, $nome_usuario_logado_2, $data, $mensagem);
                    }
                }
            }
        }
    }
    if ($acao == 'create_faturado') { //recebimento de venda forma de pagamento tipo faturado

        $forma_pagamento_selecionado = $_POST['forma_pagamento'];
        $conta_financeira_selecionado = $_POST['conta_financeira'];

        $valor_entrada = $_POST['valor_entrada'];
        if (verificaVirgula($valor_entrada)) { //verificar se tem virgula
            $valor_entrada = formatDecimal($valor_entrada); // formatar virgula para ponto
        }

        $forma_pagamento_entrada_selecionado = $_POST['forma_pagamento_entrada'];
        $conta_financeira_entrada_selecionado = $_POST['conta_financeira_entrada'];

        $conta_financeira_fpg = consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $forma_pagamento_selecionado, "cl_conta_financeira"); //conta financeira que está no cadastro da forma de pagamento
        $conta_financeira_fpg_entrada = consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $forma_pagamento_entrada_selecionado, "cl_conta_financeira"); //conta financeira que está no cadastro da forma de pagamento
        $tipo = "RECEITA";

        $valor_total_provisionado = 0;
        $mensagem_validar_data = "";

        // Inicia uma transação
        mysqli_begin_transaction($conecta);
        $erro = false;


        if ($conta_financeira_selecionado == "0") { //se não for selecionado a conta financeira na tela de recebimento será atribuido a conta que está no cadastro da forma de pagamento
            $conta_financeira_selecionado = $conta_financeira_fpg;
        }
        if ($conta_financeira_entrada_selecionado == "0") { //se não for selecionado a conta financeira na tela de recebimento será atribuido a conta que está no cadastro da forma de pagamento
            $conta_financeira_entrada_selecionado = $conta_financeira_fpg_entrada;
        }

        if ($valor_entrada > 0 and $forma_pagamento_entrada_selecionado != "0") {
            $caixa =  verifica_caixa_financeiro($conecta, $data_lancamento, $conta_financeira_entrada_selecionado);
            if (($caixa['resultado']) == "" and $data_lancamento != "") { //verificar se o caixa já foi aberto
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("VAZIO")); //alertar o usuario que o caixa ainda não foi aberto
                echo json_encode($retornar);
                exit;
            } elseif ($caixa['status'] == "fechado" and $data_lancamento != "") {
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("FECHADO")); //alertar o usuario que o caixa está fechado
                echo json_encode($retornar);
                exit;
            }
        }

        for ($i = 1; $i < 12; $i++) {
            if (isset($_POST["$i" . "valor"])) {
                $valor_parcela = $_POST["$i" . "valor"];
                if ($valor_parcela > 0) {
                    $doc = $_POST["$i" . "doc"];
                    $data_vencimento = $_POST["$i" . "dtvencimento"];

                    if (verificaVirgula($valor_parcela)) { //verificar se tem virgula
                        $valor_parcela = formatDecimal($valor_parcela); // formatar virgula para ponto
                    }

                    if ($data_vencimento == "") {
                        $mensagem_validar_data = "Data de vencimento da $i parcela não foi informada";
                        $erro = true;
                        break;
                    }
                    if ($valor_entrada > 0 and $i == 1) {
                        $descricao = utf8_decode("Entrada $doc referente a $serie_nf $numero_nf");
                        $status = 2; //recebido
                        $forma_pagamento = $forma_pagamento_entrada_selecionado;
                        $conta_financeira = $conta_financeira_entrada_selecionado;
                        $data_pagamento = $data_lancamento;
                        $classficacao_financeiro_id = consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $forma_pagamento_entrada_selecionado, "cl_classificao_id"); //classifiacação financecira da forma de pagamento

                    } else {
                        $descricao = utf8_decode("Duplicata $doc referente a $serie_nf $numero_nf");
                        $status = 1; //a receber
                        $forma_pagamento = $forma_pagamento_selecionado;
                        $conta_financeira = $conta_financeira_selecionado;
                        $data_pagamento = null;
                        $classficacao_financeiro_id = consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $forma_pagamento_selecionado, "cl_classificao_id"); //classifiacação financecira da  forma de pagamento

                    }
                    if (!recebimento_nf(
                        $conecta,
                        $data_lancamento,
                        $data_vencimento,
                        $data_pagamento,
                        $conta_financeira,
                        $forma_pagamento,
                        $parceiro_id,
                        $tipo,
                        $status,
                        $valor_parcela,
                        $valor_parcela,
                        0,
                        0,
                        0,
                        0,
                        $doc,
                        $classficacao_financeiro_id,
                        $descricao,
                        "",
                        $numero_nf,
                        $serie_nf,
                        $codigo_nf,
                        "",
                        ""
                    )) {
                        $erro = true;
                    }
                    $valor_total_provisionado += $valor_parcela;
                }
            }
        }

        $valor_total_provisionado = number_format($valor_total_provisionado, 2); //arendonar para duas casa decimais
        if ($valor_total_provisionado == 0) { //verifica se foi infomado as parcelas
            $retornar["dados"] = array("sucesso" => false, "title" => "Favor, Informe os valores das parcelas");
            $erro = true;
        } elseif ($mensagem_validar_data != "") { //verifica se alguma parcela não foi informado a data
            $retornar["dados"] = array("sucesso" => false, "title" => $mensagem_validar_data);
            $erro = true;
        } elseif ($forma_pagamento_selecionado == "0") { //verifica se foi informado a forma de pagamento da parcela
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("forma de pagamento"));
            $erro = true;
        } elseif ($valor_entrada > 0 and $forma_pagamento_entrada_selecionado == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("forma de pagamento da entrada"));
            $erro = true;
        } elseif ($valor_total_provisionado != $valor_a_receber) {
            $retornar["dados"] = array("sucesso" => false, "title" => "O total do valor informado " .
                real_format($valor_total_provisionado) . ", é diferente do valor a receber " . real_format($valor_a_receber) . ", favor, verifique");
            $erro = true;
        } elseif (!update_status_nf($conecta, "2", $data_lancamento, $id_user_logado, $nf_id, $forma_pagamento_selecionado)) {
            $erro = true;
        } else {
            $retornar["dados"] = array("sucesso" => true, "title" => "Recebimento realizado com sucesso");
        }

        if ($erro) {
            mysqli_rollback($conecta); // Desfaz a transação em caso de err
            $mensagem = utf8_decode("Tentativa sem sucesso de receber a $serie_nf $numero_nf");
            registrar_log($conecta, $nome_usuario_logado_2, $data, $mensagem);
        } else {
            $mensagem = utf8_decode("Recebimento realizado com sucesso da $serie_nf $numero_nf");
            registrar_log($conecta, $nome_usuario_logado_2, $data, $mensagem);
            mysqli_commit($conecta); // Confirma a transação se tudo ocorreu bem
        }
    }

    if ($acao == "previa_parcelas") { //previo de parcelas forma de pagamento tipo faturado
        $n_pacelas = $_POST['n_pacelas'];
        $primeira_parcela = $_POST['primeira_parcela'];
        $intervalo = $_POST['intervalo'];
        $forma_pgt_id = $_POST['forma_pgt_id'];
        $valor_entrada = $_POST['valor_entrada'];
        if (verificaVirgula($valor_entrada)) { //verificar se tem virgula
            $valor_entrada = formatDecimal($valor_entrada); // formatar virgula para ponto
        }

        $verifica_tipo_fpg = consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $forma_pgt_id, "cl_tipo_pagamento_id");
        $n_pacelas_fpg = consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $forma_pgt_id, "cl_numero_parcela");
        $intervalo_pfg = consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $forma_pgt_id, "cl_intervalo_parcela");



        if (($n_pacelas == "" and $verifica_tipo_fpg != '3') or ($n_pacelas == "" and $verifica_tipo_fpg == '3' and ($n_pacelas_fpg == 0 or $intervalo_pfg == 0))) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Favor, Informe o número de Parcelas");
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

            $valor_liquido_parcela = $valor_a_receber - $valor_entrada;
            // Calcula o valor da parcela dividindo o valor total pela quantidade de parcelas
            $valor_parcela = number_format($valor_liquido_parcela / $n_pacelas, 2, '.', '');


            for ($i = 0; $i < $n_pacelas; $i++) {
                // Adiciona a parcela ao array
                if ($i == 0 and $valor_entrada > 0) {
                    $doc = $numero_nf . "/" . "E";
                    $parcela = array(
                        'dtvencimento' => date('Y-m-d'),
                        'valor' => $valor_entrada,
                        'doc' => $doc
                    );
                    // Adiciona o valor de entrada
                    $informacao[] = $parcela;
                }

                $doc = $numero_nf . "/" . ($i + 1);
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
                $diferenca = $valor_a_receber - $valor_total_parcelas;

                // Atribui a diferença à última parcela
                $informacao[$total_parcelas - 1]['valor'] += $diferenca;
            }


            $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
        }
    }


    echo json_encode($retornar);
}


// $select = "SELECT * from tb_forma_pagamento where cl_ativo ='S' and cl_avista ='S'";
// $consultar_forma_pagamento = mysqli_query($conecta, $select);
// $consultar_forma_pagamento_2 = mysqli_query($conecta, $select);

// $select = "SELECT * from tb_conta_financeira  ";
// $consultar_conta_financeira = mysqli_query($conecta, $select);
// $consultar_conta_financeira_2 = mysqli_query($conecta, $select);
