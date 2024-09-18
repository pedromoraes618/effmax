<?php
//consultar informações para tabela
if (isset($_GET['consultar_subgrupo'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $imagem_tabela = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "22");
    $sistema_delivery =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "42"); //Verificar se o sistema é para delivery

    $consulta = $_GET['consultar_subgrupo'];

    if ($consulta == "inicial") {
        $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //consultar parametro para carrregar inicialmente a tabela

        $select = "SELECT cl_img_subgrupo_estoque, sbrgt.cl_id, sbrgt.cl_descricao,grpest.cl_descricao as grupo,und.cl_descricao as unidade, sbrgt.cl_cfop_interno,sbrgt.cl_cfop_externo,sbrgt.cl_estoque_inicial,sbrgt.cl_estoque_minimo,
            sbrgt.cl_estoque_maximo,sbrgt.cl_local from tb_subgrupo_estoque as sbrgt inner join tb_grupo_estoque as grpest on grpest.cl_id = sbrgt.cl_grupo_id inner join tb_unidade_medida as und on und.cl_id = sbrgt.cl_und_id order by sbrgt.cl_id ";
        $consultar_subgrupo_estoque = mysqli_query($conecta, $select);
        if (!$consultar_subgrupo_estoque) {
            die("Falha no banco de dados");
        }
    } else {
        $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
        $select = "SELECT cl_img_subgrupo_estoque, sbrgt.cl_id, sbrgt.cl_descricao,grpest.cl_descricao as grupo,und.cl_descricao as unidade, sbrgt.cl_cfop_interno,sbrgt.cl_cfop_externo,sbrgt.cl_estoque_inicial,sbrgt.cl_estoque_minimo,
        sbrgt.cl_estoque_maximo,sbrgt.cl_local from tb_subgrupo_estoque as sbrgt inner join tb_grupo_estoque as grpest on grpest.cl_id = sbrgt.cl_grupo_id
         inner join tb_unidade_medida as und on und.cl_id = sbrgt.cl_und_id where sbrgt.cl_descricao like '%{$pesquisa}%' or grpest.cl_descricao like '%{$pesquisa}%' or sbrgt.cl_id like '%{$pesquisa}%'  order by sbrgt.cl_id";


        $consultar_subgrupo_estoque = mysqli_query($conecta, $select);
        if (!$consultar_subgrupo_estoque) {
            die("Falha no banco de dados");
        }
    }
}

