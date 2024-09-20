<?php
if (isset($_GET['consultar_relatorio'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";

    $usuario_id = verifica_sessao_usuario(); //pegar o id do usuazrio na sessao
    $periodo_dashboard = consulta_tabela($conecta, 'tb_users', 'cl_id', $usuario_id, 'cl_periodo_dashboard');
}


if (isset($_GET['consultar_container_relatorio_atividades'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";

    $container = $_GET['consultar_container_relatorio_atividades'];

    $data_inicial = isset($_GET['data_inicial']) ? $_GET['data_inicial'] : $data_inicial;
    $data_final = isset($_GET['data_final']) ? $_GET['data_final'] : $data_final;

    // Definindo a data inicial e final do período anterior com base nas variáveis atuais
    $data_inicial_anterior = date('Y-m-01', strtotime($data_inicial . ' -1 month'));
    $data_final_anterior = date('Y-m-t', strtotime($data_final . ' -1 month'));

    // Extraindo o ano de $data_inicial
    $ano_atual = date('Y', strtotime($data_inicial));

    // Obtendo o ano anterior
    $ano_passado = $ano_atual - 1;


    /* Iniciando as variáveis */
    $variacao_vendas = 0;
    $variacao_texto = '';
    $variacao_ticket_medio = 0;
    $valor_total_vendas_presente = 0;
    $valor_total_vendas_passado = 0;
    /* query VENDAS*/
    $valor_vendido_presente = consulta_tabela_query($conecta, "SELECT sum(cl_valor_liquido) as total FROM tb_nf_saida 
    where cl_operacao ='VENDA'
     and cl_status_venda ='1' 
     and cl_data_movimento between '$data_inicial' and '$data_final' ", 'total');

    $qtd_vendas_presente = consulta_tabela_query($conecta, "SELECT count(*) as total FROM tb_nf_saida 
    where cl_operacao ='VENDA'
    and cl_status_venda ='1' 
    and cl_data_movimento between '$data_inicial' and '$data_final' ", 'total');

    $valor_vendido_passado = consulta_tabela_query($conecta, "SELECT sum(cl_valor_liquido) as total FROM tb_nf_saida 
       where cl_operacao ='VENDA'
        and cl_status_venda ='1' 
        and cl_data_movimento between '$data_inicial_anterior' and '$data_final_anterior' ", 'total');

    $qtd_vendas_passado = consulta_tabela_query($conecta, "SELECT count(*) as total FROM tb_nf_saida 
    where cl_operacao ='VENDA'
    and cl_status_venda ='1' 
    and cl_data_movimento between '$data_inicial_anterior' and '$data_final_anterior' ", 'total');



    if ($valor_vendido_passado > 0) {
        $variacao_vendas = (($valor_vendido_presente - $valor_vendido_passado) / $valor_vendido_passado) * 100;
    }
    // Formatação da variação para exibição
    $variacao_vendas_formatada = formatarPorcentagem($variacao_vendas);
    $variacao_vendas_span = ($variacao_vendas > 0) ? "<small class='text-success'><i class='bi bi-arrow-up-short'></i>$variacao_vendas_formatada Desde o mês passado</small>" :
        "<small class='text-danger'><i class='bi bi-arrow-down-short'></i>$variacao_vendas_formatada Desde o mês passado</small>";

    $valor_vendido_cancelado = consulta_tabela_query($conecta, "SELECT sum(cl_valor_liquido) as total FROM tb_nf_saida 
    where cl_operacao ='VENDA'
     and cl_status_venda ='3' 
     and cl_data_movimento between '$data_inicial' and '$data_final' ", 'total');

    $valor_pedente_recebimento = consulta_tabela_query($conecta, "SELECT sum(cl_valor_liquido) as total FROM tb_nf_saida 
   where cl_operacao ='VENDA'
    and cl_status_venda ='1' and cl_status_recebimento='1' 
    and cl_data_movimento between '$data_inicial' and '$data_final' ", 'total');


    // Variação do Ticket Médio
    // Cálculo do Ticket Médio
    $ticket_medio_vendas = ($qtd_vendas_presente > 0) ? $valor_vendido_presente / $qtd_vendas_presente : 0;
    $ticket_medio_passado = ($qtd_vendas_passado > 0) ? $valor_vendido_passado / $qtd_vendas_passado : 0;


    // Formatação do Ticket Médio
    $ticket_medio_vendas_formatado = number_format($ticket_medio_vendas, 2);
    $ticket_medio_passado_formatado = number_format($ticket_medio_passado, 2);

    if ($ticket_medio_passado > 0) {
        $variacao_ticket_medio = (($ticket_medio_vendas - $ticket_medio_passado) / $ticket_medio_passado) * 100;
    }

    // Formatação da variação do ticket médio para exibição
    $variacao_ticket_medio_formatada = formatarPorcentagem($variacao_ticket_medio);
    $variacao_ticket_medio_span = ($variacao_ticket_medio > 0)
        ? "<small class='text-success'><i class='bi bi-arrow-up-short'></i>$variacao_ticket_medio_formatada Desde o mês passado</small>" :
        "<small class='text-danger'><i class='bi bi-arrow-down-short'></i>$variacao_ticket_medio_formatada Desde o mês passado</small>";


    $consulta_produtos_mais_vendidos = consulta_linhas_tb_query($conecta, "SELECT 
    SUM(ajt.cl_quantidade) AS qtd, 
    prd.cl_descricao, 
    SUM(ajt.cl_valor_venda * ajt.cl_quantidade) AS valorvendido  FROM 
    tb_ajuste_estoque ajt INNER JOIN tb_produtos prd  ON prd.cl_id = ajt.cl_produto_id
WHERE ajt.cl_data_lancamento between '$data_inicial' and '$data_final'  and ajt.cl_ajuste = '0'  AND ajt.cl_status = 'ok'  AND ajt.cl_ajuste_inicial = '0' AND COALESCE(ajt.cl_id_nf_pai, '') = ''
GROUP BY  prd.cl_descricao ORDER BY  qtd DESC 
LIMIT 5");

    $consulta_vendedor_mais_vendas = consulta_linhas_tb_query($conecta, "SELECT user.cl_valor_comissao, sum(cl_valor_liquido) as valorvendido, nf.cl_vendedor_id, user.cl_nome,
     COUNT(*) AS totalvendas
FROM tb_nf_saida AS nf
INNER JOIN tb_users AS user ON user.cl_id = nf.cl_vendedor_id where nf.cl_data_movimento between '$data_inicial' and '$data_final' 
GROUP BY nf.cl_vendedor_id ");

    $consulta_notas_emitidas = consulta_linhas_tb_query($conecta, "SELECT count(*) as qtd, nf.* 
     FROM tb_nf_saida as nf  where  COALESCE(cl_numero_protocolo, '') <> '' 
     and nf.cl_data_emisao_nf between '$data_inicial' and '$data_final'  group by cl_serie_nf,cl_operacao order by qtd desc ");

    $valor_vendas_forma_pagamento = consulta_linhas_tb_query($conecta, "SELECT sum(lcf.cl_valor_liquido) as valor,fpg.cl_descricao as formapagamento FROM tb_nf_saida as nf
     inner join tb_lancamento_financeiro as lcf on lcf.cl_codigo_nf = nf.cl_codigo_nf inner join tb_forma_pagamento as fpg on fpg.cl_id = lcf.cl_forma_pagamento_id 
  where nf.cl_operacao ='VENDA' and  nf.cl_status_venda ='1' and nf.cl_data_movimento between '$data_inicial' and '$data_final'  and lcf.cl_status_id <> 5 group by lcf.cl_forma_pagamento_id ");



    /*funções */
    function consultar_vendas_anual_detalhado($ano, $i)
    {
        global $conecta;
        $valor = consulta_tabela_query($conecta, "SELECT sum(cl_valor_liquido) as total FROM tb_nf_saida 
        where cl_operacao ='VENDA'
         and cl_status_venda ='1' and cl_data_movimento between '$ano-$i-01' and '$ano-$i-31'  ", 'total');
        return array("valor" => $valor);
    }
}
