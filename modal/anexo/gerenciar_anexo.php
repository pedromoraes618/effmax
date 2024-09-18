<?php
if (isset($_GET['consultar_anexo'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $usuario_id = verifica_sessao_usuario();
    $codigo_nf =  isset($_GET['codigo_nf']) ? $_GET['codigo_nf'] : '';
    $form_id =  isset($_GET['form_id']) ? $_GET['form_id'] : '';
    $tipo =  isset($_GET['tipo']) ? $_GET['tipo'] : '';
}


if (isset($_GET['consultar_anexo_tabela'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";

    $pesquisa = utf8_decode($_GET['conteudo_pesquisa_anexo']); //filtro
    $tipo = utf8_decode($_GET['tipo']); //filtro
    $form_id = isset($_GET['form_id']) ? $_GET['form_id'] : ''; //filtro
    $codigo_nf = isset($_GET['codigo_nf']) ? $_GET['codigo_nf'] : ''; //filtro


    $query = "SELECT * from tb_anexo where cl_tipo = '$tipo' and 
    ( cl_descricao  like '%$pesquisa%'  )";

    if (!empty($form_id)) {
        $query .= " and cl_form_id='$form_id' ";
    }
    if (!empty($codigo_nf)) {
        $query .= " and cl_codigo_nf='$codigo_nf' ";
    }

    $query .= " order by cl_id ";
    $consulta = mysqli_query($conecta, $query);
    if (!$consulta) {
        die("Falha no banco de dados: " . mysqli_error($conecta));
    } else {
        $qtd = mysqli_num_rows($consulta);
    }
}





// //cadastrar formulario
if (isset($_POST['formulario_anexo'])) {
    include "../../conexao/conexao.php";
    include "../../funcao/funcao.php";
    $retornar = array();
    $acao = $_POST['acao'];
    $usuario_id = verifica_sessao_usuario();
    $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");

    if ($acao == "remover_arquivo") {
        // Iniciar uma transação MySQL
        mysqli_begin_transaction($conecta);
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
        }
        $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");


        if (empty($form_id)) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Arquivo não encontrado");
        } else {
            $nome_original = utf8_encode(consulta_tabela($conecta, 'tb_anexo', 'cl_id', $form_id, 'cl_nome_original'));
            $tipo = utf8_encode(consulta_tabela($conecta, 'tb_anexo', 'cl_id', $form_id, 'cl_tipo'));
            $descricao = utf8_encode(consulta_tabela($conecta, 'tb_anexo', 'cl_id', $form_id, 'cl_descricao'));
            $arquivo = utf8_encode(consulta_tabela($conecta, 'tb_anexo', 'cl_id', $form_id, 'cl_arquivo'));

            $query = "DELETE FROM `tb_anexo` WHERE `cl_id` = $form_id ";
            $delete = mysqli_query($conecta, $query);
            if ($delete) {
                $retornar["dados"] =  array("sucesso" => true, "title" => "Arquivo removido com sucesso");
                $mensagem = utf8_decode("Removeu o arquivo $nome_original com a descrição $descricao do tipo $tipo");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                $imagem_existente = '../../arquivos/anexo/' . $arquivo;
                if (file_exists($imagem_existente)) {
                    $lista_imagens = glob($imagem_existente);
                    foreach ($lista_imagens as $imagem) {
                        unlink($imagem); // Excluir a imagem existente
                    }
                }
                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de removeu o arquivo $nome_original com a descrição $descricao do tipo $tipo");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }



    echo json_encode($retornar);
}
