<?php

/**contato */
if (isset($_GET['consultar_contato_os'])) { //tabela
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   $usuario_id = verifica_sessao_usuario();
   if (isset($_GET['form_id'])) {
      $form_id = $_GET['form_id'];
   } else {
      $form_id = null;
   }
   $consulta = $_GET['consultar_contato_os'];

   $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");
   $area_usuario = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario_area");
   $tipo_usuario = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_tipo");
   $parceiro_id = $_GET['parceiro_id'];
   $codigo_nf = $_GET['codigo_nf'];

   if ($consulta == "inicial") {
      $select = "SELECT user.cl_usuario, atd.cl_status_id, atd.cl_data_agendamento, prc.cl_razao_social, atd.cl_descricao,atd.cl_id,atd.cl_data,status.cl_descricao as status
       from tb_atendimento as atd left join tb_parceiros as prc on prc.cl_id = atd.cl_parceiro_id 
       inner join tb_status_tarefas as status on status.cl_id = atd.cl_status_id 
       inner join tb_users as user on user.cl_id = atd.cl_usuario_id  where atd.cl_codigo_nf ='$codigo_nf' ";

      $select .= " order by atd.cl_data desc ";
      $consultar_atendimento = mysqli_query($conecta, $select);
      if (!$consultar_atendimento) {
         die("Erro na consulta" . mysqli_error($conecta));
      } else {
         $qtd = mysqli_num_rows($consultar_atendimento);
      }
   } else {
      $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
      $data_inicial = $_GET['data_inicial'];
      $data_final = $_GET['data_final'];
      $data_inicial = ($data_inicial . ' 01:01:01');
      $data_final = ($data_final . ' 23:59:59');

      $select = "SELECT  user.cl_usuario,  atd.cl_status_id, atd.cl_data_agendamento, prc.cl_razao_social, atd.cl_descricao,atd.cl_id,atd.cl_data,status.cl_descricao as status
      from tb_atendimento as atd left join tb_parceiros as prc on prc.cl_id = atd.cl_parceiro_id 
      inner join tb_status_tarefas as status on status.cl_id = atd.cl_status_id inner join tb_users as user on user.cl_id = atd.cl_usuario_id 
      WHERE atd.cl_codigo_nf ='$codigo_nf' and atd.cl_data between '$data_inicial' and '$data_final' and ( atd.cl_descricao like '%{$pesquisa}%' or user.cl_usuario like '%{$pesquisa}%' or prc.cl_razao_social like '%{$pesquisa}%') ";
      $select .= " order by atd.cl_data desc ";
      $consultar_atendimento = mysqli_query($conecta, $select);
      if (!$consultar_atendimento) {
         die("Erro na consulta" . mysqli_error($conecta));
      } else {
         $qtd = mysqli_num_rows($consultar_atendimento);
      }
   }
}


