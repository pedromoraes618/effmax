<?php

include "funcao/funcao.php";
$id_user_logado = verifica_sessao_usuario();
$nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $id_user_logado, "cl_usuario");

if (isset($_GET['ctg']) and isset($_GET['id']) and $nome_usuario_logado != "") {

    $id_subctg = $_GET['id'];
    if ((consulta_tabela_2_filtro($conecta, "tb_acessos", 'cl_usuario_id', $id_user_logado, 'cl_subcategoria', $id_subctg, "cl_acesso_ativo") == "1") or (consulta_tabela($conecta, "tb_users", "cl_id", $id_user_logado, "cl_tipo") == "suporte")) { //validar se o usuario tem o acesso
        if (consultar_diretorio_bd($id_subctg) != "") { //diretorio dos arquivos bd

            include "modal/" . consultar_diretorio_bd($id_subctg);
            include "view/" . consultar_subcategoria($id_subctg);
        }
    } else { //redirect
        include "view/dashboard/inicial/gerenciar_dashboard.php";
    }
} else {
    //dashboard inicial 
    include "view/dashboard/inicial/gerenciar_dashboard.php";
}

include "view/title/titulo.php";
?>
<!-- usuario logado -->
<input type="hidden" id="user_logado" value="<?php echo $usuario ?>">
<input type="hidden" id="id_user_logado" value="<?php echo $id_user ?>">
<input type="hidden" id="perfil_user_logado" value="<?php echo $tipo ?>">