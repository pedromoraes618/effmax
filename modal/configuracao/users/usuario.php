<?php
//consultar usuario sem filtro
if (isset($_GET['consultar_user'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";

    $consulta_user = $_GET['consultar_user'];
    if ($consulta_user == "inicial") {
        $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1
        $select = "SELECT * from tb_users ";
        $consultar_usuarios = mysqli_query($conecta, $select);
        if (!$consultar_usuarios) {
            die("Falha no banco de dados"); // colocar o svg do erro
        }
    } elseif ($consulta_user == "detalhado") {
        $pesquisa_user = $_GET['conteudo_pesquisa'];
        $situacao_user = $_GET['situacao_user'];
        $select = "SELECT * from tb_users where cl_usuario LIKE '%{$pesquisa_user}%' ";
        if ($situacao_user != "s") {
            $select .= " and cl_ativo = '$situacao_user'";
        }
        $consultar_usuarios = mysqli_query($conecta, $select);
        if (!$consultar_usuarios) {
            die("Falha no banco de dados"); // colocar o svg do erro
        }
    }
}




//cadastrar usuario
if (isset($_POST['formulario_cadastrar_usuario'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $nome_usuario_logado = $_POST["nome_usuario_logado"];
    $id_usuario_logado = $_POST["id_usuario_logado"];
    $nome = utf8_decode($_POST["nome"]);
    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];
    $confirmar_Senha = $_POST["confirmar_senha"];
    $perfil = $_POST["perfil"];
    $situacao =  $_POST["situacao"];
    $email =  $_POST["email"];
    $cargo =  $_POST["cargo"];
    $restricao_horario =  $_POST["restricao_horario"];
    $comissao =  $_POST["comissao"];

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

    if ($nome == "") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("nome");
    } elseif ($usuario == "") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("usuário");
    } elseif ($senha == "") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("senha");
    } elseif ($confirmar_Senha == "") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("confirmar senha");
    } elseif ($senha != $confirmar_Senha) {
        $retornar["mensagem"] = "A confirmação da senha está diferente da senha";
    } elseif ($perfil == "0") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("perfil");
    } elseif ($situacao == "s") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("situacao");
    } elseif ((!filter_var($email, FILTER_VALIDATE_EMAIL) and $email != "")) {
        $retornar["mensagem"] = "Esse Email não é valido, favor verifique!";
    } elseif ($cargo == "0") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("cargo");
    } elseif ($restricao_horario == "sn") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("restrição de horários");
    } else {

        if (verificar_user($conecta, $usuario, "cadastrar") > 0) {
            $retornar["mensagem"] = "Já existe uma pessoa com o mesmo nome de usuário, favor verifique";
        } else {
            $senha = base64_encode($senha); //criptografar a senha
            $inset = "INSERT INTO tb_users (cl_data_cadastro,cl_nome,cl_usuario,cl_senha,cl_tipo,cl_ativo,cl_email,
            cl_usuario_area,cl_vendedor,cl_autorizar_desconto,cl_cancelar_venda,cl_receber_alerta,
            cl_cancelar_pedido_delivery,cl_restricao_horario,cl_valor_comissao )
             VALUES ('$data','$nome','$usuario','$senha','$perfil','$situacao','$email','$cargo','$vendedor','$autorizar_desconto','$cancelar_venda',
             '$receber_alerta','$cancelar_pedido','$restricao_horario','$comissao')";
            $operacao_inserir = mysqli_query($conecta, $inset);
            if ($operacao_inserir) {
                $retornar["sucesso"] = true;
                //registrar no log
                $mensagem =  utf8_decode("Usuário $nome_usuario_logado cadastrou o novo usuário $usuario");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }

    echo json_encode($retornar);
}

//editar usuario
//pegar o id do usuario
if (isset($_GET['editar_user']) == true or isset($_GET['resetar_senha']) == true) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";

    $id_user = $_GET['id_user'];

    $select = "SELECT * from tb_users where cl_id = $id_user ";
    $consultar_usuarios = mysqli_query($conecta, $select);
    $linha  = mysqli_fetch_assoc($consultar_usuarios);
    $usuario_b = $linha['cl_usuario'];
    $nome_b = utf8_encode($linha['cl_nome']);
    $senha_b = base64_decode($linha['cl_senha']);
    $perfil_b = $linha['cl_tipo'];
    $situacao_b = $linha['cl_ativo'];
    $email_b = $linha['cl_email'];
    $cargo_b = $linha['cl_usuario_area'];
    $vendedor_b = $linha['cl_vendedor'];
    $autorizar_desconto = $linha['cl_autorizar_desconto'];
    $cancelar_venda = $linha['cl_cancelar_venda'];
    $receber_alerta = $linha['cl_receber_alerta'];
    $cancelar_pedido = $linha['cl_cancelar_pedido_delivery'];
    $restricao_horario = $linha['cl_restricao_horario'];
    $comissao = $linha['cl_valor_comissao'];
}


