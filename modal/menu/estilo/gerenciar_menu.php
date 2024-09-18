<?php
//cadastrar formulario
if(isset($_POST['background'])){
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
        $retornar = array();
        $backuground_principal = verficar_paramentro($conecta,"tb_parametros","cl_id","7");
        $color_texto =  verficar_paramentro($conecta,"tb_parametros","cl_id","8");
        
        $informacao = array(
            "background_nav" => $backuground_principal,
            "cor_texto" => $color_texto,
         );

        $retornar["dados"] =array("sucesso"=>true,"valores"=>$informacao);
        
        
    echo json_encode($retornar);
}
