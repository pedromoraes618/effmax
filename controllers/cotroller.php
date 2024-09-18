<?php
if(isset($_GET['resetar_password'])){
    include "view/resetar_senha/resetar_password.php";
}elseif(isset($_GET['menu'])){
    include("conexao/conexao.php");
    include "view/menu/menu.php";
}elseif(isset($_GET['logout'])){ // se for definido logout 
    include 'parameters/parameters.php';//setcookie("login", time() - 9600);
    include 'logout.php';
}else{ // se não for encontrado o diretorio correto ou não existir, voltar para a tela incial
    include "view/menu/menu.php";
}
?>