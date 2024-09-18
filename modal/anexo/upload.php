<?php
// Inicializa o array de retorno
$retornar = array();

if (isset($_FILES['file_input_anexo']) && $_FILES['file_input_anexo']['error'] === UPLOAD_ERR_OK) {

    // Inclui arquivos necessários
    include "../../conexao/conexao.php";
    include "../../funcao/funcao.php";
    $name_file  = $_FILES['file_input_anexo']['name'];
    $size_file = $_FILES['file_input_anexo']['size'];

    $ext = strtolower(strrchr($name_file, "."));

    $usuario_id = verifica_sessao_usuario();
    $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");

    // Converte o tamanho do arquivo para KB
    $size_kb = round($size_file / 1024);

    if ($size_kb <= 10000) {
        // Move o arquivo para o diretório desejado
        $arquivo = md5(uniqid(time())) . $ext;
        $arquivo = strtolower($arquivo);
        $destino = ".././../arquivos/anexo/$arquivo";

        $codigo_nf = $_POST['codigo_nf'];
        $form_id = $_POST['form_id'];
        $tipo = $_POST['tipo'];
        $descricao = utf8_decode($_POST['descricao']);

        if ($descricao != "") {
            if (move_uploaded_file($_FILES['file_input_anexo']['tmp_name'], $destino)) {
                $nome_original = utf8_decode($name_file);
                $query = "INSERT INTO `tb_anexo` (`cl_data_lancamento`, `cl_form_id`, 
                `cl_codigo_nf`, `cl_descricao`, `cl_arquivo`,`cl_nome_original`, `cl_tipo`, `cl_usuario_id` )
                 VALUES ( '$data_lancamento', '$form_id', '$codigo_nf', '$descricao', '$arquivo','$nome_original','$tipo', '$usuario_id' ) ";
                $insert = mysqli_query($conecta, $query);
                if ($insert) {
                    $retornar["dados"] = array("sucesso" => true, "title" => "Upload efetuado com sucesso");
                    $mensagem = utf8_decode("Adicionou o arquivo $nome_original com a descrição $descricao do tipo $tipo");
                    registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                } else {
                    $retornar["dados"] = array("sucesso" => false, "title" => "erro, favor contatar o suporte");
                    $mensagem = utf8_decode("Tentativa sem sucesso de Adicionar o arquivo $nome_original com a descrição $descricao do tipo $tipo");
                    registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                }
            } else {
                $retornar["dados"] = array("sucesso" => false, "title" => "Erro ao mover o arquivo para o diretório de destino");
            }
        } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Favor, informe o campo descrição");
        }
    } else {
        $retornar["dados"] = array("sucesso" => false, "title" => "O arquivo deve ter no máximo 1 mb");
    }
} else {
    $retornar["dados"] = array("sucesso" => false, "title" => "Favor, selecione um arquivo válido");
}
// Retorna a resposta em formato JSON
echo json_encode($retornar);
