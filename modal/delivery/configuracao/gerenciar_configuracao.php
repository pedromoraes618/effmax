<?php

if (isset($_GET['consultar_data_funcionamento'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";

    //data de funcionamento do delivery
    $select = "SELECT * FROM tb_data_funcionamento ";
    $consulta_data_funcionamento = mysqli_query($conecta, $select);
}
if (isset($_GET['consultar_baner'])) {
    include "../../../../../conexao/conexao.php";
    include "../../../../../funcao/funcao.php";
    //data de funcionamento do delivery
    $select = "SELECT * FROM tb_baner_delivery ";
    $consulta_baner = mysqli_query($conecta, $select);
}
if (isset($_GET['consultar_parametros'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";

    //parametros configuração do delivery

    $habiltar_area_lancamentos = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "40");
    $diferencia_dias_desabiltar_produtos_lancamentos = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "41");
    $quantidade_produtos_populares = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "24");

    $habilitar_tempo_entrega_aut = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "45");
    $qtd_minima_pedidos = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "46");
    $qtd_pouca_demanda_pd = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "47");
    $qtd_alta_demanda_pd = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "48");

    $link_instagram = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "43");
    $whatsapp = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "44");
}

if (isset($_GET['consultar_tabela_frete'])) {
    include "../../../../../conexao/conexao.php";
    include "../../../../../funcao/funcao.php";

    $consulta = $_GET['consultar_tabela_frete'];

    if ($consulta == "inicial") {

        $select = "SELECT * FROM tb_frete_delivery ";
        $consulta_frete_delivery = mysqli_query($conecta, $select);
    } else {
        $pesquisa = utf8_decode($_GET['pesquisa']);
        $promocao = $_GET['promocao'];

        $select = "SELECT * FROM tb_frete_delivery where cl_bairro like '%{$pesquisa}%' ";
        if ($promocao == "SIM") {
            $select .= " and cl_promocao_frete_gratis_delivery !='0000-00-00' ";
        }
        $consulta_frete_delivery = mysqli_query($conecta, $select);
    }
}

