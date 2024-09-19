<?php
date_default_timezone_set('America/Fortaleza');
$data = date('Y-m-d H:i:s');

$hora_atual = date("H:i:s");


$data_lancamento = date('Y-m-d');

$data_incial_lembrete = date('01/01/Y');

$data_incial_log = date('01/m/Y');
$data_final_log = date('d/m/Y');

$data_inicial = date('01/m/Y');
$data_final = date('d/m/Y');


$data_dia_bd = date('Y-m-d');

// $data_inicial_mes_bd = date('Y-m-01');
// $data_final_mes_bd = date('Y-m-d');

$data_inicial_ano_bd = date('Y-01-01');
$data_final_ano_bd = date('Y-12-31');

$data_inicial_mes_bd = date('Y-m-01');
$data_final_mes_bd = date('Y-m-t');


/*dados sobre  servidor */
$servidor = $_SERVER['SERVER_NAME'];
if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    $protocolo = "https://";
} else {
    $protocolo = "http://";
}
$url_init = $protocolo . $servidor;
$url_init_img = $protocolo . $servidor;
$diretorio_img_public = $url_init . "/assets/public";



function formatarPorcentagem($numero)
{
    return number_format($numero, 2) . '%';
}


///formatar data 
function formatarTimeStamp($value)
{
    if (($value != "") and ($value != "0000-00-00")) {
        $value = date("d/m/Y H:i:s", strtotime($value));
        return $value;
    }
}
//mensagem de alerta cadastro
function mensagem_alerta_cadastro($campo)
{
    return "Campo $campo não foi informado, favor, verifique!";
}

//mensagem de alerta de permissao
function mensagem_alerta_permissao()
{
    return "Ação bloqueada. Você não possui permissão para realizar esta ação no sistema. Por favor, verifique as suas permissões de acesso ou 
     entre em contato com o administrador do sistema para obter mais informações";
}

//mensagem de alerta de caixa 
function mensagem_alerta_caixa($valor)
{
    if ($valor == "VAZIO") {
        return "O caixa desse período ainda não foi aberto, favor, verifique";
    }
    if ($valor == "FECHADO") {
        return "O caixa desse período já foi fechado, não é possivel realizar a ação";
    }
}

//mensagem de alerta de serie cadastrada
function mensagem_serie_cadastrada()
{
    return "A serie não está cadastrada, não é possivel realizar a ação, favor, verifique com o suporte";
}

//formatar data do banco de dados
function formatDateB($value)
{
    if (($value != "") and ($value != "0000-00-00")) {
        $value = date("d/m/Y", strtotime($value));
        return $value;
    }
}

function formatarDataParaBancoDeDados($data)
{
    // Cria um objeto DateTime a partir da string da data no formato 'dd/mm/aaaa'
    $dataObj = DateTime::createFromFormat('d/m/Y', $data);

    // Retorna a data formatada no formato 'aaaa-mm-dd'
    return $dataObj->format('Y-m-d');
}

function datecheck($value)
{
    $d = DateTime::createFromFormat('d/m/Y', $value);
    if ($d && $d->format('d/m/Y') == $value) {
        return true;
    } else {
        return false;
    }
}

function verificar_user($conecta, $usuario, $acao)
{
    if ($acao == "cadastrar") {
        //verificar se já existe uma pessoa cadastrada com o mesmo usuario
        $select = "SELECT * from tb_users where cl_usuario ='$usuario'";
        $consultar_verficar_user = mysqli_query($conecta, $select);
        $cont = mysqli_num_rows($consultar_verficar_user);
        return $cont;
    } else {
        //verificar se já existe uma pessoa cadastrada com o mesmo usuario diferente do usuario que será alterado
        $select = "SELECT * from tb_users where cl_usuario = '$usuario'";
        $consultar_verficar_user = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consultar_verficar_user);
        $id_user_b = $linha['cl_id'];
        return $id_user_b;
    }
}

function reduzir_texto($texto)
{
    if (strlen($texto) > 30) { // verifica se o texto é maior do que 30 caracteres
        $texto = substr($texto, 0, 20) . "..."; // corta o texto em 30 caracteres e adiciona "..."
    } else {
        $texto = $texto; // se o texto for menor ou igual a 30 caracteres, mantém o texto original
    }
    return $texto;
}

function verificar_user_usuario($conecta, $id_user)
{
    //verificar usuario pelo id
    $select = "SELECT * from tb_users where cl_id ='$id_user'";
    $consultar_verficar_user = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consultar_verficar_user);
    $usuario_b = $linha['cl_usuario'];
    return $usuario_b;
}


//verificar se a opção remover podera ser feita
function verificar_dados_existentes($conecta, $tabela, $filtro, $resultado_filtro)
{
    //verificar usuario pelo id
    $select = "SELECT count(*) as qtd from $tabela where $filtro ='$resultado_filtro'";
    $consultar_dados_existentes = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consultar_dados_existentes);
    $resultado = $linha['qtd'];
    return $resultado;
}


//registrar log da acão
function registrar_log($conecta, $nome_usuario_logado, $data, $mensagem)
{

    //pegar o ip do usuario
    $ip_user = $_SERVER['REMOTE_ADDR'];
    $mensagem = $mensagem . " IP " . $ip_user;
    $inset = "INSERT INTO tb_log (cl_data_modificacao,cl_usuario,cl_descricao) VALUES ('$data','$nome_usuario_logado','$mensagem')";
    $operacao_inserir = mysqli_query($conecta, $inset);
    return $operacao_inserir;
}


function verficar_paramentro($conecta, $tabela, $filtro, $valor)
{
    $select = "SELECT * from $tabela where $filtro = $valor";
    $consultar_parametros = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consultar_parametros);
    $valor_parametro = $linha['cl_valor'];
    return $valor_parametro;
}

//funcao para saber qual usuario foi selecionado para adicionar ou remover acesso
function consultar_usuario_acesso($conecta, $usuario_id)
{
    //consultar nome do usuario
    $select = "SELECT * from tb_users where cl_id = '$usuario_id' ";
    $consulta_usuario = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consulta_usuario);
    $usuario_b = $linha['cl_usuario'];
    return $usuario_b;
}

//funcao para saber qual subcategoria foi selecionado para adicionar ou remover para o usúario
function consultar_subcategoria_acesso($conecta, $id_subcategoria)
{
    //consultar nome da subcategoria
    $select = "SELECT * from tb_subcategorias where cl_id = '$id_subcategoria' ";
    $consulta_subcategoria = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consulta_subcategoria);
    $subcategoria_b =  utf8_encode($linha['cl_subcategoria']);
    return $subcategoria_b;
}


//funcao para saber qual é o valor da serie pelo id
function consultar_serie($conecta, $id_serie)
{
    //consultar nome da subcategoria
    $select = "SELECT * from tb_serie where cl_id = '$id_serie' ";
    $consulta_serie = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consulta_serie);
    $valor = $linha['cl_valor'];
    return $valor;
}


//funcao para saber qual é o valor da serie
function consultar_valor_serie($conecta, $id)
{
    //consultar nome da subcategoria
    $select = "SELECT * from tb_serie where cl_id = $id ";
    $consulta_serie = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consulta_serie);
    $valor = $linha['cl_valor'];
    return $valor;
}

//funcao para realizar ajuste de estoque
function ajuste_estoque($conecta, $data, $doc, $tipo, $produto_id, $quantidade, $empresa_id, $parceiro_id, $usuario_id, $forma_pagamento_id, $valor_venda, $valor_compra, $ajuste_inical, $motivo, $codigo_nf, $item_nf_id, $item_nf_id_pai)
{
    $inset = "INSERT INTO `tb_ajuste_estoque` (`cl_data_lancamento`, `cl_documento`, `cl_produto_id`, `cl_tipo`, `cl_quantidade`, 
    `cl_empresa_id`,`cl_parceiro_id`,`cl_usuario_id`, `cl_forma_pagamento_id`, `cl_valor_venda`, `cl_valor_compra`,`cl_ajuste_inicial`,`cl_status`,`cl_motivo`,`cl_codigo_nf`, `cl_id_nf`,`cl_id_nf_pai`  ) VALUES 
    ('$data', '$doc', '$produto_id', '$tipo', '$quantidade', '$empresa_id','$parceiro_id','$usuario_id', '$forma_pagamento_id', '$valor_venda', '$valor_compra','$ajuste_inical','ok','$motivo','$codigo_nf', '$item_nf_id', '$item_nf_id_pai' )";
    $operacao_inserir = mysqli_query($conecta, $inset);
    if ($operacao_inserir) {
        return true;
    } else {
        return false;
    }
}

//funcao para realizar ajuste na quantidade do produto
function ajuste_qtd_produto($conecta, $produto_id, $quantidade, $data_validade)
{

    $update = "UPDATE `tb_produtos` SET `cl_estoque`= '$quantidade',`cl_data_validade`= '$data_validade'  where cl_id = $produto_id";
    $operacao_update = mysqli_query($conecta, $update);
    return $operacao_update;
}


//funcao para atualizar valor em serie
function adicionar_valor_serie($conecta, $id_serie, $valor)
{
    //consultar nome da subcategoria
    $update = "UPDATE `tb_serie` SET `cl_valor`= '$valor' where cl_id = '$id_serie'";
    $update_serie = mysqli_query($conecta, $update);
    if ($update_serie) {
        return true;
    } else {
        return false;
    }
}

//funcao para atualizar valor em serie
function atualizar_valor_serie($conecta, $id, $valor)
{
    //consultar nome da subcategoria
    $update = "UPDATE `tb_serie` SET `cl_valor`= '$valor' where cl_id = $id";
    $update_serie = mysqli_query($conecta, $update);
    if ($update_serie) {
        return true;
    } else {
        return false;
    }
}
//funcao para atualizar valor em serie
function verifcar_descricao_serie($conecta, $id)
{
    //consultar nome da subcategoria
    $select = "SELECT * from tb_serie where cl_id = $id ";
    $consulta_serie = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consulta_serie);
    $valor = $linha['cl_descricao'];
    return $valor;
}

//consultar se já existe um parceiro cadastrado no sistema com o mesmo cnpj que não seja ele propio
function consultar_cnpj_cadastrado($conecta, $cnpjcpf, $id_cliente)
{
    //verifiar se o campo está vazio
    if ($cnpjcpf != "") {
        $select = "SELECT count(*) as qtd from tb_parceiros where cl_cnpj_cpf = '$cnpjcpf' and cl_id != $id_cliente ";
        $consulta_tabela = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consulta_tabela);
        $qtd_encontrados = $linha["qtd"];
        return $qtd_encontrados;
    } else {
        return 0;
    }
}
//formatar cnpj
function formatCNPJCPF($cnpjcpf)
{
    $cnpjcpf = preg_replace("/[^0-9]/", "", $cnpjcpf); // Remove tudo que não é número
    if (strlen($cnpjcpf) == "14") { //formatar para cnpj
        $cnpjcpf = str_pad($cnpjcpf, 14, '0', STR_PAD_LEFT); // Completa com zeros à esquerda até 14 dígitos

        $cnpjFormatado = substr($cnpjcpf, 0, 2) . '.'; // Adiciona o primeiro ponto
        $cnpjFormatado .= substr($cnpjcpf, 2, 3) . '.'; // Adiciona o segundo ponto
        $cnpjFormatado .= substr($cnpjcpf, 5, 3) . '/'; // Adiciona a barra
        $cnpjFormatado .= substr($cnpjcpf, 8, 4) . '-'; // Adiciona o hífen
        $cnpjFormatado .= substr($cnpjcpf, 12); // Adiciona os últimos 2 dígitos

        return $cnpjFormatado;
    } elseif (strlen($cnpjcpf) == "11") { //formatar para cpf
        $cnpjcpf = preg_replace("/[^0-9]/", "", $cnpjcpf); // Remove tudo que não é número
        $cnpjcpf = str_pad($cnpjcpf, 11, '0', STR_PAD_LEFT); // Completa com zeros à esquerda até 11 dígitos

        $cpfFormatado = substr($cnpjcpf, 0, 3) . '.'; // Adiciona o primeiro ponto
        $cpfFormatado .= substr($cnpjcpf, 3, 3) . '.'; // Adiciona o segundo ponto
        $cpfFormatado .= substr($cnpjcpf, 6, 3) . '-'; // Adiciona o hífen
        $cpfFormatado .= substr($cnpjcpf, 9); // Adiciona os últimos 2 dígitos

        return $cpfFormatado;
    } else {
        return $cnpjcpf;
    }
}

//consultar qualuer tabela do bd
function consulta_tabela_query($conecta, $query, $coluna_valor)
{
    $select = $query;
    $consulta_tabela = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consulta_tabela);
    if (isset($linha["$coluna_valor"])) {
        $valor = $linha["$coluna_valor"];
    } else {
        // Lógica para lidar com o caso em que o índice não existe
        $valor = ""; // Por exemplo, atribuir um valor padrão
    }
    return $valor;
}


//consultar qualuer tabela do bd
function consulta_tabela($conecta, $tabela, $coluna_filtro, $valor, $coluna_valor)
{
    $select = "SELECT * from $tabela where $coluna_filtro = '$valor' ";
    $consulta_tabela = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consulta_tabela);
    if (isset($linha["$coluna_valor"])) {
        $valor = $linha["$coluna_valor"];
    } else {
        // Lógica para lidar com o caso em que o índice não existe
        $valor = ""; // Por exemplo, atribuir um valor padrão
    }
    return $valor;
}

//consultar qualuer tabela do bd via like
function consulta_tabela_like($conecta, $tabela, $coluna_filtro, $valor, $coluna_valor)
{
    $select = "SELECT * from $tabela where $coluna_filtro like '%$valor%' limit 1 ";
    $consulta_tabela = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consulta_tabela);
    if (isset($linha["$coluna_valor"])) {
        $valor = $linha["$coluna_valor"];
    } else {
        // Lógica para lidar com o caso em que o índice não existe
        $valor = ""; // Por exemplo, atribuir um valor padrão
    }
    return $valor;
}
//consultar qualuer tabela do bd
function consulta_tabela_2_filtro($conecta, $tabela, $coluna_filtro, $valor, $coluna_filtro2, $valor_filtro2, $coluna_valor)
{
    $select = "SELECT * from $tabela where $coluna_filtro = '$valor' and $coluna_filtro2 = '$valor_filtro2' ";
    $consulta_tabela = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consulta_tabela);
    if (isset($linha["$coluna_valor"])) {
        $valor = $linha["$coluna_valor"];
    } else {
        // Lógica para lidar com o caso em que o índice não existe
        $valor = ""; // Por exemplo, atribuir um valor padrão
    }
    return $valor;
}

//  Em PHP, você pode usar a função ctype_alpha para verificar se um caractere é uma 
//letra e a função strtoupper para transformar uma letra em maiúscula.
//   Aqui está uma função que verifica se um caractere é uma letra e, se for, converte-o em maiúscu
function uppercaseLetter($char)
{
    if (ctype_alpha($char)) {
        return strtoupper($char);
    } else {
        return $char;
    }
}


function identifyCpfOrCnpj($number)
{
    // Remove caracteres não numéricos
    $number = preg_replace('/[^0-9]/', '', $number);

    // Verifica se o número tem 11 dígitos (CPF)
    if (strlen($number) === 11) {
        // Validação do CPF
        if (preg_match('/(\d)\1{10}/', $number) || !preg_match('/^\d{11}$/', $number)) {
            return -1; // Número inválido
        }

        // Calcula os dígitos verificadores do CPF
        for ($i = 9, $j = 0, $sum = 0; $i > 1; $i--, $j++) {
            $sum += $number[$j] * $i;
        }
        $remainder = $sum % 11;
        if ($number[9] != ($remainder < 2 ? 0 : 11 - $remainder)) {
            return -1; // Número inválido
        }

        for ($i = 10, $j = 0, $sum = 0; $i > 2; $i--, $j++) {
            $sum += $number[$j] * $i;
        }
        $remainder = $sum % 11;
        return $number[10] == ($remainder < 2 ? 0 : 11 - $remainder) ? 0 : -1; // Retorna 0 para CPF válido, -1 para inválido
    }
    // Verifica se o número tem 14 dígitos (CNPJ)
    elseif (strlen($number) === 14) {
        // Validação do CNPJ
        $sum = 0;
        $weight = 5;
        for ($i = 0; $i < 12; $i++) {
            $sum += intval($number[$i]) * $weight;
            $weight = ($weight === 2) ? 9 : ($weight - 1);
        }
        $remainder = $sum % 11;
        if ($number[12] != ($remainder < 2 ? 0 : 11 - $remainder)) {
            return -1; // Número inválido
        }

        $sum = 0;
        $weight = 6;
        for ($i = 0; $i < 13; $i++) {
            $sum += intval($number[$i]) * $weight;
            $weight = ($weight === 2) ? 9 : ($weight - 1);
        }
        $remainder = $sum % 11;
        return $number[13] == ($remainder < 2 ? 0 : 11 - $remainder) ? 1 : -1; // Retorna 1 para CNPJ válido, -1 para inválido
    }

    // Se não tiver 11 ou 14 dígitos, não é válido
    return -1; // Número inválido
}

function validaCPF($cpf)
{
    // Remove caracteres não numéricos
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    // Verifica se o CPF possui 11 dígitos
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se todos os dígitos são iguais
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Calcula os dígitos verificadores
    for ($i = 9; $i < 11; $i++) {
        $soma = 0;
        for ($j = 0; $j < $i; $j++) {
            $soma += $cpf[$j] * (($i + 1) - $j);
        }
        $resto = $soma % 11;
        $digito = $resto < 2 ? 0 : 11 - $resto;
        if ($cpf[$i] != $digito) {
            return false;
        }
    }

    return true;
}

//validar cnpj
function validarCNPJ($cnpj)
{
    // Remove caracteres especiais
    $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

    // Verifica se o CNPJ possui 14 dígitos
    if (strlen($cnpj) != 14) {
        return false;
    }

    // Verifica se todos os dígitos são iguais
    if (preg_match('/(\d)\1{13}/', $cnpj)) {
        return false;
    }

    // Verifica o primeiro dígito verificador
    $soma = 0;
    for ($i = 0; $i < 12; $i++) {
        $soma += intval($cnpj[$i]) * (($i < 4) ? 5 - $i : 13 - $i);
    }
    $digito1 = (($soma % 11) < 2) ? 0 : 11 - ($soma % 11);
    if ($cnpj[12] != $digito1) {
        return false;
    }

    // Verifica o segundo dígito verificador
    $soma = 0;
    for ($i = 0; $i < 13; $i++) {
        $soma += intval($cnpj[$i]) * (($i < 5) ? 6 - $i : 14 - $i);
    }
    $digito2 = (($soma % 11) < 2) ? 0 : 11 - ($soma % 11);
    if ($cnpj[13] != $digito2) {
        return false;
    }

    // Se chegou até aqui, o CNPJ é válido
    return true;
}



//formatar para moeda real
function real_format($valor)
{
    $valor = !empty($valor) ? $valor : 0;
    $valor  = number_format($valor, 2, ",", ".");
    return "R$ " . $valor;
}

function formatarNumero($numero)
{
    if (is_int($numero)) {
        return number_format($numero, 2);
    } else {
        return number_format($numero, 0);
    }
}

//verificar se tem virgula na string
function verificaVirgula($valor)
{
    if (strpos($valor, ',') !== false) {
        // echo "A string contém uma vírgula.";
        return true;
    } else {
        // echo "A string não contém uma vírgula.";
        return false;
    }
}

//substituir uma virgula por um ponto
function formatDecimal($valor)
{
    $string_com_virgula = $valor;
    $string_com_ponto = str_replace(",", ".", $string_com_virgula);
    return $string_com_ponto;
}


function verifica_status_caixa($conecta, $consultar_tipo_contabilizacao, $dia, $mes, $ano, $conta_financeira)
{
    $select = "SELECT * FROM tb_caixa where cl_ano !='' and cl_conta ='$conta_financeira'";
    if ($consultar_tipo_contabilizacao == "DIA") {
        $select .= " and cl_dia = '$dia' and cl_mes ='$mes' and cl_ano='$ano' "; // se for por periodo de contabilização em dia a dia vai verifiar o dia, o mes e o ano
    } elseif ($consultar_tipo_contabilizacao == "MES") {
        $select .= " and cl_mes = '$mes' and cl_ano ='$ano'"; // se for por periodo de contabilização em mes a mes vai verifiar o mes e o ano
    } else {
        $select .= " and cl_dia = '$dia' and cl_mes ='$mes' and cl_ano='$ano' "; // se o paramentro estivir com valor incorreto será atribuido o periodo de dia a dia
    }
    $consulta_caixa = mysqli_query($conecta, $select);
    $resultado_consulta = mysqli_num_rows($consulta_caixa);
    $linha = mysqli_fetch_assoc($consulta_caixa);

    $status_caixa = $linha['cl_status'];
    $valor_aberto = $linha['cl_valor_abertura'];
    $dados = array("resultado" => $resultado_consulta, "status" => $status_caixa, "valor_aberto" => $valor_aberto);
    return $dados;
}

function verifica_saldo_final($conecta, $consultar_tipo_contabilizacao, $dia, $mes, $ano, $conta_financeira)
{


    // $ultimo_dia_mes_atual = date("t", strtotime(date("$ano-$mes-d")));
    $data = ("$dia-$mes-$ano");
    $ultimo_dia_mes_anterior = date('t', strtotime('-1 month', strtotime($data))); // pegar o ultimo dia do mes anterior
    $mes_anterior = date('m', strtotime('-1 month', strtotime($data))); //pegar o mes anterior
    $dia_anerior = date('d', strtotime('-1 day', strtotime($data))); //pegar o dia anterior


    $select = "SELECT * FROM tb_caixa where cl_ano !='' and cl_conta = '$conta_financeira' ";
    if ($consultar_tipo_contabilizacao == "DIA") {
        if ($mes == "01" and $dia == "01") { //se for primeiro dia do ano vai verificar o ultimo dia do mes anterior
            $dia = 31;
            $ano = $ano - 1;
            $mes = 12;
        } elseif ($dia == "01") { //se for primerio dia de cada mes, vai verificar o saldo do ultimo dia do mes anterior
            $dia = "$ultimo_dia_mes_anterior";
            $mes = "$mes_anterior";
        } else { //se for um dia qualquer verificar o dia anterior
            $dia = $dia_anerior;
        }
        $select .= " and cl_dia = '$dia' and cl_mes = '$mes' and cl_ano='$ano' "; // se for por periodo de contabilização em dia a dia vai verifiar o dia, o mes e o ano
    } elseif ($consultar_tipo_contabilizacao == "MES") {
        if ($mes == "01") {
            $ano = $ano - 1;
            $mes = 12;
        } else {
            $mes = $mes_anterior;
        }
        $select .= " and cl_mes = '$mes' and cl_ano ='$ano'"; // se for por periodo de contabilização em mes a mes vai verifiar o mes e o ano
    }
    $consulta_caixa = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consulta_caixa);
    $saldo_fechado = $linha['cl_valor_fechamento'];
    return $saldo_fechado;
}

//função para verificar os parametros do caixa do periodo
function verifica_caixa_financeiro($conecta, $data_pagamento, $conta_financeira)
{
    $select = "SELECT * FROM tb_parametros where cl_id = '6'";
    $conulta_parametros = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($conulta_parametros);
    $consultar_tipo_contabilizacao = $linha['cl_valor'];

    // Divide a data em partes
    $partes = explode('-', $data_pagamento);
    // Extrai o ano, o mês e o dia
    $ano = $partes[0];
    $mes = $partes[1];
    $dia = $partes[2];


    $select = "SELECT * FROM tb_caixa where cl_ano !='' and cl_conta ='$conta_financeira'";
    if ($consultar_tipo_contabilizacao == "DIA") {
        $select .= " and cl_dia = '$dia' and cl_mes ='$mes' and cl_ano='$ano' "; // se for por periodo de contabilização em dia a dia vai verifiar o dia, o mes e o ano
    } elseif ($consultar_tipo_contabilizacao == "MES") {
        $select .= " and cl_mes = '$mes' and cl_ano ='$ano'"; // se for por periodo de contabilização em mes a mes vai verifiar o mes e o ano
    } else {
        $select .= " and cl_dia = '$dia' and cl_mes ='$mes' and cl_ano='$ano' "; // se o paramentro estivir com valor incorreto será atribuido o periodo de dia a dia
    }
    $consulta_caixa = mysqli_query($conecta, $select);
    $resultado_consulta = mysqli_num_rows($consulta_caixa);
    $linha = mysqli_fetch_assoc($consulta_caixa);
    $status_caixa = $linha['cl_status'];
    $valor_aberto = $linha['cl_valor_abertura'];

    $dados = array("resultado" => $resultado_consulta, "status" => $status_caixa, "valor_aberto" => $valor_aberto);
    return $dados;
}

