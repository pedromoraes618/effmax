<?php
//consultar informações para tabela
if (isset($_GET['pagamentos_recebimentos'])) {

    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $acao = $_GET['acao'];
    // $consulta = $_GET['consultar_venda'];
    $data_inicial = $_GET['data_inicial'];
    $data_final = $_GET['data_final'];
    $conta_financeira = $_GET['conta_financeira'];
    $forma_pagamento = $_GET['forma_pagamento'];
    $rz_paceiro = utf8_decode($_GET['rz_paceiro']);
    $ordem = ($_GET['ordem']);

    $previsao_juros = false;
    $qtd_dia_juros = verficar_paramentro($conecta, 'tb_parametros', 'cl_id', '50');
    $taxa_juros = verficar_paramentro($conecta, 'tb_parametros', 'cl_id', '49');


    if ($acao == "consultar_a_receber") { //puxar as receitas e despesas da conta financeira selecionada
        $titulo = 'Contas a Receber';
        $th_1 = "Data Vencimento";


        $select = "SELECT lcf.cl_classificacao_id ,  DATEDIFF(CURRENT_DATE(),lcf.cl_data_vencimento) as atraso,lcf.cl_status_id, lcf.cl_parceiro_id, lcf.cl_valor_bruto,lcf.cl_juros, lcf.cl_data_vencimento, clf.cl_descricao as classificacaofin,lcf.cl_tipo_lancamento,
         lcf.cl_valor_liquido,
         fpg.cl_descricao as formapg, lcf.cl_data_pagamento,lcf.cl_documento,prc.cl_razao_social
        from tb_lancamento_financeiro as lcf inner join tb_parceiros as prc on prc.cl_id
         = lcf.cl_parceiro_id inner join tb_forma_pagamento as fpg on fpg.cl_id = lcf.cl_forma_pagamento_id inner join 
         tb_classificacao_financeiro as clf on clf.cl_id = lcf.cl_classificacao_id where lcf.cl_data_vencimento between '$data_inicial' and '$data_final' 
        and  lcf.cl_status_id = '1' and (prc.cl_razao_social  like '%$rz_paceiro%' or prc.cl_nome_fantasia  
        like '%$rz_paceiro%') ";
        if ($conta_financeira != 0) {
            $select .= " and lcf.cl_conta_financeira = '$conta_financeira' ";
        }
        if ($forma_pagamento != 0) {
            $select .= " and lcf.cl_forma_pagamento_id = '$forma_pagamento' ";
        }
        if ($ordem == 1) {
            $select .= "  order by lcf.cl_parceiro_id asc ";
        } elseif ($ordem == 2) {
            $select .= "  order by lcf.cl_classificacao_id asc ";
        } elseif ($ordem == 3) {
            $select .= "  order by lcf.cl_data_vencimento asc ";
        } else {
            $select .= " order by lcf.cl_parceiro_id asc ";
        }
        $consulta = mysqli_query($conecta, $select);
        if (!$consulta) {
            die("Erro na consulta: " . mysqli_error($conecta));
        }

        $qtd_consulta = mysqli_num_rows($consulta);
    } elseif ($acao == "consultar_a_pagar") {
        $titulo = 'Contas a Pagar';
        $th_1 = "Data Vencimento";
        $select = "SELECT lcf.cl_classificacao_id , DATEDIFF(CURRENT_DATE(),lcf.cl_data_vencimento) as atraso,lcf.cl_status_id, lcf.cl_parceiro_id, lcf.cl_valor_bruto,lcf.cl_juros, lcf.cl_data_vencimento, clf.cl_descricao as classificacaofin,lcf.cl_tipo_lancamento,
         lcf.cl_valor_liquido,
         fpg.cl_descricao as formapg, lcf.cl_data_pagamento,lcf.cl_documento,prc.cl_razao_social
        from tb_lancamento_financeiro as lcf inner join tb_parceiros as prc on prc.cl_id
         = lcf.cl_parceiro_id inner join tb_forma_pagamento as fpg on fpg.cl_id = lcf.cl_forma_pagamento_id inner join 
         tb_classificacao_financeiro as clf on clf.cl_id = lcf.cl_classificacao_id where lcf.cl_data_vencimento between '$data_inicial' and '$data_final' 
        and  lcf.cl_status_id = '3' and (prc.cl_razao_social  like '%$rz_paceiro%' or prc.cl_nome_fantasia  like '%$rz_paceiro%')  ";
        if ($conta_financeira != 0) {
            $select .= " and lcf.cl_conta_financeira = '$conta_financeira' ";
        }
        if ($forma_pagamento != 0) {
            $select .= " and lcf.cl_forma_pagamento_id = '$forma_pagamento' ";
        }
        if ($ordem == 1) {
            $select .= "  order by lcf.cl_parceiro_id asc ";
        } elseif ($ordem == 2) {
            $select .= "  order by lcf.cl_classificacao_id asc ";
        } elseif ($ordem == 3) {
            $select .= "  order by lcf.cl_data_vencimento asc ";
        } else {
            $select .= " order by lcf.cl_parceiro_id asc ";
        }
        $consulta = mysqli_query($conecta, $select);
        if (!$consulta) {
            die("Erro na consulta: " . mysqli_error($conecta));
        }
        $qtd_consulta = mysqli_num_rows($consulta);
    } elseif ($acao == "consultar_recebidas") {
        $titulo = 'Contas Recebidas';
        $th_1 = "Data Pagamento";
        $select = "SELECT lcf.cl_classificacao_id , DATEDIFF(lcf.cl_data_pagamento,lcf.cl_data_vencimento) as atraso,lcf.cl_status_id, lcf.cl_parceiro_id, lcf.cl_valor_bruto,lcf.cl_juros, lcf.cl_data_vencimento, clf.cl_descricao as classificacaofin,lcf.cl_tipo_lancamento,
         lcf.cl_valor_liquido,
         fpg.cl_descricao as formapg, lcf.cl_data_pagamento,lcf.cl_documento,prc.cl_razao_social
        from tb_lancamento_financeiro as lcf inner join tb_parceiros as prc on prc.cl_id
         = lcf.cl_parceiro_id inner join tb_forma_pagamento as fpg on fpg.cl_id = lcf.cl_forma_pagamento_id inner join 
         tb_classificacao_financeiro as clf on clf.cl_id = lcf.cl_classificacao_id where lcf.cl_data_vencimento between '$data_inicial' and '$data_final' 
        and  lcf.cl_status_id = '2' and (prc.cl_razao_social  like '%$rz_paceiro%' or prc.cl_nome_fantasia  like '%$rz_paceiro%')   ";
        if ($conta_financeira != 0) {
            $select .= " and lcf.cl_conta_financeira = '$conta_financeira' ";
        }
        if ($forma_pagamento != 0) {
            $select .= " and lcf.cl_forma_pagamento_id = '$forma_pagamento' ";
        }
        
        if ($ordem == 1) {
            $select .= "  order by lcf.cl_parceiro_id asc ";
        } elseif ($ordem == 2) {
            $select .= "  order by lcf.cl_classificacao_id asc ";
        } elseif ($ordem == 3) {
            $select .= "  order by lcf.cl_data_vencimento asc ";
        } else {
            $select .= " order by lcf.cl_parceiro_id asc ";
        }

        $consulta = mysqli_query($conecta, $select);
        if (!$consulta) {
            die("Erro na consulta: " . mysqli_error($conecta));
        }
        $qtd_consulta = mysqli_num_rows($consulta);
    } elseif ($acao == "consultar_pagar") {
        $titulo = 'Contas Pagas';
        $th_1 = "Data Pagamento";
        $select = "SELECT lcf.cl_classificacao_id , DATEDIFF(lcf.cl_data_pagamento,lcf.cl_data_vencimento) as atraso,lcf.cl_status_id, lcf.cl_parceiro_id, lcf.cl_valor_bruto,lcf.cl_juros, lcf.cl_data_vencimento, clf.cl_descricao as classificacaofin,lcf.cl_tipo_lancamento,
         lcf.cl_valor_liquido,
         fpg.cl_descricao as formapg, lcf.cl_data_pagamento,lcf.cl_documento,prc.cl_razao_social
        from tb_lancamento_financeiro as lcf inner join tb_parceiros as prc on prc.cl_id
         = lcf.cl_parceiro_id inner join tb_forma_pagamento as fpg on fpg.cl_id = lcf.cl_forma_pagamento_id inner join 
         tb_classificacao_financeiro as clf on clf.cl_id = lcf.cl_classificacao_id where lcf.cl_data_vencimento between '$data_inicial' and '$data_final' 
        and  lcf.cl_status_id = '4' and (prc.cl_razao_social  like '%$rz_paceiro%' or prc.cl_nome_fantasia  like '%$rz_paceiro%') ";
        if ($conta_financeira != 0) {
            $select .= " and lcf.cl_conta_financeira = '$conta_financeira' ";
        }
        if ($forma_pagamento != 0) {
            $select .= " and lcf.cl_forma_pagamento_id = '$forma_pagamento' ";
        }
        if ($ordem == 1) {
            $select .= "  order by lcf.cl_parceiro_id asc ";
        } elseif ($ordem == 2) {
            $select .= "  order by lcf.cl_classificacao_id asc ";
        } elseif ($ordem == 3) {
            $select .= "  order by lcf.cl_data_vencimento asc ";
        } else {
            $select .= " order by lcf.cl_parceiro_id asc ";
        }
        $consulta = mysqli_query($conecta, $select);
        if (!$consulta) {
            die("Erro na consulta: " . mysqli_error($conecta));
        }
        $qtd_consulta = mysqli_num_rows($consulta);
    } else {
        $titulo = "Listar contas";
        $qtd_consulta = 0;
    }
}

$select = "SELECT * FROM tb_conta_financeira";
$consultar_conta_financeira = mysqli_query($conecta, $select);

$select = "SELECT * FROM tb_forma_pagamento";
$consultar_forma_pagamento = mysqli_query($conecta, $select);
