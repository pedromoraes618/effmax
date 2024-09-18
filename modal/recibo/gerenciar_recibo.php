<?php
if (isset($_GET['recibo_quitacao'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";

    /*dados da empresa */
    $select = "SELECT  * from tb_empresa where cl_id ='1' ";
    $consultar_empresa = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consultar_empresa);
    $nome_fantasia_empresa = utf8_encode($linha['cl_nome_fantasia']);
    $empresa = utf8_encode($linha['cl_empresa']);
    $cnpj_empresa  = ($linha['cl_cnpj']);
    $endereco_empresa = utf8_encode($linha['cl_endereco']);
    $numero_empresa = ($linha['cl_numero']);
    $cep_empresa = ($linha['cl_cep']);
    $telefone_empresa = ($linha['cl_telefone']);
    $cidade_empresa =  utf8_encode($linha['cl_cidade']);
    $estado_empresa = ($linha['cl_estado']);
    $acao = $_GET['acao'];

    if ($acao == "venda") {
        $codigo_nf = $_GET['codigo_nf'];

        $query = "SELECT nf.*,prc.*  from tb_nf_saida as nf 
   left join tb_parceiros as prc on prc.cl_id = nf.cl_parceiro_id
    where cl_codigo_nf ='$codigo_nf' ";
        $consulta = mysqli_query($conecta, $query);
        if (!$consulta) {
            die("Falha no banco de dados " . mysqli_error($conecta));
        } else {

            $linha = mysqli_fetch_assoc($consulta);
            $parceiro_id = utf8_encode($linha['cl_parceiro_id']);
            $razao_social = utf8_encode($linha['cl_razao_social']);
            $parceiro_avulso = utf8_encode($linha['cl_parceiro_avulso']);
            $valor_liquido = ($linha['cl_valor_liquido']);
            $numero_nf = ($linha['cl_numero_nf']);
            $data_recebimento = ($linha['cl_data_recebimento']);
            if (empty($parceiro_avulso)) {
                $parceiro = $razao_social;
            } else {
                $parceiro = $parceiro_avulso;
            }
            if(empty($valor_liquido)){
                $valor_liquido = 0;
            }
            $mensagem = "<p>Recebemos de <strong>" . $parceiro . "</strong> a importância de
            <strong>" . real_format($valor_liquido) . " (" . valorPorExtenso($valor_liquido) . ")</strong>, referente à venda de mercadoria conforme 
            Nota Nº <strong>" . $numero_nf . "</strong>, na data de <strong>" . formatDateB($data_recebimento) . "</strong>. Firmamos o presente recibo, dando total quitação.</p>";

            // $url_qrdcode = "http://effmax.com.br/$empresa/view/venda/venda_mercadoria/recibo/recibo_nf.php?recibo=true&codigo_nf=$codigo_nf&serie_nf=$serie_nf";
        }
    } elseif ($acao == "os_taxa") {
        $form_id = $_GET['os_id'];
        $codigo_nf = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, "cl_codigo_nf");
        $numero_nf = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, "cl_numero_nf");


        $valor_pago = consulta_tabela_query($conecta, "SELECT sum(cl_valor_liquido) as total FROM 
   tb_lancamento_financeiro where cl_codigo_nf ='$codigo_nf' and cl_status_id = '2' and cl_tipo_lancamento='RECEITA' 
   and cl_documento = 'TAXA'", 'total');

        $query = "SELECT lcf.*,fpg.cl_descricao as fpagamento,sts.cl_descricao as statusl,sts.cl_id as idstatusl, prc.*,os.*,os.cl_numero_nf as numeroos  FROM 
   tb_lancamento_financeiro as lcf
    left join tb_forma_pagamento as fpg on lcf.cl_forma_pagamento_id = fpg.cl_id 
    left join tb_status_recebimento as sts on sts.cl_id = lcf.cl_status_id
    left join tb_parceiros as prc on prc.cl_id = lcf.cl_parceiro_id
    left join tb_os as os on os.cl_codigo_nf = lcf.cl_codigo_nf
   where lcf.cl_codigo_nf ='$codigo_nf' and lcf.cl_status_id = '2' and lcf.cl_tipo_lancamento='RECEITA' ";
        $consulta = mysqli_query($conecta, $query);
        if (!$consulta) {
            die("Falha no banco de dados " . mysqli_error($conecta));
        } else {
            $linha = mysqli_fetch_assoc($consulta);
            $razao_social = utf8_encode($linha['cl_razao_social']);
            $equipamento = utf8_encode($linha['cl_equipamento']);
            $numero_nf = utf8_encode($linha['numeroos']);
            if(empty($valor_pago)){
                $valor_pago = 0;
            }

            $mensagem = "<p>Recebemos o valor de <strong>" . real_format($valor_pago) . " (" . valorPorExtenso($valor_pago) . ")</strong> referente à taxa de diagnóstico d(a)(o)(e) 
            <strong>$equipamento</strong>, conforme ordem de serviço nº <strong>$numero_nf</strong>. 
            Confirmamos o recebimento do valor em questão na data de <strong>" . formatDateB($data_lancamento) . "
            </strong>, realizado por <strong>$razao_social</strong></p><p>Declaramos que a máquina foi devidamente inspecionada e diagnosticada, e que todas as obrigações relacionadas a este serviço foram cumpridas. Emitimos este recibo, dando total quitação do valor mencionado.</p>";


            $qtd = mysqli_num_rows($consulta); //quantidade de registros
        }
    } elseif ($acao == "os_adiantamento") {
        $form_id = $_GET['os_id'];
        $codigo_nf = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, "cl_codigo_nf");
        $numero_nf = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, "cl_numero_nf");

        $valor_pago = consulta_tabela_query($conecta, "SELECT sum(cl_valor_liquido) as total FROM 
   tb_lancamento_financeiro where cl_codigo_nf ='$codigo_nf' and cl_status_id = '2' and cl_tipo_lancamento='RECEITA' 
   and cl_documento = 'ADIANTAMENTO'", 'total');

        $query = "SELECT lcf.*,fpg.cl_descricao as fpagamento,sts.cl_descricao as statusl,sts.cl_id as idstatusl, prc.*,os.*,os.cl_numero_nf as numeroos  FROM 
   tb_lancamento_financeiro as lcf
    left join tb_forma_pagamento as fpg on lcf.cl_forma_pagamento_id = fpg.cl_id 
    left join tb_status_recebimento as sts on sts.cl_id = lcf.cl_status_id
    left join tb_parceiros as prc on prc.cl_id = lcf.cl_parceiro_id
    left join tb_os as os on os.cl_codigo_nf = lcf.cl_codigo_nf
   where lcf.cl_codigo_nf ='$codigo_nf' and lcf.cl_status_id = '2' and lcf.cl_tipo_lancamento='RECEITA' ";
        $consulta = mysqli_query($conecta, $query);
        if (!$consulta) {
            die("Falha no banco de dados " . mysqli_error($conecta));
        } else {
            $linha = mysqli_fetch_assoc($consulta);
            $razao_social = utf8_encode($linha['cl_razao_social']);
            $equipamento = utf8_encode($linha['cl_equipamento']);
            $numero_nf = utf8_encode($linha['numeroos']);

            $mensagem = "<p>Recebemos o valor de <strong>" . real_format($valor_pago) . " (" . valorPorExtenso($valor_pago) . ")</strong> referente ao adiantamento d(a)(o)(e) 
            <strong>$equipamento</strong>, conforme ordem de serviço nº <strong>$numero_nf</strong>. 
            Confirmamos o recebimento do valor em questão na data de <strong>" . formatDateB($data_lancamento) . "
            </strong>, realizado por <strong>$razao_social</strong></p><p>Declaramos que a máquina foi devidamente inspecionada e diagnosticada, e que todas as obrigações relacionadas a este serviço foram cumpridas. Emitimos este recibo, dando total quitação do valor mencionado.</p>";


            $qtd = mysqli_num_rows($consulta); //quantidade de registros
        }
    }
}
