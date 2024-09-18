<?php
//consultar informações para tabela
if (isset($_GET['consultar_resumo_cobranca'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $consulta = $_GET['consultar_resumo_cobranca'];
    if ($consulta == "inicial") {
        $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1
        $query = "SELECT lcf.*,prc.cl_id as codigo, COUNT(lcf.cl_id) as qtdduplicatas,
        sum(cl_valor_liquido) as valorduplicatas, prc.*,st.*,cd.*, cd.cl_nome as cidade
        FROM tb_lancamento_financeiro AS lcf
        LEFT JOIN tb_parceiros AS prc ON prc.cl_id = lcf.cl_parceiro_id
        inner join tb_estados as st on st.cl_id = prc.cl_estado_id 
        inner join tb_cidades as cd on cd.cl_id = prc.cl_cidade_id
        WHERE lcf.cl_data_vencimento <= NOW()
          AND lcf.cl_tipo_lancamento = 'RECEITA'
          AND lcf.cl_status_id = 1
        GROUP BY lcf.cl_parceiro_id   order by valorduplicatas desc";
        $consultar_resumo_cobranca = mysqli_query($conecta, $query);
        if (!$consultar_resumo_cobranca) {
            die("Falha no banco de dados " . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar_resumo_cobranca); //quantidade de registros
        }
    } else {
        $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro

        $query = "SELECT lcf.*,prc.cl_id as codigo, COUNT(lcf.cl_id) as qtdduplicatas,sum(cl_valor_liquido)
         as valorduplicatas, prc.*,st.*, cd.cl_nome as cidade
        FROM tb_lancamento_financeiro AS lcf
        LEFT JOIN tb_parceiros AS prc ON prc.cl_id = lcf.cl_parceiro_id
        LEFT join tb_estados as st on st.cl_id = prc.cl_estado_id 
        LEFT join tb_cidades as cd on cd.cl_id = prc.cl_cidade_id
        WHERE lcf.cl_data_vencimento <= NOW()
          AND lcf.cl_tipo_lancamento = 'RECEITA'
          AND lcf.cl_status_id = 1
          AND (
            lcf.cl_id LIKE '%$pesquisa%'
            OR prc.cl_razao_social LIKE '%$pesquisa%'
            OR prc.cl_nome_fantasia LIKE '%$pesquisa%'
            OR prc.cl_cnpj_cpf LIKE '%$pesquisa%'
            OR prc.cl_email LIKE '%$pesquisa%'
            OR prc.cl_telefone LIKE '%$pesquisa%'
          )
        GROUP BY lcf.cl_parceiro_id  order by valorduplicatas  desc";
        $consultar_resumo_cobranca = mysqli_query($conecta, $query);
        if (!$consultar_resumo_cobranca) {
            die("Falha no banco de dados " . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar_resumo_cobranca);
        }
    }
}

/*resumo por periodo detalhado */
if (isset($_GET['consultar_resumo_periodo'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $consulta = $_GET['consultar_resumo_periodo'];
    $data_inicial = $_GET['data_inicial'];
    $data_final = $_GET['data_final'];

    if ($consulta == "inicial") {
        $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1

        $query = "SELECT DATEDIFF(CURRENT_DATE(),lcf.cl_data_vencimento) as atraso, 
         lcf.*,prc.cl_id as parceiroid,lcf.cl_id as lancamentoid, prc.*,st.*,cd.*, cd.cl_nome as cidade
        FROM tb_lancamento_financeiro AS lcf
        LEFT JOIN tb_parceiros AS prc ON prc.cl_id = lcf.cl_parceiro_id
        inner join tb_estados as st on st.cl_id = prc.cl_estado_id 
        inner join tb_cidades as cd on cd.cl_id = prc.cl_cidade_id
        WHERE lcf.cl_data_vencimento between '$data_inicial' and '$data_final'
          AND lcf.cl_tipo_lancamento = 'RECEITA'
          AND lcf.cl_status_id = 1 order by prc.cl_id desc";
        $consultar = mysqli_query($conecta, $query);
        if (!$consultar) {
            die("Falha no banco de dados " . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar); //quantidade de registros
        }
    } else {
        $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro

        $query = "SELECT DATEDIFF(CURRENT_DATE(),lcf.cl_data_vencimento) as atraso,  
        lcf.*,prc.cl_id as parceiroid,lcf.cl_id as lancamentoid, prc.*,st.*,cd.*, cd.cl_nome as cidade
        FROM tb_lancamento_financeiro AS lcf
        LEFT JOIN tb_parceiros AS prc ON prc.cl_id = lcf.cl_parceiro_id
        LEFT join tb_estados as st on st.cl_id = prc.cl_estado_id 
        LEFT join tb_cidades as cd on cd.cl_id = prc.cl_cidade_id
         WHERE lcf.cl_data_vencimento between '$data_inicial' and '$data_final'
         AND lcf.cl_tipo_lancamento = 'RECEITA'
          AND lcf.cl_status_id = 1
          AND (
            lcf.cl_id LIKE '%$pesquisa%'
            OR prc.cl_razao_social LIKE '%$pesquisa%'
            OR prc.cl_nome_fantasia LIKE '%$pesquisa%'
            OR prc.cl_cnpj_cpf LIKE '%$pesquisa%'
            OR prc.cl_email LIKE '%$pesquisa%'
            OR prc.cl_telefone LIKE '%$pesquisa%'
          )
            order by prc.cl_id    desc";
        $consultar = mysqli_query($conecta, $query);
        if (!$consultar) {
            die("Falha no banco de dados " . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar);
        }
    }
}

if (isset($_GET['gerar_doc'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    include '../../../../biblioteca/phpqrcode/qrlib.php';
    $parceiro_id = $_GET['parceiro_id'];
    $codigo_nf = isset($_GET['codigo_nf']) ? $_GET['codigo_nf'] : '';
    $tamanho = 2;
    $margem = 3;
    $tipo_ordem = consulta_tabela($conecta, "tb_parametros", "cl_id", 66, "cl_valor"); //1-peças 2 -obra

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
    $email_empresa = ($linha['cl_email']);


    $url_qrdcode = $_SERVER['SERVER_NAME'] . "/$empresa/view/financeiro/resumo_cobranca/documento/resumo_combranca.php?parceiro_id=$parceiro_id";


    // Renderize o QR Code em um buffer de saída
    ob_start();
    QRcode::png($url_qrdcode, null, QR_ECLEVEL_L, $tamanho, $margem);
    $imageData = ob_get_contents();
    ob_end_clean();


    /*dados do cliente */
    $query = "SELECT prc.*,st.cl_uf,cd.cl_nome as cidade from tb_parceiros as prc
    LEFT join tb_estados as st on st.cl_id = prc.cl_estado_id 
    LEFT join tb_cidades as cd on cd.cl_id = prc.cl_cidade_id
    where prc.cl_id = '$parceiro_id' ";
    $consultar_cliente = mysqli_query($conecta, $query);
    $linha = mysqli_fetch_assoc($consultar_cliente);
    $razao_social = utf8_encode($linha['cl_razao_social']);
    $endereco = utf8_encode($linha['cl_endereco']);
    $telefone = $linha['cl_telefone'];
    $cnpj_cpf = $linha['cl_cnpj_cpf'];
    $email = $linha['cl_email'];
    $bairro = utf8_encode($linha['cl_bairro']);
    $estado = $linha['cl_uf'];
    $cidade = utf8_encode($linha['cidade']);


    $query = "SELECT lcf.*, DATEDIFF('$data_lancamento', lcf.cl_data_vencimento ) as atraso
    FROM tb_lancamento_financeiro AS lcf
    WHERE lcf.cl_data_vencimento <= '$data_lancamento'
      AND lcf.cl_tipo_lancamento = 'RECEITA'
      AND lcf.cl_status_id = 1
      AND lcf.cl_parceiro_id = '$parceiro_id' ";
    if ($codigo_nf != '') {
        $query .= "  and lcf.cl_codigo_nf= '$codigo_nf' ";
    }

    $query .= "  order by atraso desc";
    $consultar_resumo_cobranca = mysqli_query($conecta, $query);
    if (!$consultar_resumo_cobranca) {
        die("Falha no banco de dados " . mysqli_error($conecta));
    } else {
        $qtd = mysqli_num_rows($consultar_resumo_cobranca);
    }
}



if (isset($_POST['formulario_resumo_cobranca'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $acao = $_POST['acao'];
    $usuario_id = verifica_sessao_usuario();
    $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");


    if ($acao == "gerar_doc") {
        $parceiro_id = $_POST['parceiro_id'];
        $codigo_nf = isset($_POST['codigo_nf']) ? $_POST['codigo_nf'] : '';

        if ($parceiro_id == "") {
            $retornar["dados"] = array("sucesso" => false, "title" => "Cliente não encontrado! ");
        } else {
            $caminho =  "view/financeiro/resumo_cobranca/documento/resumo_combranca.php?gerar_doc=true&parceiro_id=$parceiro_id&codigo_nf=$codigo_nf";
            $retornar["dados"] = array("sucesso" => true, "title" => $caminho);
        }
    }

    echo json_encode($retornar);
}
