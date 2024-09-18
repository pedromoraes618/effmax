<?php
if (isset($_FILES['file-input-img-produto']) && !empty($_FILES['file-input-img-produto']['name'][0])) {
    // Inclui arquivos necessários
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";

    $usuario_id = verifica_sessao_usuario();
    $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");

    // Inicializa o array de retorno
    $retornar = array();
    $permitidos = array(".png", ".svg", ".jpg", ".jpeg");
    $response = array("status" => true);

    $codigo_nf = $_POST['codigo_nf'];
    $produto_id = consulta_tabela($conecta, 'tb_produtos', 'cl_codigo', $codigo_nf, 'cl_id');
    mysqli_begin_transaction($conecta);

    // Processa cada arquivo
    foreach ($_FILES['file-input-img-produto']['name'] as $key => $nome_imagem) {
        $tamanho_imagem = $_FILES['file-input-img-produto']['size'][$key];
        $ext = strtolower(strrchr($nome_imagem, "."));

        // Verifica se a extensão do arquivo é permitida
        if (!in_array($ext, $permitidos)) {
            $response = array("status" => false, "mensagem" => "Somente são aceitos arquivos com extensão .png, favor tentar novamente");
            break;
        }
        // Converte o tamanho do arquivo para KB
        $tamanho_kb = round($tamanho_imagem / 1024);

        // Verifica se o tamanho está dentro do limite (800 KB)
        if ($tamanho_kb > 900) {
            $response = array("status" => false, "mensagem" => "A imagem deve ter no máximo 900 KB");
            break;
        }

        // Move o arquivo para o diretório desejado
        $descricao_imagem = md5(uniqid(time())) . $ext;
        $descricao_imagem = strtolower($descricao_imagem);
        $destino = "../../../img/produto/$descricao_imagem";

        if (!move_uploaded_file($_FILES['file-input-img-produto']['tmp_name'][$key], $destino)) {
            $response = array("status" => false, "mensagem" => "Erro ao mover o arquivo para o diretório de destino");
            break;
        }

        /*Ordem das imagens já existente */
        $ordem = consulta_tabela_query($conecta, "SELECT MAX(cl_ordem) as ordem FROM `tb_imagem_produto` where cl_codigo_nf='$codigo_nf' ", 'ordem');
        if (empty($ordem)) {
            $nova_posicao = 1; //primeira imagem irá assumir a ordem 1
        } else {
            $nova_posicao = $ordem + 1;
        }

        /*Inserir a imagem no banco */
        $query = "INSERT INTO `tb_imagem_produto` (`cl_codigo_nf`,`cl_descricao`,`cl_ordem` ) VALUES ( '$codigo_nf','$descricao_imagem','$nova_posicao' ) ";
        $insert = mysqli_query($conecta, $query);
        if (!$insert) {
            $response = array("status" => false, "mensagem" => "Erro, favor contatar o suporte");
            break;
        }
    }

    if ($response['status'] == true) {
        $retornar["dados"] = array("status" => true, "title" => "Upload realizado com sucesso");
        $mensagem = utf8_decode("Realizou upload de imagens para o produto de código $produto_id");
        registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        mysqli_commit($conecta);
    } else {
        mysqli_rollback($conecta);
        $retornar["dados"] = array("status" => false, "title" => $response['mensagem']);
        $mensagem = utf8_decode("Tentativa sem sucesso de realizar o upload de imagens para o produto de código $produto_id");
        registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
    }
} else {
    $retornar["dados"] = array("sucesso" => false, "title" => "Favor, selecione um arquivo para enviar");
}



// Retorna a resposta em formato JSON
echo json_encode($retornar);
