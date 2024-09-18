<?php
if (isset($_GET['servico'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $usuario_id = verifica_sessao_usuario();

    if (isset($_GET['form_id'])) {
        $form_id = $_GET['form_id'];
    } else {
        $form_id = null;
    }
}



//consultar informações para tabela devolucao
if (isset($_GET['consultar_lista_servico'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $consulta = $_GET['consultar_lista_servico'];


    if ($consulta == "inicial") {
        $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1
        $select = "SELECT * from tb_servicos ";
        $consultar_servico = mysqli_query($conecta, $select);
        if (!$consultar_servico) {
            die("Falha no banco de dados " . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar_servico); //quantidade de registros
        }
    } else {
        $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
        $status = $_GET['status'];

        $select = "SELECT * from tb_servicos where ( cl_descricao  like '%$pesquisa%' or cl_id  like '%$pesquisa%' )";

        if ($status != "sn") {
            $select .= " and cl_status = '$status' ";
        }
        $consultar_servico = mysqli_query($conecta, $select);
        if (!$consultar_servico) {
            die("Falha no banco de dados " . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar_servico);
        }
    }
}



// //cadastrar formulario
if (isset($_POST['formulario_servico'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $acao = $_POST['acao'];
    $usuario_id = verifica_sessao_usuario();
    $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");

    if ($acao == "show") {
        $id = $_POST['form_id'];
        $select = "SELECT * from tb_servicos WHERE cl_id = $id";
        $consultar = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consultar);

        $descricao = utf8_encode($linha['cl_descricao']);
        $valor = ($linha['cl_valor']);
        $status = ($linha['cl_status']);

        $informacao = array(
            "descricao" => $descricao,
            "valor" => $valor,
            "status" => $status,
        );

        $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
    }



    if ($acao == "create") {
        // Iniciar uma transação MySQL
        mysqli_begin_transaction($conecta);
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
        }


        if (empty($descricao)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("descricao"));
        } elseif ($status == "sn") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("status"));
        } else {
            if (verificaVirgula($valor)) { //verificar se tem virgula
                $valor = formatDecimal($valor); // formatar virgula para ponto
            }

            $insert = "INSERT INTO `tb_servicos` ( `cl_descricao`, `cl_valor`, `cl_status`) VALUES
             ( '$descricao', '$valor', '$status') ";
            $operacao_insert = mysqli_query($conecta, $insert);
            if ($operacao_insert) {

                $retornar["dados"] =  array("sucesso" => true, "title" => "Serviço adicionado com sucesso");
                $mensagem = utf8_decode("Adicionou o serviço $descricao");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de adicionar o serviço $descricao  ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }


    if ($acao == "update") { // EDITAR

        mysqli_begin_transaction($conecta);
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
        }

        if (empty($descricao)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("descrição"));
        } elseif ($status == "sn") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("status"));
        } else {
            if (verificaVirgula($valor)) { //verificar se tem virgula
                $valor = formatDecimal($valor); // formatar virgula para ponto
            }

            $update = "UPDATE `tb_servicos` SET
            `cl_descricao` = '$descricao',
            `cl_status` = '$status',
            `cl_valor` = '$valor'
          WHERE `cl_id` = $id ";
            $operacao_update = mysqli_query($conecta, $update);
            if ($operacao_update) {
                $retornar["dados"] =  array("sucesso" => true, "title" => "Serviço alterado com sucesso");
                $mensagem = utf8_decode("Alterou o serviço de código $id");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de alterar o serviço de código $id ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }

    echo json_encode($retornar);
}