function validar_usuario($conecta, $id_usuario, $senha)
{ //validar usuario
    $senha = base64_encode($senha); //criptografar a senha
    $select = "SELECT * FROM tb_users where cl_id ='$id_usuario' and cl_senha ='$senha'";
    $consulta_usuario = mysqli_query($conecta, $select);
    $valida = mysqli_num_rows($consulta_usuario);

    if ($valida > 0) { //validado usuario
        return true;
    } else { //não foi validado
        return false;
    }
}


// function recebimento_nf_recebida($conecta, $fpg_id, $data, $serie_nf, $numero_nf, $parceiro_id, $classificacao, $valor, $documento, $codigo_nf) //verificar se a forma de pagamento é com stauts recebido se for realizar o lançamento financeiro
// {
//     $select = "SELECT * FROM tb_forma_pagamento where cl_id = $fpg_id ";
//     $consulta_forma_pagamento = mysqli_query($conecta, $select);
//     $linha = mysqli_fetch_assoc($consulta_forma_pagamento);
//     $status_id = $linha['cl_status_id'];
//     $conta_financeira = $linha['cl_conta_financeira'];

//     if ($status_id == "2") {
//         $descricao = utf8_decode("Recebimento referente a $serie_nf $numero_nf");
//         $insert = "INSERT INTO `tb_lancamento_financeiro` (`cl_data_lancamento`, `cl_data_vencimento`,
//         `cl_data_pagamento`, `cl_conta_financeira`, `cl_forma_pagamento_id`, `cl_parceiro_id`, `cl_tipo_lancamento`, 
//         `cl_status_id`, `cl_valor_bruto`, `cl_valor_liquido`,`cl_documento`, `cl_classificacao_id`, `cl_descricao`, `cl_nf`, `cl_serie_nf`, `cl_codigo_nf` )
//          VALUES ('$data', '$data', '$data', '$conta_financeira', '$fpg_id', '$parceiro_id', 'RECEITA', '2', '$valor', '$valor',
//           '$documento', '$classificacao','$descricao','$numero_nf','$serie_nf','$codigo_nf' )";
//         $operacao_insert = mysqli_query($conecta, $insert);
//         if ($operacao_insert) {
//             return true;
//         }
//     } else {
//         return false;
//     }
// }

function verifica_desconto_fpg($conecta, $fpg_id)
{
    if ($fpg_id != "") {
        $select = "SELECT * FROM tb_forma_pagamento where cl_id = $fpg_id ";
        $consulta_forma_pagamento = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consulta_forma_pagamento);
        $desconto_maximo = $linha['cl_desconto_maximo'];

        return $desconto_maximo;
    } else {
        return 0;
    }
}
//verificar se doc está repetido
function verifica_repeticao_doc($conecta, $tabela, $filtro1, $filtro2, $valor1, $valor2)
{
    $select = "SELECT count(*) as repetiacao FROM $tabela where $filtro1 = '$valor1' and $filtro2 = '$valor2' ";
    $consulta_repeticao_doc = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consulta_repeticao_doc);
    $repeticao = $linha['repetiacao'];
    if ($repeticao > 0) {
        return true;
    } else {
        return false;
    }
}


//função para retornar o ultimo id de uma tabela
function retornar_ultimo_id($conecta, $tabela)
{
    //pegar o id do ultimo produto cadastrado
    $select = "SELECT max(cl_id) as id from $tabela";
    $consultar_produto = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consultar_produto);
    $id_b = $linha['id'];
    return $id_b;
}

function validar_prod_venda($conecta, $id_produto, $retorno)
{
    //pegar o id do ultimo produto cadastrado
    $select = "SELECT * from tb_produtos where cl_id =$id_produto ";
    $consultar_produto = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consultar_produto);
    $valor = $linha["$retorno"];
    return $valor;
}

function validar_qtd_prod_venda($conecta, $id_produto, $codigo_nf, $quantidade)
{
    $select = " SELECT sum(cl_quantidade) as qtd from tb_nf_saida_item where cl_item_id = '$id_produto' and cl_codigo_nf ='$codigo_nf' ";
    $consultar_produto = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consultar_produto);
    $valor = $linha["qtd"];
    $total = $valor + $quantidade;
    return $total;
}
function valores_prod_nf($conecta, $codigo_nf)
{

    $select = "SELECT sum(cl_valor_total) as vlr_total from tb_nf_saida_item where cl_codigo_nf ='$codigo_nf' ";
    $consultar_vlr_produto = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consultar_vlr_produto);
    $valor = $linha["vlr_total"];
    return $valor;
}
function valores_total_tabela($tabela, $referencia, $filtro, $valor_filtro, $filtro_2, $valor_filtro_2)
{
    global $conecta;
    $select = "SELECT sum($referencia) as total from $tabela where $filtro ='$valor_filtro' ";
    $select .= " and $filtro_2 = '$valor_filtro_2' ";
    $consulta = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consulta);
    $total = $linha["total"];
    return $total;
}
function finalizar_produtos_nf($conecta, $codigo_nf, $serie_vnd, $numero_nf, $desconto, $data, $parceiro_id, $id_usuario_logado, $forma_pagamento_id)
{
    $ordem_item = 0;

    $select = "SELECT * from tb_nf_saida_item where cl_codigo_nf ='$codigo_nf' ";
    $consultar_produto_nf = mysqli_query($conecta, $select);
    $qtd_prod = mysqli_num_rows($consultar_produto_nf);

    if ($desconto != "0") {
        $desconto_rat = $desconto / $qtd_prod; //rateio do desconto
    } else {
        $desconto_rat = 0;
    }

    while ($linha = mysqli_fetch_assoc($consultar_produto_nf)) {
        $item_nf_id = $linha['cl_id'];
        $id_produto = $linha['cl_item_id'];
        $quantidade_vendida = $linha['cl_quantidade'];
        $prc_venda_unitario = $linha['cl_valor_unitario'];
        $id_pai_delivery = $linha['cl_id_pai_delivery'];

        $estoque = validar_prod_venda($conecta, $id_produto, "cl_estoque");

        $quantidade_atual = $estoque - $quantidade_vendida;
        $ordem_item = $ordem_item + 1;


        $update = "UPDATE `tb_nf_saida_item` SET `cl_numero_nf` = '$numero_nf', `cl_item_ordem` = '$ordem_item', 
        `cl_desconto_rat` = '$desconto_rat', `cl_status` = '1' WHERE `tb_nf_saida_item`.`cl_codigo_nf` = '$codigo_nf' ";
        $operacao_update = mysqli_query($conecta, $update);
        if ($operacao_update) {
            $update = "UPDATE `tb_produtos` SET `cl_estoque` = '$quantidade_atual' WHERE `tb_produtos`.`cl_id` = $id_produto ";
            $operacao_update_estoque = mysqli_query($conecta, $update);
            //adicionar ao ajuste de estoque
            if ($operacao_update_estoque) {
                ajuste_estoque(
                    $conecta,
                    $data,
                    "$serie_vnd-$numero_nf",
                    "SAIDA",
                    $id_produto,
                    $quantidade_vendida,
                    "1",
                    $parceiro_id,
                    $id_usuario_logado,
                    $forma_pagamento_id,
                    $prc_venda_unitario,
                    "0",
                    '0',
                    '',
                    "$codigo_nf",
                    "$item_nf_id",
                    "$id_pai_delivery"
                );
            }
            return true;
        } else {
            return false;
        }
    };
}

function finalizar_produtos_nf_entrada($conecta, $codigo_nf, $data_lancamento, $usuario_id)
{

    $select = "SELECT * from tb_nf_entrada_item where cl_codigo_nf ='$codigo_nf' ";
    $consultar_produto_nf_entrada = mysqli_query($conecta, $select);


    while ($linha = mysqli_fetch_assoc($consultar_produto_nf_entrada)) {
        $nf_id = $linha['cl_id'];
        $produto_id = $linha['cl_produto_id'];
        $quantidade_compra = $linha['cl_quantidade'];
        $valor_compra_unitario = $linha['cl_valor_unitario'];

        $estoque = consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_estoque"); //estoque atual do produto
        $serie_nf = consulta_tabela($conecta, "tb_nf_entrada", "cl_codigo_nf", $codigo_nf, "cl_serie_nf");
        $numero_nf = consulta_tabela($conecta, "tb_nf_entrada", "cl_codigo_nf", $codigo_nf, "cl_numero_nf");

        $forma_pagamento_id = consulta_tabela($conecta, "tb_nf_entrada", "cl_codigo_nf", $codigo_nf, "cl_forma_pagamento_id");
        $parceiro_id = consulta_tabela($conecta, "tb_nf_entrada", "cl_codigo_nf", $codigo_nf, "cl_parceiro_id");

        $estoque_atualizado = $estoque + $quantidade_compra;

        $custo = calcular_custo_item_nf($nf_id);
        $valor_custo = ($custo['valor_custo']);
        $prc_vnd_sugerido = ($custo['prc_vnd_sugerido']);

        $update = "UPDATE `tb_produtos` SET `cl_estoque` = '$estoque_atualizado',`cl_ult_preco_compra` = '$valor_compra_unitario', `cl_preco_custo` = '$valor_custo' ,`cl_preco_sugerido_venda` = '$prc_vnd_sugerido' WHERE `cl_id` = $produto_id ";
        $operacao_update_estoque = mysqli_query($conecta, $update);

        //adicionar ao ajuste de estoque
        if ($operacao_update_estoque) {
            ajuste_estoque(
                $conecta,
                $data_lancamento,
                "$serie_nf-$numero_nf",
                "ENTRADA",
                $produto_id,
                $quantidade_compra,
                "1",
                $parceiro_id,
                $usuario_id,
                $forma_pagamento_id,
                0,
                $valor_compra_unitario,
                '0',
                '',
                "$codigo_nf",
                "$nf_id",
                "$nf_id"
            );
        }
    };
}

function consulta_parceiro($conecta, $parceiro_id, $filtro_valor)
{
    $select = "SELECT * from tb_parceiros where cl_id = $parceiro_id ";
    $consultar_parceiro = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consultar_parceiro);
    $valor = $linha["$filtro_valor"];
    return $valor;
}


function calcularPorcentagemDesconto($valorUnitario, $valorAtual)
{
    $porcentagemDesconto = (($valorAtual - $valorUnitario) / $valorAtual) * 100;
    return  number_format($porcentagemDesconto, 2);
}
function qtd_ajst($conecta, $codigo_nf)
{
    $select = "SELECT * from tb_ajuste_estoque where cl_codigo_nf ='$codigo_nf'";
    $consultar_ajuste_estoque = mysqli_query($conecta, $select);
    $qtd = mysqli_num_rows($consultar_ajuste_estoque);
    return $qtd;
}

function verificar_data_ajst($conecta, $codigo_nf)
{
    $select = "SELECT * from tb_ajuste_estoque where cl_codigo_nf ='$codigo_nf' ";
    $consultar_ajuste_estoque = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consultar_ajuste_estoque);
    $data_lancamento = $linha['cl_data_lancamento'];
    return $data_lancamento;
}


// function recebimento_nf($conecta, $fpg_id, $data, $serie_nf, $numero_nf, $parceiro_id, $classificacao, $valor, $documento, $codigo_nf) //receber a venda manualmente
// {


//     $descricao = utf8_decode("Recebimento referente a $serie_nf $numero_nf");
//     $insert = "INSERT INTO `tb_lancamento_financeiro` (`cl_data_lancamento`, `cl_data_vencimento`,
//         `cl_data_pagamento`, `cl_conta_financeira`, `cl_forma_pagamento_id`, `cl_parceiro_id`, `cl_tipo_lancamento`, 
//         `cl_status_id`, `cl_valor_bruto`, `cl_valor_liquido`,`cl_documento`, `cl_classificacao_id`, `cl_descricao`, `cl_nf`, `cl_serie_nf`, `cl_codigo_nf` )
//          VALUES ('$data', '$data', '$data', '$conta_financeira', '$fpg_id', '$parceiro_id', 'RECEITA', '2', '$valor', '$valor',
//           '$documento', '$classificacao','$descricao','$numero_nf','$serie_nf','$codigo_nf' )";
//     $operacao_insert = mysqli_query($conecta, $insert);
//     if ($operacao_insert) {
//         return true;
//     }
// }

function recebimento_nf(
    $conecta,
    $data,
    $data_vencimento,
    $data_pagamento,
    $conta_financeira,
    $fpg_id,
    $parceiro_id,
    $tipo,
    $status,
    $valor_bruto,
    $valor_liquido,
    $baixa_parcial,
    $juros,
    $taxa,
    $desconto,
    $documento,
    $classificacao_id,
    $descricao,
    $observacao,
    $numero_nf,
    $serie_nf,
    $codigo_nf,
    $ordem_servico,
    $pai_id
) //receber a venda manualmente
{
    $id_user_logado = verifica_sessao_usuario();
    if ($conta_financeira == "0") { //se não for informado nenhum valor a conta financeira, será atribuido a conta financeira que está no cadasto da forma de pagamento
        $conta_financeira = consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $fpg_id, "cl_conta_financeira"); //conta financeira que está no cadastro da forma de pagamento
    }
    if ($classificacao_id == "0") { //se não for informado nenhum valor a conta financeira, será atribuido a conta financeira que está no cadasto da forma de pagamento
        $classificacao_id = consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $fpg_id, "cl_classificao_id"); //CLASSIFICACAO DA FORMA DE PAGAMENTO
    }
    if ($parceiro_id == "") { //se não informado o parceiro 
        $parceiro_id = consulta_tabela($conecta, "tb_parametros", "cl_id", 8, "cl_valor"); //parceiro avulso
    }
    if ($tipo == "") { //se não informado o tipo 
        $tipo = consulta_tabela($conecta, "tb_status_recebimento", "cl_id", $status, "cl_tipo"); //tipo está atrelado ao status 
    }
    if ($serie_nf == "") {
        $serie_nf = "LCFAVUL";
    }

    $vlr_taxa_pgt = consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $fpg_id, "cl_taxa"); //valor da taxa
    $tipo_taxa_pgt = consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $fpg_id, "cl_tipo_taxa"); //tipo taxa % - porcentagem ou f -fixo

    if ($vlr_taxa_pgt > 0 and $tipo == "RECEITA") {
        if ($tipo_taxa_pgt == "f") {
            $valor_liquido = $valor_liquido - $vlr_taxa_pgt; //valor em real
        } elseif ($tipo_taxa_pgt == "%") {
            $vlr_taxa_pgt = ($vlr_taxa_pgt / 100) * $valor_liquido; //valor em real
            $valor_liquido = $valor_liquido - $vlr_taxa_pgt; //valor em real
        }
    }

    $insert = " INSERT INTO `tb_lancamento_financeiro` (`cl_data_lancamento`, `cl_data_vencimento`, `cl_data_pagamento`, 
    `cl_conta_financeira`, `cl_forma_pagamento_id`, `cl_parceiro_id`, `cl_tipo_lancamento`, `cl_status_id`, `cl_valor_bruto`, `cl_valor_liquido`, 
    `cl_bx_parcial`, `cl_juros`, `cl_taxa`, `cl_desconto`, `cl_documento`, `cl_classificacao_id`, 
    `cl_descricao`, `cl_observacao`, `cl_nf`, `cl_serie_nf`,
     `cl_codigo_nf`, `cl_ordem_servico`,`cl_lancamento_pai_id`,`cl_taxa_cartao`,`cl_usuario_id` ) VALUES ('$data', '$data_vencimento', '$data_pagamento', '$conta_financeira', '$fpg_id', '$parceiro_id', 
    '$tipo', '$status', '$valor_bruto', '$valor_liquido', '$baixa_parcial',
     '$juros', '$taxa', '$desconto', '$documento', '$classificacao_id', '$descricao', '$observacao', '$numero_nf',
     '$serie_nf', '$codigo_nf', '$ordem_servico', '$pai_id', '$vlr_taxa_pgt','$id_user_logado' )";
    $operacao_insert = mysqli_query($conecta, $insert);
    if ($operacao_insert) {
        $novo_id = mysqli_insert_id($conecta);
        return $novo_id;
    } else {
        return false;
    }
}

function update_status_nf($conecta, $status, $data_recebimento, $usuario_recebimento, $nf_id, $forma_pagamento)
{ //nf saida

    if ($status == 2) { //recebido
        $data_recebimento = $data_recebimento;
        $usuario_recebimento = $usuario_recebimento;
        $forma_pagamento = $forma_pagamento;
    } else {
        $data_recebimento = "";
        $usuario_recebimento = "";
        $forma_pagamento = "";
    }
    $alterar_forma_pagamento = verficar_paramentro($conecta, "tb_parametros", "cl_id", "16"); //alterar a forma de pagamento pela tela de recebimento

    $update = "UPDATE `tb_nf_saida` SET `cl_status_recebimento` = '$status', 
    `cl_data_recebimento` = '$data_recebimento', `cl_usuario_id_recebimento` = '$usuario_recebimento' ";
    if ($alterar_forma_pagamento == "S") {
        $update .= ", `cl_forma_pagamento_id` = '$forma_pagamento' ";
    }
    $update .= " WHERE `tb_nf_saida`.`cl_id` = $nf_id ";

    $operacao_update = mysqli_query($conecta, $update);
    if ($operacao_update) {
        return true;
    }
}


function update_status_nf_entrada($conecta, $status, $usuario_provisionamento, $nf_id, $forma_pagamento)
{ //nf entrada

    $alterar_forma_pagamento = verficar_paramentro($conecta, "tb_parametros", "cl_id", "16"); //alterar a forma de pagamento pela tela de recebimento
    $update = "UPDATE `tb_nf_entrada` SET `cl_status_provisionamento` = '$status',
     `cl_usuario_id_provisionamento` = '$usuario_provisionamento' ";
    if ($alterar_forma_pagamento == "S") {
        $update .= ", `cl_forma_pagamento_id` = '$forma_pagamento' ";
    }
    $update .= " WHERE `cl_id` = $nf_id ";

    $operacao_update = mysqli_query($conecta, $update);
    if ($operacao_update) {
        return true;
    }
}

function atualizar_estoque_subitens($conecta, $item_id_nf_pai)
{ //atualizar estoque do subitens cancelado em venda
    mysqli_begin_transaction($conecta);
    $erro = false;

    $select = "SELECT * from tb_nf_saida_item where cl_id_pai_delivery ='$item_id_nf_pai' ";
    $consulta = mysqli_query($conecta, $select);
    while ($linha = mysqli_fetch_assoc($consulta)) {
        $produto_id = $linha['cl_item_id'];
        $quantidade_vendida = $linha['cl_quantidade'];

        $quantidade_atual = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_estoque"));

        $nova_quantidade = $quantidade_atual + $quantidade_vendida;
        $update_estoque = update_registro($conecta, 'tb_produtos', "cl_id", "$produto_id", "", "", "cl_estoque", "$nova_quantidade");

        if (!$update_estoque) {
            $erro = true;
        }
    }

    if (!$erro) {
        mysqli_commit($conecta); // Confirma a transação se tudo ocorreu bem
        return true;
    } else {
        mysqli_rollback($conecta); // Desfaz a transação em caso de erro
        return false;
    }
}

