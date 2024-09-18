<?php
if (isset($_FILES) && !empty($_FILES)) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";

    $retornar = array();

    $usuario_id = verifica_sessao_usuario();
    $nome_usuario_logado = (consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario"));
    $permitidos = array(".png");
    $nome_imagem    = $_FILES['file-input']['name'];
    $tamanho_imagem = $_FILES['file-input']['size'];

    $ext = strtolower(strrchr($nome_imagem, "."));

    if (in_array($ext, $permitidos)) {
        $tamanho_kb = round($tamanho_imagem / 1024);

        if ($tamanho_kb <= 700) {
            copy($_FILES['file-input']['tmp_name'], '../../../img/logo.png');
            $retornar["dados"] = array("sucesso" => true, "title" => "Imagem alterada com sucesso");
            $mensagem =  utf8_decode("Alterou a logo da empresa");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "A imagem deve ter no máximo 700kb");
        }
    } else {
        $retornar["dados"] = array("sucesso" => false, "title" => "Somente são aceitos imagem com extensão .png, favor tente novamente");
    }
} else {
    $retornar["dados"] = array("sucesso" => false, "title" => "Favor, selecione uma imagem");
}

echo json_encode($retornar);
