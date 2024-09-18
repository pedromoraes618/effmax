<?php
/*dados da empresa */
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";

if (isset($_GET['relatorio'])) {
    $relatorio = $_GET['relatorio'];
} else {
    $relatorio = "";
}

$select = "SELECT  * from tb_empresa where cl_id ='1' ";
$consultar_empresa = mysqli_query($conecta, $select);
$linha = mysqli_fetch_assoc($consultar_empresa);
//$empresa = utf8_encode($linha['cl_empresa']);
$nome_fantasia  = utf8_encode($linha['cl_nome_fantasia']);
$cnpj_empresa  = formatCNPJCPF($linha['cl_cnpj']);
$endereco_empresa = utf8_encode($linha['cl_endereco']);
$numero_empresa = ($linha['cl_numero']);
$cep_empresa = ($linha['cl_cep']);
$telefone_empresa = ($linha['cl_telefone']);
$cidade_empresa =  utf8_encode($linha['cl_cidade']);
$estado_empresa = ($linha['cl_estado']);

$consultar_contabilizacao_caixa =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "6"); //VERIFICAR PARAMETRO ID - 6 // verificar se periodo do caixa vai ser contabilizado por dia ou mês
$data_inicial = $_GET['data_inicial'];
$data_final = $_GET['data_final'];
//formatar data para o banco de dados
// $data_inicial =  formatarDataParaBancoDeDados($data_inicial);
// $data_final =  formatarDataParaBancoDeDados($data_final);

$data_inicial_relatorio = formatDateB($data_inicial);
$data_final_relatorio = formatDateB($data_final);


