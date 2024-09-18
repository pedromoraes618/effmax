<?php
//consultar informações para tabela
if (isset($_GET['consultar_relatorio_delivery'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $acao = $_GET['consultar_relatorio_delivery'];
    if ($acao == "inicial") {
        $data_inicial = $data_inicial_mes_bd;
        $data_final = $data_final_mes_bd;
    } else {
        $data_inicial = $_GET['data_inicial'];
        $data_final = $_GET['data_final'];
    }
    $select_produtos_mais_vendidos = "SELECT cl_descricao_item,sum(cl_valor_total) as valortotal, SUM(cl_quantidade) as total_vendido, sum(cl_valor_total) AS valor_total 
    FROM tb_nf_saida_item as nf inner join tb_produtos as prd on prd.cl_id = nf.cl_item_id where 
    cl_status ='1'  and (prd.cl_tipo_id ='2' or prd.cl_tipo_id ='6') and  cl_data_movimento between '$data_inicial' and '$data_final' GROUP BY cl_item_id  order by total_vendido desc";
    $consulta_produtos_vendas =  mysqli_query($conecta, $select_produtos_mais_vendidos);
    $consulta_produtos_vendas_descricao =  mysqli_query($conecta, $select_produtos_mais_vendidos);
    if (!$consulta_produtos_vendas) {
        die("Erro na consulta:" . mysqli_error($conecta));
    }

    $select_vendas = "SELECT sum(cl_valor_Liquido) as valortotal, count(cl_id) as qtdtotal from tb_nf_saida
     WHERE cl_data_movimento between '$data_inicial' and '$data_final' and cl_status_venda ='1' "; //informação das vendas quantidade de vendas e o valor total das vendas
    $consulta_vendas =  mysqli_query($conecta, $select_vendas);
    $linha = mysqli_fetch_assoc($consulta_vendas);
    $valor_total_vendas = $linha['valortotal'];
    $qtd_vendas = $linha['qtdtotal'];


    $select_vendas = "SELECT sum(cl_valor_Liquido) as valortotal, count(cl_id) as qtdtotal from tb_nf_saida
     WHERE cl_data_movimento between '$data_inicial' and '$data_final' and cl_status_venda ='1' and cl_opcao_delivery= 'LOCAL' "; //venda por tipo consumo no local
    $consulta_vendas =  mysqli_query($conecta, $select_vendas);
    $linha = mysqli_fetch_assoc($consulta_vendas);
    $valor_opcao_delivery_vendas_local = $linha['valortotal'];
    $qtd_opcao_delivery_vendas_local = $linha['qtdtotal'];

    $select_vendas = "SELECT sum(cl_valor_Liquido) as valortotal, count(cl_id) as qtdtotal from tb_nf_saida
    WHERE cl_data_movimento between '$data_inicial' and '$data_final' and cl_status_venda ='1' and cl_opcao_delivery= 'RETIRADA' "; //venda por tipo consumo no retirada
    $consulta_vendas =  mysqli_query($conecta, $select_vendas);
    $linha = mysqli_fetch_assoc($consulta_vendas);
    $valor_opcao_delivery_vendas_retirada = $linha['valortotal'];
    $qtd_opcao_delivery_vendas_retirada = $linha['qtdtotal'];


    $select_vendas = "SELECT sum(cl_valor_Liquido) as valortotal, count(cl_id) as qtdtotal from tb_nf_saida
   WHERE cl_data_movimento between '$data_inicial' and '$data_final' and cl_status_venda ='1' and cl_opcao_delivery= 'ENTREGA' "; //venda por tipo consumo no entrega
    $consulta_vendas =  mysqli_query($conecta, $select_vendas);
    $linha = mysqli_fetch_assoc($consulta_vendas);
    $valor_opcao_delivery_vendas_entrega = $linha['valortotal'];
    $qtd_opcao_delivery_vendas_entrega = $linha['qtdtotal'];


    $select_vendas = "SELECT sum(cl_valor_Liquido) as valortotal, count(cl_id) as qtdtotal from tb_nf_saida
  WHERE cl_data_movimento between '$data_inicial' and '$data_final' and cl_status_venda ='3' "; //venda canceladas
    $consulta_vendas =  mysqli_query($conecta, $select_vendas);
    $linha = mysqli_fetch_assoc($consulta_vendas);
    $valor_delivery_vendas_canceladas = $linha['valortotal'];
    $qtd_delivery_vendas_canceladas = $linha['qtdtotal'];


    $select_vendas_fpg = "SELECT fpg.cl_descricao as formapagamento, sum(nf.cl_valor_Liquido) as valortotal from tb_nf_saida as nf 
    inner join tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id
    WHERE nf.cl_data_movimento between '$data_inicial' and '$data_final' and nf.cl_status_venda ='1' group by
     nf.cl_forma_pagamento_id order by valortotal desc  "; //informação de vendas por forma de pagamento
    $consulta_vendas_fpg =  mysqli_query($conecta, $select_vendas_fpg);
    if (!$consulta_vendas_fpg) {
        die("Erro na consulta:" . mysqli_error($conecta));
    }


    $array_relatorio_vendas = [$qtd_vendas, $qtd_opcao_delivery_vendas_local, $qtd_opcao_delivery_vendas_retirada, $qtd_opcao_delivery_vendas_entrega];
}
