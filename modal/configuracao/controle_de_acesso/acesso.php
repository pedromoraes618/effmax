<?php
//consultar usuario

$select = "SELECT * from tb_users where cl_tipo = 'usuario' and cl_ativo = 1";
$consultar_usuarios = mysqli_query($conecta, $select);
if(!$consultar_usuarios){
die("Falha no banco de dados"); // colocar o svg do erro
}

//funcao para veificar se usuario tem o acesso
function consultar_ativo_acesso_usuario($usuario_id,$id_subcategoria_b){
    include "../../../conexao/conexao.php";
    $select = "SELECT * from tb_acessos where cl_usuario_id = '$usuario_id' and cl_subcategoria = '$id_subcategoria_b' and cl_acesso_ativo = '1'";
    $consulta_acessos_atuais_usuario = mysqli_query($conecta,$select);
    $cont = mysqli_num_rows($consulta_acessos_atuais_usuario);
    return $cont;
}



