<?php
if (isset($_GET['usuario'])) {
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
if (isset($_GET['consultar_usuarios'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $consulta = $_GET['consultar_usuarios'];

    if ($consulta == "inicial") {
        $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1
        $select = "SELECT * from tb_users ";
        $consultar_usuarios = mysqli_query($conecta, $select);
        if (!$consultar_usuarios) {
            die("Falha no banco de dados " . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar_usuarios); //quantidade de registros
        }
    } else {
        $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
        $status = $_GET['status_usuario'];

        $select = "SELECT * from tb_users
         where ( cl_usuario  like '%$pesquisa%' or
          cl_id  like '%$pesquisa%' or cl_nome  like '%$pesquisa%' )";

        if ($status != "sn") {
            $select .= " and cl_ativo = '$status' ";
        }
        $consultar_usuarios = mysqli_query($conecta, $select);
        if (!$consultar_usuarios) {
            die("Falha no banco de dados " . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar_usuarios);
        }
    }
}



// //cadastrar formulario
if (isset($_POST['formulario_usuario'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $acao = $_POST['acao'];
    $usuario_id = verifica_sessao_usuario();
    $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");

    if ($acao == "show") {
        $id = $_POST['form_id'];
        $select = "SELECT * from tb_users where cl_id = $id ";
        $consultar_usuarios = mysqli_query($conecta, $select);
        $linha  = mysqli_fetch_assoc($consultar_usuarios);
        $usuario = $linha['cl_usuario'];
        $nome = utf8_encode($linha['cl_nome']);
        $senha = base64_decode($linha['cl_senha']);
        $perfil = $linha['cl_tipo'];
        $situacao = $linha['cl_ativo'];
        $email = $linha['cl_email'];
        $cargo = $linha['cl_usuario_area'];
        $vendedor = $linha['cl_vendedor'];
        $tecnico = $linha['cl_tecnico'];

        $autorizar_desconto = $linha['cl_autorizar_desconto'];
        $cancelar_venda = $linha['cl_cancelar_venda'];
        $receber_alerta = $linha['cl_receber_alerta'];
        $cancelar_pedido = $linha['cl_cancelar_pedido_delivery'];
        $restricao_horario = $linha['cl_restricao_horario'];
        $comissao = $linha['cl_valor_comissao'];
        $autorizar_dados_pedido_loja = $linha['cl_autorizar_dados_pedido_loja'];
        $cancelar_lancamento_financeiro = $linha['cl_cancelar_financeiro'];
        $remover_faturamento = $linha['cl_remover_faturamento'];
        $comprador = $linha['cl_comprador'];

        $informacao = array(
            "nome" => $nome,
            "usuario" => $usuario,
            "perfil" => $perfil,
            "situacao" => $situacao,
            "email" => $email,
            "vendedor" => $vendedor,
            "cargo" => $cargo,
            "autorizar_desconto" => $autorizar_desconto,
            "cancelar_venda" => $cancelar_venda,
            "receber_alerta" => $receber_alerta,
            "cancelar_pedido" => $cancelar_pedido,
            "restricao_horario" => $restricao_horario,
            "comissao" => $comissao,
            "autorizar_dados_pedido_loja" => $autorizar_dados_pedido_loja,
            "cancelar_lancamento_financeiro" => $cancelar_lancamento_financeiro,
            "tecnico" => $tecnico,
            "remover_faturamento" => $remover_faturamento,
            "comprador" => $comprador

            // "status" => $status,
        );

        $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
    }



    if ($acao == "create") {
        // Iniciar uma transação MySQL
        mysqli_begin_transaction($conecta);
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples
        }

        $valida_usuario = consulta_tabela($conecta, "tb_users", "cl_usuario", $usuario, "cl_id");

        if (empty($nome)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("nome"));
        } elseif (empty($usuario)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("usuário"));
        } elseif (($valida_usuario) != "") {
            $retornar["dados"] = array("sucesso" => false, "title" => "Usuário já existente no sistema, favor, verifique o seu nome de usuário.");
        } elseif (empty($senha)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("senha"));
        } elseif (empty($confirmar_senha)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("confirma senha"));
        } elseif ($senha != $confirmar_senha) {
            $retornar["dados"] = array("sucesso" => false, "title" => ("A confirmação de senha não coincide com a senha fornecida"));
        } elseif (strlen($senha) < 5) {
            $retornar["dados"] = array("sucesso" => false, "title" => "A senha deve ter no mínimo 5 caracteres, contendo números, letras maiúsculas e minúsculas.");
        } elseif (!preg_match('/[0-9]/', $senha) || !preg_match('/[a-z]/', $senha) || !preg_match('/[A-Z]/', $senha)) {
            $retornar["dados"] = array("sucesso" => false, "title" => "A senha deve conter números, letras maiúsculas e minúsculas.");
        } elseif ($perfil == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("perfil"));
        } elseif ($situacao == "s") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("situação"));
        } elseif ((!filter_var($email, FILTER_VALIDATE_EMAIL) and $email != "")) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Esse e-mail não é valido, favor verifique!");
        } elseif ($cargo == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("cargo"));
        } elseif ($restricao_horario == "sn") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("restrição de horários"));
        } else {

            if (isset($_POST['vendedor']) or $perfil == "adm" or $cargo == "VENDAS" or $cargo == "GERENTE") { //verificar se o usuario é um vendedor
                $vendedor = "SIM";
            } else {
                $vendedor = "NAO";
            }
            if (isset($_POST['cancelar_venda'])  or $perfil == "adm" or $cargo == "GERENTE") { //verificar se o usuario está habiltado a cancelar venda
                $cancelar_venda = "SIM";
            } else {
                $cancelar_venda = "NAO";
            }
            if (isset($_POST['autorizar_desconto'])  or $perfil == "adm" or $cargo == "GERENTE") { //verificar se o usuario está habiltado a autorizar desconto em vendas
                $autorizar_desconto = "SIM";
            } else {
                $autorizar_desconto = "NAO";
            }
            if (isset($_POST['receber_alerta'])  or $perfil == "adm" or $cargo == "GERENTE") { //verificar se o usuario está habiltado a autorizar desconto em vendas
                $receber_alerta = "SIM";
            } else {
                $receber_alerta = "NAO";
            }
            if (isset($_POST['cancelar_pedido'])  or $perfil == "adm" or $cargo == "GERENTE") { //verificar se o usuario está habiltado a autorizar desconto em vendas
                $cancelar_pedido = "SIM";
            } else {
                $cancelar_pedido = "NAO";
            }
            if (isset($_POST['autorizar_dados_pedido_loja'])  or $perfil == "adm" or $cargo == "GERENTE") { //verificar se o usuario está habiltado a autorizar desconto em vendas
                $autorizar_dados_pedido_loja = "SIM";
            } else {
                $autorizar_dados_pedido_loja = "NAO";
            }
            if (isset($_POST['cancelar_lancamento_financeiro'])  or $perfil == "adm" or $cargo == "GERENTE") { //verificar se o usuario está habiltado a autorizar desconto em vendas
                $cancelar_lancamento_financeiro = "SIM";
            } else {
                $cancelar_lancamento_financeiro = "NAO";
            }

            if (isset($_POST['tecnico']) or $perfil == "adm" or $cargo == "GERENTE") { //verificar se o usuario é um vendedor
                $tecnico = "SIM";
            } else {
                $tecnico = "NAO";
            }

            if (isset($_POST['remover_faturamento']) or $perfil == "adm" or $cargo == "GERENTE") { //verificar se o usuario é um vendedor
                $remover_faturamento = "SIM";
            } else {
                $remover_faturamento = "NAO";
            }

            if (isset($_POST['comprador']) or $perfil == "adm" or $cargo == "GERENTE") { //verificar se o usuario é um vendedor
                $comprador = "SIM";
            } else {
                $comprador = "NAO";
            }

            $senha = base64_encode($senha); //criptografar a senha

            $inset = "INSERT INTO tb_users (cl_data_cadastro,cl_nome,cl_usuario,cl_senha,cl_tipo,cl_ativo,cl_email,
            cl_usuario_area,cl_vendedor,cl_autorizar_desconto,cl_cancelar_venda,cl_receber_alerta,
            cl_cancelar_pedido_delivery,cl_restricao_horario,cl_valor_comissao,cl_autorizar_dados_pedido_loja,
            cl_cancelar_financeiro,cl_tecnico,cl_remover_faturamento, cl_comprador )
             VALUES ('$data','$nome','$usuario','$senha','$perfil','$situacao','$email','$cargo','$vendedor',
             '$autorizar_desconto','$cancelar_venda',
             '$receber_alerta','$cancelar_pedido','$restricao_horario',
             '$comissao','$autorizar_dados_pedido_loja','$cancelar_lancamento_financeiro','$tecnico','$remover_faturamento', '$comprador' )";
            $operacao_insert = mysqli_query($conecta, $inset);

            if ($operacao_insert) {

                $retornar["dados"] = array("sucesso" => true, "title" => "Cadastro realizado com sucesso");

                //registrar no log
                $mensagem =  utf8_decode("Cadastrou o usuário $usuario");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);


                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de adicionar o usuário $usuario  ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }


    if ($acao == "update") { // EDITAR

        mysqli_begin_transaction($conecta);


        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples
        }


        if (empty($nome)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("nome"));
        } elseif ($perfil == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("perfil"));
        } elseif ($situacao == "s") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("situação"));
        } elseif ((!filter_var($email, FILTER_VALIDATE_EMAIL) and $email != "")) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Esse e-mail não é valido, favor verifique!");
        } elseif ($cargo == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("cargo"));
        } elseif ($restricao_horario == "sn") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("restrição de horários"));
        } else {


            if (isset($_POST['vendedor']) or $perfil == "adm" or $cargo == "VENDAS" or $cargo == "GERENTE") { //verificar se o usuario é um vendedor
                $vendedor = "SIM";
            } else {
                $vendedor = "NAO";
            }
            if (isset($_POST['cancelar_venda'])  or $perfil == "adm" or $cargo == "GERENTE") { //verificar se o usuario está habiltado a cancelar venda
                $cancelar_venda = "SIM";
            } else {
                $cancelar_venda = "NAO";
            }
            if (isset($_POST['autorizar_desconto'])  or $perfil == "adm" or $cargo == "GERENTE") { //verificar se o usuario está habiltado a autorizar desconto em vendas
                $autorizar_desconto = "SIM";
            } else {
                $autorizar_desconto = "NAO";
            }
            if (isset($_POST['receber_alerta'])  or $perfil == "adm" or $cargo == "GERENTE") { //verificar se o usuario está habiltado a autorizar desconto em vendas
                $receber_alerta = "SIM";
            } else {
                $receber_alerta = "NAO";
            }
            if (isset($_POST['cancelar_pedido'])  or $perfil == "adm" or $cargo == "GERENTE") { //verificar se o usuario está habiltado a autorizar desconto em vendas
                $cancelar_pedido = "SIM";
            } else {
                $cancelar_pedido = "NAO";
            }
            if (isset($_POST['autorizar_dados_pedido_loja'])  or $perfil == "adm" or $cargo == "GERENTE") { //verificar se o usuario está habiltado a autorizar desconto em vendas
                $autorizar_dados_pedido_loja = "SIM";
            } else {
                $autorizar_dados_pedido_loja = "NAO";
            }
            if (isset($_POST['cancelar_lancamento_financeiro'])  or $perfil == "adm" or $cargo == "GERENTE") { //verificar se o usuario está habiltado a autorizar desconto em vendas
                $cancelar_lancamento_financeiro = "SIM";
            } else {
                $cancelar_lancamento_financeiro = "NAO";
            }
            if (isset($_POST['tecnico']) or $perfil == "adm" or $cargo == "GERENTE") { //verificar se o usuario é um vendedor
                $tecnico = "SIM";
            } else {
                $tecnico = "NAO";
            }
            if (isset($_POST['remover_faturamento']) or $perfil == "adm" or $cargo == "GERENTE") { //verificar se o usuario é um vendedor
                $remover_faturamento = "SIM";
            } else {
                $remover_faturamento = "NAO";
            }
            if (isset($_POST['comprador']) or $perfil == "adm" or $cargo == "GERENTE") { //verificar se o usuario é um vendedor
                $comprador = "SIM";
            } else {
                $comprador = "NAO";
            }

            $usuario = consulta_tabela($conecta, "tb_users", "cl_id", $id, "cl_usuario");
            $update = "UPDATE tb_users set cl_nome = '$nome',cl_tipo = '$perfil',cl_ativo ='$situacao',cl_email='$email',cl_usuario_area ='$cargo',
                cl_vendedor='$vendedor',cl_autorizar_desconto='$autorizar_desconto', cl_cancelar_venda='$cancelar_venda',
                cl_receber_alerta ='$receber_alerta',cl_cancelar_pedido_delivery= '$cancelar_pedido',
                cl_restricao_horario= '$restricao_horario', cl_valor_comissao= '$comissao', cl_autorizar_dados_pedido_loja= '$autorizar_dados_pedido_loja',
                cl_cancelar_financeiro= '$cancelar_lancamento_financeiro', 
                cl_tecnico = '$tecnico', cl_remover_faturamento = '$remover_faturamento',
                cl_comprador = '$comprador' 
                where cl_id = $id ";
            $operacao_update = mysqli_query($conecta, $update);
            if ($operacao_update) {
                $retornar["dados"] = array("sucesso" => true, "title" => "Alteração realizada com sucesso");

                //registrar no log
                $mensagem =  utf8_decode("Alterou os dados do usuário $usuario");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de alterar os dados do usuário $usuario  ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }

    if ($acao == "resetar_senha") { // resetar a senha

        mysqli_begin_transaction($conecta);
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples
        }


        if (empty($id)) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Usuário não encontrado");
        } elseif (empty($senha)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("senha"));
        } elseif (empty($confirmar_senha)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("confirma senha"));
        } elseif ($senha != $confirmar_senha) {
            $retornar["dados"] = array("sucesso" => false, "title" => ("A confirmação de senha não coincide com a senha fornecida"));
        } elseif (strlen($senha) < 5) {
            $retornar["dados"] = array("sucesso" => false, "title" => "A senha deve ter no mínimo 5 caracteres, contendo números, letras maiúsculas e minúsculas.");
        } elseif (!preg_match('/[0-9]/', $senha) || !preg_match('/[a-z]/', $senha) || !preg_match('/[A-Z]/', $senha)) {
            $retornar["dados"] = array("sucesso" => false, "title" => "A senha deve conter números, letras maiúsculas e minúsculas.");
        } else {
            $usuario = consulta_tabela($conecta, "tb_users", "cl_id", $id, "cl_usuario");
            $senha = base64_encode($senha); //criptografar a senha

            $update = "UPDATE tb_users set cl_senha = '$senha' where cl_id = $id ";
            $operacao_update = mysqli_query($conecta, $update);
            if ($operacao_update) {
                $retornar["dados"] = array("sucesso" => true, "title" => "Senha resetada com sucesso");

                //registrar no log
                $mensagem =  utf8_decode("Resetou a senha do usuário $usuario");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de resetar a senha do usuário $usuario  ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }

    echo json_encode($retornar);
}
