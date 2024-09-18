<?php
if (isset($_GET['consultar_historico_parceiro'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $parceiro_id = $_GET['parceiro_id']; //pegar o id do parceiro via get

    $razao_social = utf8_encode(consulta_tabela($conecta, "tb_parceiros", "cl_id", $parceiro_id, "cl_razao_social"));
}


if (isset($_GET['filtro_historico_parceiro'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $acao = $_GET['acao'];

    // $consulta = $_GET['consultar_venda'];
    $data_inicial = $_GET['data_inicial'];
    $data_final = $_GET['data_final'];
    $pesquisa = utf8_decode($_GET['palavra_chave']);
    $parceiro_id = ($_GET['parceiro_id']);


    if ($acao == "financeiro") { //tabela de financeiro do parceiro
        $titulo = "Financeiro";

        $select = "SELECT sts.cl_descricao as status, lcf.cl_status_id, lcf.cl_parceiro_id, lcf.cl_valor_bruto,lcf.cl_juros, lcf.cl_data_vencimento, clf.cl_descricao as classificacaofin,lcf.cl_tipo_lancamento,
         lcf.cl_valor_liquido,
         fpg.cl_descricao as formapg, lcf.cl_data_pagamento,lcf.cl_documento,prc.cl_razao_social
        from tb_lancamento_financeiro as lcf inner join tb_parceiros as prc on prc.cl_id
         = lcf.cl_parceiro_id inner join tb_forma_pagamento as fpg on fpg.cl_id = lcf.cl_forma_pagamento_id inner join 
         tb_classificacao_financeiro as clf on clf.cl_id = lcf.cl_classificacao_id inner join tb_status_recebimento as sts on sts.cl_id = lcf.cl_status_id
          where lcf.cl_parceiro_id ='$parceiro_id' and lcf.cl_data_vencimento between '$data_inicial' and '$data_final' and (lcf.cl_documento like '%$pesquisa%' or lcf.cl_valor_liquido like '%$pesquisa%' )";
        $consulta = mysqli_query($conecta, $select);
        if (!$consulta) {
            die("Erro na consulta: " . mysqli_error($conecta)); //retornar erro
        }
        $qtd_consulta = mysqli_num_rows($consulta);
    }

    if ($acao == "duplicatas_atraso") { //duplicatas em atraso
        $titulo = "Duplicatas em atraso";


        $query = "SELECT lcf.*, DATEDIFF('$data_lancamento', lcf.cl_data_vencimento ) as atraso
            FROM tb_lancamento_financeiro AS lcf
            WHERE lcf.cl_data_vencimento <= '$data_lancamento'
            AND lcf.cl_tipo_lancamento = 'RECEITA'
            AND lcf.cl_status_id = 1
            AND lcf.cl_parceiro_id = '$parceiro_id' order by atraso desc";
        $consulta = mysqli_query($conecta, $query);
        if (!$consulta) {
            die("Erro na consulta: " . mysqli_error($conecta));
        } else {
            $qtd_consulta = mysqli_num_rows($consulta);
        }
    }


    if ($acao == "vendas") { //tabela de vendas do parceiro
        $titulo = "Vendas";

        $select = "SELECT nf.cl_status_recebimento,nf.cl_status_venda, pgt.cl_descricao as formapgt,nf.cl_valor_liquido,nf.cl_data_movimento,nf.cl_serie_nf,nf.cl_numero_nf,
        user.cl_usuario as vendedor FROM tb_nf_saida as nf inner join tb_forma_pagamento as pgt on pgt.cl_id = nf.cl_forma_pagamento_id inner join tb_users as user on user.cl_id = nf.cl_vendedor_id 
         where nf.cl_parceiro_id ='$parceiro_id' and nf.cl_data_movimento between '$data_inicial' and '$data_final' and (nf.cl_serie_nf like '%$pesquisa%' or nf.cl_numero_nf like '%$pesquisa%'  or pgt.cl_descricao like '%$pesquisa%'  or user.cl_usuario like '%$pesquisa%' or nf.cl_valor_liquido like '%$pesquisa%')";
        $consulta = mysqli_query($conecta, $select);
        if (!$consulta) {
            die("Erro na consulta: " . mysqli_error($conecta)); //retornar erro
        }
        $qtd_consulta = mysqli_num_rows($consulta);
    }

    if ($acao == "produtos_vendidos") { //tabela de produtos que o parceiro adquiriu
        $titulo = "Produtos Vendidos";
        $select = "SELECT nfitem.cl_status as statusitem,  nf.cl_serie_nf,user.cl_usuario as vendedor,nf.cl_numero_nf as numeronota,
         nfitem.cl_data_movimento, nfitem.cl_descricao_item,
         nfitem.cl_quantidade,nfitem.cl_unidade,nfitem.cl_valor_unitario,nfitem.cl_valor_total 
         FROM tb_nf_saida as nf inner join tb_forma_pagamento as pgt on pgt.cl_id = nf.cl_forma_pagamento_id inner join tb_users as user on user.cl_id = nf.cl_vendedor_id 
         inner join tb_nf_saida_item as nfitem on nfitem.cl_codigo_nf = nf.cl_codigo_nf  inner join tb_parceiros as prc on prc.cl_id
         = nf.cl_parceiro_id where nf.cl_parceiro_id ='$parceiro_id' and nf.cl_data_movimento between '$data_inicial' and '$data_final' and (nf.cl_serie_nf like '%$pesquisa%' or
          nf.cl_numero_nf like '%$pesquisa%'  or pgt.cl_descricao like '%$pesquisa%'  or user.cl_usuario like '%$pesquisa%' or nf.cl_valor_liquido like '%$pesquisa%')";
        $consulta = mysqli_query($conecta, $select);
        if (!$consulta) {
            die("Erro na consulta: " . mysqli_error($conecta)); //retornar erro
        }
        $qtd_consulta = mysqli_num_rows($consulta);
    }


    if ($acao == "compras") { //tabela de financeiro do parceiro
        $titulo = "Compras";
        $select = "SELECT  nf.cl_codigo_nf, nf.cl_status_provisionamento, 
        nf.cl_status_nf,nf.cl_id as idnota, prc.cl_razao_social as fornecedor,fpg.cl_descricao as formapgt, nf.cl_data_entrada,nf.cl_data_emissao,nf.cl_numero_nf,nf.cl_serie_nf,nf.cl_forma_pagamento_id,nf.cl_valor_total_nota
        from tb_nf_entrada as nf inner join tb_parceiros as prc on prc.cl_id = nf.cl_parceiro_id left join tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id
      WHERE nf.cl_data_emissao between '$data_inicial' and '$data_final' and nf.cl_parceiro_id ='$parceiro_id' and
       nf.cl_numero_nf like '%{$pesquisa}%' order by nf.cl_data_emissao desc ";
        $consulta = mysqli_query($conecta, $select);
        if (!$consulta) {
            die("Erro na consulta: " . mysqli_error($conecta)); //retornar erro
        }
        $qtd_consulta = mysqli_num_rows($consulta);
    }

    if ($acao == "produtos_comprados") { //tabela de financeiro do parceiro
        $titulo = "Produtos Comprados";
        $select = "SELECT prd.cl_id as codigo, nf.cl_data_entrada,nf.cl_data_emissao,
        nf.cl_serie_nf,nf.cl_numero_nf as numeronota,nfitem.cl_quantidade ,md.cl_sigla,nfitem.cl_valor_unitario as vltunit,nfitem.cl_quantidade *nfitem.cl_valor_unitario as vlrtotal,
         prd.cl_descricao as item from tb_nf_entrada as nf inner join tb_nf_entrada_item as nfitem 
        on nf.cl_codigo_nf = nfitem.cl_codigo_nf 
        inner join tb_produtos as prd on prd.cl_id = nfitem.cl_produto_id inner join tb_unidade_medida as md on md.cl_id = prd.cl_und_id
        where nf.cl_parceiro_id ='$parceiro_id' ";
        $consulta = mysqli_query($conecta, $select);
        if (!$consulta) {
            die("Erro na consulta: " . mysqli_error($conecta)); //retornar erro
        }
        $qtd_consulta = mysqli_num_rows($consulta);
    }
}