function delete_item_nf($conecta, $id_item_nf, $produto_id, $codigo_nf, $quantidade, $id_user_logado, $data)
{
    $estoque = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_estoque")); //verificar o estoque atual do produto
    $nome_usuario_logado = (consulta_tabela($conecta, "tb_users", "cl_id", $id_user_logado, "cl_usuario")); //Nome do usuário que está removendo o produto da venda
    $numero_nf = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_numero_nf")); //numero da venda
    $serie_nf = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_serie_nf")); //serie nf




    $select = "SELECT * from tb_ajuste_estoque where cl_produto_id ='$produto_id'
    and cl_codigo_nf= '$codigo_nf' and cl_quantidade ='$quantidade' ";
    $consultar_ajst = mysqli_query($conecta, $select);
    $qtd_registros = mysqli_num_rows($consultar_ajst); //verificar se o ajuste feito apos a finalização da venda está no ajuste de estoque
    if ($qtd_registros > 0) { //se tiver registro na tabela ajuste de estoque, atualizar o status para cancelado
        // Inicia uma nova transação
        mysqli_begin_transaction($conecta);
        try {
            $update = "UPDATE `tb_ajuste_estoque` SET `cl_status` = 'cancelado' 
       WHERE `tb_ajuste_estoque`.`cl_produto_id` = '$produto_id' and  cl_codigo_nf='$codigo_nf' and cl_id_nf='$id_item_nf' ";
            $operacao_update  = mysqli_query($conecta, $update);
            if ($operacao_update) { //deletar produto da nf
                $delete = "DELETE FROM `tb_nf_saida_item` WHERE `tb_nf_saida_item`.`cl_id` = $id_item_nf ";
                $operacao_delete = mysqli_query($conecta, $delete);
                if ($operacao_delete) { //atualizar o estoque do produto 
                    $novo_estoque = $estoque + $quantidade;
                    $update = "UPDATE `tb_produtos` SET `cl_estoque` = '$novo_estoque' 
                WHERE `tb_produtos`.`cl_id` = '$produto_id' ";
                    $operacao_update = mysqli_query($conecta, $update);
                    if ($operacao_update) {

                        if (atualizar_estoque_subitens($conecta, $id_item_nf)) { //atualizar o estoque do subitem
                            update_registro($conecta, 'tb_ajuste_estoque', "cl_id_nf_pai", "$id_item_nf", 'cl_codigo_nf', "$codigo_nf", "cl_status", "cancelado"); //CANCELAR DO AJUSTE DE ESTOQUE OS SUBITENS
                            remover_linha($conecta, "tb_nf_saida_item", "cl_id_pai_delivery", $id_item_nf); //REMOVER OS SUBITENS NA TABELA NF_SAIDA_ITEMS
                        }

                        if (recalcular_valor_nf_saida($conecta, $codigo_nf)) { //atualizar valor total da nota
                            $mensagem = utf8_decode("Usuário $nome_usuario_logado removeu $quantidade produto(s) de código $produto_id, $serie_nf  $numero_nf");
                            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
            // Commit a transação se todas as operações forem bem-sucedidas
            mysqli_commit($conecta);
            return true;
        } catch (Exception $e) {
            // Desfaz a transação em caso de erro
            mysqli_rollback($conecta);
            return false;
        }
    } else { //remover apenas da venda, a vennda ainda não foi finalizada
        $delete = "DELETE FROM `tb_nf_saida_item` WHERE `tb_nf_saida_item`.`cl_id` = $id_item_nf or cl_id_pai_delivery='$id_item_nf' and cl_codigo_nf='$codigo_nf'";
        $operacao_delete = mysqli_query($conecta, $delete);
        if ($operacao_delete) {
            return true;
        } else {
            return false;
        }
    }
}



function verificar_status_recebimento_vnd($conecta, $id, $codigo_nf)
{ //verificar se a venda já está recebida
    $select = "SELECT * from tb_nf_saida where cl_id =$id and codigo_nf= '$codigo_nf' ";
    $consultar_status_recebiento = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consultar_status_recebiento);
    $status_recebimento = $linha['cl_status_recebimento'];
    if ($status_recebimento == "2") { //pendente
        return true;
    } else {
        return false;
    }
}

// function recalcular_valor_nf($conecta, $codigo_nf)
// { //atualizar valor bruto e liquido da nf

//     $select = "SELECT * from tb_nf_saida where cl_codigo_nf ='$codigo_nf' ";
//     $consultar_nf = mysqli_query($conecta, $select);
//     $qtd_nf = mysqli_num_rows($consultar_nf);

//     if ($qtd_nf > 0) { //verificar se existe uma nf já feita
//         $valor_desconto = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_valor_desconto")); //verificar o estoque atual do produto
//         $valor_entrega_delivery = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_valor_entrega_delivery")); //verificar o estoque atual do produto

//         $select = "SELECT sum(cl_valor_total) as valor from tb_nf_saida_item where cl_codigo_nf ='$codigo_nf' and cl_status ='1' ";
//         $consultar_produto_nf = mysqli_query($conecta, $select);
//         $linha = mysqli_fetch_assoc($consultar_produto_nf);
//         $valor_bruto = $linha['valor'];

//         $valor_liquido = $valor_entrega_delivery + $valor_bruto - $valor_desconto;

//         $update = "UPDATE `tb_nf_saida` SET `cl_valor_bruto` = '$valor_bruto',`cl_valor_liquido` = '$valor_liquido' WHERE `tb_nf_saida`.`cl_codigo_nf` = '$codigo_nf' ";
//         $operacao_update_nf = mysqli_query($conecta, $update);
//         if ($operacao_update_nf) {
//             return true;
//         } else {
//             return false;
//         }
//         //adicionar ao ajuste de estoque
//     }
// }


function cancelar_nf($conecta, $id_nf, $codigo_nf, $id_user_logado, $data)
{
    // $estoque = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_estoque")); //verificar o estoque atual do produto
    $nome_usuario_logado = (consulta_tabela($conecta, "tb_users", "cl_id", $id_user_logado, "cl_usuario")); //Nome do usuário que está removendo o produto da venda
    $numero_nf = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_numero_nf")); //numero da venda
    $serie_nf = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_serie_nf")); //serie nf

    $valor_credito_nf = (consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id_nf, "cl_valor_credito"));
    $parceiro_id = (consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id_nf, "cl_parceiro_id"));
    $valor_credito_parceiro_id = (consulta_tabela($conecta, "tb_parceiros", "cl_id", $parceiro_id, "cl_valor_credito"));


    $select = "SELECT * FROM `tb_nf_saida_item` WHERE `tb_nf_saida_item`.`cl_codigo_nf` = '$codigo_nf' ";
    $consultar_nf_saida_item = mysqli_query($conecta, $select);
    $qtd_nf_saida_item = mysqli_num_rows($consultar_nf_saida_item);

    $select = "SELECT * FROM `tb_lancamento_financeiro` WHERE `tb_lancamento_financeiro`.`cl_codigo_nf` = '$codigo_nf' ";
    $consultar_lancamento_financeiro = mysqli_query($conecta, $select);
    $qtd_lancamento_financeiro = mysqli_num_rows($consultar_nf_saida_item);

    $select = "SELECT * FROM `tb_nf_saida` WHERE `tb_nf_saida`.`cl_codigo_nf` = '$codigo_nf' ";
    $consultar_nf_saida = mysqli_query($conecta, $select);
    $qtd_nf_saida = mysqli_num_rows($consultar_nf_saida);
    if ($qtd_nf_saida > 0) { //atualizar o status da nf_saida para cancelado
        $update = "UPDATE `tb_nf_saida` SET `cl_status_venda` = '3' 
        WHERE `tb_nf_saida`.`cl_codigo_nf` = '$codigo_nf' and  cl_id='$id_nf' ";
        $operacao_update  = mysqli_query($conecta, $update);
        if ($operacao_update) {
            if ($valor_credito_nf > 0) { //estonar valor de credito se tiver
                $valor_credito_estornado = $valor_credito_parceiro_id + $valor_credito_nf;
                update_registro($conecta, "tb_parceiros", "cl_id", $parceiro_id, "", "", "cl_valor_credito", $valor_credito_estornado);
            }

            if ($qtd_nf_saida_item > 0) {
                while ($linha = mysqli_fetch_assoc($consultar_nf_saida_item)) {
                    $id_item_nf = $linha['cl_id'];
                    $produto_id = $linha['cl_item_id'];
                    $quantidade = $linha['cl_quantidade'];
                    $estoque = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_estoque")); //verificar o estoque atual do produto
                    $novo_estoque = $estoque + $quantidade;

                    $update = "UPDATE `tb_nf_saida_item` SET `cl_status` = '3' 
                WHERE  cl_codigo_nf='$codigo_nf' ";
                    $operacao_update  = mysqli_query($conecta, $update); //atualizar o status dos itens na tabela nf_saida_item para cancelado
                    if ($operacao_update) { //atualizar para cancelado o ajuste de estoque
                        $update = "UPDATE `tb_ajuste_estoque` SET `cl_status` = 'cancelado' 
                        WHERE `tb_ajuste_estoque`.`cl_produto_id` = '$produto_id' and  cl_codigo_nf='$codigo_nf' and cl_id_nf='$id_item_nf' ";
                        $operacao_update  = mysqli_query($conecta, $update);
                        if ($operacao_update) { //atualizar o estoque
                            $update = "UPDATE `tb_produtos` SET `cl_estoque` = '$novo_estoque' 
                            WHERE `tb_produtos`.`cl_id` = '$produto_id' ";
                            $operacao_update  = mysqli_query($conecta, $update);
                            if (!$operacao_update) {
                                return false;
                                break;
                            }
                        } else {
                            return false;
                            break;
                        }
                    } else {
                        return false;
                        break;
                    }
                }
            }
            if ($qtd_lancamento_financeiro > 0) {
                $delete = "DELETE FROM `tb_lancamento_financeiro` WHERE `tb_lancamento_financeiro`.`cl_codigo_nf` = '$codigo_nf'";
                $operacao_delete  = mysqli_query($conecta, $delete);
                if (!$operacao_delete) { //deletar o recebimento da venda
                    return false;
                }
            }
            $mensagem = utf8_decode("Usuário $nome_usuario_logado cancelou a $serie_nf $numero_nf ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

            return true;
        } else {
            return false;
        }
    }
}

function remover_nf_faturamento($conecta, $id, $codigo_nf, $id_user_logado, $data) //remover venda do faturamento, parametro id não é mais utilizado
{ //verificar se a venda já está recebida

    if (empty($data)) {
        global $data;
    }

    $nome_usuario_logado = (consulta_tabela($conecta, "tb_users", "cl_id", $id_user_logado, "cl_usuario")); //Nome do usuário que está realizando a ação
    $numero_nf = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_numero_nf")); //numero da venda
    $serie_nf = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_serie_nf")); //serie nf


    $update = "UPDATE `tb_nf_saida` SET `cl_status_recebimento` = '1', `cl_usuario_id_recebimento` = '', `cl_data_recebimento` = '' 
    WHERE  cl_codigo_nf ='$codigo_nf' ";
    $operacao_update  = mysqli_query($conecta, $update);
    if ($operacao_update) {
        $delete = "DELETE FROM `tb_lancamento_financeiro` WHERE `cl_codigo_nf` = '$codigo_nf'";
        $operacao_delete  = mysqli_query($conecta, $delete);
        if ($operacao_delete) {
            $mensagem = utf8_decode("Removeu a $serie_nf $numero_nf do faturamento");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            return true;
        } else {
            return false;
        }
    }
}


function remover_nf_os_faturamento($conecta, $codigo_nf, $usuario_id) //remover os do faturamento
{ //verificar se a venda já está recebida
    global $data;
    $nome_usuario_logado = (consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario")); //Nome do usuário que está realizando a ação
    $numero_nf = (consulta_tabela($conecta, "tb_os", "cl_codigo_nf", $codigo_nf, "cl_numero_nf")); //numero da nf
    $serie_nf = (consulta_tabela($conecta, "tb_os", "cl_codigo_nf", $codigo_nf, "cl_serie_nf")); //serie nf

    $codigo_nf_material = (consulta_tabela($conecta, "tb_os", "cl_codigo_nf", $codigo_nf, "cl_codigo_nf_material"));
    $codigo_nf_servico = (consulta_tabela($conecta, "tb_os", "cl_codigo_nf", $codigo_nf, "cl_codigo_nf_servico"));
    $codigo_nf_recibo = (consulta_tabela($conecta, "tb_os", "cl_codigo_nf", $codigo_nf, "cl_codigo_nf_recibo"));

    if (!empty($codigo_nf_material) or !empty($codigo_nf_servico)) { //verificar se está preenchido, material e seviço, contem o codigo nf dos dois
        $update = "UPDATE `tb_os` SET `cl_situacao` = '0', `cl_codigo_nf_material` = '', `cl_codigo_nf_servico` = '', `cl_nf_garantia_loja` = '', `cl_validade_garantia_loja` = '',`cl_data_nf_garantia_loja` = ''  WHERE `cl_codigo_nf_material` = '$codigo_nf_material'";
        $operacao_update  = mysqli_query($conecta, $update);
        if (!$operacao_update) {
            return array("status" => false, "response" => "Erro, favor contatar o suporte");
        } else {
            /*processo de remover e cancelar o documento de saida do material*/
            $material_nf_id = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf_material, "cl_id")); //id da nf material saida 
            remover_nf_faturamento($conecta, '', $codigo_nf_material, $usuario_id, $data); //remover a nf do material do faturamento
            cancelar_nf($conecta, $material_nf_id, $codigo_nf_material, $usuario_id, $data); //cancelar a nf de saida

            /*processo de remover e cancelar o documento de saida do serviço*/
            $servico_nf_id = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf_servico, "cl_id")); //id da nf serviço saida 
            remover_nf_faturamento($conecta, '', $codigo_nf_servico, $usuario_id, $data); //remover a nf de serviço do faturamento
            cancelar_nf($conecta, $servico_nf_id, $codigo_nf_servico, $usuario_id, $data); //cancelar a nf de serviço de saida

        }
    } elseif (!empty($codigo_nf_recibo)) {
        $update = "UPDATE `tb_os` SET `cl_situacao` = '0', `cl_codigo_nf_recibo` = '', `cl_nf_garantia_loja` = '', `cl_validade_garantia_loja` = '',`cl_data_nf_garantia_loja` = '' WHERE `cl_codigo_nf_recibo` = '$codigo_nf_recibo'";
        $operacao_update  = mysqli_query($conecta, $update);
        if (!$operacao_update) {
            return array("status" => false, "response" => "Erro, favor contatar o suporte");
        } else {
            $material_nf_id = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf_recibo, "cl_id")); //id da nf material saida 
            remover_nf_faturamento($conecta, '', $codigo_nf_recibo, $usuario_id, $data); //remover a nf recibo do faturamento
            cancelar_nf($conecta, $material_nf_id, $codigo_nf_recibo, $usuario_id, $data); //cancelar a nf recibo de saida
        }
    }

    $mensagem = utf8_decode("Removeu do faturamento a $serie_nf$numero_nf");
    registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

    // Se tudo ocorreu bem, confirme a transação
    return array("status" => true, "response" => "$serie_nf$numero_nf removida do faturamento com suceso");
}




function atualizar_status_produto_adicional($conecta, $produto_adicional_id, $produto_id, $acao, $ischeck) //funcão referente ao adicional do delivery no produto
{
    $select = "SELECT * from tb_produto_adicional_delivery WHERE cl_produto_adicional_id ='$produto_adicional_id' and cl_produto_id ='$produto_id'
     and cl_obrigatorio ='NAO' "; //tipo adicinou poara delivery
    $consultar_adicionais = mysqli_query($conecta, $select);
    $qtd_consultar_adicionais = mysqli_num_rows($consultar_adicionais);
    if ($qtd_consultar_adicionais > 0) {
        $linha = mysqli_fetch_assoc($consultar_adicionais);
        $id = $linha['cl_id'];
        $status = $linha['cl_status_ativo'];

        if ($status == "NAO" and $acao == "INCLUIR") {
            $update = "UPDATE `tb_produto_adicional_delivery` SET `cl_status_ativo` = 'SIM' 
    WHERE `tb_produto_adicional_delivery`.`cl_id` = '$id' ";
            $operacao_update  = mysqli_query($conecta, $update);
        } elseif ($status == "SIM" and $acao == "REMOVER") {
            $update = "UPDATE `tb_produto_adicional_delivery` SET `cl_status_ativo` = 'NAO' 
            WHERE `tb_produto_adicional_delivery`.`cl_id` = '$id' ";
            $operacao_update  = mysqli_query($conecta, $update);
        }
    } else {
        if ($ischeck == 'CHECK') {
            $insert = "INSERT INTO `tb_produto_adicional_delivery` (`cl_produto_adicional_id`, `cl_produto_id`,
            `cl_status_ativo`, `cl_obrigatorio`, `cl_complemento`) VALUES ('$produto_adicional_id', '$produto_id', 'SIM', 'NAO','NAO') ";
            $operacao_inserir  = mysqli_query($conecta, $insert);
        }
    }
}


function verifica_status_produto_adicional($conecta, $produto_adicional_id, $produto_id) //funcão referente ao adicional do delivery no produto
{
    $select = "SELECT * from tb_produto_adicional_delivery WHERE cl_produto_adicional_id ='$produto_adicional_id' and cl_produto_id ='$produto_id' and cl_obrigatorio ='NAO' "; //tipo adicinou poara delivery
    $consultar_adicionais = mysqli_query($conecta, $select);
    $qtd_consultar_adicionais = mysqli_num_rows($consultar_adicionais);
}

function atualizar_status_produto_adicional_obrigatorio($conecta, $produto_adicional_id, $produto_id, $acao, $ischeck) //funcão referente ao adicional do delivery no produto
{
    $select = "SELECT * from tb_produto_adicional_delivery WHERE cl_produto_adicional_id ='$produto_adicional_id' and cl_produto_id ='$produto_id' and cl_obrigatorio ='SIM' "; //tipo adicinou poara delivery
    $consultar_adicionais = mysqli_query($conecta, $select);
    $qtd_consultar_adicionais = mysqli_num_rows($consultar_adicionais);
    if ($qtd_consultar_adicionais > 0) {
        $linha = mysqli_fetch_assoc($consultar_adicionais);
        $id = $linha['cl_id'];
        $status = $linha['cl_status_ativo'];

        if ($status == "NAO" and $acao == "INCLUIR") {
            $update = "UPDATE `tb_produto_adicional_delivery` SET `cl_status_ativo` = 'SIM' 
    WHERE `tb_produto_adicional_delivery`.`cl_id` = '$id' ";
            $operacao_update  = mysqli_query($conecta, $update);
        } elseif ($status == "SIM" and $acao == "REMOVER") {
            $update = "UPDATE `tb_produto_adicional_delivery` SET `cl_status_ativo` = 'NAO' 
            WHERE `tb_produto_adicional_delivery`.`cl_id` = '$id' ";
            $operacao_update  = mysqli_query($conecta, $update);
        }
    } else {
        if ($ischeck == 'CHECK') {
            $insert = "INSERT INTO `tb_produto_adicional_delivery` (`cl_produto_adicional_id`, `cl_produto_id`,
         `cl_status_ativo`, `cl_obrigatorio`,`cl_complemento`) VALUES ('$produto_adicional_id', '$produto_id', 'SIM', 'SIM','NAO') ";
            $operacao_inserir  = mysqli_query($conecta, $insert);
        }
    }
}


function verifica_status_produto_adicional_obrigatorio($conecta, $produto_adicional_id, $produto_id) //funcão referente ao adicional do delivery no produto
{
    $select = "SELECT * from tb_produto_adicional_delivery WHERE cl_produto_adicional_id ='$produto_adicional_id' and cl_produto_id ='$produto_id' and cl_obrigatorio ='SIM' "; //tipo adicinou poara delivery
    $consultar_adicionais = mysqli_query($conecta, $select);
    $qtd_consultar_adicionais = mysqli_num_rows($consultar_adicionais);
}


function atualizar_status_produto_complemento($conecta, $produto_adicional_id, $produto_id, $acao, $ischeck) //funcão referente ao adicional do delivery no produto
{
    $select = "SELECT * from tb_produto_adicional_delivery WHERE cl_produto_adicional_id ='$produto_adicional_id' and cl_produto_id ='$produto_id' and cl_complemento ='SIM' "; //tipo adicinou poara delivery
    $consultar = mysqli_query($conecta, $select);
    $qtd_consultar = mysqli_num_rows($consultar);
    if ($qtd_consultar > 0) {
        $linha = mysqli_fetch_assoc($consultar);
        $id = $linha['cl_id'];
        $status = $linha['cl_status_ativo'];

        if ($status == "NAO" and $acao == "INCLUIR") {
            $update = "UPDATE `tb_produto_adicional_delivery` SET `cl_status_ativo` = 'SIM' 
    WHERE `tb_produto_adicional_delivery`.`cl_id` = '$id' ";
            $operacao_update  = mysqli_query($conecta, $update);
        } elseif ($status == "SIM" and $acao == "REMOVER") {
            $update = "UPDATE `tb_produto_adicional_delivery` SET `cl_status_ativo` = 'NAO' 
            WHERE `tb_produto_adicional_delivery`.`cl_id` = '$id' ";
            $operacao_update  = mysqli_query($conecta, $update);
        }
    } else {
        if ($ischeck == 'CHECK') {
            $insert = "INSERT INTO `tb_produto_adicional_delivery` (`cl_produto_adicional_id`, `cl_produto_id`,
         `cl_status_ativo`, `cl_obrigatorio`,`cl_complemento`) VALUES ('$produto_adicional_id', '$produto_id', 'SIM', 'NAO','SIM') ";
            $operacao_inserir  = mysqli_query($conecta, $insert);
        }
    }
}


function verifica_status_produto_adicional_complemento($conecta, $produto_adicional_id, $produto_id) //funcão referente ao adicional do delivery no produto
{
    $select = "SELECT * from tb_produto_adicional_delivery WHERE cl_produto_adicional_id ='$produto_adicional_id' and cl_produto_id ='$produto_id' and cl_obrigatorio ='NAO' AND cl_complemento='SIM' "; //tipo adicinou poara delivery
    $consultar_complmento = mysqli_query($conecta, $select);
    $qtd_consultar_complemento = mysqli_num_rows($consultar_complmento);
}




function atualizar_valor_cotacao($conecta, $codigo_nf, $valor_desconto)
{
    if ($valor_desconto != "false") { //atualizar o valor do desconto
        $update = "UPDATE `tb_cotacao` SET `cl_valor_desconto` = '$valor_desconto' WHERE `tb_cotacao`.`cl_codigo_nf` ='$codigo_nf' ";
        $operacao_update  = mysqli_query($conecta, $update);
    }

    $select = "SELECT sum(cl_quantidade*cl_valor_unitario) as total FROM tb_cotacao_item where cl_codigo_nf = '$codigo_nf'";
    $consultar = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consultar);
    $valor_produtos = $linha['total'];

    $select = "SELECT * FROM tb_cotacao where cl_codigo_nf = '$codigo_nf'";
    $consultar = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consultar);
    $desconto = $linha['cl_valor_desconto'];

    if ($valor_produtos == 0) {
        $desconto = 0;
    }
    $valor_total = $valor_produtos - $desconto;

    $update = "UPDATE `tb_cotacao` SET `cl_valor_bruto` = '$valor_produtos' , `cl_valor_desconto` = '$desconto',
     `cl_valor_liquido` = '$valor_total' WHERE `tb_cotacao`.`cl_codigo_nf` ='$codigo_nf' ";
    $operacao_update  = mysqli_query($conecta, $update);
    if ($operacao_update) {
        return true;
    } else {
        return false;
    }
}

function insert_produto_cotacao(
    $conecta,
    $data_lancamento,
    $codigo_nf,
    $cl_vendedor_id,
    $item_id,
    $descricao_item,
    $referencia,
    $quantidade,
    $unidade,
    $valor_unitario,
    $desconto_item,
    $valor_total,
    $prazo_entrega,
    $status_item
) {
    $numero_nf = consulta_tabela($conecta, "tb_cotacao", "cl_codigo_nf", $codigo_nf, "cl_numero_nf");
    $serie_nf = consulta_tabela($conecta, "tb_cotacao", "cl_codigo_nf", $codigo_nf, "cl_serie_nf");

    $insert = "INSERT INTO `tb_cotacao_item` (`cl_data_movimento`,`cl_serie_nf`,`cl_numero_nf`, `cl_codigo_nf`, `cl_vendedor_id`,
 `cl_item_id`, `cl_descricao_item`, `cl_referencia`, `cl_quantidade`, `cl_unidade`, `cl_valor_unitario`, `cl_desconto_item`,
  `cl_valor_total`, `cl_prazo_entrega`, `cl_status_item`)VALUES ('$data_lancamento','$serie_nf','$numero_nf', '$codigo_nf', '$cl_vendedor_id', '$item_id', '$descricao_item', '$referencia', '$quantidade', 
  '$unidade', '$valor_unitario', '$desconto_item', '$valor_total', '$prazo_entrega', '$status_item')";
    $operacao_insert = mysqli_query($conecta, $insert);
    if ($operacao_insert) {
        atualizar_valor_cotacao($conecta, $codigo_nf, 'false');
    } else {
        return false;
    }
}

function descontoAcimaPermitido($valorTotal, $valorDesconto, $valorMaxDescontoPorcentagem)
{
    // Calcula o desconto em porcentagem
    $descontoPorcentagem = ($valorDesconto / $valorTotal) * 100;

    // Verifica se o desconto está acima do permitido
    if ($descontoPorcentagem > $valorMaxDescontoPorcentagem) {
        return true;
    } else {
        return false;
    }
}

function demilitar_tipo_adicionais($conecta, $produto_id, $subgrupo_id, $quantidade)
{ //Delimitar quantidade de tipos de adicionais por produtos
    $select = "SELECT * from tb_subgrupo_limite_delivery where cl_produto_id ='$produto_id' and cl_subgrupo_id ='$subgrupo_id' ";
    $consulta = mysqli_query($conecta, $select);
    $qtd_consulta = mysqli_num_rows($consulta);
    if ($qtd_consulta > 0) { //update // atualizar a quantidade delimitada para o usuario escolher o tipo de categoria que ele quer, ex 3 bananas(frutas),2 chocolates ..
        $linha = mysqli_fetch_assoc($consulta);
        $id = $linha['cl_id'];
        $update = "UPDATE `tb_subgrupo_limite_delivery` SET `cl_quantidade` = '$quantidade'  WHERE `tb_subgrupo_limite_delivery`.`cl_id` ='$id' ";
        $operacao_update  = mysqli_query($conecta, $update);
    } else { //insert
        $insert = "INSERT INTO `tb_subgrupo_limite_delivery`
         ( `cl_produto_id`, `cl_subgrupo_id`, `cl_quantidade`) VALUES ('$produto_id', '$subgrupo_id', '$quantidade')";
        $operacao_insert = mysqli_query($conecta, $insert);
    }
}

function formatarTexto($texto)
{
    return ucfirst(strtolower($texto));
}



// function status_pedido_delivery($conecta, $status)
// {
//     if ($status == '5') {
//         $update = "UPDATE `tb_nf_saida` SET `cl_status_pedido_delivery` = '$status'  WHERE `tb_nf_saida`.`cl_id` ='$pedido_id' ";
//         $operacao_update  = mysqli_query($conecta, $update);
//         if ($operacao_update) {
//             $retornar["dados"] = array("sucesso" => true, "title" => "Status alterado com sucesso");
//             //registrar no log

//         } else {
//             $retornar["dados"] = array("sucesso" => false, "title" => "Erro, favor contatar o suporte tecnico");
//         }
//     }
// }

function verifica_sessao_usuario()
{
    if (!isset($_SESSION)) {
        session_start();
    }
    if ($_SESSION["user_session_portal"]) { //oegar as informações do usuario
        $usuario_id = $_SESSION["user_session_portal"];
        return $usuario_id;
    }
}

function update_registro($conecta, $tabela, $coluna_filtro, $valor_filtro, $coluna_filtro2, $valor_filtro2, $coluna_referencia, $valor_referencia)
{
    $update = "UPDATE $tabela SET $coluna_referencia = '$valor_referencia'  WHERE $coluna_filtro ='$valor_filtro'";
    if ($coluna_filtro2 != "") {
        $update .= " and $coluna_filtro2 = '$valor_filtro2' ";
    }
    $operacao_update  = mysqli_query($conecta, $update);
    if ($operacao_update) {
        return true;
    } else {
        return false;
    }
}

function cancelar_produto_nf_delivery($conecta, $nf_id)
{ //rotina de cancelamento de pedido, devolver o produto para o estoque
    $codigo_nf = (consulta_tabela($conecta, "tb_nf_saida", "cl_id", "$nf_id", "cl_codigo_nf"));

    $select = "SELECT * FROM tb_nf_saida_item where cl_codigo_nf ='$codigo_nf' ";
    $consulta = mysqli_query($conecta, $select);
    while ($linha = mysqli_fetch_assoc($consulta)) {
        $nf_saida_item = $linha['cl_id'];
        $produto_id = $linha['cl_item_id'];
        $quantidade_nf = $linha['cl_quantidade'];

        $quantidade_prd = (consulta_tabela($conecta, "tb_produtos", "cl_id", "$produto_id", "cl_estoque"));
        $quantidade_atual =  $quantidade_prd + $quantidade_nf;

        if (update_registro($conecta, "tb_nf_saida_item", "cl_codigo_nf", $codigo_nf, "cl_id", "$nf_saida_item", "cl_status", "3")) { //alterar status do produto para cancelado
            if (update_registro($conecta, "tb_ajuste_estoque", "cl_codigo_nf", $codigo_nf, "cl_produto_id", "$produto_id", "cl_status", "cancelado")) { //cancelar ajuste de estoque
                update_registro($conecta, "tb_produtos", "cl_id", $produto_id, "", "", "cl_estoque", "$quantidade_atual");     //cancelar ajuste de estoque
            }
        }
    }
}