// //cadastrar formulario
if (isset($_POST['formulario_configuracao_delivery'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $usuario_id = verifica_sessao_usuario();
    $nome_usuario_logado = (consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario"));

    $acao = $_POST['acao'];
    if ($acao == "data_funcionamento") {

        // Inicia uma transação
        mysqli_begin_transaction($conecta);
        $erro = false;

        for ($i = 1; $i <= 7; $i++) {
            $hora_abertura = $_POST["hra$i"]; //hora de abertura
            $hora_fechamento = $_POST["hrf$i"]; //hora de abertura

            if (isset($_POST["abt$i"])) {
                $funcionamento = "SIM";
            } else {
                $funcionamento = "NAO";
            }
            if (!atualiza_delivery_funcionamento($conecta, $hora_abertura, $hora_fechamento, $funcionamento, $i)) {
                $erro = true;
                break;
            }
        }
        if ($erro) {
            mysqli_rollback($conecta); // Desfaz a transação em caso de erro
            $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte tecnico");
            //registrar no log
            $mensagem =  utf8_decode("Erro, formulario_configuracao_delivery > acao == data_funcionamento");
        } else {
            mysqli_commit($conecta); // Confirma a transação se tudo ocorreu bem
            $retornar["dados"] =  array("sucesso" => true, "title" => "Horários atualizados com sucesso");
            //registrar no log
            $mensagem =  utf8_decode("Usuário $nome_usuario_logado alterou a data de funcionamento do delivery");
        }
        registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
    } elseif ($acao == "frete") {
        // Inicia uma transação
        mysqli_begin_transaction($conecta);
        $erro = false;
        $select = "SELECT * FROM tb_frete_delivery ";
        $consulta = mysqli_query($conecta, $select);

        while ($linha = mysqli_fetch_assoc($consulta)) {
            $id_consulta = $linha['cl_id'];
            if (isset($_POST["id$id_consulta"])) { //verifica se 
                $id = $_POST["id$id_consulta"];
                $valor = $_POST["valor$id_consulta"];
                $data_promocao = $_POST["data_promocao$id_consulta"];

                if ($id_consulta == $id) {
                    if (verificaVirgula($valor)) { //verificar se tem virgula
                        $valor = formatDecimal($valor); // formatar virgula para ponto
                    }
                    if (!atualiza_frete_delivery($conecta, $id, $valor, $data_promocao)) {
                        $erro = true;
                        break;
                    }
                }
            }
        }

        if ($erro) {
            mysqli_rollback($conecta); // Desfaz a transação em caso de erro
            $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor, contatar o suporte");
            //registrar no log
            $mensagem =  utf8_decode("Erro, formulario_configuracao_delivery > acao == frete");
        } else {

            mysqli_commit($conecta); // Confirma a transação se tudo ocorreu bem
            $retornar["dados"] =  array("sucesso" => true, "title" => "Informações alteradas com sucesso");
            $mensagem =  utf8_decode("Usuário $nome_usuario_logado alterou as informações de Taxa de Entrega - delivery");
        }
        registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
    } elseif ($acao == "parametros") {
        // Inicia uma transação
        $area_lancamento = $_POST['area_lancamento'];
        $tempo_produto_lancamento = $_POST['tempo_produto_lancamento'];
        $qtd_populares = $_POST['qtd_populares'];
        $link_instagram = $_POST['link_instagram'];
        $whatsapp = $_POST['whatsapp'];

        $habilitar_tempo_entrega_aut = $_POST['habilitar_tempo_entrega_aut'];
        $qtd_minima_pedidos = $_POST['qtd_minima_pedidos'];
        $qtd_pouca_demanda_pd = $_POST['qtd_pouca_demanda_pd'];
        $qtd_alta_demanda_pd = $_POST['qtd_alta_demanda_pd'];


        mysqli_begin_transaction($conecta);
        $erro = false;


        if (!update_registro($conecta, "tb_parametros", "cl_id", "40", "", "", "cl_valor", $area_lancamento)) {
            $erro = true;
        }
        if (!update_registro($conecta, "tb_parametros", "cl_id", "41", "", "", "cl_valor", $tempo_produto_lancamento)) {
            $erro = true;
        }
        if (!update_registro($conecta, "tb_parametros", "cl_id", "24", "", "", "cl_valor", $qtd_populares)) {
            $erro = true;
        }
        if (!update_registro($conecta, "tb_parametros", "cl_id", "43", "", "", "cl_valor", $link_instagram)) {
            $erro = true;
        }
        if (!update_registro($conecta, "tb_parametros", "cl_id", "44", "", "", "cl_valor", $whatsapp)) {
            $erro = true;
        }

        if (!update_registro($conecta, "tb_parametros", "cl_id", "45", "", "", "cl_valor", $habilitar_tempo_entrega_aut)) {
            $erro = true;
        }
        if (!update_registro($conecta, "tb_parametros", "cl_id", "46", "", "", "cl_valor", $qtd_minima_pedidos)) {
            $erro = true;
        }
        if (!update_registro($conecta, "tb_parametros", "cl_id", "47", "", "", "cl_valor", $qtd_pouca_demanda_pd)) {
            $erro = true;
        }
        if (!update_registro($conecta, "tb_parametros", "cl_id", "48", "", "", "cl_valor", $qtd_alta_demanda_pd)) {
            $erro = true;
        }

        if ($erro) {
            mysqli_rollback($conecta); // Desfaz a transação em caso de erro
            $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor, contatar o suporte");
            //registrar no log
            $mensagem =  utf8_decode("Erro, formulario_configuracao_delivery > acao == parametros");
        } else {

            mysqli_commit($conecta); // Confirma a transação se tudo ocorreu bem
            $retornar["dados"] =  array("sucesso" => true, "title" => "Informações alteradas com sucesso");
            $mensagem =  utf8_decode("Usuário $nome_usuario_logado os valores de parametos do delivery");
        }
        registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
    } elseif ($acao == "adicionar_frete") {
        $bairro = utf8_decode($_POST['bairro']);
        $valor = $_POST['valor'];
        $data_promocao = $_POST['data_promocao'];


        mysqli_begin_transaction($conecta);
        $erro = false;


        if (verificaVirgula($valor)) { //verificar se tem virgula
            $valor = formatDecimal($valor); // formatar virgula para ponto
        }

        if ($bairro == "") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("bairro"));
        } else {
            if (!adicionar_frete_delivery($conecta, $bairro, $valor, $data_promocao)) {
                $erro = true;
            }

            if ($erro) {
                mysqli_rollback($conecta); // Desfaz a transação em caso de erro
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor, contatar o suporte");
                //registrar no log
                $mensagem =  utf8_decode("Erro, formulario_configuracao_delivery > acao == adicionar_frete");
            } else {
                mysqli_commit($conecta); // Confirma a transação se tudo ocorreu bem
                $retornar["dados"] =  array("sucesso" => true, "title" => "Taxa de Entrega adicionado com sucesso");
                $mensagem =  utf8_decode("Usuário $nome_usuario_logado adicionou a taxa de entrega para o $bairro - delivery");
            }
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    } elseif ($acao == "remover_frete") {
        $frete_id = $_POST['frete_id'];
        $bairro = consulta_tabela($conecta, 'tb_frete_delivery', 'cl_id', $frete_id, 'cl_bairro');

        mysqli_begin_transaction($conecta);
        $erro = false;

        if ($frete_id == "") {
            $retornar["dados"] = array("sucesso" => false, "title" => "Bairro não encontrado, favor, verifique");
        } else {
            if (!remover_linha($conecta, "tb_frete_delivery", "cl_id", $frete_id)) {
                $erro = true;
            }

            if ($erro) {
                mysqli_rollback($conecta); // Desfaz a transação em caso de erro
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor, contatar o suporte");
                //registrar no log
                $mensagem =  utf8_decode("Erro, formulario_configuracao_delivery > acao == remover_frete");
            } else {
                mysqli_commit($conecta); // Confirma a transação se tudo ocorreu bem
                $retornar["dados"] =  array("sucesso" => true, "title" => "Taxa de Entrega removido com sucesso");
                $mensagem =  utf8_decode("Usuário $nome_usuario_logado removeu a Taxa de Entrega do $bairro - delivery");
            }
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    } elseif ($acao == "remover_baner") {
        $id = $_POST['id'];

        mysqli_begin_transaction($conecta);
        $erro = false;

        if ($id == "") {
            $retornar["dados"] = array("sucesso" => false, "title" => "Baner não encontrado, favor, verifique");
        } else {
            if (!remover_linha($conecta, "tb_baner_delivery", "cl_id", $id)) {
                $erro = true;
            }

            if ($erro) {
                mysqli_rollback($conecta); // Desfaz a transação em caso de erro
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor, contatar o suporte");
                //registrar no log
                $mensagem =  utf8_decode("Erro, formulario_configuracao_delivery > acao == remover_baner");
            } else {
                mysqli_commit($conecta); // Confirma a transação se tudo ocorreu bem
                $retornar["dados"] =  array("sucesso" => true, "title" => "Baner removido com successo");
                $mensagem =  utf8_decode("Usuário $nome_usuario_logado removeu um baner - delivery");
            }
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }

    echo json_encode($retornar);
}
