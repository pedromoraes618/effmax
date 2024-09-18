<?php
if (isset($_FILES) && !empty($_FILES)) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";

    $permitidos = array(".mp4");
    $retornar = array();

    $descricap_arquivo    = $_FILES['file-input']['name'];
    $tamanho_arquivo = $_FILES['file-input']['size'];

    $ext = strtolower(strrchr($descricap_arquivo, "."));

    if (in_array($ext, $permitidos)) {
        $max_size_allowed = 3097152; // 2MB em bytes

        if ($tamanho_arquivo <= $max_size_allowed) { // se a imagem for até 2MB, envia

            $descrição_atual = md5(uniqid(time())) . $ext;

            copy($_FILES['file-input']['tmp_name'], '../../../img/baner/' . $descrição_atual);

            $insert = "INSERT INTO `tb_baner_delivery` (`cl_arquivo`) VALUES ('$descrição_atual') ";
            $operacao_insert = mysqli_query($conecta, $insert);

            $retornar["dados"] = array("sucesso" => true, "title" => 'Upload realizado com sucesso');
        } else {
            $retornar["dados"] = array("sucesso" => false, "valores" => "O arquivo deve ter no máximo 3 mb");
        }
    } else {
        $retornar["dados"] = array("sucesso" => false, "valores" => "Somente são aceitos arquivos do tipo video, favor tente novamente");
    }
} else {
    $retornar["dados"] = array("sucesso" => false, "valores" => "Favor selecione o arquivo");
}

echo json_encode($retornar);
