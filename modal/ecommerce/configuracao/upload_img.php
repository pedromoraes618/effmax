<?php
// Verifica se há um arquivo enviado // logo do site
if (isset($_FILES['file-input']) && $_FILES['file-input']['error'] === UPLOAD_ERR_OK) {
    // Inclui arquivos necessários
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";

    $usuario_id = verifica_sessao_usuario();
    $nome_usuario_logado = (consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario"));

    // Inicializa o array de retorno
    $retornar = array();

    // Obtém informações sobre o arquivo enviado
    $permitidos = array(".png");
    $nome_imagem    = $_FILES['file-input']['name'];
    $tamanho_imagem = $_FILES['file-input']['size'];

    $ext = strtolower(strrchr($nome_imagem, "."));

    // Verifica se a extensão do arquivo é permitida
    if (in_array($ext, $permitidos)) {
        // Converte o tamanho do arquivo para KB
        $tamanho_kb = round($tamanho_imagem / 1024);

        // Verifica se o tamanho está dentro do limite (700 KB)
        if ($tamanho_kb <= 700) {
            // Move o arquivo para o diretório desejado
            $destino = '../../../img/ecommerce/logo/logo.png';

            if (move_uploaded_file($_FILES['file-input']['tmp_name'], $destino)) {
                $retornar["dados"] = array("sucesso" => true, "title" => "Imagem alterada com sucesso");
                $mensagem =  utf8_decode("Alterou a logo da loja");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            } else {
                $retornar["dados"] = array("sucesso" => false, "title" => "Erro ao mover o arquivo para o diretório de destino");
            }
        } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "A imagem deve ter no máximo 700 KB");
        }
    } else {
        $retornar["dados"] = array("sucesso" => false, "title" => "Somente são aceitos arquivos com extensão .png, favor tentar novamente");
    }
} elseif (isset($_FILES['file-input-baner-topo']) && $_FILES['file-input-baner-topo']['error'] === UPLOAD_ERR_OK) { //baner topo
    // Inclui arquivos necessários
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";

    $usuario_id = verifica_sessao_usuario();
    $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");

    // Verifica se o usuário está logado
    if ($usuario_id) {
        // Inicializa o array de retorno
        $retornar = array();

        // Obtém informações sobre o arquivo enviado
        $permitidos = array(".png", ".svg", ".jpg", ".jpeg");
        $nome_imagem    = $_FILES['file-input-baner-topo']['name'];
        $tamanho_imagem = $_FILES['file-input-baner-topo']['size'];

        $ext = strtolower(strrchr($nome_imagem, "."));

        // Verifica se a extensão do arquivo é permitida
        if (in_array($ext, $permitidos)) {
            // Converte o tamanho do arquivo para KB
            $tamanho_kb = round($tamanho_imagem / 1024);

            // Verifica se o tamanho está dentro do limite (700 KB)
            if ($tamanho_kb <= 900) {
                // Move o arquivo para o diretório desejado
                $descricao_imagem = md5(uniqid(time())) . $ext;
                $destino = "../../../img/ecommerce/baner/$descricao_imagem";

                if (move_uploaded_file($_FILES['file-input-baner-topo']['tmp_name'], $destino)) {
                    $link_redirect = isset($_POST['link_redirect']) ? $_POST['link_redirect'] : '';
                    $query = "SELECT MAX(cl_ordem) as ordem FROM `tb_baner_delivery` ";
                    $consulta = mysqli_query($conecta, $query);
                    $linha = mysqli_fetch_assoc($consulta);
                    $ordem = $linha['ordem'];
                    if (empty($ordem)) {
                        $nova_posicao = 1;
                    } else {
                        $nova_posicao = $ordem + 1;
                    }
                    $query = "INSERT INTO `tb_baner_delivery` (`cl_arquivo`,`cl_ordem`,`cl_link`,`cl_inicial`) VALUES ( '$descricao_imagem','$nova_posicao','$link_redirect',1 ) ";
                    $insert = mysqli_query($conecta, $query);
                    if ($insert) {
                        $retornar["dados"] = array("sucesso" => true, "title" => "Upload efetuado com sucesso");
                        $mensagem = utf8_decode("Adicionou um banner $descricao_imagem ao site");
                        registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                    } else {
                        $retornar["dados"] = array("sucesso" => false, "title" => "Erro ao mover o arquivo para o diretório de destino");
                        $mensagem = utf8_decode("Tentativa sem sucesso de adicionar um banner ao site");
                        registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                    }
                } else {
                    $retornar["dados"] = array("sucesso" => false, "title" => "Erro ao mover o arquivo para o diretório de destino");
                    $mensagem = utf8_decode("Tentativa sem sucesso de adicionar um banner $descricao_imagem ao site - Erro ao mover o arquivo para o diretório de destino");
                    registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                }
            } else {
                $retornar["dados"] = array("sucesso" => false, "title" => "A imagem deve ter no máximo 900 KB");
            }
        } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Somente são aceitos arquivos com extensão .png, favor tentar novamente");
        }
    } else {
        $retornar["dados"] = array("sucesso" => false, "title" => "Usuário não está logado");
    }
} elseif (isset($_FILES['file-input-baner-section']) && $_FILES['file-input-baner-section']['error'] === UPLOAD_ERR_OK) { //baner topo

    // Inclui arquivos necessários
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";

    $usuario_id = verifica_sessao_usuario();
    $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");

    // Verifica se o usuário está logado

    // Inicializa o array de retorno
    $retornar = array();

    // Obtém informações sobre o arquivo enviado
    $permitidos = array(".png", ".svg", ".jpg", ".jpeg");
    $nome_imagem    = $_FILES['file-input-baner-section']['name'];
    $tamanho_imagem = $_FILES['file-input-baner-section']['size'];

    $ext = strtolower(strrchr($nome_imagem, "."));

    // Verifica se a extensão do arquivo é permitida
    if (!in_array($ext, $permitidos)) {
        $retornar["dados"] = array("sucesso" => false, "title" => "Somente são aceitos arquivos com extensão .png, favor tentar novamente");
    } else {
        // Converte o tamanho do arquivo para KB
        $tamanho_kb = round($tamanho_imagem / 1024);

        // Verifica se o tamanho está dentro do limite (700 KB)
        if ($tamanho_kb > 3072) {
            $retornar["dados"] = array("sucesso" => false, "title" => "A imagem deve ter no máximo 300 KB");
        } else {
            // Move o arquivo para o diretório desejado
            $descricao_imagem = md5(uniqid(time())) . $ext;
            $destino = "../../../img/ecommerce/baner/$descricao_imagem";

            if (!move_uploaded_file($_FILES['file-input-baner-section']['tmp_name'], $destino)) {
                $retornar["dados"] = array("sucesso" => false, "title" => "Erro ao mover o arquivo para o diretório de destino");
                $mensagem = utf8_decode("Tentativa sem sucesso de adicionar um banner $descricao_imagem ao site - Erro ao mover o arquivo para o diretório de destino");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            } else {
                $section = isset($_POST['section']) ? $_POST['section'] : '';
                $link_redirect = isset($_POST['link_redirect']) ? $_POST['link_redirect'] : '';
                $id_referencia = isset($_POST['id_referencia']) ? $_POST['id_referencia'] : '';

                $select = "SELECT * FROM `tb_baner_delivery` where ( cl_secao = '$section' and cl_id_referencia='$id_referencia' ) or ( cl_secao ='$section' and cl_id_referencia!='' ) ";
                $consulta = mysqli_query($conecta, $select);
                $qtd_baner = mysqli_num_rows($consulta);
                if ($qtd_baner == 0) {
                    $query = "INSERT INTO `tb_baner_delivery` ( `cl_arquivo`,`cl_secao`,`cl_id_referencia` ) VALUES ( '$descricao_imagem','$section','$id_referencia' ) ";
                } else {
                    $query = "UPDATE `tb_baner_delivery` SET  `cl_arquivo` = '$descricao_imagem', `cl_secao` = '$section', `cl_id_referencia` = '$id_referencia'  WHERE `cl_secao` = '$section' ";

                    /*remove  a imagem anterior */
                    $linha = mysqli_fetch_assoc($consulta);
                    $arquivo_existente = $linha['cl_arquivo'];
                    $dir_baner_existente = '../../../img/ecommerce/baner/' . $arquivo_existente; //remover o baner anterior
                    $dados = array("dir" => $dir_baner_existente);
                    delete_img($dados);
                }
                $operacao_query = mysqli_query($conecta, $query);
                if ($operacao_query) {
                    $retornar["dados"] = array("sucesso" => true, "title" => "Upload efetuado com sucesso");
                    $mensagem = utf8_decode("Adicionou um banner $descricao_imagem ao site");
                    registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                } else {
                    $retornar["dados"] = array("sucesso" => false, "title" => "Erro ao realizar o update do baner, favor, contatar o suporte");
                    $mensagem = utf8_decode("Tentativa sem sucesso de adicionar um banner ao site");
                    registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                }
            }
        }
    }
} else {
    $retornar["dados"] = array("sucesso" => false, "title" => "Favor, selecione um arquivo para enviar");
}


// Retorna a resposta em formato JSON
echo json_encode($retornar);