function formatar_hora_delivery($horaFormatoH_i)
{
    // Obtém a data atual
    $dataAtual = new DateTime();
    // Formata a data atual para o formato 'Y-m-d' (apenas a data)
    $dataFormatadaY_m_d = $dataAtual->format('Y-m-d');

    // Concatena a data formatada com a hora no formato 'H:i'
    $dataHoraCompleta = $dataFormatadaY_m_d . ' ' . $horaFormatoH_i . ':00';

    // Cria um novo objeto DateTime com a data e hora completas
    $dataHoraFinal = new DateTime($dataHoraCompleta);

    // Formata a data e hora final no formato 'Y-m-d H:i:s'
    $dataHoraFormatada = $dataHoraFinal->format('Y-m-d H:i:s');

    return $dataHoraFormatada; // Saída: 2023-08-07 04:51:00

}
function atualiza_delivery_funcionamento($conecta, $hora_abertura, $hora_fechamento, $funcionamento, $id)
{
    $hora_abertura = formatar_hora_delivery($hora_abertura);
    $hora_fechamento = formatar_hora_delivery($hora_fechamento);

    $update = "UPDATE tb_data_funcionamento SET cl_hora_abertura = '$hora_abertura',cl_hora_fechamento='$hora_fechamento',
    cl_status_funcionamento='$funcionamento' WHERE cl_id ='$id' ";
    $operacao_update  = mysqli_query($conecta, $update);
    if ($operacao_update) {
        return true;
    } else {
        return false;
    }
}

function atualiza_frete_delivery($conecta, $id, $valor, $data_promocao)
{
    $update = "UPDATE tb_frete_delivery SET cl_valor = '$valor',cl_promocao_frete_gratis_delivery='$data_promocao'
     WHERE cl_id ='$id' ";
    $operacao_update  = mysqli_query($conecta, $update);
    if ($operacao_update) {
        return true;
    } else {
        return false;
    }
}




function insertAcompanhamentoNf($conecta, $array, $status_produto_nf, $usuario_id, $qtd_produto, $id_pai, $tipo_item, $tipo_adicional, $data, $observacao)
{

    mysqli_begin_transaction($conecta);
    $erro = false;
    foreach ($array as $prd_id) {

        $codigo_nf = (consulta_tabela($conecta, "tb_nf_saida_item", "cl_id", $id_pai, "cl_codigo_nf"));
        $descricao = (consulta_tabela($conecta, "tb_produtos", "cl_id", $prd_id, "cl_descricao"));
        $unidade_id = (consulta_tabela($conecta, "tb_produtos", "cl_id", $prd_id, "cl_und_id"));
        $sigla_unidade = (consulta_tabela($conecta, "tb_unidade_medida", "cl_id", $unidade_id, "cl_sigla"));
        $referencia = (consulta_tabela($conecta, "tb_produtos", "cl_id", $prd_id, "cl_referencia"));
        $valor_unitario = (consulta_tabela($conecta, "tb_produtos", "cl_id", $prd_id, "cl_preco_venda"));

        $serie_nf = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_serie_nf"));
        $numero_nf = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_numero_nf"));

        if ($tipo_adicional == "GRATIS") {
            $valor_unitario = 0;
            $valor_total = 0;
        } else {
            $valor_total = $valor_unitario * $qtd_produto;
        }

        $insert = "INSERT INTO `tb_nf_saida_item` (`cl_data_movimento`,`cl_codigo_nf`,`cl_usuario_id`,`cl_serie_nf`,`cl_numero_nf`,`cl_item_id`, `cl_descricao_item`,`cl_quantidade`, `cl_unidade`, `cl_valor_unitario`,
         `cl_valor_total`, `cl_referencia`, `cl_status`, `cl_id_delivery`, `cl_tipo_item_delivery`, `cl_id_pai_delivery`, `cl_tipo_adicional_delivery`, `cl_observacao_delivery` ) VALUES
          ('$data','$codigo_nf','$usuario_id','$serie_nf','$numero_nf','$prd_id', '$descricao', '$qtd_produto', '$sigla_unidade','$valor_unitario','$valor_total',
          '$referencia','$status_produto_nf','$id_pai', '$tipo_item','$id_pai','$tipo_adicional','$observacao')";
        $operacao_insert = mysqli_query($conecta, $insert);
        if (!$operacao_insert) {
            break;
            $erro = true;
        }
    }

    if (!$erro) {
        mysqli_commit($conecta); // Confirma a transação se tudo ocorreu bem
        return true;
    } else {
        mysqli_rollback($conecta); // Desfaz a transação em caso de erro
        return false;
    }
}


function adicionar_frete_delivery($conecta, $bairro, $valor, $data_promocao)
{
    $insert = "INSERT INTO `tb_frete_delivery` (`cl_bairro`, `cl_valor`, `cl_promocao_frete_gratis_delivery`)
     VALUES ('$bairro', '$valor', '$data_promocao')";
    $operacao_insert = mysqli_query($conecta, $insert);
    if ($operacao_insert) {
        return true;
    } else {
        return false;
    }
}


//remover qualuer linha de tabela do bd
function remover_linha($conecta, $tabela, $coluna_filtro, $valor_filtro)
{
    $select = "DELETE FROM $tabela WHERE $coluna_filtro ='$valor_filtro' ";
    $operacao_delete = mysqli_query($conecta, $select);
    if ($operacao_delete) {
        return true;
    } else {
        return false;
    }
}

function verifica_demanda_pedidos($conecta, $data_lancamento)
{
    $select = "SELECT * FROM tb_nf_saida where cl_status_venda ='2' and cl_status_pedido_delivery='2' 
    and cl_data_movimento ='$data_lancamento'  ";
    $consulta = mysqli_query($conecta, $select);
    $qtd_consulta = mysqli_num_rows($consulta);
    return $qtd_consulta;
}


function calcularHoraEntrega($dataHoraPedido, $minutosParaPedido)
{
    if ($minutosParaPedido != "") {
        // Converte a data e hora do pedido para um objeto DateTime
        $dataHoraPedidoObj = new DateTime($dataHoraPedido);

        // Adiciona os minutos do pedido ao horário do pedido
        $dataHoraEntregaObj = clone $dataHoraPedidoObj;
        $dataHoraEntregaObj->add(new DateInterval("PT" . $minutosParaPedido . "M"));

        // Formata o horário de entrega prevista para exibição (apenas hora e minuto)
        $horaEntregaPrevista = $dataHoraEntregaObj->format("H:i");

        return $horaEntregaPrevista;
    }
}



// Consultar todas as linhas de uma tabela dinamicamente
function consulta_linhas_tb($conecta, $tabela)
{
    $select = "SELECT * from $tabela";
    $consulta_tabela = mysqli_query($conecta, $select);

    if (!$consulta_tabela) {
        die("Erro na consulta: " . mysqli_error($conecta));
    }

    $linhas = array();
    while ($linha = mysqli_fetch_assoc($consulta_tabela)) {
        $linhas[] = $linha;
    }

    return $linhas;
}

// Consultar todas as linhas de uma tabela dinamicamente
function consulta_linhas_tb_query($conecta, $query)
{
    $select = $query;
    $consulta_tabela = mysqli_query($conecta, $select);

    if (!$consulta_tabela) {
        die("Erro na consulta: " . mysqli_error($conecta));
    }

    $linhas = array();
    while ($linha = mysqli_fetch_assoc($consulta_tabela)) {
        $linhas[] = $linha;
    }

    return $linhas;
}

// Consultar todas as linhas de uma tabela dinamicamente
function consulta_linhas_tb_filtro($conecta, $tabela, $filtro, $valor_filtro)
{
    $select = "SELECT * from $tabela where $filtro = '$valor_filtro'";
    $consulta_tabela = mysqli_query($conecta, $select);

    if (!$consulta_tabela) {
        die("Erro na consulta: " . mysqli_error($conecta));
    }

    $linhas = array();
    while ($linha = mysqli_fetch_assoc($consulta_tabela)) {
        $linhas[] = $linha;
    }

    return $linhas;
}
// Consultar todas as linhas de uma tabela dinamicamente
function consulta_linhas_tb_2_filtro($conecta, $tabela, $filtro, $valor_filtro, $filtro2, $valor_filtro2)
{
    $select = "SELECT * from $tabela where $filtro = '$valor_filtro' and $filtro2 = '$valor_filtro2' ";
    $consulta_tabela = mysqli_query($conecta, $select);

    if (!$consulta_tabela) {
        die("Erro na consulta: " . mysqli_error($conecta));
    }

    $linhas = array();
    while ($linha = mysqli_fetch_assoc($consulta_tabela)) {
        $linhas[] = $linha;
    }

    return $linhas;
}
function resumo_valor_nf_entrada($conecta, $codigo_nf)
{
    $total_base_icms = 0;
    $total_valor_icms = 0;
    $total_bc_icms_sub = 0;
    $total_valor_icms_sub = 0;
    $total_valor_ipi = 0;
    $total_valor_produtos = 0;

    $select = "SELECT * FROM tb_nf_entrada_item where cl_codigo_nf ='$codigo_nf' ";
    $consulta = mysqli_query($conecta, $select);
    while ($linha = mysqli_fetch_assoc($consulta)) {
        $base_icms = $linha['cl_bc_icms'];
        $valor_icms = $linha['cl_valor_icms'];
        $bc_icms_sub = $linha['cl_bc_icms_sub'];
        $valor_icms_sub = $linha['cl_valor_icms_sub'];
        $valor_ipi = $linha['cl_valor_ipi'];
        $quantidade = $linha['cl_quantidade'];
        $valor_unitario = $linha['cl_valor_unitario'];
        $valor_produto_total = $quantidade * $valor_unitario;

        $total_base_icms += $base_icms;
        $total_valor_icms += $valor_icms;
        $total_bc_icms_sub += $bc_icms_sub;
        $total_valor_icms_sub += $valor_icms_sub;
        $total_valor_ipi += $valor_ipi;
        $total_valor_produtos += $valor_produto_total;
    }

    // Crie um array associativo com os totais
    $totais = array(
        'total_base_icms' => $total_base_icms,
        'total_valor_icms' => $total_valor_icms,
        'total_bc_icms_sub' => $total_bc_icms_sub,
        'total_valor_icms_sub' => $total_valor_icms_sub,
        'total_valor_ipi' => $total_valor_ipi,
        'total_valor_produtos' => $total_valor_produtos
    );

    // Retorne o array de totais
    return $totais;
}


function resumo_valor_nf_saida($conecta, $codigo_nf)
{
    $cst_destaque = verficar_paramentro($conecta, "tb_parametros", "cl_id", "126"); //cst que destacam tributos
    $cst_destaque = array_map('trim', explode(',', $cst_destaque));

    $total_base_icms = 0;
    $total_valor_icms = 0;
    $total_bc_icms_sub = 0;
    $total_valor_icms_sub = 0;
    $total_valor_ipi = 0;
    $total_base_iss =  0;
    $total_valor_iss =  0;
    $total_base_cofins =  0;
    $total_valor_cofins = 0;
    $total_base_pis = 0;
    $total_valor_pis = 0;
    $total_valor_produtos = 0;

    $select = "SELECT * FROM tb_nf_saida_item where cl_codigo_nf ='$codigo_nf' ";
    $consulta = mysqli_query($conecta, $select);
    while ($linha = mysqli_fetch_assoc($consulta)) {
        $base_icms = $linha['cl_base_icms'];
        $valor_icms = $linha['cl_icms'];
        $bc_icms_sub = $linha['cl_base_icms_sbt'];
        $valor_icms_sub = $linha['cl_icms_sbt'];
        $valor_ipi = $linha['cl_ipi'];
        $base_iss = $linha['cl_base_iss'];
        $valor_iss = $linha['cl_iss'];
        $base_cofins = $linha['cl_base_cofins'];
        $valor_cofins = $linha['cl_cofins'];
        $base_pis = $linha['cl_base_pis'];
        $valor_pis = $linha['cl_pis'];
        $cst_icms = $linha['cl_cst'];

        $quantidade = abs($linha['cl_quantidade']);
        $valor_unitario = $linha['cl_valor_unitario'];
        $valor_produto_total = $quantidade * $valor_unitario;

        if (in_array($cst_icms, $cst_destaque)) {  // O valor não está contido no array $variantes_opcao
            $total_base_icms += $base_icms;
            $total_valor_icms += $valor_icms;
            $total_bc_icms_sub += $bc_icms_sub;
            $total_valor_icms_sub += $valor_icms_sub;
            $total_valor_ipi += $valor_ipi;

            $total_base_iss += $base_iss;
            $total_valor_iss += $valor_iss;
            $total_base_cofins += $base_cofins;
            $total_valor_cofins += $valor_cofins;
            $total_base_pis += $base_pis;
            $total_valor_pis += $valor_pis;
        }

        $total_valor_produtos += $valor_produto_total;
    }
    $valor_desconto = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_valor_desconto"));
    $vfrete = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_valor_frete"));
    $vseguro = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_valor_seguro"));
    $outras_despesas = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_valor_outras_despesas"));
    $valor_credito = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_valor_credito"));
    $valor_entrega_delivery = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_valor_entrega_delivery"));

    $valor_bruto =  $total_valor_produtos + $total_valor_icms_sub + $total_valor_ipi + $vfrete + $outras_despesas + $vseguro + $valor_entrega_delivery;
    $valor_total_nota = $total_valor_produtos + $total_valor_icms_sub + $total_valor_ipi + $vfrete + $outras_despesas + $vseguro - $valor_desconto;

    /*servico */
    $valor_aliquota_servico = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_valor_aliquota"));
    $valor_bruto_servico = $total_valor_produtos;
    $valor_iss = round(($valor_bruto_servico * $valor_aliquota_servico) / 100, 2);

    // Crie um array associativo com os totais
    $totais = array(
        'total_base_icms' => $total_base_icms,
        'total_valor_icms' => $total_valor_icms,
        'total_bc_icms_sub' => $total_bc_icms_sub,
        'total_valor_icms_sub' => $total_valor_icms_sub,
        'total_valor_ipi' => $total_valor_ipi,

        'total_base_iss' => $total_base_iss,
        'total_valor_iss' => $total_valor_iss,
        'total_base_cofins' => $total_base_cofins,
        'total_valor_cofins' => $total_valor_cofins,
        'total_base_pis' => $total_base_pis,
        'total_valor_pis' => $total_valor_pis,
        'total_valor_bruto' => $valor_bruto,
        'total_valor_liquido' => $valor_total_nota,
        'total_desconto' => $valor_desconto,

        'total_valor_produtos' => $total_valor_produtos,

        /*servico */
        'valor_iss' => $valor_iss,
        'valor_bruto_servico' => $valor_bruto_servico

    );

    // Retorne o array de totais
    return $totais;
}

function recalcular_valor_nf_entrada($conecta, $codigo_nf)
{ //atualizar valor bruto e liquido da nf

    $select = "SELECT * from tb_nf_entrada where cl_codigo_nf ='$codigo_nf' ";
    $consultar_nf = mysqli_query($conecta, $select);
    $qtd_nf = mysqli_num_rows($consultar_nf);

    if ($qtd_nf > 0) { //verificar se existe uma nf já feita
        $valor_desconto = (consulta_tabela($conecta, "tb_nf_entrada", "cl_codigo_nf", $codigo_nf, "cl_valor_desconto"));
        $vfrete = (consulta_tabela($conecta, "tb_nf_entrada", "cl_codigo_nf", $codigo_nf, "cl_valor_frete"));
        $vfrete_conhecimento = (consulta_tabela($conecta, "tb_nf_entrada", "cl_codigo_nf", $codigo_nf, "cl_valor_frete_conhecimento"));
        $vseguro = (consulta_tabela($conecta, "tb_nf_entrada", "cl_codigo_nf", $codigo_nf, "cl_valor_seguro"));
        $outras_despesas = (consulta_tabela($conecta, "tb_nf_entrada", "cl_codigo_nf", $codigo_nf, "cl_valor_outras_despesas"));

        $totais = resumo_valor_nf_entrada($conecta, $codigo_nf); //informações sobre os itens na nota
        $vlr_total_produtos = $totais['total_valor_produtos'];
        $icms_sub_nota = $totais['total_valor_icms_sub'];
        $ipi_nota = $totais['total_valor_ipi'];

        $valor_total_nota = $vlr_total_produtos + $icms_sub_nota + $ipi_nota + $vfrete + $outras_despesas + $vseguro - $valor_desconto;

        $update = "UPDATE `tb_nf_entrada` SET `cl_valor_total_produtos` = '$vlr_total_produtos',
        `cl_valor_total_nota` = '$valor_total_nota' WHERE `cl_codigo_nf` = '$codigo_nf' ";
        $operacao_update_nf = mysqli_query($conecta, $update);
        if ($operacao_update_nf) {
            return true;
        } else {
            return false;
        }
        //adicionar ao ajuste de estoque
    } else {
        return true;
    }
}

