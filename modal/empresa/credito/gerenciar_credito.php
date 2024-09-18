<?php

//consultar informações para tabela
if (isset($_GET['consultar_credito'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $consulta = $_GET['consultar_credito'];


    if ($consulta == "inicial") {
        // $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1
        $consultar_tabela_inicialmente =  "N";
        $select = "SELECT * from tb_parceiros";
        $consultar_parceiros = mysqli_query($conecta, $select);
        if (!$consultar_parceiros) {
            die("Falha no banco de dados");
        } else {
            $qtd = mysqli_num_rows($consultar_parceiros);
        }
    } else {
        $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
        $remove_chars = array('.', '/', '-');
        $pesquisa = str_replace(($remove_chars), '', $pesquisa); // remover caracteres especias
        $select = "SELECT * from tb_parceiros where (cl_razao_social LIKE '%{$pesquisa}%' or cl_nome_fantasia LIKE '%{$pesquisa}%' or cl_cnpj_cpf LIKE '%{$pesquisa}%' or 
        cl_id ='$pesquisa') ORDER BY cl_id ";

        $consultar_parceiros = mysqli_query($conecta, $select);
        if (!$consultar_parceiros) {
            die("Falha no banco de dados");
        } else {
            $qtd = mysqli_num_rows($consultar_parceiros);
        }
    }
}

//consultar informações de historico de crédito
if (isset($_GET['consultar_historico_credito_parceiro'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $pesquisa = utf8_decode($_GET['conteudo']);
    $parceiro_id = $_GET['parceiro_id'];

    $select = "SELECT * from tb_historico_credito where cl_parceiro_id ='$parceiro_id ' ";
    if (!empty($pesquisa)) {
        $select .= " and cl_justificativa LIKE '%{$pesquisa}%'  ";
    }
    $select .= " order by cl_id asc ";
    $consultar_historico = mysqli_query($conecta, $select);
    if (!$consultar_historico) {
        die("Falha no banco de dados " . mysqli_error($conecta));
    } else {
        $qtd = mysqli_num_rows($consultar_historico);
    }
}

// //cadastrar formulario
if (isset($_POST['ajustar_credito'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $usuario_id = verifica_sessao_usuario();
    $nome_usuario_logado = (consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario"));

    $acao = $_POST['acao'];
    $total = 0;
    $valida_total = false;
    $valida_justificativa = false; //justificativa não preenchida
    if ($acao == "update") {
        // Inicia uma transação
        mysqli_begin_transaction($conecta);
        $erro = false;
        $select = "SELECT * FROM tb_parceiros ";
        $consulta = mysqli_query($conecta, $select);

        while ($linha = mysqli_fetch_assoc($consulta)) {
            $parceiro_id = $linha['cl_id'];
            if (isset($_POST["$parceiro_id"])) { //verifica se 
                $valor = $_POST["$parceiro_id"];
                $justificativa = utf8_decode($_POST["justificativa_$parceiro_id"]);
                $total += $valor;


                if ($valor != "") {
                    if ($justificativa == '') {
                        if ($valida_justificativa == false) {
                            $valida_justificativa = true;
                        }
                    }
                    if (verificaVirgula($valor)) { //verificar se tem virgula
                        $valor = formatDecimal($valor); // formatar virgula para ponto
                    }
                    $valor_credito_parceiro = consulta_tabela($conecta, "tb_parceiros", "cl_id", $parceiro_id, "cl_valor_credito");
                    if ($valor >= $valor_credito_parceiro) {
                        $valor_credito = abs($valor_credito_parceiro - $valor); //valor de credito para historico
                        $tipo = "ENTRADA";
                    } else {
                        $valor_credito = $valor_credito_parceiro - $valor ; //valor de credito para historico
                        $tipo = "SAIDA";
                    }

                    $adicionar_historico_credito = adicionar_credito_parceiro($data, $parceiro_id, $valor_credito, $justificativa,$tipo);
                    $update = "UPDATE `tb_parceiros` SET `cl_valor_credito` = '$valor' WHERE `cl_id` = $parceiro_id ";
                    $operacao_update = mysqli_query($conecta, $update);
                    if (!$operacao_update or !$adicionar_historico_credito) {
                        $erro = true;
                        break;
                    }
                }
            }
        }

        if ($valida_justificativa == true) {
            $retornar["dados"] =  array("sucesso" => false, "title" => "Favor, informe a justificativa para alteração");
        } elseif ($erro) {
            mysqli_rollback($conecta); // Desfaz a transação em caso de erro
            $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor, contatar o suporte");
            $mensagem =  utf8_decode("Erro, tentativa sem sucesso de realizar ajuste de crédito");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        } else {
            mysqli_commit($conecta); // Confirma a transação se tudo ocorreu bem
            $retornar["dados"] =  array("sucesso" => true, "title" => "Ajuste de Crédito realizado com sucesso");
            $mensagem =  utf8_decode("Usuário $nome_usuario_logado realizou ajuste de crédito");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }
    echo json_encode($retornar);
}
