<?php
//consultar informações para tabela
if (isset($_GET['consultar_pedido'])) {

    $acao = $_GET['consultar_pedido'];

    if ($acao == "inicial") {
        include "../../../conexao/conexao.php";
        include "../../../funcao/funcao.php";
        /*Pedidos que estão aguardando confirmação */
        $select = "SELECT nf.cl_id as nfid, nf.cl_valor_liquido,nf.cl_valor_entrega_delivery, nf.cl_codigo_nf, nf.cl_opcao_delivery, cl_status_pedido_delivery, cl_numero_venda,cl_data_pedido_delivery,user.cl_endereco as endereco,user.cl_numero,fpg.cl_descricao as formapgt,stp.cl_descricao as statuspedido FROM tb_nf_saida as nf  
    inner join tb_user_delivery as user on user.cl_id = nf.cl_usuario_id_delivery inner join tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id inner join tb_status_pedido_delivery as stp on stp.cl_id = nf.cl_status_pedido_delivery
    where cl_status_venda ='2' and nf.cl_status_pedido_delivery = '1'  and (cl_solicitar_cancelamento_delivery ='NAO' OR cl_solicitar_cancelamento_delivery ='') order by cl_data_pedido_delivery asc ";
        $consulta_pd_aguardando_confirmacao = mysqli_query($conecta, $select);
        $qtd_consulta_pedido_confirmacao = mysqli_num_rows($consulta_pd_aguardando_confirmacao);
        $qtd_consulta_pedido_confirmacao_topo = mysqli_num_rows($consulta_pd_aguardando_confirmacao);

        /*Pedidos que estão em preparação */
        $select = "SELECT nf.cl_id as nfid,nf.cl_valor_liquido,nf.cl_valor_entrega_delivery, nf.cl_codigo_nf, nf.cl_opcao_delivery, cl_status_pedido_delivery, cl_numero_venda,cl_data_pedido_delivery,user.cl_endereco as endereco,user.cl_numero,fpg.cl_descricao as formapgt,stp.cl_descricao as statuspedido FROM tb_nf_saida as nf  
        inner join tb_user_delivery as user on user.cl_id = nf.cl_usuario_id_delivery inner join tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id inner join tb_status_pedido_delivery as stp on stp.cl_id = nf.cl_status_pedido_delivery
        where cl_status_venda ='2' and nf.cl_status_pedido_delivery = '2'  and (cl_solicitar_cancelamento_delivery ='NAO' 
        OR cl_solicitar_cancelamento_delivery ='') order by cl_data_pedido_delivery asc ";
        $consulta_pd_andamento = mysqli_query($conecta, $select);
        $qtd_consulta_pedido_andamento = mysqli_num_rows($consulta_pd_andamento);

        /*Pedidos que estão aguardando a entrega ou retirada */
        $select = "SELECT nf.cl_id as nfid, nf.cl_valor_liquido,nf.cl_valor_entrega_delivery, nf.cl_codigo_nf, nf.cl_opcao_delivery, cl_status_pedido_delivery, cl_numero_venda,cl_data_pedido_delivery,user.cl_endereco as endereco,user.cl_numero,fpg.cl_descricao as formapgt,stp.cl_descricao as statuspedido FROM tb_nf_saida as nf  
    inner join tb_user_delivery as user on user.cl_id = nf.cl_usuario_id_delivery inner join tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id inner join tb_status_pedido_delivery as stp on stp.cl_id = nf.cl_status_pedido_delivery
    where cl_status_venda ='2' and (nf.cl_status_pedido_delivery = '3' or nf.cl_status_pedido_delivery = '4') order by cl_data_pedido_delivery asc ";
        $consulta_pd_aguardando_entrega = mysqli_query($conecta, $select);
        $qtd_consulta_pedido_aguardando_entrega = mysqli_num_rows($consulta_pd_aguardando_entrega);

        /*pedidos que foram feito solicitações para o cancelamento */
        $select = "SELECT nf.cl_id as nfid, nf.cl_valor_liquido,nf.cl_valor_entrega_delivery, nf.cl_codigo_nf, nf.cl_opcao_delivery, cl_status_pedido_delivery, cl_numero_venda,cl_data_pedido_delivery,user.cl_endereco as endereco,user.cl_numero,fpg.cl_descricao as formapgt,stp.cl_descricao as statuspedido FROM tb_nf_saida as nf  
    inner join tb_user_delivery as user on user.cl_id = nf.cl_usuario_id_delivery inner join tb_forma_pagamento as fpg on fpg.cl_id = nf.cl_forma_pagamento_id inner join tb_status_pedido_delivery as stp on stp.cl_id = nf.cl_status_pedido_delivery
    where cl_status_venda ='2'   and cl_solicitar_cancelamento_delivery ='SIM'  order by cl_data_pedido_delivery asc ";
        $consulta_pd_solicitacao_cancelar = mysqli_query($conecta, $select);
        $qtd_consulta_pd_solicitacao_cancelar = mysqli_num_rows($consulta_pd_solicitacao_cancelar);
        $qtd_consulta_pd_solicitacao_cancelar_topo = mysqli_num_rows($consulta_pd_solicitacao_cancelar);

        $habilitar_automatizacao_tempo = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "45");
        $qtd_minima_pedidos = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "46");
        $tempo_pouca_demanda_pd = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "47");
        $tempo_alta_demanda_pd = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "48");
        if ($habilitar_automatizacao_tempo == "S") {
            $demanda_atual_pedido = verifica_demanda_pedidos($conecta, $data_lancamento) +1;
  
            if ($demanda_atual_pedido > $qtd_minima_pedidos) { //o estabelcimento está com alta demanda de pedidos
                $tempo_entrega = $tempo_alta_demanda_pd;
            } else {
                $tempo_entrega = $tempo_pouca_demanda_pd;
            }
        } else {
            $tempo_entrega = '';
        }
    }

    if ($acao == "detalhado") {
        include "../../../../conexao/conexao.php";
        include "../../../../funcao/funcao.php";


        $select = "SELECT * FROM tb_status_pedido_delivery ";
        $consultar_status_pedido = mysqli_query($conecta, $select);

        $select = "SELECT * FROM tb_empresa ";
        $consultar_empresa = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consultar_empresa);
        $bairro_empresa = utf8_encode($linha['cl_bairro']);
        $cidade_empresa = utf8_encode($linha['cl_cidade']);
        $endereco_empresa = utf8_encode($linha['cl_endereco']);
        $numero_empresa = ($linha['cl_numero']);
        $endereco_maps_empresa = $bairro_empresa . ", " . $endereco_empresa;

        $pedido_id = $_GET['pedido_id'];
        $select = "SELECT nf.cl_status_recebimento, nf.cl_tempo_entrega_pedido,nf.cl_id as nfid,nf.cl_endereco_entrega_delivery,nf.cl_bairro_entrega_delivery,nf.cl_cep_entrega_delivery,nf.cl_numero_casa_delivery,nf.cl_forma_pagamento_id,nf.cl_serie_nf,nf.cl_motivo_cancelamento_delivery,nf.cl_solicitar_cancelamento_delivery,nf.cl_usuario_id_delivery,nf.cl_valor_liquido,nf.cl_valor_entrega_delivery, nf.cl_codigo_nf, nf.cl_opcao_delivery, cl_status_pedido_delivery, 
        cl_numero_venda,cl_data_pedido_delivery,user.cl_endereco as endereco,user.cl_telefone,user.cl_dd_estado,user.cl_numero, fpg.cl_descricao as formapgt,stp.cl_descricao as statuspedido FROM tb_nf_saida as nf  
    inner join tb_user_delivery as user on user.cl_id = nf.cl_usuario_id_delivery inner join tb_forma_pagamento 
    as fpg on fpg.cl_id = nf.cl_forma_pagamento_id inner join tb_status_pedido_delivery as stp on stp.cl_id = nf.cl_status_pedido_delivery 

    where nf.cl_id = '$pedido_id' ";
        $consulta_pedido_detalhado = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consulta_pedido_detalhado);
        $id_pedido = ($linha['nfid']);
        $codigo_nf = ($linha['cl_codigo_nf']);
        $serie_nf = ($linha['cl_serie_nf']);
        $usuario_id = ($linha['cl_usuario_id_delivery']);
        $numero_pedido = ($linha['cl_numero_venda']);
        $metodo_entrega = ($linha['cl_opcao_delivery']);
        $valor_entrega_delivery = ($linha['cl_valor_entrega_delivery']);
        $valor_liquido = ($linha['cl_valor_liquido']);
        $solicitar_cancelamento_delivery = ($linha['cl_solicitar_cancelamento_delivery']);
        $motivo_cancelamento_delivery = utf8_encode($linha['cl_motivo_cancelamento_delivery']);
        $status_id = ($linha['cl_status_pedido_delivery']);
        $forma_pagamento_id = ($linha['cl_forma_pagamento_id']);
        $tempo_entrega_estimado = ($linha['cl_tempo_entrega_pedido']);
        $status_recebimento = ($linha['cl_status_recebimento']);

        $usuario_delivery = (consulta_tabela($conecta, "tb_user_delivery", "cl_id", $usuario_id, "cl_usuario"));



        $telefone = ($linha['cl_telefone']);
        $dd_estado = ($linha['cl_dd_estado']);

        $formapgt = utf8_encode($linha['formapgt']);
        $statuspedido = utf8_encode($linha['statuspedido']);
        $data_pedido_delivery = ($linha['cl_data_pedido_delivery']);


        $endereco = utf8_encode($linha['cl_endereco_entrega_delivery']);
        $numero = ($linha['cl_numero_casa_delivery']);
        $bairro = utf8_encode($linha['cl_bairro_entrega_delivery']);
        $endereco_cliente = $bairro . ", " . $endereco . "," . $numero;
        $endereco_maps = $bairro . ", " . $endereco;

        if ($metodo_entrega == "ENTREGA") {
            $mesagem_alerta_pedido_pronto = utf8_encode(verficar_paramentro($conecta, 'tb_parametros', "cl_id", "37"));    //retirada
        } else {
            $mesagem_alerta_pedido_pronto = utf8_encode(verficar_paramentro($conecta, 'tb_parametros', "cl_id", "38")); //entrega

        }
    }
}




