<?php
if (isset($_FILES) && !empty($_FILES)) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";

    $permitidos = array(".jpg", ".jpeg", ".png");
    $retornar = array();

    $nome_imagem    = $_FILES['file-input']['name'];
    $tamanho_imagem = $_FILES['file-input']['size'];

    $ext = strtolower(strrchr($nome_imagem, "."));

    if (in_array($ext, $permitidos)) {
        $tamanho = round($tamanho_imagem / 1024); // convertendo para KB

        if ($tamanho_imagem <= 800000) { // se a imagem for até 800 KB, envia
            $nome_atual = md5(uniqid(time())) . $ext;

            copy($_FILES['file-input']['tmp_name'], '../../../img/produto/' . $nome_atual);

            $informacao = array(
                "name_arquivo" => $nome_atual,
                "mensagem" => "Imagem alterada com sucesso",
            );

            $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
        } else {
            $retornar["dados"] = array("sucesso" => false, "valores" => "A imagem deve ter no máximo 800 KB");
        }
    } else {
        $retornar["dados"] = array("sucesso" => false, "valores" => "Somente são aceitos arquivos do tipo imagem, favor tente novamente");
    }
} else {
    $retornar["dados"] = array("sucesso" => false, "valores" => "Favor selecione uma imagem");
}

echo json_encode($retornar);
