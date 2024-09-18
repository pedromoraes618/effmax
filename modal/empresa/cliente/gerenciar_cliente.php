<?php

//consultar informações para tabela
if (isset($_GET['consultar_cliente'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $consulta = $_GET['consultar_cliente'];


    if ($consulta == "inicial") {
        // $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1
        $consultar_tabela_inicialmente = "N";
        $select = "SELECT clt.cl_id,clt.cl_bairro,cid.cl_nome as cidade,clt.cl_email,clt.cl_situacao_ativo,clt.cl_nome_fantasia,clt.cl_razao_social,clt.cl_cnpj_cpf,est.cl_uf from tb_parceiros as clt
            inner join tb_estados as est on clt.cl_estado_id = est.cl_id inner join tb_cidades as cid on clt.cl_cidade_id
            = cid.cl_id";
        $consultar_clientes = mysqli_query($conecta, $select);
        if (!$consultar_clientes) {
            die("Falha no banco de dados");
        } else {
            $qtd = mysqli_num_rows($consultar_clientes);
        }
    } else {
        $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
        if (isset($_GET['status'])) {
            $status_pesquisa = $_GET['status'];
        }
        $remove_chars = array('.', '/', '-');
        $pesquisa = str_replace(($remove_chars), '', $pesquisa); // remover caracteres especias
        $select = "SELECT clt.cl_id,clt.cl_bairro,cid.cl_nome as cidade,clt.cl_email,clt.cl_situacao_ativo,clt.cl_razao_social,clt.cl_nome_fantasia,clt.cl_cnpj_cpf,est.cl_uf from tb_parceiros as clt
            inner join tb_estados as est on clt.cl_estado_id = est.cl_id inner join tb_cidades as cid on clt.cl_cidade_id
            = cid.cl_id where (clt.cl_razao_social LIKE '%{$pesquisa}%' or clt.cl_nome_fantasia LIKE '%{$pesquisa}%' or clt.cl_cnpj_cpf LIKE '%{$pesquisa}%' or clt.cl_id ='$pesquisa') ";
        if (isset($status_pesquisa) and $status_pesquisa != "0") {
            $select .= " and clt.cl_situacao_ativo = '$status_pesquisa' ";
        }
        $select .= " ORDER BY clt.cl_id ";

        $consultar_clientes = mysqli_query($conecta, $select);
        if (!$consultar_clientes) {
            die("Falha no banco de dados");
        } else {
            $qtd = mysqli_num_rows($consultar_clientes);
        }
    }
}

//cadastrar formulario
if (isset($_POST['formulario_cadastrar_cliente'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $nome_usuario_logado = $_POST["nome_usuario_logado"];
    $id_usuario_logado = $_POST["id_usuario_logado"];
    $perfil_usuario_logado = $_POST['perfil_usuario_logado'];


    $rzaosocial = utf8_decode($_POST["rzaosocial"]);
    $nfantasia = utf8_decode($_POST["nfantasia"]);
    $ie = ($_POST["ie"]);
    $cep = ($_POST["cep"]);
    $bairro = utf8_decode($_POST["bairro"]);
    $endereco = utf8_decode($_POST["endereco"]);
    $numero = utf8_decode($_POST["numero"]);
    $estado = ($_POST["estado"]);
    $cidade = isset($_POST['cidade']) ? $_POST['cidade'] : 0;
    $telefone = ($_POST["telefone"]);
    $email = ($_POST["email"]);
    $observacao = utf8_decode($_POST["observacao"]);
    $cnpjcpf = ($_POST["cnpjcpf"]);

    //remover caracteres especias
    $cnpjcpf = preg_replace('/[^0-9]/', '', $cnpjcpf); // remover caracteres especias
    $ie = preg_replace('/[^0-9]/', '', $ie); // remover caracteres especias
    $cep = preg_replace('/[^0-9]/', '', $cep); // remover caracteres especias
    $rzaosocial = str_replace("'", "", $rzaosocial);
    $endereco = str_replace("'", "", $endereco);
    $bairro = str_replace("'", "", $bairro);
    $nfantasia = str_replace("'", "", $nfantasia);
    $observacao = str_replace("'", "", $observacao);

    if ($rzaosocial == "") {
        $retornar["dados"] =  array("sucesso" => "false", "title" => mensagem_alerta_cadastro("Razão social"));
    } elseif ($estado == "0") {
        $retornar["dados"] =  array("sucesso" => "false", "title" => mensagem_alerta_cadastro("Estado"));
    } elseif ($cidade == "0") {
        $retornar["dados"] =  array("sucesso" => "false", "title" => mensagem_alerta_cadastro("Cidade"));
    } elseif ((strlen($cnpjcpf) < 11) and ($cnpjcpf != "")) { //verificar se o campo está preenchido e aquantidade for menor que 11
        $retornar["dados"] = array("sucesso" => false, "title" => "Favor verificque o campo Cnpj \ cpf, a numeração está incorreta");
    } elseif ((!validarCNPJ($cnpjcpf)) and (strlen($cnpjcpf) > 12)) { // validar cnpj
        $retornar["dados"] = array("sucesso" => false, "title" => "Favor verificque o campo Cnpj \ cpf, O cnpj está Incorreto");
    } elseif ((!validaCPF($cnpjcpf)) and (strlen($cnpjcpf) > 0 and strlen($cnpjcpf) <= 11)) { // validar cpf
        $retornar["dados"] = array("sucesso" => false, "title" => "Favor verificque o campo Cnpj \ cpf, O cpf está Incorreto");
    } elseif (consulta_tabela($conecta, "tb_parceiros", "cl_cnpj_cpf", $cnpjcpf, "cl_cnpj_cpf") > 0) {
        $retornar["dados"] = array("sucesso" => false, "title" => "Já existe um cadastrado com esses dados, favor verifique o campo Cnpj/Cpf");
    } elseif (($email != "") and (!filter_var($email, FILTER_VALIDATE_EMAIL))) { //validar email
        $retornar["dados"] = array("sucesso" => false, "title" => "Esse email não é valido,Favor verifique");
    } else {
        $insert = "INSERT INTO `tb_parceiros` ( `cl_data_cadastro`, `cl_usuario_id`, `cl_razao_social`, 
        `cl_nome_fantasia`, `cl_cnpj_cpf`, `cl_inscricao_estadual`, `cl_cep`, `cl_bairro`, `cl_endereco`,`cl_numero`, `cl_cidade_id`, 
        `cl_estado_id`, `cl_pais`, `cl_telefone`, `cl_email`, `cl_observacao`, `cl_situacao_ativo`) VALUES 
        ('$data_lancamento', '$id_usuario_logado', '$rzaosocial', '$nfantasia', '$cnpjcpf', '$ie', '$cep', '$bairro',
         '$endereco', '$numero', '$cidade', '$estado', 'BRASIL', '$telefone', '$email', '$observacao', 'SIM')";
        $operacao_inserir = mysqli_query($conecta, $insert);
        if ($operacao_inserir) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Cadastrado realizado com sucesso");
            $mensagem =  utf8_decode("Usuário $nome_usuario_logado cadastrou o parceiro $rzaosocial");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }
    echo json_encode($retornar);
}



// //editar formulario
if (isset($_POST['formulario_editar_cliente'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $nome_usuario_logado = $_POST["nome_usuario_logado"];
    $id_usuario_logado = $_POST["id_usuario_logado"];
    $perfil_usuario_logado = $_POST['perfil_usuario_logado'];

    $id_cliente = $_POST['id_cliente'];
    $rzaosocial = utf8_decode($_POST["rzaosocial"]);
    $nfantasia = utf8_decode($_POST["nfantasia"]);
    $ie = ($_POST["ie"]);
    $cep = ($_POST["cep"]);
    $bairro = utf8_decode($_POST["bairro"]);
    $endereco = utf8_decode($_POST["endereco"]);
    $estado = ($_POST["estado"]);
    $cidade = isset($_POST['cidade']) ? $_POST['cidade'] : 0; // verificar se o post de cidade existe
    $telefone = ($_POST["telefone"]);
    $email = ($_POST["email"]);
    $observacao = utf8_decode($_POST["observacao"]);
    $cnpjcpf = ($_POST["cnpjcpf"]);
    $situacao = ($_POST["situacao"]);
    $numero = utf8_decode($_POST["numero"]);
    //remover caracteres especias
    $cnpjcpf = preg_replace('/[^0-9]/', '', $cnpjcpf); // remover caracteres especias
    $ie = preg_replace('/[^0-9]/', '', $ie); // remover caracteres especias
    $cep = preg_replace('/[^0-9]/', '', $cep); // remover caracteres especias
    $rzaosocial = str_replace("'", "", $rzaosocial);
    $endereco = str_replace("'", "", $endereco);
    $bairro = str_replace("'", "", $bairro);
    $nfantasia = str_replace("'", "", $nfantasia);
    $observacao = str_replace("'", "", $observacao);


    if ($rzaosocial == "") {
        $retornar["dados"] =  array("sucesso" => "false", "title" => mensagem_alerta_cadastro("Razão social"));
    } elseif ($estado == "0") {
        $retornar["dados"] =  array("sucesso" => "false", "title" => mensagem_alerta_cadastro("Estado"));
    } elseif ($cidade == "0") {
        $retornar["dados"] =  array("sucesso" => "false", "title" => mensagem_alerta_cadastro("Cidade"));
    } elseif ((strlen($cnpjcpf) < 11) and ($cnpjcpf != "")) { //verificar se o campo está preenchido e aquantidade for menor que 11
        $retornar["dados"] = array("sucesso" => false, "title" => "Favor verificque o campo Cnpj \ cpf, a numeração está incorreta");
    } elseif ((!validarCNPJ($cnpjcpf)) and (strlen($cnpjcpf) > 12)) { // validar cnpj
        $retornar["dados"] = array("sucesso" => false, "title" => "Favor verificque o campo Cnpj \ cpf, O cnpj está Incorreto");
    } elseif ((!validaCPF($cnpjcpf)) and (strlen($cnpjcpf) > 0 and strlen($cnpjcpf) <= 11)) { // validar cpf
        $retornar["dados"] = array("sucesso" => false, "title" => "Favor verificque o campo Cnpj \ cpf, O cpf está Incorreto");
    } elseif (consultar_cnpj_cadastrado($conecta, $cnpjcpf, $id_cliente) > 0) { //verificar se já existe algum cliente cadastrado com o mesmo cnpj que não seja ele mesmo
        $retornar["dados"] = array("sucesso" => false, "title" => "Já existe um cadastrado com esses dados, favor verifique o campo Cnpj/Cpf");
    } elseif (($email != "") and (!filter_var($email, FILTER_VALIDATE_EMAIL))) { //validar email
        $retornar["dados"] = array("sucesso" => false, "title" => "Esse email não é valido,Favor verifique");
    } else {

        $update = "UPDATE `tb_parceiros`
         SET `cl_razao_social` = '$rzaosocial', `cl_nome_fantasia` = '$nfantasia',`cl_inscricao_estadual` = '$ie',
         `cl_cnpj_cpf` = '$cnpjcpf', `cl_cep` = '$cep', `cl_bairro` = '$bairro',
          `cl_endereco` = '$endereco', `cl_numero` = '$numero', `cl_cidade_id` = '$cidade', `cl_telefone` = '$telefone', `cl_email`
         = '$email', `cl_observacao` = '$observacao', `cl_situacao_ativo` = '$situacao' WHERE `tb_parceiros`.`cl_id` = $id_cliente";
        $operacao_update = mysqli_query($conecta, $update);
        if ($operacao_update) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Dados alterados com sucesso");
            $mensagem =  utf8_decode("Usuário $nome_usuario_logado Editou o parceiro código $id_cliente");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }


    echo json_encode($retornar);
}

// // //Editar formulario
// if(isset($_POST['formulario_editar_grupo_estoque'])){
//     include "../../../conexao/conexao.php";
//     include "../../../funcao/funcao.php";
//         $retornar = array();

//         $nome_usuario_logado = $_POST["nome_usuario_logado"];
//         $id_usuario_logado = $_POST["id_usuario_logado"];
//         $perfil_usuario_logado = $_POST['perfil_usuario_logado'];

//         $id_grupo = $_POST["id_grupo"];
//         $descricao = utf8_decode($_POST["descricao"]);


//         if($descricao == ""){
//             $retornar["mensagem"] =mensagem_alerta_cadastro("descricão");
//         }else{

//         $update = "UPDATE tb_grupo_estoque set cl_descricao = '$descricao' where cl_id = $id_grupo";
//         $operacao_update = mysqli_query($conecta, $update);
//         if($operacao_update){
//         $retornar["sucesso"] = true;
//         //registrar no log
//         $mensagem =  (utf8_decode("Usúario") . " $nome_usuario_logado alterou dados do grupo de codigo $id_grupo para $descricao");
//         registrar_log($conecta,$nome_usuario_logado,$data,$mensagem);
//         }  

//     }
//     echo json_encode($retornar);
// }

//trazer informaçãoes
if (isset($_GET['editar_cliente']) == true) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $id_cliente = $_GET['id_cliente'];
    $select = "SELECT * from tb_parceiros where cl_id = $id_cliente";
    $consultar_clientes = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consultar_clientes);
    $rzaosocial_b = utf8_encode($linha['cl_razao_social']);
    $nfantasia_b = utf8_encode($linha['cl_nome_fantasia']);
    $cnpjcpf_b = ($linha['cl_cnpj_cpf']);
    $ie_b = ($linha['cl_inscricao_estadual']);
    $cep_b = ($linha['cl_cep']);
    $bairro_b = utf8_encode($linha['cl_bairro']);
    $endereco_b = utf8_encode($linha['cl_endereco']);
    $cidade_id_b = ($linha['cl_cidade_id']);
    $estado_id_b = ($linha['cl_estado_id']);
    $telefone_b = $linha['cl_telefone'];
    $email_b = $linha['cl_email'];
    $observacao_b = utf8_encode($linha['cl_observacao']);
    $status_b = $linha['cl_situacao_ativo'];
    $numero_b = utf8_encode($linha['cl_numero']);
}




//consultar estados
$select = "SELECT * from tb_estados";
$consultar_estados = mysqli_query($conecta, $select);
