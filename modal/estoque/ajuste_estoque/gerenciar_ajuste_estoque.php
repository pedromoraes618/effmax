<?php


if (isset($_GET['ajuste_estoque'])) { //tela para realizar o ajuste

    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $serie_ajust = verifcar_descricao_serie($conecta, "2");

    if (isset($_GET['codigo_nf'])) {
        $codigo_nf = $_GET['codigo_nf'];
        $numero_ajuste = $_GET['numero_ajuste'];
        $data_ajuste = $_GET['data_ajuste'];
    } else {
        $numero_ajuste = $serie_ajust . " - " . (consultar_serie($conecta, "2") + 1);
        $codigo_nf = "";
        $data_ajuste = $data_lancamento;
    }
}


if (isset($_GET['consultar_ajuste_estoque'])) {
    include "../../../funcao/funcao.php";
}


//consultar informações para tabela dos produtos no ajuste 
if (isset($_GET['consultar_ajst'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";

    $consulta = $_GET['consultar_ajst'];
    $data_inicial = $_GET['data_inicial'];
    $data_final = $_GET['data_final'];
    $serie_ajust = verifcar_descricao_serie($conecta, "2");
    //formatar data para o banco de dados
    // $data_inicial =  formatarDataParaBancoDeDados($data_inicial);
    // $data_final =  formatarDataParaBancoDeDados($data_final);


    // $codigo_nf = $_GET['codigo_nf'];
    if ($consulta == "inicial") {
        $consultar_tabela_inicialmente =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "1"); //VERIFICAR PARAMETRO ID - 1
        $select = "SELECT ajst.cl_documento, users.cl_usuario, ajst.cl_data_lancamento,ajst.cl_codigo_nf FROM tb_ajuste_estoque as ajst
         inner join tb_users as users on users.cl_id = ajst.cl_usuario_id WHERE cl_data_lancamento between '$data_inicial' and '$data_final' and 
         cl_codigo_nf !='' and ajst.cl_documento like '%$serie_ajust%' group by cl_codigo_nf order by ajst.cl_id desc";
        $consultar_ajuste_estoque = mysqli_query($conecta, $select);
        if (!$consultar_ajuste_estoque) {
            die("Falha no banco de dados" . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar_ajuste_estoque); //quantidade de registros
        }
    } else {
        $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
        $select = "SELECT ajst.cl_documento, users.cl_usuario, ajst.cl_data_lancamento,ajst.cl_codigo_nf 
        FROM tb_ajuste_estoque as ajst inner join tb_users as users on users.cl_id = 
        ajst.cl_usuario_id WHERE cl_data_lancamento between '$data_inicial' and '$data_final' and 
         cl_codigo_nf !='' and (cl_codigo_nf like '%{$pesquisa}%' or users.cl_usuario like '%{$pesquisa}%' ) and ajst.cl_documento like '%$serie_ajust%' group by cl_codigo_nf order by ajst.cl_id desc";
        $consultar_ajuste_estoque = mysqli_query($conecta, $select);
        if (!$consultar_ajuste_estoque) {
            die("Falha no banco de dados" . mysqli_error($conecta));
        } else {
            $qtd = mysqli_num_rows($consultar_ajuste_estoque);
        }
    }
}



//consultar informações para tabela
if (isset($_GET['consultar_ajst_produtos'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";
    $codigo_nf = $_GET['codigo_nf'];
    if (isset($_GET['itens'])) { //via localstorage
        $tabela = 'ANDAMENTO';
        $itens = json_decode($_GET['itens'], true); // Transforma a string JSON em um array
    } else {
        $tabela = 'FINALIZADO';
    }

    $select = "SELECT ajst.cl_status, ajst.cl_valor_venda,ajst.cl_valor_compra, ajst.cl_id as id_ajst, ajst.cl_motivo,ajst.cl_tipo, ajst.cl_quantidade, ajst.cl_produto_id as id_produto, prd.cl_descricao as produto,users.cl_usuario,ajst.cl_documento from 
    tb_ajuste_estoque as ajst inner join tb_produtos as prd on prd.cl_id = ajst.cl_produto_id inner join 
    tb_users as users on users.cl_id = ajst.cl_usuario_id  where ajst.cl_codigo_nf ='$codigo_nf' ";
    $consultar_ajst_produtos = mysqli_query($conecta, $select);
    if (!$consultar_ajst_produtos) {
        die("Falha no banco de dados: " . mysqli_error($conecta));
    } else {
        $qtd = mysqli_num_rows($consultar_ajst_produtos); //quantidade de registros
    }
}



//cadastrar formulario
if (isset($_POST['fomulario_ajuste_estoque'])) {
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $retornar = array();
    $acao = ($_POST["acao"]);

    $usuario_id = verifica_sessao_usuario();
    $nome_usuario_logado = (consulta_tabela($conecta, "tb_users", "cl_id", $usuario_id, "cl_usuario"));


    //verificar parametro cliente responsavel para ajuste de estoque
    $empresa_ajuste = verficar_paramentro($conecta, "tb_parametros", "cl_id", "3");

    $serie_ajust = verifcar_descricao_serie($conecta, "2");
    //verificar parametro formar de  pagamento usada no ajuste de estoque
    $forma_pagamento_ajuste = verficar_paramentro($conecta, "tb_parametros", "cl_id", "4");

    //verificar parametro ajuste com valor menor ou maior ao estoque minimo e maixmo
    $parametro_ajuste_estoque_minimo_maximo = verficar_paramentro($conecta, "tb_parametros", "cl_id", "5");

    //Usuario informar o preço de venda ou compra manual
    $parametro_ajuste_preco_manual = verficar_paramentro($conecta, "tb_parametros", "cl_id", "18");
    $parametro_motivo_obrigatorio = verficar_paramentro($conecta, "tb_parametros", "cl_id", "19");

    if ($acao == "validar_produto") {
        $id_produto = ($_POST["produto_id"]);
        $tipo = ($_POST["tipo"]);
        $quantidade = ($_POST["qtd_ajuste"]);
        $motivo = utf8_decode($_POST["motivo"]);
        $codigo_nf = ($_POST["codigo_nf"]);
        $valor_item = ($_POST["valor_item"]);
        $data_validade = ($_POST["data_validade"]);

        if ($id_produto == "") {
            $retornar["dados"] = array("sucesso" => "false", "title" => "Favor selecione o produto");
        } elseif ($tipo == "0") {
            $retornar["dados"] = array("sucesso" => "false", "title" => mensagem_alerta_cadastro("tipo"));
        } elseif ($quantidade == 0) {
            $retornar["dados"] = array("sucesso" => "false", "title" => mensagem_alerta_cadastro("quantidade"));
        } elseif ($motivo == "" and $parametro_motivo_obrigatorio == 'S') {
            $retornar["dados"] = array("sucesso" => "false", "title" => mensagem_alerta_cadastro("motivo"));
        } else {
            $retornar["dados"] = array("sucesso" => "false", "title" => "$data_validade");

            if (consulta_tabela($conecta, 'tb_produtos', 'cl_id', $id_produto, 'cl_status_ativo') != "SIM") { //verificar se o produto está ativo
                $retornar["dados"] = array("sucesso" => "false", "title" => "Esse produto não está ativo, não é possivel realizar o ajuste");
            } else {


                if (verificaVirgula($quantidade)) { //verificar se tem virgula
                    $quantidade = formatDecimal($quantidade); // formatar virgula para ponto
                }


                if (verifcar_descricao_serie($conecta, "2") == "") { // verificar se a serie está cadastrada
                    $retornar["dados"] = array("sucesso" => false, "title" => mensagem_serie_cadastrada());
                } else {

                    $ajuste_estoque = consultar_serie($conecta, "2");
                    $ajuste_estoque = $ajuste_estoque + 1; //incremento para adicionar na serie ajuste de estoque

                    $estoque_atual = consulta_tabela($conecta, 'tb_produtos', 'cl_id', $id_produto, 'cl_estoque'); //consultar estoque atual do produto
                    $estoque_minimo = consulta_tabela($conecta, 'tb_produtos', 'cl_id', $id_produto, 'cl_estoque_minimo'); //consultar estoque minimo do produto
                    $estoque_maximo = consulta_tabela($conecta, 'tb_produtos', 'cl_id', $id_produto, 'cl_estoque_maximo'); //consultar estoque minimo do produto

                    $preco_venda_atual = consulta_tabela($conecta, 'tb_produtos', 'cl_id', $id_produto, 'cl_preco_venda');
                    $preco_compra_atual = consulta_tabela($conecta, 'tb_produtos', 'cl_id', $id_produto, 'cl_ult_preco_compra');


                    // $valor_venda = 0;
                    // $valor_compra = 0;
                    $valor_venda = $preco_venda_atual;
                    $valor_compra = $preco_compra_atual;

                    if ($tipo == 'ENTRADA' and $parametro_ajuste_preco_manual == "S") { //realizar a operação se for entrada e o parametro para preço de compra estiver setado como sim
                        $valor_compra = $valor_item;
                        $valor_venda = $preco_venda_atual;
                    } elseif ($tipo == 'SAIDA' and $parametro_ajuste_preco_manual == "S") {
                        $valor_venda = $valor_item;
                        $valor_compra = $preco_compra_atual;
                    } else {
                        $valor_compra = $preco_compra_atual;
                        $valor_venda = $preco_venda_atual;
                    }

                    if ($tipo == 'SAIDA') { //realizar a operação se for saida subtrair ao estoque se não somar
                        $novo_estoque = $estoque_atual - $quantidade;
                    } else {
                        $novo_estoque = $estoque_atual + $quantidade;
                    }



                    if ($tipo == 'SAIDA' and ($novo_estoque < 0)) { // verificar se o estoque vai ser negativo
                        $retornar["dados"] = array("sucesso" => "false", "title" => "Não será possível realizar o ajuste de estoque solicitado, uma vez que este resultaria em um saldo negativo no estoque desse produto");
                    } else {

                        if (($parametro_ajuste_estoque_minimo_maximo == "S") and (($estoque_minimo != "0" and $novo_estoque < $estoque_minimo) or ($estoque_maximo != "0" and $novo_estoque > $estoque_maximo))) {

                            $retornar["dados"] = array("sucesso" => "false", "title" => "Não é possivel realizar o ajuste, uma vez que este resultaria em um saldo não permitido pelo saldo mínimo ou máximo");
                        } else {
                            //adicionar ao ajuste de estoque
                            $retornar["dados"] = array("sucesso" => true, "title" => "ok");
                        }
                    }
                }
            }
        }
    }


    if ($acao == "finalizar_ajuste") {
        $codigo_nf = $_POST['codigo_nf']; //array de produtos
        $itensJSON = $_POST['itens']; //array de produtos
        $itens = json_decode($itensJSON, true); //recuperar valor do array javascript decodificando o json

        $novo_numero_ajuste = consultar_serie($conecta, "2") + 1;

        mysqli_begin_transaction($conecta);
        $erro = false;


        if (count($itens) == 0) {
            $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel finalizar, é necessairo adicionar ajustes");
        } else {

            $ajuste_estoque = consultar_serie($conecta, "2");
            $ajuste_estoque = $ajuste_estoque + 1; //incremento para adicionar na serie ajuste de estoque

            foreach ($itens as $linha) {

                $id_produto = ($linha["produto_id"]);
                $tipo = ($linha["tipo"]);
                $quantidade = ($linha["qtd_ajuste"]);
                $motivo = utf8_decode($linha["motivo"]);
                $valor_item = ($linha["valor_item"]);
                $data_validade = ($linha["data_validade"]);


                if (verificaVirgula($quantidade)) { //verificar se tem virgula
                    $quantidade = formatDecimal($quantidade); // formatar virgula para ponto
                }


                if (verifcar_descricao_serie($conecta, "2") == "") { // verificar se a serie está cadastrada
                    $retornar["dados"] = array("sucesso" => false, "title" => mensagem_serie_cadastrada());
                } else {


                    $estoque_atual = consulta_tabela($conecta, 'tb_produtos', 'cl_id', $id_produto, 'cl_estoque'); //consultar estoque atual do produto
                    $estoque_minimo = consulta_tabela($conecta, 'tb_produtos', 'cl_id', $id_produto, 'cl_estoque_minimo'); //consultar estoque minimo do produto
                    $estoque_maximo = consulta_tabela($conecta, 'tb_produtos', 'cl_id', $id_produto, 'cl_estoque_maximo'); //consultar estoque minimo do produto

                    $preco_venda_atual = consulta_tabela($conecta, 'tb_produtos', 'cl_id', $id_produto, 'cl_preco_venda');
                    $preco_compra_atual = consulta_tabela($conecta, 'tb_produtos', 'cl_id', $id_produto, 'cl_ult_preco_compra');


                    // $valor_venda = 0;
                    // $valor_compra = 0;
                    $valor_venda = $preco_venda_atual;
                    $valor_compra = $preco_compra_atual;

                    if ($tipo == 'ENTRADA' and $parametro_ajuste_preco_manual == "S") { //realizar a operação se for entrada e o parametro para preço de compra estiver setado como sim
                        $valor_compra = $valor_item;
                        $valor_venda = $preco_venda_atual;
                    } elseif ($tipo == 'SAIDA' and $parametro_ajuste_preco_manual == "S") {
                        $valor_venda = $valor_item;
                        $valor_compra = $preco_compra_atual;
                    } else {
                        $valor_compra = $preco_compra_atual;
                        $valor_venda = $preco_venda_atual;
                    }

                    if ($tipo == 'SAIDA') { //realizar a operação se for saida subtrair ao estoque se não somar
                        $novo_estoque = $estoque_atual - $quantidade;
                    } else {
                        $novo_estoque = $estoque_atual + $quantidade;
                    }

                    $juste = ajuste_estoque($conecta, $data_lancamento, "$serie_ajust-$novo_numero_ajuste", $tipo, $id_produto, $quantidade, $empresa_ajuste, "", $usuario_id, $forma_pagamento_ajuste, "$valor_venda", "$valor_compra", '0', $motivo, $codigo_nf, "", "");
                    if (!$juste) {
                        $erro = true;
                    } else {
                        if (!$ajustar_qtd_produto = ajuste_qtd_produto($conecta, $id_produto, $novo_estoque, $data_validade)) {
                            $erro = true;
                        }
                    }
                }
                //adicionar ao ajuste de estoque
            }
            if (!$erro) {
                mysqli_commit($conecta); // Confirma a transação se tudo ocorreu bem
                //atualizar valor em serie ajst // ajuste de estoque
                adicionar_valor_serie($conecta, "2", $novo_numero_ajuste);
                $retornar["dados"] = array("sucesso" => true, "title" => "Ajuste realizado com sucesso");
                //registrar no log
                $mensagem = utf8_decode("Usuário $nome_usuario_logado realizou o ajuste de estoque $serie_ajust-$novo_numero_ajuste ");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            } else {
                mysqli_rollback($conecta); // Desfaz a transação em caso de erro
                $retornar["dados"] = array("sucesso" => false, "title" => "$mensagem");

                $mensagem = utf8_decode("Tentativa do usuário $nome_usuario_logado realizar o ajuste de estoque $serie_ajust-$novo_numero_ajuste sem sucesso");
                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
        }
    }

    echo json_encode($retornar);
}
