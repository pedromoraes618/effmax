<?php
if (isset($_POST['dashboard_inicial'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $usuario = $_POST['usuario'];
    $usuario_id = verifica_sessao_usuario();


    $verifica_periodo = $_POST['verifica_periodo'];
    if ($verifica_periodo != "consultar") {
        $update = "UPDATE tb_users SET cl_periodo_dashboard = '$verifica_periodo' WHERE cl_id = '$usuario_id'";
        $update_usuario = mysqli_query($conecta, $update);
    }

    $select = "SELECT * from tb_users where cl_id = '$usuario_id'";
    $consultar_usuario = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consultar_usuario);
    $periodo = $linha['cl_periodo_dashboard'];
    $area = $linha['cl_usuario_area'];


    $retornar["dados"] = array("sucesso" => true, "periodo" => $periodo, "area" => $area);

    echo json_encode($retornar);
}


//consultar informações para tabela
if (isset($_GET['dashboard_inicial'])) {
    include "../../../../../conexao/conexao.php";
    include "../../../../../funcao/funcao.php";
    $ano = date('Y');

    if (isset($_GET['periodo'])) {
        $periodo = $_GET['periodo'];
    }
    if (isset($_GET['usuario'])) {
        $usuario = $_GET['usuario'];
    }
    $usuario_id = verifica_sessao_usuario(); //pegar o id do usuazrio na sessao

    $consultar_sistema_delivery =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "42"); //VERIFICAR PARAMETRO ID - 42 // verificar se o sistema é para delivery
    $consultar_contabilizacao_caixa =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "6"); //VERIFICAR PARAMETRO ID - 6 // verificar se periodo do caixa vai ser contabilizado por dia ou mês
    $dias_alerta_validade =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "20"); //VERIFICAR PARAMETRO ID - 6 // verificar se periodo do caixa vai ser contabilizado por dia ou mês
    if ($dias_alerta_validade == "") {
        $dias_alerta_validade = 0;
    }
    // Divide a data em partes
    $partes = explode('-', $data_lancamento);

    // Extrai o ano, o mês e o dia
    $ano = $partes[0];

    $mes = $partes[1];
    $dia = $partes[2];

    $select_receita_caixa = "SELECT sum(cl_valor_liquido) as valor from tb_lancamento_financeiro as lcf inner join tb_forma_pagamento as fpg on fpg.cl_id = 
    lcf.cl_forma_pagamento_id where lcf.cl_status_id ='2'  "; //receita recbdo

    $select_despesa_caixa = "SELECT sum(cl_valor_liquido) as valor from tb_lancamento_financeiro as lcf inner join tb_forma_pagamento as fpg on fpg.cl_id = 
lcf.cl_forma_pagamento_id where lcf.cl_status_id ='4'  "; //receita recbdo

    $select_prod_validade = "SELECT * FROM tb_produtos
    WHERE DATEDIFF(cl_data_validade, CURDATE()) <= $dias_alerta_validade and cl_status_ativo ='SIM' and (cl_data_validade !='' or cl_data_validade!='0000-00-00') "; //receita recbdo
    $consultar_validade_prod = mysqli_query($conecta, $select_prod_validade); //valor da conta financeira caixa no financeiro receita
    $qtd_consultar_validade_prd  = mysqli_num_rows($consultar_validade_prod);


    $select_caixa = "SELECT 
    SUM(CASE WHEN cl_status_id = '2' THEN cl_valor_liquido ELSE 0 END) AS entrada,
    SUM(CASE WHEN cl_status_id = '4' THEN cl_valor_liquido ELSE 0 END) AS saida,
    SUM(CASE WHEN cl_status_id = '2' THEN cl_valor_liquido ELSE 0 END) - SUM(CASE WHEN cl_status_id = '4'
     THEN cl_valor_liquido ELSE 0 END) as saldo_inicial
FROM 
    tb_lancamento_financeiro 