//cadastrar dados formulario
if (isset($_POST['formulario_cadastrar_subgrupo_estoque'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $nome_usuario_logado = $_POST["nome_usuario_logado"];
    $id_usuario_logado = $_POST["id_usuario_logado"];


    $descricao = utf8_decode($_POST["descricao"]);
    $grupo_estoque_id = $_POST["grupo_estoque"];
    $estoque_inicial = $_POST['est_inicial'];
    $estoque_minimo = $_POST['est_minimo'];
    $estoque_maximo = $_POST['est_maximo'];
    $local_estoque = $_POST['local_estoque'];
    $unidade_medida_id = $_POST['unidade_md'];
    $cfop_interno = $_POST['cfop_interno'];
    $cfop_externo = $_POST['cfop_externo'];
    $img_subgrupo_estoque = $_POST['img_subgrupo_estoque'];
    $ncm = $_POST['ncm'];
    $cst_icms = $_POST['cst_icms'];
    $cst_pis_s = $_POST['cst_pis_s'];
    $cst_pis_e = $_POST['cst_pis_e'];
    $cst_cofins_s = $_POST['cst_cofins_s'];
    $cst_cofins_e = $_POST['cst_cofins_e'];


    if ($descricao == "") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("descricão");
    } elseif ($grupo_estoque_id == "0") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("grupo pai");
    } elseif ($unidade_medida_id == "0") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("unidade de medida");
    } elseif ($cfop_interno == "0") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("cfop interno");
    } elseif ($cfop_externo == "0") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("cfop externo");
    } else {
        if (isset($_POST['delivery'])) {
            $delivery = "SIM";
        } else {
            $delivery = "NAO";
        }

        $inset = "INSERT INTO tb_subgrupo_estoque (cl_descricao,cl_grupo_id,cl_cfop_interno,cl_cfop_externo,cl_estoque_inicial,cl_estoque_minimo,cl_estoque_maximo,
        cl_local,cl_und_id,cl_img_subgrupo_estoque,cl_delivery,cl_ncm,cl_cst_icms,cl_cst_pis_s,cl_cst_pis_e,cl_cst_cofins_s,cl_cst_cofins_e ) 
         VALUES ('$descricao','$grupo_estoque_id','$cfop_interno','$cfop_externo','$estoque_inicial','$estoque_minimo','$estoque_maximo','$local_estoque'
         ,'$unidade_medida_id','$img_subgrupo_estoque','$delivery','$ncm','$cst_icms','$cst_pis_s','$cst_pis_e','$cst_cofins_s','$cst_cofins_e' )";
        $operacao_inserir = mysqli_query($conecta, $inset);
        if ($operacao_inserir) {
            $retornar["sucesso"] = true;
            //registrar no log
            $mensagem =  (utf8_decode("Usúario") . " $nome_usuario_logado cadastrou o subgrupo $descricao ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }

    echo json_encode($retornar);
}


//Editar formulario
if (isset($_POST['formulario_editar_subgrupo_estoque'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();

    $nome_usuario_logado = $_POST["nome_usuario_logado"];
    $id_usuario_logado = $_POST["id_usuario_logado"];
    $perfil_usuario_logado = $_POST['perfil_usuario_logado'];

    $id_subgrupo = $_POST["id_subgrupo"];
    $descricao = utf8_decode($_POST["descricao"]);


    $descricao = utf8_decode($_POST["descricao"]);
    $grupo_estoque_id = $_POST["grupo_estoque"];
    $estoque_inicial = $_POST['est_inicial'];
    $estoque_minimo = $_POST['est_minimo'];
    $estoque_maximo = $_POST['est_maximo'];
    $local_estoque = $_POST['local_estoque'];
    $unidade_medida_id = $_POST['unidade_md'];
    $cfop_interno = $_POST['cfop_interno'];
    $cfop_externo = $_POST['cfop_externo'];
    $img_subgrupo_estoque = $_POST['img_subgrupo_estoque'];
    //$status_ativo_produto = ($_POST["status_ativo_produto"]);

    $ncm = $_POST['ncm'];
    $cst_icms = $_POST['cst_icms'];
    $cst_pis_s = $_POST['cst_pis_s'];
    $cst_pis_e = $_POST['cst_pis_e'];
    $cst_cofins_s = $_POST['cst_cofins_s'];
    $cst_cofins_e = $_POST['cst_cofins_e'];


    if ($descricao == "") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("descricão");
    } elseif ($grupo_estoque_id == "0") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("grupo pai");
    } elseif ($unidade_medida_id == "0") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("unidade de medida");
    } elseif ($cfop_interno == "0") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("cfop interno");
    } elseif ($cfop_externo == "0") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("cfop externo");
    } else {
        if (isset($_POST['delivery'])) {
            $delivery = "SIM";
        } else {
            $delivery = "NAO";
        }
        $update = "UPDATE tb_subgrupo_estoque set cl_descricao = '$descricao',cl_grupo_id='$grupo_estoque_id',cl_cfop_interno='$cfop_interno',cl_cfop_externo='$cfop_externo'
        ,cl_estoque_inicial='$estoque_inicial',cl_estoque_minimo='$estoque_minimo',cl_estoque_maximo='$estoque_maximo
        ',cl_local='$local_estoque',cl_und_id='$unidade_medida_id',cl_img_subgrupo_estoque='$img_subgrupo_estoque',cl_delivery='$delivery',cl_ncm='$ncm',
        cl_cst_icms='$cst_icms',cl_cst_pis_s='$cst_pis_s',cl_cst_pis_e='$cst_pis_e',cl_cst_cofins_s='$cst_cofins_s',cl_cst_cofins_e='$cst_cofins_e'  where cl_id = $id_subgrupo";
        $operacao_update = mysqli_query($conecta, $update);
        if ($operacao_update) {
            $retornar["sucesso"] = true;
            // if ($status_ativo_produto != "0") {
            //     update_registro($conecta, "tb_produtos", "cl_grupo_id", $id_subgrupo, "", "", "cl_status_ativo", "$status_ativo_produto"); //ativar ou inativas os produto que estão atrelados ao subgrupo
            // }
            //registrar no log
            $mensagem =  utf8_decode("Usúario $nome_usuario_logado alterou dados do subgrupo de código $id_subgrupo");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }
    echo json_encode($retornar);
}

