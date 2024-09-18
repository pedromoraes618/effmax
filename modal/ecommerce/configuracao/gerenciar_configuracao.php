<?php

if (isset($_GET['cupom_tela'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $form_id = isset($_GET['form_id']) ? $_GET['form_id'] : '';
}


if (isset($_GET['consultar_configuracao'])) {
    if (isset($_GET['aba'])) {
        $aba = $_GET['aba'];
        include "../../../../conexao/conexao.php";
        include "../../../../funcao/funcao.php";
    }
}
if (isset($_GET['consultar_cupom'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $consulta = $_GET['consultar_cupom'];
    $data_inicial = $_GET['data_inicial'];
    $data_final = $_GET['data_final'];
    if ($consulta == "inicial") {
        $consultar_tabela_inicialmente =  verficar_paramentro(
            $conecta,
            "tb_parametros",
            "cl_id",
            "1"
        ); //VERIFICAR PARAMETRO ID - 1

        $query = "SELECT * FROM tb_cupom where cl_data between '$data_inicial' and '$data_final' ";
        $consultar_cupom = mysqli_query($conecta, $query);
        if (!$consultar_cupom) {
            die("Falha no banco de dados " . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar_cupom); //quantidade de registros
        }
    } else {
        $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
        $status_cupom = utf8_decode($_GET['status_cupom']); //filtro

        $query = "SELECT * FROM tb_cupom where cl_data between '$data_inicial' and '$data_final' and  ( cl_codigo  like '%$pesquisa%' or cl_descricao
        like '%$pesquisa%' )  ";
        if ($status_cupom != "sn") {
            $query .= " and cl_status = '$status_cupom' ";
        }
        $consultar_cupom = mysqli_query($conecta, $query);
        if (!$consultar_cupom) {
            die("Falha no banco de dados " . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar_cupom);
        }
    }
}

// //cadastrar formulario
if (isset($_POST['formulario_configuracao_ecommerce'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $acao = $_POST['acao'];
    $usuario_id = verifica_sessao_usuario();
    $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");

    if ($acao == "show_cupom") {
        $id = $_POST['form_id'];
        $query = "SELECT cp.*,cd.* FROM tb_cupom as cp  left join tb_condicao_cupom as cd on cd.cl_id = cp.cl_condicao_id where cp.cl_id ='$id'";
        $consulta = mysqli_query($conecta, $query);
        $linha = mysqli_fetch_assoc($consulta);
        $descricao = utf8_encode($linha['cl_descricao']);
        $cupom = utf8_encode($linha['cl_codigo']);
        $valor = ($linha['cl_valor']);
        $status = ($linha['cl_status']);
        $data_validade_inicial = ($linha['cl_data_validade_inicial']);
        $data_validade_final = ($linha['cl_data_validade_final']);
        $data_validade_final_view = formatDateB($linha['cl_data_validade_final']);


        $valor_minimo = ($linha['cl_valor_minimo']);
        $limite_utilizado = ($linha['cl_limite_utilizado']);
        $primeira_compra = ($linha['cl_primeira_compra']);
        $operador = ($linha['cl_operador']);
        $limite_cliente = ($linha['cl_limite_cliente']);
        $condicao_cadastrado = ($linha['cl_condicao_cadastrado']);

        $informacao = array(
            "descricao" => $descricao,
            "cupom" => $cupom,
            "valor" => $valor,
            "status" => $status,
            "data_validade_inicial" => $data_validade_inicial,
            "data_validade_final" => $data_validade_final,
            "data_validade_final_view" => $data_validade_final_view,
            "operador" => $operador,
            "limite_cliente" => $limite_cliente,
            "valor_minimo" => $valor_minimo,

            "limite_utilizado" => $limite_utilizado,
            "primeira_compra" => $primeira_compra,
            "condicao_cadastrado" => $condicao_cadastrado,
        );

        $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
    }

    if ($acao == "create_cupom") {
        mysqli_begin_transaction($conecta);
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples

        }
        if (!empty($cupom)) {
            $valida_cupom = consulta_tabela($conecta, 'tb_cupom', 'cl_codigo', $cupom, 'cl_id'); //verifica se já existe o cupom
        }
        if (empty($cupom)) {
            $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro("cupom"));
        } elseif (empty($descricao)) {
            $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro("descricao"));
        } elseif (($valor) <= 0) {
            $retornar["dados"] = array("sucesso" => false, "title" =>  "O valor do dsconto deve ser maior que zero");
        } elseif (($status) == "sn") {
            $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro("status"));
        } elseif (!empty($valida_cupom)) {
            $retornar["dados"] = array("sucesso" => false, "title" =>  "O cupom $cupom já está sendo utilizado en outro cupom");
        } else {

            $limite_cliente_cupom = isset($_POST['limite_cliente_cupom']) ? 1 : 0;
            $limite_total_cupom = isset($_POST['limite_total_cupom']) ? 1 : 0;
            $sem_expiracao_validade = isset($_POST['sem_expiracao_validade']) ? 1 : 0;

            $validade_data_final = $sem_expiracao_validade == 0 ? $_POST['validade_data_final'] : '';
            $limite_utilizado = $limite_total_cupom == 1 ? $_POST['limite_utilizado'] : '';
            $condicao_cadastrado = isset($_POST['condicao_cadastrado']) ? 1 : 0;


            $query = "INSERT INTO `tb_condicao_cupom` (`cl_data`, `cl_valor_minimo`, `cl_limite_utilizado`,`cl_limite_cliente`, `cl_condicao_cadastrado` ) 
            VALUES ('$data', '$valor_minimo', '$limite_utilizado', '$limite_cliente_cupom', '$condicao_cadastrado'  ) ";
            $operacao_insert_condicao = mysqli_query($conecta, $query);
            if ($operacao_insert_condicao) {
                $condicao_id = mysqli_insert_id($conecta);
            }

            $query = "INSERT INTO `tb_cupom` (`cl_data`, `cl_codigo`, `cl_descricao`, `cl_operador`,`cl_valor`, `cl_data_validade_inicial`,
             `cl_data_validade_final`, `cl_condicao_id`, `cl_status`) 
            VALUES ('$data', '$cupom', '$descricao','$operador', '$valor','$validade_data_incial','$validade_data_final', '$condicao_id',
             '$status' )";
            $operacao_insert_cupom = mysqli_query($conecta, $query);
            if ($operacao_insert_cupom) {
                $retornar["dados"] =  array("sucesso" => true, "title" => "Cupom adicionado com sucesso");
                $mensagem = utf8_decode("Adicionou o cupom $cupom com sucesso");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de adicionar o cupom $cupom ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }

    if ($acao == "update_cupom") {
        mysqli_begin_transaction($conecta);
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples
        }
        $cupom_antigo = consulta_tabela($conecta, 'tb_cupom', 'cl_id', $id, 'cl_codigo');
        $valida_cupom = consulta_tabela_2_filtro($conecta, 'tb_pedido_loja', 'cl_cupom', $cupom_antigo, 'cl_status_compra', 'CONCLUIDO', 'cl_id');
        $duplicidade_cupom = consulta_tabela_query($conecta, "SELECT * FROM tb_cupom where cl_id !='$id' and cl_codigo='$cupom'", 'cl_codigo');

        if (empty($cupom)) {
            $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro("cupom"));
        } elseif (!empty($duplicidade_cupom)) {
            $retornar["dados"] = array("sucesso" => false, "title" =>  "O cupom $cupom já é utilizado");
        } elseif ($cupom_antigo != $cupom and !empty($valida_cupom)) {
            $retornar["dados"] = array("sucesso" => false, "title" =>  "Não é possivel alterar o campo cupom, pois o cupom já está sendo utilizado em pedidos");
        } elseif (empty($descricao)) {
            $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro("descricao"));
        } elseif (($valor) <= 0) {
            $retornar["dados"] = array("sucesso" => false, "title" =>  "O valor do dsconto deve ser maior que zero");
        } elseif (($status) == "sn") {
            $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro("status"));
        } else {
            $condicao_id = consulta_tabela($conecta, 'tb_cupom', 'cl_id', $id, 'cl_condicao_id');


            $limite_cliente_cupom = isset($_POST['limite_cliente_cupom']) ? 1 : 0;
            $limite_total_cupom = isset($_POST['limite_total_cupom']) ? 1 : 0;
            $sem_expiracao_validade = isset($_POST['sem_expiracao_validade']) ? 1 : 0;

            $validade_data_final = $sem_expiracao_validade == 0 ? $_POST['validade_data_final'] : '';
            $limite_utilizado = $limite_total_cupom == 1 ? $_POST['limite_utilizado'] : '';

            $condicao_cadastrado = isset($_POST['condicao_cadastrado']) ? 1 : 0;



            $query = "UPDATE `tb_condicao_cupom` SET `cl_valor_minimo` = '$valor_minimo', 
            `cl_limite_utilizado` = '$limite_utilizado',  `cl_limite_cliente`='$limite_cliente_cupom',`cl_condicao_cadastrado`='$condicao_cadastrado'
            WHERE `cl_id` = $condicao_id ";
            $operacao_update_condicao = mysqli_query($conecta, $query);

            $query = "UPDATE `tb_cupom` SET `cl_codigo` = '$cupom',`cl_descricao` = '$descricao', `cl_operador`='$operador', `cl_valor` = '$valor',  
            `cl_data_validade_inicial`='$validade_data_incial', `cl_data_validade_final`='$validade_data_final',
            `cl_status` = '$status' WHERE `cl_id` = $id ";
            $operacao_update_cupom = mysqli_query($conecta, $query);
            if ($operacao_update_cupom) {
                $valida_ativo_cupom = consulta_tabela_query($conecta, "SELECT * FROM tb_cupom where cl_status = 1", 'cl_id'); //verficar se tem algum cupom ativo 
                if (!empty($valida_ativo_cupom)) {
                    update_registro($conecta, 'tb_parametros', 'cl_id', 106, '', '', 'cl_valor', 'S'); //se não tiver nenhum cupom ativo, será inativado o baner 
                } else {
                    update_registro($conecta, 'tb_parametros', 'cl_id', 106, '', '', 'cl_valor', 'N'); //se não tiver nenhum cupom ativo, será inativado o baner 
                }

                $retornar["dados"] =  array("sucesso" => true, "title" => "Cupom alterado com sucesso");
                $mensagem = utf8_decode("Alterou o cupom $cupom_antigo com sucesso");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de alterar o cupom $cupom_antigo ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }
    if ($acao == "show_frete") {

        $frete_gratis = consulta_tabela($conecta, "tb_parametros", "cl_id", 87, "cl_valor"); //habiltar frete grátis
        $taxa_frete_gratis_dentro_estado = consulta_tabela($conecta, "tb_parametros", "cl_id", 88, "cl_valor");
        $taxa_frete_gratis_fora_estado = consulta_tabela($conecta, "tb_parametros", "cl_id", 89, "cl_valor");
        $valor_entrega_local = consulta_tabela($conecta, "tb_parametros", "cl_id", 100, "cl_valor");
        $prazo_entrega_local = consulta_tabela($conecta, "tb_parametros", "cl_id", 93, "cl_valor");
        $codigo_postal_entrega_local = consulta_tabela($conecta, "tb_parametros", "cl_id", 92, "cl_valor");
        $endereco_retirada = utf8_encode(consulta_tabela($conecta, "tb_parametros", "cl_id", 91, "cl_valor"));
        $instrucao_retirada = utf8_encode(consulta_tabela($conecta, "tb_parametros", "cl_id", 99, "cl_valor"));
        $frete_retirada = utf8_encode(consulta_tabela($conecta, "tb_parametros", "cl_id", 90, "cl_valor"));
        $qtd_postagem = (consulta_tabela($conecta, "tb_parametros", "cl_id", 135, "cl_valor"));


        $informacao = array(
            "frete_gratis" => $frete_gratis,
            "taxa_frete_gratis_dentro_estado" => $taxa_frete_gratis_dentro_estado,
            "taxa_frete_gratis_fora_estado" => $taxa_frete_gratis_dentro_estado,
            "valor_entrega_local" => $valor_entrega_local,
            "prazo_entrega_local" => $prazo_entrega_local,
            "codigo_postal_entrega_local" => $codigo_postal_entrega_local,
            "endereco_retirada" => $endereco_retirada,
            "instrucao_retirada" => $instrucao_retirada,
            "frete_retirada" => $frete_retirada,
            "qtd_postagem" => $qtd_postagem,
        );

        $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
    }

    if ($acao == "show_site") {

        $whatsapp = consulta_tabela($conecta, "tb_parametros", "cl_id", 44, "cl_valor");
        $tiktok = consulta_tabela($conecta, "tb_parametros", "cl_id", 101, "cl_valor");
        $instagram = consulta_tabela($conecta, "tb_parametros", "cl_id", 43, "cl_valor");
        $facebook = consulta_tabela($conecta, "tb_parametros", "cl_id", 80, "cl_valor");
        $nome_site = consulta_tabela($conecta, "tb_parametros", "cl_id", 64, "cl_valor");

        $sobre_nos = str_replace("'", "", utf8_encode(consulta_tabela($conecta, "tb_parametros", "cl_id", 82, "cl_valor")));
        $apresentacao =  str_replace("'", "", utf8_encode(consulta_tabela($conecta, "tb_parametros", "cl_id", 79, "cl_valor")));


        $informacao = array(
            "whatsapp" => $whatsapp,
            "tiktok" => $tiktok,
            "instagram" => $instagram,
            "facebook" => $facebook,
            "nome_site" => $nome_site,

            "sobre_nos" => $sobre_nos,
            "apresentacao" => $apresentacao,

        );

        $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
    }
    if ($acao == "show_politicas") {

        $termos_condicoes = str_replace("'", "", utf8_encode(consulta_tabela($conecta, "tb_parametros", "cl_id", 84, "cl_valor")));
        $politicas_privacidade = str_replace("'", "", utf8_encode(consulta_tabela($conecta, "tb_parametros", "cl_id", 83, "cl_valor")));
        $politicas_devolucao = str_replace("'", "", utf8_encode(consulta_tabela($conecta, "tb_parametros", "cl_id", 102, "cl_valor")));


        $informacao = array(
            "termos_condicoes" => $termos_condicoes,
            "politicas_privacidade" => $politicas_privacidade,
            "politicas_devolucao" => $politicas_devolucao,


        );

        $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
    }


    if ($acao == "show_layout") {

        $status_baner_topo = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '103', 'cl_valor');
        $status_baner_cupom = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '106', 'cl_valor');
        $status_baner_secao = (consulta_tabela($conecta, 'tb_parametros', 'cl_id', '122', 'cl_valor'));

        /*seção */
        $status_novidade = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '107', 'cl_valor');
        $titulo_novidade = utf8_encode(consulta_tabela($conecta, 'tb_parametros', 'cl_id', '113', 'cl_valor'));

        $status_desconto = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '108', 'cl_valor');
        $titulo_desconto = utf8_encode(consulta_tabela($conecta, 'tb_parametros', 'cl_id', '112', 'cl_valor'));

        $status_destaque = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '109', 'cl_valor');
        $titulo_destaque = utf8_encode(consulta_tabela($conecta, 'tb_parametros', 'cl_id', '114', 'cl_valor'));

        $status_inscreva_se = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '110', 'cl_valor');
        $titulo_inscreva_se = utf8_encode(consulta_tabela($conecta, 'tb_parametros', 'cl_id', '111', 'cl_valor'));

        $status_mais_buscados = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '123', 'cl_valor');
        $titulo_mais_buscados = utf8_encode(consulta_tabela($conecta, 'tb_parametros', 'cl_id', '124', 'cl_valor'));


        $titulo_catalogo = utf8_encode(consulta_tabela($conecta, 'tb_parametros', 'cl_id', '115', 'cl_valor'));
        $limite_produto_secao = (consulta_tabela($conecta, 'tb_parametros', 'cl_id', '105', 'cl_valor'));
        $limite_produto_pagina = (consulta_tabela($conecta, 'tb_parametros', 'cl_id', '85', 'cl_valor'));

        $informacao = array(
            "baner_topo_status" => $status_baner_topo,
            "baner_topo_cupom_status" => $status_baner_cupom,
            "status_baner_secao" => $status_baner_secao,

            "secao_novidade_status" => $status_novidade,
            "titulo_secao_novidade" => $titulo_novidade,

            "secao_desconto_status" => $status_desconto,
            "titulo_secao_desconto" => $titulo_desconto,

            "secao_destaque_status" => $status_destaque,
            "titulo_secao_destaque" => $titulo_destaque,

            "titulo_secao_catalogo" => $titulo_catalogo,

            "secao_inscreva_se_status" => $status_inscreva_se,
            "titulo_secao_inscreva_se" => $titulo_inscreva_se,

            "limite_produto_secao" => $limite_produto_secao,
            "limite_produto_pagina" => $limite_produto_pagina,

            "status_mais_buscados" => $status_mais_buscados,
            "titulo_mais_buscados" => $titulo_mais_buscados,


        );

        $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
    }


    if ($acao == "show_modelo_caixa") {

        $id = isset($_POST['id']) ? $_POST['id'] : 0;

        $query = "SELECT * FROM tb_modelo_caixa_ecommerce where cl_id = $id ";
        $consulta = mysqli_query($conecta, $query);
        $linha = mysqli_fetch_assoc($consulta);
        $nome = utf8_encode($linha['cl_nome']);

        $limite_produto = ($linha['cl_limite_produto']);
        $largura = ($linha['cl_largura']);
        $comprimento = ($linha['cl_comprimento']);
        $altura = ($linha['cl_altura']);
        $peso = ($linha['cl_peso']);

        $informacao = array(
            "id" => $id,
            "nome" => $nome,
            "limite_produto" => $limite_produto,
            "largura" => $largura,
            "comprimento" => $comprimento,
            "altura" => $altura,
            "peso" => $peso,
        );

        $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
    }

    /*modelo caixa */
    if ($acao == "update_modelo_caixa") { // EDITAR
        mysqli_begin_transaction($conecta);
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples

        }
        $valida_caixa_id  = consulta_tabela($conecta, 'tb_modelo_caixa_ecommerce', 'cl_id', $caixa_id, 'cl_id');
        if (empty($nome_caixa)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("nome"));
        } elseif (empty($limite_produto_caixa)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("limite de produto"));
        } elseif (empty($largura_caixa)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("largura"));
        } elseif (empty($altura_caixa)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("altura"));
        } elseif (empty($comprimento_caixa)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("comprimento"));
        } elseif (empty($peso_caixa)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("peso"));
        } else {

            if (empty($valida_caixa_id)) { //insert
                $query = "INSERT INTO `tb_modelo_caixa_ecommerce` (`cl_nome`, `cl_limite_produto`,
         `cl_altura`, `cl_comprimento`, `cl_largura`, `cl_peso`)
         VALUES ('$nome_caixa', '$limite_produto_caixa',
          '$altura_caixa', '$comprimento_caixa', '$largura_caixa', '$peso_caixa')";
                $insert = mysqli_query($conecta, $query);
                if ($insert) {
                    $retornar["dados"] =  array("sucesso" => true, "title" => "Adicionado o modelo com sucesso");
                    $mensagem = utf8_decode("Adicionou o mdelo de caixa $nome_caixa - Loja ");
                    registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                    mysqli_commit($conecta);
                } else {
                    mysqli_rollback($conecta);
                    $retornar["dados"] =  array("sucesso" => true, "title" => "Erro, favor, contatar o suporte");
                    $mensagem = utf8_decode("Tentativa sem sucesso de adicionar o mdelo de caixa $nome_caixa - Loja ");
                    registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                }
            } else { //update
                $query = "UPDATE `tb_modelo_caixa_ecommerce` 
                        SET 
                        `cl_nome` = '$nome_caixa', 
                        `cl_limite_produto` = '$limite_produto_caixa', 
                        `cl_altura` = '$altura_caixa', 
                        `cl_comprimento` = '$comprimento_caixa', 
                        `cl_largura` = '$largura_caixa', 
                        `cl_peso` = '$peso_caixa'
                        WHERE 
                        `cl_id` = '$caixa_id'";
                $update = mysqli_query($conecta, $query);
                if ($update) {
                    $retornar["dados"] =  array("sucesso" => true, "title" => "Alterou o modelo com sucesso");
                    $mensagem = utf8_decode("Alterou o mdelo de caixa $nome_caixa - Loja ");
                    registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                    mysqli_commit($conecta);
                } else {
                    mysqli_rollback($conecta);
                    $retornar["dados"] =  array("sucesso" => true, "title" => "Erro, favor, contatar o suporte");
                    $mensagem = utf8_decode("Tentativa sem sucesso de alterar o mdelo de caixa $nome_caixa - Loja ");
                    registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                }
            }
        }
    }


    if ($acao == "update_frete") { // EDITAR

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples

        }
        $qtd_postagem = empty($qtd_postagem) ? 0 : $qtd_postagem;

        update_registro($conecta, 'tb_parametros', 'cl_id', 135, '', '', 'cl_valor', $qtd_postagem); //atualizar a quantidade  de dias para a postagem da mercadoria a transpotraodra

        $retornar["dados"] =  array("sucesso" => true, "title" => "Alteração realizada com sucesso");
        $mensagem = utf8_decode("Alterou a configuração de frete - parametro 135 - loja ");
        registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
    }


    if ($acao == "update_frete_gratis") { // EDITAR

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples

        }
        if ($frete_gratis == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("frete gratis"));
        } elseif ($taxa_frete_gratis_dentro_estado == "" && $frete_gratis == "true") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("taxa dentro do estado"));
        } elseif ($taxa_frete_gratis_fora_estado == "" && $frete_gratis == "true") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("ftaxa fora do estado"));
        } else {
            update_registro($conecta, 'tb_parametros', 'cl_id', 87, '', '', 'cl_valor', $frete_gratis);
            update_registro($conecta, 'tb_parametros', 'cl_id', 88, '', '', 'cl_valor', $taxa_frete_gratis_dentro_estado);
            update_registro($conecta, 'tb_parametros', 'cl_id', 89, '', '', 'cl_valor', $taxa_frete_gratis_fora_estado);

            $retornar["dados"] =  array("sucesso" => true, "title" => "Alteração realizada com sucesso");
            $mensagem = utf8_decode("Alterou a configuração de frete grátis - parametro 87,88,89  - loja");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }

    if ($acao == "update_frete_local") { // EDITAR

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples

        }
        if ($valor_entrega_local == "") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("valor da entrega local"));
        } elseif ($prazo_entrega_local <= 1) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("o prazo deve ser maior que um"));
        } else {
            update_registro($conecta, 'tb_parametros', 'cl_id', 100, '', '', 'cl_valor', $valor_entrega_local);
            update_registro($conecta, 'tb_parametros', 'cl_id', 93, '', '', 'cl_valor', $prazo_entrega_local);
            update_registro($conecta, 'tb_parametros', 'cl_id', 92, '', '', 'cl_valor', $codigo_postal_entrega_local);

            $retornar["dados"] =  array("sucesso" => true, "title" => "Alteração realizada com sucesso");
            $mensagem = utf8_decode("Alterou a configuração de frete - frete local - loja");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }

    if ($acao == "update_frete_retirada_local") { // EDITAR
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples
        }

        if ($frete_retirada == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("frete retirada"));
        } elseif (empty($endereco_retirada) and $frete_retirada == "S") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("endereço da entrega"));
        } else {
            update_registro($conecta, 'tb_parametros', 'cl_id', 91, '', '', 'cl_valor', $endereco_retirada);
            update_registro($conecta, 'tb_parametros', 'cl_id', 99, '', '', 'cl_valor', $instrucao_retirada);
            update_registro($conecta, 'tb_parametros', 'cl_id', 90, '', '', 'cl_valor', $frete_retirada);

            $retornar["dados"] =  array("sucesso" => true, "title" => "Alteração realizada com sucesso");
            $mensagem = utf8_decode("Alterou a configuração de frete - retirada - loja");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }

    /*site site*/
    if ($acao == "update_site") { // EDITAR

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples

        }
        if ($nome_site == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("nome do site"));
        } else {
            update_registro($conecta, 'tb_parametros', 'cl_id', 64, '', '', 'cl_valor', $nome_site);
            $retornar["dados"] =  array("sucesso" => true, "title" => "Alteração realizada com sucesso");

            $mensagem = utf8_decode("Alterou a configuração de site - loja");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }

    if ($acao == "update_redes_sociais") { // EDITAR

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples
        }

        update_registro($conecta, 'tb_parametros', 'cl_id', 44, '', '', 'cl_valor', $whatsapp);
        update_registro($conecta, 'tb_parametros', 'cl_id', 101, '', '', 'cl_valor', $tiktok);
        update_registro($conecta, 'tb_parametros', 'cl_id', 43, '', '', 'cl_valor', $instagram);
        update_registro($conecta, 'tb_parametros', 'cl_id', 80, '', '', 'cl_valor', $facebook);

        $retornar["dados"] =  array("sucesso" => true, "title" => "Alteração realizada com sucesso");
        $mensagem = utf8_decode("Alterou a configuração das redes sociais - loja");
        registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
    }


    if ($acao == "update_sobre") { // EDITAR

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples

        }
        if (empty($sobre_nos)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("sobre nós"));
        } elseif (empty($apresentacao)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("apresentação"));
        } else {
            update_registro($conecta, 'tb_parametros', 'cl_id', 79, '', '', 'cl_valor', $apresentacao);
            update_registro($conecta, 'tb_parametros', 'cl_id', 82, '', '', 'cl_valor', $sobre_nos);
            $retornar["dados"] =  array("sucesso" => true, "title" => "Alteração realizada com sucesso");

            $mensagem = utf8_decode("Alterou as informações de sobre nós e apresentação - loja");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }


    if ($acao == "update_politicas") { // EDITAR

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples
        }

        if (empty($termos_condicoes)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("termos e condições"));
        } elseif (empty($politicas_privacidade)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("políticas de privacidade"));
        } elseif (empty($politicas_devolucao)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("políticas de devolução"));
        } else {

            update_registro($conecta, 'tb_parametros', 'cl_id', 84, '', '', 'cl_valor', $termos_condicoes);
            update_registro($conecta, 'tb_parametros', 'cl_id', 83, '', '', 'cl_valor', $politicas_privacidade);
            update_registro($conecta, 'tb_parametros', 'cl_id', 102, '', '', 'cl_valor', $politicas_devolucao);

            $retornar["dados"] =  array("sucesso" => true, "title" => "Alteração realizada com sucesso");
            $mensagem = utf8_decode("Alterou a configuração das políticas - loja");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }
    if ($acao == "update_forma_pagamento") { // EDITAR

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples
        }

        $resultados = consulta_linhas_tb_filtro($conecta, 'tb_forma_pagamento', 'cl_ativo_delivery', 'S');
        if ($resultados) {
            foreach ($resultados as $linha) {
                $id = $linha['cl_id'];

                if (isset($_POST['forma_pagamento_' . $id])) {
                    update_registro($conecta, 'tb_forma_pagamento', 'cl_id', $id, '', '', 'cl_ativo', 'S');
                } else {
                    update_registro($conecta, 'tb_forma_pagamento', 'cl_id', $id, '', '', 'cl_ativo', 'N');
                }
                if (isset($_POST['parcelamento_' . $id])) {
                    $parcelamento = $_POST['parcelamento_' . $id];
                    update_registro($conecta, 'tb_forma_pagamento', 'cl_id', $id, '', '', 'cl_parcelamento_sem_juros', $parcelamento);
                }
                if (isset($_POST['desconto_' . $id])) {
                    $desconto = $_POST['desconto_' . $id];
                    update_registro($conecta, 'tb_forma_pagamento', 'cl_id', $id, '', '', 'cl_desconto', $desconto);
                }
            }

            $retornar["dados"] =  array("sucesso" => true, "title" => "Alteração realizada com sucesso");
            $mensagem = utf8_decode("Alterou a configuração de forma de pagamento - loja");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        } else {
            $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
            $mensagem = utf8_decode("Tentativa sem sucesso de alterar as configuração das forma de pagamento - loja");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }

    /*layout */
    if ($acao == "update_baner_topo") { // EDITAR
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples
        }

        $baner_topo_status = (isset($_POST['baner_topo_status'])) ? 'S' : 'N';
        update_registro($conecta, 'tb_parametros', 'cl_id', 103, '', '', 'cl_valor', $baner_topo_status);

        $retornar["dados"] =  array("sucesso" => true, "title" => "Alteração realizada com sucesso");
        $mensagem = utf8_decode("Alterou a configuração de layout baner topo, parametro 103 - loja");
        registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
    }

    if ($acao == "update_status_baner_secao") { // EDITAR
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples
        }

        $baner_status = (isset($_POST['status_baner_secao'])) ? 'S' : 'N';
        update_registro($conecta, 'tb_parametros', 'cl_id', 122, '', '', 'cl_valor', $baner_status);

        $retornar["dados"] =  array("sucesso" => true, "title" => "Alteração realizada com sucesso");
        $mensagem = utf8_decode("Alterou a configuração de layout baner topo, parametro 122 - loja");
        registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
    }


    if ($acao == "update_baner_topo_cupom") { // EDITAR
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples
        }

        $baner_topo_cupom_status = (isset($_POST['baner_topo_cupom_status'])) ? 'S' : 'N';
        update_registro($conecta, 'tb_parametros', 'cl_id', 106, '', '', 'cl_valor', $baner_topo_cupom_status);

        $retornar["dados"] =  array("sucesso" => true, "title" => "Alteração realizada com sucesso");
        $mensagem = utf8_decode("Alterou a configuração de layout baner topo cupom, parametro 106 - loja");
        registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
    }

    if ($acao == "update_secao_site") { // EDITAR
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples
        }

        $secao_novidade_status = isset($_POST['secao_novidade_status']) ? 'S' : 'N';
        $secao_desconto_status = isset($_POST['secao_desconto_status']) ? 'S' : 'N';
        $secao_destaque_status = isset($_POST['secao_destaque_status']) ? 'S' : 'N';
        $secao_inscreva_se_status = isset($_POST['secao_inscreva_se_status']) ? 'S' : 'N';
        $secao_mais_buscado_status = isset($_POST['secao_mais_buscado_status']) ? 'S' : 'N';

        if (empty($titulo_secao_novidade) and $secao_novidade_status == "S") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("titulo novidades"));
        } elseif (empty($titulo_secao_desconto) and $secao_desconto_status == "S") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("titulo desconto"));
        } elseif (empty($titulo_secao_destaque) and $secao_destaque_status == "S") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("titulo destaque"));
        } elseif (empty($titulo_secao_catalogo)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("titulo catalogo"));
        } elseif (empty($titulo_secao_inscreva_se) and $secao_inscreva_se_status == "S") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("titulo inscreva-se"));
        } elseif ($limite_produto_pagina <= 4) {
            $retornar["dados"] = array("sucesso" => false, "title" => ("Informe um valor maior que 4 para o campo llmite de produtos por página"));
        } elseif ($limite_produto_secao <= 4) {
            $retornar["dados"] = array("sucesso" => false, "title" => ("Informe um valor maior que 4 para o campo llmite de produtos por seção"));
        } else {
            update_registro($conecta, 'tb_parametros', 'cl_id', '107', '', '', 'cl_valor', $secao_novidade_status);
            update_registro($conecta, 'tb_parametros', 'cl_id', '113', '', '', 'cl_valor', $titulo_secao_novidade);


            update_registro($conecta, 'tb_parametros', 'cl_id', '108', '', '', 'cl_valor', $secao_desconto_status);
            update_registro($conecta, 'tb_parametros', 'cl_id', '112', '', '', 'cl_valor', $titulo_secao_desconto);


            update_registro($conecta, 'tb_parametros', 'cl_id', '109', '', '', 'cl_valor', $secao_destaque_status);
            update_registro($conecta, 'tb_parametros', 'cl_id', '114', '', '', 'cl_valor', $titulo_secao_destaque);


            update_registro($conecta, 'tb_parametros', 'cl_id', '110', '', '', 'cl_valor', $secao_inscreva_se_status);
            update_registro($conecta, 'tb_parametros', 'cl_id', '111', '', '', 'cl_valor', $titulo_secao_inscreva_se);


            update_registro($conecta, 'tb_parametros', 'cl_id', '115', '', '', 'cl_valor', $titulo_secao_catalogo);
            update_registro($conecta, 'tb_parametros', 'cl_id', '105', '', '', 'cl_valor', $limite_produto_secao);
            update_registro($conecta, 'tb_parametros', 'cl_id', '85', '', '', 'cl_valor', $limite_produto_pagina);


            update_registro($conecta, 'tb_parametros', 'cl_id', '123', '', '', 'cl_valor', $secao_mais_buscado_status);
            update_registro($conecta, 'tb_parametros', 'cl_id', '124', '', '', 'cl_valor', $titulo_secao_prd_mais_buscado);


            $retornar["dados"] =  array("sucesso" => true, "title" => "Alteração realizada com sucesso");
            $mensagem = utf8_decode("Alterou a configuração de layout, secão, parametro
             107,113,108,112,109,114,118,111,115,105,85,123,124 - loja");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }


    if ($acao == "delete_baner_topo") { // remover
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples
        }

        $arquivo = (consulta_tabela($conecta, 'tb_baner_delivery', 'cl_id', $id, 'cl_arquivo'));
        $query = "DELETE from tb_baner_delivery where cl_id = $id";
        $delete = mysqli_query($conecta, $query);
        if ($delete) {
            $retornar["dados"] =  array("sucesso" => true, "title" => "Baner removido com sucesso");

            $imagem_existente = '../../../img/ecommerce/baner/' . $arquivo;
            if (file_exists($imagem_existente)) {
                $lista_imagens = glob($imagem_existente);
                foreach ($lista_imagens as $imagem) {
                    unlink($imagem); // Excluir a imagem existente
                }
            }
            $mensagem = utf8_decode("Removeu o baner topo ($arquivo) do site - loja");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        } else {
            $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
            $mensagem = utf8_decode("Tentativa sem sucesso de remover o baner topo ($arquivo) - loja");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }

    if ($acao == "delete_baner_secao") { // remover
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples
        }

        $arquivo = (consulta_tabela($conecta, 'tb_baner_delivery', 'cl_id', $id, 'cl_arquivo'));
        $query = "DELETE from tb_baner_delivery where cl_id = $id";
        $delete = mysqli_query($conecta, $query);
        if ($delete) {
            $retornar["dados"] =  array("sucesso" => true, "title" => "Baner removido com sucesso");

            $imagem_existente = '../../../img/ecommerce/baner/' . $arquivo;
            $dados_dir = array("dir" => $imagem_existente);
            delete_img($dados_dir);
            $mensagem = utf8_decode("Removeu o baner seção ($arquivo) do site - loja");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        } else {
            $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
            $mensagem = utf8_decode("Tentativa sem sucesso de remover o baner seção ($arquivo) - loja");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }
    if ($acao == "ordernar_baner_topo") { // ordernar baner

        // Decodifica os dados JSON recebidos
        $dados = json_decode($_POST['dados'], true);

        // Loop através dos dados
        foreach ($dados as $div) {
            $id = $div['id'];
            $nova_posicao = $div['position'];
            $nova_posicao = $nova_posicao + 1;

            update_registro($conecta, 'tb_baner_delivery', 'cl_id', $id, '', '', 'cl_ordem', $nova_posicao);
        }

        // $posicao_atual = consulta_tabela($conecta, 'tb_baner_delivery', 'cl_id', $id, 'cl_ordem'); //verificar a posição atual do baner que está sendo processado
        // $baner_alterado_ordem_id = consulta_tabela($conecta, 'tb_baner_delivery', 'cl_ordem', $nova_posicao, 'cl_id'); //baner que vai ser alterado a sua posição


        // update_registro($conecta, 'tb_baner_delivery', 'cl_id', $baner_alterado_ordem_id, '', '', 'cl_ordem', $posicao_atual);
        $retornar["dados"] =  array("sucesso" => true, "title" => "Baner alterado com sucesso");
    }

    /*integração integracao*/
    if ($acao == "show_integracao") {
        /*mercado pago */
        $ambiente_mercado_pago = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '70', 'cl_valor'); //defniir se está em homologação ou produção
        $token_homologacao_mercado_pago = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '71', 'cl_valor');
        $token_producao_mercado_pago = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '72', 'cl_valor');

        /*paypal*/
        $ambiente_paypal = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '116', 'cl_valor'); //defniir se está em homologação ou produção
        $token_homologacao_paypal = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '117', 'cl_valor');
        $token_producao_paypal = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '118', 'cl_valor');
        $email_paypal = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '119', 'cl_valor');

        /*frete - kangu*/
        $api_frete = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '86', 'cl_valor'); //definir a opção de frete
        $token_producao_kangu = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '67', 'cl_valor');



        /*frete - kangu*/
        $api_meta = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '97', 'cl_valor'); //definir se a api do meta está ativo ou não
        $token_producao_api_meta = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '96', 'cl_valor');
        $datasetid_api_meta  = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '95', 'cl_valor');
        $span_conf_api_meta = (empty($token_producao_api_meta) or empty($datasetid_api_meta)) ? 'Configuração Pendente' : 'Configuração Concluida';
        $informacao = array(
            "ambiente_mercado_pago" => $ambiente_mercado_pago,
            "token_homologacao_mercado_pago" => $token_homologacao_mercado_pago,
            "token_producao_mercado_pago" => $token_producao_mercado_pago,

            "ambiente_paypal" => $ambiente_paypal,
            "token_homologacao_paypal" => $token_homologacao_paypal,
            "token_producao_paypal" => $token_producao_paypal,
            "email_paypal" => $email_paypal,

            "api_frete" => $api_frete,
            "token_producao_kangu" => $token_producao_kangu,


            "api_meta" => $api_meta,
            "token_producao_api_meta" => $token_producao_api_meta,
            "datasetid_api_meta" => $datasetid_api_meta,
            "span_conf_api_meta" => $span_conf_api_meta,
        );

        $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
    }

    if ($acao == "update_integracao_pagamento") { // EDITAR
        mysqli_begin_transaction($conecta);
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples
        }

        if (!isset($_POST['flexSwitchCheckCheckedFpgMercadoPago'])) {
            update_registro($conecta, 'tb_parametros', 'cl_id', '70', '', '', 'cl_valor', '');
        } elseif (isset($_POST['flexRadioDefaultMercadoPago']) and $_POST['flexRadioDefaultMercadoPago'] == "mercado_pago_homologacao") { //ambiente homologação mercado pago
            if ($token_homologacao_mercado_pago == "") {
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("token para homologação do mercado pago"));
                echo json_encode($retornar);
                exit;
            } else {
                update_registro($conecta, 'tb_parametros', 'cl_id', '70', '', '', 'cl_valor', '1');
                update_registro($conecta, 'tb_parametros', 'cl_id', '71', '', '', 'cl_valor', $token_homologacao_mercado_pago);
            }
        } elseif (isset($_POST['flexRadioDefaultMercadoPago']) and $_POST['flexRadioDefaultMercadoPago'] == "mercado_pago_producao") {
            if ($token_producao_mercado_pago == "") {
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("token para produção do mercado pago"));
                echo json_encode($retornar);
                exit;
            } else {
                update_registro($conecta, 'tb_parametros', 'cl_id', '70', '', '', 'cl_valor', '2');
                update_registro($conecta, 'tb_parametros', 'cl_id', '72', '', '', 'cl_valor', $token_producao_mercado_pago);
            }
        } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Informe uma opção para o tipo de ambiente para o mercado pago");
            echo json_encode($retornar);
            exit;
        }


        if (!isset($_POST['flexSwitchCheckCheckedFpgPaypal'])) {
            update_registro($conecta, 'tb_parametros', 'cl_id', '116', '', '', 'cl_valor', '');
        } elseif (isset($_POST['flexRadioDefaultPayPal']) and $_POST['flexRadioDefaultPayPal'] == "pay_pal_homologacao") { //ambiente homologação mercado pago
            if ($token_homologacao_pay_pal == "") {
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("token para homologação do PayPal"));
                echo json_encode($retornar);
                exit;
            } else {
                update_registro($conecta, 'tb_parametros', 'cl_id', '116', '', '', 'cl_valor', '1');
                update_registro($conecta, 'tb_parametros', 'cl_id', '117', '', '', 'cl_valor', $token_homologacao_pay_pal);
            }
        } elseif (isset($_POST['flexRadioDefaultPayPal']) and $_POST['flexRadioDefaultPayPal'] == "pay_pal_producao") {
            if ($token_producao_pay_pal == "") {
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("token para produção do PayPal"));
                echo json_encode($retornar);
                exit;
            } else {
                update_registro($conecta, 'tb_parametros', 'cl_id', '116', '', '', 'cl_valor', '2');
                update_registro($conecta, 'tb_parametros', 'cl_id', '118', '', '', 'cl_valor', $token_producao_pay_pal);
            }
        } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Informe uma opção para o tipo de ambiente para o PayPal");
            echo json_encode($retornar);
            exit;
        }

        if (!isset($_POST['flexSwitchCheckCheckedFpgMercadoPago']) and !isset($_POST['flexSwitchCheckCheckedFpgPaypal'])) {
            mysqli_rollback($conecta);

            $retornar["dados"] = array("sucesso" => false, "title" => "Informe uma opção para a pagamento");
            echo json_encode($retornar);
            exit;
        }

        update_registro($conecta, 'tb_parametros', 'cl_id', '119', '', '', 'cl_valor', $email_paypal);
        $retornar["dados"] =  array("sucesso" => true, "title" => "Alteração realizada com sucesso");
        $mensagem = utf8_decode("Alterou a configuração de integração de pagamento, parâmetros 70,71,72,116,117,118,119 - loja");
        registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        mysqli_commit($conecta);
    }


    if ($acao == "update_integracao_frete") { // EDITAR
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples
        }

        if (isset($_POST['flexSwitchCheckCheckedFrete']) and $_POST['flexSwitchCheckCheckedFrete'] == "kangu") {
            update_registro($conecta, 'tb_parametros', 'cl_id', '86', '', '', 'cl_valor', 'kangu'); //atualizar o parametro para kangu

            if ($token_producao_kangu == "") {
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("token para produção da kangu"));
                echo json_encode($retornar);
                exit;
            } else {
                update_registro($conecta, 'tb_parametros', 'cl_id', '67', '', '', 'cl_valor', $token_producao_kangu);
            }
        } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Informe uma opção para o frete");
            echo json_encode($retornar);
            exit;
        }

        $retornar["dados"] =  array("sucesso" => true, "title" => "Alteração realizada com sucesso");
        $mensagem = utf8_decode("Alterou a configuração de integração, parâmetros 67,86 - loja");
        registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
    }


    if ($acao == "update_integracao_conversao") { // EDITAR
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples
        }

        if (!isset($_POST['flexSwitchCheckCheckedApiMeta'])) {
            update_registro($conecta, 'tb_parametros', 'cl_id', '97', '', '', 'cl_valor', 'N'); //API Meta ativo - loja S/N	
        } else {
            update_registro($conecta, 'tb_parametros', 'cl_id', '97', '', '', 'cl_valor', 'S'); //API Meta ativo - loja S/N	
            update_registro($conecta, 'tb_parametros', 'cl_id', '96', '', '', 'cl_valor', $token_producao_api_meta); //token producao API META - loja	
            update_registro($conecta, 'tb_parametros', 'cl_id', '95', '', '', 'cl_valor', $datasetid_api_meta); //DatasetId API Meta - loja	
        }

        $retornar["dados"] =  array("sucesso" => true, "title" => "Alteração realizada com sucesso");
        $mensagem = utf8_decode("Alterou a configuração de integração, parâmetros 97,96,95 - loja");
        registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
    }


    echo json_encode($retornar);
}