function recalcular_valor_nf_saida($conecta, $codigo_nf)
{ //atualizar valor bruto e liquido da nf

    $select = "SELECT * from tb_nf_saida where cl_codigo_nf ='$codigo_nf' ";
    $consultar_nf = mysqli_query($conecta, $select);
    $qtd_nf = mysqli_num_rows($consultar_nf);

    if ($qtd_nf > 0) { //verificar se existe uma nf já feita
        $valor_desconto = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_valor_desconto"));
        $vfrete = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_valor_frete"));
        $vseguro = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_valor_seguro"));
        $outras_despesas = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_valor_outras_despesas"));
        $valor_credito = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_valor_credito"));
        $valor_entrega_delivery = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_valor_entrega_delivery"));
        $operacao = (consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_operacao"));

        $totais = resumo_valor_nf_saida($conecta, $codigo_nf); //informações sobre os itens na nota
        $vlr_total_produtos = $totais['total_valor_produtos'];
        $icms_sub_nota = $totais['total_valor_icms_sub'];
        $ipi_nota = $totais['total_valor_ipi'];

        $valor_bruto =  $vlr_total_produtos + $icms_sub_nota + $ipi_nota + $vfrete + $outras_despesas + $vseguro + $valor_entrega_delivery;
        $valor_total_nota = $vlr_total_produtos + $icms_sub_nota + $ipi_nota + $vfrete + $outras_despesas + $vseguro + $valor_entrega_delivery - $valor_desconto - $valor_credito;
        // if ($operacao == "DEVCOMPRA") {
        //     $valor_total_nota = 0;
        //  }
        $update = "UPDATE `tb_nf_saida` SET `cl_valor_bruto` = '$valor_bruto',
        `cl_valor_liquido` = '$valor_total_nota' WHERE `cl_codigo_nf` = '$codigo_nf' ";
        $operacao_update_nf = mysqli_query($conecta, $update);
        if ($operacao_update_nf) {
            return true;
        } else {
            return false;
        }
        //adicionar ao ajuste de estoque
    } else {
        return true;
    }
}


function verifica_dupliidade_de_dados_outros($conecta, $tabela, $filtro_1, $valor_1, $filtro_2, $valor_2)
{ //verificar se já existe uma informação  para outro
    $select = "SELECT * from $tabela where $filtro_1 ='$valor_1' and $filtro_2 !='$valor_2' ";
    $consultar = mysqli_query($conecta, $select);
    $qtd = mysqli_num_rows($consultar);
    return $qtd;
}

function cancelar_ajuste_estoque($conecta, $codigo_nf, $operacao) //1 cancelar 3-remover
{
    $erro = false;
    if ($operacao == "3" or $operacao == "") { //remover o ajuste
        $select = "SELECT * from tb_ajuste_estoque where cl_codigo_nf = '$codigo_nf' ";
        $consultar = mysqli_query($conecta, $select);
        while ($linha = mysqli_fetch_assoc($consultar)) {
            $id = $linha['cl_id'];
            $produto_id = $linha['cl_produto_id'];
            $quantidade = $linha['cl_quantidade'];
            $tipo = $linha['cl_tipo'];
            $status = $linha['cl_status'];

            $estoque = consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_estoque"); //estoque atual do produto

            if ($tipo == "ENTRADA") {
                $nova_estoque = $estoque - $quantidade;
                if ($status == "ok") { //se o ajuste estiver ok
                    if (!update_registro($conecta, 'tb_produtos', "cl_id", $produto_id, "", "", "cl_estoque", $nova_estoque)) { {
                            $erro = true;
                            break;
                        }
                    }
                }
            } else {
                $nova_estoque = $estoque + $quantidade;
                if ($status == "ok") { //se o ajuste estiver ok
                    if (!update_registro($conecta, 'tb_produtos', "cl_id", $produto_id, "", "", "cl_estoque", $nova_estoque)) { {
                            $erro = true;
                            break;
                        }
                    }
                }
            }
        }

        if (!remover_linha($conecta, "tb_ajuste_estoque", "cl_codigo_nf", $codigo_nf)) {
            $erro = true;
        }

        if ($erro == false) {
            return true;
        } else {
            return false;
        }
    } else { //cancelar o ajuste
        $select = "SELECT * from tb_ajuste_estoque where cl_codigo_nf = '$codigo_nf' ";
        $consultar = mysqli_query($conecta, $select);
        while ($linha = mysqli_fetch_assoc($consultar)) {
            $id = $linha['cl_id'];
            $produto_id = $linha['cl_produto_id'];
            $quantidade = $linha['cl_quantidade'];
            $tipo = $linha['cl_tipo'];
            $status = $linha['cl_status'];

            $estoque = consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_estoque"); //estoque atual do produto

            if ($tipo == "ENTRADA") {
                $nova_estoque = $estoque - $quantidade;
                if ($status == "ok") { //se o ajuste estiver ok
                    if (!update_registro($conecta, 'tb_produtos', "cl_id", $produto_id, "", "", "cl_estoque", $nova_estoque)) { {
                            $erro = true;
                            break;
                        }
                    }
                }
            } else {
                $nova_estoque = $estoque + $quantidade;
                if ($status == "ok") { //se o ajuste estiver ok
                    if (!update_registro($conecta, 'tb_produtos', "cl_id", $produto_id, "", "", "cl_estoque", $nova_estoque)) { {
                            $erro = true;
                            break;
                        }
                    }
                }
            }
        }

        if (!update_registro($conecta, 'tb_ajuste_estoque', "cl_codigo_nf", $codigo_nf, "", "", "cl_status", "cancelado")) { {
                $erro = true;
            }
        }

        if ($erro == false) {
            return true;
        } else {
            return false;
        }
    }
}
function excluirArquivo($caminhoArquivo)
{
    if (file_exists($caminhoArquivo)) {
        if (unlink($caminhoArquivo)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function verifica_data_caixa_aberto($conecta)
{
    $select = "SELECT MAX(cl_data_abertura) AS datamaisrecente
    FROM tb_caixa WHERE cl_status IN ('aberto', 'reaberto') ";
    $consulta = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consulta);
    $data_caixa = $linha['datamaisrecente'];
    return $data_caixa;
}

function gerar_nf($conecta, $nf, $cfop, $serie_nf)
{
    $erro = false;
    $nf_atual = consulta_tabela($conecta, "tb_serie", "cl_descricao", $serie_nf, "cl_valor"); //verificar a numeração da venda atual
    $informacao_adicional = (consulta_tabela($conecta, "tb_cfop", "cl_codigo_cfop", $cfop, "cl_informacao_adicional")); //informações adicionais atrelado no cfop

    $nf_prox = $nf_atual + 1; //proxima numeracao da nf
    global  $data_lancamento;
    $qtdNfe = count($nf); //verificar o numero de nf selecionadas
    if ($qtdNfe == 1) {
        foreach ($nf as $chave => $nf_id) {
            $codigo_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $nf_id, "cl_codigo_nf");
            $valor_frete = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $nf_id, "cl_valor_frete");
            $tipo_frete = $valor_frete > 0 ? 0 : 9;

            $update = "UPDATE `tb_nf_saida` SET `cl_data_movimento` = '$data_lancamento',`cl_numero_nf` = '$nf_prox',
            `cl_finalidade` = '1', `cl_tipo_frete_id` = '$tipo_frete', `cl_serie_nf` = '$serie_nf', 
            `cl_cfop` = '$cfop',`cl_visualizar_duplicata` = '1',`cl_tipo_documento_nf` = '1',`cl_observacao` = CONCAT(`cl_observacao`, ' ', '$informacao_adicional') 
        
            WHERE cl_id = '$nf_id' ";
            $operacao_update_nf = mysqli_query($conecta, $update);
            if ($operacao_update_nf and gerar_nf_item($codigo_nf, $serie_nf)) {
                $erro = false;
            } else {
                $erro = true;
            }
        }
    }
    if (!update_registro($conecta, 'tb_serie', 'cl_descricao', $serie_nf, "", "", "cl_valor", $nf_prox)) { //atualizar valor da serie 
        $erro = true;
    }


    if ($erro == false) {
        return true;
    } else {
        return false;
    }
}

function gerar_nf_item($codigo_nf, $serie_nf)
{
    global $conecta;
    $crt_empresa = verficar_paramentro($conecta, "tb_parametros", "cl_id", "57"); //verificar o crt
    $estado_empresa = consulta_tabela($conecta, "tb_empresa", "cl_id", "1", "cl_estado"); //estado da empresa
    $estado_empresa_id = consulta_tabela($conecta, "tb_estados", "cl_uf", $estado_empresa, "cl_id"); //verificar o estado do emitente

    $parceiro_id = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_parceiro_id");
    $numero_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_numero_nf");

    $parceiro_estado_id = consulta_tabela($conecta, "tb_parceiros", "cl_id", $parceiro_id, "cl_estado_id"); //estado do cliente
    $aliq_empresa = consulta_tabela($conecta, "tb_estados", "cl_uf", $estado_empresa, "cl_aliq"); //estado da empresa
    $cfop_informado_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_cfop"); //verificar se foi informado o cfop na nf


    $erro = false;
    $update_cfop = false;

    $select = "SELECT * FROM tb_nf_saida_item where cl_codigo_nf ='$codigo_nf' ";
    $consulta = mysqli_query($conecta, $select);
    while ($linha = mysqli_fetch_assoc($consulta)) {

        $item_id = $linha['cl_id'];
        $produto_id = $linha['cl_item_id'];
        $quantidade = $linha['cl_quantidade'];
        $valor_unitario = $linha['cl_valor_unitario'];
        $cfop_atual = $linha['cl_cfop'];
        $valor_total_item = $quantidade * $valor_unitario;
        $cst_icms_prd = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_cst_icms"));
        $cst_cofins_prd = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_cst_cofins_s"));
        $cst_pis_prd = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_cst_pis_s"));
        $ncm_prd = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_ncm"));
        $cest_prd = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_cest"));
        $gtin_prd = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_gtin"));
        $grupo_id = consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_grupo_id");

        $cfop_interno = (consulta_tabela($conecta, "tb_subgrupo_estoque", "cl_id", $grupo_id, "cl_cfop_interno"));
        $cfop_externo = (consulta_tabela($conecta, "tb_subgrupo_estoque", "cl_id", $grupo_id, "cl_cfop_externo"));

        if ($estado_empresa_id == $parceiro_estado_id) { //cliente do mesmo estado
            $aliq = $aliq_empresa;
            $cfop = $cfop_interno;
        } else { //fora do estado
            $aliq = 12;
            $cfop = $cfop_externo;
        }

        if ($cfop_atual != "") {
            $cfop = $cfop_atual;
        }

        if ($crt_empresa == '1' or $crt_empresa == 0) { //simples
            // ICMS
            $cst_icms = ($cst_icms_prd == "") ? 102 : $cst_icms_prd;

            // COFINS
            $cst_cofins = ($cst_cofins_prd == "") ? "" : $cst_cofins_prd;

            // PIS
            $cst_pis = ($cst_pis_prd == "") ? "" : $cst_pis_prd;


            $cst_icms = $cst_icms;
            $base_icms = 0;
            $valor_icms = 0;

            $base_cofins = 0;
            $valor_cofins = 0;
            $cst_cofins = $cst_cofins;

            $base_pis = 0;
            $valor_pis = 0;
            $cst_pis = $cst_pis;
        } else { //regime tributavel
            $valor_icms = (($valor_total_item * $aliq) / 100);
            $valor_cofins = (($valor_total_item * 3) / 100);
            $valor_pis = (($valor_total_item * 0.65) / 100);

            // ICMS
            $cst_icms = ($cst_icms_prd == "") ? "" : $cst_icms_prd;

            // COFINS
            $cst_cofins = ($cst_cofins_prd == "") ? "" : $cst_cofins_prd;

            // PIS
            $cst_pis = ($cst_pis_prd == "") ? "" : $cst_pis_prd;

            $base_icms = $valor_total_item;
            $valor_icms = $valor_icms;
            $cst_icms = $cst_icms;

            $base_cofins = $valor_total_item;
            $valor_cofins = $valor_cofins;
            $cst_cofins = $cst_cofins;

            $base_pis = $valor_total_item;
            $valor_pis = $valor_pis;
            $cst_pis = $cst_pis;
        }

        $update = "UPDATE `tb_nf_saida_item` SET `cl_numero_nf` = '$numero_nf', `cl_serie_nf` = '$serie_nf', `cl_cst` = '$cst_icms', `cl_ncm` = '$ncm_prd', 
        `cl_cest` = '$cest_prd', `cl_aliq_icms` = '$aliq',`cl_base_icms` = '$base_icms', `cl_icms` = '$valor_icms', `cl_base_icms_sbt` = '0', `cl_icms_sbt` = '0', `cl_base_pis` = '$base_pis',
         `cl_pis` = '$valor_pis', `cl_cst_pis` = '$cst_pis_prd', `cl_base_cofins` = '$base_cofins', `cl_cofins` = '$valor_cofins',
          `cl_cst_cofins` = '$cst_cofins', `cl_cfop` = '$cfop',`cl_gtin` = '$gtin_prd'  WHERE cl_id = $item_id ";
        $operacao_update = mysqli_query($conecta, $update);
        if ($operacao_update) {
            if ((empty($cfop_informado_nf) or $cfop_informado_nf == "0") and $update_cfop == false) { //cfop não infomado, será atualizado com o primeiro cfop de um produto
                update_registro($conecta, 'tb_nf_saida', 'cl_codigo_nf', $codigo_nf, '', '', 'cl_cfop', $cfop);
                $update_cfop = true;
            }
        } else {
            $erro = true;
            break;
        }
    }
    if ($erro == false) {
        return true;
    } else {
        return false;
    }
}


function gerar_nfs_item($codigo_nf)
{
    global $conecta;

    $crt_empresa = verficar_paramentro($conecta, "tb_parametros", "cl_id", "57"); //verificar o crt
    $estado_empresa = consulta_tabela($conecta, "tb_empresa", "cl_id", "1", "cl_estado"); //estado da empresa
    $estado_empresa_id = consulta_tabela($conecta, "tb_estados", "cl_uf", $estado_empresa, "cl_id"); //verificar o estado do emitente
    $parceiro_id = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_parceiro_id");
    $numero_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_numero_nf");
    $parceiro_estado_id = consulta_tabela($conecta, "tb_parceiros", "cl_id", $parceiro_id, "cl_estado_id"); //estado do cliente
    $aliq_empresa = consulta_tabela($conecta, "tb_estados", "cl_uf", $estado_empresa, "cl_aliq"); //estado da empresa
    $erro = false;
    $update_cfop = false;

    $select = "SELECT * FROM tb_nf_saida_item where cl_codigo_nf ='$codigo_nf' and  COALESCE(cl_item_id, '') <> ''";
    $consulta = mysqli_query($conecta, $select);
    while ($linha = mysqli_fetch_assoc($consulta)) {
        $item_id = $linha['cl_id'];
        $produto_id = $linha['cl_item_id'];

        $quantidade = $linha['cl_quantidade'];
        $valor_unitario = $linha['cl_valor_unitario'];
        $cfop_atual = $linha['cl_cfop'];
        $valor_total_item = $quantidade * $valor_unitario;

        $cst_icms_prd = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_cst_icms"));
        $cst_cofins_prd = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_cst_cofins_s"));
        $cst_pis_prd = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_cst_pis_s"));
        $ncm_prd = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_ncm"));
        $cest_prd = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_cest"));
        $gtin_prd = (consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_gtin"));
        $grupo_id = consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_grupo_id");

        $cfop_interno = (consulta_tabela($conecta, "tb_subgrupo_estoque", "cl_id", $grupo_id, "cl_cfop_interno"));
        $cfop_externo = (consulta_tabela($conecta, "tb_subgrupo_estoque", "cl_id", $grupo_id, "cl_cfop_externo"));

        if ($estado_empresa_id == $parceiro_estado_id) { //cliente do mesmo estado
            $aliq = $aliq_empresa;
            $cfop = $cfop_interno;
        } else { //fora do estado
            $aliq = 12;
            $cfop = $cfop_externo;
        }

        if ($cfop_atual != "") {
            $cfop = $cfop_atual;
        }

        if ($crt_empresa == '1' or $crt_empresa == 0) { //simples
            // ICMS
            $cst_icms = ($cst_icms_prd == "") ? 102 : $cst_icms_prd;

            // COFINS
            $cst_cofins = ($cst_cofins_prd == "") ? "" : $cst_cofins_prd;

            // PIS
            $cst_pis = ($cst_pis_prd == "") ? "" : $cst_pis_prd;


            $cst_icms = $cst_icms;
            $base_icms = 0;
            $valor_icms = 0;

            $base_cofins = 0;
            $valor_cofins = 0;
            $cst_cofins = $cst_cofins;

            $base_pis = 0;
            $valor_pis = 0;
            $cst_pis = $cst_pis;
        } else { //regime tributavel
            $valor_icms = (($valor_total_item * $aliq) / 100);
            $valor_cofins = (($valor_total_item * 3) / 100);
            $valor_pis = (($valor_total_item * 0.65) / 100);

            // ICMS
            $cst_icms = ($cst_icms_prd == "") ? "" : $cst_icms_prd;

            // COFINS
            $cst_cofins = ($cst_cofins_prd == "") ? "" : $cst_cofins_prd;

            // PIS
            $cst_pis = ($cst_pis_prd == "") ? "" : $cst_pis_prd;

            $base_icms = $valor_total_item;
            $valor_icms = $valor_icms;
            $cst_icms = $cst_icms;

            $base_cofins = $valor_total_item;
            $valor_cofins = $valor_cofins;
            $cst_cofins = $cst_cofins;

            $base_pis = $valor_total_item;
            $valor_pis = $valor_pis;
            $cst_pis = $cst_pis;
        }

        $update = "UPDATE `tb_nf_saida_item` SET `cl_numero_nf` = '$numero_nf', `cl_cst` = '$cst_icms', `cl_ncm` = '$ncm_prd', 
        `cl_cest` = '$cest_prd', `cl_aliq_icms` = '$aliq',`cl_base_icms` = '$base_icms', `cl_icms` = '$valor_icms', `cl_base_icms_sbt` = '0',
         `cl_icms_sbt` = '0', `cl_base_pis` = '$base_pis',`cl_pis` = '$valor_pis', `cl_cst_pis` = '$cst_pis_prd', 
         `cl_base_cofins` = '$base_cofins', `cl_cofins` = '$valor_cofins',
          `cl_cst_cofins` = '$cst_cofins', `cl_cfop` = '$cfop',`cl_gtin` = '$gtin_prd'  WHERE cl_id = $item_id ";
        $operacao_update = mysqli_query($conecta, $update);
        if (!$operacao_update) {
            $erro = true;
            break;
        }
        if ($update_cfop == false) {
            update_registro($conecta, 'tb_nf_saida', 'cl_codigo_nf', $codigo_nf, '', '', 'cl_cfop', $cfop); //atualizar o cfop no cabeçalho da nota
            $update_cfop = true;
        }
    }
    if ($erro == false) {
        return true;
    } else {
        return false;
    }
}

function esconderParteCPF($numero)
{
    $numero_original = $numero;
    // Remove caracteres não numéricos
    $numero = preg_replace('/\D/', '', $numero);

    // Verifica se o CPF fornecido é válido (11 dígitos)
    if (strlen($numero) !== 11) {
        $numeroOculto = $numero_original;
    } else {
        // Oculta parte dos dígitos (exibindo apenas os 4 últimos)
        $numeroOculto = substr_replace($numero, str_repeat('•', 5), 3, 5);
    }

    return $numeroOculto;
}


function calcular_custo_item_nf($id)
{
    global $conecta;


    $select = "SELECT * FROM tb_nf_entrada_item where cl_id = $id ";
    $consulta = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consulta);

    $produto_id = $linha['cl_produto_id'];
    $codigo_nf = $linha['cl_codigo_nf'];
    $quantidade = $linha['cl_quantidade'];
    $valor_unitario = $linha['cl_valor_unitario'];
    $valor_total_item = $quantidade * $valor_unitario;

    $select = "SELECT * FROM tb_nf_entrada where cl_codigo_nf ='$codigo_nf' ";
    $consulta_qtd = mysqli_query($conecta, $select);
    $qtd_consulta = mysqli_num_rows($consulta_qtd);

    $magem_lucro_prd = consulta_tabela($conecta, 'tb_produtos', "cl_id", $produto_id, "cl_margem_lucro");

    $saguro_nf = consulta_tabela($conecta, 'tb_nf_entrada', "cl_codigo_nf", $codigo_nf, "cl_valor_seguro");
    $frete_conhecimento = consulta_tabela($conecta, 'tb_nf_entrada', "cl_codigo_nf", $codigo_nf, "cl_valor_frete_conhecimento");
    $valor_frete = consulta_tabela($conecta, 'tb_nf_entrada', "cl_codigo_nf", $codigo_nf, "cl_valor_frete");
    $valor_outras_despesas = consulta_tabela($conecta, 'tb_nf_entrada', "cl_codigo_nf", $codigo_nf, "cl_valor_outras_despesas");
    $valor_desconto = consulta_tabela($conecta, 'tb_nf_entrada', "cl_codigo_nf", $codigo_nf, "cl_valor_desconto");

    $valor_rat_seguro = ($qtd_consulta != 0) ? $saguro_nf / $qtd_consulta : 0;
    $valor_rat_frete_conhecimento = ($qtd_consulta != 0) ? $frete_conhecimento / $qtd_consulta : 0;
    $valor_rat_frete = ($qtd_consulta != 0) ? $valor_frete / $qtd_consulta : 0;
    $valor_rat_outras_despesas = ($qtd_consulta != 0) ? $valor_outras_despesas / $qtd_consulta : 0;
    $valor_rat_desconto = ($qtd_consulta != 0) ? $valor_desconto / $qtd_consulta : 0;

    $valor_custo = number_format(($valor_unitario + $valor_rat_seguro + $valor_rat_frete_conhecimento + $valor_rat_frete + $valor_rat_outras_despesas - $valor_rat_desconto), 2, '.', '');
    $preco_venda_sugerido =  number_format(($valor_custo / (1 - ($magem_lucro_prd / 100))), 2, '.', '');


    return ['valor_custo' => $valor_custo, 'prc_vnd_sugerido' => $preco_venda_sugerido]; // Retorna o array 


}

function verifica_data_funcionamento($conecta, $data, $hora_atual)
{
    /*dia da semana */
    $timestamp = time();
    $dia_semana = date('l', $timestamp);

    $select = "SELECT * FROM tb_data_funcionamento where cl_dia_semana = '$dia_semana'";
    $data_funcionamento = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($data_funcionamento);
    $dia_semana_bd = $linha['cl_dia_semana'];
    $hora_abertura = $linha['cl_hora_abertura'];
    $hora_fechamento = $linha['cl_hora_fechamento'];
    $status_funcionamento = $linha['cl_status_funcionamento'];

    $dateTimeAbertura = new DateTime($hora_abertura);
    $dateTimeFechamento = new DateTime($hora_fechamento);
    // Formata a data para obter apenas a hora no formato de 24 horas
    $hora_abertura = $dateTimeAbertura->format('H:i:s');
    $hora_fechamento = $dateTimeFechamento->format('H:i:s');

    // Verifica o nome do dia da semana
    if ($dia_semana == $dia_semana_bd and $status_funcionamento == "SIM" and  $hora_abertura <= $hora_atual and $hora_fechamento >= $hora_atual) {
        return true; //está aberto
    } else {
        return false; //está fechado
    }
}


function requisitar_pecas_os($os_id)
{
    global $conecta;
    global $data;
    $erro  = false;
    $codigo_nf = consulta_tabela($conecta, "tb_os", "cl_id", $os_id, 'cl_codigo_nf');
    $parceiro_id = consulta_tabela($conecta, "tb_os", "cl_id", $os_id, 'cl_parceiro_id');
    $atendente_id = consulta_tabela($conecta, "tb_os", "cl_id", $os_id, 'cl_atendente_id');
    $forma_pagamento_id = consulta_tabela($conecta, "tb_os", "cl_id", $os_id, 'cl_forma_pagamento_id');
    $numero_nf = consulta_tabela($conecta, "tb_os", "cl_id", $os_id, 'cl_numero_nf');
    $serie_nf = consulta_tabela($conecta, "tb_os", "cl_id", $os_id, 'cl_serie_nf');


    $select = "SELECT * FROM tb_infraestrutura_os where cl_codigo_nf ='$codigo_nf' and ( cl_tipo ='MATERIAL'  or cl_tipo ='MATERIALMOBILIZADO' ) and cl_peca_requisitada = 0 ";
    $consulta = mysqli_query($conecta, $select);
    $qtd_consulta = mysqli_num_rows($consulta);
    if ($consulta and $qtd_consulta > 0) {
        while ($linha = mysqli_fetch_assoc($consulta)) {
            $item_nf_id = $linha['cl_id'];
            $tipo = $linha['cl_tipo'];
            $produto_id = $linha['cl_produto_id'];
            $quantidade_orcada = $linha['cl_quantidade_orcada'];
            $peca_requisitada = $linha['cl_peca_requisitada'];
            $valor_unitario = $linha['cl_valor_unitario'];
            $estoque_atual = consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_estoque");
            $novo_estoque = $estoque_atual - $quantidade_orcada;
            // $estoque_atual = 0;
            if ($estoque_atual > 0  and $novo_estoque >= 0) {
                $update = "UPDATE tb_infraestrutura_os set cl_quantidade_requisitada = '$quantidade_orcada', cl_peca_requisitada =1 where cl_id = '$item_nf_id' "; //atualizar a quantidade requisitada
                $operacao_update = mysqli_query($conecta, $update);
                $update_estoque = update_registro($conecta, 'tb_produtos', "cl_id", $produto_id, '', '', 'cl_estoque', $novo_estoque); //atualizar o estoque

                $ajuste_estoque = ajuste_estoque(
                    $conecta,
                    $data,
                    "$serie_nf-$numero_nf",
                    "SAIDA",
                    $produto_id,
                    $quantidade_orcada,
                    "1",
                    $parceiro_id,
                    $atendente_id,
                    $forma_pagamento_id,
                    $valor_unitario,
                    "0",
                    '0',
                    '',
                    $codigo_nf,
                    $item_nf_id,
                    ""
                );
                if (($operacao_update or $update_estoque or $ajuste_estoque) == false) {
                    $erro = true;
                    break;
                }
            }
        }
    }

    if ($erro) {
        return false;
    } else {
        return true;
    }
}


function cancelar_requisicao_pecas_os($os_id)
{
    global $conecta;
    global $data;
    $erro  = false;
    $codigo_nf = consulta_tabela($conecta, "tb_os", "cl_id", $os_id, 'cl_codigo_nf');
    $parceiro_id = consulta_tabela($conecta, "tb_os", "cl_id", $os_id, 'cl_parceiro_id');
    $atendente_id = consulta_tabela($conecta, "tb_os", "cl_id", $os_id, 'cl_atendente_id');
    $forma_pagamento_id = consulta_tabela($conecta, "tb_os", "cl_id", $os_id, 'cl_forma_pagamento_id');
    $numero_nf = consulta_tabela($conecta, "tb_os", "cl_id", $os_id, 'cl_numero_nf');
    $serie_nf = consulta_tabela($conecta, "tb_os", "cl_id", $os_id, 'cl_serie_nf');


    $select = "SELECT * FROM tb_infraestrutura_os where cl_codigo_nf ='$codigo_nf' and ( cl_tipo ='MATERIAL'  or cl_tipo ='MATERIALMOBILIZADO' ) and cl_peca_requisitada = 1 ";
    $consulta = mysqli_query($conecta, $select);
    $qtd_consulta = mysqli_num_rows($consulta);
    if ($consulta and $qtd_consulta > 0) {
        while ($linha = mysqli_fetch_assoc($consulta)) {
            $item_nf_id = $linha['cl_id'];
            $tipo = $linha['cl_tipo'];
            $produto_id = $linha['cl_produto_id'];
            $quantidade_orcada = $linha['cl_quantidade_orcada'];
            $peca_requisitada = $linha['cl_peca_requisitada'];
            $valor_unitario = $linha['cl_valor_unitario'];
            $estoque_atual = consulta_tabela($conecta, "tb_produtos", "cl_id", $produto_id, "cl_estoque");
            $novo_estoque = $estoque_atual + $quantidade_orcada;

            $update = "UPDATE tb_infraestrutura_os set cl_quantidade_requisitada = '0', cl_peca_requisitada =0 "; //atualizar a quantidade requisitada
            $operacao_update = mysqli_query($conecta, $update);
            $update_estoque = update_registro($conecta, 'tb_produtos', "cl_id", $produto_id, '', '', 'cl_estoque', $novo_estoque); //atualizar o estoque

            $select = "DELETE FROM tb_ajuste_estoque WHERE cl_codigo_nf ='$codigo_nf' and cl_id_nf ='$item_nf_id' ";
            $operacao_delete = mysqli_query($conecta, $select); //deletar o ajuse de estoque

            if (($peca_requisitada or $novo_estoque or $operacao_delete) == false) {
                $erro = true;
                break;
            }
        }
    }

    if ($erro) {
        return false;
    } else {
        return true;
    }
}

function adicionar_credito_parceiro($data, $paceiro_id, $valor, $justificativa, $tipo)
{
    global $conecta;
    $insert = "INSERT INTO `tb_historico_credito` 
    ( `cl_data`, `cl_parceiro_id`, `cl_valor`, `cl_justificativa`,`cl_tipo`)
    VALUES ('$data','$paceiro_id', '$valor', '$justificativa','$tipo') ";
    $operacao_insert = mysqli_query($conecta, $insert);
    if ($operacao_insert) {
        return true;
    } else {
        return false;
    }
}


function buscar_cep($cep)
{

    // Remova caracteres não numéricos do CEP
    $cep_replace = preg_replace("/[^0-9]/", "", $cep);

    // Construa a URL com o CEP
    $tamanhoDaString = strlen($cep_replace);

    if ($tamanhoDaString == 8) {
        $url = "https://viacep.com.br/ws/{$cep_replace}/json/";

        // Faça a requisição usando file_get_contents
        $response = file_get_contents($url);


        if ($response) {
            // Decodifique a resposta JSON
            $data = json_decode($response, true);

            // Verifique se a requisição foi bem-sucedida
            if ($data !== null && !isset($data['erro'])) {
                // Os dados estão agora no array associativo $data
                // Faça o que for necessário com os dados aqui

                $retornar["dados"] = array("sucesso" => true, "valores" => $data);
            } else {
                // Trate caso ocorra algum erro na requisição ou se o CEP for inválido
                $retornar["dados"] = array("sucesso" => false, "title" => "CEP inválido");
            }
        } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "CEP inválido");
        }
    } else {
        $retornar["dados"] = array("sucesso" => false, "title" => "CEP inválido");
    }

    return $retornar;
}

function formatarNumeroTelefone($numero)
{
    // Remove caracteres indesejados
    $numero = str_replace(array('(', ')', ' '), '', $numero);

    // Verifica se o número tem 11 dígitos (incluindo o DDD)
    if (strlen($numero) == 11) {
        // Formata o número com o código de área separado
        $formatado = '(' . substr($numero, 0, 2) . ') ' . substr($numero, 2, 5) . '-' . substr($numero, 7);
    } else {
        // Caso contrário, assume que não tem código de área e apenas formata o número
        $formatado = substr($numero, 0, 5) . '-' . substr($numero, 5);
    }

    return $formatado;
}


function rastrearObjetoKangu($rastreio, $accesstoken)
{
    global $conecta;
    global $data;
    // URL da API
    $url = "https://portal.kangu.com.br/tms/transporte/rastrear/$rastreio";

    // Cabeçalhos da requisição
    $headers = array(
        "accept: application/json",
        "token: $accesstoken",
        "Content-Type: application/json"
    );

    // Inicializa o cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode == 200) {
        $retornar["data"] = array("status" => true, "response" => json_decode($response, true));
    } else { //code 400
        $decoded_response = json_decode($response, true);
        $errorCode = $decoded_response['error']['codigo'];
        $errorMessage = $decoded_response['error']['mensagem'];

        switch ($errorCode) {
            case 500:
                $errorMessage = "Código de rastreio não identificado";
                break;
            case 510:
                $errorMessage = "Código de rastreio pendente";
                break;
            default:
                $errorMessage = "Erro desconhecido: " . $errorMessage;
                break;
        }

        $mensagem = utf8_decode("Ecommerce - código de rastreio $rastreio - " . str_replace("'", "", $errorMessage));
        registrar_log($conecta, 'ecommerce', $data, $mensagem); // Registrar log do erro


        $retornar["data"] = array("status" => false, "message" => str_replace("'", "", $errorMessage));
    }

    if (curl_errno($ch)) {

        $mensagem = utf8_decode("Ecommerce - código de rastreio $rastreio - " . str_replace("'", "", curl_error($ch)));
        registrar_log($conecta, 'ecommerce', $data, $mensagem); // Registrar log do erro
        $retornar["data"] = array("status" => false, "message" => str_replace("'", "", curl_error($ch)));
    }

    // Fecha o cURL
    curl_close($ch);
    return $retornar;
}

