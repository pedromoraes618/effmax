<?php
//consultar informações para tabela
if (isset($_GET['movimento_estoque'])) {

    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";

    $acao = $_GET['acao'];
    // $consulta = $_GET['consultar_venda'];
    $data_inicial = $_GET['data_inicial'];
    $data_final = $_GET['data_final'];
    $palavra_chave = utf8_decode($_GET['palavra_chave']);
    if (isset($_GET['grupos_prd'])) { //verificar se foi selecionados grupos dos produtos
        $grupos_prd = ($_GET['grupos_prd']);
    } else {
        $grupos_prd = [];
    }


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

    if ($acao == "movimento_estoque") {
        $select = "SELECT
        prd.*,
        prd.cl_codigo AS codigo_produto,
        prd.cl_descricao AS descricao_produto,
        SUM(CASE WHEN ae.cl_tipo = 'ENTRADA' THEN ae.cl_quantidade ELSE 0 END) AS quantidade_entrada,
        SUM(CASE WHEN ae.cl_tipo = 'SAIDA' THEN ae.cl_quantidade ELSE 0 END) AS quantidade_saida,
        AVG(CASE WHEN ae.cl_tipo = 'SAIDA' THEN ae.cl_valor_venda ELSE 0 END) AS preco_medio,
        MIN(CASE WHEN ae.cl_tipo = 'SAIDA' AND ae.cl_valor_venda > 0 THEN ae.cl_valor_venda ELSE NULL END) AS preco_mais_barato,
        MAX(CASE WHEN ae.cl_tipo = 'SAIDA' THEN ae.cl_valor_venda ELSE 0 END) AS preco_mais_caro,
        SUM(CASE WHEN ae.cl_tipo = 'SAIDA' THEN ae.cl_valor_venda ELSE 0 END) AS valor_vendido,
        MAX(CASE WHEN ae.cl_tipo = 'ENTRADA' THEN ae.cl_data_lancamento ELSE NULL END) AS ultima_compra,
        MAX(CASE WHEN ae.cl_tipo = 'SAIDA' THEN ae.cl_data_lancamento ELSE NULL END) AS ultima_venda,
        DATEDIFF(NOW(), GREATEST(MAX(CASE WHEN ae.cl_tipo = 'SAIDA' THEN ae.cl_data_lancamento ELSE NULL END),
         MAX(CASE WHEN ae.cl_tipo = 'ENTRADA' THEN ae.cl_data_lancamento ELSE NULL END))) AS dias_sem_movimentacao

    FROM
        tb_produtos as prd
    LEFT JOIN
        tb_ajuste_estoque as ae ON prd.cl_id = ae.cl_produto_id
        where  ( prd.cl_descricao  like '%$palavra_chave%' or prd.cl_referencia  like '%$palavra_chave%'  or prd.cl_id  like '%$palavra_chave%' ) ";
        if (count($grupos_prd) > 0) {
            $select .= " AND (prd.cl_grupo_id IN (" . implode(',', $grupos_prd) . ")) ";
        }
        $select .= " GROUP BY
        prd.cl_id
    ORDER BY
        codigo_produto ";
        $consulta = mysqli_query($conecta, $select);
        $consulta_dashboard = mysqli_query($conecta, $select);
        if (!$consulta) {
            die("Erro na consulta: " . mysqli_error($conecta));
        }
        $qtd_consulta = mysqli_num_rows($consulta);

        // $resultados_dashboard = dashboard($consulta_dashboard, 'cl_razao_social', 'valor');
        // $dados_dashboard = $resultados_dashboard['data'];
        // $media_valores_dashboard = $resultados_dashboard['media'];
    } elseif ($acao == "estoque_zerado") {
        $select = "SELECT
        prd.*,
        prd.cl_codigo AS codigo_produto,
        prd.cl_descricao AS descricao_produto,
        SUM(CASE WHEN ae.cl_tipo = 'ENTRADA' THEN ae.cl_quantidade ELSE 0 END) AS quantidade_entrada,
        SUM(CASE WHEN ae.cl_tipo = 'SAIDA' THEN ae.cl_quantidade ELSE 0 END) AS quantidade_saida,
        AVG(CASE WHEN ae.cl_tipo = 'SAIDA' THEN ae.cl_valor_venda ELSE 0 END) AS preco_medio,
        MIN(CASE WHEN ae.cl_tipo = 'SAIDA' AND ae.cl_valor_venda > 0 THEN ae.cl_valor_venda ELSE NULL END) AS preco_mais_barato,
        MAX(CASE WHEN ae.cl_tipo = 'SAIDA' THEN ae.cl_valor_venda ELSE 0 END) AS preco_mais_caro,
        SUM(CASE WHEN ae.cl_tipo = 'SAIDA' THEN ae.cl_valor_venda ELSE 0 END) AS valor_vendido,
        MAX(CASE WHEN ae.cl_tipo = 'ENTRADA' THEN ae.cl_data_lancamento ELSE NULL END) AS ultima_compra,
        MAX(CASE WHEN ae.cl_tipo = 'SAIDA' THEN ae.cl_data_lancamento ELSE NULL END) AS ultima_venda,
        DATEDIFF(NOW(), GREATEST(MAX(CASE WHEN ae.cl_tipo = 'SAIDA' THEN ae.cl_data_lancamento ELSE NULL END),
         MAX(CASE WHEN ae.cl_tipo = 'ENTRADA' THEN ae.cl_data_lancamento ELSE NULL END))) AS dias_sem_movimentacao

    FROM
        tb_produtos as prd
    LEFT JOIN
        tb_ajuste_estoque as ae ON prd.cl_id = ae.cl_produto_id
        where prd.cl_estoque <=0 and ( prd.cl_descricao  like '%$palavra_chave%' or prd.cl_referencia  like '%$palavra_chave%'  or prd.cl_id  like '%$palavra_chave%' ) ";
        if (count($grupos_prd) > 0) {
            $select .= " AND (prd.cl_grupo_id IN (" . implode(',', $grupos_prd) . ")) ";
        }
        $select .= " GROUP BY
        prd.cl_id
    ORDER BY
        codigo_produto ";
        $consulta = mysqli_query($conecta, $select);
        $consulta_dashboard = mysqli_query($conecta, $select);
        if (!$consulta) {
            die("Erro na consulta: " . mysqli_error($conecta));
        }
        $qtd_consulta = mysqli_num_rows($consulta);

        // $resultados_dashboard = dashboard($consulta_dashboard, 'cl_razao_social', 'valor');
        // $dados_dashboard = $resultados_dashboard['data'];
        // $media_valores_dashboard = $resultados_dashboard['media'];
    } elseif ($acao == "estoque_minimo_maximo") {
        $select = "SELECT
        prd.*,um.cl_sigla as unidadem,
        prd.cl_codigo AS codigo_produto,
        prd.cl_descricao AS descricao_produto,
        MAX(CASE WHEN ae.cl_tipo = 'ENTRADA' THEN ae.cl_data_lancamento ELSE NULL END) AS ultima_compra,
        MAX(CASE WHEN ae.cl_tipo = 'SAIDA' THEN ae.cl_data_lancamento ELSE NULL END) AS ultima_venda
    FROM
        tb_produtos as prd
    LEFT JOIN
        tb_ajuste_estoque as ae ON prd.cl_id = ae.cl_produto_id
    LEFT JOIN
        tb_unidade_medida as um ON um.cl_id = prd.cl_und_id
        where ( prd.cl_descricao  like '%$palavra_chave%' or prd.cl_referencia  like '%$palavra_chave%'  or prd.cl_id  like '%$palavra_chave%') and ( prd.cl_estoque_minimo > prd.cl_estoque ) ";
        if (count($grupos_prd) > 0) {
            $select .= " AND (prd.cl_grupo_id IN (" . implode(',', $grupos_prd) . ")) ";
        }
        $select .= " GROUP BY prd.cl_Id
    ORDER BY
        codigo_produto ";
        $consulta = mysqli_query($conecta, $select);
        $consulta_dashboard = mysqli_query($conecta, $select);
        if (!$consulta) {
            die("Erro na consulta: " . mysqli_error($conecta));
        }
        $qtd_consulta = mysqli_num_rows($consulta);
    } elseif ($acao == "sugestao_compra") {
        $select = "SELECT
    prd.cl_estoque_minimo - prd.cl_estoque AS repor,
        prd.*,um.cl_sigla as unidadem,
        prd.cl_codigo AS codigo_produto,
        prd.cl_descricao AS descricao_produto,
        MAX(CASE WHEN ae.cl_tipo = 'ENTRADA' THEN ae.cl_data_lancamento ELSE NULL END) AS ultima_compra,
        MAX(CASE WHEN ae.cl_tipo = 'SAIDA' THEN ae.cl_data_lancamento ELSE NULL END) AS ultima_venda
    FROM
        tb_produtos as prd
    LEFT JOIN
        tb_ajuste_estoque as ae ON prd.cl_id = ae.cl_produto_id
    LEFT JOIN
        tb_unidade_medida as um ON um.cl_id = prd.cl_und_id
        where ( prd.cl_descricao  like '%$palavra_chave%' or prd.cl_referencia 
         like '%$palavra_chave%'  or prd.cl_id  like '%$palavra_chave%') and ( prd.cl_estoque_minimo > prd.cl_estoque ) ";
        if (count($grupos_prd) > 0) {
            $select .= " AND (prd.cl_grupo_id IN (" . implode(',', $grupos_prd) . ")) ";
        }
        $select .= " GROUP BY prd.cl_Id
    ORDER BY
        codigo_produto ";
        $consulta = mysqli_query($conecta, $select);
        $consulta_dashboard = mysqli_query($conecta, $select);
        if (!$consulta) {
            die("Erro na consulta: " . mysqli_error($conecta));
        }
        $qtd_consulta = mysqli_num_rows($consulta);
    } else {
        $titulo = "Listar contas";
        $qtd_consulta = 0;
    }
}
