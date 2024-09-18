<?php
include "../conexao/conexao.php";
include "../funcao/funcao.php";
if(isset($_POST['post_resetar_senha'])){
    $retorno = array();
    $usuario =  $_POST["usuario"];
    $senha =  $_POST["senha"];
    $nova_senha =  $_POST["nova_senha"];
    $confirmar_Senha =  $_POST["confirmar_senha"];
 
    if($usuario != "" and $senha !="" and $nova_senha != "" and $confirmar_Senha !=""){//verificar se todos os campos estão preenchidos
        //verificar se o usuario existe 
        $select = "SELECT * FROM tb_users where cl_usuario = '$usuario'";
        $consultar_usuario = mysqli_query($conecta,$select);
        $cont = mysqli_num_rows($consultar_usuario);
        $row = mysqli_fetch_assoc($consultar_usuario);
        $senha_bd = $row['cl_senha'];
  
        if($cont > 0){ //usuario existir verificar se a senha está correta
            $senha_bd = base64_decode($senha_bd);   
            $retorno["mensagem"] = $senha_bd;
            if($senha_bd == $senha){//verificar se a senha do usuario está correta
                if($nova_senha ==  $confirmar_Senha){//verificar se o campo nova senha e confirmar senha é igual
                    $nova_senha = base64_encode($nova_senha);
                  
                    $update = "UPDATE tb_users set cl_senha = '{$nova_senha}' where cl_usuario = '$usuario'";
                    $operacao_update_senha = mysqli_query($conecta, $update);
                    if($operacao_update_senha){
                        $retorno["sucesso"] = true;
                        $mensagem =  utf8_decode("Tentativa de resetar a senha do usúario $usuario, Com sucesso!! ");
                        registrar_log($conecta,$usuario,$data,$mensagem);
                    }else{
                        $retorno["sucesso"] = false;
                        $mensagem =  utf8_decode("Tentativa de resetar a senha do usúario $usuario, Erro no banco de dados, contatar suporte!! ");
                        registrar_log($conecta,$usuario,$data,$mensagem);
                    }   
                    
                }else{
                    $retorno["mensagem"] = "A confirmação de senha não esá igual a sua nova senha, favor verifique";
                    $mensagem =  utf8_decode("Tentativa de resetar a senha do usúario $usuario, sem sucesso! ");
                    registrar_log($conecta,$usuario,$data,$mensagem);
                }
            }else{
                $retorno["mensagem"] = "Senha atual incorreta, Favor verifique";
                $mensagem =  utf8_decode("Tentativa de resetar a senha do usúario $usuario, sem sucesso! ");
                registrar_log($conecta,$usuario,$data,$mensagem);
            }
        }else{
            $retorno["mensagem"] = "Usuário inexistente";
            $mensagem =  utf8_decode("Tentativa de resetar a senha do usúario $usuario, sem sucesso! ");
            registrar_log($conecta,$usuario,$data,$mensagem);

        }


    }else{
        $retorno["mensagem"] = "Campo vazio, favor verifique";
    }
    echo json_encode($retorno);
  
   
}