if (isset($_POST['formulario_editar_usuario'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $nome_usuario_logado = $_POST["nome_usuario_logado"];
    $id_usuario_logado = $_POST["id_usuario_logado"];
    $id_user = $_POST["id_user"];
    $nome = utf8_decode($_POST["nome"]);
    $usuario = $_POST["usuario"];
    $perfil = $_POST["perfil"];
    $situacao =  $_POST["situacao"];
    $email =  $_POST["email"];
    $cargo =  $_POST["cargo"];
    $restricao_horario =  $_POST["restricao_horario"];
    $comissao =  $_POST["comissao"];


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
    if (isset($_POST['receber_alerta'])  or $perfil == "adm" or $cargo == "GERENTE") {
        $receber_alerta = "SIM";
    } else {
        $receber_alerta = "NAO";
    }
    if (isset($_POST['cancelar_pedido'])  or $perfil == "adm" or $cargo == "GERENTE") { //cancelar pedido delivery
        $cancelar_pedido = "SIM";
    } else {
        $cancelar_pedido = "NAO";
    }


    if ($nome == "") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("nome");
    } elseif ($perfil == "0") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("perfil");
    } elseif ($situacao == "s") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("situacao");
    } elseif ((!filter_var($email, FILTER_VALIDATE_EMAIL) and $email != "")) {
        $retornar["mensagem"] = "Esse Email não é valido, favor verifique";
    } elseif ($cargo == "0") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("cargo");
    } elseif ($restricao_horario == "sn") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("restrição de horários");
    } else {

        if (verificar_user($conecta, $usuario, "editar") == $id_user or verificar_user($conecta, $usuario, "editar") == "") {
            $update = "UPDATE tb_users set cl_nome = '$nome',cl_tipo = '$perfil',cl_ativo ='$situacao',cl_email='$email',cl_usuario_area ='$cargo',
            cl_vendedor='$vendedor',cl_autorizar_desconto='$autorizar_desconto', cl_cancelar_venda='$cancelar_venda',
            cl_receber_alerta ='$receber_alerta',cl_cancelar_pedido_delivery= '$cancelar_pedido',
            cl_restricao_horario= '$restricao_horario', cl_valor_comissao= '$comissao' where cl_id = $id_user ";
            $operacao_update = mysqli_query($conecta, $update);
            if ($operacao_update) {
                $retornar["sucesso"] = true;
                //registrar no log
                $mensagem =  utf8_decode("Usuário $nome_usuario_logado alterou dados do usuário $usuario");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        } else {
            $retornar["mensagem"] = "Já existe uma pessoa com o mesmo nome de usuário, favor, verifique!";
        }
    }


    echo json_encode($retornar);
}


//resetar senha
if (isset($_POST['formulario_resetar_senha_usuario'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $nome_usuario_logado = $_POST["nome_usuario_logado"];
    $id_usuario_logado = $_POST["id_usuario_logado"];
    $id_user = $_POST["id_user"];
    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];
    $confirmar_Senha = $_POST["confirmar_senha"];

    if ($senha == "") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("senha");
    } elseif ($confirmar_Senha == "") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("confirmar senha");
    } elseif ($senha != $confirmar_Senha) {
        $retornar["mensagem"] = "A confirmação da senha está diferente da senha";
    } else {
        $senha = base64_encode($senha); //criptografar a senha
        $update = "UPDATE tb_users set cl_senha = '$senha' where cl_id = $id_user ";
        $operacao_update = mysqli_query($conecta, $update);
        if ($operacao_update) {
            $retornar["sucesso"] = true;
            $mensagem =  utf8_decode("Usuário $nome_usuario_logado Resetou a senha do  usuário $usuario");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }


    echo json_encode($retornar);
}
