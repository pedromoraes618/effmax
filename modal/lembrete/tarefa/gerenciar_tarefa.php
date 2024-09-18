<?php
//consultar informações para tabela
if (isset($_GET['consultar_tarefa'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $consulta = $_GET['consultar_tarefa'];
    $user_logado = $_GET['user_logado'];
    if ($consulta == "inicial") {
        $select = "SELECT trf.cl_id, userord.cl_usuario as usuarioordem, trf.cl_data_lancamento,trf.cl_descricao,trf.cl_comentario,user.cl_usuario 
        as usuario_func,trf.cl_prioridade,trf.cl_data_limite,strf.cl_descricao as status from tb_tarefas as trf inner join tb_users as user on user.cl_id = trf.cl_usuario_func inner join tb_users as userord on userord.cl_id = trf.cl_usuario
        inner join tb_status_tarefas as strf on strf.cl_id = trf.cl_status where  userord.cl_usuario  = '$user_logado' order by trf.cl_data_lancamento desc,trf.cl_status";
        $consultar_tarefas = mysqli_query($conecta, $select);
        if (!$consultar_tarefas) {
            die("Falha no banco de dados"); // colocar o svg do erro
        }
    } else {
        $pesquisa = utf8_decode($_GET['conteudo_pesquisa']);
        $status = ($_GET['conteudo_status']);
        $data_inicial = $_GET['data_inicial'];
        $data_final = $_GET['data_final'];


        // if(datecheck($data_inicial) && datecheck($data_final)){
        //     //formatar data para o banco
        //     if($data_inicial !=""){
        //     $div1 = explode("/",$_GET['data_inicial']);
        //     $data_inicial = $div1[2]."-".$div1[1]."-".$div1[0];  
        //     }
        //     if($data_final !=""){
        //         $div2 = explode("/",$_GET['data_final']);
        //         $data_final = $div2[2]."-".$div2[1]."-".$div2[0];  
        //     }


        $select = "SELECT trf.cl_id,userord.cl_usuario as usuarioordem, trf.cl_data_lancamento,trf.cl_descricao,trf.cl_comentario,user.cl_usuario 
    as usuario_func,trf.cl_prioridade,trf.cl_data_limite,strf.cl_descricao as status from tb_tarefas as trf inner join tb_users as user on user.cl_id = trf.cl_usuario_func inner join tb_users as userord on userord.cl_id = trf.cl_usuario 
    inner join tb_status_tarefas as strf on strf.cl_id = trf.cl_status where userord.cl_usuario  = '$user_logado' and trf.cl_descricao like '%{$pesquisa}%' and trf.cl_data_lancamento between '$data_inicial' and '$data_final' ";
        if ($status != "0") {
            $select .= " and trf.cl_status = '$status'";
        }
        $select .= "order by trf.cl_data_lancamento desc ,trf.cl_status";
        $consultar_tarefas = mysqli_query($conecta, $select);
        if (!$consultar_tarefas) {
            die("Falha no banco de dados"); // colocar o svg do erro
        }
    }
}


//cadastrar formulario
if (isset($_POST['formulario_cadastrar_tarefa'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $nome_usuario_logado = $_POST["nome_usuario_logado"];
    $id_usuario_logado = $_POST["id_usuario_logado"];
    $perfil_usuario_logado = $_POST['perfil_usuario_logado'];

    $descricao = utf8_decode($_POST["descricao"]);
    $data_limite = $_POST["data_limite"];
    $status = $_POST["status"];
    $usuario = $_POST["usuario"];
    $comentario = utf8_decode($_POST["comentario"]);


    if ($descricao == "") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("descricão");
    } elseif ($status == "0") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("status");
    }elseif ($usuario == "0") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("usúario");
    } else {

        if (isset($_POST['prioridade'])) {
            $prioridade = '1';
        } else {
            $prioridade = "0";
        }

        // if ($data_limite != "") {
        //     $div1 = explode("/", $_POST['data_limite']);
        //     $data_limite = $div1[2] . "-" . $div1[1] . "-" . $div1[0];
        // }




        $inset = "INSERT INTO tb_tarefas (cl_data_lancamento,cl_descricao,cl_data_limite,cl_status,cl_usuario,cl_comentario,cl_prioridade,cl_usuario_func)
         VALUES ('$data_lancamento','$descricao','$data_limite','$status','$id_usuario_logado','$comentario','$prioridade','$usuario')";
        $operacao_inserir = mysqli_query($conecta, $inset);
        if ($operacao_inserir) {
            $retornar["sucesso"] = true;
            //registrar no log
            $mensagem =  (utf8_decode("Usuário") . " $nome_usuario_logado adicionou o lembrete $descricao ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }

    echo json_encode($retornar);
}

// //Editar formulario
if (isset($_POST['formulario_editar_tarefa'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();

    $nome_usuario_logado = $_POST["nome_usuario_logado"];
    $id_usuario_logado = $_POST["id_usuario_logado"];
    $perfil_usuario_logado = $_POST['perfil_usuario_logado'];

    $id_tarefa = $_POST["id_tarefa"];
    $descricao = utf8_decode($_POST["descricao"]);
    $data_limite = $_POST["data_limite"];
    $status = $_POST["status"];
    $usuario = $_POST["usuario"];
    $comentario = utf8_decode($_POST["comentario"]);


    if ($descricao == "") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("descricão");
    } elseif ($status == "0") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("status");
    } elseif ($usuario == "0") {
        $retornar["mensagem"] = mensagem_alerta_cadastro("usúario");
    } else {

        if (isset($_POST['prioridade'])) {
            $prioridade = '1';
        } else {
            $prioridade = "0";
        }

        // if ($data_limite != "") {
        //     $div1 = explode("/", $_POST['data_limite']);
        //     $data_limite = $div1[2] . "-" . $div1[1] . "-" . $div1[0];
        // }


        $update = "UPDATE tb_tarefas set cl_descricao = '$descricao', cl_data_limite = '$data_limite', cl_status = '$status',cl_usuario='$id_usuario_logado',cl_comentario='$comentario',cl_prioridade = '$prioridade',cl_usuario_func='$usuario' where cl_id = $id_tarefa";
        $operacao_update = mysqli_query($conecta, $update);
        if ($operacao_update) {
            $retornar["sucesso"] = true;
            //registrar no log
            $mensagem =  (utf8_decode("Usuário") . " $nome_usuario_logado alterou tarefa de codigo $id_tarefa");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }
    echo json_encode($retornar);
}


//remover formulario

if (isset($_POST['remover_tarefa'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();

    $nome_usuario_logado = $_POST["nome_usuario_logado"];
    // $id_usuario_logado = $_POST["id_usuario_logado"];
    // $perfil_usuario_logado = $_POST['perfil_usuario_logado'];

    $id_tarefa = $_POST["id_tarefa"];
    $update = "DELETE FROM tb_tarefas WHERE cl_id = $id_tarefa";
    $operacao_delete = mysqli_query($conecta, $update);
    if ($operacao_delete) {
        $retornar["sucesso"] = true;
        //registrar no log
        $mensagem =  (utf8_decode("Usuário") . " $nome_usuario_logado removeu tarefa de codigo $id_tarefa");
        registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
    }

    echo json_encode($retornar);
}

//trazer informaçãoes
if (isset($_GET['editar_tarefa']) == true) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $id_tarefa = $_GET['id_tarefa'];
    $select = "SELECT * from tb_tarefas where cl_id = $id_tarefa";
    $consultar_tarefas = mysqli_query($conecta, $select);
    $linha  = mysqli_fetch_assoc($consultar_tarefas);
    $descricao_b = utf8_encode($linha['cl_descricao']);
    $data_limite_b = ($linha['cl_data_limite']);
    $status_b = ($linha['cl_status']);
    $usuario_ordem_b = $linha['cl_usuario'];
    $comentario_b = utf8_encode($linha['cl_comentario']);
    $comentario_func_b = utf8_encode($linha['cl_comentario_func']);
    $prioridade_b = ($linha['cl_prioridade']);
    $usuario_func_b = ($linha['cl_usuario_func']);
}
//consultar para filtro
$select = "SELECT * from tb_status_tarefas";
$consultar_status_tarefas = mysqli_query($conecta, $select);
if (!$consultar_status_tarefas) {
    die("Falha no banco de dados"); // colocar o svg do erro
}

$select = "SELECT * from tb_users";
$consultar_usuarios = mysqli_query($conecta, $select);
if (!$consultar_usuarios) {
    die("Falha no banco de dados"); // colocar o svg do erro
}