if ($relatorio == "resumo_extrato_financeiro") {
    $conta_financeira = $_GET['conta_financeira'];
    $titulo_relatorio = "Extrato financeiro $data_inicial_relatorio - $data_final_relatorio"; //titulo do relatorio

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
AND cl_conta_financeira = '$conta_financeira'  ";
    $consulta = mysqli_query($conecta, $select);
    $qtd_consulta = mysqli_num_rows($consulta);
    if (!$consulta) {
        die("Erro na consulta:" . mysqli_error($conecta));
    } else {
        $linha = mysqli_fetch_assoc($consulta);
        $saldo_inical = $linha['saldo_inicial'];
    }




    $select = "SELECT clf.cl_descricao as classificacaofin,lcf.cl_tipo_lancamento, lcf.cl_valor_liquido, fpg.cl_descricao as formapg, lcf.cl_data_pagamento,lcf.cl_documento,prc.cl_razao_social
        from tb_lancamento_financeiro as lcf inner join tb_parceiros as prc on prc.cl_id
         = lcf.cl_parceiro_id inner join tb_forma_pagamento as fpg on fpg.cl_id = lcf.cl_forma_pagamento_id inner join tb_classificacao_financeiro as clf on clf.cl_id = lcf.cl_classificacao_id where lcf.cl_data_pagamento between '$data_inicial' and '$data_final' 
        and  (lcf.cl_status_id = '4' or lcf.cl_status_id='2') and lcf.cl_conta_financeira = '$conta_financeira' order by lcf.cl_status_id asc";
    $consulta_extrato_financeiro = mysqli_query($conecta, $select);
    $qtd_consulta_extrato_financeiro = mysqli_num_rows($consulta_extrato_financeiro);
} elseif ($relatorio == "resumo_caixa") { //resumo do caixa

    $titulo_relatorio = "Movimento do caixa $data_inicial_relatorio - $data_final_relatorio"; //titulo do relatorio

    // $partes = explode('-', $data_inicial);

    // // Extrai o ano, o mês e o dia
    // $ano = $partes[0];
    // $mes = $partes[1];
    // $dia = $partes[2];


    // $select = "SELECT * FROM tb_caixa where cl_ano !='' and cl_conta ='CAIXA' ";
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
    AND cl_conta_financeira = 'CAIXA'  ";
        $consulta = mysqli_query($conecta, $select);
        $qtd_consulta = mysqli_num_rows($consulta);
        if (!$consulta) {
            die("Erro na consulta:" . mysqli_error($conecta));
        } else {
            $linha = mysqli_fetch_assoc($consulta);
            $saldo_inical = $linha['saldo_inicial'];//SALDO INICIAL
        }

        $select = "SELECT lcf.cl_valor_liquido, fpg.cl_descricao as formapg, lcf.cl_data_pagamento,lcf.cl_documento,prc.cl_razao_social
        from tb_lancamento_financeiro as lcf inner join tb_parceiros as prc on prc.cl_id
         = lcf.cl_parceiro_id inner join tb_forma_pagamento as fpg on fpg.cl_id = lcf.cl_forma_pagamento_id where lcf.cl_data_pagamento between '$data_inicial' and '$data_final' 
        and lcf.cl_status_id = '4' and lcf.cl_conta_financeira = 'CAIXA' and lcf.cl_codigo_nf !='' order by lcf.cl_id desc";
        $consultar_despesa = mysqli_query($conecta, $select);

        $select = "SELECT lcf.cl_valor_liquido, fpg.cl_descricao as formapg, lcf.cl_data_pagamento,lcf.cl_documento,prc.cl_razao_social
        from tb_lancamento_financeiro as lcf inner join tb_parceiros as prc on prc.cl_id
         = lcf.cl_parceiro_id inner join tb_forma_pagamento as fpg on fpg.cl_id = lcf.cl_forma_pagamento_id where lcf.cl_data_pagamento between '$data_inicial' and '$data_final' 
        and lcf.cl_status_id = '4' and lcf.cl_conta_financeira = 'CAIXA' and (lcf.cl_codigo_nf = '' or lcf.cl_codigo_nf IS NULL ) order by lcf.cl_id desc";
        $consultar_outras_despesa = mysqli_query($conecta, $select);

        $select = "SELECT lcf.cl_valor_liquido, fpg.cl_descricao as formapg, lcf.cl_data_pagamento,lcf.cl_documento,prc.cl_razao_social
        from tb_lancamento_financeiro as lcf inner join tb_parceiros as prc on prc.cl_id
         = lcf.cl_parceiro_id inner join tb_forma_pagamento as fpg on fpg.cl_id = lcf.cl_forma_pagamento_id where lcf.cl_data_pagamento between '$data_inicial' and '$data_final' 
        and lcf.cl_status_id = '2' and lcf.cl_conta_financeira = 'CAIXA' and lcf.cl_codigo_nf !='' order by lcf.cl_id desc";
        $consultar_receita = mysqli_query($conecta, $select);

        $select = "SELECT lcf.cl_valor_liquido, fpg.cl_descricao as formapg, lcf.cl_data_pagamento,lcf.cl_documento,prc.cl_razao_social
        from tb_lancamento_financeiro as lcf inner join tb_parceiros as prc on prc.cl_id
         = lcf.cl_parceiro_id inner join tb_forma_pagamento as fpg on fpg.cl_id = lcf.cl_forma_pagamento_id where lcf.cl_data_pagamento between '$data_inicial' and '$data_final' 
        and lcf.cl_status_id = '2' and lcf.cl_conta_financeira = 'CAIXA' and (lcf.cl_codigo_nf = '' or lcf.cl_codigo_nf IS NULL ) order by lcf.cl_id desc";
        $consultar_outras_receita = mysqli_query($conecta, $select);
} elseif ($relatorio == "venda_fpg_caixa") {

    $titulo_relatorio = "Vendas $data_inicial_relatorio - $data_final_relatorio"; //titulo do relatorio

    $select = "SELECT cl_data_movimento,nf.cl_serie_nf,nf.cl_numero_nf,cl_razao_social,fpg.cl_descricao as formapg,nf.cl_valor_liquido from tb_nf_saida as nf inner join tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id inner join tb_parceiros as prc on prc.cl_id = nf.cl_parceiro_id where nf.cl_data_movimento
    between '$data_inicial' and '$data_final' 
            and nf.cl_status_venda = '1' order by nf.cl_forma_pagamento_id desc";
    $consultar_vendas_fpg = mysqli_query($conecta, $select);

    $select = "SELECT fpg.cl_descricao as formapg,sum(nf.cl_valor_liquido) as valorliquido from tb_nf_saida as nf inner join tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id inner join tb_parceiros as prc on prc.cl_id = nf.cl_parceiro_id where nf.cl_data_movimento
    between '$data_inicial' and '$data_final'  and nf.cl_status_venda = '1'  group by nf.cl_forma_pagamento_id order by nf.cl_forma_pagamento_id desc";
    $consultar_resumo_vendas_fpg = mysqli_query($conecta, $select);
}