//trazer informaçãoes
if (isset($_GET['editar_subgrupo_estoque']) == true) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $id_subgrupo = $_GET['id_subgrupo'];
    $select = "SELECT * from tb_subgrupo_estoque where cl_id = $id_subgrupo";
    $consultar_grupo = mysqli_query($conecta, $select);
    $linha  = mysqli_fetch_assoc($consultar_grupo);
    $descricao_b = utf8_encode($linha['cl_descricao']);
    $grupo_pai_b = ($linha['cl_grupo_id']);
    $und_b = ($linha['cl_und_id']);
    $cfop_interno_b = ($linha['cl_cfop_interno']);
    $cfop_externo_b = ($linha['cl_cfop_externo']);
    $estoque_inicial_b = ($linha['cl_estoque_inicial']);
    $estoque_minimo_b = ($linha['cl_estoque_minimo']);
    $estoque_maximo_b = ($linha['cl_estoque_maximo']);
    $estoque_local_b = utf8_encode($linha['cl_local']);
    $img_subgrupo_estoque_b = ($linha['cl_img_subgrupo_estoque']);
    $delivery = ($linha['cl_delivery']);
    $ncm = ($linha['cl_ncm']);
    $cst_icms = ($linha['cl_cst_icms']);
    $cst_pis_s = ($linha['cl_cst_pis_s']);
    $cst_pis_e = ($linha['cl_cst_pis_e']);
    $cst_cofins_s = ($linha['cl_cst_cofins_s']);
    $cst_cofins_e = ($linha['cl_cst_cofins_e']);
}

//remover formulario
if (isset($_POST['remover_subgrupo_estoque'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();

    $nome_usuario_logado = $_POST["nome_usuario_logado"];
    // $id_usuario_logado = $_POST["id_usuario_logado"];
    // $perfil_usuario_logado = $_POST['perfil_usuario_logado'];

    $id_subgrupo = $_POST["id_subgrupo"];

    if (verificar_dados_existentes($conecta, "tb_produtos", "cl_grupo_id", $id_subgrupo) > 0) { // verificar se o fabricante está vinculado com algum produto cadastrado no sistema
        $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel remover esse subgrupo, 
        pois esse subgrupo está vinculado a um ou mais produtos no sistema.");
    } else {

        $subgrupo_estoque = consulta_tabela($conecta, "tb_subgrupo_estoque", "cl_id", $id_subgrupo, "cl_descricao"); //consultar a descricao do subgrupo

        $update = "DELETE FROM tb_subgrupo_estoque WHERE cl_id = $id_subgrupo";
        $operacao_delete = mysqli_query($conecta, $update);
        if ($operacao_delete) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Subgrupo removido com sucesso");
            //registrar no log
            $mensagem =  (utf8_decode("Usúario") . " $nome_usuario_logado removeu o subgrupo  $subgrupo_estoque");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }

    echo json_encode($retornar);
}




$sistema_delivery =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "42"); //Verificar se o sistema é para delivery

//consultar grupo estoque
$select = "SELECT * from tb_grupo_estoque";
$consultar_grupo_estoque = mysqli_query($conecta, $select);

//consultar cfop
$select = "SELECT * from tb_cfop";
$consultar_cfop_interno = mysqli_query($conecta, $select);

//consultar cfop
$select = "SELECT * from tb_cfop";
$consultar_cfop_externo = mysqli_query($conecta, $select);

//consultar unidade medida
$select = "SELECT * from tb_unidade_medida";
$consultar_und_medida = mysqli_query($conecta, $select);
