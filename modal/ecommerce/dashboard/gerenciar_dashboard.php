<?php
if (isset($_GET['consultar_dashboard'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";

    $usuario_id = verifica_sessao_usuario(); //pegar o id do usuazrio na sessao
    $periodo_dashboard = consulta_tabela($conecta, 'tb_users', 'cl_id', $usuario_id, 'cl_periodo_dashboard');
}

if (isset($_GET['consultar_container_dashboard'])) { //containers
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $container = $_GET['consultar_container_dashboard'];
    $periodo = isset($_GET['periodo']) ? $_GET['periodo'] : '';
    if ($periodo == "DIA") {
        $data_inicial = ($data_lancamento . ' 01:01:01');
        $data_final = ($data_lancamento . ' 23:59:59');
    } elseif ($periodo == "MES") {
        $data_inicial = ($data_inicial_mes_bd . ' 01:01:01');
        $data_final = ($data_final_mes_bd . ' 23:59:59');
    } elseif ($periodo == "ANO") {
        $data_inicial = ($data_inicial_ano_bd . ' 01:01:01');
        $data_final = ($data_final_ano_bd . ' 23:59:59');
    }

    if ($container == "resumo_pedidos_1") {
        $query = "
        SELECT 
            SUM(cl_valor_liquido) AS totalpedido,
            SUM(CASE WHEN cl_status_compra = 'CONCLUIDO' AND cl_status_pagamento = 'approved' THEN 1 ELSE 0 END) AS totalconcluido,
            SUM(CASE WHEN cl_status_compra = 'ANDAMENTO' THEN 1 ELSE 0 END) AS totalandamento,
            SUM(CASE WHEN cl_status_compra = 'CANCELADO' THEN 1 ELSE 0 END) AS totalcancelado
        FROM tb_pedido_loja
        WHERE cl_data BETWEEN '$data_inicial' AND '$data_final'
    
    ";
        $consulta_resumo_pedidos_1 = mysqli_query($conecta, $query);
        $linha = mysqli_fetch_assoc($consulta_resumo_pedidos_1);
        $total_pedidos_aprovados = $linha['totalconcluido'];
        $total_pedidos_andamento = $linha['totalandamento'];
        $total_pedidos_cancelados = $linha['totalcancelado'];
        $total_pedidos = $linha['totalpedido'];


        function consultar_pd_pagamento($dados)
        { //função para consultar por quantidade o total de pedido agrupado ou não por status pagamento e filtrado poelo ano e mes

            global $conecta;
            global $data_inicial_ano_bd;
            global $data_final_ano_bd;

            $data_inicial = ($data_inicial_ano_bd . ' 01:01:01');
            $data_final = ($data_final_ano_bd . ' 23:59:59');

            $mes = $dados['mes'];
            $ano = $dados['ano'];
            $status_pagamento = isset($dados['cl_status_pagamento']) ? $dados['cl_status_pagamento'] : '';
            $ulitmo_dia = date('t');

            $query = "SELECT count(*) as totalpedido, cl_status_pagamento FROM `tb_pedido_loja` ";
            if (!empty($mes)) {
                $query .= " WHERE cl_data BETWEEN  '$ano-$mes-01' and '$ano-$mes-$ulitmo_dia'";
            } elseif (!empty($ano)) {
                $query .= " WHERE cl_data BETWEEN  '$data_inicial' and '$data_final' ";
            }
            if (empty($status_pagamento)) {
                $query .= " group by cl_status_pagamento";
            } else {
                $query .= " and cl_status_pagamento = '$status_pagamento'  ";
            }

            $consulta_pedido_status_pgt = mysqli_query($conecta, $query);

            $result = [];
            while ($row = mysqli_fetch_assoc($consulta_pedido_status_pgt)) {
                $result[] = $row;
            }
            return $result;
        }

        $dados_status_pagamento_grupo_pagamento = array('mes' => '', 'ano' => date('Y'));
        $row_grupo_pd_pagamento = (consultar_pd_pagamento($dados_status_pagamento_grupo_pagamento));


        function consultar_resumo_vlr_pagamento($dados)
        { //função para consultar o valor total agurado por forma de pagamento de pedidos aprovados

            global $conecta;
            global $data_inicial_ano_bd;
            global $data_final_ano_bd;

            $data_inicial = ($data_inicial_ano_bd . ' 01:01:01');
            $data_final = ($data_final_ano_bd . ' 23:59:59');

            $mes = $dados['mes'];
            $ano = $dados['ano'];
            $ulitmo_dia = date('t');
            $forma_pagamento_id = isset($dados['cl_pagamento_id_interno']) ? $dados['cl_pagamento_id_interno'] : '';


            $query = "SELECT fpg.cl_id as pagamentoid, fpg.cl_descricao as formapagamento, 
            sum(cl_valor_liquido) as total from tb_pedido_loja as pdl inner join
             tb_forma_pagamento as fpg on fpg.cl_id = pdl.`cl_pagamento_id_interno`  ";
            if (!empty($mes)) {
                $query .= " WHERE pdl.cl_data BETWEEN  '$ano-$mes-01' and '$ano-$mes-$ulitmo_dia'";
            } elseif (!empty($ano)) {
                $query .= " WHERE pdl.cl_data BETWEEN  '$data_inicial' and '$data_final' ";
            }
            if (!empty($forma_pagamento_id)) {
                $query .= " and pdl.cl_pagamento_id_interno = '$forma_pagamento_id' ";
            } else {
                $query .= " group by pdl.cl_pagamento_id_interno";
            }
            $consulta = mysqli_query($conecta, $query);
            $result = [];
            while ($row = mysqli_fetch_assoc($consulta)) {
                $result[] = $row;
            }
            return $result;
        }
        $dados_resumo_vlr_pagamento = array('mes' => '', 'ano' => date('Y'));
        $row_dados_resumo_vlr_pagamento = (consultar_resumo_vlr_pagamento($dados_resumo_vlr_pagamento));



        /*query para buscar o total de pedidos concluidos por estado */
        $query = "SELECT count(*) as total, cl_estado FROM tb_pedido_loja 
        WHERE cl_data BETWEEN '$data_inicial' AND '$data_final' and cl_status_compra = 'CONCLUIDO' AND cl_status_pagamento = 'approved'   group by cl_estado";
        $consulta = mysqli_query($conecta, $query);
        $pedidos_uf_array = [];
        while ($row = mysqli_fetch_assoc($consulta)) {
            $pedidos_uf_array[] = $row;
        }

        /*query para totla de cupons utilziados em pedidos concluidos */
        $query = "SELECT count(*) as total, cl_cupom FROM tb_pedido_loja 
             WHERE cl_data BETWEEN '$data_inicial' AND'$data_final'
              and cl_status_compra = 'CONCLUIDO' AND cl_status_pagamento = 'approved'  AND cl_cupom IS NOT NULL  AND cl_cupom != '' GROUP BY cl_cupom";
        $consulta = mysqli_query($conecta, $query);
        $pedidos_cupom_array = [];
        while ($row = mysqli_fetch_assoc($consulta)) {
            $pedidos_cupom_array[] = $row;
        }



        /*query para totla de cupons utilziados em pedidos concluidos */
        $query = "SELECT count(*) as total FROM tb_user_loja 
             WHERE cl_data BETWEEN '$data_inicial' AND'$data_final' ";
        $consulta = mysqli_query($conecta, $query);
        $cliente_array = [];
        while ($row = mysqli_fetch_assoc($consulta)) {
            $cliente_array[] = $row;
        }



        // $linha = mysqli_fetch_assoc($consulta);
        // $total_pedidos_aprovados = $linha['totalconcluido'];
        // $total_pedidos_andamento = $linha['totalandamento'];
        // $total_pedidos_cancelados = $linha['totalcancelado'];
        // $total_pedidos = $linha['totalpedido'];
    }
}
