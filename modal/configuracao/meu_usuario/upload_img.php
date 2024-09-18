<?php
if(isset($_FILES)){
if(!empty($_FILES)){
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
/* formatos de imagem permitidos */
$permitidos = array(".jpg", ".jpeg", ".png");
$retornar = array();
$nome_imagem    = $_FILES['file-input']['name'];
$tamanho_imagem = $_FILES['file-input']['size'];

/* pega a extensão do arquivo */
$ext = strtolower(strrchr($nome_imagem, "."));

/*  verifica se a extensão está entre as extensões permitidas */
if (in_array($ext, $permitidos)) {
    /* converte o tamanho para KB */
    $tamanho = round($tamanho_imagem / 10024);

    if ($tamanho < 10024) { //se imagem for até 1MB envia
        $nome_atual = md5(uniqid(time())) . $ext;

        //caminho temporário da imagem
        copy($_FILES['file-input']['tmp_name'], '../../../img/usuario/' . $nome_atual);
        $informacao = array(
            "name_arquivo" => $nome_atual,
            "mensagem" => "Imagem alterada com sucesso",
        );

        $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);

        /* se enviar a foto, insere o nome da foto no banco de dados */
    } else {
        $retornar["dados"] = array("sucesso" => false, "valores" => "A imagem deve ser no máximo 1MB");
    }
} else {
    $retornar["dados"] = array("sucesso" => false, "valores" => "Somente são aceitos arquivos do tipo Imagemm, favor tente novamente");
}}else{
    $retornar["dados"] = array("sucesso" => false, "valores" => "Favor selecione uma imagem");
}

echo json_encode($retornar);
}

//