function ajuste_preco($conecta, $dados)
{
    global $data_lancamento;
    global $data;
    foreach ($dados as $item) {
        $documento = $item['documento'];
        $produto_id = $item['produto_id'];
        $codigo_nf = $item['codigo_nf'];
        $tipo = $item['tipo'];
        $unidade = $item['unidade'];
        $valor_antigo = $item['valor_antigo'];
        $valor = $item['valor'];
        $usuario_id = $item['usuario_id'];
        $data_promocao = isset($item['data_promocao']) ? $item['data_promocao'] : '';
    }

    if ($tipo == "venda") {
        update_registro($conecta, 'tb_produtos', 'cl_id', $produto_id, '', '', 'cl_preco_venda', $valor); //atualizar o preço de venda
        update_registro($conecta, 'tb_produtos', 'cl_id', $produto_id, '', '', 'cl_data_ajst_preco_venda', $data_lancamento); //adicionar ao produto a data do ultimo ajuste 
    } elseif ($tipo == "custo") {
        update_registro($conecta, 'tb_produtos', 'cl_id', $produto_id, '', '', 'cl_preco_custo', $valor); //atualizar o preço de custo
    } elseif ($tipo == "promocao") {
        update_registro($conecta, 'tb_produtos', 'cl_id', $produto_id, '', '', 'cl_preco_promocao', $valor); //atualizar o preço de custo
        update_registro($conecta, 'tb_produtos', 'cl_id', $produto_id, '', '', 'cl_data_valida_promocao', $data_promocao); //a data promocional do item
    }

    $query = "INSERT INTO `tb_ajuste_preco` (`cl_data`,`cl_documento`,`cl_codigo_nf`, `cl_produto_id`, `cl_tipo`,
     `cl_unidade`, `cl_valor`, `cl_valor_antigo`, `cl_usuario_id`) VALUES
    ('$data','$documento', '$codigo_nf', '$produto_id', '$tipo', '$unidade', '$valor', '$valor_antigo', '$usuario_id')";
    $insert = mysqli_query($conecta, $query);
    if ($insert) {
        return true;
    } else {
        return mysqli_error($conecta);
    }
}


function ajuste_estoque1($conecta, $dados)
{
    global $data_lancamento;
    global $data;
    foreach ($dados as $item) {
        $documento = $item['documento'];
        $produto_id = $item['produto_id'];
        $codigo_nf = $item['codigo_nf'];
        $tipo = $item['tipo'];
        $usuario_id = $item['usuario_id'];

        $qtd_ajustado = $item['qtd_ajustado'];
        $novo_estoque = $item['novo_estoque'];
        $ajuste = $item['ajuste'];

        $motivo = isset($item['motivo']) ? $item['motivo'] : '';
        $status = isset($item['status']) ? $item['status'] : 'ok';


        $parceiro_id = isset($item['parceiro_id']) ? $item['parceiro_id'] : '';
        $empresa_id = isset($item['empresa_id']) ? $item['empresa_id'] : '1';
        $forma_pagamento_id = isset($item['forma_pagamento_id']) ? $item['forma_pagamento_id'] : '';

        $item_id_nf = isset($item['item_id_nf']) ? $item['item_id_nf'] : '';
        $id_nf_pai = isset($item['cl_id_nf_pai']) ? $item['cl_id_nf_pai'] : '';

        $valor_saida = isset($item['valor_saida']) ? $item['valor_saida'] : 0;
        $valor_entrada = isset($item['valor_entrada']) ? $item['valor_entrada'] : 0;

        $atualizar_estoque = $item['atualizar_qtd']; //atualizar o estoque/estoque miinimo/estoque maximo na tabela tb_produtos ou não 
    }


    if ($atualizar_estoque == "S") {
        update_registro($conecta, 'tb_produtos', 'cl_id', $produto_id, '', '', 'cl_estoque', $novo_estoque); //atualizar o preço de venda
        update_registro($conecta, 'tb_produtos', 'cl_id', $produto_id, '', '', 'cl_data_ajst_estoque', $data_lancamento);
    }

    $query = "INSERT INTO `tb_ajuste_estoque` (`cl_data_lancamento`, `cl_ajuste_inicial`,
     `cl_documento`, `cl_produto_id`, `cl_tipo`, `cl_quantidade`, `cl_empresa_id`, `cl_parceiro_id`,
      `cl_usuario_id`, `cl_forma_pagamento_id`, `cl_valor_venda`, `cl_valor_compra`, `cl_status`,
       `cl_motivo`, `cl_codigo_nf`, `cl_id_nf`, `cl_id_nf_pai`,`cl_ajuste`) VALUES
        ('$data_lancamento', '0', '$documento', '$produto_id', '$tipo', '$qtd_ajustado', '$empresa_id', '$parceiro_id', '$usuario_id', '$forma_pagamento_id',
         '$valor_saida', '$valor_entrada', '$status',
         '$motivo', '$codigo_nf', '$item_id_nf', '$id_nf_pai','$ajuste' )";
    $insert = mysqli_query($conecta, $query);
    if ($insert) {
        return true;
    } else {
        return mysqli_error($conecta);
    }
}



function valorPorExtenso($valor = 0)
{
    $singular = array("", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
    $plural = array("", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");

    $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
    $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
    $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezessete", "dezoito", "dezenove");
    $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");

    $z = 0;
    $valor = number_format($valor, 2, ".", ".");
    $inteiro = explode(".", $valor);
    for ($i = 0; $i < count($inteiro); $i++) {
        for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++) {
            $inteiro[$i] = "0" . $inteiro[$i];
        }
    }

    $rt = "";
    $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
    for ($i = 0; $i < count($inteiro); $i++) {
        $valor = $inteiro[$i];
        $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
        $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
        $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

        $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
        $t = count($inteiro) - 1 - $i;
        $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
        if ($valor == "000") $z++;
        elseif ($z > 0) $z--;

        if (($t == 1) && ($z > 0) && ($inteiro[0] > 0)) $r .= (($z > 1) ? " de " : "") . $plural[$t];
        if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
    }

    return ($rt ? $rt : "zero");
}



function removerProtocolo($link)
{
    $protocolos = array("http://", "https://");
    foreach ($protocolos as $protocolo) {
        if (strpos($link, $protocolo) === 0) {
            return substr($link, strlen($protocolo));
        }
    }
    return $link;
}

// Função para obter o domínio base do link
function obterDominioBase($link)
{
    $parsed_url = parse_url($link);
    if (isset($parsed_url['scheme']) && isset($parsed_url['host'])) {
        return $parsed_url['scheme'] . '://' . $parsed_url['host'];
    }
    return null;
}

//função para remover imagem
function delete_img($dados)
{
    $dir = $dados['dir'];
    if (file_exists($dir)) {
        $lista_imagens = glob($dir);
        foreach ($lista_imagens as $imagem) {
            unlink($imagem); // Excluir a imagem existente
        }
    }
}



function insert_produto_variante($dados)
{
    $codigo_nf = $dados['codigo_nf'];
    $codigo_nf_novo = $dados['codigo_nf_novo'];
    $variacoes = $dados['variacao'];
    global $data_lancamento;
    global $conecta;
    $variacoes_array = array_map('trim', explode(',', $variacoes));

    $span_descricao_variante = '';

    // Itera sobre as variações e constrói a string de descrições e valores
    foreach ($variacoes_array as $variacao) {
        $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_variante_produto WHERE cl_id = '$variacao'");
        if ($resultados) {
            foreach ($resultados as $linha) {
                $variante_descricao = $linha['cl_descricao'];
                $valor_variante = $linha['cl_valor'];
                $incluir_titulo = $linha['cl_incluir_titulo'];
                if ($incluir_titulo == 1) {
                    $span_descricao_variante .= ($span_descricao_variante ? ', ' : '') . " $valor_variante";
                }
            }
        }
    }
    $query = "INSERT INTO `tb_produtos` (
      `cl_codigo`,
      `cl_data_cadastro`,
      `cl_descricao`,
      `cl_tamanho`,
      `cl_localizacao`,
      `cl_referencia`,
      `cl_equivalencia`,
      `cl_observacao`,
      `cl_codigo_barra`,
      `cl_preco_custo`,
      `cl_preco_venda`,
      `cl_preco_sugerido_venda`,
      `cl_preco_promocao`,
      `cl_data_valida_promocao`,
      `cl_data_validade`,
      `cl_peso_produto`,
      `cl_ult_preco_compra`,
      `cl_desconto_maximo`,
      `cl_margem_lucro`,
      `cl_cest`,
      `cl_ncm`,
      `cl_cst_icms`,
      `cl_cst_pis_s`,
      `cl_cst_pis_e`,
      `cl_cst_cofins_s`,
      `cl_cst_cofins_e`,
      `cl_estoque_minimo`,
      `cl_estoque_maximo`,
      `cl_cfop_interno`,
      `cl_cfop_externo`,
      `cl_fabricante_id`,
      `cl_fabricante`,
      `cl_grupo_id`,
      `cl_und_id`,
      `cl_tipo_id`,
      `cl_status_ativo`,
      `cl_descricao_delivery`,
      `cl_descricao_extendida_delivery`,
      `cl_qtd_adicional_obrigatorio_delivery`,
      `cl_status_adicional_obrigatorio_delivery`,
      `cl_img_produto`,
      `cl_min_produto_delivery`,
      `cl_lancamento`,
      `cl_gtin`,
      `cl_tipo_ecommerce`,
      `cl_condicao`,
      `cl_destaque`,
      `cl_fixo`,
      `cl_codigo_nf_pai`,
      `cl_variacao`
  )
  SELECT
      '$codigo_nf_novo',
      '$data_lancamento',
      CONCAT(`cl_descricao`, ' - ', '$span_descricao_variante'), /* Concatenar a descrição existente com a nova variação */
      `cl_tamanho`,
      `cl_localizacao`,
      `cl_referencia`,
      `cl_equivalencia`,
      `cl_observacao`,
      `cl_codigo_barra`,
      `cl_preco_custo`,
      `cl_preco_venda`,
      `cl_preco_sugerido_venda`,
      `cl_preco_promocao`,
      `cl_data_valida_promocao`,
      `cl_data_validade`,
      `cl_peso_produto`,
      `cl_ult_preco_compra`,
      `cl_desconto_maximo`,
      `cl_margem_lucro`,
      `cl_cest`,
      `cl_ncm`,
      `cl_cst_icms`,
      `cl_cst_pis_s`,
      `cl_cst_pis_e`,
      `cl_cst_cofins_s`,
      `cl_cst_cofins_e`,
      `cl_estoque_minimo`,
      `cl_estoque_maximo`,
      `cl_cfop_interno`,
      `cl_cfop_externo`,
      `cl_fabricante_id`,
      `cl_fabricante`,
      `cl_grupo_id`,
      `cl_und_id`,
      '8',/*grupo variação */
      `cl_status_ativo`,
      `cl_descricao_delivery`,
      `cl_descricao_extendida_delivery`,
      `cl_qtd_adicional_obrigatorio_delivery`,
      `cl_status_adicional_obrigatorio_delivery`,
      `cl_img_produto`,
      `cl_min_produto_delivery`,
      `cl_lancamento`,
      `cl_gtin`,
      `cl_tipo_ecommerce`,
      `cl_condicao`,
      `cl_destaque`,
      `cl_fixo`,
      '$codigo_nf',
      '$variacoes'
  FROM
      `tb_produtos`
  WHERE
      `cl_codigo` = '$codigo_nf' ";
    $inserir_produto = mysqli_query($conecta, $query);
    if (!$inserir_produto) {
        return false;
    }

    //atualizar o a coluna cl_produto_pai para 1
    $produto_variante_id = mysqli_insert_id($conecta);
    update_registro($conecta, 'tb_produtos', 'cl_codigo', $codigo_nf, '', '', 'cl_produto_pai', 1); //definir o produto como pai

    return $produto_variante_id;
}



