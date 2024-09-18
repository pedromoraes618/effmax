<?php
//consultar informações para tabela
if (isset($_GET['faturamento'])) {

    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $acao = $_GET['acao'];
    // $consulta = $_GET['consultar_venda'];
    $data_inicial = $_GET['data_inicial'];
    $data_final = $_GET['data_final'];
    $palavra_chave = utf8_decode($_GET['palavra_chave']);

    function dashboard($consulta, $valor_ref1, $valor_ref2)
    {
        global $qtd_consulta;
        $data_array = [];  // Array para armazenar os valores de data
        $soma_valores = 0;
        while ($linha = mysqli_fetch_assoc($consulta)) {
            $dados1 = utf8_encode($linha["$valor_ref1"]); //label
            $dados2 = ($linha["$valor_ref2"]); //valor
            $soma_valores += $dados2; // Adiciona o valor para a soma

            $data_array[] = ['label' => $dados1, 'valor' => $dados2];
        }
        $media_valores = $soma_valores / $qtd_consulta; // Calcula a média

        return ['data' => $data_array, 'media' => $media_valores]; // Retorna o array 
    }

    if ($acao == "faturamento_cliente") { //puxar as receitas e despesas da conta financeira selecionada
        $select = "SELECT prc.cl_razao_social, sum(nf.cl_valor_liquido) as valor FROM tb_nf_saida as nf inner join
         tb_parceiros as prc on prc.cl_id = nf.cl_parceiro_id  where nf.cl_data_movimento
          between '$data_inicial' and '$data_final' and (prc.cl_razao_social  like '%$palavra_chave%' or 
          prc.cl_nome_fantasia  like '%$palavra_chave%' or 
          prc.cl_cnpj_Cpf  like '%$palavra_chave%' ) 
          and nf.cl_status_venda ='1' and nf.cl_operacao='VENDA' group by nf.cl_parceiro_id order by valor desc";
        $consulta = mysqli_query($conecta, $select);
        $consulta_dashboard = mysqli_query($conecta, $select);
        if (!$consulta) {
            die("Erro na consulta: " . mysqli_error($conecta));
        }
        $qtd_consulta = mysqli_num_rows($consulta);

        $resultados_dashboard = dashboard($consulta_dashboard, 'cl_razao_social', 'valor');
        $dados_dashboard = $resultados_dashboard['data'];
        $media_valores_dashboard = $resultados_dashboard['media'];
    } elseif ($acao == "faturamento_produto") {
        $select = "SELECT prd.cl_id as codigo, prd.cl_descricao as produto,count(nfi.cl_item_id) as qtdvendidos, sum(nfi.cl_valor_total) as valor 
         FROM tb_nf_saida_item  as nfi inner join tb_produtos as prd on prd.cl_id = nfi.cl_item_id 
         inner join tb_nf_saida as nf on nf.cl_codigo_nf = nfi.cl_codigo_nf where nf.cl_data_movimento between '$data_inicial' and '$data_final'  
         and prd.cl_descricao  like '%$palavra_chave%'  and nf.cl_status_venda ='1'  and nf.cl_operacao='VENDA'
         group by nfi.cl_item_id order by valor desc ";
        $consulta = mysqli_query($conecta, $select);
        $consulta_dashboard = mysqli_query($conecta, $select);

        if (!$consulta) {
            die("Erro na consulta: " . mysqli_error($conecta));
        }
        $qtd_consulta = mysqli_num_rows($consulta);

        $resultados_dashboard = dashboard($consulta_dashboard, 'produto', 'valor');
        $dados_dashboard = $resultados_dashboard['data'];
        $media_valores_dashboard = $resultados_dashboard['media'];
    } elseif ($acao == "faturamento_grupo_produto") {
        $select = "SELECT grp.cl_descricao as grupo,  sub.cl_descricao as subgrupo, sum(nfi.cl_valor_total) as valor 
         FROM tb_nf_saida_item as nfi inner join tb_produtos as prd on prd.cl_id = nfi.cl_item_id 
         inner join tb_subgrupo_estoque as sub on sub.cl_id =prd.cl_grupo_id inner join 
         tb_grupo_estoque as grp on grp.cl_id = sub.cl_grupo_id inner join tb_nf_saida as nf on nf.cl_codigo_nf = nfi.cl_codigo_nf
         where nf.cl_data_movimento between '$data_inicial' and '$data_final'  
         and sub.cl_descricao  like '%$palavra_chave%'  and nf.cl_status_venda ='1' and nf.cl_operacao='VENDA'  group by
          prd.cl_grupo_id order by valor desc ";
        $consulta = mysqli_query($conecta, $select);
        $consulta_dashboard = mysqli_query($conecta, $select);

        if (!$consulta) {
            die("Erro na consulta: " . mysqli_error($conecta));
        }
        $qtd_consulta = mysqli_num_rows($consulta);
        $resultados_dashboard = dashboard($consulta_dashboard, 'subgrupo', 'valor');
        $dados_dashboard = $resultados_dashboard['data'];
        $media_valores_dashboard = $resultados_dashboard['media'];
    } elseif ($acao == "faturamento_vendedor") {
        $select = "SELECT 
        user.cl_nome as usuario, 
        SUM(nf.cl_valor_liquido) as valor, 
        COUNT(nf.cl_id) as numerovendas,
        SUM(nf.cl_valor_liquido) / COUNT(nf.cl_id) as mediavendas
    FROM 
        tb_nf_saida as nf 
    INNER JOIN
        tb_users as user on user.cl_id = nf.cl_vendedor_id 
    WHERE 
        nf.cl_data_movimento BETWEEN '$data_inicial' AND '$data_final' 
        AND user.cl_usuario LIKE '%$palavra_chave%'
        AND nf.cl_status_venda = '1' 
        and nf.cl_operacao='VENDA'
    GROUP BY 
        nf.cl_vendedor_id  order by valor desc";
        $consulta = mysqli_query($conecta, $select);
        $consulta_dashboard = mysqli_query($conecta, $select);

        if (!$consulta) {
            die("Erro na consulta: " . mysqli_error($conecta));
        }
        $qtd_consulta = mysqli_num_rows($consulta);
        $resultados_dashboard = dashboard($consulta_dashboard, 'usuario', 'valor');
        $dados_dashboard = $resultados_dashboard['data'];
        $media_valores_dashboard = $resultados_dashboard['media'];
    } elseif ($acao == "faturamento_pagamento") {
        $select = "SELECT nf.*,SUM(nf.cl_valor_liquido) as valor,fpg.cl_descricao as pagamento from tb_nf_saida as nf inner join tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id
         where nf.cl_data_movimento between '$data_inicial' and '$data_final'  
         and fpg.cl_descricao  like '%$palavra_chave%'  and nf.cl_status_venda ='1'  and nf.cl_operacao='VENDA' group by
          fpg.cl_id order by valor desc ";
        $consulta = mysqli_query($conecta, $select);
        $consulta_dashboard = mysqli_query($conecta, $select);

        if (!$consulta) {
            die("Erro na consulta: " . mysqli_error($conecta));
        }
        $qtd_consulta = mysqli_num_rows($consulta);
        $resultados_dashboard = dashboard($consulta_dashboard, 'pagamento', 'valor');
        $dados_dashboard = $resultados_dashboard['data'];
        $media_valores_dashboard = $resultados_dashboard['media'];
    } elseif ($acao == "faturamento_diario") {
        $select = "SELECT nf.*,SUM(nf.cl_valor_liquido) as valor,count(*) as qtdvendas, nf.cl_data_movimento from tb_nf_saida as nf 
         where nf.cl_data_movimento between '$data_inicial' and '$data_final' and nf.cl_status_venda ='1' and nf.cl_operacao='VENDA' group by nf.cl_data_movimento 
         order by cl_data_movimento desc ";
        $consulta = mysqli_query($conecta, $select);
        $consulta_dashboard = mysqli_query($conecta, $select);

        if (!$consulta) {
            die("Erro na consulta: " . mysqli_error($conecta));
        }
        $qtd_consulta = mysqli_num_rows($consulta);
        $resultados_dashboard = dashboard($consulta_dashboard, 'cl_data_movimento', 'valor');
        $dados_dashboard = $resultados_dashboard['data'];
        $media_valores_dashboard = $resultados_dashboard['media'];
    } else {
        $titulo = "Listar contas";
        $qtd_consulta = 0;
    }
}

