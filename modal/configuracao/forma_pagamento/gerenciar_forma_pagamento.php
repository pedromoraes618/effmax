<?php

//consultar informações para tabela
if (isset($_GET['consultar_fpg'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   $consulta = $_GET['consultar_fpg'];
   if ($consulta == "inicial") {
      $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1
      $select = "SELECT fpg.cl_id as formaPagamentoID, fpg.cl_ativo,fpg.cl_descricao as formaPagamentoDescricao,fpg.cl_default,ctf.cl_banco,tpg.cl_descricao as tipoPagamento,strc.cl_descricao as statusRecebimento from 
      tb_forma_pagamento as fpg inner join tb_conta_financeira as ctf on ctf.cl_conta = fpg.cl_conta_financeira
      inner join tb_status_recebimento as strc on strc.cl_id = fpg.cl_status_id inner join tb_tipo_pagamento as tpg on tpg.cl_id = fpg.cl_tipo_pagamento_id order by fpg.cl_id ";
      $consultar_forma_pagamento = mysqli_query($conecta, $select);
      if (!$consultar_forma_pagamento) {
         die("Falha no banco de dados");
      } else {
         $qtd = mysqli_num_rows($consultar_forma_pagamento);
      }
   } else {
      $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
      $select = "SELECT fpg.cl_id as formaPagamentoID, fpg.cl_ativo,fpg.cl_descricao as formaPagamentoDescricao,fpg.cl_default,ctf.cl_banco,tpg.cl_descricao as tipoPagamento,strc.cl_descricao as statusRecebimento from 
      tb_forma_pagamento as fpg inner join tb_conta_financeira as ctf on ctf.cl_conta = fpg.cl_conta_financeira
      inner join tb_status_recebimento as strc on strc.cl_id = fpg.cl_status_id inner join tb_tipo_pagamento as tpg on tpg.cl_id = fpg.cl_tipo_pagamento_id 
      WHERE fpg.cl_descricao like '%{$pesquisa}%' or fpg.cl_id like '%{$pesquisa}%' order by fpg.cl_id";
      $consultar_forma_pagamento = mysqli_query($conecta, $select);
      if (!$consultar_forma_pagamento) {
         die("Falha no banco de dados");
      } else {
         $qtd = mysqli_num_rows($consultar_forma_pagamento);
      }
   }
}

// //cadastrar formulario
if (isset($_POST['formulario_forma_pagamento'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";
   $retornar = array();
   $acao = $_POST['acao'];
   $id_user_logado = verifica_sessao_usuario();
   $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $id_user_logado, "cl_usuario");



   if ($acao == "show") {
      $id_forma_pagamento = $_POST['forma_pagamento_id'];
      $select = "SELECT * from tb_forma_pagamento WHERE cl_id = $id_forma_pagamento";
      $consultar_forma_pagamento = mysqli_query($conecta, $select);
      $linha = mysqli_fetch_assoc($consultar_forma_pagamento);
      $descricao = utf8_encode($linha['cl_descricao']);
      $conta_financeira = $linha['cl_conta_financeira'];
      $status_recebimento = $linha['cl_status_id'];
      $classificao = $linha['cl_classificao_id'];
      $tipo_pagamento = $linha['cl_tipo_pagamento_id'];
      $avista = $linha['cl_avista'];
      $default = $linha['cl_default'];
      $prazo_fatura = $linha['cl_prazo_fatura'];
      $numero_parcela = $linha['cl_numero_parcela'];
      $intervalo_parcela = $linha['cl_intervalo_parcela'];
      $desconto_maximo = $linha['cl_desconto_maximo'];
      $taxa = $linha['cl_taxa'];
      $ativo = $linha['cl_ativo'];
      $ativo_delivery = $linha['cl_ativo_delivery'];
      $tipo_pagamento_nf = $linha['cl_tipo_pagamento_nf'];
      $tipo_taxa = $linha['cl_tipo_taxa'];
      $informacao = array(
         "descricao" => $descricao,
         "conta_financeira" => $conta_financeira,
         "status_recebimento" => $status_recebimento,
         "classficacao" => $classificao,
         "tipo_pagamento" => $tipo_pagamento,
         "avista" => $avista,
         "default" => $default,
         "prazo_fatura" => $prazo_fatura,
         "numero_parcela" => $numero_parcela,
         "intervalo_parcela" => $intervalo_parcela,
         "desconto_maximo" => $desconto_maximo,
         "taxa" => $taxa,
         "ativo" => $ativo,
         "delivery" => $ativo_delivery,
         "tipo_pagamento_nf" => $tipo_pagamento_nf,
         "tipo_taxa" => $tipo_taxa,
      );
      $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
   }


   if ($acao == "create") {

      $descricao = utf8_decode($_POST['descricao']);
      $conta_financeira = $_POST['conta_financeira'];
      $status = $_POST['status'];
      $classificacao = $_POST['classificacao'];
      $tipo_pagamento = $_POST['tipo_pagamento'];
      $numero_parcela = $_POST['numero_parcela'];
      $prazo_fatura = $_POST['prazo_fatura'];
      $intervalo_parcela = $_POST['intervalo_parcela'];
      $desconto_maximo = $_POST['desconto_maximo'];
      $taxa = $_POST['taxa'];
      $tipo_pagamento_nf = $_POST['tipo_pagamento_nf'];
      $tipo_taxa = $_POST['tipo_taxa'];

      if (isset($_POST['ativo'])) {
         $ativo = 'S';
      } else {
         $ativo = 'N';
      }

      if (isset($_POST['default'])) {
         $default = 'S';
      } else {
         $default = 'N';
      }

      if (isset($_POST['avista'])) {
         $avista = 'S';
      } else {
         $avista = 'N';
      }
      if (isset($_POST['delivery'])) {
         $delivery = 'S';
      } else {
         $delivery = 'N';
      }



      if ($descricao == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("descricão"));
      } elseif ($conta_financeira == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("conta financeira"));
      } elseif ($status == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("status"));
      } elseif ($classificacao == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("classificacao"));
      } elseif ($tipo_pagamento == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("tipo pagamento"));
      } else {

         $insert = "INSERT INTO `tb_forma_pagamento` (`cl_descricao`, `cl_conta_financeira`, `cl_status_id`, `cl_classificao_id`, `cl_tipo_pagamento_id`, `cl_avista`, `cl_default`, 
         `cl_prazo_fatura`, `cl_numero_parcela`, `cl_intervalo_parcela`, `cl_desconto_maximo`, `cl_taxa`, `cl_ativo`,
          `cl_ativo_delivery`,`cl_tipo_pagamento_nf`, `cl_tipo_taxa`)
         VALUES ( '$descricao', '$conta_financeira', '$status', '$classificacao', '$tipo_pagamento', '$avista', '$default',
          '$prazo_fatura', '$numero_parcela', '$intervalo_parcela', '$desconto_maximo', '$taxa', '$ativo','$delivery' ,
          '$tipo_pagamento_nf', '$tipo_taxa' )";
         $operacao_insert = mysqli_query($conecta, $insert);
         if ($operacao_insert) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Forma de pagamento cadastrado com sucesso");
            $mensagem =  (utf8_decode("Usúario") . "Adicionou a forma de pagamento $descricao");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
            $mensagem =  utf8_decode("tentativa de adicionar a forma de pagamento $descricao sem sucesso");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }


   if ($acao == "update") { // EDITAR

      $id = $_POST['id'];
      $descricao = utf8_decode($_POST['descricao']);
      $conta_financeira = $_POST['conta_financeira'];
      $status = $_POST['status'];
      $classificacao = $_POST['classificacao'];
      $tipo_pagamento = $_POST['tipo_pagamento'];
      $numero_parcela = $_POST['numero_parcela'];
      $prazo_fatura = $_POST['prazo_fatura'];
      $intervalo_parcela = $_POST['intervalo_parcela'];
      $desconto_maximo = $_POST['desconto_maximo'];
      $taxa = $_POST['taxa'];
      $tipo_pagamento_nf = $_POST['tipo_pagamento_nf'];
      $tipo_taxa = $_POST['tipo_taxa'];
      
      if (isset($_POST['ativo'])) {
         $ativo = 'S';
      } else {
         $ativo = 'N';
      }

      if (isset($_POST['default'])) {
         $default = 'S';
      } else {
         $default = 'N';
      }

      if (isset($_POST['avista'])) {
         $avista = 'S';
      } else {
         $avista = 'N';
      }
      if (isset($_POST['delivery'])) {
         $delivery = 'S';
      } else {
         $delivery = 'N';
      }


      if ($descricao == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("descricão"));
      } elseif ($conta_financeira == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("conta financeira"));
      } elseif ($status == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("status"));
      } elseif ($classificacao == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("classificacao"));
      } elseif ($tipo_pagamento == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("tipo pagamento"));
      } else {

         $update = " UPDATE `tb_forma_pagamento` SET `cl_descricao` = '$descricao', `cl_conta_financeira` = '$conta_financeira', `cl_status_id` = '$status', `cl_classificao_id` = '$classificacao', `cl_tipo_pagamento_id` = '$tipo_pagamento',
          `cl_avista` = '$avista', `cl_default` = '$default', `cl_prazo_fatura` = '$prazo_fatura', `cl_numero_parcela` = '$numero_parcela', `cl_intervalo_parcela` = '$intervalo_parcela',
           `cl_desconto_maximo` = '$desconto_maximo', `cl_taxa` = '$taxa', `cl_ativo` = '$ativo' ,
            `cl_ativo_delivery` = '$delivery',`cl_tipo_pagamento_nf` = '$tipo_pagamento_nf',`cl_tipo_taxa` = '$tipo_taxa' WHERE `cl_id` = $id ";
         $operacao_update = mysqli_query($conecta, $update);
         if ($operacao_update) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Forma de pagamento alterada com sucesso");
            $mensagem =  utf8_decode("Usuário $nome_usuario_logado, alterou a forma de pagamento de código $id");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
            $mensagem =  utf8_decode("Usuário $nome_usuario_logado, tentativa de alterar a forma de pagamento de código $id");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }

   echo json_encode($retornar);
}