function faturar_ordem_servico($conecta, $dados)
{
    global $data_lancamento;
    $id_user_logado = verifica_sessao_usuario();
    $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $id_user_logado, "cl_usuario");
    $tipo_os = consulta_tabela($conecta, 'tb_parametros', 'cl_id', 66, "cl_valor"); //verificar se a ordem de serviço segue peças ou obras   
    $servico_default = consulta_tabela($conecta, 'tb_parametros', 'cl_id', 128, "cl_valor");
    $garantia_dias = consulta_tabela($conecta, 'tb_parametros', 'cl_id', 129, "cl_valor"); //verificar a quantidade de dias para os

    $ordem_servico = isset($dados['os']) ? $dados['os'] : 0; //array
    $forma_pagamento_id = isset($dados['forma_pagamento']) ? $dados['forma_pagamento'] : 0;
    $opcao_faturamento =  isset($dados['opcao_faturamento']) ? $dados['opcao_faturamento'] : 0;
    $atividade =  isset($dados['atividade']) ? $dados['atividade'] : 0;
    $form_id =  isset($dados['form_id']) ? $dados['form_id'] : '';
    $serie_servico =  isset($dados['serie_servico']) ? $dados['serie_servico'] : ''; //id das serie
    $serie_material =  isset($dados['serie_material']) ? $dados['serie_material'] : ''; //id das serie

    $codigo_nf =  isset($dados['codigo_nf_novo']) ? $dados['codigo_nf_novo'] : md5(uniqid(time())); //se não for definido gerar um novo codigo nf
    $serie_os = consulta_tabela($conecta, 'tb_serie', 'cl_id', 15, 'cl_descricao'); //numero_atual 

    $valor_aliquota_servico = consulta_tabela($conecta, 'tb_parametros', 'cl_id', 130, 'cl_valor');

    /*iniciando variaveis */
    $valor_total_infra = 0;
    $valor_total_servico = 0;
    $valor_total_material = 0;

    $qtd_servico = 0;
    $qtd_material = 0;

    $valor_total_bruto = 0;
    $valor_total_liquido = 0;
    $valor_total_desconto = 0;
    $valor_total_taxa = 0;
    $valor_total_despesa = 0;

    $valor_total_taxa_servico = 0;
    $valor_total_taxa_material = 0;
    $valor_total_desconto_material = 0;
    $valor_total_desconto_servico = 0;
    $valor_total_despesa_material = 0;

    $valor_liquido_material = 0;
    $valor_liquido_material = 0;

    $status = array("status" => true);
    $terceirizado_id = 0;

    if ($serie_material != "" and $serie_servico != "") {

        $serie_id_material = $serie_material;
        $serie_id_servico = $serie_servico;

        /* numero serie serviço  */
        $numero_nf_atual_serie_servico = consulta_tabela($conecta, 'tb_serie', 'cl_id', $serie_id_servico, 'cl_valor'); //numero_atual 
        $numero_nf_prox_serie_servico = $numero_nf_atual_serie_servico + 1;

        /* numero serie material  */
        $numero_nf_atual_serie_material = consulta_tabela($conecta, 'tb_serie', 'cl_id', $serie_id_material, 'cl_valor'); //numero_atual 
        $numero_nf_prox_serie_material = $numero_nf_atual_serie_material + 1;

        $serie_material = consulta_tabela($conecta, 'tb_serie', 'cl_id', $serie_material, 'cl_descricao'); //descricao das 
        $serie_servico = consulta_tabela($conecta, 'tb_serie', 'cl_id', $serie_servico, 'cl_descricao');
    } else {
        $status = array("status" => false, "mensagem" => "Series não identificadas, favor, verifique");
        return $status;
    }

    if (count($ordem_servico) > 0) {
        if ($opcao_faturamento == "recibo") { //recibo unificado, serviço e material junto em um documento
            foreach ($ordem_servico as $os) {
                $dados_os = consulta_dados_os($conecta, $os); //consultando detalhes da os
                $codigo_nf_os = $dados_os['cl_codigo_nf'];
                $parceiro_id = $dados_os['cl_parceiro_id'];
                $numero_os = $dados_os['cl_numero_nf'];
                $serie_nf = $dados_os['cl_serie_nf'];

                $taxa_adiantamento_os = $dados_os['cl_taxa_adiantamento'];
                $desconto_os = $dados_os['cl_desconto'];
                $valor_servico_os = $dados_os['cl_valor_servico'];
                $valor_pecas_os = $dados_os['cl_valor_pecas'];
                $ordem_fechada = $dados_os['cl_ordem_fechada'];
                $valor_despesa_os = $dados_os['cl_valor_despesa'];

                $valor_total_desconto += $desconto_os; //valor total do desconto
                $valor_total_taxa += $taxa_adiantamento_os; //valor total dos adiantamentos

                $valor_total_bruto = $valor_total_bruto + ($valor_pecas_os + $valor_servico_os + $valor_despesa_os); //valor bruto, sem taxa de adiantamento e sem o desconto
                $valor_total_liquido = $valor_total_liquido + ($valor_pecas_os + $valor_servico_os + $valor_despesa_os - $desconto_os); //valor liquido

                /*consultar detalhes do item da os */
                $query = "SELECT * FROM tb_infraestrutura_os WHERE cl_codigo_nf = '$codigo_nf_os' AND cl_tipo IN ('SERVICO', 'MATERIAL') ";
                $consulta = mysqli_query($conecta, $query);
                if (!$consulta) {
                    $status = array("status" => false, "mensagem" => "Erro na consulta à base de dados.");
                    break;
                } else {
                    $qtd_consulta = mysqli_num_rows($consulta);
                    if ($qtd_consulta > 0) {
                        while ($linha = mysqli_fetch_assoc($consulta)) {
                            $tipo = $linha['cl_tipo'];
                            $produto_id = $linha['cl_produto_id'];
                            $item_descricao = $linha['cl_item_descricao'];
                            $valor_unitario = $linha['cl_valor_unitario'];
                            $valor_total = $linha['cl_valor_total'];
                            $unidade = $linha['cl_unidade'];
                            $referencia = $linha['cl_referencia'];
                            $quantidade_requisitada = $linha['cl_quantidade_requisitada'];
                            $quantidade_orcada = $linha['cl_quantidade_orcada'];
                            $parceiro_terceirizado_id = $linha['cl_parceiro_terceirizado_id'];
                            if (($parceiro_terceirizado_id != 0) and ($terceirizado_id == 0)) {
                                $terceirizado_id = $parceiro_terceirizado_id;
                            }

                            $valor_total_infra += $valor_total;
                            if ($tipo == "SERVICO") { //inserir os serviços na nf
                                $qtd_servico++; //contabilzar os serviços

                                $query = "INSERT INTO `tb_nf_saida_item` (`cl_data_movimento`, `cl_codigo_nf`, `cl_usuario_id`,
                                `cl_serie_nf`, `cl_numero_nf`,`cl_item_id`, `cl_descricao_item`, `cl_quantidade`, `cl_unidade`, 
                                `cl_valor_unitario`, `cl_valor_total`, `cl_referencia`, `cl_status` )
                                  VALUES ( '$data_lancamento', '$codigo_nf', '$id_user_logado', '$serie_servico', '$numero_nf_prox_serie_servico','$servico_default',
                                   '$item_descricao', '$quantidade_orcada', '$unidade', '$valor_unitario', '$valor_total', '$referencia', '1' ) ";
                                $insert_servico = mysqli_query($conecta, $query);
                                if (!$insert_servico) {
                                    $status = array("status" => false, "mensagem" => "Erro ao inserir o produto, favor verifique com suporte");
                                    break 2;
                                }

                                // $valor_total_servico += $valor_total;
                                // $descricao_total_servico .= $item_descricao . " ; ";
                            }

                            if ($tipo == "MATERIAL") { //inserir os materias a nf
                                $qtd_material++; //contabilizar os materiais

                                if ($quantidade_requisitada < $quantidade_orcada) { //verificar se todas as peças estão requisitadas
                                    $status = array("status" => false, "mensagem" => "O item " . utf8_encode($item_descricao) . "
                                     que está alocada na $serie_nf$numero_os não está com todas as peças requisitidas, favor, verifique");
                                    break 2;
                                } else {
                                    $query = "INSERT INTO `tb_nf_saida_item` (`cl_data_movimento`, `cl_codigo_nf`, `cl_usuario_id`,
                                     `cl_serie_nf`, `cl_numero_nf`, `cl_item_id`, `cl_descricao_item`, `cl_quantidade`, `cl_unidade`, 
                                     `cl_valor_unitario`, `cl_valor_total`, `cl_referencia`, `cl_status` )
                                       VALUES ( '$data_lancamento', '$codigo_nf', '$id_user_logado', '$serie_servico', '$numero_nf_prox_serie_servico', '$produto_id',
                                        '$item_descricao', '$quantidade_requisitada', '$unidade', '$valor_unitario', '$valor_total', '$referencia', '1' ) ";
                                    $insert_produto = mysqli_query($conecta, $query);
                                    if (!$insert_produto) {
                                        $status = array("status" => false, "mensagem" => "Erro ao inserir o produto, favor verifique com suporte");
                                        break 2;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if ($status['status'] == true) {
                if ($qtd_material == 0 and $qtd_servico == 0) { //verificar se tem material ou serviço  na os
                    $status = array("status" => false, "mensagem" => "É necessário alocar pelo menos um serviço ou material(requisitados) na $serie_servico para prosseguir.");
                }
            }

            if ($status['status'] == true) { //verificar se não ocorreu nenhum erro
                $status_recebimento = $valor_total_liquido == $valor_total_taxa ? 2 : 1; //verificar se o valor do adiantamento abate o valor total liquido da os 1 - pendente e 2- recebido
                $data_recebimento = $status_recebimento == 2 ? $data_lancamento : ''; //verificar se está no status recebido
                $usuario_id_recebimento = $status_recebimento == 2 ? $id_user_logado : ''; //verificar se está no status recebido

                /*servico */
                $retem_iss = consulta_tabela($conecta, 'tb_parceiros', 'cl_id', $parceiro_id, 'cl_retem_iss');
                $valor_base_calculo_servico = $valor_total_infra;

                $valor_iss = (($valor_total_infra * $valor_aliquota_servico) / 100);
                $valor_iss_retido = 0;

                if ($retem_iss == 1) { //se empresa for retentora do iss
                    $valor_iss_retido = $valor_iss;
                }



                $query = "INSERT INTO `tb_nf_saida` 
                        (`cl_data_movimento`, `cl_codigo_nf`, `cl_finalidade`, `cl_tipo_documento_nf`, `cl_parceiro_id`, 
                        `cl_forma_pagamento_id`, `cl_numero_nf`, `cl_serie_nf`, `cl_status_recebimento`,`cl_data_recebimento`,`cl_usuario_id_recebimento`, 
                         `cl_status_venda`, `cl_valor_bruto`, `cl_valor_desconto`, `cl_valor_liquido`,`cl_valor_adto`, `cl_usuario_id`, `cl_retem_iss`,
                         `cl_operacao`,`cl_vendedor_id`, `cl_visualizar_duplicata`,`cl_tipo_frete_id`, 
                          `cl_valor_base_calculo`, `cl_valor_aliquota` , `cl_valor_iss`, `cl_valor_iss_retido`,
                          `cl_atividade_id`,`cl_natureza_operacao_servico`,`cl_intermediario_id` ) 
                        VALUES 
                        ('$data_lancamento', '$codigo_nf', '1', '1', '$parceiro_id', '$forma_pagamento_id', 
                        '$numero_nf_prox_serie_servico', '$serie_servico',  '$status_recebimento', '$data_recebimento','$usuario_id_recebimento', '1', 
                        '$valor_total_bruto', '$valor_total_desconto', '$valor_total_liquido',  '$valor_total_taxa', '$id_user_logado', '1',
                         'SERVICO', '$id_user_logado', '1','9',
                         '$valor_base_calculo_servico','$valor_aliquota_servico','$valor_iss','$valor_iss_retido','$atividade','1','$terceirizado_id' )";
                $insert_nf = mysqli_query($conecta, $query);
                if (!$insert_nf) {
                    $status = array("status" => false, "mensagem" => "Erro ao inserir a nota fiscal.");
                } else {
                    gerar_nfs_item($codigo_nf); //recalcular valores de base,cfop.... dos produtos

                    $status = array("status" => true, "mensagem" => "Faturamento realizado com sucesso, gerado a $serie_servico $numero_nf_prox_serie_material");


                    /*atualizar os */
                    foreach ($ordem_servico as $os) {
                        update_registro($conecta, 'tb_os', 'cl_id', $os, '', '', 'cl_situacao', 1); //atualizar a situação da os para faturado
                        update_registro($conecta, 'tb_os', 'cl_id', $os, '', '', 'cl_codigo_nf_recibo', $codigo_nf); //atuializar a coluna codigo_nf_recibo 

                        // Calcular a data de término da garantia
                        $data_nf_garantia = date('Y-m-d', strtotime("+$garantia_dias days"));
                        // Atualizar a data de término da garantia na OS
                        update_registro($conecta, 'tb_os', 'cl_id', $os, '', '', 'cl_data_nf_garantia_loja', $data_nf_garantia);
                        update_registro($conecta, 'tb_os', 'cl_id', $os, '', '', 'cl_nf_garantia_loja', "$serie_servico$numero_nf_prox_serie_material");
                        update_registro($conecta, 'tb_os', 'cl_id', $os, '', '', 'cl_validade_garantia_loja', $garantia_dias);
                    }
                    //ATUALIZAR A NUMERAÇÃO DA SERIE
                    update_registro($conecta, 'tb_serie', 'cl_id', $serie_id_servico, '', '', 'cl_valor', $numero_nf_prox_serie_servico);
                }
            }
        } else { //gerar duas notas
            $codigo_nf_servico = md5(uniqid(time()));
            $codigo_nf_material = md5(uniqid(time()));
            foreach ($ordem_servico as $os) {

                $dados_os = consulta_dados_os($conecta, $os); //consultando detalhes da os
                $codigo_nf_os = $dados_os['cl_codigo_nf'];
                $serie_nf = $dados_os['cl_serie_nf'];
                $numero_os = $dados_os['cl_numero_nf'];

                $parceiro_id = $dados_os['cl_parceiro_id'];
                $taxa_adiantamento_os = $dados_os['cl_taxa_adiantamento'];
                $desconto_os = $dados_os['cl_desconto'];
                $valor_servico_os = $dados_os['cl_valor_servico'];
                $valor_pecas_os = $dados_os['cl_valor_pecas'];
                $ordem_fechada = $dados_os['cl_ordem_fechada'];
                $valor_despesa_os = $dados_os['cl_valor_despesa'];

                $valor_total_desconto += $desconto_os; //valor total do desconto
                $valor_total_taxa += $taxa_adiantamento_os; //valor total dos adiantamentos
                $valor_total_despesa += $valor_despesa_os; //valor total dos adiantamentos


                // $valor_total_bruto = $valor_total_bruto + ($valor_pecas_os + $valor_servico_os + $valor_despesa_os); //valor bruto, sem taxa de adiantamento e sem o desconto
                // $valor_total_liquido = $valor_total_liquido + ($valor_pecas_os + $valor_servico_os + $valor_despesa_os - $desconto_os); //valor liquido




                /*consultar detalhes do item da os */
                $query = "SELECT * FROM tb_infraestrutura_os WHERE cl_codigo_nf = '$codigo_nf_os' AND cl_tipo IN ('SERVICO', 'MATERIAL') ";
                $consulta = mysqli_query($conecta, $query);
                if (!$consulta) {
                    $status = array("status" => false, "mensagem" => "Erro na consulta à base de dados.");
                    break;
                } else {
                    $qtd_consulta = mysqli_num_rows($consulta);
                    if ($qtd_consulta > 0) {
                        while ($linha = mysqli_fetch_assoc($consulta)) {
                            $tipo = $linha['cl_tipo'];
                            $produto_id = $linha['cl_produto_id'];
                            $item_descricao = $linha['cl_item_descricao'];
                            $valor_unitario = $linha['cl_valor_unitario'];
                            $valor_total = $linha['cl_valor_total'];
                            $unidade = $linha['cl_unidade'];
                            $referencia = $linha['cl_referencia'];
                            $quantidade_requisitada = $linha['cl_quantidade_requisitada'];
                            $quantidade_orcada = $linha['cl_quantidade_orcada'];
                            $parceiro_terceirizado_id = $linha['cl_parceiro_terceirizado_id'];

                            if (($parceiro_terceirizado_id != 0) and ($terceirizado_id == 0)) {
                                $terceirizado_id = $parceiro_terceirizado_id;
                            }

                            if ($tipo == "SERVICO") { //inserir os serviços na nf
                                $qtd_servico++; //contabilzar os serviços

                                $valor_total_servico += $valor_total; //valor bruto 
                                $query = "INSERT INTO `tb_nf_saida_item` (`cl_data_movimento`, `cl_codigo_nf`, `cl_usuario_id`,
                                `cl_serie_nf`, `cl_numero_nf`,`cl_item_id`, `cl_descricao_item`, `cl_quantidade`, `cl_unidade`, 
                                `cl_valor_unitario`, `cl_valor_total`, `cl_referencia`, `cl_status`  )
                                  VALUES ( '$data_lancamento', '$codigo_nf_servico', '$id_user_logado', '$serie_servico', '$numero_nf_prox_serie_servico','$servico_default',
                                   '$item_descricao', '$quantidade_orcada', '$unidade', '$valor_unitario', '$valor_total', '$referencia', '1' ) ";
                                $insert_servico = mysqli_query($conecta, $query);
                                if (!$insert_servico) {
                                    $status = array("status" => false, "mensagem" => "Erro ao inserir o produto, favor verifique com suporte");
                                    break 2;
                                }
                            }

                            if ($tipo == "MATERIAL") { //inserir os materias a nf
                                $qtd_material++; //contabilizar os materiais
                                if ($quantidade_requisitada < $quantidade_orcada) { //verificar se todas as peças estão requisitadas
                                    $status = array("status" => false, "mensagem" => "O item " . utf8_encode($item_descricao) . "
                                    que está alocada na $serie_nf$numero_os não está com todas as peças requisitidas, favor, verifique");
                                    break 2;
                                } else {
                                    $valor_total_material += $valor_total; //valor bruto 
                                    $query = "INSERT INTO `tb_nf_saida_item` (`cl_data_movimento`, `cl_codigo_nf`, `cl_usuario_id`,
                                     `cl_serie_nf`, `cl_numero_nf`, `cl_item_id`, `cl_descricao_item`, `cl_quantidade`, `cl_unidade`, 
                                     `cl_valor_unitario`, `cl_valor_total`, `cl_referencia`, `cl_status`  )
                                       VALUES ( '$data_lancamento', '$codigo_nf_material', '$id_user_logado', '$serie_material', '$numero_nf_prox_serie_material', '$produto_id',
                                        '$item_descricao', '$quantidade_requisitada', '$unidade', '$valor_unitario', '$valor_total', '$referencia', '1' ) ";
                                    $insert_produto = mysqli_query($conecta, $query);
                                    if (!$insert_produto) {
                                        $status = array("status" => false, "mensagem" => "Erro ao inserir o produto, favor verifique com suporte");
                                        break 2;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if ($status['status'] == true) {
                if ($qtd_material == 0 or $qtd_servico == 0) { //verificar se tem material ou serviço  na os
                    $status = array("status" => false, "mensagem" => "É necessário alocar pelo menos um serviço e um material(requisitados) para a opção escolhida.");
                }
            }

            if ($status['status'] == true) { //verificar se não ocorreu nenhum erro
                // $status_recebimento = $valor_total_liquido == $valor_total_taxa ? 2 : 1; //verificar se o valor do adiantamento abate o valor total liquido da os 1 - pendente e 2- recebido
                // $data_recebimento = $status_recebimento == 2 ? $data_lancamento : ''; //verificar se está no status recebido
                // $usuario_id_recebimento = $status_recebimento == 2 ? $id_user_logado : ''; //verificar se está no status recebido


                /*outras despesas será somando ao valor bruto no dcumento do material */
                if ($valor_total_despesa > 0) {
                    $valor_total_despesa_material = $valor_total_despesa;
                }

                /*valor do desconto será atribuido ao documento do material e serviço se for possivel */
                if ($valor_total_desconto > 0) {
                    if ($valor_total_desconto <= $valor_total_material) {
                        // Se o valor do material suportar todo o desconto, será atribuído integralmente ao material
                        $valor_total_desconto_material = $valor_total_desconto;
                        $valor_total_desconto_servico = 0;
                    } elseif ($valor_total_desconto <= $valor_total_servico) {
                        // Se o valor do serviço suportar todo o desconto, será atribuído integralmente ao serviço
                        $valor_total_desconto_servico = $valor_total_desconto;
                        $valor_total_desconto_material = 0;
                    } else {
                        // Se o desconto é maior que ambos, divide entre material e serviço
                        $valor_total_desconto_material = $valor_total_material;
                        $valor_total_desconto_servico = $valor_total_desconto - $valor_total_material;
                    }
                }

                /* Valor da nota */
                /* Material */

                $valor_bruto_material = $valor_total_material;
                $valor_liquido_material = $valor_total_material +  $valor_total_despesa - $valor_total_desconto_material;


                /* Serviço */

                $valor_bruto_servico = $valor_total_servico;
                $valor_liquido_servico = $valor_total_servico - $valor_total_desconto_servico;


                if ($valor_total_taxa > 0) {
                    if ($valor_total_taxa <= $valor_liquido_servico) {
                        // Se o valor do serviço suportar o valor da taxa, será atribuído o valor da taxa ao documento de serviço
                        $valor_total_taxa_servico = $valor_total_taxa;
                        $valor_total_taxa_material = 0;
                    } elseif ($valor_total_taxa <= $valor_liquido_servico + $valor_liquido_material) {
                        // Se o valor da taxa for maior do que o serviço, distribui o restante no material
                        $valor_total_taxa_servico = $valor_liquido_servico;
                        $valor_total_taxa_material = $valor_total_taxa - $valor_liquido_servico;
                    } else {
                        // Se a taxa total é maior do que o somatório do serviço e material, distribui o máximo possível
                        $valor_total_taxa_servico = $valor_liquido_servico;
                        $valor_total_taxa_material = $valor_liquido_material;
                    }
                }


                /*status recebimento */
                /*material */
                $status_recebimento_material = $valor_liquido_material == $valor_total_taxa_material ? 2 : 1; //verificar se o valor do adiantamento abate o valor total liquido da os 1 - pendente e 2- recebido
                $data_recebimento_material = $status_recebimento_material == 2 ? $data_lancamento : ''; //verificar se está no status recebido
                $usuario_id_recebimento_material = $status_recebimento_material == 2 ? $id_user_logado : ''; //verificar se está no status recebido

                /*servico */
                $status_recebimento_servico = $valor_liquido_servico == $valor_total_taxa_servico ? 2 : 1; //verificar se o valor do adiantamento abate o valor total liquido da os 1 - pendente e 2- recebido
                $data_recebimento_servico = $status_recebimento_servico == 2 ? $data_lancamento : ''; //verificar se está no status recebido
                $usuario_id_recebimento_servico = $status_recebimento_servico == 2 ? $id_user_logado : ''; //verificar se está no status recebido


                /*servico */
                $retem_iss = consulta_tabela($conecta, 'tb_parceiros', 'cl_id', $parceiro_id, 'cl_retem_iss');
                $valor_base_calculo_servico = $valor_total_infra;

                $valor_iss = (($valor_total_infra * $valor_aliquota_servico) / 100);
                $valor_iss_retido = 0;

                if ($retem_iss == 1) { //se empresa for retentora do iss
                    $valor_iss_retido = $valor_iss;
                }




                /*serviço */
                $query = "INSERT INTO `tb_nf_saida` 
                        (`cl_data_movimento`, `cl_codigo_nf`, `cl_finalidade`, `cl_tipo_documento_nf`, `cl_parceiro_id`, 
                        `cl_forma_pagamento_id`, `cl_numero_nf`, `cl_serie_nf`, `cl_status_recebimento`,`cl_data_recebimento`,`cl_usuario_id_recebimento`, 
                         `cl_status_venda`, `cl_valor_bruto`, `cl_valor_desconto`, `cl_valor_liquido`,`cl_valor_adto`, `cl_usuario_id`, `cl_retem_iss`,
                         `cl_operacao`,`cl_vendedor_id`, `cl_visualizar_duplicata`,`cl_tipo_frete_id`,
                        `cl_valor_base_calculo`, `cl_valor_aliquota` , `cl_valor_iss`, `cl_valor_iss_retido`,`cl_atividade_id`,
                        `cl_natureza_operacao_servico`, `cl_intermediario_id`
                         ) 
                        VALUES 
                        ('$data_lancamento', '$codigo_nf_servico', '1', '1', '$parceiro_id', '$forma_pagamento_id', 
                        '$numero_nf_prox_serie_servico', '$serie_servico', '$status_recebimento_servico', '$data_recebimento_servico','$usuario_id_recebimento_servico', '1', 
                        '$valor_bruto_servico', '$valor_total_desconto_servico', '$valor_liquido_servico', '$valor_total_taxa_servico', '$id_user_logado', '$retem_iss',
                         'SERVICO', '$id_user_logado', '1', '9',
                        '$valor_base_calculo_servico','$valor_aliquota_servico','$valor_iss','$valor_iss_retido','$atividade','1' ,'$terceirizado_id' )";
                $insert_nf_servico = mysqli_query($conecta, $query);


                /*material */
                $query = "INSERT INTO `tb_nf_saida` 
                        (`cl_data_movimento`, `cl_codigo_nf`, `cl_finalidade`, `cl_tipo_documento_nf`, `cl_parceiro_id`, 
                        `cl_forma_pagamento_id`, `cl_numero_nf`, `cl_serie_nf`, `cl_status_recebimento`,`cl_data_recebimento`,`cl_usuario_id_recebimento`, 
                         `cl_status_venda`, `cl_valor_bruto`, `cl_valor_desconto`, `cl_valor_liquido`,`cl_valor_adto`, `cl_usuario_id`, `cl_retem_iss`,
                         `cl_operacao`,`cl_vendedor_id`, `cl_visualizar_duplicata`,`cl_tipo_frete_id`,`cl_valor_outras_despesas`,
                       `cl_valor_base_calculo`, `cl_valor_aliquota` , `cl_valor_iss`, `cl_valor_iss_retido`,`cl_atividade_id`,`cl_natureza_operacao_servico` ) 
                        VALUES 
                        ('$data_lancamento', '$codigo_nf_material', '1', '1', '$parceiro_id', '$forma_pagamento_id', 
                        '$numero_nf_prox_serie_material', '$serie_material',  '$status_recebimento_material', '$data_recebimento_material','$usuario_id_recebimento_material', '1', 
                        '$valor_bruto_material', '$valor_total_desconto_material', '$valor_liquido_material',  '$valor_total_taxa_material', '$id_user_logado', '$retem_iss',
                         'SERVICO', '$id_user_logado', '1','9','$valor_total_despesa_material',
                         '$valor_base_calculo_servico','$valor_aliquota_servico','$valor_iss','$valor_iss_retido','$atividade','1' )";
                $insert_nf_material = mysqli_query($conecta, $query);


                if (!$insert_nf_servico or !$insert_nf_material) {
                    $status = array("status" => false, "mensagem" => "Erro ao inserir a nota fiscal.");
                } else {
                    gerar_nfs_item($codigo_nf_servico); //recalcular valores de base,cfop.... dos produtos
                    gerar_nfs_item($codigo_nf_material); //recalcular valores de base,cfop.... dos produtos

                    $status = array("status" => true, "mensagem" => "Faturamento realizado com sucesso, gerado a $serie_material $numero_nf_prox_serie_material e a $serie_servico $numero_nf_prox_serie_servico");



                    /*atualizar os */
                    foreach ($ordem_servico as $os) {
                        update_registro($conecta, 'tb_os', 'cl_id', $os, '', '', 'cl_situacao', 1); //atualizar a situação da os para faturado
                        update_registro($conecta, 'tb_os', 'cl_id', $os, '', '', 'cl_codigo_nf_material', $codigo_nf_material); //atualizar a situação da os para faturado
                        update_registro($conecta, 'tb_os', 'cl_id', $os, '', '', 'cl_codigo_nf_servico', $codigo_nf_servico); //atualizar a situação da os para faturado
                        // Calcular a data de término da garantia
                        $data_nf_garantia = date('Y-m-d', strtotime("+$garantia_dias days"));
                        // Atualizar a data de término da garantia na OS
                        update_registro($conecta, 'tb_os', 'cl_id', $os, '', '', 'cl_data_nf_garantia_loja', $data_nf_garantia); //data calculada da garantia
                        update_registro($conecta, 'tb_os', 'cl_id', $os, '', '', 'cl_nf_garantia_loja', "$serie_material $numero_nf_prox_serie_material"); //bf= da garantia
                        update_registro($conecta, 'tb_os', 'cl_id', $os, '', '', 'cl_validade_garantia_loja', $garantia_dias);
                    }

                    //ATUALIZAR A NUMERAÇÃO DAS SERIES
                    update_registro($conecta, 'tb_serie', 'cl_id', $serie_id_servico, '', '', 'cl_valor', $numero_nf_prox_serie_servico);
                    update_registro($conecta, 'tb_serie', 'cl_id', $serie_id_material, '', '', 'cl_valor', $numero_nf_prox_serie_material);
                }
            }
        }

        return $status;
    }
}



function consulta_dados_os($conecta, $form_id)
{
    $query = "SELECT  * FROM tb_os WHERE cl_id = '$form_id'";
    // Preparando a query
    $resultado = mysqli_query($conecta, $query);
    // Retornando o resultado como um array associativo
    $dados = mysqli_fetch_assoc($resultado);
    return $dados;
}



function sendEmailLoja($mail, $email_destinatario, $assunto, $attbody, $html) //enviar email
{
    global $conecta;
    global $url_init_img;
    $host_email = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '73', "cl_valor");
    $nome_ecommerce = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '64', "cl_valor");
    $email_remetente = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '74', "cl_valor");
    $senha_email = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '62', "cl_valor");
    $porta_email = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '77', "cl_valor");
    $nome_fantasia = utf8_encode(consulta_tabela($conecta, 'tb_empresa', 'cl_id', '1', "cl_nome_fantasia"));
    $nome_site = utf8_encode(consulta_tabela($conecta, 'tb_empresa', 'cl_id', '1', "cl_empresa"));

    $whatsap = (consulta_tabela($conecta, 'tb_parametros', 'cl_id', 44, 'cl_valor')); //numero whatsap
    $instagram = (consulta_tabela($conecta, 'tb_parametros', 'cl_id', 43, 'cl_valor')); //link do instagram
    $facebook = (consulta_tabela($conecta, 'tb_parametros', 'cl_id', 80, 'cl_valor')); //link do facebook
    // $email = (consulta_tabela('tb_parametros', 'cl_id', 74, 'cl_valor')); //email para contato
    // $telefone = (consulta_tabela('tb_parametros', 'cl_id', 81, 'cl_valor')); //telefone para contato


    try {
        // Configurações do servidor SMTP
        $mail->isSMTP();
        $mail->Host = $host_email;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Username = $email_remetente;
        $mail->Password = $senha_email;
        $mail->Port = $porta_email;
        $mail->CharSet = 'UTF-8'; // Configuração da codificação

        // Define o remetente
        $mail->setFrom($email_remetente, $nome_fantasia);

        // Define o destinatário
        $mail->addAddress($email_destinatario);
        $mail->Subject = $assunto;
        $mail->AltBody = $attbody; // Caso o cliente de e-mail não suporte HTML

        $html = "  <div style='max-width: 700px; border:1px solid black; padding:5px; margin-left: auto; margin-right: auto; font-family: Arial, sans-serif;'>
            <div style='text-align: center; padding: 20px 0;'>
                <img src='$url_init_img/$nome_site/img/ecommerce/logo/logo.png' alt='Logo' width='120'>
            </div>" . $html . "<div style='max-width: 700px; margin-left: auto; margin-right: auto;'>
            <hr>
            <div style='text-align: center;'>
            <p style='font-size: 0.8em;'> Atenciosamente, $nome_fantasia</p>
                <a href='https://api.whatsapp.com/send?phone=$whatsap'><img src='https://i.pinimg.com/564x/26/88/29/268829190281a967d829180a7e0db375.jpg' alt='Whatsapp' style='width: 30px;'></a>
                <a href='$instagram'><img src='https://toppng.com/uploads/preview/ew-instagram-logo-transparent-related-keywords-logo-instagram-vector-2017-115629178687gobkrzwak.png' alt='Instagram' style='width: 30px;'></a>
                <a href='$facebook'><img src='https://i1.wp.com/www.multarte.com.br/wp-content/uploads/2019/03/logo-facebook-png.png?fit=696%2C696&ssl=1' alt='Facebook' style='width: 30px; margin-left: 5px;'></a>
            </div>
        <div>";

        $mail->Body = $html;

        // Envia o e-mail
        $mail->send();
        // update_registro("tb_pre_venda", 'cl_id', $external_reference, "", "", "cl_email_verificado", 1);
        return true;
    } catch (Exception $e) {
        // update_registro("tb_pre_venda", 'cl_id', $external_reference, "", "", "cl_email_verificado", 0);
        return false;
        // echo 'Erro ao enviar o e-mail: ', $mail->ErrorInfo;
    }
}


// function recursos_ordem_servico($conecta, $dados)
// { //dados é um array

//     $codigo_nf =  isset($dados['codigo_nf']) ? $dados['codigo_nf'] : ''; //id das serie
//     $matricula =  isset($dados['matricula']) ? $dados['matricula'] : ''; //id das serie

// }


function enviar_nf_loja($conecta, $id_nf)
{

    global  $conecta;
    global $data;

    $sub_acao = "enviar_nf";
    $ambiente = verficar_paramentro($conecta, "tb_parametros", "cl_id", "35"); // 1 - homologacao 2 - producao
    $opcao_nfs = verficar_paramentro($conecta, "tb_parametros", "cl_id", "133"); // 1 - nacional 2 - prefeitura

    if ($ambiente == "1") {
        $server = verficar_paramentro($conecta, "tb_parametros", "cl_id", "60");
        $login =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "58"); //token para homologacao
        $server_danfe  = verficar_paramentro($conecta, "tb_parametros", "cl_id", "68");
    } elseif ($ambiente == "2") {
        $server =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "61");
        $login = verficar_paramentro($conecta, "tb_parametros", "cl_id", "59"); //token para producao
        $server_danfe =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "69");
    } else {
        $server = "";
        $login = "";
    }
    $password = "";

    $select = "SELECT * from tb_nf_saida where cl_id = $id_nf";
    $consultar_nf = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consultar_nf);
    $numero_nf = ($linha['cl_numero_nf']);
    $serie_nf = ($linha['cl_serie_nf']);
    $codigo_nf = ($linha['cl_codigo_nf']);
    $parceiro_id = ($linha['cl_parceiro_id']);
    $finalidade_id = ($linha['cl_finalidade']);
    $tipo_documento = ($linha['cl_tipo_documento_nf']);
    $forma_pagamento = ($linha['cl_forma_pagamento_id']);
    $tipo_forma_pagamento = (consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $forma_pagamento, "cl_tipo_pagamento_nf"));
    if ($tipo_forma_pagamento == 0) {
        $tipo_forma_pagamento = 99; //outros
    }
    $cfop = ($linha['cl_cfop']);
    $primeiroNumeroCfop = substr($cfop, 0, 1); // Extrai o primeiro caractere (índice 0)
    if ($primeiroNumeroCfop != 6) { //operação interna // nfc
        $local_destino = 1;
    } else { //operação externa
        $local_destino = 2;
    }
    $descricao_cfop = utf8_encode(consulta_tabela($conecta, "tb_cfop", "cl_codigo_cfop", $cfop, "cl_desc_cfop"));
    $frete = ($linha['cl_tipo_frete_id']);


    $desconto_nota = $linha['cl_valor_desconto'];
    $observacao = utf8_encode($linha['cl_observacao']);
    $informacao_adicional_servico = ($linha['cl_observacao']);
    $numero_pedido = $linha['cl_numero_pedido'];

    $visualizar_duplicata = $linha['cl_visualizar_duplicata'];
    $cpf_cnpj_avulso_nf = $linha['cl_cpf_cnpj_avulso_nf'];

    /*transporadora */
    $placa = $linha['cl_placa_trans'];
    $uf_veiculo = $linha['cl_uf_veiculo_trans'];
    $quantidade_tra = $linha['cl_quantidade_trans'];
    $especie_tra = $linha['cl_especie_trans'];

    $peso_bruto = $linha['cl_peso_bruto'];
    $peso_liquido = $linha['cl_peso_liquido'];
    $outras_despesas = $linha['cl_valor_outras_despesas'];
    $valor_frete = $linha['cl_valor_frete'];

    $valor_seguro = $linha['cl_valor_seguro'];

    $chave_acesso_ref = $linha['cl_chave_acesso_referencia']; //devolucao
    $numero_nf_ref = $linha['cl_numero_nf_ref']; //devolucao
    $retem_iss = $linha['cl_retem_iss']; //serviço

    $caminho_pdf_nf = $linha['cl_pdf_nf'];
    $caminho_xml_nf = $linha['cl_caminho_xml_nf'];
    $transportadora_id = $linha['cl_transportadora_id'];
    $totais = resumo_valor_nf_saida($conecta, $codigo_nf); //informações sobre os itens na nota
    $valor_total_produtos = $totais['total_valor_produtos'];
    $valor_total_produtos = abs($valor_total_produtos);     // Transformando em positivo quando se tratar de estorno ou devoluçao
    $icms_sub_nota = $totais['total_valor_icms_sub'];
    $ipi_nota = $totais['total_valor_ipi'];


    /* Serviço */
    $valor_pis_servico = ($linha['cl_valor_pis_servico']);
    $valor_cofins_servico = ($linha['cl_valor_cofins_servico']);
    $valor_deducoes = ($linha['cl_valor_deducoes']);
    $valor_inss = ($linha['cl_valor_inss']);
    $valor_ir = ($linha['cl_valor_ir']);
    $valor_csll = ($linha['cl_valor_csll']);
    $valor_iss = ($linha['cl_valor_iss']);
    $valor_iss_retido = ($linha['cl_valor_iss_retido']);
    $valor_outras_retencoes = ($linha['cl_valor_outras_retencoes']);
    $valor_base_calculo = ($linha['cl_valor_base_calculo']);
    $aliq_servico = ($linha['cl_valor_aliquota']);
    $valor_desconto_condicionado = ($linha['cl_valor_desconto_condicionado']);
    $valor_desconto_incondicionado = ($linha['cl_valor_desconto_incondicionado']);
    $atividade_id = ($linha['cl_atividade_id']);
    $natureza_operacao_servico = ($linha['cl_natureza_operacao_servico']);
    $intermediario_id = ($linha['cl_intermediario_id']);


    $valor_total_nota = $valor_total_produtos + $icms_sub_nota + $ipi_nota + $valor_frete + $outras_despesas + $valor_seguro - $desconto_nota;



    /*itens */
    $select = "SELECT * from tb_nf_saida_item where cl_codigo_nf = '$codigo_nf'";
    $consultar_nf_item = mysqli_query($conecta, $select);



    /*cliente obrigatorio */
    $select = "SELECT * from tb_parceiros where cl_id = $parceiro_id";
    $consultar_destinatario = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consultar_destinatario);
    $razao_social_dest = utf8_encode($linha['cl_razao_social']);
    $nome_fantasia_dest = utf8_encode($linha['cl_nome_fantasia']);
    $cpfcnpj_dest = $linha['cl_cnpj_cpf'];
    $inscricao_estadual_dest = $linha['cl_inscricao_estadual'];

    if (identifyCpfOrCnpj($cpfcnpj_dest) == "0" or identifyCpfOrCnpj($cpfcnpj_dest) == "-1") { //funcao para verificar se o cliente é cpf ou cnpj //0-cpf 1-cnpj
        $cpf_dest = $cpfcnpj_dest;
        $cnpj_dest = "";
        $inscricao_estadual_dest = "";
    } elseif (identifyCpfOrCnpj($cpfcnpj_dest) == "1") { //cnpj
        $cpf_dest = "";
        $cnpj_dest = $cpfcnpj_dest;
        $inscricao_estadual_dest = $inscricao_estadual_dest;
    }

    $endereco_dest = utf8_encode($linha['cl_endereco']);
    $cidade_id_dest = ($linha['cl_cidade_id']);
    $cidade_dest = utf8_encode(consulta_tabela($conecta, "tb_cidades", "cl_id", $cidade_id_dest, 'cl_nome'));
    $codigo_municipio_destinatario = (consulta_tabela($conecta, "tb_cidades", "cl_id", $cidade_id_dest, 'cl_ibge'));
    $cep_dest = $linha['cl_cep'];
    $bairro_dest = utf8_encode($linha['cl_bairro']);
    $numero_dest = utf8_encode($linha['cl_numero']);
    $numero_dest = $numero_dest != "" ? $numero_dest : 'SN';

    $estado_id_dest = $linha['cl_estado_id'];
    $estado_dest = consulta_tabela($conecta, "tb_estados", "cl_id", $estado_id_dest, 'cl_uf');
    $aliq_interna_dest = consulta_tabela($conecta, "tb_estados", "cl_id", $estado_id_dest, 'cl_aliq'); //aliq interna do parceiro
    $telefone_dest = $linha['cl_telefone'];
    $email_dest = $linha['cl_email'];




    /*emitente*/
    $select = "SELECT * FROM tb_empresa where cl_id = '1' ";
    $consultar_emitente = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consultar_emitente);
    $razao_social_emit = utf8_encode($linha['cl_razao_social']);
    $nome_fantasia_emit = utf8_encode($linha['cl_nome_fantasia']);
    $inscricao_estadual_emit = ($linha['cL_inscricao_estadual']);
    $inscricao_municipal_emit = ($linha['cl_inscricao_municipal']);
    $cnpj_emit = ($linha['cl_cnpj']);
    $endereco_emit = utf8_encode($linha['cl_endereco']);
    $bairro_emit = utf8_encode($linha['cl_bairro']);
    $cidade_emit = utf8_encode($linha['cl_cidade']);
    $estado_emit = utf8_encode($linha['cl_estado']); //descricao
    $numero_emit = ($linha['cl_numero']); //descricao
    $cep_emit = ($linha['cl_cep']); //descricao
    $email_emit = ($linha['cl_email']);
    $telefone_emit = ($linha['cl_telefone']);
    $codigo_municipio = $linha['cl_codigo_municipio']; //ibge da cidade
    $codigo_cnae = $linha['cl_cnae']; //ibge da cidade



    /*transporadora */
    if ($transportadora_id != "") { //verificar se tem transportadora 
        $select = "SELECT * from tb_parceiros where cl_id = $transportadora_id";
        $consultar_transportadora = mysqli_query($conecta, $select);
        $linha = mysqli_fetch_assoc($consultar_transportadora);
        $razao_social_trans = utf8_encode($linha['cl_razao_social']);
        $cpfcnpj_trans = $linha['cl_cnpj_cpf'];
        $inscricao_estadual_trans = $linha['cl_inscricao_estadual'];
        $endereco_trans = utf8_encode($linha['cl_endereco']);
        $cidade_id_trans = ($linha['cl_cidade_id']);
        $cidade_trans = utf8_encode(consulta_tabela($conecta, "tb_cidades", "cl_id", $cidade_id_trans, 'cl_nome'));

        $cep_trans = $linha['cl_cep'];
        $bairro_trans = utf8_encode($linha['cl_bairro']);
        $numero_trans = utf8_encode($linha['cl_numero']);
        $estado_id_trans = $linha['cl_estado_id'];
        $estado_trans = consulta_tabela($conecta, "tb_estados", "cl_id", $estado_id_trans, 'cl_uf');

        $telefone_trans = $linha['cl_telefone'];
        $placa_trans = $placa;
        $uf_veiculo_trans = $uf_veiculo;

        $quantidade_vl = $quantidade_tra;
        $especie_vl = $especie_tra;
        $peso_bruto_vl = $peso_bruto;
        $peso_liquido_vl = $peso_liquido;
    } else {
        $quantidade_vl = "";
        $especie_vl = "";
        $peso_bruto_vl = "";
        $peso_liquido_vl = "";

        $cpfcnpj_trans = "";
        $razao_social_trans = "";
        $inscricao_estadual_trans = "";
        $endereco_trans = "";
        $cidade_trans = "";
        $estado_trans = "";
        $placa_trans = "";
        $uf_veiculo_trans = "";
    }

    $ref = $serie_nf . $cnpj_emit . $numero_nf; //numero da nf


    // $retornar["dados"] = array("sucesso" => true, "title" => "Nota alterada com sucesso");

    if ($serie_nf == "NFE") {
        if ($sub_acao == "enviar_nf") { //enviar nfe
            $nfe = array(
                "natureza_operacao" => "$descricao_cfop",
                "data_emissao" => $data,
                "data_entrada_saida" => $data,
                "tipo_documento" => $tipo_documento,
                "finalidade_emissao" => $finalidade_id,
                "serie" => "1",
                "numero" => $numero_nf,
                "cnpj_emitente" => "$cnpj_emit",
                "nome_emitente" => "$razao_social_emit",
                "nome_fantasia_emitente" => "$nome_fantasia_emit",
                "logradouro_emitente" => "$endereco_emit",
                "numero_emitente" => "$numero_emit",
                "bairro_emitente" => "$bairro_emit",
                "municipio_emitente" => "$cidade_emit",
                "uf_emitente" => "$estado_emit",
                "cep_emitente" => "$cep_emit",
                "telefone_emitente" => "$telefone_emit",

                "inscricao_estadual_emitente" => "$inscricao_estadual_emit",
                "nome_destinatario" => "$razao_social_dest",
                "cpf_destinatario" => $cpf_dest,
                "cnpj_destinatario" => $cnpj_dest,
                "inscricao_estadual_destinatario" => $inscricao_estadual_dest,
                // "indicador_inscricao_estadual_destinatario" => "$indicado_inscricao",
                "telefone_destinatario" => "$telefone_dest",

                "logradouro_destinatario" => "$endereco_dest",
                "numero_destinatario" => $numero_dest,
                "bairro_destinatario" => "$bairro_dest",
                "municipio_destinatario" => "$cidade_dest",
                "uf_destinatario" => "$estado_dest",
                "pais_destinatario" => "Brasil",
                "cep_destinatario" => "$cep_dest",
                //"valor_seguro" => "0",
                //"valor_outras_despesas"=>"$outras_despesas",
                "valor_total" => "$valor_total_nota",
                "valor_produtos" => "$valor_total_produtos",
                "modalidade_frete" => "$frete",

                "informacoes_adicionais_contribuinte" => "$observacao",
                "pedido_compra" => $numero_pedido,
                "nome_transportador" => "$razao_social_trans",
                "cnpj_transportador" => "$cpfcnpj_trans",
                "inscricao_estadual_transportador" => "$inscricao_estadual_trans",
                "endereco_transportador" => "$endereco_trans",
                "municipio_transportador" => "$cidade_trans",
                "uf_transportador" => "$estado_trans",
                "veiculo_placa" => "$placa_trans",
                "veiculo_uf" => "$uf_veiculo_trans",

                "items" => array(),
                "volumes" => array(),
                "duplicatas" => array(),
                "formas_pagamento" => array(),
                "notas_referenciadas" => array(),

            );


            /*duplicatas */
            $select = "SELECT lcf.* from tb_lancamento_financeiro as lcf inner join tb_forma_pagamento as fpg on fpg.cl_id = lcf.cl_forma_pagamento_id 
                     where lcf.cl_codigo_nf = '$codigo_nf' and fpg.cl_tipo_pagamento_id ='3'"; //apenas as forma de pagamento que tiver o tipo faturado
            $consultar_duplicatas = mysqli_query($conecta, $select);
            $qtd_duplicatas = mysqli_num_rows($consultar_duplicatas);
            $indicador_pagamento = 0; //a vista
            if ($qtd_duplicatas > 0 and $visualizar_duplicata == 1) {
                $numero_duplicata = 0;
                $duplicatas = array(); // Crie um array para armazenar as duplicatas
                $indicador_pagamento = 1; //a prazo
                while ($linha = mysqli_fetch_assoc($consultar_duplicatas)) {
                    $numero_duplicata = $numero_duplicata + 1;
                    $data_vencimento = $linha['cl_data_vencimento'];
                    $valor_liquido = $linha['cl_valor_liquido'];

                    // Adicione os campos da duplicata ao array $duplicata
                    $duplicata = array(
                        "numero" => $numero_duplicata,
                        "data_vencimento" => $data_vencimento,
                        "valor" => $valor_liquido,
                        // Adicione outros campos da duplicata, se houver
                    );
                    array_push($nfe["duplicatas"], $duplicata);
                    // // Adicione o array da duplicata ao array de duplicatas
                    // $duplicatas[] = $duplicata;
                }
            }


            /*volume transportadora */
            $volume_trans = array(
                "quantidade" => "$quantidade_vl",
                "especie" => "$especie_tra",
                "peso_liquido" => "$peso_liquido_vl",
                "peso_bruto" => "$peso_bruto_vl",
            );
            array_push($nfe["volumes"], $volume_trans);

            /*devolucao */
            $nf_referenciada = array(
                "chave_nfe" => "$chave_acesso_ref",
            );


            if ($chave_acesso_ref != "") { //finaljidade para estorno ou devolução sao necessario referenciar a chave de acesso
                array_push($nfe["notas_referenciadas"], $nf_referenciada);
                $fpgmt = array(
                    "forma_pagamento" => "90", //sem pagamento
                );
            } else {
                $fpgmt = array(
                    "indicador_pagamento" => "$indicador_pagamento",
                    "forma_pagamento" => "$tipo_forma_pagamento",
                    "valor_pagamento" => "$valor_total_nota",
                );
            }

            array_push($nfe["formas_pagamento"], $fpgmt);
            $qtd_prod = mysqli_num_rows($consultar_nf_item);

            if ($valor_frete > 0 and $valor_frete != "") {
                $valor_frete_item = $valor_frete / $qtd_prod;
            } else {
                $valor_frete_item = "0";
            }

            if ($outras_despesas > 0 and  $outras_despesas != "") {
                $outras_despesas_item = $outras_despesas / $qtd_prod;
            } else {
                $outras_despesas_item = "0";
            }

            if ($desconto_nota > 0 and  $desconto_nota != "") {
                $desconto_item = floor($desconto_nota / $qtd_prod * 100) / 100; // Arredonda para duas casas decimais para baixo
            } else {
                $desconto_item = "0";
            }

            if ($valor_seguro > 0 and  $valor_seguro != "") {
                $valor_seguro_item = $valor_seguro / $qtd_prod;
            } else {
                $valor_seguro_item = "0";
            }



            $item_nf = 0;
            // Calcule a diferença entre o desconto total na nota e o total de desconto nos itens
            $diferenca_desconto = round($desconto_nota - ($desconto_item * $qtd_prod), 2);
            $verificacao_realizada_desconto = false; // Inicialmente, a verificação não foi realizada

            while ($linha = mysqli_fetch_assoc($consultar_nf_item)) {
                //   $item_nf = $linha['item'];
                $item_nf = $item_nf + 1;
                $id_produto = ($linha['cl_item_id']);
                $descricao = utf8_encode($linha['cl_descricao_item']);
                $und = utf8_encode($linha['cl_unidade']);
                $quantidade = ($linha['cl_quantidade']);
                $quantidade = abs($quantidade);     // Transformando em positivo quando se tratar de estorno ou devoluçao

                $valor_unitario = ($linha['cl_valor_unitario']);
                $valor_produto = $quantidade * $valor_unitario;


                $ncm = ($linha['cl_ncm']);
                $cest = ($linha['cl_cest']);
                $cfop_item = ($linha['cl_cfop']);
                $cst = ($linha['cl_cst']);
                $bc_icms = ($linha['cl_base_icms']);
                $aliq_icms = ($linha['cl_aliq_icms']);
                $valor_icms = ($linha['cl_icms']);
                $base_icms_sub = ($linha['cl_base_icms_sbt']);
                $icms_sub = ($linha['cl_icms_sbt']);
                $aliq_ipi = ($linha['cl_aliq_ipi']);
                $valor_ipi = ($linha['cl_ipi']);
                $ipi_devolvido = ($linha['cl_ipi_devolvido']);
                $base_pis = ($linha['cl_base_pis']);
                $valor_pis = ($linha['cl_pis']);
                $cst_pis = ($linha['cl_cst_pis']);
                $base_cofins = ($linha['cl_base_cofins']);
                $valor_cofins = ($linha['cl_cofins']);
                $cst_cofins = ($linha['cl_cst_cofins']);
                $base_iss = ($linha['cl_base_iss']);
                $valor_iss = ($linha['cl_iss']);
                $gtin = $linha['cl_gtin'];
                $numero_pedido_item = $linha['cl_numero_pedido']; //numero do pedido de compra
                $numero_item_pedido = $linha['cl_item_pedido']; //numero do item

                if ($diferenca_desconto < $valor_produto and ($verificacao_realizada_desconto == false)) { //adicionar ao desconto em um item a diferencia para corrir o desconto total
                    $verificacao_realizada_desconto = true;
                    $item_nf_verificado = $item_nf;
                    $desconto_item = $desconto_item + $diferenca_desconto;
                }
                if ($sub_acao == "preview_nf") {
                    $ncm = empty($ncm) ? '00000000' : $ncm;
                }

                $aliq_cofins = ($base_cofins != 0) ? ($valor_cofins * 100) / $base_cofins : 0; //aligq cofins
                $aliq_pis = ($base_pis != 0) ? ($valor_pis * 100) / $base_pis : 0; //aliq pis


                if (in_array($cst, ['102', '900'])) {
                    $icms_origem = '0';
                } elseif (strlen($cst) == 3) {
                    $icms_origem = substr($cst, 0, 1);
                } else {
                    $icms_origem = '0';
                }
                $aliq_ipi = $aliq_ipi != "" ? $aliq_ipi : 0;
                $base_ipi = $valor_ipi > 0 ? $valor_produto : 0;
                // if ($desconto_nota != 0 and $desconto_nota <= $valor_produto and $valida_desconto == false) {//primeiro produto que suportar o dessconto sera atriuido o desconto total a ele
                //     $desconto_item = $desconto_nota;
                //     $valida_desconto = true;
                // } else {
                //     $desconto_item = 0;
                // }

                $item = array(
                    "numero_item" => "$item_nf",
                    "codigo_produto" => "$id_produto",
                    "descricao" => "$descricao",
                    "cfop" => "$cfop_item",
                    "unidade_comercial" => "$und",
                    "quantidade_comercial" => "$quantidade",
                    "valor_unitario_comercial" => "$valor_unitario",
                    "valor_unitario_tributavel" => "$valor_unitario",
                    "unidade_tributavel" => "$und",
                    "codigo_ncm" => "$ncm",
                    "cest" => "$cest",
                    "quantidade_tributavel" => "$quantidade",
                    "valor_bruto" => "$valor_produto",

                    "valor_outras_despesas" => "$outras_despesas_item",
                    "valor_frete" => "$valor_frete_item",
                    "valor_desconto" => "$desconto_item",
                    "valor_seguro" => "$valor_seguro_item",
                    "codigo_barras_comercial" => $gtin,
                    "codigo_barras_tributavel" => $gtin,
                    "pedido_compra" => "$numero_pedido_item",
                    "numero_item_pedido_compra" => "$numero_item_pedido",

                    "icms_modalidade_base_calculo" => "3",
                    "icms_modalidade_base_calculo_st" => "3",


                    "icms_situacao_tributaria" => "$cst",
                    "icms_origem" => "$icms_origem",
                    "icms_base_calculo" => "$bc_icms",
                    "icms_valor" => "$valor_icms",
                    "icms_aliquota" => "$aliq_icms",
                    "icms_valor_total_st" => "$icms_sub",
                    "icms_base_calculo_st" => "$base_icms_sub",

                    "ipi_base_calculo" => $base_ipi,
                    "ipi_aliquota" => $aliq_ipi,
                    "ipi_valor" => $valor_ipi,
                    "valor_ipi_devolvido" => $ipi_devolvido,

                    "ipi_situacao_tributaria" => "50", //saída tributada
                    "ipi_quantidade_selo_controle" => "1",
                    "ipi_codigo_enquadramento_legal" => "101",
                    "ipi_codigo_selo_controle" => "9710-01", // Produto Nacional

                    "pis_base_calculo" => "$base_pis",
                    "pis_valor" => "$valor_pis",
                    "pis_situacao_tributaria" => "$cst_pis",
                    "pis_aliquota_porcentual" => "$aliq_pis",

                    "cofins_base_calculo" => "$base_cofins",
                    "cofins_valor" => "$valor_cofins",
                    "cofins_situacao_tributaria" => "$cst_cofins",
                    "cofins_aliquota_porcentual" => "$aliq_cofins",

                    // "issqn_base_calculo" => "$base_iss",
                    // "issqn_valor" => "$valor_iss",
                    // "issqn_codigo_municipio" => "1400605",
                );

                if ((empty($inscricao_estadual_dest)) && substr($cfop, 0, 1) == '6') { //enviar informações caso o cliente seja fora do estado e não tenha inscrição estadual independente se é jurido ou fisico, adicionar infomrações do grupo do icms
                    $item['icms_base_calculo_uf_destino'] = $bc_icms;
                    $item['fcp_base_calculo_uf_destino'] = $bc_icms;
                    $item['fcp_percentual_uf_destino'] = 0;
                    $item['icms_aliquota_interna_uf_destino'] = "$aliq_interna_dest";
                    $item['icms_aliquota_interestadual'] = "12";
                    $item['fcp_base_calculo_retido_st'] = "$base_icms_sub";
                    $item['fcp_valor_retido_st'] = "$icms_sub";
                    $item['icms_percentual_partilha'] = "100";
                    $item['fcp_valor_uf_destino'] = "0";
                    $item['icms_valor_uf_destino'] = "0";
                    $item['icms_valor_uf_remetente'] = "0";
                }

                $desconto_item = floor($desconto_nota / $qtd_prod * 100) / 100; // Arredonda para duas casas decimais para baixo

                array_push($nfe["items"], $item);
            }

            $tentativas = 0;
            $max_tentativas = 7; // Número máximo de tentativas para a consulta
            $intervalo_tentativas = 10; // Intervalo entre tentativas (em segundos)

            if ($sub_acao == "enviar_nf") {
                // Primeira requisição: enviar a nota fiscal
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_URL, $server . "/v2/nfe?ref=" . $ref);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($nfe));
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
                $body = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                // Retorno da primeira requisição
                $txtretorno = $http_code . $body;

                if ($http_code == 422 || $http_code == 415) {
                    // Se houver erro nos códigos 422 ou 415, não precisa tentar mais.
                    $mensagem = utf8_decode("Erro ao enviar a nota $serie_nf$numero_nf via script $http_code"); //registrar no log o evento de envio
                    registrar_log($conecta, "Loja", $data, $mensagem);
                    return array("status" => false);
                }

                // Aguardar antes de continuar com a próxima requisição
                while ($tentativas < $max_tentativas) {
                    sleep($intervalo_tentativas);

                    // Segunda Requisição: Consultar o status da nota fiscal
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_URL, $server . "/v2/nfe/" . $ref . "?completa=1");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                    curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
                    $body = curl_exec($ch);
                    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    $txtretorno = $http_code . $body;

                    $response = json_decode($body, true);

                    if (isset($response['mensagem'])) {
                        $mensagem = $response['mensagem'];
                    } else {
                        $mensagem = "Operação cancelada, favor, verifique se todas as informações estão corretas";
                    }

                    // Verifica se o status existe na resposta
                    if (isset($response['status'])) {
                        $status = $response['status'];

                        // Erro de autorização
                        if ($status == "erro_autorizacao") {
                            $mensagem_sefaz = isset($response['mensagem_sefaz']) ? $response['mensagem_sefaz'] : $mensagem;
                            $requisicao = isset($response['requisicao_nota_fiscal']) ? $response['requisicao_nota_fiscal'] : '';

                            // Coletar a chave de acesso
                            if (!empty($requisicao)) {
                                $chaveAcesso = $requisicao['chave_nfe'];
                                $chaveAcesso = substr($chaveAcesso, 3);

                                // Atualizar registro da chave de acesso na tabela
                                $existe_chave_acesso = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id_nf, "cl_chave_acesso");
                                if ($existe_chave_acesso == "") {
                                    update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_chave_acesso", $chaveAcesso);
                                }
                            } else {
                                $chaveAcesso = '';
                            }

                            $mensagem = utf8_decode("Erro ao enviar a nota $serie_nf$numero_nf via script - mensagem: $mensagem_sefaz"); //registrar no log o evento de envio
                            registrar_log($conecta, "Loja", $data, $mensagem);

                            return array("status" => false, "mensagem" => "O Enviou da nota $serie_nf$numero_nf falhou");
                        } elseif ($status == "autorizado") {
                            // Nota validada
                            $mensagem_sefaz = $response['mensagem_sefaz'];
                            $chaveAcesso = $response['chave_nfe'];
                            $chaveAcesso = substr($chaveAcesso, 3);
                            $nprotocolo = $response['protocolo'];
                            $caminho_xml_nota_fiscal = $response['caminho_xml_nota_fiscal'];
                            $caminho_danfe = $response['caminho_danfe'];
                            $requisicao = $response['requisicao_nota_fiscal'];
                            $data_emissao = DateTime::createFromFormat("Y-m-d\TH:i:sP", $requisicao['data_emissao'])->format("Y-m-d");

                            // Atualizar dados na tabela
                            $existe_numero_protocolo = consulta_tabela($conecta, "tb_nf_saida", "cl_id", $id_nf, "cl_numero_protocolo");
                            if ($existe_numero_protocolo == "") {
                                update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_numero_protocolo", $nprotocolo);
                            }

                            update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_data_emisao_nf", $data_emissao);
                            update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_chave_acesso", $chaveAcesso);
                            update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_caminho_xml_nf", $caminho_xml_nota_fiscal);
                            update_registro($conecta, "tb_nf_saida", "cl_id", $id_nf, "", "", "cl_pdf_nf", $caminho_danfe);

                            $mensagem = utf8_decode("Enviou a nota $serie_nf$numero_nf via script"); //registrar no log o evento de envio
                            registrar_log($conecta, "Loja", $data, $mensagem);

                            return array("status" => true, "mensagem" => "Enviou a nota $serie_nf$numero_nf com sucesso");
                        }
                    }
                    $tentativas++;
                }

                curl_close($ch);
            }
        }
    }
}
