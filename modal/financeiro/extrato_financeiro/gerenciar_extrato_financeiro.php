<?php
//consultar informações para tabela
if (isset($_GET['extrato_financeiro'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $acao = $_GET['acao'];
    // $consulta = $_GET['consultar_venda'];
    $data_inicial = $_GET['data_inicial'];
    $data_final = $_GET['data_final'];
    $conta_financeira = $_GET['conta_financeira'];


    // // //formatar data para o banco de dados
    // // $data_inicial =  formatarDataParaBancoDeDados($data_inicial);
    // // $data_final =  formatarDataParaBancoDeDados($data_final);

    // $consultar_contabilizacao_caixa =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "6"); //VERIFICAR PARAMETRO ID - 6 // verificar se periodo do caixa vai ser contabilizado por dia ou mês
    // // Divide a data em partes
    // $partes = explode('-', $data_inicial);

    // // Extrai o ano, o mês e o dia
    // $ano = $partes[0];
    // $mes = $partes[1];
    // $dia = $partes[2];


    // $select = "SELECT * FROM tb_caixa where cl_ano !='' and cl_conta ='$conta_financeira' ";
    // if ($consultar_contabilizacao_caixa == "DIA") {
    //     $select .= " and cl_dia = '$dia' and cl_mes ='$mes' and cl_ano='$ano' "; // se for por periodo de contabilização em dia a dia vai verifiar o dia, o mes e o ano
    // } elseif ($consultar_contabilizacao_caixa == "MES") {
    //     $select .= " and cl_mes = '$mes' and cl_ano ='$ano'"; // se for por periodo de contabilização em mes a mes vai verifiar o mes e o ano
    // } else {
    //     $select .= " and cl_dia = '$dia' and cl_mes ='$mes' and cl_ano='$ano' "; // se o paramentro estivir com valor incorreto será atribuido o periodo de dia a dia
    // }

    // $consulta_caixa = mysqli_query($conecta, $select);
    // if ($consulta_caixa) {
    //     $resultado_consulta = mysqli_num_rows($consulta_caixa);
    //     $linha = mysqli_fetch_assoc($consulta_caixa);
    //     $status = $linha['cl_status'];
    //     $valor_fechado = $linha['cl_valor_abertura'];
    // }
    $select = "SELECT 
    SUM(CASE WHEN cl_status_id = '2' THEN cl_valor_liquido ELSE 0 END) AS entrada,
    SUM(CASE WHEN cl_status_id = '4' THEN cl_valor_liquido ELSE 0 END) AS saida,
    SUM(CASE WHEN cl_status_id = '2' THEN cl_valor_liquido ELSE 0 END) - SUM(CASE WHEN cl_status_id = '4' THEN cl_valor_liquido ELSE 0 END) as saldo_inicial
FROM 
    tb_lancamento_financeiro 
WHERE 
    cl_data_pagamento < '$data_inicial' 
    AND cl_conta_financeira = '$conta_financeira'  "; //total de entrada para saldo inicial
    $consulta = mysqli_query($conecta, $select);
    $qtd_consulta = mysqli_num_rows($consulta);
    if (!$consulta) {
        die("Erro na consulta:" . mysqli_error($conecta));
    } else {
        $linha = mysqli_fetch_assoc($consulta);
        $saldo_inical = $linha['saldo_inicial'];
    }


    if ($acao == "extrato") { //puxar as receitas e despesas da conta financeira selecionada
        $select = "SELECT clf.cl_descricao as classificacaofin,lcf.cl_tipo_lancamento, 
        lcf.cl_valor_liquido, fpg.cl_descricao as formapg, lcf.cl_data_pagamento,lcf.cl_documento,prc.cl_razao_social
        from tb_lancamento_financeiro as lcf inner join tb_parceiros as prc on prc.cl_id
         = lcf.cl_parceiro_id inner join tb_forma_pagamento as fpg on fpg.cl_id = lcf.cl_forma_pagamento_id inner join tb_classificacao_financeiro as clf on clf.cl_id = lcf.cl_classificacao_id where lcf.cl_data_pagamento between '$data_inicial' and '$data_final' 
        and  (lcf.cl_status_id = '4' or lcf.cl_status_id='2') and lcf.cl_conta_financeira = '$conta_financeira' order by lcf.cl_data_pagamento asc";
        $consulta_extrato_financeiro = mysqli_query($conecta, $select);
        $qtd_consulta_extrato_financeiro = mysqli_num_rows($consulta_extrato_financeiro);

        $select = "SELECT clf.cl_descricao as classificacaofin,
        SUM(CASE WHEN lcf.cl_status_id = '2' THEN lcf.cl_valor_liquido ELSE 0 END) AS total_receita,
        SUM(CASE WHEN lcf.cl_status_id = '4' THEN lcf.cl_valor_liquido ELSE 0 END) AS total_despesa
     from tb_lancamento_financeiro as lcf inner join tb_parceiros as prc on prc.cl_id
         = lcf.cl_parceiro_id inner join tb_classificacao_financeiro as clf on clf.cl_id = lcf.cl_classificacao_id 
          where lcf.cl_data_pagamento between '$data_inicial' and '$data_final' 
        and lcf.cl_conta_financeira = '$conta_financeira' group by lcf.cl_classificacao_id ";
        $consulta_extrato_financeiro_classificacao = mysqli_query($conecta, $select);
        if (!$consulta_extrato_financeiro_classificacao) {
            die("erro na consulta " . mysqli_error($conecta));
        }
        $qtd_consulta_extrato_financeiro_classificacao = mysqli_num_rows($consulta_extrato_financeiro_classificacao);

        $select = "SELECT fpg.cl_descricao as formapagamento,
        SUM(CASE WHEN lcf.cl_status_id = '2' THEN lcf.cl_valor_liquido ELSE 0 END) AS total_receita,
        SUM(CASE WHEN lcf.cl_status_id = '4' THEN lcf.cl_valor_liquido ELSE 0 END) AS total_despesa
     from tb_lancamento_financeiro as lcf inner join tb_parceiros as prc on prc.cl_id
         = lcf.cl_parceiro_id inner join tb_classificacao_financeiro as clf on clf.cl_id = lcf.cl_classificacao_id 
         inner join tb_forma_pagamento as fpg on fpg.cl_id = lcf.cl_forma_pagamento_id  where lcf.cl_data_pagamento between '$data_inicial' and '$data_final' 
        and lcf.cl_conta_financeira = '$conta_financeira' group by lcf.cl_forma_pagamento_id ";
        $consulta_extrato_financeiro_forma_pagamento = mysqli_query($conecta, $select);
        if (!$consulta_extrato_financeiro_forma_pagamento) {
            die("erro na consulta " . mysqli_error($conecta));
        }
        $qtd_consulta_extrato_financeiro_forma_pagamento = mysqli_num_rows($consulta_extrato_financeiro_forma_pagamento);
    } elseif ($acao == "resumo_extrato") {


        $select = "SELECT
        DATE(cl_data_pagamento) AS data,
        SUM(CASE WHEN cl_status_id = '2' THEN cl_valor_liquido ELSE 0 END) AS entrada,
        SUM(CASE WHEN cl_status_id = '4' THEN cl_valor_liquido ELSE 0 END) AS saida,
        (
            SELECT SUM(CASE WHEN cl_status_id = '2' THEN cl_valor_liquido ELSE 0 END)
            FROM tb_lancamento_financeiro
            WHERE
                cl_conta_financeira = '$conta_financeira'
                AND DATE(cl_data_pagamento) < '$data_inicial'
        ) AS entrada_anterior,
        (
            SELECT SUM(CASE WHEN cl_status_id = '4' THEN cl_valor_liquido ELSE 0 END)
            FROM tb_lancamento_financeiro
            WHERE
                cl_conta_financeira = '$conta_financeira'
                AND DATE(cl_data_pagamento) < '$data_inicial'
        ) AS saida_anterior
    FROM
        tb_lancamento_financeiro
    WHERE
        cl_conta_financeira = '$conta_financeira'
        AND cl_data_pagamento BETWEEN '$data_inicial' AND '$data_final'
    GROUP BY
        DATE(cl_data_pagamento)";
        $consulta = mysqli_query($conecta, $select);
        $qtd_consulta = mysqli_num_rows($consulta);
        if (!$consulta) {
            die("Erro na consulta:" . mysqli_error($conecta));
        }
    }
}

$select = "SELECT * FROM tb_conta_financeira";
$consultar_conta_financeira = mysqli_query($conecta, $select);
