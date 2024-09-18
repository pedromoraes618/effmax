<?php
//pegar o id
if ($_SESSION["user_session_portal"]) {
    $user = $_SESSION["user_session_portal"];
    $consulta  = "SELECT * FROM tb_users WHERE cl_id = $user";
    $consulta_users = mysqli_query($conecta, $consulta);
    $row = mysqli_fetch_assoc($consulta_users);
    $usuario = $row['cl_usuario'];
    $area_usuario = $row['cl_usuario_area'];
    $tipo = $row['cl_tipo'];


    //verficar a quantidade de lembretes com o staus a fazer ou inciado para o usuario
    $consulta  = "SELECT count(*) as qtd FROM tb_tarefas where cl_usuario_func = '$user' and cl_status !='3'";
    $consulta_qtd_lembrete = mysqli_query($conecta, $consulta);
    $row = mysqli_fetch_assoc($consulta_qtd_lembrete);
    $qtd_lembrete = $row['qtd'];


    //verficar a quantidade de lembretes com o staus a fazer ou inciado para o usuario
    $select = "SELECT count(*) qtd FROM tb_atendimento where ";
    if ($area_usuario != "GERENTE" or $tipo == "usuario") {
        $select .= "  (cl_visualizar = 'T' or cl_usuario_id = '$user') and ";
    }
    $select .= "  ( cl_status_id !='3' )  ";

    $consulta_qtd_atendimento = mysqli_query($conecta, $select);
    $row = mysqli_fetch_assoc($consulta_qtd_atendimento);
    $qtd_atendimento = $row['qtd'];

    if (isset($_GET['ctg'])) {
        $categoria_top = $_GET['ctg'];
    } else {
        $categoria_top = "Dashboard";
    }

    //pegar qual a subcategoria o usuario está
    if (isset($_GET['ctg']) and isset($_GET['id'])) {
        $subcategoria_id = $_GET['id'];
        $consulta  = "SELECT * FROM tb_subcategorias WHERE cl_id = $subcategoria_id";
        $consulta_subcategoria = mysqli_query($conecta, $consulta);
        $row = mysqli_fetch_assoc($consulta_subcategoria);
        $subcategoria = utf8_encode($row['cl_subcategoria']);
        $sub_top = $categoria_top . " > " . $subcategoria;
    } else {

        $sub_top = "";
    }


    /*Pedidos que estão aguardando confirmação */
    $select = "SELECT * FROM tb_nf_saida  where cl_status_venda ='2' and cl_status_pedido_delivery = '1'  and (cl_solicitar_cancelamento_delivery ='NAO' OR cl_solicitar_cancelamento_delivery ='') order by cl_data_pedido_delivery asc ";
    $consulta_pd_aguardando_confirmacao_topo = mysqli_query($conecta, $select);
    $qtd_consulta_pedido_confirmacao_topo = mysqli_num_rows($consulta_pd_aguardando_confirmacao_topo);
}
