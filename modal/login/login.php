<?php
include "../../conexao/conexao.php";
include "../../funcao/funcao.php";
session_start();
$usuario =  $_POST["usuario"];
$senha =  $_POST["senha"];
$chave_aleatoria =  $_POST["chave_aleatorio"];

if ($usuario != "" and $senha != "") { //verificar se os campos estão preenchidos
    $consulta  = "SELECT * FROM tb_users WHERE cl_usuario = '$usuario' and cl_ativo = '1'";
    $consulta_login = mysqli_query($conecta, $consulta);
    if ($consulta_login) {
        $user_acess = mysqli_fetch_assoc($consulta_login);
        if (empty($user_acess)) {
            echo "Login sem Sucesso";

            //registrar no log
            $mensagem =  utf8_decode("Tentativa de acesso do Usuário $usuario, sem sucesso ");
            registrar_log($conecta, $usuario, $data, $mensagem);
        } else {
            $senha_bd = $user_acess['cl_senha'];
            $senha_bd = base64_decode($senha_bd);
            $restricao_horario = $user_acess['cl_restricao_horario'];

            if ($restricao_horario == 1 and !verifica_data_funcionamento($conecta, $data, $hora_atual)) {
                echo "Usuário não autorizado a utilizar o sistema neste horário";
                //registrar no log    
                $mensagem =  utf8_decode("Usuário $usuario tentou acessar o sistema fora do horário permitido");
                registrar_log($conecta, $usuario, $data, $mensagem);
            } else {
                if ($senha == $senha_bd) {
                    $_SESSION["user_session_portal"] = $user_acess["cl_id"];
                    $id_user = $_SESSION["user_session_portal"];
                    //adicionar chave aleatoria para o usuario
                    $update = "UPDATE tb_users set cl_chave_aleatoria = '$chave_aleatoria' where cl_id = $id_user";
                    $operacao_update_chave_aleatorio = mysqli_query($conecta, $update);

                    echo "ok";

                    //registrar no log    
                    $mensagem =  utf8_decode("Usuário $usuario acessou ao sistema");
                    registrar_log($conecta, $usuario, $data, $mensagem);
                } else {
                    echo "Senha Incorreta";
                    //registrar no log
                    $mensagem =  utf8_decode("Tentativa de acesso do Usuário $usuario, sem sucesso ");
                    registrar_log($conecta, $usuario, $data, $mensagem);
                }
            }
        }
    } else {
        die("Falha na consulta ao banco de dados");
    }
} else {
    echo "Informe a sua senha e seu usuario";
}
