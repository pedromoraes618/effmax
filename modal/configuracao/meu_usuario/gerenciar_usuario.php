<?php
//consultar informações para tabela
if (isset($_POST['consultar_meu_user'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $acao = $_POST['acao'];
    if ($acao == "show") {
        $id_usuario = $_POST['id_user'];
        $select = "SELECT * from tb_users WHERE cl_id = $id_usuario";
        $consultar_usuario = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consultar_usuario);
        $nome = utf8_encode($linha['cl_nome']);
        $usuario = $linha['cl_usuario'];
        $tipo_usuario = $linha['cl_tipo'];
        $email = $linha['cl_email'];
        $img = $linha['cl_img'];

        $informacao = array(
            "nome" => $nome,
            "usuario" => $usuario,
            "tipo_usuario" => $tipo_usuario,
            "email" => $email,
            "img" => $img,
        );
        $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
    } elseif ($acao == "update") {
        $id_usuario = $_POST['id_user'];
        $nome_usuario_logado = $_POST['user_logado'];
        $img = $_POST['img'];
        $update = " UPDATE `tb_users` SET `cl_img` = '$img' where cl_id='$id_usuario'";
        $update_usuario = mysqli_query($conecta, $update);
        if ($update_usuario) {
            $retornar["dados"] = array("sucesso" => true, "valores" => "Imagem alterada com sucesso");
            $mensagem =  (utf8_decode("Usúario") . " $nome_usuario_logado alterou a sua foto");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        } else {
            $retornar["dados"] = array("sucesso" => false, "valores" => "Erro, favor contatar o suporte");
            $mensagem =  (utf8_decode("Usúario") . " $nome_usuario_logado tentou alterar a sua foto sem sucesso");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    } elseif ($acao == "update_senha") { //resetar a senha
        $id_usuario = $_POST['id_user'];
        $nome_usuario_logado = $_POST['user_logado'];
        $nova_senha = $_POST['nova_senha'];
        $confirmar_senha = $_POST['confirmar_senha'];
        if ($nova_senha == "" or $confirmar_senha == "") {
            $retornar["dados"] = array("sucesso" => false, "title" => "Favor informe todos os campos");
        } elseif ($nova_senha != $confirmar_senha) {
            $retornar["dados"] = array("sucesso" => false, "title" => "A confirmação de senha difere da sua nova senha, favor verifique");
        } else {
            $nova_senha = base64_encode($nova_senha); //criptografar a senha
            $update = "UPDATE tb_users set cl_senha = '$nova_senha' where cl_id = $id_usuario ";
            $update_usuario = mysqli_query($conecta, $update);
            if ($update_usuario) {
                $retornar["dados"] = array("sucesso" => true, "title" => "Senha alterada com sucesso");
                $mensagem =  (utf8_decode("Usúario") . " $nome_usuario_logado alterou a sua senha");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            } else {
                $retornar["dados"] = array("sucesso" => false, "valores" => "Erro, favor contatar o suporte");
                $mensagem =  (utf8_decode("Usúario") . "$nome_usuario_logado tentou alterar a sua senha sem sucesso");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }
    echo json_encode($retornar);
}
