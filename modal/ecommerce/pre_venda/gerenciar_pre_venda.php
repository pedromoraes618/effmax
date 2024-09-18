<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_GET['pre_venda'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $usuario_id = verifica_sessao_usuario();

    if (isset($_GET['form_id'])) {
        $form_id = $_GET['form_id'];
    } else {
        $form_id = null;
    }

    $empresa = consulta_tabela($conecta, 'tb_empresa', 'cl_id', '1', 'cl_empresa'); //diretorio raiz sistema
    $imagem_produto_default = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "34");

    $query = "SELECT pdl.*,prd.* FROM tb_pre_venda as pdl 
    inner join tb_produtos as prd on prd.cl_id = pdl.cl_produto_id where pdl.cl_id = '$form_id' ";
    $consultaProdutos = mysqli_query($conecta, $query);
}



//consultar informações para tabela devolucao
if (isset($_GET['consultar_pre_venda'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $consulta = $_GET['consultar_pre_venda'];

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
        $select = "SELECT pdl.*,pdl.cl_id as carrinho,fpg.cl_descricao as formapagamento from tb_pre_venda as pdl left join tb_forma_pagamento as fpg on fpg.cl_id = pdl.cl_pagamento_id_interno
        where cl_data between '$data_inicial' and '$data_final' order by pdl.cl_id desc";
        $consultar_pre_venda = mysqli_query($conecta, $select);
        if (!$consultar_pre_venda) {
            die("Falha no banco de dados " . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar_pre_venda); //quantidade de registros
        }
    } else {
        $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
        $status_pedido = $_GET['status_pedido'];
        $status_pagamento = $_GET['status_pagamento'];
        $forma_pgt = $_GET['forma_pgt'];
        $produto_id = $_GET['produto_id'];

        $select = "SELECT pdl.*,pdl.cl_id as carrinho,fpg.cl_descricao as formapagamento from tb_pre_venda as pdl 
        left join tb_forma_pagamento as fpg on fpg.cl_id = pdl.cl_pagamento_id_interno
         where cl_data between '$data_inicial' and '$data_final' and ( cl_nome_completo  like '%$pesquisa%' or pdl.cl_id
           like '%$pesquisa%' or  cl_pagamento_id  like '%$pesquisa%' or
            cl_email like '%$pesquisa%' ) ";

        if ($status_pedido != "0") {
            $select .= " and cl_status_compra = '$status_pedido' ";
        }

        if ($status_pagamento != "0") {
            $select .= " and cl_status_pagamento = '$status_pagamento' ";
        }

        if ($forma_pgt != "0") {
            $select .= " and cl_pagamento_id_interno = '$forma_pgt' ";
        }
        if ($produto_id != "0") {
            $select .= " and cl_produto_id = '$produto_id' ";
        }

        $select .= " order by pdl.cl_id desc";

        $consultar_pre_venda = mysqli_query($conecta, $select);
        if (!$consultar_pre_venda) {
            die("Falha no banco de dados " . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar_pre_venda);
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
    $produto_id_default = consulta_tabela($conecta, "tb_parametros", "cl_id", '78', "cl_valor");

    $serie_venda = verifcar_descricao_serie($conecta, "12"); //verificar qual seria usado na venda
    $numero_venda_atual = consultar_valor_serie($conecta, "12"); //verificar a numeração da venda atual

    $accesstokenKangu = consulta_tabela($conecta, "tb_parametros", "cl_id", '67', "cl_valor");
    $apiFrete = consulta_tabela($conecta, "tb_parametros", "cl_id", '86', "cl_valor");
    $msgRastreio = "";
    if ($acao == "show") {
        $id = $_POST['form_id'];
        $query = "SELECT pd.*,fpg.cl_descricao as formapagamento FROM tb_pre_venda as pd 
        left join tb_forma_pagamento as fpg on fpg.cL_id = pd.cl_pagamento_id_interno WHERE pd.cl_id =$id  ";
        $consulta = mysqli_query($conecta, $query);
        if ($consulta) {
            $qtd_registro = mysqli_num_rows($consulta);
            $linha = mysqli_fetch_assoc($consulta);
            $codigo_nf = ($linha['cl_codigo_nf']);
            $nome = utf8_encode($linha['cl_nome_completo']);
            $data_pedido = ($linha['cl_data']);
            $pedido = ($linha['cl_pagamento_id']);
            $email = ($linha['cl_email']);
            $cpfcnpj = ($linha['cl_cpf']);
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
            $email_codigo_rastreio = ($linha['cl_email_codigo_rastreio']);
            $span_email_codigo_rastreio = '';
            if ($email_codigo_rastreio == 1) {
                $span_email_codigo_rastreio = "<span>Email com o codigo de rastreio enviado com sucesso</span>";
            }
            $status_pagamento = $status_pagamento == "" ? 'naorealizado' : $status_pagamento;

            if (!empty($codigo_rastreio) and $apiFrete == "kangu") {
                $status_rastrear =  rastrearObjetoKangu($codigo_rastreio, $accesstokenKangu)['data'];
                if ($status_rastrear['status'] == false) {
                    $msgRastreio = $status_rastrear['message'] . " - " . $span_email_codigo_rastreio;
                } else {
                    $msgRastreio = "<span class='text-success'>Status atual: " .
                        $status_rastrear["response"]["situacao"]["ocorrencia"] . " - " .
                        ($status_rastrear["response"]["situacao"]["data"] . "</span>") . " - " . $span_email_codigo_rastreio;;
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

        include "../../../biblioteca/PHPMailer/src/Exception.php";
        include "../../../biblioteca/PHPMailer/src/PHPMailer.php";
        include "../../../biblioteca/PHPMailer/src/SMTP.php";

        mysqli_begin_transaction($conecta);
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
        }


        if (empty($id)) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Pré venda não encontrada, favor, verifique");
        } elseif (empty($cep)) {
            $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro("cep"));
        } elseif (empty($cidade)) {
            $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro("cidade"));
        } elseif (empty($estado)) {
            $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro("estado"));
        } elseif (empty($endereco)) {
            $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro("endereço"));
        } elseif (empty($numero)) {
            $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro("nº casa"));
        } else {

            // Remover caracteres especiais, deixando apenas os números
            $cep = preg_replace("/[^0-9]/", "", $cep);

            /*dados do produto */         /*dados de configuração */
            $enviado_email_rastreio = consulta_tabela($conecta, 'tb_pre_venda', 'cl_id', $id, 'cl_email_codigo_rastreio'); //verificar se já foi enviado o email com o codigo de rastreio
            $link_rastreio = consulta_tabela($conecta, 'tb_parametros', 'cl_id', 131, 'cl_valor'); //link para rastrear encomenda
            $status_envio_email_automatico_rastreio = consulta_tabela($conecta, 'tb_parametros', 'cl_id', 132, 'cl_valor'); //verificar se o sistema está habilitado para enviar o email de forma automatica

            $update = "UPDATE `tb_pre_venda` SET 
             `cl_cep` = '$cep', `cl_cidade` = '$cidade',  `cl_estado` = '$estado', 
             `cl_endereco` = '$endereco',`cl_bairro` = '$bairro',
              `cl_numero` = '$numero', `cl_complemento` = '$complemento',`cl_observacao` = '$observacao',
              `cl_codigo_rastreio` = '$codigo_rastreio',
              `cl_data_entrega` = '$data_entrega' WHERE `cl_id` = $id ";

            $operacao_update = mysqli_query($conecta, $update);
            if ($operacao_update) {
                $retornar["dados"] =  array("sucesso" => true, "title" => "Pré venda alterada com sucesso");

                if ($enviado_email_rastreio == 0 and $status_envio_email_automatico_rastreio == "S" and !empty($codigo_rastreio) and !empty($link_rastreio)) { //enviar email de codigo de rastreio
                    $pedido_codigo = consulta_tabela($conecta, 'tb_pre_venda', 'cl_id', $id, 'cl_pagamento_id');
                    $email_cliente = consulta_tabela($conecta, 'tb_pre_venda', 'cl_id', $id, 'cl_email');
                    $descricao_produto = utf8_encode(consulta_tabela($conecta, 'tb_pre_venda', 'cl_id', $id, 'cl_descricao_produto'));
                    $quantidade_produto = utf8_encode(consulta_tabela($conecta, 'tb_pre_venda', 'cl_id', $id, 'cl_quantidade'));

                    $nomeFantasia = utf8_encode(consulta_tabela($conecta, 'tb_empresa', 'cl_id', 1, 'cl_nome_fantasia')); //nome do ecommerce
                    $nome_loja = utf8_encode(consulta_tabela($conecta, 'tb_parametros', 'cl_id', 64, 'cl_valor')); //nome da loja


                    $mail = new PHPMailer(true);
                    $attbody = "Código de rastreamento do seu pedido";
                    $assunto = "Código de rastreamento disponível para o produto $descricao_produto";
                    $html = "<div style='max-width: 700px; margin: 0 auto;'>
                                <p style='margin-bottom: 1rem;'>Pedido #$pedido_codigo</p>
                                <h3>A $nomeFantasia está preparando o envio do seu item: $descricao_produto, quantidade: $quantidade_produto </h3>
                                <p>Seu código de rastreamento é <strong>$codigo_rastreio</strong>.</p>
                                <p>Para acompanhar o status do seu pedido, acesse o site através do seguinte link: <a href='$link_rastreio' 
                                style='color: #007BFF; text-decoration: none;'>$link_rastreio</a> .</p>
                                <p>Obrigado pela compra!<br>Visite nosso site para mais novidades 
                                 <a hrfe='$url_init/$nome_loja' style='color: #007BFF; text-decoration: none;'>$url_init/$nome_loja</a> </p>
                                </div>";
                    $email_cod_rastreio = sendEmailLoja($mail, $email_cliente, $assunto, $attbody, $html);
                    if ($email_cod_rastreio) {
                        update_registro($conecta, 'tb_pre_venda', 'cl_id', $id, '', '', 'cl_email_codigo_rastreio', 1); //atualizar a coluna para 1 responsavel em validar se o email já foi enviado
                    }
                }

                $mensagem = utf8_decode("Alterou a pré venda nº $id");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);


                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de alterar a pré venda nº $id ");
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


        $codigo_nf = !empty($id) ? consulta_tabela($conecta, "tb_pre_venda", "cl_id", $id, "cl_codigo_nf") : '';
        $valida_venda = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_codigo_nf");
        $estado_empresa = consulta_tabela($conecta, 'tb_empresa', 'cl_id', 1, 'cl_estado');



        if (empty($id)) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Pré venda não encontrada, favor, verifique");
        } elseif (!empty($valida_venda)) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel gerar a venda, pois já existe uma venda registrada para essa pré venda");
        } else {
            $select = "SELECT pdl.*,und.cl_sigla as unidade FROM tb_pre_venda as pdl
             inner join tb_produtos as prd on prd.cl_id = pdl.cl_produto_id
              inner join tb_unidade_medida as und on und.cl_id = prd.cl_und_id
              where pdl.cl_id = $id ";
            $operacao_consulta = mysqli_query($conecta, $select);
            $linha = mysqli_fetch_assoc($operacao_consulta);
            $codigo_nf = $linha['cl_codigo_nf'];
            $cpf = $linha['cl_cpf'];
            $cep = $linha['cl_cep'];
            $nome_completo = ($linha['cl_nome_completo']);
            $endereco = ($linha['cl_endereco']);
            $bairro = ($linha['cl_bairro']);
            $valor_frete = ($linha['cl_valor_frete']);
            $valor_produto = ($linha['cl_valor_produto']);
            $desconto = ($linha['cl_desconto']);
            $valor_liquido = ($linha['cl_valor_liquido']);
            $quantidade = ($linha['cl_quantidade']);
            $forma_pagamento = ($linha['cl_pagamento_descricao']);
            $status_pagamento = ($linha['cl_status_pagamento']);
            $status_compra = ($linha['cl_status_compra']);
            $email = ($linha['cl_email']);
            $telefone = ($linha['cl_telefone']);
            $numero = ($linha['cl_numero']);
            $descricao_produto = ($linha['cl_descricao_produto']);
            $produto_id = ($linha['cl_produto_id']);
            $unidade = ($linha['unidade']);
            $forma_pagamento_id = ($linha['cl_pagamento_id_interno']);

            $valor_bruto = $valor_frete + ($valor_produto * $quantidade);
            $valor_total_produto = $valor_produto * $quantidade;

            $resultado_cep = buscar_cep($cep);
            $api_cep = $resultado_cep["dados"]["sucesso"];

            if ($api_cep == false) {
                $retornar["dados"] =  array("sucesso" => false, "title" => $resultado_cep["dados"]["sucesso"]);
                echo json_encode($retornar);
                exit;
            }

            $cidade_ibge =  $resultado_cep["dados"]["valores"]['ibge'];
            $estado_cliente =  $resultado_cep["dados"]["valores"]['uf'];
            $tipo_frete = $estado_cliente == $estado_empresa ?    $tipo_frete = 3 : 0;
            $cidade_id = consulta_tabela($conecta, "tb_cidades", "cl_ibge ", $cidade_ibge, "cl_id");
            $estado_id = consulta_tabela($conecta, "tb_cidades", "cl_ibge ", $cidade_ibge, "cl_estado_id");



            $valida_cliente = consulta_tabela($conecta, "tb_parceiros", "cl_cnpj_cpf ", $cpf, "cl_id");
            if (empty($valida_cliente)) { //cliente ainda não foi cadastrado
                $insert = "INSERT INTO `tb_parceiros` (
            `cl_data_cadastro`, `cl_usuario_id`, `cl_razao_social`, 
             `cl_cnpj_cpf`, `cl_cep`, `cl_bairro`, `cl_endereco`, `cl_numero`, `cl_cidade_id`,
              `cl_estado_id`, `cl_pais`, `cl_telefone`, `cl_email`,`cl_situacao_ativo` )
                VALUES ('$data_lancamento', '$usuario_id', '$nome_completo', 
                 '$cpf', '$cep', '$bairro', '$endereco', '$numero','$cidade_id', '$estado_id', 'BRASIL', '$telefone', '$email', 'SIM' )";
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

            $numero_nf = $numero_venda_atual + 1; //adicionar um a númeração atual da nf
            $insert = "INSERT INTO `tb_nf_saida`
                (`cl_data_movimento`, `cl_codigo_nf`, `cl_parceiro_id`,
                 `cl_forma_pagamento_id`, `cl_numero_nf`, `cl_numero_venda`, `cl_serie_nf`, `cl_operacao`, 
                 `cl_valor_bruto`, `cl_valor_frete`, `cl_valor_desconto`, `cl_valor_liquido`,`cl_status_recebimento`,
                 `cl_status_venda`, `cl_vendedor_id`,`cl_usuario_id`,`cl_usuario_id_recebimento`,`cl_data_recebimento`,`cl_tipo_frete_id` )
            VALUES
                ('$data_lancamento', '$codigo_nf', '$cliente_id', '$forma_pagamento_id', '$numero_nf', '$numero_nf', '$serie_venda', 
                'VENDA', '$valor_bruto','$valor_frete','$desconto', '$valor_liquido','2','1', '$usuario_id','$usuario_id','$usuario_id',
                '$data_lancamento', '$tipo_frete' ) ";
            $operacao_insert_dados = mysqli_query($conecta, $insert); //adicionar os dados basicos da venda


            $insert = "INSERT INTO `tb_nf_saida_item` ( `cl_data_movimento`, `cl_codigo_nf`, `cl_usuario_id`, `cl_serie_nf`,
             `cl_numero_nf`, `cl_item_id`, `cl_descricao_item`, `cl_quantidade`, `cl_unidade`, `cl_valor_unitario`,
              `cl_valor_total`,`cl_status` ) VALUES ( '$data_lancamento', '$codigo_nf', '$usuario_id', '$serie_venda', 
               '$numero_nf','$produto_id','$descricao_produto', '$quantidade', '$unidade', '$valor_produto', '$valor_total_produto','1') ";
            $operacao_insert_item = mysqli_query($conecta, $insert); //adicionar os dados basicos da venda

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

            if ($operacao_insert_dados and $operacao_insert_item and $recebimento_nf) {
                $retornar["dados"] =  array("sucesso" => true, "title" => "$serie_venda $numero_nf gerada com sucesso");
                $mensagem = utf8_decode("Gerou a venda $serie_venda $numero_nf da pré venda nº $id");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                $atualizar_serie_venda = update_registro($conecta, "tb_serie", "cl_id", "12", '', '', 'cl_valor', $numero_nf);
                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de gerar uma venda da pré venda nº $id ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }


    echo json_encode($retornar);
}