if (isset($_GET['atendimento_tela']) or isset($_GET['consultar_contato'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";
   $usuario_id = verifica_sessao_usuario();
   if (isset($_GET['form_id'])) {
      $form_id = $_GET['form_id'];
   } else {
      $form_id = null;
   }
   if (isset($_GET['parceiro_id'])) {
      $parceiro_id = $_GET['parceiro_id'];
   } else {
      $parceiro_id = '';
   }

   if (isset($_GET['codigo_nf'])) {
      $codigo_nf = $_GET['codigo_nf'];
   } else {
      $codigo_nf = '';
   }
}



//consultar informações para tabela
if (isset($_GET['consultar_atendimento'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   $consulta = $_GET['consultar_atendimento'];
   $usuario_id = verifica_sessao_usuario();
   $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");
   $area_usuario = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario_area");
   $tipo_usuario = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_tipo");

   if ($consulta == "inicial") {
      $select = "SELECT user.cl_usuario, atd.cl_status_id, atd.cl_data_agendamento, prc.cl_razao_social, atd.cl_descricao,atd.cl_id,atd.cl_data,status.cl_descricao as status
       from tb_atendimento as atd left join tb_parceiros as prc on prc.cl_id = atd.cl_parceiro_id 
       inner join tb_status_tarefas as status on status.cl_id = atd.cl_status_id inner join tb_users as user on user.cl_id = atd.cl_usuario_id where atd.cl_status_id != '3' ";
      if ($area_usuario != "GERENTE" or $tipo_usuario == "usuario") {
         $select .= " and (atd.cl_visualizar = 'T' or atd.cl_usuario_id = '$usuario_id' ) ";
      } 

      $select .= " order by atd.cl_data desc ";
      $consultar_atendimento = mysqli_query($conecta, $select);
      if (!$consultar_atendimento) {
         die("Erro na consulta" . mysqli_error($conecta));
      } else {
         $qtd = mysqli_num_rows($consultar_atendimento);
      }
   } else {
      $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
      $data_inicial = $_GET['data_inicial'];
      $data_final = $_GET['data_final'];
      $status = ($_GET['status']); //filtro

      $data_inicial = ($data_inicial . ' 01:01:01');
      $data_final = ($data_final . ' 23:59:59');

      $select = "SELECT  user.cl_usuario,  atd.cl_status_id, atd.cl_data_agendamento, prc.cl_razao_social, atd.cl_descricao,atd.cl_id,atd.cl_data,status.cl_descricao as status
      from tb_atendimento as atd left join tb_parceiros as prc on prc.cl_id = atd.cl_parceiro_id 
      inner join tb_status_tarefas as status on status.cl_id = atd.cl_status_id inner join tb_users as user on user.cl_id = atd.cl_usuario_id 
      WHERE atd.cl_data between '$data_inicial' and '$data_final' and ( atd.cl_descricao like '%{$pesquisa}%' or user.cl_usuario like '%{$pesquisa}%' or prc.cl_razao_social like '%{$pesquisa}%') ";
      if ($area_usuario != "GERENTE" or $tipo_usuario == "usuario") {
         $select .= " and (atd.cl_visualizar = 'T' or atd.cl_usuario_id = '$usuario_id' ) ";
      }
      if ($status != "0") {
         $select .= " and atd.cl_status_id = '$status' ";
      } else {
         $select .= " and atd.cl_status_id !='3' ";
      }
      $select .= " order by atd.cl_data desc ";
      $consultar_atendimento = mysqli_query($conecta, $select);
      if (!$consultar_atendimento) {
         die("Erro na consulta" . mysqli_error($conecta));
      } else {
         $qtd = mysqli_num_rows($consultar_atendimento);
      }
   }
}

// //cadastrar formulario
if (isset($_POST['formulario_atendimento'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";
   $retornar = array();
   $acao = $_POST['acao'];
   $usuario_id = verifica_sessao_usuario();
   $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");
   if ($acao == "show") {
      $id = $_POST['form_id'];
      $select = "SELECT * from tb_atendimento WHERE cl_id = $id";
      $consultar = mysqli_query($conecta, $select);
      $linha = mysqli_fetch_assoc($consultar);
      $usuario_id = ($linha['cl_usuario_id']);
      $parceiro_id = ($linha['cl_parceiro_id']);
      $data_agendamento = ($linha['cl_data_agendamento']);
      $visualizar = ($linha['cl_visualizar']);
      $status_id = ($linha['cl_status_id']);
      $descricao = utf8_encode($linha['cl_descricao']);
      $codigo_nf = ($linha['cl_codigo_nf']);

      $informacao = array(
         "usuario_id" => $usuario_id,
         "parceiro_id" => $parceiro_id,
         "data_agendamento" => $data_agendamento,
         "visualizar" => $visualizar,
         "status_id" => $status_id,
         "descricao" => $descricao,
         "codigo_nf" => $codigo_nf,
      );

      $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
   }


   if ($acao == "create") {;
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);

      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = $value;
      }

      $descricao = utf8_decode($_POST['descricao']);
      if ($descricao == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("descrição"));
      } elseif ($status == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("status"));
      } elseif ($atendente == '0') {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("atendente"));
      } else {
         $insert = "INSERT INTO `tb_atendimento` (`cl_usuario_id`, `cl_parceiro_id`, `cl_descricao`, `cl_data`,
          `cl_status_id`, `cl_data_agendamento`, `cl_visualizar`,`cl_codigo_nf`) 
         VALUES ( '$atendente', '$parceiro', '$descricao', '$data', '$status', '$agendar', '$visualizacao','$codigo_nf' )";
         $operacao_insert = mysqli_query($conecta, $insert);
         if ($operacao_insert) {
            $novo_id_inserido = mysqli_insert_id($conecta);

            $retornar["dados"] =  array("sucesso" => true, "title" => "Atendimento Registrado com sucesso");
            $mensagem = utf8_decode("Adicionou um atendimento de código $novo_id_inserido");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

            // Se tudo ocorreu bem, confirme a transação
            mysqli_commit($conecta);
         } else {
            // Se ocorrer um erro, reverta a transação
            mysqli_rollback($conecta);
            $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
            $mensagem = utf8_decode("Tentativa sem sucesso de adicionar um atendimento ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }


   if ($acao == "update") { // EDITAR

      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);

      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = $value;
      }

      $descricao = utf8_decode($_POST['descricao']);
      if ($descricao == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("descrição"));
      } elseif ($status == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("status"));
      } elseif ($atendente == '0') {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("atendente"));
      } else {
         $update = "UPDATE `tb_atendimento` SET `cl_usuario_id` = '$atendente', `cl_parceiro_id` = '$parceiro',
          `cl_descricao` = '$descricao',`cl_status_id` = '$status',
           `cl_data_agendamento` = '$agendar', `cl_visualizar` = '$visualizacao' WHERE`cl_id` = $id ";
         $operacao_update = mysqli_query($conecta, $update);
         if ($operacao_update) {
            $retornar["dados"] =  array("sucesso" => true, "title" => "Atendimento alterado com sucesso");
            $mensagem = utf8_decode("Alterou um atendimento de código $id");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

            // Se tudo ocorreu bem, confirme a transação
            mysqli_commit($conecta);
         } else {
            // Se ocorrer um erro, reverta a transação
            mysqli_rollback($conecta);
            $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
            $mensagem = utf8_decode("Tentativa sem sucesso de alterar um atendimento de código $id ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }

   if ($acao == "delete") { // remover
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);

      $id = $_POST['form_id'];

      $delete = "DELETE FROM tb_atendimento WHERE `cl_id` = $id ";
      $operacao_delete = mysqli_query($conecta, $delete);
      if ($operacao_delete) {
         $retornar["dados"] =  array("sucesso" => true, "title" => "Atendimento $id removido com sucesso");
         $mensagem = utf8_decode("Removeu o atendimento, código $id");
         registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

         // Se tudo ocorreu bem, confirme a transação
         mysqli_commit($conecta);
      } else {
         // Se ocorrer um erro, reverta a transação
         mysqli_rollback($conecta);
         $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
         $mensagem = utf8_decode("Tentativa sem sucesso de remover o atendimento de código $id ");
         registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
      }
   }
   echo json_encode($retornar);
}
