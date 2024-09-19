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

if (isset($_POST['nf_saida'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $acao = $_POST['acao'];
    $nf_atual = consultar_valor_serie($conecta, "12"); //verificar a numeração da venda atual
    $cliente_avulso_id = verficar_paramentro($conecta, "tb_parametros", "cl_id", "8"); //verificar o id do cliente avulso
    $estado_empresa = consulta_tabela($conecta, "tb_empresa", "cl_id", "1", "cl_estado"); //verificar o estado do emitente
    $estado_empresa = consulta_tabela($conecta, "tb_estados", "cl_uf", $estado_empresa, "cl_id"); //verificar o estado do emitente
    $crt_empresa = consulta_tabela($conecta, "tb_parametros", "cl_id", 57, "cl_valor");

    $id_usuario_logado = verifica_sessao_usuario(); //pegar a sessão do usuario
    $nome_usuario_logado = (consulta_tabela($conecta, "tb_users", "cl_id", $id_usuario_logado, "cl_usuario"));


    if ($acao == "gerar_nf") {
        // Iniciar uma transação MySQL
        mysqli_begin_transaction($conecta);

        $serie_nf = $_POST['serie_nf'];
        $cfop = isset($_POST['cfop']) ? $_POST['cfop'] : 0;
        $primeiroNumeroCfop = substr($cfop, 0, 1); // Extrai o primeiro caractere (índice 0)

        $nf = $_POST['nf'];
        $nf = json_decode($nf, true); //recuperar valor do array javascript decodificando o json
        $qtdNfe = count($nf);

        $nf_id_fpg_chek = null; //verificar se as notas selecionadas estão com a mesma forma de pagamento
        $nf_id_prc_chek = null; //verificar se as notas selecionadas estão com o mesmo parceiro
        $nf_id_cfop_check = null;

        $erro = false;

        $nf_atual = consulta_tabela($conecta, "tb_serie", "cl_descricao", $serie_nf, "cl_valor"); //verificar a numeração da venda atual
        $nf_nova = $nf_atual + 1; //proxima nf
        $mensagem = '';
        if ($qtdNfe == 0) {
            $erro = true;
            $mensagem = 'Favor, selecione a(s) nota(s)';
        } elseif ($qtdNfe == 2) {
            $erro = true;
            $mensagem = 'Favor, selecione apenas uma nota';
        } else {
            foreach ($nf as $chave => $nf_id) {
                $nf_id_fpg = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $nf_id, "cl_forma_pagamento_id");
                $nf_id_prc = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $nf_id, "cl_parceiro_id");
                $serie_nf_vnd_atual = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $nf_id, "cl_serie_nf");

                //   $nf_id_prc = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $nf_id, "cl_operacao");

                $nf_id_prc_estado = consulta_tabela($conecta, "tb_parceiros", "cl_id", $nf_id_prc, "cl_estado_id"); //estado do cliente
                $prc_cnpj_cfp = consulta_tabela($conecta, "tb_parceiros", "cl_id", $nf_id_prc, "cl_cnpj_cpf");

                if ($nf_id_fpg != $nf_id_fpg_chek and $nf_id_fpg_chek != null) {
                    $erro = true;
                    $mensagem = "Todas as notas selecionadas precisam está com a mesma forma de pagamento para proseguir, favor, verifique";
                    break;
                } elseif ($serie_nf == $serie_nf_vnd_atual) {
                    $erro = true;
                    $mensagem = "Não é possivel gerar essa nota para a mesma série $serie_nf";
                    break;
                } elseif ($prc_cnpj_cfp == "" and $serie_nf != "NFC") {
                    $erro = true;
                    $mensagem = "Operação cancelada, cliente está sem o cpf/cnpj, favor, verifique em seu cadastro";
                    break;
                } elseif ($prc_cnpj_cfp != "" and $serie_nf == "NFC") {
                    $erro = true;
                    $mensagem = "Operação cancelada, a venda não pode conter cliente para a série $serie_nf";
                    break;
                } elseif ($nf_id_prc != $nf_id_prc_chek and $nf_id_prc_chek != null) {
                    $erro = true;
                    $mensagem = "Todas as notas selecionadas precisam está com o mesmo cliente, para proseguir, favor, verifique";
                    break;
                } else {
                    $nf_id_fpg_chek = $nf_id_fpg; //será atribuido ao variavel nf_id_fpg_check a forma de pagameno anterior verificada
                    $nf_id_prc_chek = $nf_id_prc;
                }
            }

            if ($erro == false and ($estado_empresa == $nf_id_prc_estado and $primeiroNumeroCfop != 5) and (!empty($cfop)  and $cfop != 0)) {
                $erro = true;
                $mensagem = "O cfop informado está invalido, o cliente sendo dentro do estado, o cfop deve ser inciado com o número 5, favor, verifique";
            } elseif ($erro == false and ($estado_empresa != $nf_id_prc_estado and $primeiroNumeroCfop != 6) and (!empty($cfop)  and $cfop != 0)) {
                $erro = true;
                $mensagem = "O cfop informado está invalido, o cliente sendo fora do estado, o cfop deve ser inciado com o número 6, favor, verifique   ";
            }

            if ($erro == false) {
                $gerar_nf =  gerar_nf($conecta, $nf, $cfop, $serie_nf); //gerar nota

                if (!$gerar_nf) {
                    $retornar["dados"] = array("sucesso" => false, "title" => "Erro, não foi possivel gerar a $serie_nf, favor, verifique com o suporte");
                    echo json_encode($retornar);
                    exit;
                }
            }
        }

        if ($erro == true) {
            $retornar["dados"] = array("sucesso" => false, "title" => $mensagem);
            // Se ocorrer um erro, reverta a transação
            mysqli_rollback($conecta);
            $mensagem = utf8_decode("Tentativa sem sucesso do usuário $nome_usuario_logado gerar a nota $serie_nf");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        } else {
            $retornar["dados"] = array("sucesso" => true, "title" => "$serie_nf$nf_nova gerada com sucesso");
            $mensagem = utf8_decode("$nome_usuario_logado gerou a $serie_nf$nf_nova");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            // Se tudo ocorreu bem, confirme a transação
            mysqli_commit($conecta);
        }
    } elseif ($acao == 'fiscal') {
        $id_nf = $_POST['nf_id'];
        $sub_acao = $_POST['subacao'];
        $ambiente = verficar_paramentro($conecta, "tb_parametros", "cl_id", "35"); // 1 - homologacao 2 - producao
        $opcao_nfs = verficar_paramentro($conecta, "tb_parametros", "cl_id", "133"); // 1 - nacional 2 - prefeitura

        if ($ambiente == "1") {
            $server = verficar_paramentro($conecta, "tb_parametros", "cl_id", "60");
            $login =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "58"); //token para homologacao
            $server_danfe  = verficar_paramentro($conecta, "tb_parametros", "cl_id", "68");
        } elseif ($ambiente == "2") {
            $server =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "61");
            $login = verficar_paramentro($conecta, "tb_parametros", "cl_id", "59"); //token para producao
            $server_danfe =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "69");
        } else {
            $server = "";
            $login = "";
        }
        $password = "";

        $select = "SELECT * from tb_nf_saida where cl_id = $id_nf";
        $consultar_nf = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consultar_nf);
        $numero_nf = ($linha['cl_numero_nf']);
        $serie_nf = ($linha['cl_serie_nf']);
        $codigo_nf = ($linha['cl_codigo_nf']);
        $parceiro_id = ($linha['cl_parceiro_id']);
        $finalidade_id = ($linha['cl_finalidade']);
        $tipo_documento = ($linha['cl_tipo_documento_nf']);
        $forma_pagamento = ($linha['cl_forma_pagamento_id']);
        $tipo_forma_pagamento = (consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $forma_pagamento, "cl_tipo_pagamento_nf"));
        if ($tipo_forma_pagamento == 0) {
            $tipo_forma_pagamento = 99; //outros
        }
        $cfop = ($linha['cl_cfop']);
        $primeiroNumeroCfop = substr($cfop, 0, 1); // Extrai o primeiro caractere (índice 0)
        if ($primeiroNumeroCfop != 6) { //operação interna // nfc
            $local_destino = 1;
        } else { //operação externa
            $local_destino = 2;
        }
        $descricao_cfop = utf8_encode(consulta_tabela($conecta, "tb_cfop", "cl_codigo_cfop", $cfop, "cl_desc_cfop"));
        $frete = ($linha['cl_tipo_frete_id']);


        $desconto_nota = $linha['cl_valor_desconto'];
        $observacao = utf8_encode($linha['cl_observacao']);
        $informacao_adicional_servico = ($linha['cl_observacao']);
        $numero_pedido = $linha['cl_numero_pedido'];

        $visualizar_duplicata = $linha['cl_visualizar_duplicata'];
        $cpf_cnpj_avulso_nf = $linha['cl_cpf_cnpj_avulso_nf'];

        /*transporadora */
        $placa = $linha['cl_placa_trans'];
        $uf_veiculo = $linha['cl_uf_veiculo_trans'];
        $quantidade_tra = $linha['cl_quantidade_trans'];
        $especie_tra = $linha['cl_especie_trans'];

        $peso_bruto = $linha['cl_peso_bruto'];
        $peso_liquido = $linha['cl_peso_liquido'];
        $outras_despesas = $linha['cl_valor_outras_despesas'];
        $valor_frete = $linha['cl_valor_frete'];

        $valor_seguro = $linha['cl_valor_seguro'];

        $chave_acesso_ref = $linha['cl_chave_acesso_referencia']; //devolucao
        $numero_nf_ref = $linha['cl_numero_nf_ref']; //devolucao
        $retem_iss = $linha['cl_retem_iss']; //serviço

        $caminho_pdf_nf = $linha['cl_pdf_nf'];
        $caminho_xml_nf = $linha['cl_caminho_xml_nf'];
        $transportadora_id = $linha['cl_transportadora_id'];
        $totais = resumo_valor_nf_saida($conecta, $codigo_nf); //informações sobre os itens na nota
        $valor_total_produtos = $totais['total_valor_produtos'];
        $valor_total_produtos = abs($valor_total_produtos);     // Transformando em positivo quando se tratar de estorno ou devoluçao
        $icms_sub_nota = $totais['total_valor_icms_sub'];
        $ipi_nota = $totais['total_valor_ipi'];


        /* Serviço */
        $valor_pis_servico = ($linha['cl_valor_pis_servico']);
        $valor_cofins_servico = ($linha['cl_valor_cofins_servico']);
        $valor_deducoes = ($linha['cl_valor_deducoes']);
        $valor_inss = ($linha['cl_valor_inss']);
        $valor_ir = ($linha['cl_valor_ir']);
        $valor_csll = ($linha['cl_valor_csll']);
        $valor_iss = ($linha['cl_valor_iss']);
        $valor_iss_retido = ($linha['cl_valor_iss_retido']);
        $valor_outras_retencoes = ($linha['cl_valor_outras_retencoes']);
        $valor_base_calculo = ($linha['cl_valor_base_calculo']);
        $aliq_servico = ($linha['cl_valor_aliquota']);
        $valor_desconto_condicionado = ($linha['cl_valor_desconto_condicionado']);
        $valor_desconto_incondicionado = ($linha['cl_valor_desconto_incondicionado']);
        $atividade_id = ($linha['cl_atividade_id']);
        $natureza_operacao_servico = ($linha['cl_natureza_operacao_servico']);
        $intermediario_id = ($linha['cl_intermediario_id']);


        $valor_total_nota = $valor_total_produtos + $icms_sub_nota + $ipi_nota + $valor_frete + $outras_despesas + $valor_seguro - $desconto_nota;



        /*itens */
        $select = "SELECT * from tb_nf_saida_item where cl_codigo_nf = '$codigo_nf'";
        $consultar_nf_item = mysqli_query($conecta, $select);



        /*cliente obrigatorio */
        $select = "SELECT * from tb_parceiros where cl_id = $parceiro_id";
        $consultar_destinatario = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consultar_destinatario);
        $razao_social_dest = utf8_encode($linha['cl_razao_social']);
        $nome_fantasia_dest = utf8_encode($linha['cl_nome_fantasia']);
        $cpfcnpj_dest = $linha['cl_cnpj_cpf'];
        $inscricao_estadual_dest = $linha['cl_inscricao_estadual'];

        if (identifyCpfOrCnpj($cpfcnpj_dest) == "0" or identifyCpfOrCnpj($cpfcnpj_dest) == "-1") { //funcao para verificar se o cliente é cpf ou cnpj //0-cpf 1-cnpj
            $cpf_dest = $cpfcnpj_dest;
            $cnpj_dest = "";
            $inscricao_estadual_dest = "";
        } elseif (identifyCpfOrCnpj($cpfcnpj_dest) == "1") { //cnpj
            $cpf_dest = "";
            $cnpj_dest = $cpfcnpj_dest;
            $inscricao_estadual_dest = $inscricao_estadual_dest;
        }

        $endereco_dest = utf8_encode($linha['cl_endereco']);
        $cidade_id_dest = ($linha['cl_cidade_id']);
        $cidade_dest = utf8_encode(consulta_tabela($conecta, "tb_cidades", "cl_id", $cidade_id_dest, 'cl_nome'));
        $codigo_municipio_destinatario = (consulta_tabela($conecta, "tb_cidades", "cl_id", $cidade_id_dest, 'cl_ibge'));
        $cep_dest = $linha['cl_cep'];
        $bairro_dest = utf8_encode($linha['cl_bairro']);
        $numero_dest = utf8_encode($linha['cl_numero']);
        $numero_dest = $numero_dest != "" ? $numero_dest : 'SN';

        $estado_id_dest = $linha['cl_estado_id'];
        $estado_dest = consulta_tabela($conecta, "tb_estados", "cl_id", $estado_id_dest, 'cl_uf');
        $aliq_interna_dest = consulta_tabela($conecta, "tb_estados", "cl_id", $estado_id_dest, 'cl_aliq'); //aliq interna do parceiro
        $telefone_dest = $linha['cl_telefone'];
        $email_dest = $linha['cl_email'];




        /*emitente*/
        $select = "SELECT * FROM tb_empresa where cl_id = '1' ";
        $consultar_emitente = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consultar_emitente);
        $razao_social_emit = utf8_encode($linha['cl_razao_social']);
        $nome_fantasia_emit = utf8_encode($linha['cl_nome_fantasia']);
        $inscricao_estadual_emit = ($linha['cL_inscricao_estadual']);
        $inscricao_municipal_emit = ($linha['cl_inscricao_municipal']);
        $cnpj_emit = ($linha['cl_cnpj']);
        $endereco_emit = utf8_encode($linha['cl_endereco']);
        $bairro_emit = utf8_encode($linha['cl_bairro']);
        $cidade_emit = utf8_encode($linha['cl_cidade']);
        $estado_emit = utf8_encode($linha['cl_estado']); //descricao
        $numero_emit = ($linha['cl_numero']); //descricao
        $cep_emit = ($linha['cl_cep']); //descricao
        $email_emit = ($linha['cl_email']);
        $telefone_emit = ($linha['cl_telefone']);
        $codigo_municipio = $linha['cl_codigo_municipio']; //ibge da cidade
        $codigo_cnae = $linha['cl_cnae']; //ibge da cidade



        /*transporadora */
        if ($transportadora_id != "") { //verificar se tem transportadora 
            $select = "SELECT * from tb_parceiros where cl_id = $transportadora_id";
            $consultar_transportadora = mysqli_query($conecta, $select);
            $linha = mysqli_fetch_assoc($consultar_transportadora);
            $razao_social_trans = utf8_encode($linha['cl_razao_social']);
            $cpfcnpj_trans = $linha['cl_cnpj_cpf'];
            $inscricao_estadual_trans = $linha['cl_inscricao_estadual'];
            $endereco_trans = utf8_encode($linha['cl_endereco']);
            $cidade_id_trans = ($linha['cl_cidade_id']);
            $cidade_trans = utf8_encode(consulta_tabela($conecta, "tb_cidades", "cl_id", $cidade_id_trans, 'cl_nome'));

            $cep_trans = $linha['cl_cep'];
            $bairro_trans = utf8_encode($linha['cl_bairro']);
            $numero_trans = utf8_encode($linha['cl_numero']);
            $estado_id_trans = $linha['cl_estado_id'];
            $estado_trans = consulta_tabela($conecta, "tb_estados", "cl_id", $estado_id_trans, 'cl_uf');

            $telefone_trans = $linha['cl_telefone'];
            $placa_trans = $placa;
            $uf_veiculo_trans = $uf_veiculo;

            $quantidade_vl = $quantidade_tra;
            $especie_vl = $especie_tra;
            $peso_bruto_vl = $peso_bruto;
            $peso_liquido_vl = $peso_liquido;
        } else {
            $quantidade_vl = "";
            $especie_vl = "";
            $peso_bruto_vl = "";
            $peso_liquido_vl = "";

            $cpfcnpj_trans = "";
            $razao_social_trans = "";
            $inscricao_estadual_trans = "";
            $endereco_trans = "";
            $cidade_trans = "";
            $estado_trans = "";
            $placa_trans = "";
            $uf_veiculo_trans = "";
        }

        $ref = $serie_nf . $cnpj_emit . $numero_nf; //numero da nf


        // $retornar["dados"] = array("sucesso" => true, "title" => "Nota alterada com sucesso");

        if ($serie_nf == "NFE") {
            if ($sub_acao == "enviar_nf" or $sub_acao == "preview_nf") { //enviar nfe
                $nfe = array(
                    "natureza_operacao" => "$descricao_cfop",
                    "data_emissao" => $data,
                    "data_entrada_saida" => $data,
                    "tipo_documento" => $tipo_documento,
                    "finalidade_emissao" => $finalidade_id,
                    "serie" => "1",
                    "numero" => $numero_nf,
                    "cnpj_emitente" => "$cnpj_emit",
                    "nome_emitente" => "$razao_social_emit",
                    "nome_fantasia_emitente" => "$nome_fantasia_emit",
                    "logradouro_emitente" => "$endereco_emit",
                    "numero_emitente" => "$numero_emit",
                    "bairro_emitente" => "$bairro_emit",
                    "municipio_emitente" => "$cidade_emit",
                    "uf_emitente" => "$estado_emit",
                    "cep_emitente" => "$cep_emit",
                    "telefone_emitente" => "$telefone_emit",

                    "inscricao_estadual_emitente" => "$inscricao_estadual_emit",
                    "nome_destinatario" => "$razao_social_dest",
                    "cpf_destinatario" => $cpf_dest,
                    "cnpj_destinatario" => $cnpj_dest,
                    "inscricao_estadual_destinatario" => $inscricao_estadual_dest,
                    // "indicador_inscricao_estadual_destinatario" => "$indicado_inscricao",
                    "telefone_destinatario" => "$telefone_dest",

                    "logradouro_destinatario" => "$endereco_dest",
                    "numero_destinatario" => $numero_dest,
                    "bairro_destinatario" => "$bairro_dest",
                    "municipio_destinatario" => "$cidade_dest",
                    "uf_destinatario" => "$estado_dest",
                    "pais_destinatario" => "Brasil",
                    "cep_destinatario" => "$cep_dest",
                    //"valor_seguro" => "0",
                    //"valor_outras_despesas"=>"$outras_despesas",
                    "valor_total" => "$valor_total_nota",
                    "valor_produtos" => "$valor_total_produtos",
                    "modalidade_frete" => "$frete",

                    "informacoes_adicionais_contribuinte" => "$observacao",
                    "pedido_compra" => $numero_pedido,
                    "nome_transportador" => "$razao_social_trans",
                    "cnpj_transportador" => "$cpfcnpj_trans",
                    "inscricao_estadual_transportador" => "$inscricao_estadual_trans",
                    "endereco_transportador" => "$endereco_trans",
                    "municipio_transportador" => "$cidade_trans",
                    "uf_transportador" => "$estado_trans",
                    "veiculo_placa" => "$placa_trans",
                    "veiculo_uf" => "$uf_veiculo_trans",

                    "items" => array(),
                    "volumes" => array(),
                    "duplicatas" => array(),
                    "formas_pagamento" => array(),
                    "notas_referenciadas" => array(),

                );


                /*duplicatas */
                $select = "SELECT lcf.* from tb_lancamento_financeiro as lcf inner join tb_forma_pagamento as fpg on fpg.cl_id = lcf.cl_forma_pagamento_id 
                             where lcf.cl_codigo_nf = '$codigo_nf' and fpg.cl_tipo_pagamento_id ='3'"; //apenas as forma de pagamento que tiver o tipo faturado
                $consultar_duplicatas = mysqli_query($conecta, $select);
                $qtd_duplicatas = mysqli_num_rows($consultar_duplicatas);
                $indicador_pagamento = 0; //a vista
                if ($qtd_duplicatas > 0 and $visualizar_duplicata == 1) {
                    $numero_duplicata = 0;
                    $duplicatas = array(); // Crie um array para armazenar as duplicatas
                    $indicador_pagamento = 1; //a prazo
                    while ($linha = mysqli_fetch_assoc($consultar_duplicatas)) {
                        $numero_duplicata = $numero_duplicata + 1;
                        $data_vencimento = $linha['cl_data_vencimento'];
                        $valor_liquido = $linha['cl_valor_liquido'];

                        // Adicione os campos da duplicata ao array $duplicata
                        $duplicata = array(
                            "numero" => $numero_duplicata,
                            "data_vencimento" => $data_vencimento,
                            "valor" => $valor_liquido,
                            // Adicione outros campos da duplicata, se houver
                        );
                        array_push($nfe["duplicatas"], $duplicata);
                        // // Adicione o array da duplicata ao array de duplicatas
                        // $duplicatas[] = $duplicata;
                    }
                }


                /*volume transportadora */
                $volume_trans = array(
                    "quantidade" => "$quantidade_vl",
                    "especie" => "$especie_tra",
                    "peso_liquido" => "$peso_liquido_vl",
                    "peso_bruto" => "$peso_bruto_vl",
                );
                array_push($nfe["volumes"], $volume_trans);

                /*devolucao */
                $nf_referenciada = array(
                    "chave_nfe" => "$chave_acesso_ref",
                );


                if ($chave_acesso_ref != "") { //finaljidade para estorno ou devolução sao necessario referenciar a chave de acesso
                    array_push($nfe["notas_referenciadas"], $nf_referenciada);
                    $fpgmt = array(
                        "forma_pagamento" => "90", //sem pagamento
                    );
                } else {
                    $fpgmt = array(
                        "indicador_pagamento" => "$indicador_pagamento",
                        "forma_pagamento" => "$tipo_forma_pagamento",
                        "valor_pagamento" => "$valor_total_nota",
                    );
                }

                array_push($nfe["formas_pagamento"], $fpgmt);
                $qtd_prod = mysqli_num_rows($consultar_nf_item);

                if ($valor_frete > 0 and $valor_frete != "") {
                    $valor_frete_item = $valor_frete / $qtd_prod;
                } else {
                    $valor_frete_item = "0";
                }

                if ($outras_despesas > 0 and  $outras_despesas != "") {
                    $outras_despesas_item = $outras_despesas / $qtd_prod;
                } else {
                    $outras_despesas_item = "0";
                }

                if ($desconto_nota > 0 and  $desconto_nota != "") {
                    $desconto_item = floor($desconto_nota / $qtd_prod * 100) / 100; // Arredonda para duas casas decimais para baixo
                } else {
                    $desconto_item = "0";
                }

                if ($valor_seguro > 0 and  $valor_seguro != "") {
                    $valor_seguro_item = $valor_seguro / $qtd_prod;
                } else {
                    $valor_seguro_item = "0";
                }



                $item_nf = 0;
                // Calcule a diferença entre o desconto total na nota e o total de desconto nos itens
                $diferenca_desconto = round($desconto_nota - ($desconto_item * $qtd_prod), 2);
                $verificacao_realizada_desconto = false; // Inicialmente, a verificação não foi realizada

                while ($linha = mysqli_fetch_assoc($consultar_nf_item)) {
                    //   $item_nf = $linha['item'];
                    $item_nf = $item_nf + 1;
                    $id_produto = ($linha['cl_item_id']);
                    $descricao = utf8_encode($linha['cl_descricao_item']);
                    $und = utf8_encode($linha['cl_unidade']);
                    $quantidade = ($linha['cl_quantidade']);
                    $quantidade = abs($quantidade);     // Transformando em positivo quando se tratar de estorno ou devoluçao

                    $valor_unitario = ($linha['cl_valor_unitario']);
                    $valor_produto = $quantidade * $valor_unitario;


                    $ncm = ($linha['cl_ncm']);
                    $cest = ($linha['cl_cest']);
                    $cfop_item = ($linha['cl_cfop']);
                    $cst = ($linha['cl_cst']);
                    $bc_icms = ($linha['cl_base_icms']);
                    $aliq_icms = ($linha['cl_aliq_icms']);
                    $valor_icms = ($linha['cl_icms']);
                    $base_icms_sub = ($linha['cl_base_icms_sbt']);
                    $icms_sub = ($linha['cl_icms_sbt']);
                    $aliq_ipi = ($linha['cl_aliq_ipi']);
                    $valor_ipi = ($linha['cl_ipi']);
                    $ipi_devolvido = ($linha['cl_ipi_devolvido']);
                    $base_pis = ($linha['cl_base_pis']);
                    $valor_pis = ($linha['cl_pis']);
                    $cst_pis = ($linha['cl_cst_pis']);
                    $base_cofins = ($linha['cl_base_cofins']);
                    $valor_cofins = ($linha['cl_cofins']);
                    $cst_cofins = ($linha['cl_cst_cofins']);
                    $base_iss = ($linha['cl_base_iss']);
                    $valor_iss = ($linha['cl_iss']);
                    $gtin = $linha['cl_gtin'];
                    $numero_pedido_item = $linha['cl_numero_pedido']; //numero do pedido de compra
                    $numero_item_pedido = $linha['cl_item_pedido']; //numero do item

                    if ($diferenca_desconto < $valor_produto and ($verificacao_realizada_desconto == false)) { //adicionar ao desconto em um item a diferencia para corrir o desconto total
                        $verificacao_realizada_desconto = true;
                        $item_nf_verificado = $item_nf;
                        $desconto_item = $desconto_item + $diferenca_desconto;
                    }
                    if ($sub_acao == "preview_nf") {
                        $ncm = empty($ncm) ? '00000000' : $ncm;
                    }

                    $aliq_cofins = ($base_cofins != 0) ? ($valor_cofins * 100) / $base_cofins : 0; //aligq cofins
                    $aliq_pis = ($base_pis != 0) ? ($valor_pis * 100) / $base_pis : 0; //aliq pis


                    if (in_array($cst, ['102', '900'])) {
                        $icms_origem = '0';
                    } elseif (strlen($cst) == 3) {
                        $icms_origem = substr($cst, 0, 1);
                    } else {
                        $icms_origem = '0';
                    }
                    $aliq_ipi = $aliq_ipi != "" ? $aliq_ipi : 0;
                    $base_ipi = $valor_ipi > 0 ? $valor_produto : 0;
                    // if ($desconto_nota != 0 and $desconto_nota <= $valor_produto and $valida_desconto == false) {//primeiro produto que suportar o dessconto sera atriuido o desconto total a ele
                    //     $desconto_item = $desconto_nota;
                    //     $valida_desconto = true;
                    // } else {
                    //     $desconto_item = 0;
                    // }

                    $item = array(
                        "numero_item" => "$item_nf",
                        "codigo_produto" => "$id_produto",
                        "descricao" => "$descricao",
                        "cfop" => "$cfop_item",
                        "unidade_comercial" => "$und",
                        "quantidade_comercial" => "$quantidade",
                        "valor_unitario_comercial" => "$valor_unitario",
                        "valor_unitario_tributavel" => "$valor_unitario",
                        "unidade_tributavel" => "$und",
                        "codigo_ncm" => "$ncm",
                        "cest" => "$cest",
                        "quantidade_tributavel" => "$quantidade",
                        "valor_bruto" => "$valor_produto",

                        "valor_outras_despesas" => "$outras_despesas_item",
                        "valor_frete" => "$valor_frete_item",
                        "valor_desconto" => "$desconto_item",
                        "valor_seguro" => "$valor_seguro_item",
                        "codigo_barras_comercial" => $gtin,
                        "codigo_barras_tributavel" => $gtin,
                        "pedido_compra" => "$numero_pedido_item",
                        "numero_item_pedido_compra" => "$numero_item_pedido",

                        "icms_modalidade_base_calculo" => "3",
                        "icms_modalidade_base_calculo_st" => "3",


                        "icms_situacao_tributaria" => "$cst",
                        "icms_origem" => "$icms_origem",
                        "icms_base_calculo" => "$bc_icms",
                        "icms_valor" => "$valor_icms",
                        "icms_aliquota" => "$aliq_icms",
                        "icms_valor_total_st" => "$icms_sub",
                        "icms_base_calculo_st" => "$base_icms_sub",

                        "ipi_base_calculo" => $base_ipi,
                        "ipi_aliquota" => $aliq_ipi,
                        "ipi_valor" => $valor_ipi,
                        "valor_ipi_devolvido" => $ipi_devolvido,

                        "ipi_situacao_tributaria" => "50", //saída tributada
                        "ipi_quantidade_selo_controle" => "1",
                        "ipi_codigo_enquadramento_legal" => "101",
                        "ipi_codigo_selo_controle" => "9710-01", // Produto Nacional

                        "pis_base_calculo" => "$base_pis",
                        "pis_valor" => "$valor_pis",
                        "pis_situacao_tributaria" => "$cst_pis",
                        "pis_aliquota_porcentual" => "$aliq_pis",

                        "cofins_base_calculo" => "$base_cofins",
                        "cofins_valor" => "$valor_cofins",
                        "cofins_situacao_tributaria" => "$cst_cofins",
                        "cofins_aliquota_porcentual" => "$aliq_cofins",

                        // "issqn_base_calculo" => "$base_iss",
                        // "issqn_valor" => "$valor_iss",
                        // "issqn_codigo_municipio" => "1400605",
                    );

                    if ((empty($inscricao_estadual_dest)) && substr($cfop, 0, 1) == '6') { //enviar informações caso o cliente seja fora do estado e não tenha inscrição estadual independente se é jurido ou fisico, adicionar infomrações do grupo do icms
                        $item['icms_base_calculo_uf_destino'] = $bc_icms;
                        $item['fcp_base_calculo_uf_destino'] = $bc_icms;
                        $item['fcp_percentual_uf_destino'] = 0;
                        $item['icms_aliquota_interna_uf_destino'] = "$aliq_interna_dest";
                        $item['icms_aliquota_interestadual'] = "12";
                        $item['fcp_base_calculo_retido_st'] = "$base_icms_sub";
                        $item['fcp_valor_retido_st'] = "$icms_sub";
                        $item['icms_percentual_partilha'] = "100";
                        $item['fcp_valor_uf_destino'] = "0";
                        $item['icms_valor_uf_destino'] = "0";
                        $item['icms_valor_uf_remetente'] = "0";
                    }

                    $desconto_item = floor($desconto_nota / $qtd_prod * 100) / 100; // Arredonda para duas casas decimais para baixo

                    array_push($nfe["items"], $item);
                }



                // // Distribua a diferença entre os itens
                // if ($diferenca_desconto > 0 && count($nfe["items"]) > 0) {

                //     // Ajuste o desconto em cada item
                //     foreach ($nfe["items"] as $item) {
                //         $produto_id = $item["codigo_produto"];
                //         $valor_desconto = $item["valor_desconto"];
                //         $valor_produto = consulta_tabela($conecta, 'tb_nfe_saida_item', 'nfe_iten_saidaID', $produto_id, 'valor_produto');

                //         if ($diferenca_desconto < $valor_produto) {
                //             $valor_desconto + $diferenca_desconto;

                //         }
                //     }
                // }

                // if (isset($response['mensagem'])) {
                //     $mensagem_sefaz = $response['mensagem'];
                // } else {
                //     $mensagem_sefaz = "Em processamento";
                // }

                //   $json_nfe = json_encode($nfe);

                if ($sub_acao == "enviar_nf") {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_URL, $server . "/v2/nfe?ref=" . $ref);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($nfe));
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                    curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
                    $body = curl_exec($ch);
                    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    // As próximas três linhas são um exemplo de como imprimir as informações de retorno da API.
                    $txtretorno = $http_code . $body;
                    $response = json_decode($body, true);

                    if ($http_code == 422 or $http_code == 415) { //erro_validacao_schema	 nfe_nao_autorizada	
                        $code = isset($response['codigo']) ? $response['codigo'] : '';

                        $retornar["dados"] = array(
                            "sucesso" => false,
                            "code" => $code,
                            "valores" => $txtretorno . json_encode($nfe, JSON_PRETTY_PRINT),
                            "http_code" => $http_code
                        );
                    } else {
                        $retornar["dados"] = array("sucesso" => true, "valores" => $txtretorno, "http_code" => $http_code);
                    }
                } elseif ($sub_acao = "preview_nf") {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_URL, $server_danfe);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($nfe));
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                    curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
                    $body = curl_exec($ch);
                    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    $txtretorno = $http_code . $body;
                    $response = json_decode($body, true);

                    if ($http_code == 200) {
                        // Save the received PDF content to a file
                        $pdfFilePath = '../../../img/pdf/nf/preview.pdf';
                        $caminho_danfe = "img/pdf/nf/preview.pdf";
                        file_put_contents($pdfFilePath, $body);

                        $retornar["dados"] = array("sucesso" => true, "valores" => "Pré visualização da NF-E", "open_danfe_nf" => $caminho_danfe);
                    } else {
                        $retornar["dados"] = array("sucesso" => false, "valores" => "$txtretorno");
                    }
                } else {
                    $retornar["dados"] = array("sucesso" => false, "valores" => "Erro'");
                }
                curl_close($ch);

                //   $retornar["dados"] = array("sucesso" => true, "valores" => "teste");
            } elseif ($sub_acao == "consultar_nf") {
                // Inicia o processo de envio das informações usando o cURL.
                //  $retornar["dados"] = array("sucesso" => true, "valores" => "chave");

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_URL, $server . "/v2/nfe/" . $ref . "?completa=1");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array());
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
                $body = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $txtretorno = $http_code . $body;
                $response = json_decode($body, true);
                if (isset($response['mensagem'])) {
                    $mensagem = $response['mensagem'];
                } else {
                    $mensagem = "Operação cancelada, favor, verifique se todas as informações estão corretas";
                }

                if (isset($response['status'])) { //verificar se a status
                    $status = $response['status'];
                    if ($status == "erro_autorizacao") { //verificar se teve alguma rejeição
                        $mensagem_sefaz = isset($response['mensagem_sefaz']) ? $response['mensagem_sefaz'] : $mensagem; //coletar o retorno da sefaz
                        $requisicao = isset($response['requisicao_nota_fiscal']) ? $response['requisicao_nota_fiscal'] : '';
                        if ($requisicao != '') {
                            $chaveAcesso = $requisicao['chave_nfe']; //pegar a chave de acesso
                            $chaveAcesso = substr($chaveAcesso, 3);
                            $existe_chave_acesso = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id_nf, "cl_chave_acesso");
                            if ($existe_chave_acesso == "") {
                                update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_chave_acesso", $chaveAcesso); //incluir a chave de acesso

                            }
                        } else {
                            $chaveAcesso = '';
                        }
                        $retornar["dados"] = array(
                            "sucesso" => false,
                            "valores" => $txtretorno,
                            "chave_acesso" => $chaveAcesso,
                            "status" => $status,
                            "mensagem_sefaz" => $mensagem_sefaz
                        );
                    } elseif ($status == "autorizado") { //nota validada
                        $mensagem_sefaz = $response['mensagem_sefaz']; //coletar o retorno da sefaz
                        $chaveAcesso = $response['chave_nfe'];
                        $chaveAcesso = substr($chaveAcesso, 3);
                        $nprotocolo = $response['protocolo'];
                        $caminho_xml_nota_fiscal = $response['caminho_xml_nota_fiscal'];
                        $caminho_danfe = $response['caminho_danfe'];
                        $requisicao = $response['requisicao_nota_fiscal'];
                        $data_emissao = $requisicao['data_emissao'];
                        $data_saida = $requisicao['data_entrada_saida'];

                        $data_emissao = DateTime::createFromFormat("Y-m-d\TH:i:sP", $data_emissao)->format("Y-m-d");
                        //$data_saida = DateTime::createFromFormat("Y-m-d\TH:i:sP", $data_saida)->format("Y-m-d");
                        $existe_numero_protcolo = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id_nf, "cl_numero_protocolo");
                        if ($existe_numero_protcolo == "") {
                            update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_numero_protocolo", $nprotocolo); //incluir a chave de acesso
                            $mensagem = utf8_decode("Usuário $nome_usuario_logado enviou a nota fiscal $serie_nf $numero_nf"); //registrar no log o evento de envio
                            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                        }
                        update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_data_emisao_nf", $data_emissao); //atualizar a data de emissão
                        update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_chave_acesso", $chaveAcesso); //atualizar a chave de acesso
                        update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_numero_protocolo", $nprotocolo); //atualizar o numero de protocolo
                        update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_caminho_xml_nf", $caminho_xml_nota_fiscal); //atualizar o caminho do xml
                        update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_pdf_nf", $caminho_danfe); //atualizar o caminho do pdf

                        $retornar["dados"] = array(
                            "sucesso" => true,
                            "valores" => $txtretorno,
                            "status" => $status,
                            "chave_acesso" => $chaveAcesso,
                            "nprotocolo" => $nprotocolo,
                            "opem_danfe_nf" => "$server$caminho_danfe",
                            "mensagem_sefaz" => $mensagem_sefaz
                        );
                    } elseif ($status == "processando_autorizacao") {
                        $retornar["dados"] = array(
                            "sucesso" => false,
                            "valores" => $txtretorno,
                            "status" => "pendente",
                            "mensagem_sefaz" => "Processando - ",
                        );
                    } else {
                        $retornar["dados"] = array(
                            "sucesso" => false,
                            "valores" => $txtretorno,
                            "status" => "pendente",
                            "mensagem_sefaz" => "O ambiente da sefaz está instável. Por favor, tente enviar daqui a alguns minutos.",
                        );
                    }
                } else {
                    $retornar["dados"] = array(
                        "sucesso" => false,
                        "valores" => $txtretorno,
                        "status" => 'erro_schema',
                        "mensagem_sefaz" => $mensagem,
                    );
                }
                curl_close($ch);
            } elseif ($sub_acao == 'inutilizar_nf') {
                $numero_nf = $_POST['numero_nf'];
                $justificativa = $_POST['justificativa'];

                $inutiliza = array(
                    "cnpj" => $cnpj_emit,
                    "serie" => "1",
                    "numero_inicial" => $numero_nf,
                    "numero_final" => $numero_nf,
                    "justificativa" => $justificativa
                );
                // Inicia o processo de envio das informações usando o cURL.
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_URL, $server . "/v2/nfe/inutilizacao");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($inutiliza));
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
                $body = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                $txtretorno = $http_code . $body;

                $response = json_decode($body, true);
                if (isset($response['mensagem'])) {
                    $mensagem = $response['mensagem'];
                } else {
                    $mensagem = "Operação cancelada, favor, verifique se todas as informações estão corretas";
                }

                if (isset($response['status'])) {
                    $status = $response['status'];
                    $mensagem_sefaz = $response['mensagem_sefaz'];
                    if ($status == "autorizado") { //status cancelado // alterar a finalidade para cancelado
                        //  $caminho_danfe = $response['caminho_pdf_carta_correcao'];
                        $protocolo_sefaz = $response["protocolo_sefaz"];
                        $mensagem = utf8_decode("Usuário $nome_usuario_logado inutilizou a númeração da nota fiscal $serie_nf $numero_nf"); //registrar no log o evento de envio
                        registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                        $retornar["dados"] = array(
                            "sucesso" => true,
                            "valores" => $txtretorno,
                            "status" => $status,
                            "http_code" => $http_code,
                            "protocolo_sefaz" => $protocolo_sefaz,
                            "mensagem_sefaz" => $mensagem_sefaz
                        );
                    } else {

                        $retornar["dados"] = array("sucesso" => false, "valores" => $txtretorno, "status" => $status, "mensagem_sefaz" => $mensagem_sefaz);
                    }
                } else {
                    $retornar["dados"] = array(
                        "sucesso" => false,
                        "valores" => $txtretorno,
                        "status" => "erro_autorizacao",
                        "mensagem_sefaz" => $mensagem
                    );
                }
                curl_close($ch);
            } elseif ($sub_acao == "carta_correcao_nf") {
                $correcao = $_POST['correcao'];
                $correcao = array(
                    "correcao" => $correcao,
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_URL, $server . "/v2/nfe/" . $ref  . "/carta_correcao");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($correcao));
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
                $body = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                $txtretorno = $http_code . $body;

                $response = json_decode($body, true);
                if (isset($response['mensagem'])) {
                    $mensagem = $response['mensagem'];
                } else {
                    $mensagem = "Operação cancelada, favor, verifique se todas as informações estão corretas";
                }

                if (isset($response['status'])) {
                    $status = $response['status'];
                    $mensagem_sefaz = isset($response['mensagem_sefaz']) ? $response['mensagem_sefaz'] : $mensagem;

                    if ($status == "autorizado") { //status cancelado // alterar a finalidade para cancelado
                        $caminho_danfe = $response['caminho_pdf_carta_correcao'];
                        update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_carta_correcao_nf", $caminho_danfe);

                        $retornar["dados"] = array(
                            "sucesso" => true,
                            "valores" => $txtretorno,
                            "status" => $status,
                            "http_code" => $http_code,
                            "opem_danfe_crt" => "$server$caminho_danfe",
                            "mensagem_sefaz" => $mensagem_sefaz
                        );
                    } else {
                        $retornar["dados"] = array(
                            "sucesso" => false,
                            "valores" => $txtretorno,
                            "status" => $status,
                            "http_code" => $http_code,
                            "mensagem_sefaz" => $mensagem_sefaz
                        );
                    }
                } else {
                    $retornar["dados"] = array(
                        "sucesso" => false,
                        "valores" => $txtretorno,
                        "status" => "rejeicao",
                        "http_code" => $http_code,
                        "mensagem_sefaz" => $mensagem
                    );
                }
                //  $retornar["dados"] = array("sucesso" => true, "valores" => $txtretorno, "http_code" => $http_code);
                curl_close($ch);
            } elseif ($sub_acao == "cancelar_nf") {
                $justificativa_inf = $_POST['justificativa'];
                $justificativa = array("justificativa" => "$justificativa_inf");
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_URL, $server . "/v2/nfe/" . $ref . "?completa=1");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($justificativa));
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
                $body = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                $txtretorno = $http_code . $body;

                $response = json_decode($body, true);
                if (isset($response['mensagem'])) {
                    $mensagem = $response['mensagem'];
                } else {
                    $mensagem = "Operação cancelada, favor, verifique se todas as informações estão corretas";
                }

                if (isset($response['status'])) {
                    $status = $response['status'];

                    if ($status == "cancelado") { //status cancelado // alterar a finalidade para cancelado
                        $caminho_xml_nota_fiscal = $response['caminho_xml_cancelamento'];
                        $mensagem_sefaz = $response['mensagem_sefaz'];

                        $caminho_danfe = $response['caminho_danfe'];
                        update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_pdf_nf", $caminho_danfe);
                        update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_caminho_xml_nf", $caminho_xml_nota_fiscal);
                        $retornar["dados"] = array(
                            "sucesso" => true,
                            "valores" => $txtretorno,
                            "http_code" => $http_code,
                            "opem_danfe_nf" => "$server$caminho_danfe",
                            "mensagem_sefaz" => $mensagem_sefaz
                        );
                    } else {
                        $retornar["dados"] = array("sucesso" => false, "valores" => $txtretorno, "http_code" => $http_code, "mensagem_sefaz" => "Rejeição, favor, verifique se as informações estão corretas");
                    }
                } else {
                    $retornar["dados"] = array(
                        "sucesso" => false,
                        "valores" => $txtretorno,
                        "http_code" => $http_code,
                        "mensagem_sefaz" => $mensagem
                    );
                }
                // if (isset($response['mensagem']) == "A nota fiscal já foi cancelada") {
                //     cancelamento_nf($conecta, $id_nf);
                // }


                curl_close($ch);
            }
        }
        if ($serie_nf == 'NFC') {
            if ($sub_acao == "enviar_nf") {

                if ($cpf_cnpj_avulso_nf != '') { //verificar se foi informado cpf ou cnpj na nfc
                    if (identifyCpfOrCnpj($cpf_cnpj_avulso_nf) == "0" or identifyCpfOrCnpj($cpf_cnpj_avulso_nf) == "-1") { //funcao para verificar se o cliente é cpf ou cnpj //0-cpf 1-cnpj
                        $cpf_dest = $cpf_cnpj_avulso_nf;
                        $cnpj_dest = "";
                    } elseif (identifyCpfOrCnpj($cpf_cnpj_avulso_nf) == "1") { //cnpj
                        $cpf_dest = "";
                        $cnpj_dest = $cpf_cnpj_avulso_nf;
                    }
                }
                $nfe = array(
                    "numero" => $numero_nf,
                    "serie" => "0",
                    "cnpj_emitente" => $cnpj_emit,
                    "nome_emitente" => "$razao_social_emit",
                    "nome_fantasia_emitente" => "$nome_fantasia_emit",
                    "logradouro_emitente" => "$endereco_emit",
                    "numero_emitente" => "$numero_emit",
                    "bairro_emitente" => "$bairro_emit",
                    "municipio_emitente" => "$cidade_emit",
                    "uf_emitente" => "$estado_emit",
                    "cep_emitente" => "$cep_emit",
                    "telefone_emitente" => "$telefone_emit",

                    "data_emissao" => $data,
                    "modalidade_frete" => $frete,
                    "local_destino" => $local_destino,
                    "presenca_comprador" => "1",
                    "natureza_operacao" => $descricao_cfop,
                    "inscricao_estadual_emitente" => "$inscricao_estadual_emit",

                    "cpf_destinatario" => $cpf_dest,
                    "cnpj_destinatario" => $cnpj_dest,
                    "valor_total" => "$valor_total_nota",
                    "valor_produtos" => "$valor_total_produtos",

                    "itens" => array(),

                    "formas_pagamento" => array(

                        array(
                            "forma_pagamento" => "$tipo_forma_pagamento",
                            "valor_pagamento" => "$valor_total_nota",
                            // "nome_credenciadora" => "Cielo",
                            // "bandeira_operadora" => "02",
                            // "numero_autorizacao" => "R07242"
                        )
                    ),
                );


                $qtd_prod = mysqli_num_rows($consultar_nf_item);

                if ($valor_frete > 0 and $valor_frete != "") {
                    $valor_frete_item = $valor_frete / $qtd_prod;
                } else {
                    $valor_frete_item = "0";
                }

                if ($outras_despesas > 0 and  $outras_despesas != "") {
                    $outras_despesas_item = $outras_despesas / $qtd_prod;
                } else {
                    $outras_despesas_item = "0";
                }

                if ($desconto_nota > 0 and  $desconto_nota != "") {
                    $desconto_item = floor($desconto_nota / $qtd_prod * 100) / 100; // Arredonda para duas casas decimais para baixo
                } else {
                    $desconto_item = "0";
                }

                if ($valor_seguro > 0 and  $valor_seguro != "") {
                    $valor_seguro_item = $valor_seguro / $qtd_prod;
                } else {
                    $valor_seguro_item = "0";
                }




                $item_nf = 0;
                // Calcule a diferença entre o desconto total na nota e o total de desconto nos itens
                $diferenca_desconto = round($desconto_nota - ($desconto_item * $qtd_prod), 2);
                $verificacao_realizada_desconto = false; // Inicialmente, a verificação não foi realizada

                while ($linha = mysqli_fetch_assoc($consultar_nf_item)) {
                    //   $item_nf = $linha['item'];
                    $item_nf = $item_nf + 1;
                    $id_produto = ($linha['cl_item_id']);
                    $descricao = utf8_encode($linha['cl_descricao_item']);
                    $und = utf8_encode($linha['cl_unidade']);
                    $quantidade = ($linha['cl_quantidade']);
                    $valor_unitario = ($linha['cl_valor_unitario']);
                    $valor_produto = $quantidade * $valor_unitario;
                    $ncm = ($linha['cl_ncm']);
                    $cest = ($linha['cl_cest']);
                    $cfop_item = ($linha['cl_cfop']);
                    $cst = ($linha['cl_cst']);
                    $bc_icms = ($linha['cl_base_icms']);
                    $aliq_icms = ($linha['cl_aliq_icms']);
                    $valor_icms = ($linha['cl_icms']);
                    $base_icms_sub = ($linha['cl_base_icms_sbt']);
                    $icms_sub = ($linha['cl_icms_sbt']);
                    $aliq_ipi = ($linha['cl_aliq_ipi']);
                    $valor_ipi = ($linha['cl_ipi']);
                    $ipi_devolvido = ($linha['cl_ipi_devolvido']);
                    $base_pis = ($linha['cl_base_pis']);
                    $valor_pis = ($linha['cl_pis']);
                    $cst_pis = ($linha['cl_cst_pis']);
                    $base_cofins = ($linha['cl_base_cofins']);
                    $valor_cofins = ($linha['cl_cofins']);
                    $cst_cofins = ($linha['cl_cst_cofins']);
                    $base_iss = ($linha['cl_base_iss']);
                    $valor_iss = ($linha['cl_iss']);
                    $gtin = $linha['cl_gtin'];
                    $numero_pedido_item = $linha['cl_numero_pedido']; //numero do pedido de compra
                    $numero_item_pedido = $linha['cl_item_pedido']; //numero do item

                    if ($diferenca_desconto < $valor_produto and ($verificacao_realizada_desconto == false)) { //adicionar ao desconto em um item a diferencia para corrir o desconto total
                        $verificacao_realizada_desconto = true;
                        $item_nf_verificado = $item_nf;
                        $desconto_item = $desconto_item + $diferenca_desconto;
                    }

                    $aliq_cofins = ($base_cofins != 0) ? ($valor_cofins * 100) / $base_cofins : 0; //aligq cofins
                    $aliq_pis = ($base_pis != 0) ? ($valor_pis * 100) / $base_pis : 0; //aliq pis


                    if (in_array($cst, ['102', '900'])) {
                        $icms_origem = '0';
                    } elseif (strlen($cst) == 3) {
                        $icms_origem = substr($cst, 0, 1);
                    } else {
                        $icms_origem = '0';
                    }

                    // if ($desconto_nota != 0 and $desconto_nota <= $valor_produto and $valida_desconto == false) {//primeiro produto que suportar o dessconto sera atriuido o desconto total a ele
                    //     $desconto_item = $desconto_nota;
                    //     $valida_desconto = true;
                    // } else {
                    //     $desconto_item = 0;
                    // }

                    $item = array(
                        "numero_item" => "$item_nf",
                        "codigo_ncm" => "$ncm",
                        "quantidade_comercial" => "$quantidade",
                        "quantidade_tributavel" => "$quantidade",
                        "cfop" => "$cfop",
                        "valor_unitario_tributavel" => "$valor_unitario",
                        "valor_unitario_comercial" => "$valor_unitario",
                        "descricao" => "$descricao",
                        "codigo_produto" => "$id_produto",

                        "unidade_comercial" => "$und",
                        "unidade_tributavel" => "$und",
                        "valor_outras_despesas" => "$outras_despesas_item",
                        "valor_frete" => "$valor_frete_item",
                        "valor_desconto" => "$desconto_item",
                        "valor_seguro" => "$valor_seguro_item",

                        "icms_modalidade_base_calculo" => "3",
                        "icms_modalidade_base_calculo_st" => "3",

                        "icms_situacao_tributaria" => "$cst",
                        "icms_origem" => "$icms_origem",
                        "icms_base_calculo" => "$bc_icms",
                        "icms_valor" => "$valor_icms",
                        "icms_aliquota" => "$aliq_icms",
                        "icms_valor_total_st" => "$icms_sub",
                        "icms_base_calculo_st" => "$base_icms_sub",

                        // "ipi_aliquota" => "$aliq_ipi",
                        // "ipi_valor" => "$valor_ipi",
                        // "valor_ipi_devolvido" => "$ipi_devolvido",

                        "pis_base_calculo" => "$base_pis",
                        "pis_valor" => "$valor_pis",
                        "pis_situacao_tributaria" => "$cst_pis",
                        "pis_aliquota_porcentual" => "$aliq_pis",

                        "cofins_base_calculo" => "$base_cofins",
                        "cofins_valor" => "$valor_cofins",
                        "cofins_situacao_tributaria" => "$cst_cofins",
                        "cofins_aliquota_porcentual" => "$aliq_cofins",
                        // "issqn_base_calculo" => "$base_iss",
                        // "issqn_valor" => "$valor_iss",
                    );

                    $desconto_item = floor($desconto_nota / $qtd_prod * 100) / 100; // Arredonda para duas casas decimais para baixo

                    array_push($nfe["itens"], $item);
                }




                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_URL, $server . "/v2/nfce?ref=" . $ref);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($nfe));
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
                $body = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                // As próximas três linhas são um exemplo de como imprimir as informações de retorno da API.
                $txtretorno = $http_code . $body;

                if ($http_code == 422) { //erro_validacao_schema	 nfe_nao_autorizada	
                    $retornar["dados"] = array("sucesso" => false, "valores" =>
                    $txtretorno . json_encode($nfe, JSON_PRETTY_PRINT), "http_code" => $http_code);
                } else {
                    $retornar["dados"] = array("sucesso" => true, "valores" => $txtretorno, "http_code" => $http_code);
                }
                curl_close($ch);
            } elseif ($sub_acao == "consultar_nf") {
                // Inicia o processo de envio das informações usando o cURL.
                //  $retornar["dados"] = array("sucesso" => true, "valores" => "chave");

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_URL, $server . "/v2/nfce/" . $ref . "?completa=1");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array());
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
                $body = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $txtretorno = $http_code . $body;
                $response = json_decode($body, true);
                if (isset($response['mensagem'])) {
                    $mensagem = $response['mensagem'];
                } else {
                    $mensagem = "Operação cancelada, favor, verifique se todas as informações estão corretas";
                }

                if (isset($response['status'])) { //verificar se a status
                    $status = $response['status'];
                    if ($status == "erro_autorizacao") { //verificar se teve alguma rejeição
                        $mensagem_sefaz = isset($response['mensagem_sefaz']) ? $response['mensagem_sefaz'] : $mensagem; //coletar o retorno da sefaz
                        if (isset($response['chave_nfe'])) {
                            $chaveAcesso = $response['chave_nfe']; //pegar a chave de acesso
                            $chaveAcesso = substr($chaveAcesso, 3);
                            $existe_chave_acesso = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id_nf, "cl_chave_acesso");
                            if ($existe_chave_acesso == "") {
                                update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_chave_acesso", $chaveAcesso); //incluir a chave de acesso

                            }
                        } else {
                            $chaveAcesso = '';
                        }


                        $retornar["dados"] = array(
                            "sucesso" => false,
                            "valores" => $txtretorno,
                            "chave_acesso" => $chaveAcesso,
                            "status" => $status,
                            "mensagem_sefaz" => $mensagem_sefaz
                        );
                    } elseif ($status == "autorizado") { //nota validada
                        $mensagem_sefaz = $response['mensagem_sefaz']; //coletar o retorno da sefaz
                        $chaveAcesso = $response['chave_nfe'];
                        $chaveAcesso = substr($chaveAcesso, 3);
                        $nprotocolo = $response['protocolo'];
                        $caminho_xml_nota_fiscal = $response['caminho_xml_nota_fiscal'];
                        $caminho_danfe = $response['caminho_danfe'];

                        $existe_numero_protcolo = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id_nf, "cl_numero_protocolo");
                        if ($existe_numero_protcolo == "") { //adicionar data de emissão
                            update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_data_emisao_nf", $data_lancamento); //incluir a chave de acesso
                            $mensagem = utf8_decode("Usuário $nome_usuario_logado enviou a nota fiscal $serie_nf $numero_nf"); //registrar no log o evento de envio
                            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                        }

                        update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_chave_acesso", $chaveAcesso); //atualizar a chave de acesso
                        update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_numero_protocolo", $nprotocolo); //atualizar o numero de protocolo
                        update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_caminho_xml_nf", $caminho_xml_nota_fiscal); //atualizar o caminho do xml
                        update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_pdf_nf", $caminho_danfe); //atualizar o caminho do pdf

                        $retornar["dados"] = array(
                            "sucesso" => true,
                            "valores" =>  $txtretorno,
                            "status" => $status,
                            "chave_acesso" => $chaveAcesso,
                            "nprotocolo" => $nprotocolo,
                            "opem_danfe_nf" => "$server$caminho_danfe",
                            "mensagem_sefaz" => $mensagem_sefaz
                        );
                    }
                } else {
                    $retornar["dados"] = array(
                        "sucesso" => false,
                        "valores" => $txtretorno,
                        "status" => 'erro_schema',
                        "mensagem_sefaz" => "$response"
                    );
                }
                curl_close($ch);
            } elseif ($sub_acao == "cancelar_nf") {
                $justificativa_inf = $_POST['justificativa'];
                $justificativa = array("justificativa" => "$justificativa_inf");
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_URL, $server . "/v2/nfce/" . $ref . "?completa=1");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($justificativa));
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
                $body = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                $txtretorno = $http_code . $body;
                $response = json_decode($body, true);
                if (isset($response['mensagem'])) {
                    $mensagem = $response['mensagem'];
                } else {
                    $mensagem = "Operação cancelada, favor, verifique se todas as informações estão corretas";
                }

                if (isset($response['status'])) {
                    $status = $response['status'];

                    if ($status == "cancelado") { //status cancelado // alterar a finalidade para cancelado
                        $caminho_xml_nota_fiscal = $response['caminho_xml_cancelamento'];
                        $mensagem_sefaz = $response['mensagem_sefaz'];

                        $caminho_danfe = $response['caminho_danfe'];
                        update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_caminho_xml_nf", $caminho_xml_nota_fiscal);

                        $retornar["dados"] = array(
                            "sucesso" => true,
                            "valores" => $txtretorno,
                            "http_code" => $http_code,
                            "opem_danfe_nf" => "",
                            "mensagem_sefaz" => $mensagem_sefaz
                        );
                    } else {
                        $retornar["dados"] = array("sucesso" => false, "valores" => $txtretorno, "http_code" => $http_code, "mensagem_sefaz" => $mensagem);
                    }
                } else {
                    $retornar["dados"] = array("sucesso" => false, "valores" => $txtretorno, "http_code" => $http_code, "mensagem_sefaz" => $mensagem);
                }
                // if (isset($response['mensagem']) == "A nota fiscal já foi cancelada") {
                //     cancelamento_nf($conecta, $id_nf);
                // }


                curl_close($ch);
            }
        }
        if ($serie_nf == "NFS") {
            if ($sub_acao == "enviar_nf") {
                $descricao_total = '';
                while ($linha = mysqli_fetch_assoc($consultar_nf_item)) {

                    $descricao_servico = ($linha['cl_descricao_item']);
                    $descricao_total .= $descricao_servico . "; ";
                }

                if (!empty($informacao_adicional_servico)) {
                    $descricao_total =  $descricao_total . " Informação adicional: $informacao_adicional_servico";
                }

                $descricao_total = utf8_encode($descricao_total);

                $cod_atividade = consulta_tabela($conecta, 'tb_atividade_servico', 'cl_id', $atividade_id, 'cl_atividade_id');

                $valor_total_nota = $valor_total_produtos; //valor do servico
                $valor_iss =  round($valor_total_nota * $aliq_servico / 100, 2);
                $optante_simples_nacional = $crt_empresa == "1" ? true : false;
                $iss_retido = $retem_iss == 1 ? true : false;
                $tipo_retencao_iss = $retem_iss == 1 ? 2 : 1;
                $data = (new DateTime())->format('Y-m-d\TH:i:sP');


                if ($opcao_nfs == "1") { //nacional
                    $nfe = array(
                        "data_emissao" => (new DateTime())->format('Y-m-d\TH:i:sP'),
                        "natureza_operacao" => $natureza_operacao,
                        "optante_simples_nacional" => $optante_simples_nacional,

                        "prestador" => array(
                            "cnpj" => $cnpj_emit,
                            "inscricao_municipal" => $inscricao_municipal_emit,
                            "email" => $email_emit,
                            "codigo_municipio" => $codigo_municipio
                        ),
                        "tomador" => array(
                            "razao_social" => $razao_social_dest,
                            "endereco" => array(
                                "logradouro" => $endereco_dest,
                                "codigo_municipio" => $codigo_municipio_destinatario,
                                "numero" => $numero_dest,
                                "complemento" => "",
                                "bairro" => $bairro_dest,
                                "uf" => $estado_dest,
                                "cep" => $cep_dest
                            ),
                            "telefone" => $telefone_dest,
                            "email" => $email_dest,
                        ),

                        "servico" => array(
                            "iss_retido" => $iss_retido,
                            "item_lista_servico" => $cod_atividade,
                            "aliquota" => $aliq_servico,
                            "valor_servicos" => $valor_total_nota,
                            "valor_iss" => $valor_iss,
                            "discriminacao" => $descricao_total, //descrição do servico
                            "codigo_cnae" => $codigo_cnae,
                        ),

                        "nacional" => array(
                            "numero_rps" => $numero_nf,
                            "serie_rps" => '1',
                            "tipo_rps" => '1',

                            "serie_dps" => '1',
                            "numero_dps" => $numero_nf,

                            "item_lista_servico" => $cod_atividade,
                            "tipo_retencao_iss" => $tipo_retencao_iss,
                            "tipo_retencao_pis_cofins" => 2, //não retido
                            "descricao_servico" => $descricao_total //descrição do servico
                        ),
                    );
                } elseif ($opcao_nfs == "2") { //prefeitura
                    $nfe = array(
                        "data_emissao" => (new DateTime())->format('Y-m-d\TH:i:sP'),
                        "natureza_operacao" => $natureza_operacao_servico,
                        "optante_simples_nacional" => $optante_simples_nacional,
                        // "tipo_dps" => '1',
                        // "serie_dps" => '1',
                        // "numero_dps" => $numero_nf,

                        // "tipo_rps" => '1',
                        // "serie_rps" => '1',
                        // "numero_rps" => $numero_nf,

                        "prestador" => array(
                            "cnpj" => $cnpj_emit,
                            "inscricao_municipal" => $inscricao_municipal_emit,
                            "email" => $email_emit,
                            "codigo_municipio" => $codigo_municipio
                        ),

                        "tomador" => array(
                            "razao_social" => $razao_social_dest,
                            "telefone" => $telefone_dest,
                            "email" => $email_dest,
                            "endereco" => array(
                                "logradouro" => $endereco_dest,
                                "codigo_municipio" => $codigo_municipio_destinatario,
                                "numero" => $numero_dest,
                                "complemento" => "",
                                "bairro" => $bairro_dest,
                                "uf" => $estado_dest,
                                "cep" => $cep_dest
                            ),
                        ),
                        "servico" => array(
                            "codigo_tributario_municipio" => $codigo_municipio,
                            "item_lista_servico" => $cod_atividade,
                            "codigo_cnae" => $codigo_cnae,
                            "iss_retido" => $iss_retido,
                            "valor_iss_retido" => $valor_iss_retido,
                            "valor_iss" => $valor_iss,
                            "valor_servicos" => 2,
                            "discriminacao" => $descricao_total, //descrição do servico
                            "valor_deducoes" => $valor_deducoes,
                            "valor_pis" => $valor_pis_servico,
                            "valor_cofins" => $valor_cofins_servico,
                            "valor_inss" => $valor_inss,
                            "valor_ir" => $valor_ir,
                            "valor_csll" => $valor_csll,
                            "outras_retencoes" => $valor_outras_retencoes,
                            "base_calculo" => $valor_base_calculo,
                            "aliquota" => $aliq_servico,
                            "desconto_incondicionado" => $valor_desconto_incondicionado,
                            "desconto_condicionado" => $valor_desconto_condicionado,
                        ),
                    );
                }

                if (empty($cnpj_dest)) { //definr se o destinatario e juridico ou pessoa fisica
                    $nfe['tomador']['cpf'] = $cpf_dest;
                } elseif (empty($cpf_dest)) {
                    $nfe['tomador']['cnpj'] = $cnpj_dest;
                }

                if ($intermediario_id != 0) { //intermediario
                    $razao_social_int = utf8_encode(consulta_tabela($conecta, 'tb_parceiros', 'cl_id', $intermediario_id, 'cl_razao_social'));
                    $cpfcnpj_int = (consulta_tabela($conecta, 'tb_parceiros', 'cl_id', $intermediario_id, 'cl_cnpj_cpf'));
                    $inscricao_municipal_int = (consulta_tabela($conecta, 'tb_parceiros', 'cl_id', $intermediario_id, 'cl_inscricao_municipal'));

                    $nfe['intermediario']['razao_social'] = $razao_social_int;
                    if (identifyCpfOrCnpj($cpfcnpj_int) == "0" or identifyCpfOrCnpj($cpfcnpj_int) == "-1") { //funcao para verificar se o cliente é cpf ou cnpj //0-cpf 1-cnpj
                        $nfe['intermediario']['cpf'] = $cpfcnpj_int;
                    } elseif (identifyCpfOrCnpj($cpfcnpj_int) == "1") { //cnpj
                        $nfe['intermediario']['cnpj'] = $cpfcnpj_int;
                    }
                    $nfe['intermediario']['inscricao_municipal'] = $inscricao_municipal_int;
                }


                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_URL, $server . "/v2/nfse?ref=" . $ref);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($nfe));
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
                $body = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                // As próximas três linhas são um exemplo de como imprimir as informações de retorno da API.
                $txtretorno = $http_code . $body;

                if ($http_code == 422) { //erro_validacao_schema	 nfe_nao_autorizada	
                    $retornar["dados"] = array("sucesso" => false, "valores" => $txtretorno . json_encode($nfe, JSON_PRETTY_PRINT), "http_code" => $http_code);
                } else {
                    $retornar["dados"] = array("sucesso" => true, "valores" => $txtretorno, "http_code" => $http_code);
                }
                curl_close($ch);
            } elseif ($sub_acao == "consultar_nf") {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_URL, $server . "/v2/nfse/" . $ref);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array());
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
                $body = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $txtretorno = $http_code . $body;
                $response = json_decode($body, true);

                if (isset($response['mensagem'])) {
                    $mensagem = $response['mensagem'];
                } else {
                    $mensagem = "Operação cancelada, favor, verifique se todas as informações estão corretas";
                }

                if (isset($response['status'])) { //verificar se a status
                    $status = $response['status'];
                    if ($status == "erro_autorizacao") { //verificar se teve alguma rejeição
                        $mensagem_sefaz = isset($response['mensagem_sefaz']) ? $response['mensagem_sefaz'] : $mensagem; //coletar o retorno da sefaz
                        $retornar["dados"] = array(
                            "sucesso" => false,
                            "valores" => $txtretorno,
                            "chave_acesso" => '',
                            "status" => $status,
                            "mensagem_sefaz" => $mensagem_sefaz
                        );
                    } elseif ($status == "autorizado") { //nota validada
                        $mensagem_sefaz = "Autorizada o uso da NFS $numero_nf";
                        $chaveAcesso = isset($response['codigo_verificacao']) ? $response['codigo_verificacao'] : '';
                        $caminho_danfe = isset($response['url_danfse']) ? $response['url_danfse'] : '';
                        $caminho_xml_nota_fiscal = isset($response['caminho_xml_nota_fiscal']) ? $response['caminho_xml_nota_fiscal'] : '';
                        $nprotocolo = $chaveAcesso;
                        $existe_numero_protcolo = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id_nf, "cl_numero_protocolo");
                        if ($existe_numero_protcolo == "") { //adicionar data de emissão
                            update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_data_emisao_nf", $data_lancamento); //incluir a chave de acesso
                            $mensagem = utf8_decode("Usuário $nome_usuario_logado enviou a nota fiscal $serie_nf $numero_nf"); //registrar no log o evento de envio
                            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                        }

                        update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_chave_acesso", $chaveAcesso); //atualizar a chave de acesso
                        update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_numero_protocolo", $chaveAcesso); //atualizar o numero de protocolo
                        update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_caminho_xml_nf", $caminho_xml_nota_fiscal); //atualizar o caminho do xml
                        update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_pdf_nf", $caminho_danfe); //atualizar o caminho do pdf

                        $retornar["dados"] = array(
                            "sucesso" => true,
                            "valores" =>  $txtretorno,
                            "status" => $status,
                            "chave_acesso" => $chaveAcesso,
                            "nprotocolo" => $nprotocolo,
                            "opem_danfe_nf" => "$caminho_danfe",
                            "mensagem_sefaz" => $mensagem_sefaz
                        );
                    } else {
                        $retornar["dados"] = array(
                            "sucesso" => true,
                            "valores" =>  $txtretorno,
                            "status" => $status,
                            "mensagem_sefaz" => $mensagem_sefaz
                        );
                    }
                } else {
                    $retornar["dados"] = array(
                        "sucesso" => false,
                        "valores" => $txtretorno,
                        "status" => 'erro_schema',
                        "mensagem_sefaz" => "$response"
                    );
                }
            } elseif ($sub_acao == "cancelar_nf") {
                $justificativa_inf = $_POST['justificativa'];
                $justificativa = array("justificativa" => "$justificativa_inf");
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_URL, $server . "/v2/nfse/" . $ref);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($justificativa));
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
                $body = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                $txtretorno = $http_code . $body;
                $response = json_decode($body, true);
                if (isset($response['mensagem'])) {
                    $mensagem = $response['mensagem'];
                } else {
                    $mensagem = "Operação cancelada, favor, verifique se todas as informações estão corretas";
                }

                if (isset($response['status'])) {
                    $status = $response['status'];

                    if ($status == "cancelado") { //status cancelado // alterar a finalidade para cancelado
                        $mensagem_sefaz = "NFS $numero_nf cancelada com sucesso";
                        $retornar["dados"] = array(
                            "sucesso" => true,
                            "valores" => $txtretorno,
                            "http_code" => $http_code,
                            "opem_danfe_nf" => "",
                            "mensagem_sefaz" => $mensagem_sefaz
                        );
                    } else {
                        $retornar["dados"] = array("sucesso" => false, "valores" => $txtretorno, "http_code" => $http_code, "mensagem_sefaz" => $mensagem);
                    }
                } else {
                    $retornar["dados"] = array("sucesso" => false, "valores" => $txtretorno, "http_code" => $http_code, "mensagem_sefaz" => $mensagem);
                }

                curl_close($ch);
            }
        }
    } elseif ($acao == "consultar_ncm") {
        $ambiente = verficar_paramentro($conecta, "tb_parametros", "cl_id", "35"); // 1 - homologacao 2 - producao

        if ($ambiente == "1") {
            $server = verficar_paramentro($conecta, "tb_parametros", "cl_id", "60");
            $login =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "58"); //token para homologacao
            $server_danfe  = verficar_paramentro($conecta, "tb_parametros", "cl_id", "68");
        } elseif ($ambiente == "2") {
            $server =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "61");
            $login = verficar_paramentro($conecta, "tb_parametros", "cl_id", "59"); //token para producao
            $server_danfe =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "69");
        } else {
            $server = "";
            $login = "";
        }
        $password = "";

        $descricao_ncm = isset($_POST['descricao']) ? ($_POST['descricao']) : '';
        if (!empty($descricao_ncm)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_URL, $server . "/v2/ncms?descricao=$descricao_ncm");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array());
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
            $body = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            // As próximas três linhas são um exemplo de como imprimir as informações de retorno da API.
            $txtretorno = $http_code . $body;
            $retornar["dados"] = array("sucesso" => false, "valores" => $txtretorno, "http_code" => $http_code);
        } else {
            $retornar["dados"] = array("sucesso" => false, "valores" => "Informe a descrição", "http_code" => 404);
        }
    }

    echo json_encode($retornar);
}
