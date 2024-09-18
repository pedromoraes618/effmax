<?php





//consultar informações para tabela devolucao
if (isset($_GET['consultar_gestao_servico'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $consulta = $_GET['consultar_gestao_servico'];
    $data_inicial = $_GET['data_inicial'];
    $data_final = $_GET['data_final'];
    $data_inicial = ($data_inicial . ' 01:01:01');
    $data_final = ($data_final . ' 23:59:59');

    $usuario_id = verifica_sessao_usuario();


    if ($consulta == "inicial") {
        $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1
        $select = "SELECT resp.cl_nome as responsavel, os.*,prc.cl_razao_social,prc.cl_nome_fantasia,user.cl_usuario,tpo.cl_descricao as 
        tiposervico,status.cl_descricao as statusos from tb_os as os 
       left join tb_parceiros as prc on prc.cl_id = os.cl_parceiro_id 
       left join tb_users as user on user.cl_id = os.cl_atendente_id 
       left join tb_tipo_servico_os as tpo on tpo.cl_id = os.cl_tipo_servico_id
       left join tb_status_os as status on status.cl_id = os.cl_status_id
       left join tb_infraestrutura_os as infra on infra.cl_codigo_nf = os.cl_codigo_nf
       left join tb_users as resp on resp.cl_id = infra.cl_responsavel
        WHERE os.cl_data_abertura between '$data_inicial' and '$data_final'
         and infra.cl_responsavel = '$usuario_id' group by os.cl_codigo_nf
          order by os.cl_data_abertura desc  ";
        $consultar_gestao_servico = mysqli_query($conecta, $select);
        if (!$consultar_gestao_servico) {
            die("Falha no banco de dados " . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar_gestao_servico); //quantidade de registros
        }
    } else {

        $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
        $status_ordem = isset($_GET['status_ordem']) ? $_GET['status_ordem'] : '';

        $select = "SELECT resp.cl_nome as responsavel, os.*,prc.cl_razao_social,prc.cl_nome_fantasia,user.cl_usuario,tpo.cl_descricao as tiposervico,status.cl_descricao as statusos  from tb_os as os 
       left join tb_parceiros as prc on prc.cl_id = os.cl_parceiro_id left join tb_users as user on user.cl_id = os.cl_atendente_id
       left join tb_tipo_servico_os as tpo on tpo.cl_id = os.cl_tipo_servico_id left join tb_status_os as status on status.cl_id = os.cl_status_id
       left join tb_infraestrutura_os as infra on infra.cl_codigo_nf = os.cl_codigo_nf
       left join tb_users as resp on resp.cl_id = infra.cl_responsavel

        WHERE os.cl_data_abertura between '$data_inicial' and '$data_final' and ( prc.cl_razao_social  like '%$pesquisa%'
       or prc.cl_nome_fantasia  like '%$pesquisa%' or prc.cl_cnpj_cpf like '%$pesquisa%'
         or os.cl_numero_serie like '%$pesquisa%' or os.cl_numero_nf like '%$pesquisa%' or os.cl_equipamento like '%$pesquisa%' ) and infra.cl_responsavel = '$usuario_id'   ";

        if ($status_ordem != "0" and $status_ordem != "") {
            $select .= " and os.cl_status_id = '$status_ordem' ";
        }

        $select .= " group by os.cl_codigo_nf  order by os.cl_data_abertura desc ";
        $consultar_gestao_servico = mysqli_query($conecta, $select);
        if (!$consultar_gestao_servico) {
            die("Falha no banco de dados " . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar_gestao_servico);
        }
    }
}


//consultar informações para tabela devolucao
if (isset($_GET['consultar_diagnostico_tecnico'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $consulta = $_GET['consultar_diagnostico_tecnico'];
    $data_inicial = $_GET['data_inicial'];
    $data_final = $_GET['data_final'];
    $data_inicial = ($data_inicial . ' 01:01:01');
    $data_final = ($data_final . ' 23:59:59');


    if ($consulta == "inicial") {
        $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1
        $select = "SELECT os.*,tecnico.cl_usuario as tecnico,prc.cl_razao_social,prc.cl_nome_fantasia,user.cl_usuario,tpo.cl_descricao as tiposervico,status.cl_descricao as statusos from tb_os as os 
       inner join tb_parceiros as prc on prc.cl_id = os.cl_parceiro_id 
       inner join tb_users as user on user.cl_id = os.cl_atendente_id 
       left join tb_users as tecnico  on tecnico.cl_id = os.cl_tecnico_id
       inner join tb_tipo_servico_os as tpo on tpo.cl_id = os.cl_tipo_servico_id inner join tb_status_os as status on status.cl_id = os.cl_status_id
        WHERE os.cl_data_abertura between '$data_inicial' and '$data_final'  order by os.cl_data_abertura desc ";
        $consultar_ordem_servico = mysqli_query($conecta, $select);
        if (!$consultar_ordem_servico) {
            die("Falha no banco de dados " . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar_ordem_servico); //quantidade de registros
        }
    } else {

        $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
        $status_ordem = $_GET['status_ordem'];
        $tecnico_os = $_GET['tecnico_os'];

        $select = "SELECT os.*,tecnico.cl_usuario as tecnico,prc.cl_razao_social,prc.cl_nome_fantasia,user.cl_usuario,tpo.cl_descricao as tiposervico,status.cl_descricao as statusos  from tb_os as os 
       left join tb_parceiros as prc on prc.cl_id = os.cl_parceiro_id 
       left join tb_users as user on user.cl_id = os.cl_atendente_id
       left join tb_users as tecnico  on tecnico.cl_id = os.cl_tecnico_id
       left join tb_infraestrutura_os as item  on item.cl_codigo_nf = os.cl_codigo_nf
       inner join tb_tipo_servico_os as tpo on tpo.cl_id = os.cl_tipo_servico_id inner join tb_status_os as status on status.cl_id = os.cl_status_id
        WHERE os.cl_data_abertura between '$data_inicial' and '$data_final' and ( prc.cl_razao_social  like '%$pesquisa%'
       or prc.cl_nome_fantasia  like '%$pesquisa%' or prc.cl_cnpj_cpf like '%$pesquisa%'  or os.cl_numero_serie like '%$pesquisa%' or os.cl_numero_nf like '%$pesquisa%' ) ";

        if ($status_ordem != "0") {
            $select .= " and os.cl_status_id = '$status_ordem' ";
        }
        if ($tecnico_os != "0") {
            $select .= " and item.cl_responsavel = '$tecnico_os' ";
        }
        $select .= " Group by os.cl_id order by os.cl_data_abertura desc ";
        $consultar_ordem_servico = mysqli_query($conecta, $select);
        if (!$consultar_ordem_servico) {
            die("Falha no banco de dados " . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar_ordem_servico);
        }
    }
}




// //cadastrar formulario
if (isset($_POST['formulario_ordem_servico'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $acao = $_POST['acao'];
    $usuario_id = verifica_sessao_usuario();
    $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");


    $desconto_permitido = consulta_tabela($conecta, 'tb_parametros', 'cl_id', 63, "cl_valor"); //desconto permitido para ordem de serviço
    $tipo_os = consulta_tabela($conecta, 'tb_parametros', 'cl_id', 66, "cl_valor"); //verificar se a ordem de serviço segue peças ou obras   
    $serie_os = consulta_tabela($conecta, 'tb_serie', 'cl_id', 15, "cl_descricao"); //serie da os
    $numero_os_atual = consulta_tabela($conecta, 'tb_serie', 'cl_id', 15, "cl_valor"); //numero da os 
    $numero_os_nova = $numero_os_atual + 1;

    if ($acao == "show") {
        $id = $_POST['form_id'];
        $select = "SELECT * from tb_os WHERE cl_id = $id";
        $consultar = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consultar);

        $numero_nf = ($linha['cl_numero_nf']);
        $serie_nf = ($linha['cl_serie_nf']);
        $codigo_nf = ($linha['cl_codigo_nf']);
        $atendente = ($linha['cl_atendente_id']);
        $contato = ($linha['cl_contato']);
        $data_abertura = ($linha['cl_data_abertura']);

        $parceiro_id = ($linha['cl_parceiro_id']);
        $parceiro_descricao = utf8_encode(consulta_tabela($conecta, "tb_parceiros", "cl_id", $parceiro_id, "cl_razao_social"));

        $status_id = ($linha['cl_status_id']);
        $numero_caixa = ($linha['cl_numero_caixa']);
        $numero_serie = ($linha['cl_numero_serie']);
        $equipamento = utf8_encode($linha['cl_equipamento']);
        $tipo_servico_id = ($linha['cl_tipo_servico_id']);
        $defeito_informado = utf8_encode($linha['cl_defeito_informado']);
        $defeito_constatado = utf8_encode($linha['cl_defeito_constatado']);
        $nf_garantia_fabrica = ($linha['cl_nf_garantia_fabrica']);
        $data_nf_garantia_fabrica = ($linha['cl_data_nf_garantia_fabrica']);
        $validade_garantia_loja = ($linha['cl_validade_garantia_loja']);
        $nf_garantia_loja = ($linha['cl_nf_garantia_loja']);
        $data_nf_garantia_loja = ($linha['cl_data_nf_garantia_loja']);
        $solicitacao_pecas = ($linha['cl_solicitacao_pecas']);
        $observacao = utf8_encode($linha['cl_observacao']);
        $ordem_fechada = ($linha['cl_ordem_fechada']);
        $forma_pagamento_id = ($linha['cl_forma_pagamento_id']);
        $tecnico_id = ($linha['cl_tecnico_id']);
        $tecnico_id  = $tecnico_id != "" ? $tecnico_id : 0;
        $descricao_obra = utf8_encode($linha['cl_descricao_obra']);
        $local_obra = utf8_encode($linha['cl_local_obra']);
        $data_prazo_entrega = ($linha['cl_prazo_entrega']);
        $data_entrega_obra = ($linha['cl_entrega_obra']);
        $valor_fechado = ($linha['cl_valor_fechado']);
        $valor_despesa = ($linha['cl_valor_despesa']);


        $desconto = ($linha['cl_desconto']);
        $valor_servico = ($linha['cl_valor_servico']);
        $valor_pecas = ($linha['cl_valor_pecas']);
        $taxa_adiantamento = ($linha['cl_taxa_adiantamento']);
        $valor_liquido = ($linha['cl_valor_liquido']);
        $valor_a_receber = $valor_pecas + $valor_servico + $valor_despesa - $taxa_adiantamento -  $desconto;


        $informacao = array(
            "serie_nf" => $serie_nf,
            "numero_nf" => $numero_nf,
            "codigo_nf" => $codigo_nf,
            "atendente" => $atendente,
            "contato" => $contato,
            "data_abertura" => $data_abertura,
            "parceiro_id" => $parceiro_id,
            "parceiro_descricao" => $parceiro_descricao,
            "status_id" => $status_id,
            "numero_caixa" => $numero_caixa,
            "numero_serie" => $numero_serie,
            "equipamento" => $equipamento,
            "tipo_servico_id" => $tipo_servico_id,
            "defeito_informado" => $defeito_informado,
            "defeito_constatado" => $defeito_constatado,
            "nf_garantia_fabrica" => $nf_garantia_fabrica,
            "data_nf_garantia_fabrica" => $data_nf_garantia_fabrica,
            "validade_garantia_loja" => $validade_garantia_loja,
            "nf_garantia_loja" => $nf_garantia_loja,
            "data_nf_garantia_loja" => $data_nf_garantia_loja,
            "solicitacao_pecas" => $solicitacao_pecas,
            "observacao" => $observacao,
            "ordem_fechada" => $ordem_fechada,
            "forma_pagamento_id" => $forma_pagamento_id,

            "valor_pecas" => $valor_pecas,
            "valor_servico" => $valor_servico,
            "taxa_adiantamento" => $taxa_adiantamento,
            "valor_desconto" => $desconto,
            "valor_a_receber" => $valor_a_receber,
            "valor_liquido" => $valor_liquido,
            "tecnico_id" => $tecnico_id,

            "descricao_obra" => $descricao_obra,
            "local_obra" => $local_obra,
            "data_prazo_entrega" => $data_prazo_entrega,
            "data_entrega_obra" => $data_entrega_obra,
            "valor_fechado" => $valor_fechado,
            "valor_despesa" => $valor_despesa,

            "valor_pecas_moeda" =>  real_format($valor_pecas),
            "valor_servico_moeda" => real_format($valor_servico),
            "taxa_adiantamento_moeda" => real_format($taxa_adiantamento),
            "valor_a_receber" => real_format($valor_a_receber),
            "valor_desconto_moeda" => real_format($desconto),
            "valor_liquido_moeda" => real_format($valor_liquido),
            "valor_despesa_moeda" => real_format($valor_despesa),
        );

        $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
    }

    if ($acao == "show_det_peca") {
        $id = $_POST['id'];
        $select = "SELECT * from tb_infraestrutura_os WHERE cl_id = $id";
        $consultar = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consultar);

        $item_descricao = utf8_encode($linha['cl_item_descricao']);
        $produto_id = ($linha['cl_produto_id']);
        $item_id = ($linha['cl_id']);
        $quantidade_orcada = ($linha['cl_quantidade_orcada']);
        $unidade = ($linha['cl_unidade']);
        $referencia = ($linha['cl_referencia']);
        $valor_unitario = ($linha['cl_valor_unitario']);
        $valor_total = ($linha['cl_valor_total']);
        $servico_destinado_id = ($linha['cl_servico_destinado_id']);
        $tipo_material_id = ($linha['cl_tipo_material_id']);

        $informacao = array(
            "item_descricao" => $item_descricao,
            "produto_id" => $produto_id,
            "item_id" => $item_id,
            "quantidade_orcada" => $quantidade_orcada,
            "unidade" => $unidade,
            "referencia" => $referencia,
            "valor_unitario" => $valor_unitario,
            "valor_total" => $valor_total,
            "servico_destinado_id" => $servico_destinado_id,
            "tipo_material_id" => $tipo_material_id,

        );

        $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
    }
    if ($acao == "show_det_servicoa") {
        $id = $_POST['id'];
        $select = "SELECT * from tb_infraestrutura_os WHERE cl_id = $id";
        $consultar = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consultar);

        $item_descricao = utf8_encode($linha['cl_item_descricao']);
        $produto_id = ($linha['cl_produto_id']);
        $item_id = ($linha['cl_id']);
        $quantidade_orcada = ($linha['cl_quantidade_orcada']);
        $valor_unitario = ($linha['cl_valor_unitario']);
        $valor_total = ($linha['cl_valor_total']);
        $responsavel = ($linha['cl_responsavel']);

        $parceiro_terceirizado_id = ($linha['cl_parceiro_terceirizado_id']);
        $data_inicio_terceirizado = ($linha['cl_data_inicio']);
        $data_fim_terceirizado = ($linha['cl_data_fim']);
        $valor_fechado_terceirizado = ($linha['cl_valor_fechado']);
        $descricao_servico_terceirizado = utf8_encode($linha['cl_descricao_servico']);


        $informacao = array(
            "item_descricao" => $item_descricao,
            "servico_id" => $produto_id,
            "item_id" => $item_id,
            "quantidade_orcada" => $quantidade_orcada,
            "valor_unitario" => $valor_unitario,
            "valor_total" => $valor_total,
            "responsavel" => $responsavel,

            "parceiro_terceirizado_id" => $parceiro_terceirizado_id,
            "data_inicio_terceirizado" => $data_inicio_terceirizado,
            "data_fim_terceirizado" => $data_fim_terceirizado,
            "valor_fechado_terceirizado" => $valor_fechado_terceirizado,
            "descricao_servico_terceirizado" => $descricao_servico_terceirizado,
        );

        $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
    }
    if ($acao == "create") {
        // Iniciar uma transação MySQL
        mysqli_begin_transaction($conecta);
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            // Remover aspas simples usando str_replace
            ${$name} = str_replace("'", "", ${$name});
        }

        $codigo_nf = md5(uniqid(time())); //gerar um novo codigo para nf

        if (isset($_POST['pedido_pecas'])) { //ordem fechada, se marcado não poderá alterar nada da os
            $pedido_pecas = 1;
        } else {
            $pedido_pecas = 0;
        }

        // if (isset($_POST['ordem_fechada'])) { //ordem fechada, se marcado não poderá alterar nada da os
        //     $ordem_fechada = 1;
        // } else {
        //     $ordem_fechada = 0;
        // }
        $valida_status = consulta_tabela($conecta, 'tb_status_os', 'cl_id', $status, 'cl_peca_requisitada'); //para alterar para esses status é necessario ter todas as pçeças requisitadas
        $descricao_status = utf8_encode(consulta_tabela($conecta, 'tb_status_os', 'cl_id', $status, 'cl_descricao'));



        if ($atendente == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("atendente"));
        } elseif ($forma_pagamento == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("forma pagamento"));
        } elseif ($tipo_servico == '0') {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("tipo servico"));
        } elseif ($status == '0') {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("status"));
        } elseif ($valida_status == 1) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Por favor, para efetuar a alteração para o status $descricao_status,
            é necessario que todos os materiais já tenham sido requisitados. É Necessario adicionar os materiais a ordem de serviço");
        } elseif (empty($parceiro_id)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("cliente"));
        } elseif (empty($equipamento) and $tipo_os != "2") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("equipamento"));
        } else {
            // $equipamento = utf8_decode($equipamento);
            // $observacao = utf8_decode($observacao);
            // $defeito_informado = utf8_decode($defeito_informado);

            if (verificaVirgula($valor_fechado)) { //verificar se tem virgula
                $valor_fechado = formatDecimal($valor_fechado); // formatar virgula para ponto
            }

            $insert = "INSERT INTO `tb_os` ( `cl_codigo_nf`, `cl_numero_nf`, `cl_serie_nf`, `cl_data_abertura`,
             `cl_contato`, `cl_numero_caixa`, `cl_numero_serie`, `cl_equipamento`, `cl_atendente_id`,
             `cl_parceiro_id`, `cl_forma_pagamento_id`, `cl_tipo_servico_id`, `cl_status_id`, `cl_defeito_informado`,
             `cl_nf_garantia_fabrica`, `cl_data_nf_garantia_fabrica`, `cl_validade_garantia_loja`,
               `cl_nf_garantia_loja`, `cl_data_nf_garantia_loja`, `cl_solicitacao_pecas`, `cl_observacao`,
               `cl_descricao_obra`,`cl_local_obra`,`cl_prazo_entrega`,`cl_entrega_obra`,`cl_valor_fechado` ) VALUES ( '$codigo_nf', '$numero_os_nova', '$serie_os', '$data',
                '$contato', '$numero_caixa', '$numero_serie', '$equipamento', 
                '$atendente', '$parceiro_id', '$forma_pagamento', '$tipo_servico', '$status', '$defeito_informado', '$nf_garantia_fabrica',
                 '$data_garantia_fabrica', '$validade_garantia_loja', '$nf_garantia_loja', '$data_garantia_loja',
                 '$pedido_pecas', '$observacao', '$descricao_obra' ,'$local_obra' ,'$prazo_entrega' ,'$entrega_obra', '$valor_fechado' ) ";
            $operacao_insert = mysqli_query($conecta, $insert);
            if ($operacao_insert) {
                $novo_id_inserido = mysqli_insert_id($conecta);
                update_registro($conecta, "tb_serie", "cl_id", '15', "", "", "cl_valor", $numero_os_nova); //atualizar o valor da serie
                $retornar["dados"] =  array("sucesso" => true, "id" => $novo_id_inserido, "numero_os" => $numero_os_nova,  "title" => "Ordem de serviço Nº $numero_os_nova lançada com sucesso");
                $mensagem = utf8_decode("Adicionou a ordem de serviço $serie_os$numero_os_nova ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de adicionar a ordem de serviço $serie_os$numero_os_nova  ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }


    if ($acao == "update") { // EDITAR

        mysqli_begin_transaction($conecta);
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            // Remover aspas simples usando str_replace
            ${$name} = str_replace("'", "", ${$name});
        }


        if (isset($_POST['pedido_pecas'])) { //ordem fechada, se marcado não poderá alterar nada da os
            $pedido_pecas = 1;
        } else {
            $pedido_pecas = 0;
        }

        // if (isset($_POST['ordem_fechada'])) { //ordem fechada, se marcado não poderá alterar nada da os
        //     $ordem_fechada = 1;
        // } else {
        //     $ordem_fechada = 0;
        // }



        $valida_liquido_ordem = consulta_tabela($conecta, "tb_os", "cl_id", $id, "cl_valor_liquido");
        $serie_os = consulta_tabela($conecta, "tb_os", "cl_id", $id, "cl_serie_nf");
        $numero_os = consulta_tabela($conecta, "tb_os", "cl_id", $id, "cl_numero_nf");
        $valida_status = consulta_tabela($conecta, 'tb_status_os', 'cl_id', $status, 'cl_peca_requisitada'); //para alterar para esses status é necessario ter todas as pçeças requisitadas
        $codigo_nf = consulta_tabela($conecta, "tb_os", "cl_id", $id, 'cl_codigo_nf');
        $descricao_status = utf8_encode(consulta_tabela($conecta, 'tb_status_os', 'cl_id', $status, 'cl_descricao'));
        $situacao = consulta_tabela($conecta, "tb_os", "cl_id", $id, 'cl_situacao');


        if ($valida_status == 1) { //se selecionado o status que requesita peças automaticamente
            $requisitar_pecas = requisitar_pecas_os($id);
        }

        $select  = "SELECT
        COUNT(*) AS total,
        COUNT(CASE WHEN cl_peca_requisitada = 0 THEN 1 END) AS totalnaoreq
      FROM tb_infraestrutura_os
      WHERE cl_codigo_nf = '$codigo_nf'  and cl_tipo ='MATERIAL' ";
        $consulta_material_nao_requisitado = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consulta_material_nao_requisitado);
        $qtd_consulta_material_nao_requisitado = $linha['totalnaoreq'];
        $qtd_pecas_os = $linha['total'];


        if ($atendente == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("atendente"));
        } elseif ($forma_pagamento == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("forma pagamento"));
        } elseif ($tipo_servico == '0') {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("tipo servico"));
        } elseif ($status == '0') {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("status"));
        } elseif (($valida_status == 1 and $qtd_consulta_material_nao_requisitado > 0) or ($qtd_pecas_os == 0 and $valida_status == 1)) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Por favor, para efetuar a alteração para o status $descricao_status,
             é necessario que todos os materiais já tenham sido requisitados. Ainda existem materiais pendentes de requisição");
        } elseif (empty($parceiro_id)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("cliente"));
        } elseif (empty($equipamento) and $tipo_os == 1) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("equipamento"));
        }
        // elseif (( $valida_liquido_ordem == 0)) {
        //     $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel adicionar essa ordem de serviço fechada, 
        //     é necessario incluir peças ou seviços para assim definir como fechada");
        // }
        else {


            $update = "UPDATE `tb_os` SET
            `cl_contato` = '$contato',
            `cl_numero_caixa` = '$numero_caixa',
            `cl_numero_serie` = '$numero_serie',
            `cl_equipamento` = '$equipamento',
            `cl_atendente_id` = '$atendente',
            `cl_parceiro_id` = '$parceiro_id',
            `cl_forma_pagamento_id` = '$forma_pagamento',
            `cl_tipo_servico_id` = '$tipo_servico',
            `cl_status_id` = '$status',
            `cl_defeito_informado` = '$defeito_informado',
            `cl_nf_garantia_fabrica` = '$nf_garantia_fabrica',
            `cl_data_nf_garantia_fabrica` = '$data_garantia_fabrica',
            `cl_validade_garantia_loja` = '$validade_garantia_loja',
            `cl_nf_garantia_loja` = '$nf_garantia_loja',
            `cl_data_nf_garantia_loja` = '$data_garantia_loja',
            `cl_solicitacao_pecas` = '$pedido_pecas',
            `cl_observacao` = '$observacao',
            `cl_descricao_obra`='$descricao_obra',
            `cl_local_obra`='$local_obra',
            `cl_prazo_entrega`='$prazo_entrega',
            `cl_entrega_obra`='$entrega_obra',
            `cl_valor_fechado`='$valor_fechado'
          WHERE `cl_id` = $id ";
            $operacao_update = mysqli_query($conecta, $update);
            if ($operacao_update) {
                $retornar["dados"] =  array("sucesso" => true, "title" => "Ordem de serviço alterada com sucesso");
                $mensagem = utf8_decode("Alterou a ordem de serviço $serie_os$numero_os ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de alterar a ordem de serviço $serie_os$numero_os  ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }

    if ($acao == "update_diagnostico_tecnico") { // update no diagnostico tecnico

        mysqli_begin_transaction($conecta);
        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            // Remover aspas simples usando str_replace
            ${$name} = str_replace("'", "", ${$name});
        }

        $serie_os = consulta_tabela($conecta, "tb_os", "cl_id", $id, "cl_serie_nf");
        $numero_os = consulta_tabela($conecta, "tb_os", "cl_id", $id, "cl_numero_nf");
        $codigo_nf = consulta_tabela($conecta, "tb_os", "cl_id", $id, 'cl_codigo_nf');
        $valida_status = consulta_tabela($conecta, 'tb_status_os', 'cl_id', $status, 'cl_peca_requisitada'); //para alterar para esses status é necessario ter todas as pçeças requisitadas
        $descricao_status = utf8_encode(consulta_tabela($conecta, 'tb_status_os', 'cl_id', $status, 'cl_descricao'));

        $valor_pecas_os = consulta_tabela($conecta, "tb_os", "cl_id", $id, 'cl_valor_pecas');
        $valor_liquido_os = consulta_tabela($conecta, "tb_os", "cl_id", $id, 'cl_valor_liquido');
        $valor_taxa_adiantamento_os = consulta_tabela($conecta, "tb_os", "cl_id", $id, 'cl_taxa_adiantamento');
        $valor_desconto_os = consulta_tabela($conecta, "tb_os", "cl_id", $id, 'cl_desconto');
        $ordem_fechada = consulta_tabela($conecta, "tb_os", "cl_id", $id, 'cl_ordem_fechada');
        $valor_despesa_os = consulta_tabela($conecta, "tb_os", "cl_id", $id, 'cl_valor_despesa');
        $situacao = consulta_tabela($conecta, "tb_os", "cl_id", $id, 'cl_situacao');


        if ($valida_status == 1) { //se selecionado o status que requesita peças automaticamente
            $requisitar_pecas = requisitar_pecas_os($id);
        }

        $select  = "SELECT
        COUNT(*) AS total,
        COUNT(CASE WHEN cl_peca_requisitada = 0 THEN 1 END) AS totalnaoreq
      FROM tb_infraestrutura_os
      WHERE cl_codigo_nf = '$codigo_nf' and cl_tipo ='MATERIAL' ";
        $consulta_material_nao_requisitado = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consulta_material_nao_requisitado);
        $qtd_consulta_material_nao_requisitado = $linha['totalnaoreq'];
        $qtd_pecas_os = $linha['total'];

        if ($equipamento == '0') {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("equipamento"));
        } elseif ($tipo_servico == '0') {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("tipo servico"));
        } elseif ($status == '0') {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("status"));
        } elseif (($valida_status == 1 and $qtd_consulta_material_nao_requisitado > 0) or ($qtd_pecas_os == 0 and $valida_status == 1)) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Por favor, para efetuar a alteração para o status $descricao_status,
             é necessario que todos os materiais já tenham sido requisitados. Ainda existem materiais pendentes de requisição");
        } elseif (empty($equipamento)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("equipamento"));
        } elseif (empty($defeito_constatado)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("defeito constatado"));
        } elseif ($situacao == 1) { //faturada
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel alterar o serviço, a ordem de serviço já está faturada");
        } elseif ($situacao == 2) { //cancelada
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel alterar o serviço, a ordem de serviço já está cancelada");
        } else {

            $update = "UPDATE `tb_os` SET
            `cl_numero_caixa` = '$numero_caixa',
            `cl_numero_serie` = '$numero_serie',
            `cl_equipamento` = '$equipamento', `cl_tipo_servico_id` = '$tipo_servico',
            `cl_status_id` = '$status',
            `cl_defeito_constatado` = '$defeito_constatado'
          WHERE `cl_id` = $id ";

            $operacao_update = mysqli_query($conecta, $update);
            if ($operacao_update) {
                $retornar["dados"] =  array("sucesso" => true, "title" => "Diagnóstico alterada com sucesso");
                $mensagem = utf8_decode("Alterou o Diagnóstico técnico - serviço da $serie_os $numero_os ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de alterar o Diagnóstico técnico - serviço da $serie_os $numero_os  ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }

    if ($acao == "pecas_diagnostico_tecnico") { // incluir material

        // Iniciar uma transação MySQL
        mysqli_begin_transaction($conecta);

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            // Remover aspas simples usando str_replace
            ${$name} = str_replace("'", "", ${$name});
        }


        if ($item_id == "") { //incluir
            $codigo_nf = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_codigo_nf');
            $numero_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_numero_nf');
            $serie_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_serie_nf');
            $parceiro_id = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_parceiro_id');
            $valor_liquido_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_liquido');
            $taxa_adiantamento_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_taxa_adiantamento');
            $desconto_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_desconto');
            $valor_servico_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_servico');
            $valor_pecas_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_pecas');
            $ordem_fechada = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_ordem_fechada');
            $valor_despesa_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_despesa');
            $situacao = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_situacao');

            if ($produto_id == "") {
                $retornar["dados"] = array("sucesso" => false, "title" => "É necessario selecionar o material");
                echo json_encode($retornar);
                exit; // Encerra a execução do script
            }

            $unidade_id = consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_und_id"); //unidade_id
            $unidade = (consulta_tabela($conecta, "tb_unidade_medida", "cl_id", $unidade_id, "cl_sigla"));
            $descricao_produto = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_descricao"));
            $referencia = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_referencia"));
            $preco_venda = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_preco_venda"));


            if (verificaVirgula($quantidade)) { //verificar se tem virgula
                $quantidade = formatDecimal($quantidade); // formatar virgula para ponto
            }
            if (verificaVirgula($preco_venda)) { //verificar se tem virgula
                $preco_venda = formatDecimal($preco_venda); // formatar virgula para ponto
            }
            $valor_total_item = $quantidade * $preco_venda;

            if ($form_id == "") {
                $retornar["dados"] = array("sucesso" => false, "title" => "Ordem de serviço inexistente, favor, verifique");
            } elseif ($situacao == 1) { //faturada
                $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel incluir o material, a ordem de serviço já está faturada");
            } elseif ($situacao == 2) { //cancelada
                $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel incluir o material, a ordem de serviço já está cancelada");
            } elseif ($descricao_produto == "") {
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("produto"));
            } elseif (($valor_total_item) == 0) {
                $retornar["dados"] = array("sucesso" => false, "title" => "Valor total do material não pode ser 0, favor, verifique");
            } else {

                $insert = "INSERT INTO `tb_infraestrutura_os` ( `cl_codigo_nf`, `cl_numero_nf`, `cl_serie_nf`, `cl_tipo`, 
            `cl_produto_id`, `cl_item_descricao`, `cl_quantidade_requisitada`, `cl_quantidade_orcada`,
             `cl_unidade`,`cl_referencia`, `cl_valor_unitario`, `cl_valor_total`, `cl_peca_requisitada`) 
             VALUES ( '$codigo_nf', '$numero_os', '$serie_os', 'MATERIAL', '$produto_id',
              '$descricao_produto', '0', '$quantidade', '$unidade', '$referencia', '$preco_venda', '$valor_total_item','0' )";
                $operacao_insert = mysqli_query($conecta, $insert);

                $novo_valor_pecas = $valor_pecas_os + $valor_total_item;
                $novo_valor_liquido = $novo_valor_pecas + $valor_servico_os + $valor_despesa_os - $desconto_os;

                $update_valor_pecas =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_valor_pecas", $novo_valor_pecas); //atualizar o valor das peças na os
                $update_valor_liquido =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_valor_liquido", $novo_valor_liquido); //atualizar o valor 

                if ($operacao_insert and $update_valor_pecas and $update_valor_liquido) {
                    $retornar["dados"] =  array("sucesso" => true, "query" => "insert", "title" => "Material incluido com sucesso");
                    $mensagem = utf8_decode("Adicionou o material $descricao_produto, valor $valor_total_item, $serie_os $numero_os ");
                    registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                    // Se tudo ocorreu bem, confirme a transação
                    mysqli_commit($conecta);
                } else {
                    // Se ocorrer um erro, reverta a transação
                    mysqli_rollback($conecta);
                    $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                    $mensagem = utf8_decode("Tentativa sem sucesso de adicionar o material $descricao_produto, valor $valor_total_item, $serie_os $numero_os ");
                    registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                }
            }
        } else { //alterar

            $codigo_nf = consulta_tabela($conecta, "tb_infraestrutura_os", "cl_id", $item_id, 'cl_codigo_nf'); //codigo nf da os
            $form_id = consulta_tabela($conecta, "tb_os", "cl_codigo_nf", $codigo_nf, 'cl_id'); //id os
            $numero_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_numero_nf');
            $serie_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_serie_nf');
            $parceiro_id = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_parceiro_id');
            $valor_liquido_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_liquido');
            $taxa_adiantamento_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_taxa_adiantamento');
            $desconto_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_desconto');
            $valor_servico_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_servico');
            $valor_pecas_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_pecas');
            $ordem_fechada = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_ordem_fechada');
            $peca_requisitada = consulta_tabela($conecta, "tb_infraestrutura_os", "cl_id", $item_id, 'cl_peca_requisitada');
            $valor_despesa_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_despesa');
            $situacao = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_situacao');

            if ($produto_id == "") {
                $retornar["dados"] = array("sucesso" => false, "title" => "É necessario selecionar o material");
                echo json_encode($retornar);
                exit; // Encerra a execução do script
            }
            $unidade_id = consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_und_id"); //unidade_id
            $unidade = (consulta_tabela($conecta, "tb_unidade_medida", "cl_id", $unidade_id, "cl_sigla"));
            $descricao_produto = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_descricao"));
            $referencia = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_referencia"));
            $preco_venda = (consulta_tabela($conecta, "tb_infraestrutura_os", "cl_id", $item_id, "cl_valor_unitario")); //valor unitario do item na tabela infraestrutura os
            $quantidade =  (consulta_tabela($conecta, "tb_infraestrutura_os", "cl_id", $item_id, "cl_quantidade_orcada"));


            // if (verificaVirgula($quantidade)) { //verificar se tem virgula
            //     $quantidade = formatDecimal($quantidade); // formatar virgula para ponto
            // }
            // if (verificaVirgula($preco_venda)) { //verificar se tem virgula
            //     $preco_venda = formatDecimal($preco_venda); // formatar virgula para ponto
            // }
            $valor_total_item = $quantidade * $preco_venda;

            if ($form_id == "") {
                $retornar["dados"] = array("sucesso" => false, "title" => "Ordem de serviço inexistente, favor, verifique");
            } elseif ($peca_requisitada == 1) {
                $retornar["dados"] = array("sucesso" => false, "title" => "Não é possível fazer alterações neste material, pois a requisição já foi feita");
            } elseif ($situacao == 1) { //faturada
                $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel alterar o material, a ordem de serviço já está faturada");
            } elseif ($situacao == 2) { //cancelada
                $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel alterar o material, a ordem de serviço já está cancelada");
            } elseif ($produto_id == "" and $unidade == "") { //produto avulso é necessario informar a unidade 
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("und"));
            } elseif (($valor_total_item) == 0) {
                $retornar["dados"] = array("sucesso" => false, "title" => "Valor total do material não pode ser 0, favor, verifique");
            } else {
                $update = "UPDATE `tb_infraestrutura_os` SET
                `cl_tipo` = 'MATERIAL',
                `cl_produto_id` = '$produto_id',
                `cl_quantidade_orcada` = '$quantidade',
                `cl_valor_unitario` = '$preco_venda',
                `cl_valor_total` = '$valor_total_item'
                 WHERE cl_id = '$item_id'";
                $operacao_update = mysqli_query($conecta, $update);

                $novo_valor_pecas = valores_total_tabela('tb_infraestrutura_os', 'cl_valor_total', 'cl_codigo_nf', $codigo_nf, 'cl_tipo', 'MATERIAL'); //valor total dos materias apos o update
                $novo_valor_liquido = $novo_valor_pecas + $valor_servico_os + $valor_despesa_os - $desconto_os;

                $update_valor_pecas =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_valor_pecas", $novo_valor_pecas); //atualizar o valor das peças na os
                $update_valor_liquido =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_valor_liquido", $novo_valor_liquido); //atualizar o valor 

                if ($operacao_update and $update_valor_pecas and $update_valor_liquido) {
                    $retornar["dados"] =  array("sucesso" => true, "query" => "update", "title" => "Material alterado com sucesso");
                    $mensagem = utf8_decode("Alterou o produto $descricao_produto, valor $valor_total_item, $serie_os $numero_os ");
                    registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                    // Se tudo ocorreu bem, confirme a transação
                    mysqli_commit($conecta);
                } else {
                    // Se ocorrer um erro, reverta a transação
                    mysqli_rollback($conecta);
                    $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                    $mensagem = utf8_decode("Tentativa sem sucesso de alterar o material $descricao_produto, valor $valor_total_item, $serie_os$numero_os ");
                    registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                }
            }
        }
    }

    if ($acao == "adicionar_desconto") { // EDITAR
        // Iniciar uma transação MySQL
        mysqli_begin_transaction($conecta);

        $form_id = $_POST['form_id'];
        $desconto = $_POST['desconto'];
        $check_autorizador = $_POST['check_autorizador'];


        $numero_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_numero_nf');
        $serie_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_serie_nf');

        $valor_desconto_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_desconto');
        $valor_servico_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_servico');
        $valor_pecas_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_pecas');
        $valor_liquido_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_liquido');
        $valor_despesa_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_despesa');
        $valor_taxa_adiantamento_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_taxa_adiantamento');
        $situacao = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_situacao');

        $valor_ordem = $valor_pecas_os + $valor_servico_os + $valor_despesa_os - $valor_desconto_os;
        $valor_liquido = $valor_pecas_os + $valor_servico_os + $valor_despesa_os - $desconto;
        if (verificaVirgula($desconto)) { //verificar se tem virgula
            $desconto = formatDecimal($desconto); // formatar virgula para ponto
        }

        if ($form_id == "") {
            $retornar["dados"] = array("sucesso" => false, "title" => "É necessario adicionar a ordem de serviço");
        } elseif ($situacao == 1) { //faturada
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel adicionaro desconto, a ordem de serviço já está faturada");
        } elseif ($situacao == 2) { //cancelada
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel adicionaro desconto, a ordem de serviço já está cancelada");
        } elseif ($desconto == "") {
            $retornar["dados"] = array("sucesso" => false, "title" => "Informe o valor do desconto");
        } elseif ($desconto != 0 and (descontoAcimaPermitido($valor_ordem, $desconto, $desconto_permitido) == true) and ($check_autorizador == "false")) {
            $retornar["dados"] =  array("sucesso" => "autorizar", "title" => "O desconto está acima do permitido, continue com a operação autorizando com a senha");
        } else {

            $update = "UPDATE `tb_os` SET `cl_desconto` = '$desconto', `cl_valor_liquido` = '$valor_liquido' WHERE `cl_id` = $form_id";
            $operacao_update = mysqli_query($conecta, $update);
            if ($operacao_update) {
                $retornar["dados"] =  array("sucesso" => true, "title" => "Desconto alterado com sucesso");
                $mensagem = utf8_decode("Alterou o desconto para $desconto, valor ordem " . ($valor_ordem - $desconto) . ",  ordem de serviço $serie_os $numero_os  ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de alterar o desconto da ordem de serviço, $serie_os $numero_os ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }
    if ($acao == "incluir_taxa") { // incluir taxa e adiantamento

        // Iniciar uma transação MySQL
        mysqli_begin_transaction($conecta);

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            // Remover aspas simples usando str_replace
            ${$name} = str_replace("'", "", ${$name});
        }

        $codigo_nf = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_codigo_nf');
        $numero_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_numero_nf');
        $serie_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_serie_nf');
        $parceiro_id = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_parceiro_id');
        $valor_liquido_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_liquido');
        $taxa_adiantamento_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_taxa_adiantamento');
        $valor_despesa_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_despesa');

        $desconto_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_desconto');
        $valor_servico_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_servico');
        $valor_pecas_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_pecas');
        $ordem_fechada = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_ordem_fechada');
        $situacao = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_situacao');

        $conta_financeira = consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $forma_pagamento, 'cl_conta_financeira');

        if (!empty($dt_recebimento)) {
            $caixa =  verifica_caixa_financeiro($conecta, $dt_recebimento, $conta_financeira);
        }
        if (verificaVirgula($valor)) { //verificar se tem virgula
            $valor = formatDecimal($valor); // formatar virgula para ponto
        }

        if ($form_id == "") {
            $retornar["dados"] = array("sucesso" => false, "title" => "É necessario adicionar a ordem de serviço");
        } elseif ($situacao == 1) { //faturada
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel incluir a taxa/adiantamento, a ordem de serviço já está faturada");
        } elseif ($situacao == 2) { //cancelada
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel incluir a taxa/adiantamento, a ordem de serviço já está cancelada");
        } elseif (empty($dt_recebimento)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("data recebimento"));
        } elseif (($forma_pagamento) == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("forma pagamento"));
        } elseif (($tipo) == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro("tipo"));
        } elseif (empty($valor)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("valor"));
        } elseif (($caixa['resultado']) == "") { //verificar se o caixa já foi aberto
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("VAZIO")); //alertar o usuario que o caixa ainda não foi aberto
        } elseif ($caixa['status'] == "fechado") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("FECHADO")); //alertar o usuario que o caixa está fechado
        } else {
            $descricao = "Recebimento referente a $serie_os $numero_os, $tipo ";

            $insert_taxa = recebimento_nf(
                $conecta,
                $dt_recebimento,
                $data_lancamento,
                $data_lancamento,
                "0",
                $forma_pagamento,
                $parceiro_id,
                "RECEITA",
                "2",
                $valor,
                $valor,
                0,
                0,
                0,
                0,
                "$serie_os$numero_os/$tipo",
                0,
                $descricao,
                "",
                $numero_os,
                $serie_os,
                $codigo_nf,
                $form_id,
                ""
            ); //lancar no financeiro o recebimento
            $novo_valor_taxa_adiantamento = $taxa_adiantamento_os + $valor;
            // $novo_valor_liquido = $valor_pecas_os + $valor_servico_os +  $valor_despesa_os - $desconto_os;

            // $update_valor_liquido =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_valor_liquido", $novo_valor_liquido);
            $update_valor_taxa =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_taxa_adiantamento", $novo_valor_taxa_adiantamento);

            if ($insert_taxa  and $update_valor_taxa) {
                $retornar["dados"] =  array("sucesso" => true, "title" => "Valor incluido com sucesso");
                $mensagem = utf8_decode("Adicionou o lançamento no valor de $valor do tipo $tipo na $serie_os $numero_os  ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de lançar o valor de $valor do tipo $tipo  na $serie_os $numero_os ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }
    if ($acao == "incluir_despesa") { // incluir despesas

        // Iniciar uma transação MySQL
        mysqli_begin_transaction($conecta);

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            // Remover aspas simples usando str_replace
            ${$name} = str_replace("'", "", ${$name});
        }


        $codigo_nf = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_codigo_nf');
        $numero_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_numero_nf');
        $serie_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_serie_nf');
        $valor_liquido_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_liquido');
        $taxa_adiantamento_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_taxa_adiantamento');
        $valor_despesa_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_despesa');
        $desconto_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_desconto');
        $valor_servico_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_servico');
        $valor_pecas_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_pecas');
        $ordem_fechada = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_ordem_fechada');
        $situacao = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_situacao');

        $conta_financeira = consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $forma_pagamento_id, 'cl_conta_financeira');

        if (!empty($dt_pagamento)) {
            $caixa =  verifica_caixa_financeiro($conecta, $dt_pagamento, $conta_financeira);
        }

        if (verificaVirgula($valor)) { //verificar se tem virgula
            $valor = formatDecimal($valor); // formatar virgula para ponto
        }

        if ($form_id == "") {
            $retornar["dados"] = array("sucesso" => false, "title" => "É necessario adicionar a ordem de serviço");
        } elseif ($situacao == 1) { //faturada
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel incluir a despesa, a ordem de serviço já está faturada");
        } elseif ($situacao == 2) { //cancelada
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel incluir a despesa, a ordem de serviço já está cancelada");
        } elseif (empty($dt_pagamento)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("data pagamento"));
        } elseif (($classificao_id) == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("classificação"));
        } elseif (($tipo) == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro("tipo"));
        } elseif (($forma_pagamento_id) == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro("forma pagamento"));
        } elseif (($valor) == 0) {
            $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro("valor"));
        } elseif (($caixa['resultado']) == "") { //verificar se o caixa já foi aberto
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("VAZIO")); //alertar o usuario que o caixa ainda não foi aberto
        } elseif ($caixa['status'] == "fechado") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("FECHADO")); //alertar o usuario que o caixa está fechado
        } else {

            $doc = "$serie_os$numero_os/Despesa";
            $insert_despesa = recebimento_nf(
                $conecta,
                $data_lancamento,
                $dt_pagamento,
                $dt_pagamento,
                "0",
                $forma_pagamento_id,
                $parceiro_id,
                "DESPESA",
                "4",
                $valor,
                $valor,
                0,
                0,
                0,
                0,
                $doc,
                $classificao_id,
                $descricao,
                "",
                $numero_os,
                $serie_os,
                $codigo_nf,
                $form_id,
                ""
            ); //lancar no financeiro o recebimento

            $insert = "INSERT INTO `tb_despesa_os` (`cl_data_movimento`,`cl_codigo_nf`,`cl_lancamento_id`,`cl_tipo`,`cl_servico_destinado_id`) VALUES
             ( '$dt_pagamento','$codigo_nf','$insert_despesa', '$tipo','$servico_id' )";
            $operacao_insert = mysqli_query($conecta, $insert);

            $update_valor_despesa = true;
            $update_valor_liquido = true;

            if ($tipo == 1) { //contabiliza na os
                $novo_valor_despesa = $valor_despesa_os + $valor;
                $novo_valor_liquido = $valor_pecas_os + $valor_servico_os +  $novo_valor_despesa - $desconto_os;

                $update_valor_liquido =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_valor_liquido", $novo_valor_liquido);
                $update_valor_despesa =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_valor_despesa", $novo_valor_despesa);
            }

            if ($operacao_insert and $insert_despesa and $update_valor_liquido and $update_valor_despesa) {
                $tipo = $tipo == 1 ? 'contabiliza' : 'Não contabiliza';
                $retornar["dados"] =  array("sucesso" => true, "title" => "Despesa Incluida com sucesso");
                $mensagem = utf8_decode("Adicionou a despesa do tipo $tipo no valor de $valor, $serie_os $numero_os ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                $tipo = $tipo == 1 ? 'contabiliza' : 'Não contabiliza';
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de adicionar a despesa do tipo $tipo no valor de $valor, $serie_os $numero_os ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }

    if ($acao == "cancelar_taxa") { // remover taxa e adiantamento

        // Iniciar uma transação MySQL
        mysqli_begin_transaction($conecta);

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            // Remover aspas simples usando str_replace
            ${$name} = str_replace("'", "", ${$name});
        }


        $codigo_nf = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id, 'cl_codigo_nf'); //codigo da os
        $data_pagamento = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id, 'cl_data_pagamento'); //codigo da os
        $valor_cancelado_taxa = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id, 'cl_valor_bruto'); //codigo da os
        $conta_financeira = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id, 'cl_conta_financeira'); //
        $form_id = consulta_tabela($conecta, "tb_os", "cl_codigo_nf", $codigo_nf, 'cl_id'); //id da os
        $numero_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_numero_nf');
        $serie_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_serie_nf');
        $parceiro_id = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_parceiro_id');
        $valor_liquido_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_liquido');
        $taxa_adiantamento_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_taxa_adiantamento');
        $desconto_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_desconto');
        $valor_servico_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_servico');
        $valor_pecas_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_pecas');
        $ordem_fechada = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_ordem_fechada');
        $valor_despesa_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_despesa');
        $situacao = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_situacao');

        $caixa =  verifica_caixa_financeiro($conecta, $data_pagamento, $conta_financeira);

        if ($form_id == "") {
            $retornar["dados"] = array("sucesso" => false, "title" => "É necessario adicionar a ordem de serviço");
        } elseif ($situacao == 1) { //faturada
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel cancelar a taxa/adiantamento, a ordem de serviço já está faturada");
        } elseif ($situacao == 2) { //cancelada
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel cancelar a taxa/adiantamento, a ordem de serviço está cancelada");
        } elseif (($caixa['resultado']) == "") { //verificar se o caixa já foi aberto
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("VAZIO")); //alertar o usuario que o caixa ainda não foi aberto
        } elseif ($caixa['status'] == "fechado") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("FECHADO")); //alertar o usuario que o caixa está fechado
        } elseif ($check_autorizador == "false") {
            $retornar["dados"] =  array("sucesso" => "autorizar", "title" =>  "Continue com a operação autorizando com a senha");
        } else {
            $novo_valor_taxa_adiantamento = $taxa_adiantamento_os - $valor_cancelado_taxa;
            // $novo_valor_liquido = $valor_pecas_os + $valor_servico_os + $valor_despesa_os - $desconto_os ;

            // $update_valor_liquido =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_valor_liquido", $novo_valor_liquido); //atualizar o valor liquido da os
            $update_valor_taxa =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_taxa_adiantamento", $novo_valor_taxa_adiantamento); //atualizar o valor do adiantamento
            $update_status_lancamento =  update_registro($conecta, "tb_lancamento_financeiro", "cl_id", $id, '', '', "cl_status_id", 5); //alterar para canccelado o lançamento no financeciro

            if ($update_status_lancamento and $update_valor_taxa) {
                $retornar["dados"] =  array("sucesso" => true, "title" => "Cancelamento realizado com sucesso");
                $mensagem = utf8_decode("Cancelou a taxa/Adiantamento no valor de $valor_cancelado_taxa, $serie_os $numero_os ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de cencelar a taxa/Adiantamento no valor de $valor_cancelado_taxa,  $serie_os $numero_os ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }

    if ($acao == "cancelar_despesa") { // cancelar despesa

        // Iniciar uma transação MySQL
        mysqli_begin_transaction($conecta);

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            // Remover aspas simples usando str_replace
            ${$name} = str_replace("'", "", ${$name});
        }


        $codigo_nf = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id, 'cl_codigo_nf'); //codigo da os
        $valor_cancelado_despesa = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id, 'cl_valor_liquido'); //codigo da os
        $tipo_despesa = consulta_tabela($conecta, "tb_despesa_os", "cl_lancamento_id", $id, 'cl_tipo'); //1-contabiliza na os 2 não contabiliza
        $conta_financeira = consulta_tabela($conecta, "tb_lancamento_financeiro", "cl_id", $id, 'cl_conta_financeira'); //1-contabiliza na os 2 não contabiliza

        $form_id = consulta_tabela($conecta, "tb_os", "cl_codigo_nf", $codigo_nf, 'cl_id'); //id da os
        $numero_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_numero_nf');
        $serie_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_serie_nf');
        $parceiro_id = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_parceiro_id');
        $valor_liquido_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_liquido');
        $taxa_adiantamento_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_taxa_adiantamento');
        $desconto_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_desconto');
        $valor_servico_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_servico');
        $valor_pecas_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_pecas');
        $ordem_fechada = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_ordem_fechada');
        $valor_despesa_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_despesa');
        $situacao = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_situacao');

        $caixa =  verifica_caixa_financeiro($conecta, $data_lancamento, $conta_financeira);

        $update_status_lancamento = true;
        $update_valor_liquido = true;
        $update_valor_despesa = true;

        if ($form_id == "") {
            $retornar["dados"] = array("sucesso" => false, "title" => "É necessario adicionar a ordem de serviço");
        } elseif ($situacao == 1) { //faturada
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel cancelar a despesa, a ordem de serviço já está faturada");
        } elseif ($situacao == 2) { //cancelada
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel cancelar a despesa, a ordem de serviço já está cancelada");
        } elseif (($caixa['resultado']) == "") { //verificar se o caixa já foi aberto
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("VAZIO")); //alertar o usuario que o caixa ainda não foi aberto
        } elseif ($caixa['status'] == "fechado") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_caixa("FECHADO")); //alertar o usuario que o caixa está fechado
        } else {
            if ($tipo_despesa == "1") {
                $novo_valor_despesa = $valor_despesa_os - $valor_cancelado_despesa;
                $novo_valor_liquido = $valor_pecas_os + $valor_servico_os + $novo_valor_despesa - $desconto_os;

                $update_valor_liquido =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_valor_liquido", $novo_valor_liquido); //atualizar o valor liquido da os
                $update_valor_despesa =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_valor_despesa", $novo_valor_despesa); //atualizar o valor do adiantamento
                $update_status_lancamento =  update_registro($conecta, "tb_lancamento_financeiro", "cl_id", $id, '', '', "cl_status_id", 5); //alterar para canccelado o lançamento no financeciro
            } else {
                $update_status_lancamento =  update_registro($conecta, "tb_lancamento_financeiro", "cl_id", $id, '', '', "cl_status_id", 5); //alterar para canccelado o lançamento no financeciro
                $update_valor_liquido = true;
                $update_valor_despesa = true;
            }

            if ($update_status_lancamento and $update_valor_liquido and $update_valor_despesa) {
                $retornar["dados"] =  array("sucesso" => true, "title" => "Cancelamento realizado com sucesso");
                $mensagem = utf8_decode("Cancelou a despesa de código $id no valor de $valor_cancelado_despesa, $serie_os $numero_os ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de cencelar a despesa no valor de $valor_cancelado_despesa, $serie_os $numero_os ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }

    if ($acao == "pecas") { // incluir material

        // Iniciar uma transação MySQL
        mysqli_begin_transaction($conecta);

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            // Remover aspas simples usando str_replace
            ${$name} = str_replace("'", "", ${$name});
        }

        $servico_destinado = isset($_POST['servico_id']) ? $_POST['servico_id'] : '';

        if ($item_id == "") { //incluir
            $codigo_nf = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_codigo_nf');
            $numero_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_numero_nf');
            $serie_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_serie_nf');
            $parceiro_id = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_parceiro_id');
            $valor_liquido_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_liquido');
            $taxa_adiantamento_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_taxa_adiantamento');
            $desconto_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_desconto');
            $valor_servico_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_servico');
            $valor_pecas_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_pecas');
            $ordem_fechada = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_ordem_fechada');
            $valor_despesa_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_despesa');
            $produto_default_id = consulta_tabela($conecta, "tb_parametros", "cl_id", 127, 'cl_valor'); //produto default
            $situacao = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_situacao');

            /*material */
            if ($produto_id == "") { //material avulso não tem no estoque

                if ($produto_default_id == "") { //parametro não informado
                    $retornar["dados"] = array("sucesso" => false, "title" => "Para prosseguir, é necessário definir um produto padrão. Por favor, entre em contato com o suporte.");
                    echo json_encode($retornar);
                    exit;
                } else {
                    $valida_produto_default = consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_default_id, "cl_id"); //unidade_id
                    if ($valida_produto_default == "") { //produto não encontrado
                        $retornar["dados"] = array("sucesso" => false, "title" => "Produto padrão não cadastrado no sistema, Por favor, entre em contato com o suporte.");
                        echo json_encode($retornar);
                        exit;
                    }
                }

                $produto_id = $produto_default_id;
                $referencia = "";
                $descricao_produto = ($descricao_produto);
                $unidade = ($unidade);
            } else {
                $unidade_id = consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_und_id"); //unidade_id
                $unidade = (consulta_tabela($conecta, "tb_unidade_medida", "cl_id", $unidade_id, "cl_sigla"));
                $descricao_produto = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_descricao"));
                $referencia = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_referencia"));
            }

            if (verificaVirgula($quantidade)) { //verificar se tem virgula
                $quantidade = formatDecimal($quantidade); // formatar virgula para ponto
            }
            if (verificaVirgula($preco_venda)) { //verificar se tem virgula
                $preco_venda = formatDecimal($preco_venda); // formatar virgula para ponto
            }
            $valor_total_item = $quantidade * $preco_venda;

            $tipo_material = isset($_POST['tipo_material']) ? $_POST['tipo_material'] : '9'; //9- uso e consumo
            $tipo = $tipo_material == "9" ? "MATERIAL" : 'MATERIALMOBILIZADO';

            if ($form_id == "") {
                $retornar["dados"] = array("sucesso" => false, "title" => "É necessario adicionar a ordem de serviço");
            } elseif ($situacao == 1) { //faturada
                $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel incluir o material, a ordem de serviço já está faturada");
            } elseif ($situacao == 2) { //cancelada
                $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel incluir o material, a ordem de serviço já está cancelada");
            } elseif ($descricao_produto == "") {
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("produto"));
            } elseif ($unidade == "") { //produto avulso é necessario informar a unidade 
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("und"));
            } elseif (($valor_total_item) == 0 and $tipo_material == "9") {
                $retornar["dados"] = array("sucesso" => false, "title" => "Valor total do material não pode ser 0, favor, verifique");
            } else {


                $insert = "INSERT INTO `tb_infraestrutura_os` ( `cl_codigo_nf`, `cl_numero_nf`, `cl_serie_nf`, `cl_tipo`, 
            `cl_produto_id`, `cl_item_descricao`, `cl_quantidade_requisitada`, `cl_quantidade_orcada`,
             `cl_unidade`,`cl_referencia`, `cl_valor_unitario`, `cl_valor_total`, `cl_peca_requisitada`,`cl_servico_destinado_id` ,`cl_tipo_material_id` ) 
             VALUES ( '$codigo_nf', '$numero_os', '$serie_os','$tipo','$produto_id',
              '$descricao_produto', '0', '$quantidade', '$unidade', '$referencia', '$preco_venda', '$valor_total_item','0','$servico_destinado','$tipo_material'  )";
                $operacao_insert = mysqli_query($conecta, $insert);

                $valor_total_item = $tipo_material == "10" ? 0 : $valor_total_item; //produto 10 - ativo imobilizado não contabiliza na os

                $novo_valor_pecas = $valor_pecas_os + $valor_total_item;
                $novo_valor_liquido = $novo_valor_pecas + $valor_servico_os + $valor_despesa_os - $desconto_os;

                $update_valor_pecas =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_valor_pecas", $novo_valor_pecas); //atualizar o valor das peças na os
                $update_valor_liquido =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_valor_liquido", $novo_valor_liquido); //atualizar o valor 

                if ($operacao_insert and $update_valor_pecas and $update_valor_liquido) {
                    $retornar["dados"] =  array("sucesso" => true, "query" => "insert", "title" => "Material incluido com sucesso");
                    $mensagem = utf8_decode("Adicionou o produto $descricao_produto, valor $valor_total_item na $serie_os$numero_os ");
                    registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                    // Se tudo ocorreu bem, confirme a transação
                    mysqli_commit($conecta);
                } else {
                    // Se ocorrer um erro, reverta a transação
                    mysqli_rollback($conecta);
                    $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                    $mensagem = utf8_decode("Tentativa sem sucesso de adicionar o material $descricao_produto, valor $valor_total_item na $serie_os$numero_os ");
                    registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                }
            }
        } else { //alterar

            $codigo_nf = consulta_tabela($conecta, "tb_infraestrutura_os", "cl_id", $item_id, 'cl_codigo_nf'); //codigo nf da os
            $form_id = consulta_tabela($conecta, "tb_os", "cl_codigo_nf", $codigo_nf, 'cl_id'); //id os
            $numero_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_numero_nf');
            $serie_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_serie_nf');
            $parceiro_id = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_parceiro_id');
            $valor_liquido_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_liquido');
            $taxa_adiantamento_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_taxa_adiantamento');
            $desconto_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_desconto');
            $valor_servico_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_servico');
            $valor_pecas_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_pecas');
            $ordem_fechada = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_ordem_fechada');
            $peca_requisitada = consulta_tabela($conecta, "tb_infraestrutura_os", "cl_id", $item_id, 'cl_peca_requisitada');
            $valor_despesa_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_despesa');
            $situacao = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_situacao');

            /*material */
            if ($produto_id == "") { //material avulso não tem no estoque
                $referencia = "";

                $descricao_produto = utf8_decode($descricao_produto);
                $referencia = utf8_decode($referencia);
                $unidade = utf8_decode($unidade);
            } else {
                $unidade_id = consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_und_id"); //unidade_id
                $unidade = (consulta_tabela($conecta, "tb_unidade_medida", "cl_id", $unidade_id, "cl_sigla"));
                $descricao_produto = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_descricao"));
                $referencia = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_referencia"));
            }

            if (verificaVirgula($quantidade)) { //verificar se tem virgula
                $quantidade = formatDecimal($quantidade); // formatar virgula para ponto
            }
            if (verificaVirgula($preco_venda)) { //verificar se tem virgula
                $preco_venda = formatDecimal($preco_venda); // formatar virgula para ponto
            }

            $valor_total_item = $quantidade * $preco_venda;
            $tipo_material = isset($_POST['tipo_material']) ? $_POST['tipo_material'] : '9'; // 9- uso e consumo 10 - ativo imobilizado
            $tipo = $tipo_material == "9" ? "MATERIAL" : 'MATERIALMOBILIZADO';

            if ($form_id == "") {
                $retornar["dados"] = array("sucesso" => false, "title" => "É necessario selecionar o material");
            } elseif ($peca_requisitada == 1) {
                $retornar["dados"] = array("sucesso" => false, "title" => "Não é possível fazer alterações neste material, pois a requisição já foi feita");
            } elseif ($situacao == 1) { //faturada
                $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel alterar o material, a ordem de serviço já está faturada");
            } elseif ($situacao == 2) { //cancelada
                $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel alterar o material, a ordem de serviço já está cancelada");
            } elseif ($descricao_produto == "") {
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("produto"));
            } elseif ($produto_id == "" and $unidade == "") { //produto avulso é necessario informar a unidade 
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("und"));
            } elseif (($valor_total_item) == 0 and $tipo_material == "9") {
                $retornar["dados"] = array("sucesso" => false, "title" => "Valor total do material não pode ser 0, favor, verifique");
            } else {

                $valor_total_item = $tipo_material == "10" ? 0 : $valor_total_item; //produto 10 - ativo imobilizado não contabiliza na os

                $update = "UPDATE `tb_infraestrutura_os` SET
                `cl_tipo` = '$tipo',
                `cl_produto_id` = '$produto_id',
                `cl_item_descricao` = '$descricao_produto',
                `cl_quantidade_orcada` = '$quantidade',
                `cl_unidade` = '$unidade',
                `cl_referencia` = '$referencia',
                `cl_valor_unitario` = '$preco_venda',
                `cl_valor_total` = '$valor_total_item',
                `cl_servico_destinado_id` = '$servico_destinado',
                `cl_tipo_material_id` = '$tipo_material'
                 WHERE cl_id = '$item_id'";
                $operacao_update = mysqli_query($conecta, $update);

                $novo_valor_pecas = valores_total_tabela('tb_infraestrutura_os', 'cl_valor_total', 'cl_codigo_nf', $codigo_nf, 'cl_tipo', 'MATERIAL'); //valor total dos materias apos o update
                $novo_valor_liquido = $novo_valor_pecas + $valor_servico_os + $valor_despesa_os - $desconto_os;

                $update_valor_pecas =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_valor_pecas", $novo_valor_pecas); //atualizar o valor das peças na os
                $update_valor_liquido =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_valor_liquido", $novo_valor_liquido); //atualizar o valor 

                if ($operacao_update and $update_valor_pecas and $update_valor_liquido) {
                    $retornar["dados"] =  array("sucesso" => true, "query" => "update", "title" => "Material alterado com sucesso");
                    $mensagem = utf8_decode("Alterou o produto $descricao_produto, valor $valor_total_item, $serie_os $numero_os ");
                    registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                    // Se tudo ocorreu bem, confirme a transação
                    mysqli_commit($conecta);
                } else {
                    // Se ocorrer um erro, reverta a transação
                    mysqli_rollback($conecta);
                    $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                    $mensagem = utf8_decode("Tentativa sem sucesso de alterar o material $descricao_produto, valor $valor_total_item, $serie_os $numero_os ");
                    registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                }
            }
        }
    }


    if ($acao == "servico") { // incluir serviço
        /*iniciando variavel */
        $parceiro_terceirizado = 0;
        $data_inicio_terceirizado = '';
        $data_fim_terceirizado = '';
        $descricao_terceirizado_terceirizado = '';
        $valor_fechado_terceirizado = 0;

        // Iniciar uma transação MySQL
        mysqli_begin_transaction($conecta);

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            // Remover aspas simples usando str_replace
            ${$name} = str_replace("'", "", ${$name});
        }


        if ($item_id == "") { //incluir
            $codigo_nf = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_codigo_nf');
            $numero_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_numero_nf');
            $serie_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_serie_nf');
            $parceiro_id = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_parceiro_id');
            $valor_liquido_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_liquido');
            $taxa_adiantamento_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_taxa_adiantamento');
            $desconto_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_desconto');
            $valor_servico_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_servico');
            $valor_pecas_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_pecas');
            $ordem_fechada = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_ordem_fechada');
            $valor_despesa_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_despesa');
            $situacao = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_situacao');

            /*servico */
            if ($servico_id == "") { //servico avulso não tem no estoque
                $descricao_servico = ($descricao_servico);
            } else {
                $descricao_servico = (consulta_tabela($conecta, "tb_servicos", "cl_id", $servico_id, "cl_descricao"));
            }

            if (verificaVirgula($quantidade)) { //verificar se tem virgula
                $quantidade = formatDecimal($quantidade); // formatar virgula para ponto
            }
            if (verificaVirgula($valor_unitario)) { //verificar se tem virgula
                $valor_unitario = formatDecimal($valor_unitario); // formatar virgula para ponto
            }
            $valor_total = $quantidade * $valor_unitario;

            if ($form_id == "") {
                $retornar["dados"] = array("sucesso" => false, "title" => "É necessario adicionar a ordem de serviço");
            } elseif ($situacao == 1) { //faturada
                $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel incluir o serviço, a ordem de serviço já está faturada");
            } elseif ($situacao == 2) { //cancelada
                $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel incluir o serviço, a ordem de serviço está cancelada");
            } elseif ($descricao_servico == "") {
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("descrição do serviço"));
            } elseif ($responsavel == 0) {
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("responsável"));
            } elseif (($valor_total) == 0) {
                $retornar["dados"] = array("sucesso" => false, "title" => "Valor total do serviço não pode ser 0, favor, verifique");
            } else {

                $insert = "INSERT INTO `tb_infraestrutura_os` ( `cl_codigo_nf`, `cl_numero_nf`, `cl_serie_nf`, `cl_tipo`, 
            `cl_produto_id`, `cl_item_descricao`, `cl_quantidade_requisitada`, `cl_quantidade_orcada`,
             `cl_unidade`,`cl_valor_unitario`, `cl_valor_total`, `cl_peca_requisitada`,`cl_responsavel`,`cl_parceiro_terceirizado_id`,`cl_data_inicio`,`cl_data_fim`,`cl_valor_fechado`,`cl_descricao_servico`) 
             VALUES ( '$codigo_nf', '$numero_os', '$serie_os', 'SERVICO', '$servico_id',
              '$descricao_servico', '$quantidade', '$quantidade', 'SE',  '$valor_unitario', '$valor_total', '1','$responsavel','$parceiro_terceirizado' ,'$data_inicio_terceirizado' ,'$data_fim_terceirizado' ,'$valor_fechado_terceirizado','$descricao_terceirizado_terceirizado'   )";
                $operacao_insert = mysqli_query($conecta, $insert);
                $servico_novo_id = mysqli_insert_id($conecta);

                $novo_valor_servico = $valor_servico_os + $valor_total;
                $novo_valor_liquido = $valor_pecas_os + $novo_valor_servico + $valor_despesa_os - $desconto_os;

                $update_valor_servico =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_valor_servico", $novo_valor_servico); //atualizar o valor das peças na os
                $update_valor_liquido =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_valor_liquido", $novo_valor_liquido); //atualizar o valor 

                if ($operacao_insert and $update_valor_servico and $update_valor_liquido) {
                    $retornar["dados"] =  array("sucesso" => true, "query" => "insert", "title" => "Serviço incluido com sucesso");
                    $mensagem = utf8_decode("Adicionou o serviço $descricao_servico, valor $valor_total na $serie_os$numero_os ");
                    registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                    if (isset($_POST['nome_equipe_1'])) { //Adicionar os menbros da equipe 
                        for ($i = 1; $i <= 15; $i++) {
                            if (isset($_POST["nome_equipe_$i"])) {
                                $nome_equipe = $_POST["nome_equipe_$i"];
                                $matricula_equipe = $_POST["matricula_equipe_$i"];
                                $funcao_equipe = $_POST["funcao_equipe_$i"];
                                $data_inicio_equipe = $_POST["data_inicio_equipe_$i"];
                                $data_final_equipe = $_POST["data_final_equipe_$i"];
                                $codigo_nf_equipe = $codigo_nf . "_" . $i;
                                if (!empty($nome_equipe) or !empty($matricula_equipe) or !empty($funcao_equipe)) {
                                    $query = "INSERT INTO `tb_equipe_servico` (`cl_codigo_nf`, `cl_nome`, `cl_matricula`,
                                 `cl_funcao`, `cl_data_inicio`, `cl_data_fim`,`cl_servico_id` ) 
                                VALUES ('$codigo_nf_equipe', '$nome_equipe', '$matricula_equipe', '$funcao_equipe', '$data_inicio_equipe', '$data_final_equipe','$servico_novo_id' )";
                                    $insert = mysqli_query($conecta, $query);
                                }
                            }
                        }
                    }

                    // Se tudo ocorreu bem, confirme a transação
                    mysqli_commit($conecta);
                } else {
                    // Se ocorrer um erro, reverta a transação
                    mysqli_rollback($conecta);
                    $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                    $mensagem = utf8_decode("Tentativa sem sucesso de adicionar o serviço $descricao_servico, valor $valor_total na $serie_os$numero_os ");
                    registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                }
            }
        } else { //alterar

            $codigo_nf = consulta_tabela($conecta, "tb_infraestrutura_os", "cl_id", $item_id, 'cl_codigo_nf'); //codigo nf da os
            $form_id = consulta_tabela($conecta, "tb_os", "cl_codigo_nf", $codigo_nf, 'cl_id'); //id os
            $numero_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_numero_nf');
            $serie_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_serie_nf');
            $parceiro_id = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_parceiro_id');
            $valor_liquido_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_liquido');
            $taxa_adiantamento_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_taxa_adiantamento');
            $desconto_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_desconto');
            $valor_servico_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_servico');
            $valor_pecas_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_pecas');
            $ordem_fechada = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_ordem_fechada');
            $peca_requisitada = consulta_tabela($conecta, "tb_infraestrutura_os", "cl_id", $item_id, 'cl_peca_requisitada');
            $valor_despesa_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_despesa');
            $situacao = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_situacao');


            /*servico */
            if ($servico_id == "") { //servico avulso não tem no estoque
                $descricao_servico = ($descricao_servico);
            } else {
                $descricao_servico = (consulta_tabela($conecta, "tb_servicos", "cl_id", $servico_id, "cl_descricao"));
            }

            if (verificaVirgula($quantidade)) { //verificar se tem virgula
                $quantidade = formatDecimal($quantidade); // formatar virgula para ponto
            }
            if (verificaVirgula($valor_unitario)) { //verificar se tem virgula
                $valor_unitario = formatDecimal($valor_unitario); // formatar virgula para ponto
            }
            $valor_total = $quantidade * $valor_unitario;

            if ($form_id == "") {
                $retornar["dados"] = array("sucesso" => false, "title" => "É necessario adicionar a ordem de serviço");
            } elseif ($situacao == 1) { //faturada
                $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel alterar o serviço, a ordem de serviço já está faturada");
            } elseif ($situacao == 2) { //cancelada
                $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel alterar o serviço, a ordem de serviço já está cancelada");
            } elseif ($descricao_servico == "") {
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("serviço"));
            } elseif ($responsavel == 0) {
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("responsável"));
            } elseif (($valor_total) == 0) {
                $retornar["dados"] = array("sucesso" => false, "title" => "Valor total do serviço não pode ser 0, favor, verifique");
            } else {
                $update = "UPDATE `tb_infraestrutura_os` SET
                `cl_tipo` = 'SERVICO',
                `cl_produto_id` = '$servico_id',
                `cl_item_descricao` = '$descricao_servico',
                `cl_quantidade_orcada` = '$quantidade',
                `cl_valor_unitario` = '$valor_unitario',
                `cl_valor_total` = '$valor_total', 
                `cl_responsavel` = '$responsavel',
                `cl_parceiro_terceirizado_id` = '$parceiro_terceirizado',
                `cl_data_inicio` = '$data_inicio_terceirizado',
                `cl_data_fim` = '$data_fim_terceirizado',
                `cl_valor_fechado` = '$valor_fechado_terceirizado',
                `cl_descricao_servico` = '$descricao_terceirizado_terceirizado'
                 WHERE cl_id = '$item_id'";
                $operacao_update = mysqli_query($conecta, $update);

                $novo_valor_servico = valores_total_tabela('tb_infraestrutura_os', 'cl_valor_total', 'cl_codigo_nf', $codigo_nf, 'cl_tipo', 'SERVICO'); //valor total dos materias apos o update
                $novo_valor_liquido = $valor_pecas_os + $novo_valor_servico + $valor_despesa_os - $desconto_os;

                $update_valor_servico =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_valor_servico", $novo_valor_servico); //atualizar o valor do servico na os
                $update_valor_liquido =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_valor_liquido", $novo_valor_liquido); //atualizar o valor 

                if ($operacao_update and $update_valor_servico and $update_valor_liquido) {
                    $retornar["dados"] =  array("sucesso" => true, "query" => "update", "title" => "Serviço alterado com sucesso");
                    $mensagem = utf8_decode("Alterou o serviço $descricao_servico, valor  $valor_total, $serie_os $numero_os ");
                    registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);


                    if (isset($_POST['nome_equipe_1'])) { //Adicionar os menbros da equipe 
                        $query = "DELETE FROM tb_equipe_servico where cl_servico_id = '$item_id'"; //remover todos os menbro para inserir novamente
                        $delete = mysqli_query($conecta, $query);

                        for ($i = 1; $i <= 15; $i++) {
                            if (isset($_POST["nome_equipe_$i"])) {
                                $nome_equipe = $_POST["nome_equipe_$i"];
                                $matricula_equipe = $_POST["matricula_equipe_$i"];
                                $funcao_equipe = $_POST["funcao_equipe_$i"];
                                $data_inicio_equipe = $_POST["data_inicio_equipe_$i"];
                                $data_final_equipe = $_POST["data_final_equipe_$i"];
                                $codigo_nf_equipe = $codigo_nf . "_" . $i;

                                if (!empty($nome_equipe) or !empty($matricula_equipe) or !empty($funcao_equipe)) {
                                    $query = "INSERT INTO `tb_equipe_servico` (`cl_codigo_nf`, `cl_nome`, `cl_matricula`,
                                 `cl_funcao`, `cl_data_inicio`, `cl_data_fim`,`cl_servico_id` ) 
                                VALUES ('$codigo_nf_equipe', '$nome_equipe', '$matricula_equipe', '$funcao_equipe', '$data_inicio_equipe', '$data_final_equipe','$item_id' )";
                                    $insert = mysqli_query($conecta, $query);
                                }
                            }
                        }
                    }


                    // Se tudo ocorreu bem, confirme a transação
                    mysqli_commit($conecta);
                } else {
                    // Se ocorrer um erro, reverta a transação
                    mysqli_rollback($conecta);
                    $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                    $mensagem = utf8_decode("Tentativa sem sucesso de alterar o serviço $descricao_servico, valor $valor_total, $serie_os $numero_os ");
                    registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                }
            }
        }
    }


    if ($acao == "remover_peca") {

        // Iniciar uma transação MySQL
        mysqli_begin_transaction($conecta);

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            // Remover aspas simples usando str_replace
            ${$name} = str_replace("'", "", ${$name});
        }


        $codigo_nf = consulta_tabela($conecta, "tb_infraestrutura_os", "cl_id", $item_id, 'cl_codigo_nf'); //codigo nf da os
        $form_id = consulta_tabela($conecta, "tb_os", "cl_codigo_nf", $codigo_nf, 'cl_id'); //id os
        $numero_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_numero_nf');
        $serie_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_serie_nf');
        $parceiro_id = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_parceiro_id');
        $valor_liquido_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_liquido');
        $taxa_adiantamento_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_taxa_adiantamento');
        $desconto_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_desconto');
        $valor_servico_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_servico');
        $valor_pecas_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_pecas');
        $ordem_fechada = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_ordem_fechada');
        $peca_requisitada = consulta_tabela($conecta, "tb_infraestrutura_os", "cl_id", $item_id, 'cl_peca_requisitada');
        $valor_total_item = consulta_tabela($conecta, "tb_infraestrutura_os", "cl_id", $item_id, 'cl_valor_total');
        $produto_id = consulta_tabela($conecta, "tb_infraestrutura_os", "cl_id", $item_id, 'cl_produto_id');
        $valor_despesa_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_despesa');
        $situacao = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_situacao');

        if ($form_id == "") {
            $retornar["dados"] = array("sucesso" => false, "title" => "É necessario adicionar a ordem de serviço");
        } elseif ($situacao == 1) { //faturada
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel remover o material, a ordem de serviço já está faturada");
        } elseif ($situacao == 2) { //cancelada
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel cancelar a material, a ordem de serviço está cancelada");
        } elseif ($peca_requisitada == 1) {
            $retornar["dados"] = array("sucesso" => false, "title" => 'Não é possivel remover, o material já está requisitado!');
        } else {

            $delete = "DELETE FROM tb_infraestrutura_os where cl_id = '$item_id' ";
            $operacao_delete = mysqli_query($conecta, $delete);

            $novo_valor_pecas = valores_total_tabela('tb_infraestrutura_os', 'cl_valor_total', 'cl_codigo_nf', $codigo_nf, 'cl_tipo', 'MATERIAL'); //valor total dos materias apos o DELETE
            $novo_valor_liquido = $novo_valor_pecas + $valor_servico_os + $valor_despesa_os - $desconto_os;

            $update_valor_pecas =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_valor_pecas", $novo_valor_pecas); //atualizar o valor das peças na os
            $update_valor_liquido =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_valor_liquido", $novo_valor_liquido); //atualizar o valor 

            if ($operacao_delete and $update_valor_pecas and $update_valor_liquido) {
                $retornar["dados"] =  array("sucesso" => true, "query" => "insert", "title" => "Material removido com sucesso");
                $mensagem = utf8_decode("Removeu o produto de código $produto_id da $serie_os$numero_os ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de remover o produto de código $produto_id da $serie_os$numero_os ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }
    if ($acao == "remover_membro_equipe") {
        // Iniciar uma transação MySQL
        mysqli_begin_transaction($conecta);

        $form_id = isset($_POST['form_id']) ? $_POST['form_id'] : '';
        $query = "DELETE FROM tb_equipe_servico where cl_id =$form_id";
        $delete = mysqli_query($conecta, $query);
        if ($delete) {
            $retornar["dados"] =  array("sucesso" => true, "title" => "Membro removido com sucesso");
            mysqli_commit($conecta);
        } else {
            mysqli_rollback($conecta);
            $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor, contatar o suporte");
            $mensagem = utf8_decode("Tentativa sem sucesso de remover um membro de equipe - serviço ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }

    if ($acao == "remover_servico") {

        // Iniciar uma transação MySQL
        mysqli_begin_transaction($conecta);

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            // Remover aspas simples usando str_replace
            ${$name} = str_replace("'", "", ${$name});
        }

        $codigo_nf = consulta_tabela($conecta, "tb_infraestrutura_os", "cl_id", $item_id, 'cl_codigo_nf'); //codigo nf da os
        $form_id = consulta_tabela($conecta, "tb_os", "cl_codigo_nf", $codigo_nf, 'cl_id'); //id os
        $numero_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_numero_nf');
        $serie_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_serie_nf');
        $parceiro_id = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_parceiro_id');
        $valor_liquido_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_liquido');
        $taxa_adiantamento_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_taxa_adiantamento');
        $desconto_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_desconto');
        $valor_servico_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_servico');
        $valor_pecas_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_pecas');
        $ordem_fechada = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_ordem_fechada');
        $peca_requisitada = consulta_tabela($conecta, "tb_infraestrutura_os", "cl_id", $item_id, 'cl_peca_requisitada');
        $valor_total_item = consulta_tabela($conecta, "tb_infraestrutura_os", "cl_id", $item_id, 'cl_valor_total');
        $produto_id = consulta_tabela($conecta, "tb_infraestrutura_os", "cl_id", $item_id, 'cl_produto_id');
        $descricao_item = consulta_tabela($conecta, "tb_infraestrutura_os", "cl_id", $item_id, 'cl_item_descricao');
        $valor_despesa_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_valor_despesa');
        $situacao = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_situacao');
        $valida_servico_destinado = consulta_tabela($conecta, "tb_infraestrutura_os", "cl_servico_destinado_id", $item_id, 'cl_id'); //verificar se existem materiais para o serviço 


        if ($form_id == "") {
            $retornar["dados"] = array("sucesso" => false, "title" => "É necessario adicionar a ordem de serviço");
        } elseif (!empty($valida_servico_destinado)) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possível remover o serviço, pois há materiais vinculados a ele. Por favor, verifique e remova os materiais associados antes de tentar novamente.");
        } elseif ($situacao == 1) { //faturada
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel remover o serviço, a ordem de serviço já está faturada");
        } elseif ($situacao == 2) { //cancelada
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel remover o serviço, a ordem de serviço já está cancelada");
        } else {

            $delete = "DELETE FROM tb_infraestrutura_os where cl_id = '$item_id' ";
            $operacao_delete = mysqli_query($conecta, $delete);

            $novo_valor_servico = valores_total_tabela('tb_infraestrutura_os', 'cl_valor_total', 'cl_codigo_nf', $codigo_nf, 'cl_tipo', 'SERVICO'); //valor total dos materias apos o update

            $novo_valor_liquido = $valor_pecas_os + $novo_valor_servico + $valor_despesa_os - $desconto_os;

            $update_valor_servico =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_valor_servico", $novo_valor_servico); //atualizar o valor das peças na os
            $update_valor_liquido =  update_registro($conecta, "tb_os", "cl_id", $form_id, '', '', "cl_valor_liquido", $novo_valor_liquido); //atualizar o valor 

            if ($operacao_delete and $update_valor_servico and $update_valor_liquido) {
                $retornar["dados"] =  array("sucesso" => true, "query" => "insert", "title" => "Serviço removido com sucesso");
                $mensagem = utf8_decode("Removeu o serviço $item_id, $descricao_item da $serie_os$numero_os ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                //remover as os menbros da equipe destinado ao serviço
                $delete = "DELETE FROM tb_equipe_servico where cl_id = '$item_id' ";
                $operacao_delete = mysqli_query($conecta, $delete);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de remover o serviço $item_id, $descricao_item  da $serie_os$numero_os ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }

    if ($acao == "requisitar_peca") {

        // Iniciar uma transação MySQL
        mysqli_begin_transaction($conecta);

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            // Remover aspas simples usando str_replace
            ${$name} = str_replace("'", "", ${$name});
        }


        $numero_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_numero_nf');
        $serie_nf = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_serie_nf');
        $codigo_nf = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_codigo_nf');
        $ordem_fechada = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_ordem_fechada'); //consulta se a ordem está fechada 1 ou 0
        $situacao = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_situacao');


        // $select  = "SELECT * FROM tb_infraestrutura_os where cl_codigo_nf = '$codigo_nf' ";
        // $consulta_material = mysqli_query($conecta, $select);
        // $qtd_consulta_material = mysqli_num_rows($consulta_material);


        // $select  = "SELECT count(*) as total FROM tb_infraestrutura_os where cl_codigo_nf = '$codigo_nf' and cl_peca_requisitada = 0 ";
        // $consulta_material_nao_requisitado = mysqli_query($conecta, $select);
        // $linha = mysqli_fetch_assoc($consulta_material_nao_requisitado);
        // $qtd_consulta_material_nao_requisitado = $linha['total'];



        $select  = "SELECT
        COUNT(*) AS total,
        COUNT(CASE WHEN cl_peca_requisitada = 0 THEN 1 END) AS totalnaoreq
      FROM tb_infraestrutura_os
      WHERE cl_codigo_nf = '$codigo_nf' ";
        $consulta_material_nao_requisitado = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consulta_material_nao_requisitado);
        $qtd_consulta_material_nao_requisitado = $linha['totalnaoreq'];
        $qtd_consulta_material = $linha['total'];


        if (($qtd_consulta_material) == 0) {
            $retornar["dados"] = array("sucesso" => false, "title" => "É necessario incluir os materiais na ordem de serviço");
        } elseif ($qtd_consulta_material_nao_requisitado == 0) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Todos os materiais já foram requisitados ");
        } elseif ($situacao == 1) { //faturada
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel requisitar, a ordem de serviço já está faturada");
        } elseif ($situacao == 2) { //cancelada
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel requisitar, a ordem de serviço está cancelada");
        } else {
            $requisitar_pecas = requisitar_pecas_os($form_id);
            if ($requisitar_pecas) {
                $msg = $qtd_consulta_material_nao_requisitado > 0 ? 'Alguns materiais estão com estoque zerado, favor, verifique' : 'Materiais requisitados com sucesso';
                $retornar["dados"] =  array("sucesso" => true, "title" => $msg);
                $mensagem = utf8_decode("Requisitou os materiais da $serie_nf $numero_os, obs: mensagem $msg ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de requisitar os materiais da $serie_os$numero_os ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }
    if ($acao == "cancelar_requisicao_peca") {

        // Iniciar uma transação MySQL
        mysqli_begin_transaction($conecta);

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            // Remover aspas simples usando str_replace
            ${$name} = str_replace("'", "", ${$name});
        }


        $numero_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_numero_nf');
        $serie_nf = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_serie_nf');
        $codigo_nf = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_codigo_nf');
        $situacao = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_situacao');

        $select  = "SELECT * FROM tb_infraestrutura_os where cl_codigo_nf = '$codigo_nf' ";
        $consulta_material = mysqli_query($conecta, $select);
        $qtd_consulta_material = mysqli_num_rows($consulta_material);


        $select  = "SELECT count(*) as total FROM tb_infraestrutura_os where cl_codigo_nf = '$codigo_nf' and cl_peca_requisitada = 1 ";
        $consulta_material_requisitado = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consulta_material_requisitado);
        $qtd_consulta_material_requisitado = $linha['total'];

        if (($qtd_consulta_material) == 0) {
            $retornar["dados"] = array("sucesso" => false, "title" => "A ordem de serviço não tem material");
        } elseif ($qtd_consulta_material_requisitado == 0) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Não existem materiais requisitados ");
        } elseif ($situacao == 1) { //faturada
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel cancelar a requisição, a ordem de serviço já está faturada");
        } elseif ($situacao == 2) { //cancelada
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possivel cancelar a requisição, a ordem de serviço está cancelada");
        } else {
            $cancelar_requisicao_pecas = cancelar_requisicao_pecas_os($form_id);
            if ($cancelar_requisicao_pecas) {
                $retornar["dados"] =  array("sucesso" => true, "title" => "Requisição de materiais cancelado com sucesso");
                $mensagem = utf8_decode("Cancelou a requisição dos materiais da $serie_nf $numero_os ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de cancelar a requisição dos materiais da $serie_os$numero_os ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }
    if ($acao == "faturar") {

        // Iniciar uma transação MySQL
        mysqli_begin_transaction($conecta);

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            // Remover aspas simples usando str_replace
            ${$name} = str_replace("'", "", ${$name});
        }

        $numero_os = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_numero_nf');
        $serie_nf = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_serie_nf');
        $teste = ""; // Inicialize a variável fora do foreach

        $os_pendente = isset($_POST['os_pendente']) ? json_decode($_POST['os_pendente']) : [];
        $codigo_nf = md5(uniqid(time())); //gerar um novo codigo para nf

        // foreach ($os_pendente as $ordem) {
        //     $mensagem = utf8_decode("Realizou o faturamento da testre " . $ordem);
        //     registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        // }

        if ($opcao_faturamento == "recibo") {
            if ($recibo == "0") {
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("recibo"));
                echo json_encode($retornar);
                exit;
            }
            $serie_servico_info = $recibo;
            $serie_material_info = $recibo;
        } elseif ($opcao_faturamento == "doc_material_recibo_servico") {
            if ($recibo == "0") {
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("recibo"));
                echo json_encode($retornar);
                exit;
            } elseif ($serie_material == "0") {
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("documento material"));
                echo json_encode($retornar);
                exit;
            }
            $serie_servico_info = $recibo;
            $serie_material_info = $serie_material;
        } elseif ($opcao_faturamento == "doc_servico_recibo_material") {
            if ($recibo == "0") {
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("recibo"));
                echo json_encode($retornar);
                exit;
            } elseif ($serie_servico == "0") {
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("documento serviço"));
                echo json_encode($retornar);
                exit;
            }
            $serie_servico_info = $serie_servico;
            $serie_material_info = $recibo;
        } elseif ($opcao_faturamento == "doc_material_doc_servico") {
            if ($serie_material == "0") {
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("documento material"));
                echo json_encode($retornar);
                exit;
            } elseif ($serie_servico == "0") {
                $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("documento serviço"));
                echo json_encode($retornar);
                exit;
            }
            $serie_servico_info = $serie_servico;
            $serie_material_info = $serie_material;
        }
        if ($forma_pagamento == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("forma pagamento"));
        } elseif ($atividade == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("atividade realizada"));
        } else {
            // $os_pendente = array_map('trim', explode(',', $os_pendente));
            $dados = [
                'os' => $os_pendente,
                'forma_pagamento' => $forma_pagamento,
                'opcao_faturamento' => $opcao_faturamento,
                'atividade' => $atividade,
                'form_id' => $form_id,
                'serie_servico' => $serie_servico_info,
                'serie_material' => $serie_material_info,
                'codigo_nf_novo' => $codigo_nf,
            ];

            $faturamento = faturar_ordem_servico($conecta, $dados);

            if ($faturamento['status'] == true) {
                $retornar["dados"] =  array("sucesso" => true, "title" => $faturamento['mensagem']);
                $mensagem = utf8_decode("Realizou o faturamento da $serie_nf$numero_os, " . $faturamento['mensagem']);
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => $faturamento['mensagem']);
                $mensagem = utf8_decode("Tentativa sem sucesso de faturar a $serie_nf$numero_os ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }

    if ($acao == "remover_nf_faturamento") {
        // Iniciar uma transação MySQL
        mysqli_begin_transaction($conecta);

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            // Remover aspas simples usando str_replace
            ${$name} = str_replace("'", "", ${$name});
        }

        $form_id = isset($_POST['form_id']) ? $_POST['form_id'] : '';
        $codigo_nf = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, "cl_codigo_nf");
        $usuario_id = isset($_POST['usuario_id']) ? $_POST['usuario_id'] : verifica_sessao_usuario();

        $status_os = consulta_tabela($conecta, "tb_os", "cl_codigo_nf", $codigo_nf, "cl_situacao"); //0 - andamento 1 -faturada 2 - cancelada
        $serie_nf = consulta_tabela($conecta, "tb_os", "cl_codigo_nf", $codigo_nf, "cl_serie_nf");
        $numero_nf = consulta_tabela($conecta, "tb_os", "cl_codigo_nf", $codigo_nf, "cl_numero_nf");

        if ($codigo_nf == "") {
            $retornar["dados"] = array("sucesso" => false, "title" => "Ordem de serviço não encontrado, favor verifique");
        } elseif ($status_os == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possível remover a $serie_nf do faturamento, pois ela está em andamento");
        } elseif ($status_os == "2") {
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possível remover a $serie_nf do faturamento, pois ela está em cancelada");
        } elseif ($check_autorizador == "false") {
            $retornar["dados"] =  array("sucesso" => "autorizar", "title" =>  "Continue com a operação autorizando com a senha ");
        } else {
            $remover_faturamento =  remover_nf_os_faturamento($conecta, $codigo_nf, $usuario_id);
            if ($remover_faturamento['status'] == true) {
                $retornar["dados"] = array("sucesso" => true, "title" => $remover_faturamento['response']);
                mysqli_commit($conecta);
            } else {
                $retornar["dados"] = array("sucesso" => false, "title" => $remover_faturamento['response']);
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
            }
        }
    }
    if ($acao == "cancelar_os") {
        // Iniciar uma transação MySQL
        mysqli_begin_transaction($conecta);

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            // Remover aspas simples usando str_replace
            ${$name} = str_replace("'", "", ${$name});
        }

        $form_id = isset($_POST['form_id']) ? $_POST['form_id'] : '';
        $codigo_nf = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, "cl_codigo_nf");

        $status_os = consulta_tabela($conecta, "tb_os", "cl_codigo_nf", $codigo_nf, "cl_situacao"); //0 - andamento 1 -faturada 2 - cancelada
        $serie_nf = consulta_tabela($conecta, "tb_os", "cl_codigo_nf", $codigo_nf, "cl_serie_nf");
        $numero_nf = consulta_tabela($conecta, "tb_os", "cl_codigo_nf", $codigo_nf, "cl_numero_nf");

        $usuario_id  = $_POST['usuario_id'] != '' ? $_POST['usuario_id'] : verifica_sessao_usuario();
        $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");


        if ($codigo_nf == "") {
            $retornar["dados"] = array("sucesso" => false, "title" => "Ordem de serviço não encontrado, favor verifique");
        } elseif ($status_os == "2") {
            $retornar["dados"] = array("sucesso" => false, "title" => "Não é possível cancelar a ordem de serviço, pois ela está cancelada");
        } elseif ($check_autorizador == "false") {
            $retornar["dados"] =  array("sucesso" => "autorizar", "title" =>  "Continue com a operação autorizando com a senha");
        } else {
            $remover_faturamento =  remover_nf_os_faturamento($conecta, $codigo_nf, $usuario_id);
            $cancelar_os =  update_registro($conecta, 'tb_os', 'cl_id', $form_id, '', '', 'cl_situacao', 2);
            if ($remover_faturamento['status'] and $cancelar_os) {
                $retornar["dados"] = array("sucesso" => true, "title" => "$serie_nf$numero_nf cancelada(o) com sucesso");

                $mensagem = utf8_decode("Realizou o cancelamento da ordem de serviço $serie_nf$numero_nf");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                mysqli_commit($conecta);
            } else {
                $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);

                $mensagem = utf8_decode("Tentativa sem sucesso de cancelar a ordem de serviço $serie_nf$numero_nf ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }
    if ($acao == "gerar_doc") {
        $form_id = $_POST['form_id'];
        $tipo = $_POST['tipo'];

        $recibo = $tipo_os == "1" ? 'recibo.php' : 'recibo_2.php';
        $pdf = $tipo_os == "1" ? 'pdf.php' : 'pdf_2.php';
        $codigo_nf = consulta_tabela($conecta, "tb_os", "cl_id", $form_id, 'cl_codigo_nf');


        if ($codigo_nf == "") {
            $retornar["dados"] = array("sucesso" => false, "title" => "Ordem de serviço inexistente, favor, salve a ordem de serviço para prosseguir");
        } else {
            if ($tipo == 'recibo') {

                $caminho =  "view/servico/ordem_servico/documento/$recibo?gerar_doc=true&tipo=recibo&codigo_nf=$codigo_nf";
                $retornar["dados"] = array("sucesso" => true, "title" => $caminho);
            } elseif ($tipo == "pdf") {
                $caminho =  "view/servico/ordem_servico/documento/$pdf?gerar_doc=true&tipo=pdf&codigo_nf=$codigo_nf";
                $retornar["dados"] = array("sucesso" => true, "title" => $caminho);
            } elseif ($tipo == "pdf_detalhado_1") {
                $caminho =  "view/servico/ordem_servico/documento/pdf_detalhado_1.php?gerar_doc=true&tipo=pdf_1&codigo_nf=$codigo_nf";
                $retornar["dados"] = array("sucesso" => true, "title" => $caminho);
            }
        }
    }

    echo json_encode($retornar);
}
