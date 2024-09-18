<?php
if (isset($_POST['subgrupo_selecionado'])) { //trazer informações do subgrupo de estoque
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $id_subgrupo = $_POST['id_subgrupo'];
    $retornar = array();

    $consultar_automatizar_campo_produto =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "2"); //VERIFICAR PARAMETRO ID - 2 verificar se está habilitado a função de preencher as informações automaicamente no campos do produto 
    //consultar subgrupo para trazer as informações para o produto
    if ($consultar_automatizar_campo_produto == "S") {
        $select = "SELECT * from tb_subgrupo_estoque where cl_id = $id_subgrupo ";
        $consultar_subgrupo = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consultar_subgrupo);
        $estoque_minimo_b = $linha['cl_estoque_minimo'];
        $estoque_inicial_b = $linha['cl_estoque_inicial'];
        $estoque_maximo_b = $linha['cl_estoque_maximo'];
        $local_b = $linha['cl_local'];
        $unidade_b = $linha['cl_und_id'];
        $cfop_interno_b = $linha['cl_cfop_interno'];
        $cfop_externo_b = $linha['cl_cfop_externo'];

        $ncm = ($linha['cl_ncm']);
        $cst_icms = ($linha['cl_cst_icms']);
        $cst_pis_s = ($linha['cl_cst_pis_s']);
        $cst_pis_e = ($linha['cl_cst_pis_e']);
        $cst_cofins_s = ($linha['cl_cst_cofins_s']);
        $cst_cofins_e = ($linha['cl_cst_cofins_e']);

        $peso_kg = consulta_tabela($conecta, 'tb_unidade_medida', "cl_id", $unidade_b, "cl_peso_kg");



        $retornar["dados"] = array(
            "sucesso" => true, "estoque_minimo" => $estoque_minimo_b,
            "estoque_inicial" => $estoque_inicial_b, "estoque_maximo" => $estoque_maximo_b,
            "local" => $local_b, "unidade" => $unidade_b, "cfop_interno" => $cfop_interno_b,
            "cfop_extero" => $cfop_externo_b,
            "ncm" => $ncm,
            "cst_icms" => $cst_icms,
            "cst_pis_s" => $cst_pis_s,
            "cst_pis_e" => $cst_pis_e,
            "cst_cofins_s" => $cst_cofins_s,
            "cst_cofins_e" => $cst_cofins_e,
            "peso_kg" => $peso_kg,

        );
    } else {
        $retornar["dados"] = array("sucesso" => false);
    }


    echo json_encode($retornar);
}