if (isset($_POST['consultar_pedido'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $acao = $_POST['acao'];

    $usuario_id = verifica_sessao_usuario();
    $usuario = (consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario"));

    // Verifica se ação é "update_status" para atualizar o status do pedido
    if ($acao == "update_status") {
        $status = $_POST['status'];
        $pedido_id = $_POST['pedido_id'];

        if (isset($_POST['tempo_entrega'])) {
            $tempo_entrega = $_POST['tempo_entrega'];
        } else {
            $tempo_entrega = '';
        }
        // Obtém o número do pedido a partir do ID do pedido
        $numero_pedido = (consulta_tabela($conecta, "tb_nf_saida", "cl_id", $pedido_id, "cl_numero_venda")); // Número do pedido

        // Obtém a descrição do status selecionado
        $descricao_status = utf8_encode(consulta_tabela($conecta, "tb_status_pedido_delivery", "cl_id", $status, "cl_descricao")); // Descrição do status

        if ($status == "0") {
            // Se nenhum status for selecionado, retorna uma mensagem de erro
            $retornar["dados"] = array("sucesso" => false, "title" => "Por favor, selecione um status para o pedido");
        } else {
            // Inicia a transação
            mysqli_begin_transaction($conecta);
            try {
                if ($status == "1" or $status == "2" or $status == "3" or $status == "4") {
                    // Altera o status do pedido
                    $update = "UPDATE tb_nf_saida SET cl_status_pedido_delivery = '$status', cl_usuario_id='$usuario_id', cl_vendedor_id='$usuario_id' ";
                    if ($tempo_entrega != "") {
                        $update .= ", cl_tempo_entrega_pedido='$tempo_entrega'";
                    }
                    $update .= " WHERE tb_nf_saida.cl_id ='$pedido_id' ";
                    $operacao_update  = mysqli_query($conecta, $update);
                    if ($operacao_update) {
                        // Se a atualização for bem-sucedida, retorna uma mensagem de sucesso e registra a mudança no log
                        $retornar["dados"] = array("sucesso" => true, "title" => "Status alterado com sucesso");
                        $mensagem =  utf8_decode("Usuário $usuario alterou o status do pedido $pedido_id para $descricao_status");
                        registrar_log($conecta, $usuario, $data, $mensagem);
                    } else {
                        // Se a atualização falhar, retorna uma mensagem de erro
                        $retornar["dados"] = array("sucesso" => false, "title" => "Erro, por favor, entre em contato com o suporte técnico");
                    }
                } elseif ($status == "5") {
                    // Define o pedido como entregue
                    $update = "UPDATE tb_nf_saida SET cl_status_pedido_delivery = '$status', cl_status_venda ='1', cl_usuario_id='$usuario_id', cl_vendedor_id='$usuario_id'  WHERE tb_nf_saida.cl_id ='$pedido_id' ";
                    $operacao_update  = mysqli_query($conecta, $update);
                    if ($operacao_update) {
                        // Se a atualização for bem-sucedida, retorna uma mensagem de sucesso e registra a mudança no log
                        $retornar["dados"] = array("sucesso" => true, "title" => "Pedido finalizado com sucesso");
                        $mensagem =  utf8_decode("Usuário $usuario alterou o status do pedido $pedido_id para $descricao_status");
                        registrar_log($conecta, $usuario, $data, $mensagem);
                    } else {
                        // Se a atualização falhar, retorna uma mensagem de erro
                        $retornar["dados"] = array("sucesso" => false, "title" => "Erro, por favor, entre em contato com o suporte técnico");
                    }
                } elseif ($status == "6") {
                    // Cancela o pedido
                    $update = "UPDATE tb_nf_saida SET cl_status_pedido_delivery = '$status', cl_status_venda ='3', cl_usuario_id='$usuario_id',
                 cl_vendedor_id='$usuario_id'  WHERE tb_nf_saida.cl_id ='$pedido_id' ";
                    $operacao_update  = mysqli_query($conecta, $update);
                    if ($operacao_update) {
                        cancelar_produto_nf_delivery($conecta, $pedido_id);
                        // Se o cancelamento for bem-sucedido, retorna uma mensagem de sucesso e registra o cancelamento no log
                        $retornar["dados"] = array("sucesso" => true, "title" => "Pedido cancelado com sucesso");

                        $mensagem =  utf8_decode("Usuário $usuario cancelou o pedido $pedido_id ");
                        registrar_log($conecta, $usuario, $data, $mensagem);
                    } else {
                        // Se o cancelamento falhar, retorna uma mensagem de erro
                        $retornar["dados"] = array("sucesso" => false, "title" => "Erro, por favor, entre em contato com o suporte técnico");
                    }
                } elseif ($status == "7") {
                    // Recusa o pedido
                    $update = "UPDATE tb_nf_saida SET cl_status_pedido_delivery = '$status', 
                cl_status_venda ='3', cl_usuario_id='$usuario_id', cl_vendedor_id='$usuario_id'  
                WHERE tb_nf_saida.cl_id ='$pedido_id' ";
                    $operacao_update  = mysqli_query($conecta, $update);
                    if ($operacao_update) {
                        // Se a recusa for bem-sucedida, retorna uma mensagem de sucesso e registra a recusa no log
                        cancelar_produto_nf_delivery($conecta, $pedido_id);

                        $retornar["dados"] = array("sucesso" => true, "title" => "Pedido recusado com sucesso");
                        $mensagem =  utf8_decode("Usuário $usuario recusou o pedido $pedido_id ");
                        registrar_log($conecta, $usuario, $data, $mensagem);
                    } else {
                        // Se a recusa falhar, retorna uma mensagem de erro
                        $retornar["dados"] = array("sucesso" => false, "title" => "Erro, por favor, entre em contato com o suporte técnico");
                    }
                } elseif ($status == "8") {
                    // aceitar solicitação de cancelamento
                    $update = "UPDATE tb_nf_saida SET cl_status_pedido_delivery = '6', 
                cl_status_venda ='3', cl_usuario_id='$usuario_id', cl_vendedor_id='$usuario_id'  
                WHERE tb_nf_saida.cl_id ='$pedido_id' ";
                    $operacao_update  = mysqli_query($conecta, $update);
                    if ($operacao_update) {
                        // Se a recusa for bem-sucedida, retorna uma mensagem de sucesso e registra a recusa no log
                        cancelar_produto_nf_delivery($conecta, $pedido_id);

                        $retornar["dados"] = array("sucesso" => true, "title" => "Pedido cancelado com sucesso");
                        $mensagem =  utf8_decode("Usuário $usuario aceitou o cancelamento feito pelo cliente, pedido $pedido_id ");
                        registrar_log($conecta, $usuario, $data, $mensagem);
                    } else {
                        // Se a recusa falhar, retorna uma mensagem de erro
                        $retornar["dados"] = array("sucesso" => false, "title" => "Erro, por favor, entre em contato com o suporte técnico");
                    }
                } elseif ($status == "rec_can") { // recusar solicitação de cancelamento feito pelo cliente
                    $update = "UPDATE tb_nf_saida SET cl_status_pedido_delivery = '2', 
                cl_status_venda ='2', cl_solicitar_cancelamento_delivery='', cl_usuario_id='$usuario_id',
                 cl_vendedor_id='$usuario_id'  
                WHERE tb_nf_saida.cl_id ='$pedido_id' ";
                    $operacao_update  = mysqli_query($conecta, $update);
                    if ($operacao_update) {
                        // Se a recusa for bem-sucedida, retorna uma mensagem de sucesso e registra a recusa no log
                        // cancelar_produto_nf_delivery($conecta, $pedido_id);

                        $retornar["dados"] = array("sucesso" => true, "title" => "Cancelamento de Pedido recusado com sucesso");
                        $mensagem =  utf8_decode("Usuário $usuario recusou o cancelamento do cliente, pedido $pedido_id ");
                        registrar_log($conecta, $usuario, $data, $mensagem);
                    } else {
                        // Se a recusa falhar, retorna uma mensagem de erro
                        $retornar["dados"] = array("sucesso" => false, "title" => "Erro, por favor, entre em contato com o suporte técnico");
                    }
                }


                // Comita a transação se todas as operações forem bem-sucedidas
                mysqli_commit($conecta);
            } catch (Exception $e) {
                // Alguma exceção ocorreu, desfaz a transação
                mysqli_rollback($conecta);
                $retornar["dados"] = array("sucesso" => false, "title" => "Erro, por favor, entre em contato com o suporte técnico.");
            }
        }
    }



    echo json_encode($retornar);
}


if (isset($_GET['comanda'])) {


    $pedido_id = $_GET['pedido_id'];

    /*dados da empresa */
    $select = "SELECT  * from tb_empresa where cl_id ='1' ";
    $consultar_empresa = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consultar_empresa);
    $nome_fantasia_empresa = utf8_encode($linha['cl_nome_fantasia']);
    $empresa = utf8_encode($linha['cl_empresa']);
    $cnpj_empresa  = ($linha['cl_cnpj']);
    $endereco_empresa = utf8_encode($linha['cl_endereco']);
    $bairro = utf8_encode($linha['cl_bairro']);
    $numero_empresa = ($linha['cl_numero']);
    $cep_empresa = ($linha['cl_cep']);
    $telefone_empresa = ($linha['cl_telefone']);
    $cidade_empresa =  utf8_encode($linha['cl_cidade']);
    $estado_empresa = ($linha['cl_estado']);
    $endereco_empresa = $bairro . ", " . $endereco_empresa . ", " . $numero_empresa;

    $url_qrdcode = "http://effmax.com.br/$empresa/view/delivery/pedido/comanda/modelo_1.php?comanda=true&pedido_id=$pedido_id";


    $select = "SELECT nf.cl_valor_bruto,nf.cl_valor_desconto, nf.cl_id as nfid,nf.cl_forma_pagamento_id,nf.cl_serie_nf,nf.cl_motivo_cancelamento_delivery,nf.cl_solicitar_cancelamento_delivery,nf.cl_usuario_id_delivery,nf.cl_valor_liquido,nf.cl_valor_entrega_delivery, nf.cl_codigo_nf, nf.cl_opcao_delivery, cl_status_pedido_delivery, 
    cl_numero_venda,cl_data_pedido_delivery,user.cl_endereco as endereco,user.cl_telefone,user.cl_dd_estado,user.cl_numero,frete.cl_bairro as bairro,fpg.cl_descricao as formapgt,stp.cl_descricao as statuspedido FROM tb_nf_saida as nf  
inner join tb_user_delivery as user on user.cl_id = nf.cl_usuario_id_delivery inner join tb_forma_pagamento 
as fpg on fpg.cl_id = nf.cl_forma_pagamento_id inner join tb_status_pedido_delivery as stp on stp.cl_id = nf.cl_status_pedido_delivery 
inner join tb_frete_delivery as frete on frete.cl_id = user.cl_bairro_id 
where nf.cl_id = '$pedido_id' ";
    $consulta_pedido_detalhado = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consulta_pedido_detalhado);
    $id_pedido = ($linha['nfid']);
    $codigo_nf = ($linha['cl_codigo_nf']);
    $serie_nf = ($linha['cl_serie_nf']);
    $usuario_id = ($linha['cl_usuario_id_delivery']);
    $numero_pedido = ($linha['cl_numero_venda']);
    $metodo_entrega = ($linha['cl_opcao_delivery']);
    $valor_entrega_delivery = ($linha['cl_valor_entrega_delivery']);
    $valor_liquido = ($linha['cl_valor_liquido']);
    $solicitar_cancelamento_delivery = ($linha['cl_solicitar_cancelamento_delivery']);
    $motivo_cancelamento_delivery = utf8_encode($linha['cl_motivo_cancelamento_delivery']);
    $status_id = ($linha['cl_status_pedido_delivery']);
    $forma_pagamento_id = ($linha['cl_forma_pagamento_id']);
    $valor_bruto = ($linha['cl_valor_bruto']);
    $desconto = ($linha['cl_valor_desconto']);

    $data_pedido_delivery = formatarTimeStamp($linha['cl_data_pedido_delivery']);
    $endereco = utf8_encode($linha['endereco']);
    $telefone = ($linha['cl_telefone']);
    $dd_estado = ($linha['cl_dd_estado']);
    $numero = ($linha['cl_numero']);
    $formapgt = utf8_encode($linha['formapgt']);
    $statuspedido = utf8_encode($linha['statuspedido']);
    $bairro = utf8_encode($linha['bairro']);
    $endereco_cliente = $bairro . ", " . $endereco . "," . $numero;
    $endereco_maps = $bairro . ", " . $endereco;
}

if (isset($_GET['comanda2'])) {

    $pedido_id = $_GET['pedido_id'];

    /*dados da empresa */
    $select = "SELECT  * from tb_empresa where cl_id ='1' ";
    $consultar_empresa = mysqli_query($conecta, $select);

    $linha = mysqli_fetch_assoc($consultar_empresa);
    $nome_fantasia_empresa = utf8_encode($linha['cl_nome_fantasia']);
    $empresa = utf8_encode($linha['cl_empresa']);
    $cnpj_empresa  = ($linha['cl_cnpj']);
    $endereco_empresa = utf8_encode($linha['cl_endereco']);
    $bairro = utf8_encode($linha['cl_bairro']);
    $numero_empresa = ($linha['cl_numero']);
    $cep_empresa = ($linha['cl_cep']);
    $telefone_empresa = ($linha['cl_telefone']);
    $cidade_empresa =  utf8_encode($linha['cl_cidade']);
    $estado_empresa = ($linha['cl_estado']);
    $endereco_empresa = $bairro . ", " . $endereco_empresa . ", " . $numero_empresa;

    $url_qrdcode = "http://effmax.com.br/$empresa/view/delivery/pedido/comanda/modelo_2.php?comanda=true&pedido_id=$pedido_id";


    $select = "SELECT * FROM tb_nf_saida where cl_id = '$pedido_id' ";
    $consulta_pedido_detalhado = mysqli_query($conecta, $select);
    if (!$consulta_pedido_detalhado) {
        die("Erro na consulta: " . mysqli_error($conecta));
    } else {

        $linha = mysqli_fetch_assoc($consulta_pedido_detalhado);

        $codigo_nf = ($linha['cl_codigo_nf']);
        $serie_nf = ($linha['cl_serie_nf']);
        $usuario_id = ($linha['cl_usuario_id_delivery']);
        $numero_pedido = ($linha['cl_numero_venda']);
        $cliente_avulso = ($linha['cl_parceiro_avulso']);
        $metodo_entrega = ($linha['cl_opcao_delivery']);
        $valor_entrega_delivery = ($linha['cl_valor_entrega_delivery']);
        $valor_liquido = ($linha['cl_valor_liquido']);
        $solicitar_cancelamento_delivery = ($linha['cl_solicitar_cancelamento_delivery']);
        $motivo_cancelamento_delivery = utf8_encode($linha['cl_motivo_cancelamento_delivery']);
        $status_id = ($linha['cl_status_pedido_delivery']);
        $forma_pagamento_id = ($linha['cl_forma_pagamento_id']);
        $valor_bruto = ($linha['cl_valor_bruto']);
        $desconto = ($linha['cl_valor_desconto']);
        $observacao = utf8_encode($linha['cl_observacao']);

        $telefone = utf8_encode(consulta_tabela($conecta, "tb_user_delivery", "cl_id", $usuario_id, "cl_telefone"));
        $dd_telefone = utf8_encode(consulta_tabela($conecta, "tb_user_delivery", "cl_id", $usuario_id, "cl_dd_estado"));

        $formapgt = utf8_encode(consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $forma_pagamento_id, "cl_descricao"));
      
        $usuario_delivery = (consulta_tabela($conecta, "tb_user_delivery", "cl_id", $usuario_id, "cl_usuario"));


        $endereco_entrega_delivery = utf8_encode($linha['cl_endereco_entrega_delivery']);
        $bairro_entrega_delivery = utf8_encode($linha['cl_bairro_entrega_delivery']);
        $cep_entrega_delivery = ($linha['cl_cep_entrega_delivery']);
        $numero_casa_delivery = ($linha['cl_numero_casa_delivery']);

        $data_pedido_delivery = ($linha['cl_data_pedido_delivery']);
        $tempo_entrega_pedido = ($linha['cl_tempo_entrega_pedido']);
        if($cliente_avulso==""){
            $cliente_avulso = "Balcão";
        }
        // if($usuario_id==""){
        //     $telefone ="";
        //     $endereco =
        // }else{

        // }

    }

    // $data_pedido_delivery = formatarTimeStamp($linha['cl_data_pedido_delivery']);
    // $endereco = utf8_encode($linha['endereco']);
    // $telefone = ($linha['cl_telefone']);
    // $dd_estado = ($linha['cl_dd_estado']);
    // $numero = ($linha['cl_numero']);
    // $formapgt = utf8_encode($linha['formapgt']);
    // $statuspedido = utf8_encode($linha['statuspedido']);
    // $bairro = utf8_encode($linha['bairro']);
    // $endereco_cliente = $bairro . ", " . $endereco . "," . $numero;
    // $endereco_maps = $bairro . ", " . $endereco;


}
