<?php



// //cadastrar formulario
if (isset($_POST['formulario_registro_empresa'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";
   $retornar = array();
   $acao = $_POST['acao'];
   if ($acao == "show") {

      $select = "SELECT * from tb_empresa WHERE cl_id = 1";
      $consultar = mysqli_query($conecta, $select);
      $linha = mysqli_fetch_assoc($consultar);
      $rzaosocial = utf8_encode($linha['cl_razao_social']);
      $nfantasia = utf8_encode($linha['cl_nome_fantasia']);
      $cnpjcpf = ($linha['cl_cnpj']);
      $ie = ($linha['cL_inscricao_estadual']);
      $cep = ($linha['cl_cep']);
      $endereco = utf8_encode($linha['cl_endereco']);
      $cidade = utf8_encode($linha['cl_cidade']);
      $bairro = utf8_encode($linha['cl_bairro']);
      $estado = ($linha['cl_estado']);
      $numero = ($linha['cl_numero']);

      $email = ($linha['cl_email']);
      $telefone = ($linha['cl_telefone']);
      $inscricao_municipal = ($linha['cl_inscricao_municipal']);
      $cnae = ($linha['cl_cnae']);
      $regime_tributario = consulta_tabela($conecta, 'tb_parametros', 'cl_id', 57, 'cl_valor');

      /*api focus */
      $ambiente_focusnfe = consulta_tabela($conecta, 'tb_parametros', 'cl_id', 35, 'cl_valor'); //ambiente 1 homolgoacao 2 - producao
      $token_homologacao_focusnfe = consulta_tabela($conecta, 'tb_parametros', 'cl_id', 58, 'cl_valor'); //homologacao
      $token_producao_focusnfe = consulta_tabela($conecta, 'tb_parametros', 'cl_id', 59, 'cl_valor'); //produtocao


      $informacao = array(
         "rzaosocial" => $rzaosocial,
         "nfantasia" => $nfantasia,
         "cnpjcpf" => $cnpjcpf,
         "rzaosocial" => $rzaosocial,
         "ie" => $ie,
         "cep" => $cep,
         "endereco" => $endereco,
         "cidade" => $cidade,
         "bairro" => $bairro,
         "estado" => $estado,
         "numero" => $numero,
         "email" => $email,
         "telefone" => $telefone,
         "regime_tributario" => $regime_tributario,
         "inscricao_municipal" => $inscricao_municipal,

         "ambiente_focusnfe" => $ambiente_focusnfe,
         "token_homologacao_focusnfe" => $token_homologacao_focusnfe,
         "token_producao_focusnfe" => $token_producao_focusnfe,
         "cnae" => $cnae,
      );
      $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
   }


   if ($acao == "update") { // EDITAR
      $usuario_id = verifica_sessao_usuario();
      $nome_usuario_logado = (consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario"));

      $cidade_ibge = "";
      $rzaosocial = utf8_decode($_POST['rzaosocial']);
      $nfantasia = utf8_decode($_POST['nfantasia']);
      $cnpjcpf = $_POST['cnpjcpf'];
      $ie = $_POST['ie'];
      $cep = $_POST['cep'];
      $endereco = utf8_decode($_POST['endereco']);
      $numero = $_POST['numero'];
      $bairro = utf8_decode($_POST['bairro']);
      $estado = utf8_decode($_POST['estado']);
      $cidade = utf8_decode($_POST['cidade']);
      $telefone = ($_POST['telefone']);
      $email = ($_POST['email']);
      $regime_tributario = ($_POST['regime_tributario']);
      $inscricao_municipal = ($_POST['inscricao_municipal']);
      $flexRadioDefaultfocusnfe = ($_POST['flexRadioDefaultfocusnfe']);
      $token_homologacao_focusnfe = ($_POST['token_homologacao_focusnfe']);
      $token_producao_focusnfe = ($_POST['token_producao_focusnfe']);
      $cnae = ($_POST['cnae']);

      //remover caracteres especias
      $cnpjcpf = preg_replace('/[^0-9]/', '', $cnpjcpf); // remover caracteres especias
      $ie = preg_replace('/[^0-9]/', '', $ie); // remover caracteres especias
      $cep = preg_replace('/[^0-9]/', '', $cep); // remover caracteres especias
      $telefone = preg_replace('/[^0-9]/', '', $telefone); // remover caracteres especias
      $cnae = preg_replace('/[^0-9]/', '', $cnae); // remover caracteres especias

      if ($rzaosocial == "") {
         $retornar["dados"] =  array("sucesso" => "false", "title" => mensagem_alerta_cadastro("Razão social"));
      } elseif ($estado == "") {
         $retornar["dados"] =  array("sucesso" => "false", "title" => mensagem_alerta_cadastro("Estado"));
      } elseif ($cidade == "") {
         $retornar["dados"] =  array("sucesso" => "false", "title" => mensagem_alerta_cadastro("Cidade"));
      } elseif ($regime_tributario == "0") {
         $retornar["dados"] =  array("sucesso" => "false", "title" => mensagem_alerta_cadastro("regime tributário"));
      } elseif ((strlen($cnpjcpf) < 11) and ($cnpjcpf != "")) { //verificar se o campo está preenchido e aquantidade for menor que 11
         $retornar["dados"] = array("sucesso" => false, "title" => "Favor verificque o campo Cnpj \ cpf, a numeração está incorreta");
      } elseif ((!validarCNPJ($cnpjcpf)) and (strlen($cnpjcpf) > 12)) { // validar cnpj
         $retornar["dados"] = array("sucesso" => false, "title" => "Favor verificque o campo Cnpj \ cpf, O cnpj está Incorreto");
      } elseif ((!validaCPF($cnpjcpf)) and (strlen($cnpjcpf) > 0 and strlen($cnpjcpf) <= 11)) { // validar cpf
         $retornar["dados"] = array("sucesso" => false, "title" => "Favor verificque o campo Cnpj \ cpf, O cpf está Incorreto");
      } elseif (($email != "") and (!filter_var($email, FILTER_VALIDATE_EMAIL))) { //validar email
         $retornar["dados"] = array("sucesso" => false, "title" => "Esse email não é valido,Favor verifique");
      } else {


         $resultado_cep = buscar_cep($cep);
         if (isset($resultado_cep["dados"]["sucesso"]) and $resultado_cep["dados"]["sucesso"] == true) {
            $cidade_ibge = $resultado_cep["dados"]["valores"]['ibge'];
         }

         if ($cidade_ibge == "") { //obrigatorio, utilizado na ordem de serviço
            $retornar["dados"] = array("sucesso" => false, "title" => "Código da cidade não foi definido, favor, informe um cep válido");
            echo json_encode($retornar);
            exit;
         }

         for ($i = 1; $i <= 7; $i++) {
            $hora_abertura = $_POST["hra$i"]; //hora de abertura
            $hora_fechamento = $_POST["hrf$i"]; //hora de abertura

            if (isset($_POST["abt$i"])) {
               $funcionamento = "SIM";
            } else {
               $funcionamento = "NAO";
            }
            if (!atualiza_delivery_funcionamento($conecta, $hora_abertura, $hora_fechamento, $funcionamento, $i)) {
               $erro = true;
               break;
            }
         }

         if ($flexRadioDefaultfocusnfe == "focusnfe_homologacao") {
            $ambiente_focusnfe = 1; //homologcao
         } elseif ($flexRadioDefaultfocusnfe == "focusnfe_producao") {
            $ambiente_focusnfe = 2; //producao
         }

         $update = " UPDATE `tb_empresa` SET `cl_razao_social` = '$rzaosocial', `cl_nome_fantasia` = '$nfantasia', `cl_cnpj` = '$cnpjcpf', `cL_inscricao_estadual` = '$ie', `cl_endereco` = '$endereco', 
         `cl_cidade` = '$cidade', `cl_bairro` = '$bairro', `cl_numero` = '$numero', 
         `cl_estado` = '$estado', `cl_cep` = '$cep', `cl_email` = '$email',
          `cl_telefone` = '$telefone',
          `cl_codigo_municipio` = '$cidade_ibge',
          `cl_inscricao_municipal` = '$inscricao_municipal', `cl_cnae` = '$cnae'
            WHERE `tb_empresa`.`cl_id` = 1; ";
         $operacao_update = mysqli_query($conecta, $update);
         if ($operacao_update) {
            update_registro($conecta, 'tb_parametros', 'cl_id', 57, '', '', 'cl_valor', $regime_tributario); //atualizar o crt da empresa

            update_registro($conecta, 'tb_parametros', 'cl_id', 35, '', '', 'cl_valor', $ambiente_focusnfe); //atualizar o ambiente focusnfe
            update_registro($conecta, 'tb_parametros', 'cl_id', 58, '', '', 'cl_valor', $token_homologacao_focusnfe); //atualizar o tokem de homologacao
            update_registro($conecta, 'tb_parametros', 'cl_id', 59, '', '', 'cl_valor', $token_producao_focusnfe); //atualizar o token de producao

            $retornar["dados"] = array("sucesso" => true, "title" => "Registro alterado com sucesso");
            $mensagem =  utf8_decode("Alterou o registro da empresa");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
            $mensagem =  utf8_decode("Tentativa de alterar registro da empresa sem sucesso");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }

   echo json_encode($retornar);
}
