<?php
//consulta para o filtro
include "../../../conexao/conexao.php";
$select = "SELECT * from tb_users";
$consultar_usuarios = mysqli_query($conecta, $select);
if(!$consultar_usuarios){
die("Falha no banco de dados"); // colocar o svg do erro
}