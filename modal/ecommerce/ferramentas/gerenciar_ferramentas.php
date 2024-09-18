<?php


// //cadastrar formulario
if (isset($_POST['formulario_ferramentas_ecommerce'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $acao = $_POST['acao'];
    $usuario_id = verifica_sessao_usuario();
    $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");


    if ($acao == "gerar_link") {
        mysqli_begin_transaction($conecta);
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples

        }

        if (empty($url_link_deep_link)) {
            $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro("link"));
        } else {

            // Limpar e sanitizar o URL fornecido pelo usuário
            $link = htmlspecialchars($url_link_deep_link);

            // Obter o domínio base do link
            $dominio_base = obterDominioBase($link);

            // Remover 'http://' ou 'https://'
            $link_sem_protocolo = removerProtocolo($link);

            if ($btnApp == "all") {
                if ($btnDisp == "android") {
                    // // Concatenar o link sem protocolo com o esquema de redirecionamento 'intent://'
                    // $link_concatenado = "intent://$link_sem_protocolo/#Intent;package=com.android.chrome;scheme=https;end";//abrir no chrome
                    $link_concatenado = "intent://$link_sem_protocolo/#Intent;scheme=https;end"; //abrir no navegador padrão
                    $retornar["dados"] = array("sucesso" => true, "link" => $link_concatenado);
                } elseif ($btnDisp == "ios") {
                    $link_concatenado = "https://$link_sem_protocolo?utm_source=instagram&utm_medium=ad&utm_campaign=redirect";
                    $retornar["dados"] = array("sucesso" => true, "link" => $link_concatenado);
                }
            }
        }
    }



    echo json_encode($retornar);
}