WHERE 
    cl_data_pagamento < '$data_lancamento' 
    AND cl_conta_financeira = 'CAIXA'  "; //total de entrada para saldo inicial
    $consulta_valor_caixa = mysqli_query($conecta, $select_caixa); //verifica o valor inicial do caixa 
    $qtd_consulta_valor_caixa = mysqli_num_rows($consulta_valor_caixa);
    if (!$consulta_valor_caixa) {
        die("Erro na consulta:" . mysqli_error($conecta));
    } else {
        $linha = mysqli_fetch_assoc($consulta_valor_caixa);
        $valor_fechado = $linha['saldo_inicial']; //saldo inicial
    }


    $select = "SELECT * FROM tb_caixa where cl_ano !='' and cl_conta ='CAIXA' ";
    if ($consultar_contabilizacao_caixa == "DIA") {
        $select .= " and cl_dia = '$dia' and cl_mes ='$mes' and cl_ano='$ano' "; // se for por periodo de contabilização em dia a dia vai verifiar o dia, o mes e o ano
        $select_receita_caixa .= " and cl_data_pagamento between '$ano-$mes-$dia' and '$ano-$mes-$dia' ";
        $select_despesa_caixa .= " and cl_data_pagamento between '$ano-$mes-$dia' and '$ano-$mes-$dia' ";
    } elseif ($consultar_contabilizacao_caixa == "MES") {
        //  $select .= " and cl_mes = '$mes' and cl_ano ='$ano'"; // se for por periodo de contabilização em mes a mes vai verifiar o mes e o ano
        $select_receita_caixa .= " and cl_data_pagamento between '$ano-$mes-01' and '$ano-$mes-31' ";
        $select_despesa_caixa .= " and cl_data_pagamento between '$ano-$mes-01' and '$ano-$mes-31' ";
    } else {
        $select .= " and cl_dia = '$dia' and cl_mes ='$mes' and cl_ano='$ano' "; // se o paramentro estivir com valor incorreto será atribuido o periodo de dia a dia
        $select_receita_caixa .= " and cl_data_pagamento between '$ano-$mes-$dia' and '$ano-$mes-$dia' ";
        $select_despesa_caixa .= " and cl_data_pagamento between '$ano-$mes-$dia' and '$ano-$mes-$dia' ";
    }


    $consulta_caixa = mysqli_query($conecta, $select); //verificar se o caixa está aberto
    if ($consulta_caixa) {
        $resultado_consulta = mysqli_num_rows($consulta_caixa);
        $linha = mysqli_fetch_assoc($consulta_caixa);
        $status = $linha['cl_status'];
        // $valor_fechado = $linha['cl_valor_abertura'];
    }



    $select_receita_caixa .= " and fpg.cl_conta_financeira = 'CAIXA' "; //valor da conta financeira caixa no financeiro receita
    $select_despesa_caixa .= " and fpg.cl_conta_financeira = 'CAIXA' "; //valor da conta financeira caixa no financeiro despesa

    $consulta_valor_caixa_financeiro = mysqli_query($conecta, $select_receita_caixa); //valor da conta financeira caixa no financeiro receita
    $linha = mysqli_fetch_assoc($consulta_valor_caixa_financeiro);
    $valor_receita_caixa_financeiro = $linha['valor'];

    $consulta_despesa_caixa_financeiro = mysqli_query($conecta, $select_despesa_caixa); //valor da conta financeira caixa no financeiro despesa
    $linha = mysqli_fetch_assoc($consulta_despesa_caixa_financeiro);
    $valor_despesa_caixa_financeiro = $linha['valor'];



    $valor_caixa_total = $valor_fechado + $valor_receita_caixa_financeiro - $valor_despesa_caixa_financeiro; //saldo inicial mais o atual


    $select_receita = "SELECT sum(cl_valor_liquido) as valor from tb_lancamento_financeiro where";

    $select_despesa = "SELECT sum(cl_valor_liquido) as valor from tb_lancamento_financeiro where";

    $select_a_receber = "SELECT DATEDIFF(CURRENT_DATE(),lcf.cl_data_vencimento) as atraso,  lcf.cl_data_vencimento,prc.cl_razao_social,lcf.cl_valor_liquido from tb_lancamento_financeiro as 
    lcf inner join tb_parceiros as prc on prc.cl_id = lcf.cl_parceiro_id where";

    $select_a_pagar = "SELECT DATEDIFF(CURRENT_DATE(),lcf.cl_data_vencimento) as atraso, lcf.cl_data_vencimento,prc.cl_razao_social,lcf.cl_valor_liquido from tb_lancamento_financeiro as 
    lcf inner join tb_parceiros as prc on prc.cl_id = lcf.cl_parceiro_id where";

    $select_lembretes = "SELECT trf.cl_id, userord.cl_usuario as usuarioordem, trf.cl_data_lancamento,trf.cl_descricao,trf.cl_comentario,user.cl_usuario 
    as usuario_func,trf.cl_prioridade,trf.cl_data_limite,strf.cl_descricao as status from tb_tarefas as trf inner join tb_users as user on user.cl_id = trf.cl_usuario_func inner join tb_users as userord on userord.cl_id = trf.cl_usuario
    inner join tb_status_tarefas as strf on strf.cl_id = trf.cl_status where user.cl_id   = '$usuario_id' and (trf.cl_status ='1' or trf.cl_status='2') ";

    $select_desempenho_equipe = " SELECT user.cl_usuario  as vendedor ,sum(cl_valor_liquido) as valor,count(*) as vendas  from tb_nf_saida as nf inner join tb_users as user on user.cl_id = nf.cl_vendedor_id 
    WHERE cl_operacao='VENDA' and cl_status_venda ='1' "; //venda finalizada

    $select_faturamento_em_venda = " SELECT sum(cl_valor_liquido) as valor from tb_nf_saida as nf inner join tb_users as user on user.cl_id = nf.cl_vendedor_id 
    WHERE cl_operacao='VENDA' and cl_status_venda ='1' "; //venda finalizada


    $select_produtos_mais_vendidos = "SELECT prd.*, SUM(cl_quantidade) as total_vendido, sum(cl_valor_venda) AS valor_total FROM tb_ajuste_estoque as nf
    inner join tb_produtos as prd on prd.cl_id = nf.cl_produto_id where nf.cl_ajuste_inicial ='0' and nf.cl_tipo='SAIDA' AND nf.cl_status ='OK' and nf.cl_ajuste='0'   ";

    $select_minhas_vendas = "SELECT * FROM tb_nf_saida
