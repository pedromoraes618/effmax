<?php
 $select = "SELECT * from tb_categorias order by cl_ordem";
 $consultar_categoria = mysqli_query($conecta,$select);
 $consultar_categoria_mobile = mysqli_query($conecta,$select);

//pegar o id
if ($_SESSION["user_session_portal"]) {
    $user = $_SESSION["user_session_portal"];
    $consulta  = "SELECT * FROM tb_users WHERE cl_id = $user";
    $consulta_users = mysqli_query($conecta, $consulta);
    $row = mysqli_fetch_assoc($consulta_users);
    $usuario = $row['cl_usuario'];
    $tipo = $row['cl_tipo'];
    $id_user = $row['cl_id'];
    $img = $row['cl_img'];
}

//funcao para verificar se a subcategoria está liberado via acesso
function consultar_acesso_subcategoria($cl_id_user,$id_subcategoria){
include 'conexao/conexao.php';
$select = "SELECT tbsubc.cl_id, tbsubc.cl_subcategoria from tb_subcategorias as tbsubc inner join tb_acessos as acessos on 
acessos.cl_subcategoria = tbsubc.cl_id where acessos.cl_usuario_id = '$cl_id_user' and acessos.cl_subcategoria = '$id_subcategoria' and acessos.cl_acesso_ativo = '1'";
$consultar_subcategoria = mysqli_query($conecta,$select);
$quant = mysqli_num_rows($consultar_subcategoria);
return $quant;
}
//funcao para verificar se a subcategoria está liberado via acesso
function consultar_acesso_categoria($cl_id_user,$id_categoria){
    include 'conexao/conexao.php';
    $select = "SELECT tbsubc.cl_id, tbsubc.cl_subcategoria from tb_subcategorias as tbsubc inner join tb_acessos as acessos on 
    acessos.cl_subcategoria = tbsubc.cl_id where acessos.cl_usuario_id = '$cl_id_user' and tbsubc.cl_categoria  = '$id_categoria' and acessos.cl_acesso_ativo = '1'";
    $consultar_categoria = mysqli_query($conecta,$select);
    $quant = mysqli_num_rows($consultar_categoria);
    return $quant;
}
    

//passar o diretorio da subcategoria via url
function consultar_subcategoria($id_subctg){
include 'conexao/conexao.php';
$select = "SELECT * FROM tb_subcategorias where cl_id = $id_subctg";
$consultar_subcategoria = mysqli_query($conecta,$select);
$row = mysqli_fetch_assoc($consultar_subcategoria);
$diretorio = $row['cl_diretorio'];
return $diretorio;
 }

//passar o diretorio da subcategoria via url
function consultar_diretorio_bd($id_subctg){
    include 'conexao/conexao.php';
    $select = "SELECT * FROM tb_subcategorias where cl_id = $id_subctg";
    $consultar_subcategoria = mysqli_query($conecta,$select);
    $row = mysqli_fetch_assoc($consultar_subcategoria);
    $diretorio_bd = $row['cl_diretorio_bd'];
    return $diretorio_bd;
}
    