<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
//remover acesso
if(isset($_POST['removeracessos']) and (isset($_POST['idsubcategoria'])) and (isset($_POST['user_id'])) ){
    $id_subcategoria = $_POST['idsubcategoria'];
    $usuario_id = $_POST['user_id'];
    $usuario_logado = $_POST['user_logado'];
    $retornar = array();    
    
    $select = "SELECT * from tb_acessos where cl_usuario_id = '$usuario_id' and cl_subcategoria = '$id_subcategoria'";
    $consulta_acesso_usuario = mysqli_query($conecta,$select);
    $cont = mysqli_num_rows($consulta_acesso_usuario);
    if($cont > 0){//verificar se o acesso existe para o ususario // se sim desabilitar o acesso ao usuario
        $update = "UPDATE tb_acessos set cl_acesso_ativo = '0' where cl_usuario_id = '$usuario_id' and cl_subcategoria = '$id_subcategoria'";
        $update_alterar_inativo = mysqli_query($conecta,$update);
        if($update_alterar_inativo){
            $retornar["sucesso"] = true;
            $mensagem =  utf8_decode("Usúario $usuario_logado removeu o acesso a subcategoria ".consultar_subcategoria_acesso($conecta,$id_subcategoria)." ao usúario ".consultar_usuario_acesso($conecta,$usuario_id).", Com sucesso! ");
            registrar_log($conecta,$usuario_logado,$data,$mensagem);
        }else{
            $retornar["sucesso"] = false;
        }
    }
    echo json_encode($retornar);
    }
        
//adicionar acesso
if(isset($_POST['addicionaracesso']) and (isset($_POST['idsubcategoria'])) and (isset($_POST['user_id'])) ){
    $id_subcategoria = $_POST['idsubcategoria'];
    $usuario_id = $_POST['user_id'];
    $usuario_logado = $_POST['user_logado'];
    $retornar = array();    
    
    $select = "SELECT * from tb_acessos where cl_usuario_id = '$usuario_id' and cl_subcategoria = '$id_subcategoria'";
    $consulta_acesso_usuario = mysqli_query($conecta,$select);


    $cont = mysqli_num_rows($consulta_acesso_usuario);
    if($cont > 0){//se o acesso existir e estiver desabilitado fazer um update para ativar o acesso para o usuario
        $update = "UPDATE tb_acessos set cl_acesso_ativo = '1' where cl_usuario_id = '$usuario_id' and cl_subcategoria = '$id_subcategoria'";
        $update_alterar_ativo = mysqli_query($conecta,$update);
        if($update_alterar_ativo){
            $retornar["sucesso"] = true;
            $mensagem =  utf8_decode("Usúario $usuario_logado adicionou acesso a subcategoria ".consultar_subcategoria_acesso($conecta,$id_subcategoria)." ao usuário ".consultar_usuario_acesso($conecta,$usuario_id).", Com sucesso! ");
            registrar_log($conecta,$usuario_logado,$data,$mensagem);
        }

    }else{//se o acesso não exister, fazer um insert do acesso para o usuario
        $insert = "INSERT INTO tb_acessos (cl_usuario_id,cl_subcategoria,cl_acesso_ativo) values ('$usuario_id','$id_subcategoria','1')";
        $insert_acesso_usuario = mysqli_query($conecta,$insert);
        if($insert_acesso_usuario){
            $retornar["sucesso"] = true;
            $mensagem =  utf8_decode("Usúario $usuario_logado adicionou acesso a subcategoria ".consultar_subcategoria_acesso($conecta,$id_subcategoria)." ao usuário ".consultar_usuario_acesso($conecta,$usuario_id).", Com sucesso! ");
            registrar_log($conecta,$usuario_logado,$data,$mensagem);
        }

    }
    echo json_encode($retornar);
    
}