where cl_status_venda ='1' and cl_vendedor_id ='$usuario_id' and cl_operacao ='VENDA' ";


    $select_valor_minhas_vendas = "SELECT sum(cl_valor_liquido) as vlrMinhasVendas, count(cl_id) as qtdVendas FROM tb_nf_saida
where cl_status_venda ='1' and cl_vendedor_id ='$usuario_id'  ";

    $select_total_de_vendas = "SELECT sum(cl_valor_liquido) as valortotalvenda, count(*) as qtdvendas FROM tb_nf_saida
where cl_status_venda ='1'  ";

    $select_vendas_a_receber = "SELECT * FROM tb_nf_saida
 where cl_status_venda ='1' and cl_status_recebimento ='1'  ";

    $select_perguntas_clientes = "SELECT  prd.*, duv.*,user.* FROM tb_duvida_loja as duv 
    left join tb_user_loja as user on user.cl_id = duv.cl_usuario_id
   left join tb_produtos as prd on prd.cl_id = duv.cl_produto_id
    where duv.cl_origem_mensagem ='0' and cl_respondido = 0 ";

    if ($consultar_sistema_delivery == "S") {
        $select_produtos_mais_vendidos .= " and (prd.cl_tipo_id ='2' or prd.cl_tipo_id ='6') ";
    }

    if ($periodo == "DIA") {
        $select_receita .= " cl_data_pagamento between '$data_dia_bd' and '$data_dia_bd' and cl_status_id ='2' "; //receita recebido
        $select_despesa .= " cl_data_pagamento between '$data_dia_bd' and '$data_dia_bd' and cl_status_id ='4' "; //despesa pago
        $select_a_receber .= " lcf.cl_data_vencimento between '$data_dia_bd' and '$data_dia_bd' and lcf.cl_status_id ='1' order by lcf.cl_data_vencimento desc"; //contas a receber
        $select_a_pagar .= " lcf.cl_data_vencimento between '$data_dia_bd' and '$data_dia_bd' and lcf.cl_status_id ='3'order by lcf.cl_data_vencimento  desc"; //contas a pagar
        $select_lembretes .= " and  trf.cl_data_lancamento between '$data_dia_bd' and '$data_dia_bd'"; //lembretes
        $select_desempenho_equipe .= " and cl_data_movimento between '$data_dia_bd' and '$data_dia_bd' "; //desempenho equipe
        $select_faturamento_em_venda .= " and cl_data_movimento between '$data_dia_bd' and '$data_dia_bd' "; //faturamento total em venda
        $select_produtos_mais_vendidos .= " and cl_data_lancamento between '$data_dia_bd' and '$data_dia_bd' "; //produtos mais vendidos

        $select_minhas_vendas .= " and cl_data_movimento between '$data_dia_bd' and '$data_dia_bd' ";
        $select_valor_minhas_vendas .= " and cl_data_movimento between '$data_dia_bd' and '$data_dia_bd' "; //soma das vendas feita pelo vendedor
        $select_total_de_vendas .= " and cl_data_movimento between '$data_dia_bd' and '$data_dia_bd' "; //total de vendas
        $select_vendas_a_receber .= " and cl_data_movimento between '$data_dia_bd' and '$data_dia_bd' "; //vendas a receber

        $select_perguntas_clientes .= " and duv.cl_data between '$data_dia_bd 01:01:01' and '$data_dia_bd 23:59:59' ";
    } elseif ($periodo == "MES") {

        $select_receita .= " cl_data_pagamento between '$data_inicial_mes_bd' and '$data_final_mes_bd' and cl_status_id ='2' "; //receita
        $select_despesa .= " cl_data_pagamento between '$data_inicial_mes_bd' and '$data_final_mes_bd' and cl_status_id ='4' "; //despesa
        $select_a_receber .= " lcf.cl_data_vencimento between '$data_inicial_mes_bd' and '$data_final_mes_bd' and lcf.cl_status_id ='1' order by lcf.cl_data_vencimento desc"; //contas a receber
        $select_a_pagar .= " lcf.cl_data_vencimento between '$data_inicial_mes_bd' and '$data_final_mes_bd' and lcf.cl_status_id ='3'order by lcf.cl_data_vencimento desc "; //contas a pagar
        $select_lembretes .= " and  trf.cl_data_lancamento between '$data_inicial_mes_bd' and '$data_final_mes_bd'"; //lembretes
        $select_desempenho_equipe .= " and cl_data_movimento between '$data_inicial_mes_bd' and '$data_final_mes_bd' "; //desempenho equipe
        $select_faturamento_em_venda .= " and cl_data_movimento between '$data_inicial_mes_bd' and '$data_final_mes_bd' "; //faturamento total em venda
        $select_produtos_mais_vendidos .= " and cl_data_lancamento between '$data_inicial_mes_bd' and '$data_final_mes_bd' "; //produtos mais vendidos
        $select_minhas_vendas .= " and cl_data_movimento between '$data_inicial_mes_bd' and '$data_final_mes_bd' ";
        $select_valor_minhas_vendas .= " and cl_data_movimento between '$data_inicial_mes_bd' and '$data_final_mes_bd' "; //soma das vendas feita pelo vendedor
        $select_total_de_vendas .= " and cl_data_movimento between '$data_inicial_mes_bd' and '$data_final_mes_bd' "; //total de vendas
        $select_vendas_a_receber .= " and cl_data_movimento between '$data_inicial_mes_bd' and '$data_final_mes_bd' ";
        $select_perguntas_clientes .= " and duv.cl_data  between '$data_inicial_mes_bd 01:01:01' and '$data_final_mes_bd 23:59:59' ";
    } else {

        $select_receita .= " cl_data_pagamento between '$data_inicial_ano_bd' and '$data_final_ano_bd' and cl_status_id ='2' "; //receita
        $select_despesa .= " cl_data_pagamento between '$data_inicial_ano_bd' and '$data_final_ano_bd' and cl_status_id ='4' "; //despesa
        $select_a_receber .= " lcf.cl_data_vencimento between '$data_inicial_ano_bd' and '$data_final_ano_bd' and lcf.cl_status_id ='1' order by lcf.cl_data_vencimento desc "; //contas a receber
        $select_a_pagar .= " lcf.cl_data_vencimento between '$data_inicial_ano_bd' and '$data_final_ano_bd' and lcf.cl_status_id ='3'order by lcf.cl_data_vencimento desc"; //contas a pagar
        $select_lembretes .= " and  trf.cl_data_lancamento between '$data_inicial_ano_bd' and '$data_final_ano_bd'"; //lembretes
        $select_desempenho_equipe .= " and cl_data_movimento  between '$data_inicial_ano_bd' and '$data_final_ano_bd' "; //desempenho equipe
        $select_faturamento_em_venda .= " and cl_data_movimento  between '$data_inicial_ano_bd' and '$data_final_ano_bd' "; //faturamento total em venda
        $select_produtos_mais_vendidos .= " and cl_data_lancamento between '$data_inicial_ano_bd' and '$data_final_ano_bd' "; //produtos mais vendidos

        $select_minhas_vendas .= " and cl_data_movimento between '$data_inicial_ano_bd' and '$data_final_ano_bd' ";
        $select_valor_minhas_vendas .= " and cl_data_movimento between '$data_inicial_ano_bd' and '$data_final_ano_bd' "; //oma das vendas feita pelo vendedor
        $select_total_de_vendas .= " and cl_data_movimento between '$data_inicial_ano_bd' and '$data_final_ano_bd' "; //total de vendas
        $select_vendas_a_receber .= " and cl_data_movimento between '$data_inicial_ano_bd' and '$data_final_ano_bd' ";
        $select_perguntas_clientes .= " and duv.cl_data  between '$data_inicial_ano_bd 01:01:01' and '$data_final_ano_bd 23:59:59' ";
    }
    $select_perguntas_clientes .= " order by duv.cl_id asc ";
    $consultar_perguntas_clientes = mysqli_query($conecta, $select_perguntas_clientes); //perguntas dos cliente que foram feita no ecommerce
    $qtd_perguntas_clientes  = mysqli_num_rows($consultar_perguntas_clientes);



    $select_produtos_mais_vendidos .= "  GROUP BY cl_produto_id ORDER BY total_vendido DESC LIMIT 5 ";
    if (verficar_paramentro($conecta, "tb_parametros", "cl_id", "12") == "QUANTIDADE") {
        $select_desempenho_equipe .= " group by cl_vendedor_id order by vendas desc ";
    } else {
        $select_desempenho_equipe .= " group by cl_vendedor_id order by valor desc ";
    }

    $select_despesa .= " and cl_classificacao_id !='6'"; //despesa

    $consultar_prod_mais_vendidos = mysqli_query($conecta, $select_produtos_mais_vendidos); //produtos mais vendidos

    $qtd_prod_mais_vendidos = mysqli_num_rows($consultar_prod_mais_vendidos);

    $consultar_receita_total = mysqli_query($conecta, $select_receita); //receita para o card //container center 
    $linha = mysqli_fetch_assoc($consultar_receita_total);
    $receita_total = ($linha['valor']);

    $consultar_despesa_total = mysqli_query($conecta, $select_despesa); //receita para o card //container center 
    $linha = mysqli_fetch_assoc($consultar_despesa_total);
    $despesa_total = ($linha['valor']);

    $consultar_contas_a_receber = mysqli_query($conecta, $select_a_receber); //contas a receber //container center 
    $qtd_consultar_contas_a_receber = mysqli_num_rows($consultar_contas_a_receber);

    $consultar_contas_a_pagar = mysqli_query($conecta, $select_a_pagar); //contas a pagar //container center 
    $qtd_consultar_contas_a_pagar = mysqli_num_rows($consultar_contas_a_pagar);

    $consultar_lembretes = mysqli_query($conecta, $select_lembretes); //consultar tarefas
    $qtd_consultar_lembretes = mysqli_num_rows($consultar_lembretes);

    $consultar_desemepenho_equipe = mysqli_query($conecta, $select_desempenho_equipe); //consultar desempenho da equipe para dashboard
    $qtd_desempenho_equipe = mysqli_num_rows($consultar_desemepenho_equipe);


    $consultar_faturamento_venda = mysqli_query($conecta, $select_faturamento_em_venda); //faturamento total em venda por periodo
    $linha = mysqli_fetch_assoc($consultar_faturamento_venda);
    $valor_total_venda = ($linha['valor']);

    $consultar_valor_minhas_vendas = mysqli_query($conecta, $select_valor_minhas_vendas); //total de vendas do vendedor



    if (!$consultar_valor_minhas_vendas) {
        die("Erro na consulta " . mysqli_error($conecta));
    } else {
        $linha = mysqli_fetch_assoc($consultar_valor_minhas_vendas);
        $valor_total_minhas_venda = ($linha['vlrMinhasVendas']);
        $qtd_total_minhas_vendas = ($linha['qtdVendas']);
    }

    $consultar_total_vendas = mysqli_query($conecta, $select_total_de_vendas); //consultar tota de vendas
    if (!$consultar_total_vendas) {
        die("Erro na consulta " . mysqli_error($conecta));
    } else {
        $linha = mysqli_fetch_assoc($consultar_total_vendas);
        $valor_total_vendas = ($linha['valortotalvenda']);
        $qtd_total_vendas = ($linha['qtdvendas']);
    }


    $consultar_vendas_a_receber = mysqli_query($conecta, $select_vendas_a_receber); //vendas a receber
    if (!$consultar_vendas_a_receber) {
        die("Erro na consulta " . mysqli_error($conecta));
    }


    function calcularMediaVendas($suasVendas, $totalVendas)
    {
        if ($totalVendas > 0) {
            $media = ($suasVendas / $totalVendas) * 100;
            return number_format($media, 2);
        } else {
            return 0; // Evitar divisão por zero
        }
    }

    $consultar_minhas_vendas = mysqli_query($conecta, $select_minhas_vendas); //total de vendas do vendedor
    if (!$consultar_minhas_vendas) {
        die("Erro na consulta " . mysqli_error($conecta));
    }

    function consultar_receita_anual_detalhado($i, $ano)
    {
        global $conecta;
        $select = "SELECT sum(cl_valor_liquido) as valor from tb_lancamento_financeiro where cl_data_pagamento between '$ano-$i-01' and '$ano-$i-31' and cl_status_id ='2'";
        $consulta_valores_receita_array = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consulta_valores_receita_array);
        $valor_total  = $linha['valor'];
        return $valor_total;
    }


    function consultar_receita_anual_anterior_detalhado($i, $ano)
    {
        global $conecta;
        $ano = $ano - 1;
        $select = "SELECT sum(cl_valor_liquido) as valor from tb_lancamento_financeiro where cl_data_pagamento between '$ano-$i-01' and '$ano-$i-31' and cl_status_id ='2'";
        $consulta_valores_receita_array = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consulta_valores_receita_array);
        $valor_total  = $linha['valor'];
        return $valor_total;
    }

    function consultar_despesa_anual_detalhado($i, $ano)
    {
        global $conecta;
        $select = "SELECT sum(cl_valor_liquido) as valor from tb_lancamento_financeiro where cl_data_pagamento between '$ano-$i-01' and '$ano-$i-31'
         and cl_status_id ='4' and cl_classificacao_id !='6' ";
        $consulta_valores_receita_array = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consulta_valores_receita_array);
        $valor_total  = $linha['valor'];
        return $valor_total;
    }
    function consultar_despesa_anual_anterior_detalhado($i, $ano)
    {

        global $conecta;
        $ano = $ano - 1;
        $select = "SELECT sum(cl_valor_liquido) as valor from tb_lancamento_financeiro where cl_data_pagamento between '$ano-$i-01' and '$ano-$i-31' and cl_status_id ='4' and cl_classificacao_id !='6' ";
        $consulta_valores_receita_array = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consulta_valores_receita_array);
        $valor_total  = $linha['valor'];
        return $valor_total;
    }

    function consulta_quantidade_venda_anual($i, $ano)
    {
        global $conecta;
        $select = " SELECT COUNT(*) AS qtdv FROM tb_nf_saida 
        WHERE cl_data_movimento between '$ano-$i-01' and '$ano-$i-31' 
        and cl_status_venda = '1' and cl_operacao ='VENDA' ";
        $consulta_qtd_vendas_array = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consulta_qtd_vendas_array);
        $qtd_total = $linha['qtdv'];
        return $qtd_total;
    }
    function consulta_valor_venda_anual($i, $ano)
    {
        global $conecta;
        $select = " SELECT sum(cl_valor_liquido) AS total FROM tb_nf_saida WHERE cl_data_movimento between '$ano-$i-01' and '$ano-$i-31' 
        and cl_status_venda = '1'  and  cl_operacao ='VENDA'  ";
        $consulta_valor_vendas_array = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consulta_valor_vendas_array);
        $total = $linha['total'];
        return $total;
    }

    function consulta_valor_total_minhas_vendas($conecta, $i, $ano, $usuario_id)
    {

        $select = " SELECT  sum(cl_valor_liquido) as vlrMinhasVendas FROM tb_nf_saida WHERE cl_vendedor_id = '$usuario_id' and
         cl_data_movimento between '$ano-$i-01' and '$ano-$i-31' and cl_status_venda = '1' and  cl_operacao ='VENDA' ";
        $consulta_qtd_vendas_array = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consulta_qtd_vendas_array);
        $valor_total_minhas_vendas = $linha['vlrMinhasVendas'];
        return $valor_total_minhas_vendas;
    }

    function consulta_qtd_total_minhas_vendas($conecta, $i, $ano, $usuario_id)
    {

        $select = " SELECT  count(*) as qtdMinhasVendas FROM tb_nf_saida WHERE cl_vendedor_id = '$usuario_id' and
         cl_data_movimento between '$ano-$i-01' and '$ano-$i-31' and cl_status_venda = '1' and  cl_operacao ='VENDA'  ";
        $consulta_qtd_vendas_array = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consulta_qtd_vendas_array);
        $qtd_minhas_vendas = $linha['qtdMinhasVendas'];
        return $qtd_minhas_vendas;
    }
}
