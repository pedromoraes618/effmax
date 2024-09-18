<?php
if (isset($_GET['ajuste_historico'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $codigo_nf = isset($_GET['codigo_nf']) ? $_GET['codigo_nf'] : '';
    $codigo_ajuste = isset($_GET['codigo']) ? $_GET['codigo'] : '';
    $query = "SELECT prd.cl_id as prdid,ajst.cl_tipo as tipoajst, ajst.*,user.*,prd.*  FROM tb_ajuste_estoque as ajst
    left join tb_produtos as prd on prd.cl_id = ajst.cl_produto_id
    left join tb_users as user on user.cl_id = ajst.cl_usuario_id
    where ajst.cl_codigo_nf = '$codigo_nf' order by ajst.cl_tipo";
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
        $query = "SELECT count(*) as qtd,ajst.*,user.*  FROM tb_ajuste_estoque as ajst
         left join tb_produtos as prd on prd.cl_id = ajst.cl_produto_id
         left join tb_users as user on user.cl_id = ajst.cl_usuario_id

         where ajst.cl_data_lancamento between '$data_inicial' and '$data_final' and cl_ajuste =1 group by ajst.cl_codigo_nf order by ajst.cl_id desc ";
        $consultar = mysqli_query($conecta, $query);
        if (!$consultar) {
            die("Falha no banco de dados" . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar); //quantidade de registros
        }
    } else {
        $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
        $usuario_id = ($_GET['usuario_id']); //filtro

        $query = "SELECT count(*) as qtd,ajst.*,user.*  FROM tb_ajuste_estoque as ajst
        left join tb_produtos as prd on prd.cl_id = ajst.cl_produto_id
        left join tb_users as user on user.cl_id = ajst.cl_usuario_id
        where ajst.cl_data_lancamento between '$data_inicial' and '$data_final' and cl_ajuste =1 and  (prd.cl_descricao like '%{$pesquisa}%' or
         prd.cl_id  like '%{$pesquisa}%' or ajst.cl_documento  like '%{$pesquisa}%') ";
        if ($usuario_id != "0") {
            $query .= " and ajst.cl_usuario_id = '$usuario_id' ";
        }
        $query .= " group by ajst.cl_codigo_nf order by ajst.cl_Id desc ";
        $consultar = mysqli_query($conecta, $query);
        if (!$consultar) {
            die("Falha no banco de dados" . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar);
        }
    }
}

// //cadastrar formulario
if (isset($_POST['formulario_ajuste_estoque'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $acao = $_POST['acao'];
    $usuario_id = verifica_sessao_usuario();
    $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario");
    $descricao_serie_ajuste_estoque = consulta_tabela($conecta, 'tb_serie', 'cl_id', 2, 'cl_descricao'); //descrição da serie destinado ao ajuste de preço
    $serie_ajuste_estoque = consulta_tabela($conecta, 'tb_serie', 'cl_id', 2, 'cl_valor'); //serie destinado ao ajuste de preço


    $max_min_estoque = consulta_tabela($conecta, 'tb_parametros', 'cl_id', 5, 'cl_valor'); //paramnetro para verificar se será possivel informar uma quantidade acima do estoque minimo ou maximo
    $erro = null;
    $msg = null;
    $qtd_ajuste = 0;

    if ($acao == "create") { // create
        mysqli_begin_transaction($conecta);

        $qtd_registro = $_POST['qt_registro'];
        $codigo_nf = md5(uniqid(time())); //gerar um novo codigo nf
        $codigo_ajuste = $serie_ajuste_estoque + 1;

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples

        }

        for ($i = 1; $i <= $qtd_registro; $i++) {
            if (isset($_POST["produto_id_$i"])) {
                $produto_id = $_POST["produto_id_$i"];
                if (isset($_POST["qtd_$produto_id"])) {

                    $tipo_ajuste = $_POST["tipo_ajuste_$produto_id"]; //definie o tipo do ajuste
                    $qtd = $_POST["qtd_$produto_id"]; //define a quantidade do ajuste

                    if ($qtd > 0) {
                        $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_produtos where cl_id ='$produto_id'");
                        if ($resultados) {
                            foreach ($resultados as $linha) {
                                $estoque_minimo = ($linha['cl_estoque_minimo']);
                                $estoque_maximo = ($linha['cl_estoque_maximo']);
                                $estoque_atual = ($linha['cl_estoque']);
                                $preco_venda = ($linha['cl_preco_venda']);
                                $preco_custo = ($linha['cl_preco_custo']);
                            }

                            if ($max_min_estoque == "S" and $estoque_maximo != 0 and $qtd > $estoque_maximo and $tipo_ajuste == "estoque") { //Bloquear se o estoque está acima do estoque maximo ou abaixo do estoque minimo
                                $msg = "A quantidade informada do produto com código $produto_id está acima do estoque máximo de $estoque_maximo";
                                break;
                            } elseif ($max_min_estoque == "S"  and $estoque_minimo != 0  and  $qtd < $estoque_minimo and $tipo_ajuste == "estoque") {
                                $msg = "A quantidade informada do produto com código $produto_id está abaixo do estoque mínimo de $estoque_minimo";
                                break;
                            } elseif ($tipo_ajuste == "estoque_minimo" and $max_min_estoque == "S"  and $qtd > $estoque_atual) {
                                $msg = "A quantidade informada para estoque minimo do produto com código $produto_id está acima do estoque atual de $estoque_atual";
                                break;
                            } elseif ($tipo_ajuste == "estoque_maximo" and $max_min_estoque == "S"  and $qtd < $estoque_atual) {
                                $msg = "A quantidade informada para estoque máximo do produto com código $produto_id está abaixo do estoque atual de $estoque_atual";
                                break;
                            }

                            if ($qtd >= $estoque_atual) {
                                $tipo = "ENTRADA";
                                $qtd_ajuste = $qtd - $estoque_atual;
                                $valor_entrada = $preco_custo;
                                $valor_saida = 0;
                            } else {
                                $tipo = "SAIDA";
                                $qtd_ajuste = $estoque_atual - $qtd;
                                $valor_saida = $preco_venda;
                                $valor_entrada = 0;
                            }

                            if ($tipo_ajuste == "estoque") { //atualizar o estoque
                                $dados[] = [
                                    'documento' => "$descricao_serie_ajuste_estoque-$codigo_ajuste",
                                    'produto_id' => $produto_id,
                                    'codigo_nf' => $codigo_nf,
                                    'tipo' => $tipo,
                                    'usuario_id' => $usuario_id,
                                    'qtd_ajustado' => $qtd_ajuste,
                                    'novo_estoque' => $qtd,
                                    'valor_entrada' => $valor_entrada,
                                    'valor_saida' => $valor_saida,
                                    'ajuste' => 1,
                                    'atualizar_qtd' => 'S',
                                ];

                                $ajuste = ajuste_estoque1($conecta, $dados); //realizar o ajuste
                                if ($ajuste !== true) { //operação retornou erro
                                    $erro = $ajuste;
                                    break;
                                }
                            } elseif ($tipo_ajuste == "estoque_minimo") { //atualizar o estoque minimo
                                update_registro($conecta, 'tb_produtos', 'cl_id', $produto_id, '', '', 'cl_estoque_minimo', $qtd);
                            } elseif ($tipo_ajuste == "estoque_maximo") { //atualizar o estoque maximo
                                update_registro($conecta, 'tb_produtos', 'cl_id', $produto_id, '', '', 'cl_estoque_maximo', $qtd);
                            }
                            $qtd_ajuste++;
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
                update_registro($conecta, 'tb_serie', 'cl_id', 2, '', '', 'cl_valor', $codigo_ajuste); //atualizar valor na serie
                $mensagem = utf8_decode("Realizou o ajuste de estoque $codigo_ajuste");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                // Se tudo ocorreu bem, confirme a transação
                mysqli_commit($conecta);
            } else {
                // Se ocorrer um erro, reverta a transação
                mysqli_rollback($conecta);
                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                $mensagem = utf8_decode("Tentativa sem sucesso de realizar o ajuste de estoque, " . str_replace("'", "", $erro));
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }
    if ($acao == "create_lote") { // create
        mysqli_begin_transaction($conecta);

        $qtd_registro = $_POST['qt_registro'];
        $codigo_nf = md5(uniqid(time())); //gerar um novo codigo nf
        $codigo_ajuste = $serie_ajuste_estoque + 1;

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples

        }


        if (empty($qtd)) {
            $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("quantidade"));
            echo json_encode($retornar);
            exit;
        }
        for ($i = 1; $i <= $qtd_registro; $i++) {
            if (isset($_POST["produto_id_$i"])) {
                $produto_id = $_POST["produto_id_$i"];
                if (isset($_POST["check_$i"])) { //
                    $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_produtos where cl_id ='$produto_id'");
                    if ($resultados) {
                        foreach ($resultados as $linha) {
                            $estoque_minimo = ($linha['cl_estoque_minimo']);
                            $estoque_maximo = ($linha['cl_estoque_maximo']);
                            $estoque_atual = ($linha['cl_estoque']);
                            $preco_venda = ($linha['cl_preco_venda']);
                            $preco_custo = ($linha['cl_preco_custo']);
                        }

                        if ($max_min_estoque == "S" and $estoque_maximo != 0 and $qtd > $estoque_maximo and $tipo_ajuste == "estoque") { //Bloquear se o estoque está acima do estoque maximo ou abaixo do estoque minimo
                            $msg = "A quantidade informada do produto com código $produto_id está acima do estoque máximo de $estoque_maximo";
                            break;
                        } elseif ($max_min_estoque == "S"  and $estoque_minimo != 0  and  $qtd < $estoque_minimo and $tipo_ajuste == "estoque") {
                            $msg = "A quantidade informada do produto com código $produto_id está abaixo do estoque mínimo de $estoque_minimo";
                            break;
                        } elseif ($tipo_ajuste == "estoque_minimo" and $max_min_estoque == "S"  and $qtd > $estoque_atual) {
                            $msg = "A quantidade informada para estoque minimo do produto com código $produto_id está acima do estoque atual de $estoque_atual";
                            break;
                        } elseif ($tipo_ajuste == "estoque_maximo" and $max_min_estoque == "S"  and $qtd < $estoque_atual) {
                            $msg = "A quantidade informada para estoque máximo do produto com código $produto_id está abaixo do estoque atual de $estoque_atual";
                            break;
                        }

                        if ($qtd >= $estoque_atual) {
                            $tipo = "ENTRADA";
                            $qtd_ajuste = $qtd - $estoque_atual;
                            $valor_entrada = $preco_custo;
                            $valor_saida = 0;
                        } else {
                            $tipo = "SAIDA";
                            $qtd_ajuste = $estoque_atual - $qtd;
                            $valor_saida = $preco_venda;
                            $valor_entrada = 0;
                        }

                        if ($tipo_ajuste == "estoque") { //atualizar o estoque
                            $dados[] = [
                                'documento' => "$descricao_serie_ajuste_estoque-$codigo_ajuste",
                                'produto_id' => $produto_id,
                                'codigo_nf' => $codigo_nf,
                                'tipo' => $tipo,
                                'usuario_id' => $usuario_id,
                                'qtd_ajustado' => $qtd_ajuste,
                                'novo_estoque' => $qtd,
                                'valor_entrada' => $valor_entrada,
                                'valor_saida' => $valor_saida,
                                'ajuste' => 1,
                                'atualizar_qtd' => 'S',
                            ];

                            $ajuste = ajuste_estoque1($conecta, $dados); //realizar o ajuste
                            if ($ajuste !== true) { //operação retornou erro
                                $erro = $ajuste;
                                break;
                            }
                        } elseif ($tipo_ajuste == "estoque_minimo") { //atualizar o estoque minimo
                            update_registro($conecta, 'tb_produtos', 'cl_id', $produto_id, '', '', 'cl_estoque_minimo', $qtd);
                        } elseif ($tipo_ajuste == "estoque_maximo") { //atualizar o estoque maximo
                            update_registro($conecta, 'tb_produtos', 'cl_id', $produto_id, '', '', 'cl_estoque_maximo', $qtd);
                        }
                        $qtd_ajuste++;
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
    if ($acao == "create_parametro") { // create

        $qtd_registro = $_POST['qt_registro'];
        $codigo_nf = md5(uniqid(time())); //gerar um novo codigo nf
        $codigo_ajuste = $serie_ajuste_estoque + 1;

        foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
            ${$name} = utf8_decode($value);
            ${$name} = str_replace("'", "", ${$name}); //remover aspas simples

        }

        if (empty($cest) and empty($ncm) and empty($cst) and ($subgrupo) == "0" and ($unidade_medida) == "0") {
            $retornar["dados"] = array("sucesso" => false, "title" => "Favor, informe os valores");
            echo json_encode($retornar);
            exit;
        }


        for ($i = 1; $i <= $qtd_registro; $i++) {
            if (isset($_POST["produto_id_$i"])) {
                $produto_id = $_POST["produto_id_$i"];
                if (isset($_POST["check_$i"])) { //
                    $qtd_ajuste++;

                    if (!empty($cst)) {
                        update_registro($conecta, 'tb_produtos', 'cl_id', $produto_id, '', '', 'cl_cst_icms', $cst);
                    }
                    if (!empty($cest)) {
                        update_registro($conecta, 'tb_produtos', 'cl_id', $produto_id, '', '', 'cl_cest', $cest);
                    }
                    if (!empty($ncm)) {
                        update_registro($conecta, 'tb_produtos', 'cl_id', $produto_id, '', '', 'cl_ncm', $ncm);
                    }
                    if ($subgrupo != "0") {
                        update_registro($conecta, 'tb_produtos', 'cl_id', $produto_id, '', '', 'cl_grupo_id', $subgrupo);
                    }
                    if ($unidade_medida != "0") {
                        update_registro($conecta, 'tb_produtos', 'cl_id', $produto_id, '', '', 'cl_und_id', $unidade_medida);
                    }
                }
            }
        }


        if ($qtd_registro == 0) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Para realizar os ajuste,
         é necessario realizar a busca dos produtos e informar os valores");
        } elseif ($qtd_ajuste == 0) {
            $retornar["dados"] = array("sucesso" => false, "title" => "Favor, selecione pelo menos um produto para realizar o ajuste.");
        } else {
            $retornar["dados"] =  array("sucesso" => true, "title" => "Ajuste realizado com sucesso");
            $mensagem = utf8_decode("Realizou o ajuste de parâmetros de produtos");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
        }
    }
    echo json_encode($retornar);
}
