<?php
if (isset($_GET['ajuste_historico'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $codigo_nf = isset($_GET['codigo_nf']) ? $_GET['codigo_nf'] : '';
    $codigo_ajuste = isset($_GET['codigo']) ? $_GET['codigo'] : '';
    $query = "SELECT prd.cl_id as prdid,ajst.cl_tipo as tipoajst, ajst.*,user.*,prd.*  FROM tb_ajuste_preco as ajst
    left join tb_produtos as prd on prd.cl_id = ajst.cl_produto_id
    left join tb_users as user on user.cl_id = ajst.cl_usuario_id
    where ajst.cl_codigo_nf = '$codigo_nf' order by ajst.cl_tipo, ajst.cl_unidade";
    $consultar = mysqli_query($conecta, $query);
    if (!$consultar) {
        die("Falha no banco de dados" . mysqli_error($conecta));
    } else {
        $qtd = mysqli_num_rows($consultar); //quantidade de registros
    }
}

if (isset($_GET['consultar_ajuste'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $consulta = $_GET['consultar_ajuste'];
    $data_inicial = $_GET['data_inicial'];
    $data_final = $_GET['data_final'];

    $data_inicial = ($data_inicial . ' 01:01:01');
    $data_final = ($data_final . ' 23:59:59');

    if ($consulta == "inicial") {
        $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1
        $query = "SELECT count(*) as qtd,ajst.*,user.*  FROM tb_ajuste_preco as ajst
         left join tb_produtos as prd on prd.cl_id = ajst.cl_produto_id
         left join tb_users as user on user.cl_id = ajst.cl_usuario_id

         where ajst.cl_data between '$data_inicial' and '$data_final' group by ajst.cl_codigo_nf order by ajst.cl_data desc ";
        $consultar = mysqli_query($conecta, $query);
        if (!$consultar) {
            die("Falha no banco de dados" . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar); //quantidade de registros
        }
    } else {
        $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
        $usuario_id = ($_GET['usuario_id']); //filtro

        $query = "SELECT count(*) as qtd,ajst.*,user.*  FROM tb_ajuste_preco as ajst
        left join tb_produtos as prd on prd.cl_id = ajst.cl_produto_id
        left join tb_users as user on user.cl_id = ajst.cl_usuario_id
        where ajst.cl_data between '$data_inicial' and '$data_final' and (prd.cl_descricao like '%{$pesquisa}%' or
         prd.cl_id  like '%{$pesquisa}%' or ajst.cl_documento  like '%{$pesquisa}%') ";
        if ($usuario_id != "0") {
            $query .= " and cl_usuario_id = '$usuario_id' ";
        }
        $query .= " group by ajst.cl_codigo_nf order by ajst.cl_data desc ";
        $consultar = mysqli_query($conecta, $query);
        if (!$consultar) {
            die("Falha no banco de dados" . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar);
        }
    }
}

// //cadastrar formulario
if (isset($_POST['formulario_ajuste_preco'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $acao = $_POST['acao'];
    $usuario_id = verifica_sessao_usuario();
    $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");
    $descricao_serie_ajuste_estoque = consulta_tabela($conecta, 'tb_serie', 'cl_id', 16, 'cl_descricao'); //serie destinado ao ajuste de preço
    $serie_ajuste_preco = consulta_tabela($conecta, 'tb_serie', 'cl_id', 16, 'cl_valor'); //serie destinado ao ajuste de preço
    $erro = null;
    $msg = null;
    $qtd_ajuste = 0;

    if ($acao == "create") { // create
        mysqli_begin_transaction($conecta);

        $qtd_registro = $_POST['qt_registro'];
        $codigo_nf = md5(uniqid(time())); //gerar um novo codigo nf
        $codigo_ajuste = $serie_ajuste_preco + 1;

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples

        }

        for ($i = 1; $i <= $qtd_registro; $i++) {
            if (isset($_POST["produto_id_$i"])) {
                $produto_id = $_POST["produto_id_$i"];
                if (isset($_POST["valor_$produto_id"])) {
                    $valor = $_POST["valor_$produto_id"];
                    $forma_ajuste = $_POST["forma_ajuste_$produto_id"];
                    $tipo_ajuste = $_POST["tipo_ajuste_$produto_id"];
                    $tipo_modificacao = $_POST["tipo_modificacao_$produto_id"];
                    if ($valor > 0) {
                        if ($forma_ajuste == "0" or $tipo_ajuste == "0" or $tipo_modificacao == "0") {
                            $msg = "informe o tipo de ajuste, a unidade do ajuste e o tipo de modificação para o produto com o código $produto_id";
                            break;
                        } else {
                            $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_produtos where cl_id ='$produto_id'");
                            if ($resultados) {
                                foreach ($resultados as $linha) {
                                    $preco_venda = ($linha['cl_preco_venda']);
                                    $preco_custo = ($linha['cl_preco_custo']);
                                }

                                if ($tipo_ajuste === 'venda') {
                                    $preco_antigo = $preco_venda;
                                } else if ($tipo_ajuste === 'custo') {
                                    $preco_antigo = $preco_custo;
                                }

                                // Cálculo do novo valor com base no tipo de modificação
                                if ($tipo_modificacao === 'total') {
                                    $novoValor = $valor;
                                } else if ($tipo_modificacao === 'acrescimo') {
                                    if ($forma_ajuste === 'moeda') {
                                        $novoValor = $preco_antigo + $valor;
                                    } else if ($forma_ajuste === 'percent') {
                                        $novoValor = $preco_antigo + ($preco_antigo * $valor / 100);
                                    }
                                } else if ($tipo_modificacao === 'decrescimo') {
                                    if ($forma_ajuste === 'moeda') {
                                        $novoValor = $preco_antigo - $valor;
                                    } else if ($forma_ajuste === 'percent') {
                                        $novoValor = $preco_antigo - ($preco_antigo * $valor / 100);
                                    }
                                }
                                $qtd_ajuste++;
                                $documento = "$descricao_serie_ajuste_estoque-$codigo_ajuste";
                                $dados[] = [
                                    'documento' => $documento,
                                    'produto_id' => $produto_id,
                                    'codigo_nf' => $codigo_nf,
                                    'tipo' => $tipo_ajuste,
                                    'unidade' => $forma_ajuste,
                                    'valor_antigo' => $preco_antigo,
                                    'valor' => $novoValor,
                                    'usuario_id' => $usuario_id
                                ];

                                $ajuste = ajuste_preco($conecta, $dados); //realizar o ajuste
                                if ($ajuste !== true) { //operação retornou erro
                                    $erro = $ajuste;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($qtd_registro == 0) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Para realizar os ajuste,
             é necessario realizar a busca dos produtos e informar os valores");
        } elseif ($msg != null) {
            $retornar["dados"] = array("sucesso" => false, "title" => $msg);
        } elseif ($qtd_ajuste == 0) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Favor, informe os valores");
        } else {
            if ($erro == null) {
                $retornar["dados"] =  array("sucesso" => true, "title" => "Ajuste nº $codigo_ajuste realizado com sucesso");
                update_registro($conecta, 'tb_serie', 'cl_id', 16, '', '', 'cl_valor', $codigo_ajuste);
                $mensagem = utf8_decode("Realizou o ajuste de preço $codigo_ajuste");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de realizar o ajuste de preço, " . str_replace("'", "", $erro));
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }
    if ($acao == "create_lote") { // create
        mysqli_begin_transaction($conecta);

        $qtd_registro = $_POST['qt_registro'];
        $codigo_nf = md5(uniqid(time())); //gerar um novo codigo nf
        $codigo_ajuste = $serie_ajuste_preco + 1;

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples

        }
        $forma_ajuste = $_POST["forma_ajuste"];
        $tipo_ajuste = $_POST["tipo_ajuste"];
        $valor = $_POST["valor_ajuste"];

        if ($tipo_ajuste == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("tipo"));
            echo json_encode($retornar);
            exit;
        } elseif (empty($valor)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("valor"));
            echo json_encode($retornar);
            exit;
        } elseif ($tipo_modificacao == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("tipo de modificação"));
            echo json_encode($retornar);
            exit;
        }
        for ($i = 1; $i <= $qtd_registro; $i++) {
            if (isset($_POST["produto_id_$i"])) {
                $produto_id = $_POST["produto_id_$i"];
                if (isset($_POST["check_$i"])) { //
                    $qtd_ajuste++;
                    $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_produtos where cl_id ='$produto_id'");
                    if ($resultados) {
                        foreach ($resultados as $linha) {
                            $preco_venda = ($linha['cl_preco_venda']);
                            $preco_custo = ($linha['cl_preco_custo']);
                        }

                        if ($tipo_ajuste === 'venda') {
                            $preco_antigo = $preco_venda;
                        } else if ($tipo_ajuste === 'custo') {
                            $preco_antigo = $preco_custo;
                        }

                        // Cálculo do novo valor com base no tipo de modificação
                        if ($tipo_modificacao === 'total') {
                            $novoValor = $valor;
                        } else if ($tipo_modificacao === 'acrescimo') {
                            if ($forma_ajuste === 'moeda') {
                                $novoValor = $preco_antigo + $valor;
                            } else if ($forma_ajuste === 'percent') {
                                $novoValor = $preco_antigo + ($preco_antigo * $valor / 100);
                            }
                        } else if ($tipo_modificacao === 'decrescimo') {
                            if ($forma_ajuste === 'moeda') {
                                $novoValor = $preco_antigo - $valor;
                            } else if ($forma_ajuste === 'percent') {
                                $novoValor = $preco_antigo - ($preco_antigo * $valor / 100);
                            }
                        }
                        $documento = "$descricao_serie_ajuste_estoque-$codigo_ajuste";

                        $dados[] = [
                            'documento' => $documento,
                            'produto_id' => $produto_id,
                            'codigo_nf' => $codigo_nf,
                            'tipo' => $tipo_ajuste,
                            'unidade' => $forma_ajuste,
                            'valor_antigo' => $preco_antigo,
                            'valor' => $novoValor,
                            'usuario_id' => $usuario_id
                        ];

                        $ajuste = ajuste_preco($conecta, $dados); //realizar o ajuste
                        if ($ajuste !== true) { //operação retornou erro
                            $erro = $ajuste;
                            break;
                        }
                    }
                }
            }
        }

        if ($qtd_registro == 0) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Para realizar os ajuste,
         é necessario realizar a busca dos produtos e informar os valores");
        } elseif ($msg != null) {
            $retornar["dados"] = array("sucesso" => false, "title" => $msg);
        } elseif ($qtd_ajuste == 0) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Favor, selecione pelo menos um produto para realizar o ajuste.");
        } else {
            if ($erro == null) {
                $retornar["dados"] =  array("sucesso" => true, "title" => "Ajuste nº $codigo_ajuste realizado com sucesso");
                update_registro($conecta, 'tb_serie', 'cl_id', 16, '', '', 'cl_valor', $codigo_ajuste);
                $mensagem = utf8_decode("Realizou o ajuste de preço $codigo_ajuste");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de realizar o ajuste de preço, " . str_replace("'", "", $erro));
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }
    if ($acao == "create_promocao") { // create
        mysqli_begin_transaction($conecta);

        $qtd_registro = $_POST['qt_registro'];
        $codigo_nf = md5(uniqid(time())); //gerar um novo codigo nf
        $codigo_ajuste = $serie_ajuste_preco + 1;

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples

        }
        $forma_ajuste = $_POST["forma_ajuste"];
        $valor = $_POST["valor_ajuste"];

        if ($tipo_modificacao == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("tipo de modificação"));
            echo json_encode($retornar);
            exit;
        } elseif (empty($valor)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("valor"));
            echo json_encode($retornar);
            exit;
        } 


        for ($i = 1; $i <= $qtd_registro; $i++) {
            if (isset($_POST["produto_id_$i"])) {
                $produto_id = $_POST["produto_id_$i"];
                if (isset($_POST["check_$i"])) { //
                    $qtd_ajuste++;
                    $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_produtos where cl_id ='$produto_id'");
                    if ($resultados) {
                        foreach ($resultados as $linha) {
                            $preco_venda = ($linha['cl_preco_venda']);
                        }

                        $preco_antigo = $preco_venda;


                        // Cálculo do novo valor com base no tipo de modificação
                        if ($tipo_modificacao === 'total') {
                            $novoValor = $valor;
                        } else if ($tipo_modificacao === 'acrescimo') {
                            if ($forma_ajuste === 'moeda') {
                                $novoValor = $preco_antigo + $valor;
                            } else if ($forma_ajuste === 'percent') {
                                $novoValor = $preco_antigo + ($preco_antigo * $valor / 100);
                            }
                        } else if ($tipo_modificacao === 'decrescimo') {
                            if ($forma_ajuste === 'moeda') {
                                $novoValor = $preco_antigo - $valor;
                            } else if ($forma_ajuste === 'percent') {
                                $novoValor = $preco_antigo - ($preco_antigo * $valor / 100);
                            }
                        }
                        $documento = "$descricao_serie_ajuste_estoque-$codigo_ajuste";

                        $dados[] = [
                            'documento' => $documento,
                            'produto_id' => $produto_id,
                            'codigo_nf' => $codigo_nf,
                            'tipo' => "promocao",
                            'unidade' => $forma_ajuste,
                            'valor_antigo' => $preco_antigo,
                            'valor' => $novoValor,
                            'usuario_id' => $usuario_id,
                            'data_promocao' => $duracao_promocao,
                        ];

                        $ajuste = ajuste_preco($conecta, $dados); //realizar o ajuste
                        if ($ajuste !== true) { //operação retornou erro
                            $erro = $ajuste;
                            break;
                        }
                    }
                }
            }
        }

        if ($qtd_registro == 0) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Para realizar os ajuste,
         é necessario realizar a busca dos produtos e informar os valores");
        } elseif ($msg != null) {
            $retornar["dados"] = array("sucesso" => false, "title" => $msg);
        } elseif ($qtd_ajuste == 0) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Favor, selecione pelo menos um produto para realizar o ajuste.");
        } else {
            if ($erro == null) {
                $retornar["dados"] =  array("sucesso" => true, "title" => "Ajuste nº $codigo_ajuste realizado com sucesso");
                update_registro($conecta, 'tb_serie', 'cl_id', 16, '', '', 'cl_valor', $codigo_ajuste);
                $mensagem = utf8_decode("Realizou o ajuste de preço $codigo_ajuste");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de realizar o ajuste de preço, " . str_replace("'", "", $erro));
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }
    echo json_encode($retornar);
}
