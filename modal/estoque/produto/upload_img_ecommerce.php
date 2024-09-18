<?php
if (isset($_FILES) && !empty($_FILES)) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";

    $permitidos = array(".jpg", ".jpeg", ".png", ".PNG", ".JPG", ".JPEG");
    $retornar = array();

    $nome_imagem    = $_FILES['file-input']['name'];
    $tamanho_imagem = $_FILES['file-input']['size'];

    $ext = strtolower(strrchr($nome_imagem, "."));

    $ordem = $_POST['ordem'];
    $codigo_nf = $_POST['codigo_nf'];

    if (in_array($ext, $permitidos)) {
        $tamanho = round($tamanho_imagem / 1024); // convertendo para KB

        if ($tamanho_imagem <= 1000000) { // se a imagem for até 1 MB, envia
            $nome_imagem = $codigo_nf . "_" . $ordem;

            $imagem_id = consulta_tabela($conecta, "tb_imagem_produto", "cl_descricao", $nome_imagem, "cl_id");
            if ($imagem_id == "") {
                $insert = "INSERT INTO `tb_imagem_produto` (`cl_codigo_nf`, `cl_descricao`,`cl_extensao`)
             VALUES ('$codigo_nf', '$nome_imagem', '$ext')";
                $operacao_insert = mysqli_query($conecta, $insert);
                if ($operacao_insert) {
                    $retornar["dados"] = array("sucesso" => true, "title" => "Imagem alterada com sucesso");

                    // Caminho da imagem temporária
                    $caminho_temporario = $_FILES['file-input']['tmp_name'];

                    copy($_FILES['file-input']['tmp_name'], '../../../img/produto/' . $nome_imagem . $ext);
                } else {
                    $retornar["dados"] = array("sucesso" => false, "title" => "erro, favor contatar o suporte");
                }
            } else {
                // Se a imagem já existe, você também precisará redimensioná-la e salvar a nova versão
                // Código para atualizar a imagem existente...
                $update = "UPDATE tb_imagem_produto set cl_descricao = '$nome_imagem',cl_extensao='$ext' where cl_id = '$imagem_id'";
                $operacao_update = mysqli_query($conecta, $update);
                if ($operacao_update) {
                    $retornar["dados"] = array("sucesso" => true, "title" => "Imagem alterada com sucesso");
                    // Caminho da imagem temporária
                    $caminho_temporario = $_FILES['file-input']['tmp_name'];
                    copy($_FILES['file-input']['tmp_name'], '../../../img/produto/' . $nome_imagem . $ext);
                } else {
                    $retornar["dados"] = array("sucesso" => false, "title" => "erro, favor contatar o suporte");
                }
            }
        } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "A imagem deve ter no máximo 1 mb");
        }
    } else {
        $retornar["dados"] = array("sucesso" => false, "title" => "Formato do arquivo (.$ext) é inválido, favor, tente novamente ");
    }
} else {
    $retornar["dados"] = array("sucesso" => false, "title" => "Favor selecione uma imagem ");
}
echo json_encode($retornar);
