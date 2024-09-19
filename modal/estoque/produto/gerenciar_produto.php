<?php


if (isset($_GET['form_id'])) {
   $form_id = $_GET['form_id'];
} else {
   $form_id = "";
}

if (isset($_GET['detalhe_produto'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";

   $form_id = isset($_GET['form_id']) ? $_GET['form_id'] : 0;
   $query = "SELECT * FROM tb_produtos where cl_id = $form_id ";
   $consulta_produto = mysqli_query($conecta, $query);
   if (!$consulta_produto) {
      die("Falha no banco de dados: " . mysqli_error($conecta));
   } else {
      $linha = mysqli_fetch_assoc($consulta_produto);
      $qtd = mysqli_num_rows($consulta_produto);

      $codigo_nf = utf8_encode($linha['cl_codigo']);
      $descricao = utf8_encode($linha['cl_descricao']);
      $referencia = utf8_encode($linha['cl_referencia']);
   }
   $imagem_produto_default = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "34");
   $img_produto = consulta_tabela_query($conecta, "select * from tb_imagem_produto where cl_codigo_nf ='$codigo_nf' order by cl_ordem asc limit 1", 'cl_descricao');
   if ($img_produto == "") {
      $img_produto = $imagem_produto_default;
   } else {
      $img_produto = "img/produto/$img_produto";
   }

   $qtd_carrinho = consulta_tabela_query($conecta, "SELECT COALESCE(SUM(cl_quantidade), 0) as qtd FROM tb_carrinho_loja WHERE cl_produto_id = $form_id", 'qtd');
   $qtd_favorito = consulta_tabela_query($conecta, "SELECT COALESCE(count(*), 0) as qtd FROM tb_favorito_loja WHERE cl_produto_id = $form_id", 'qtd');

   $qtd_pedidos = consulta_tabela_query($conecta, "SELECT COALESCE(sum(prdl.cl_quantidade), 0 ) as qtd FROM `tb_produto_pedido_loja` as prdl 
   left join tb_pedido_loja as pd on pd.cl_codigo_nf = prdl.cl_codigo_nf where prdl.cl_produto_id = '$form_id' and pd.cl_status_pagamento ='approved'", 'qtd');

   $qtd_visualizacao = consulta_tabela_query($conecta, "SELECT * FROM tb_metricas_produtos WHERE cl_produto_id = $form_id", 'cl_visualizacao');
   $qtd_visualizacao = empty($qtd_visualizacao) ? 0 : $qtd_visualizacao;


   // $select_perguntas_clientes .= " order by duv.cl_id asc ";
   // $consultar_perguntas_clientes = mysqli_query($conecta, $select_perguntas_clientes); //perguntas dos cliente que foram feita no ecommerce
   // $qtd_perguntas_clientes  = mysqli_num_rows($consultar_perguntas_clientes);



}

if (isset($_GET['pergunta_tela'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";
   $form_id = isset($_GET['form_id']) ? $_GET['form_id'] : 0;

   $query = "SELECT duv.cl_id as duvid, prd.*, duv.*,user.* FROM tb_duvida_loja as duv 
    left join tb_user_loja as user on user.cl_id = duv.cl_usuario_id
   left join tb_produtos as prd on prd.cl_id = duv.cl_produto_id
    where duv.cl_id=$form_id ";
   $consulta_duvida = mysqli_query($conecta, $query);
   if (!$consulta_duvida) {
      die("Falha no banco de dados: " . mysqli_error($conecta));
   } else {
      $linha = mysqli_fetch_assoc($consulta_duvida);
      $qtd = mysqli_num_rows($consulta_duvida);

      $cliente = utf8_encode($linha['cl_nome']);
      $produto = utf8_encode($linha['cl_descricao']);
      $pergunta = utf8_encode($linha['cl_mensagem']);
   }
}
if (isset($_GET['tela_produto']) or isset($_GET['consultar_produto_tela'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";
   $altera_estoque = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '104', 'cl_valor');
   $altera_preco = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '25', 'cl_valor');

   $form_id = isset($_GET['form_id']) ? $_GET['form_id'] : 0;
   $valida_produto_variante = consulta_tabela($conecta, 'tb_produtos', 'cl_id', $form_id, 'cl_codigo_nf_pai');
}

if (isset($_GET['consultar_marcadores'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   $codigo_nf = isset($_GET['codigo_nf']) ? $_GET['codigo_nf'] : '';
}

if (isset($_GET['consultar_variacao'])) { //variação de produtos

   $codigo_nf = isset($_GET['codigo_nf']) ? $_GET['codigo_nf'] : '';
   $imagem_produto_default = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "34");
   $imagem_produto_tabela = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "22");

   $query = "SELECT prd.*, vt.*, vt.cl_descricao as titulovariante,
    vt.cl_id as idvariante
          FROM tb_variante_produto as vt
          INNER JOIN tb_produtos as prd 
          ON prd.cl_codigo = vt.cl_produto_pai_codigo_nf
          WHERE vt.cl_produto_pai_codigo_nf = '$codigo_nf' ORDER BY prd.cl_codigo, vt.cl_descricao ";
   $consultar_opcoes = mysqli_query($conecta, $query);
   if (!$consultar_opcoes) {
      die("Falha no banco de dados: " . mysqli_error($conecta));
   } else {
      $qtd = mysqli_num_rows($consultar_opcoes);
   }

   $query = "SELECT * FROM tb_produtos as prd where cl_codigo_nf_pai = '$codigo_nf'  ";
   $consultar_variantes = mysqli_query($conecta, $query);
   if (!$consultar_variantes) {
      die("Falha no banco de dados: " . mysqli_error($conecta));
   } else {
      $qtd = mysqli_num_rows($consultar_variantes);
   }
}

//consultar informações para tabela
if (isset($_GET['consultar_produto'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";

   $imagem_produto_default = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "34");
   $imagem_produto_tabela = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "22");
   $sistema_delivery =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "42"); //Verificar se o sistema é para delivery


   $consulta = $_GET['consultar_produto'];

   if ($consulta == "inicial") {
      // $consultar_tabela_inicialmente =  verficar_paramentro($conecta,"tb_parametros","cl_id","1");//VERIFICAR PARAMETRO ID - 1
      $consultar_tabela_inicialmente = "N";
      $select = "SELECT prd.*, cl_fabricante, cl_img_produto,grp.cl_descricao as grupo, prd.cl_id as produtoid,prd.cl_preco_promocao,prd.cl_codigo,prd.cl_estoque_minimo,prd.cl_estoque_maximo, prd.cl_descricao as 
      descricao,prd.cl_status_ativo as ativo,prd.cl_referencia, subgrp.cl_descricao as subgrupo,und.cl_sigla as und,und.cl_descricao as descunidade,prd.cl_estoque,prd.cl_preco_venda 
      from tb_produtos as prd
      left join tb_subgrupo_estoque as subgrp on subgrp.cl_id = prd.cl_grupo_id
      left join tb_grupo_estoque as grp on grp.cl_id = subgrp.cl_grupo_id
      left join tb_unidade_medida as und on und.cl_id = prd.cl_und_id ORDER BY prd.cl_id";
      $consultar_produtos = mysqli_query($conecta, $select);
      if (!$consultar_produtos) {
         die("Falha no banco de dados: " . mysqli_error($conecta));
      } else {
         $qtd = mysqli_num_rows($consultar_produtos);
      }
   } else {
      $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
      $status_prod = isset($_GET['status_prod']) ? $_GET['status_prod'] : '0';
      $subgrupo = isset($_GET['subgrupo']) ? $_GET['subgrupo'] : '0';
      $tipo_produto = isset($_GET['tipo_produto']) ? $_GET['tipo_produto'] : '0';
      $estoque_consulta = isset($_GET['estoque_consulta']) ? $_GET['estoque_consulta'] : '0';
      $status_promocao = isset($_GET['status_promocao']) ? $_GET['status_promocao'] : '0';
      $unidade_medida = isset($_GET['unidade_medida']) ? $_GET['unidade_medida'] : '0';
      $operacao_produto = isset($_GET['operacao_produto']) ? $_GET['operacao_produto'] : '';




      $select = "SELECT prd.*, cl_fabricante,grp.cl_descricao as grupo, cl_preco_promocao,cl_data_valida_promocao, cl_img_produto, prd.cl_id as produtoid,prd.cl_preco_promocao,prd.cl_descricao as
       descricao,prd.cl_codigo,prd.cl_estoque_minimo,prd.cl_estoque_maximo,prd.cl_referencia,prd.cl_status_ativo as ativo, 
       subgrp.cl_descricao as subgrupo,und.cl_sigla as und,und.cl_descricao as descunidade,prd.cl_estoque,prd.cl_preco_venda 
            from tb_produtos as prd left join tb_subgrupo_estoque as subgrp on subgrp.cl_id = prd.cl_grupo_id
            left join tb_grupo_estoque as grp on grp.cl_id = subgrp.cl_grupo_id
            left join tb_unidade_medida as und on und.cl_id = prd.cl_und_id 
            where (prd.cl_descricao like '%{$pesquisa}%' or
            prd.cl_id  like '%{$pesquisa}%' or cl_fabricante like '%{$pesquisa}%' or prd.cl_referencia LIKE '%{$pesquisa}%' 
            or prd.cl_codigo_barra LIKE '%{$pesquisa}%'   or prd.cl_ncm LIKE '%{$pesquisa}%' or prd.cl_cest LIKE '%{$pesquisa}%' or prd.cl_cst_icms LIKE '%{$pesquisa}%' )";
      if ($status_prod != "0") {
         $select .= " AND prd.cl_status_ativo = '$status_prod' ";
      }
      if ($estoque_consulta == "S") {
         $select .= " AND prd.cl_estoque > 0 ";
      } elseif ($estoque_consulta == 'N') {
         $select .= " AND prd.cl_estoque <= 0 ";
      }

      if ($subgrupo != 0) {
         $select .= " AND subgrp.cl_id = '$subgrupo' ";
      }
      if ($tipo_produto != 0) {
         $select .= " AND prd.cl_tipo_id = '$tipo_produto' ";
      }
      if ($status_promocao == "ativo") {
         $select .= " AND ( prd.cl_preco_promocao > 0
         and prd.cl_data_valida_promocao >= '$data_lancamento' ) ";
      } elseif ($status_promocao == "expirado") {
         $select .= " AND ( prd.cl_preco_promocao > 0
         and prd.cl_data_valida_promocao < '$data_lancamento' ) ";
      }
      if ($unidade_medida != "0") {
         $select .= " AND prd.cl_und_id = '$unidade_medida' ";
      }
      $select .= " ORDER BY prd.cl_id ";
      $consultar_produtos = mysqli_query($conecta, $select);
      if (!$consultar_produtos) {
         die("Falha no banco de dados: " . mysqli_error($conecta));
      } else {
         $qtd = mysqli_num_rows($consultar_produtos);
      }
   }
}


//consultar informações para tabela ecommerce
if (isset($_GET['consultar_produto_ecommerce'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";

   $imagem_produto_default = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "34");
   $imagem_produto_tabela = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "22");
   $sistema_delivery =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "42"); //Verificar se o sistema é para delivery


   $consulta = $_GET['consultar_produto_ecommerce'];

   if ($consulta == "inicial") {
      // $consultar_tabela_inicialmente =  verficar_paramentro($conecta,"tb_parametros","cl_id","1");//VERIFICAR PARAMETRO ID - 1
      $consultar_tabela_inicialmente = "N";
      $select = "SELECT prd.*, cl_fabricante,grp.cl_descricao as grupo, cl_img_produto, prd.cl_id as produtoid,prd.cl_preco_promocao,prd.cl_codigo,prd.cl_estoque_minimo,prd.cl_estoque_maximo, prd.cl_descricao as 
      descricao,prd.cl_status_ativo as ativo,prd.cl_referencia, subgrp.cl_descricao as subgrupo,und.cl_sigla as und,und.cl_descricao as descunidade,prd.cl_estoque,prd.cl_preco_venda 
      from tb_produtos as prd 
      left join tb_subgrupo_estoque as subgrp on subgrp.cl_id = prd.cl_grupo_id 
      left join tb_grupo_estoque as grp on grp.cl_id = subgrp.cl_grupo_id
      left join tb_unidade_medida as und on und.cl_id = prd.cl_und_id ORDER BY prd.cl_id";
      $consultar_produtos = mysqli_query($conecta, $select);
      if (!$consultar_produtos) {
         die(mysqli_error($conecta));
      } else {
         $qtd = mysqli_num_rows($consultar_produtos);
      }
   } else {
      $pesquisa = utf8_decode($_GET['conteudo_pesquisa']); //filtro
      $status_prod = isset($_GET['status_prod']) ? $_GET['status_prod'] : '0';
      $subgrupo = isset($_GET['subgrupo']) ? $_GET['subgrupo'] : '0';
      $estoque_consulta = isset($_GET['estoque_consulta']) ? $_GET['estoque_consulta'] : '0';
      $status_promocao = isset($_GET['status_promocao']) ? $_GET['status_promocao'] : '0';
      $tipo_produto = isset($_GET['tipo_produto']) ? $_GET['tipo_produto'] : '0';

      $select = "SELECT prd.*,  cl_fabricante, grp.cl_descricao as grupo, cl_preco_promocao,cl_data_valida_promocao, cl_img_produto, prd.cl_id as produtoid,prd.cl_preco_promocao,prd.cl_descricao as
       descricao,prd.cl_codigo,prd.cl_estoque_minimo,prd.cl_estoque_maximo,prd.cl_referencia,prd.cl_status_ativo as ativo, 
       subgrp.cl_descricao as subgrupo,und.cl_sigla as und,und.cl_descricao as descunidade,prd.cl_estoque,prd.cl_preco_venda 
            from tb_produtos as prd 
            left join tb_subgrupo_estoque as subgrp on subgrp.cl_id = prd.cl_grupo_id 
            left join tb_grupo_estoque as grp on grp.cl_id = subgrp.cl_grupo_id
            left join tb_unidade_medida as und on und.cl_id = prd.cl_und_id 
             where (prd.cl_descricao like '%{$pesquisa}%' or
            prd.cl_id  like '%{$pesquisa}%' or prd.cl_fabricante like '%{$pesquisa}%' or 
            prd.cl_referencia LIKE '%{$pesquisa}%' or prd.cl_codigo_barra LIKE '%{$pesquisa}%')";
      if ($status_prod != "0") {
         $select .= " and prd.cl_status_ativo = '$status_prod' ";
      }
      if ($estoque_consulta == "S") {
         $select .= " and prd.cl_estoque > 0 ";
      } elseif ($estoque_consulta == 'N') {
         $select .= " and prd.cl_estoque <= 0 ";
      }
      if ($tipo_produto != 0) {
         $select .= " AND prd.cl_tipo_id = '$tipo_produto' ";
      }
      if ($subgrupo != 0) {
         $select .= " and subgrp.cl_id = '$subgrupo' ";
      }
      if ($status_promocao == "ativo") {
         $select .= " AND ( prd.cl_preco_promocao > 0
         and prd.cl_data_valida_promocao >= '$data_lancamento' ) ";
      } elseif ($status_promocao == "expirado") {
         $select .= " AND ( prd.cl_preco_promocao > 0
         and prd.cl_data_valida_promocao < '$data_lancamento' ) ";
      }
      $select .= " ORDER BY prd.cl_id ";
      $consultar_produtos = mysqli_query($conecta, $select);
      if (!$consultar_produtos) {
         die(mysqli_error($conecta));
      } else {
         $qtd = mysqli_num_rows($consultar_produtos);
      }
   }
}


if (isset($_GET['consultar_pergunta_produto_ecommerce'])) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   $consulta = $_GET['consultar_pergunta_produto_ecommerce'];

   $data_inicial = $_GET['data_inicial'];
   $data_final = $_GET['data_final'];
   $produto_id = isset($_GET['produto_id']) ? $_GET['produto_id'] : 0;
   $pesquisa = utf8_decode($_GET['conteudo_pesquisa']);

   $data_inicial = ($data_inicial . ' 01:01:01');
   $data_final = ($data_final . ' 23:59:59');

   $query = "SELECT duv.cl_id as duvid, prd.*, duv.*,user.* FROM tb_duvida_loja as duv 
    left join tb_user_loja as user on user.cl_id = duv.cl_usuario_id
   left join tb_produtos as prd on prd.cl_id = duv.cl_produto_id
    where duv.cl_origem_mensagem ='0' and  duv.cl_data  
    between '$data_inicial' and '$data_final' and duv.cl_produto_id = '$produto_id'
     and ( prd.cl_descricao  like '%$pesquisa%' or duv.cl_mensagem like '%$pesquisa%' or user.cl_nome like '%$pesquisa%' ) 
     order by duv.cl_respondido asc, duv.cl_id asc, user.cl_id asc ";
   $consultar_perguntas_clientes = mysqli_query($conecta, $query);
   if (!$consultar_perguntas_clientes) {
      die(mysqli_error($conecta));
   } else {
      $qtd = mysqli_num_rows($consultar_perguntas_clientes);
   }
}


if (isset($_POST['formulario_produto'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";
   $retornar = array();
   $acao = $_POST['acao'];

   $alterar_preco_venda = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "25");
   $ncm_obrigatorio = verficar_paramentro($conecta, "tb_parametros", "cl_id", "13");
   $serie_ajst = verifcar_descricao_serie($conecta, 2);

   $id_user_logado = verifica_sessao_usuario();
   $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $id_user_logado, "cl_usuario");
   $altera_estoque = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '104', 'cl_valor'); //ALTERAR ESTOQUE PELA TELA DE EDITAR PRODUTO
   $altera_preco = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '25', 'cl_valor');

   if ($acao == "show") {
      $id_produto = $_POST['produto_id'];
      $select = "SELECT * from tb_produtos where cl_id = $id_produto";
      $consultar_produtos = mysqli_query($conecta, $select);
      $linha = mysqli_fetch_assoc($consultar_produtos);
      $codigo_nf = ($linha['cl_codigo']);
      $descricao_b = utf8_encode($linha['cl_descricao']);
      $referencia_b = utf8_encode($linha['cl_referencia']);
      $equivalencia_b = utf8_encode($linha['cl_equivalencia']);
      $codigo_barras_b = ($linha['cl_codigo_barra']);
      $grupo_id_b = ($linha['cl_grupo_id']);
      $fabricante_b = ($linha['cl_fabricante']);
      $tipo_b = ($linha['cl_tipo_id']);
      $estoque_b = ($linha['cl_estoque']);
      $est_minimo_b = ($linha['cl_estoque_minimo']);
      $est_max_b = ($linha['cl_estoque_maximo']);
      $local_b = utf8_encode($linha['cl_localizacao']);
      $tamanho_b = utf8_encode($linha['cl_tamanho']);
      $und_b = ($linha['cl_und_id']);
      $status_ativo_b = ($linha['cl_status_ativo']);
      $preco_venda_b = ($linha['cl_preco_venda']);
      $preco_custo_b = ($linha['cl_preco_custo']);
      $margem_b = ($linha['cl_margem_lucro']);
      $preco_promocao_b = ($linha['cl_preco_promocao']);
      $desconto_maximo_b = ($linha['cl_desconto_maximo']);
      $ult_preco_compra_b = ($linha['cl_ult_preco_compra']);
      $cest_b = ($linha['cl_cest']);
      $ncm_b = ($linha['cl_ncm']);
      $cst_icms_b = ($linha['cl_cst_icms']);
      $pis_s_b = ($linha['cl_cst_pis_s']);
      $pis_e_b = ($linha['cl_cst_pis_e']);
      $cofins_s_b = ($linha['cl_cst_cofins_s']);
      $cofins_e_b = ($linha['cl_cst_cofins_e']);

      $observacao_b = utf8_encode($linha['cl_observacao']);

      $data_valida_promocao =  ($linha['cl_data_valida_promocao']);
      $data_validade =  ($linha['cl_data_validade']);

      /*delivery */
      $descricao_delivery = utf8_encode($linha['cl_descricao_delivery']);
      $descricao_ext_delivery = utf8_encode($linha['cl_descricao_extendida_delivery']);
      $img_produto = ($linha['cl_img_produto']);

      $lancamento = ($linha['cl_lancamento']);

      $informacao = array(
         "codigo_nf" => $codigo_nf,
         "descricao" => $descricao_b,
         "referencia" => $referencia_b,
         "equivalencia" => $equivalencia_b,
         "codigo_barras" => $codigo_barras_b,
         "grupo_id" => $grupo_id_b,
         "fabricante" => $fabricante_b,
         "tipo" => $tipo_b,
         "estoque" => $estoque_b,
         "est_minimo" => $est_minimo_b,
         "est_maximo" => $est_max_b,
         "local" => $local_b,
         "tamanho" => $tamanho_b,
         "und" => $und_b,
         "status_ativo" => $status_ativo_b,
         "preco_venda" => $preco_venda_b,
         "preco_custo" => $preco_custo_b,
         "margem" => $margem_b,
         "preco_promocao" => $preco_promocao_b,
         "desconto_maximo" => $desconto_maximo_b,
         "ult_preco_compra" => $ult_preco_compra_b,
         "cest" => $cest_b,
         "ncm" => $ncm_b,
         "cst_icms" => $cst_icms_b,
         "pis_s" => $pis_s_b,
         "pis_e" => $pis_e_b,
         "cofins_s" => $cofins_s_b,
         "cofins_e" => $cofins_e_b,
         "observacao" => $observacao_b,
         "data_valida_promocao" => $data_valida_promocao,
         "data_validade" => $data_validade,
         "descricao_delivery" => $descricao_delivery,
         "img_produto" => $img_produto,
         "descricao_ext_delivery" => $descricao_ext_delivery,
         "lancamento" => $lancamento,
      );

      $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
   }

   if ($acao == "create") {


      $codigo_nf = utf8_decode($_POST["codigo_nf"]);
      $descricao = utf8_decode($_POST["descricao"]);
      $referencia = utf8_decode($_POST["referencia"]);
      $fabricante = utf8_decode($_POST["fabricante"]);
      $equivalencia = utf8_decode($_POST["equivalencia"]);
      $observacao = utf8_decode($_POST["observacao"]);
      $codigo_barras = ($_POST["codigo_barras"]);
      $grupo_estoque = ($_POST["grupo_estoque"]);
      $tipo = utf8_decode($_POST["tipo"]);
      $status = ($_POST["status"]);
      $estoque = ($_POST["estoque"]);
      $est_minimo = ($_POST["est_minimo"]);
      $est_maximo = ($_POST["est_maximo"]);
      $local_produto = utf8_decode($_POST["local_produto"]);
      $tamanho = utf8_decode($_POST["tamanho"]);
      $unidade_md = ($_POST["unidade_md"]);
      $prc_venda = ($_POST["prc_venda"]);
      $prc_custo = ($_POST["prc_custo"]);
      $margem_lucro = ($_POST["margem_lucro"]);
      $prc_promocao = ($_POST["prc_promocao"]);
      $cest = ($_POST["cest"]);
      $ncm = ($_POST["ncm"]);
      $cst_icms = ($_POST["cst_icms"]);
      $cst_pis_s = ($_POST["cst_pis_s"]);
      $cst_pis_e = ($_POST["cst_pis_e"]);
      $cst_cofins_s = ($_POST["cst_cofins_s"]);
      $cst_cofins_e = ($_POST["cst_cofins_e"]);
      $cfop_interno = ($_POST["cfop_interno"]);
      $cfop_externo = ($_POST["cfop_externo"]);
      $data_valida_promocao = ($_POST["data_valida_promocao"]);
      $data_validade = ($_POST["data_validade"]);
      // $descricao_delivery = utf8_decode($_POST["descricao_delivery"]);
      // $descricao_ext_delivery = utf8_decode($_POST["descricao_ext_delivery"]);
      $img_produto = ($_POST["img_produto"]);


      if (isset($_POST['lancamento'])) {
         $lancamento = 'SIM';
      } else {
         $lancamento = 'NAO';
      }
      if ($descricao == "") {
         $retornar["dados"] = array("sucesso" => "false", "title" => mensagem_alerta_cadastro("descricão"));
      } elseif ($grupo_estoque == "0") {
         $retornar["dados"] = array("sucesso" => "false", "title" => mensagem_alerta_cadastro("grupo"));
      } elseif ($tipo == "0") {
         $retornar["dados"] =  array("sucesso" => "false", "title" => mensagem_alerta_cadastro("tipo"));
      } elseif ($status == "0") {
         $retornar["dados"] =  array("sucesso" => "false", "title" => mensagem_alerta_cadastro("status"));
      } elseif ($unidade_md == "0") {
         $retornar["dados"] =  array("sucesso" => "false", "title" => mensagem_alerta_cadastro("unidade de medida"));
      } elseif ($ncm == "" and $ncm_obrigatorio == "S") {
         $retornar["dados"] =  array("sucesso" => "false", "title" => mensagem_alerta_cadastro("Ncm"));
      } elseif ($prc_promocao > $prc_venda) {
         $retornar["dados"] =  array("sucesso" => "false", "title" => "Preço promoção não pode ser maior do que o preço de venda, favor, verifique ");
      } else {


         if ($prc_custo != "") {
            if (verificaVirgula($prc_custo)) { //verificar se tem virgula
               $prc_custo = formatDecimal($prc_custo); // formatar virgula para ponto
            }
         }
         if ($prc_venda != "") {
            if (verificaVirgula($prc_venda)) { //verificar se tem virgula
               $prc_venda = formatDecimal($prc_venda); // formatar virgula para ponto
            }
         }
         if ($margem_lucro != "") {
            if (verificaVirgula($margem_lucro)) { //verificar se tem virgula
               $margem_lucro = formatDecimal($margem_lucro); // formatar virgula para ponto
            }
         }
         if ($prc_promocao != "") {
            if (verificaVirgula($prc_promocao)) { //verificar se tem virgula
               $prc_promocao = formatDecimal($prc_promocao); // formatar virgula para ponto
            }
         }

         if ($estoque != "") {
            if (verificaVirgula($estoque)) { //verificar se tem virgula
               $estoque = formatDecimal($estoque); // formatar virgula para ponto
            }
         }
         if ($est_minimo != "") {
            if (verificaVirgula($est_minimo)) { //verificar se tem virgula
               $est_minimo = formatDecimal($est_minimo); // formatar virgula para ponto
            }
         }
         if ($est_maximo != "") {
            if (verificaVirgula($est_maximo)) { //verificar se tem virgula
               $est_maximo = formatDecimal($est_maximo); // formatar virgula para ponto
            }
         }
         if ($cest != "") {
            if (verificaVirgula($cest)) { //verificar se tem virgula
               $cest = formatDecimal($cest); // formatar virgula para ponto
            }
         }
         if ($ncm != "") {
            if (verificaVirgula($ncm)) { //verificar se tem virgula
               $ncm = formatDecimal($ncm); // formatar virgula para ponto
            }
         }
         if ($cst_icms != "") {
            if (verificaVirgula($cst_icms)) { //verificar se tem virgula
               $cst_icms = formatDecimal($cst_icms); // formatar virgula para ponto
            }
         }
         if ($cst_pis_s != "") {
            if (verificaVirgula($cst_pis_s)) { //verificar se tem virgula
               $cst_pis_s = formatDecimal($cst_pis_s); // formatar virgula para ponto
            }
         }
         if ($cst_pis_e != "") {
            if (verificaVirgula($ncm)) { //verificar se tem virgula
               $ncm = formatDecimal($ncm); // formatar virgula para ponto
            }
         }
         if ($cst_cofins_s != "") {
            if (verificaVirgula($cst_cofins_s)) { //verificar se tem virgula
               $cst_cofins_s = formatDecimal($cst_cofins_s); // formatar virgula para ponto
            }
         }
         if ($cst_cofins_e != "") {
            if (verificaVirgula($cst_cofins_e)) { //verificar se tem virgula
               $cst_cofins_e = formatDecimal($cst_cofins_e); // formatar virgula para ponto
            }
         }

         if (verifcar_descricao_serie($conecta, "2") == "") { // verificar se a serie está cadastrada
            $retornar["dados"] = array("sucesso" => "false", "title" => mensagem_serie_cadastrada());
         } else {

            $descricao = str_replace("'", "", $descricao); //remover aspas simples
            $observacao = str_replace("'", "", $observacao); //remover aspas simples

            //$codigo_produto = consultar_serie($conecta, "PRD");
            //    $codigo_produto = $codigo_produto + 1; //incremento para adicionar ao codigo do produto

            $inset = "INSERT INTO tb_produtos (cl_codigo,cl_data_cadastro,cl_descricao,cl_tamanho,cl_localizacao,cl_referencia,cl_codigo_barra,cl_observacao,cl_preco_custo,cl_preco_venda,cl_estoque,
           cl_preco_promocao,cl_data_valida_promocao,cl_margem_lucro,cl_cest,cl_ncm,cl_cst_icms,cl_cst_pis_s,cl_cst_pis_e,cl_cst_cofins_s,cl_cst_cofins_e,
           cl_estoque_minimo,cl_estoque_maximo,cl_cfop_interno,cl_cfop_externo,cl_equivalencia,cl_fabricante,cl_und_id,cl_grupo_id,cl_tipo_id,cl_status_ativo,
           cl_data_validade,cl_img_produto,cl_lancamento )
            VALUES ('$codigo_nf','$data_lancamento','$descricao','$tamanho','$local_produto','$referencia','$codigo_barras','$observacao','$prc_custo','$prc_venda','$estoque',
            '$prc_promocao','$data_valida_promocao','$margem_lucro','$cest','$ncm','$cst_icms','$cst_pis_s','$cst_pis_e','$cst_cofins_s','$cst_cofins_e',
            '$est_minimo','$est_maximo','$cfop_interno','$cfop_externo','$equivalencia','$fabricante','$unidade_md','$grupo_estoque','$tipo','$status','$data_validade',
            '$img_produto','$lancamento')";
            $operacao_inserir = mysqli_query($conecta, $inset);
            if ($operacao_inserir) {
               //pegar o id do ultimo produto cadastrado
               $id_produto_b = mysqli_insert_id($conecta);
               $retornar["dados"] = array("sucesso" => true, "title" => "Cadastro realizado com sucesso, código do produto $id_produto_b");

               //registrar no log
               $mensagem =  utf8_decode("Cadastrou o produto, código $id_produto_b");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            } else {
               $retornar["dados"] = array("sucesso" => false, "title" => "Erro, entre em contato com o suporte");
               //registrar no log
               $mensagem =  utf8_decode("Tentativa sem sucesso de cadastrar o produto $descricao ");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
         }
      }
   }


   if ($acao == "update") {


      $id_produto = $_POST['id'];
      // $codigo_produto = ($_POST["codigo_produto"]);
      $descricao = utf8_decode($_POST["descricao"]);
      $referencia = utf8_decode($_POST["referencia"]);
      $fabricante = utf8_decode($_POST["fabricante"]);
      $equivalencia = utf8_decode($_POST["equivalencia"]);
      $observacao = utf8_decode($_POST["observacao"]);
      $codigo_barras = ($_POST["codigo_barras"]);
      $grupo_estoque = ($_POST["grupo_estoque"]);
      $tipo = utf8_decode($_POST["tipo"]);
      $status = ($_POST["status"]);

      $est_minimo = ($_POST["est_minimo"]);
      $est_maximo = ($_POST["est_maximo"]);
      $local_produto = utf8_decode($_POST["local_produto"]);
      $tamanho = utf8_decode($_POST["tamanho"]);
      $unidade_md = ($_POST["unidade_md"]);

      $margem_lucro = ($_POST["margem_lucro"]);
      if ($altera_preco == "S") {
         $prc_venda = ($_POST["prc_venda"]);
         $prc_custo = ($_POST["prc_custo"]);
         $prc_promocao = ($_POST["prc_promocao"]);
      }
      $cest = ($_POST["cest"]);
      $ncm = ($_POST["ncm"]);
      $cst_icms = ($_POST["cst_icms"]);
      $cst_pis_s = ($_POST["cst_pis_s"]);
      $cst_pis_e = ($_POST["cst_pis_e"]);
      $cst_cofins_s = ($_POST["cst_cofins_s"]);
      $cst_cofins_e = ($_POST["cst_cofins_e"]);
      $cfop_interno = ($_POST["cfop_interno"]);
      $cfop_externo = ($_POST["cfop_externo"]);
      $data_valida_promocao = isset($_POST['data_valida_promocao']) ? $_POST["data_valida_promocao"] : '';
      $data_validade = ($_POST["data_validade"]);


      if (isset($_POST['lancamento'])) {
         $lancamento = 'SIM';
      } else {
         $lancamento = 'NAO';
      }

      if (isset($_POST['estoque'])) {
         $estoque = $_POST['estoque'];
      } else {
         $estoque = '';
      }
      if ($descricao == "") {
         $retornar["dados"] = array("sucesso" => "false", "title" => mensagem_alerta_cadastro("descricão"));
      } elseif ($grupo_estoque == "0") {
         $retornar["dados"] = array("sucesso" => "false", "title" => mensagem_alerta_cadastro("grupo"));
      } elseif ($tipo == "0") {
         $retornar["dados"] =  array("sucesso" => "false", "title" => mensagem_alerta_cadastro("tipo"));
      } elseif ($status == "0") {
         $retornar["dados"] =  array("sucesso" => "false", "title" => mensagem_alerta_cadastro("status"));
      } elseif ($unidade_md == "0") {
         $retornar["dados"] =  array("sucesso" => "false", "title" => mensagem_alerta_cadastro("unidade de medida"));
      } elseif ($ncm == "" and $ncm_obrigatorio == "S") {
         $retornar["dados"] =  array("sucesso" => "false", "title" => mensagem_alerta_cadastro("ncm"));
      } elseif (($altera_preco == "S") and isset($prc_custo) and ($prc_promocao > $prc_venda)) {
         $retornar["dados"] =  array("sucesso" => false, "title" => "Preço promoção não pode ser maior do que o preço de venda, favor, verifique ");
      } else {


         if (isset($prc_custo) and $prc_custo != "") {
            if (verificaVirgula($prc_custo)) { //verificar se tem virgula
               $prc_custo = formatDecimal($prc_custo); // formatar virgula para ponto
            }
         }
         if (isset($prc_venda) and $prc_venda != "") {
            if (verificaVirgula($prc_venda)) { //verificar se tem virgula
               $prc_venda = formatDecimal($prc_venda); // formatar virgula para ponto
            }
         }
         if (isset($prc_promocao) and $prc_promocao != "") {
            if (verificaVirgula($prc_promocao)) { //verificar se tem virgula
               $prc_promocao = formatDecimal($prc_promocao); // formatar virgula para ponto
            }
         }

         if ($margem_lucro != "") {
            if (verificaVirgula($margem_lucro)) { //verificar se tem virgula
               $margem_lucro = formatDecimal($margem_lucro); // formatar virgula para ponto
            }
         }

         if ($estoque != "") {
            if (verificaVirgula($estoque)) { //verificar se tem virgula
               $estoque = formatDecimal($estoque); // formatar virgula para ponto
            }
         }

         if ($est_minimo != "") {
            if (verificaVirgula($est_minimo)) { //verificar se tem virgula
               $est_minimo = formatDecimal($est_minimo); // formatar virgula para ponto
            }
         }
         if ($est_maximo != "") {
            if (verificaVirgula($est_maximo)) { //verificar se tem virgula
               $est_maximo = formatDecimal($est_maximo); // formatar virgula para ponto
            }
         }
         if ($cest != "") {
            if (verificaVirgula($cest)) { //verificar se tem virgula
               $cest = formatDecimal($cest); // formatar virgula para ponto
            }
         }
         if ($ncm != "") {
            if (verificaVirgula($ncm)) { //verificar se tem virgula
               $ncm = formatDecimal($ncm); // formatar virgula para ponto
            }
         }
         if ($cst_icms != "") {
            if (verificaVirgula($cst_icms)) { //verificar se tem virgula
               $cst_icms = formatDecimal($cst_icms); // formatar virgula para ponto
            }
         }
         if ($cst_pis_s != "") {
            if (verificaVirgula($cst_pis_s)) { //verificar se tem virgula
               $cst_pis_s = formatDecimal($cst_pis_s); // formatar virgula para ponto
            }
         }
         if ($cst_pis_e != "") {
            if (verificaVirgula($ncm)) { //verificar se tem virgula
               $ncm = formatDecimal($ncm); // formatar virgula para ponto
            }
         }
         if ($cst_cofins_s != "") {
            if (verificaVirgula($cst_cofins_s)) { //verificar se tem virgula
               $cst_cofins_s = formatDecimal($cst_cofins_s); // formatar virgula para ponto
            }
         }
         if ($cst_cofins_e != "") {
            if (verificaVirgula($cst_cofins_e)) { //verificar se tem virgula
               $cst_cofins_e = formatDecimal($cst_cofins_e); // formatar virgula para ponto
            }
         }

         $descricao = str_replace("'", "", $descricao); //remover aspas simples
         $observacao = str_replace("'", "", $observacao); //remover aspas simples

         $update = "UPDATE `tb_produtos` SET `cl_descricao`= '$descricao', 
         `cl_tamanho` = '$tamanho',
         `cl_localizacao` = '$local_produto',
         `cl_referencia` = '$referencia',
         `cl_equivalencia` = '$equivalencia', 
         `cl_observacao` = '$observacao',
         `cl_codigo_barra` = '$codigo_barras', 
         `cl_data_validade`='$data_validade',
         `cl_margem_lucro` = '$margem_lucro', 
         `cl_cest` = '$cest',
         `cl_ncm` = '$ncm',
         `cl_cst_icms` = '$cst_icms',
         `cl_cst_pis_s` = '$cst_pis_s', 
         `cl_cst_pis_e` = '$cst_pis_e',
         `cl_cst_cofins_s` = '$cst_cofins_s', 
         `cl_cst_cofins_e` = '$cst_cofins_e', ";
         if ($altera_estoque == "S") {
            $update .= " cl_estoque = '$estoque', ";
         };
         if ($altera_preco == "S") {
            $update .= " 
              cl_data_valida_promocao ='$data_valida_promocao',
               cl_preco_promocao = '$prc_promocao', 
               cl_preco_venda = '$prc_venda', 
               cl_preco_custo = '$prc_custo', ";
         };
         $update .= " `cl_estoque_minimo` = '$est_minimo',
         `cl_estoque_maximo`= '$est_maximo',
         `cl_cfop_interno` = '$cfop_interno',
         `cl_cfop_externo` = '$cfop_externo', 
         `cl_fabricante` = '$fabricante',
         `cl_grupo_id` = '$grupo_estoque',
         `cl_und_id` = '$unidade_md', 
         `cl_tipo_id` = '$tipo', 
         `cl_status_ativo` = '$status',
         `cl_lancamento` = '$lancamento' ";
         $update .= " WHERE `cl_id` = $id_produto";
         $operacao_update = mysqli_query($conecta, $update);
         if ($operacao_update) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Produto alterado com sucesso");
            $mensagem =  utf8_decode("Alterou o produto de código $id_produto ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, não foi possivel realizar a ação, favor, verifique com o suporte");
            //registrar no log
            $mensagem =  utf8_decode("Usúario $nome_usuario_logado tentou alterar o produto de código $id_produto sem sucesso, Erro");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }

   if ($acao == 'clonar') {
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);
      $form_id = $_POST['form_id'];
      $codigo_nf_novo = md5(uniqid(time())); //gerar um novo codigo para nf

      if (empty($form_id)) {
         $retornar["dados"] = array("sucesso" => "false", "title" => "Produto não encontrado");
      } else {
         $insert = "INSERT INTO `tb_produtos` (
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
            `cl_tipo_ecommerce`
        )
        SELECT
             '$codigo_nf_novo',
            '$data_lancamento',
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
            `cl_tipo_ecommerce`
        FROM
            `tb_produtos`
        WHERE
            `cl_id` = '$form_id' ";
         $operacao_clonagem = mysqli_query($conecta, $insert);
         if ($operacao_clonagem) {
            $novo_id = mysqli_insert_id($conecta);
            $retornar["dados"] = array("sucesso" => true, "title" => "Produto clonado com sucesso, código do produto $novo_id");

            $mensagem = utf8_decode("Usúario $nome_usuario_logado gerou um novo produto de código $novo_id do resultado da clonagem do produto de código $form_id ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

            mysqli_commit($conecta);
         } else {
            mysqli_rollback($conecta);
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, entre em contato com o suporte");

            $mensagem = utf8_decode("Tentativa sem sucesso de clonar o produto $form_id ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }

   if ($acao == "update_delivery") {
      $nome_usuario_logado = $_POST["nome_usuario_logado"];
      $id_usuario_logado = $_POST["id_usuario_logado"];
      $perfil_usuario_logado = $_POST['perfil_usuario_logado'];

      $id_produto = $_POST['id'];

      $descricao_delivery = utf8_decode($_POST["descricao_delivery"]);
      $descricao_ext_delivery = utf8_decode($_POST["descricao_ext_delivery"]);
      // $img_produto = ($_POST["img_produto"]);
      $min_produto_delivery = ($_POST["min_produto_delivery"]);

      if ($id_produto == "") {
         $retornar["dados"] = array("sucesso" => "false", "title" => "Favor, selecione o produto");
      } elseif (strlen($descricao_delivery) > 100) {
         $retornar["dados"] =  array("sucesso" => "false", "title" => "A descrição do delivery atingiu o limite de 100 caracteres, favor, verifique");
      } elseif (strlen($descricao_ext_delivery) > 500) {
         $retornar["dados"] =  array("sucesso" => "false", "title" => "A descrição completa do delivery atingiu o limite de 500 caracteres, favor, verifique");
      } else {

         $update = "UPDATE `tb_produtos` SET `cl_descricao_delivery` = '$descricao_delivery',`cl_descricao_extendida_delivery` = '$descricao_ext_delivery', `cl_min_produto_delivery` = '$min_produto_delivery'  ";

         if (isset($_POST['max_add_obg'])) { //quantidade possiveis que o usuario pode coloque de adicional gratuitos
            $max_add_obg = ($_POST["max_add_obg"]);
            $update .= " , `cl_qtd_adicional_obrigatorio_delivery` = '$max_add_obg' ";
         }

         if (isset($_POST['status_obg'])) { //definir se o adicional gratuito será obrigatorio
            $update .= " , `cl_status_adicional_obrigatorio_delivery` = 'SIM' ";
         } else {
            $update .= " , `cl_status_adicional_obrigatorio_delivery` = 'NAO' ";
         }

         $update .= " WHERE `cl_id` = $id_produto ";
         $operacao_update = mysqli_query($conecta, $update);
         if ($operacao_update) {


            $select = "SELECT * from tb_produtos WHERE cl_status_ativo ='SIM' and cl_tipo_id ='5'"; //tipo adicinou poara delivery
            $consultar_adicionais = mysqli_query($conecta, $select);
            while ($linha = mysqli_fetch_assoc($consultar_adicionais)) {

               $produto_add = $linha['cl_id'];
               if (isset($_POST["add$produto_add"])) { //adicionais gratuitos
                  atualizar_status_produto_adicional($conecta, $produto_add, $id_produto, "INCLUIR", "CHECK");
               } else {
                  atualizar_status_produto_adicional($conecta, $produto_add, $id_produto, "REMOVER", "NOTCHECK");
               }

               if (isset($_POST["addobg$produto_add"])) { //adicionais pagos
                  atualizar_status_produto_adicional_obrigatorio($conecta, $produto_add, $id_produto, "INCLUIR", "CHECK");
               } else {
                  atualizar_status_produto_adicional_obrigatorio($conecta, $produto_add, $id_produto, "REMOVER", "NOTCHECK");
               }
            }
            $select = "SELECT * from tb_produtos WHERE cl_status_ativo ='SIM' and cl_tipo_id ='2'"; //tipo complemento
            $consultar_complemento = mysqli_query($conecta, $select);
            while ($linha = mysqli_fetch_assoc($consultar_complemento)) {
               $produto_add = $linha['cl_id'];
               if (isset($_POST["addcpm$produto_add"])) { //adicionais 
                  atualizar_status_produto_complemento($conecta, $produto_add, $id_produto, "INCLUIR", "CHECK");
               } else {
                  atualizar_status_produto_complemento($conecta, $produto_add, $id_produto, "REMOVER", "NOTCHECK");
               }
            }

            $select = "SELECT * from tb_subgrupo_estoque"; //subgrupos
            $consultar_subgrupos = mysqli_query($conecta, $select);
            while ($linha = mysqli_fetch_assoc($consultar_subgrupos)) {
               $subgrupo_id = $linha['cl_id'];
               if (isset($_POST["qtd_subgrupo_$subgrupo_id"])) {
                  $quantidade = $_POST["qtd_subgrupo_$subgrupo_id"];
                  if ($quantidade != "S") {
                     demilitar_tipo_adicionais($conecta, $id_produto, $subgrupo_id, $quantidade);
                  }
               }
            }

            $retornar["dados"] = array("sucesso" => true, "title" => "Produto alterado com sucesso");
            $mensagem =  utf8_decode("Usúario $nome_usuario_logado Alterou o produto delivery de código $id_produto ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, não foi possivel realizar a ação, favor, verifique com o suporte");
            //registrar no log
            $mensagem =  utf8_decode("Usúario $nome_usuario_logado tentou alterar o produto de código $id_produto sem sucesso");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }

   if ($acao == "opcao_produto") {
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);

      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = utf8_decode($value);
         // Remover aspas simples usando str_replace
         ${$name} = str_replace("'", "", ${$name});
      }
      $codigo_nf_novo = md5(uniqid(time())); //gerar um novo codigo para nf
      $erro = false;


      if (!empty($codigo_nf)) {
         /*consultar valores do produo pai */
         $resultados_prd_pai = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_produtos where cl_codigo ='$codigo_nf' ");
         if ($resultados_prd_pai) {
            foreach ($resultados_prd_pai as $linha) {
               $produto_id = ($linha['cl_id']);
               $descricao_produto = ($linha['cl_descricao']);
            }
         }
      }
      if (empty($produto_id)) {
         $retornar["dados"] = array("sucesso" => false, "title" => "É necessário salvar o produto para adicionar as variações");
         echo json_encode($retornar);
         exit;
      }

      $incluir_titulo = isset($_POST['incluir_titulo']) ? 1 : 0;

      if ($opcao_id == "") {
         if (empty($codigo_nf)) {
            $retornar["dados"] =  array("sucesso" => false, "title" => "Produto pai não identificado, favor, refaça a operação");
         } elseif (empty($nome_opcao)) {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("nome opção"));
         } elseif (empty($variantes_opcao)) {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("variantes"));
         } else {

            $variantes_opcao = array_map('trim', explode(',', $variantes_opcao));
            $ordem_opcao = consulta_tabela_query($conecta, "SELECT * FROM tb_variante_produto WHERE cl_produto_pai_codigo_nf= '$codigo_nf' group by cl_descricao order by cl_ordem_opcao desc ", 'cl_ordem_opcao');
            $ordem_opcao = !empty($ordem_opcao) ? $ordem_opcao + 1 : 1;

            $valida_opcao_existe = consulta_tabela_query($conecta, "SELECT * FROM tb_variante_produto 
            WHERE cl_produto_pai_codigo_nf= '$codigo_nf' and cl_descricao = '$nome_opcao'", 'cl_id');

            if (!empty($valida_opcao_existe)) { //verificar se existe a opção, não é possivel incluir a mesma opção para o mesmo produto
               $retornar["dados"] =  array("sucesso" => false, "title" => "Essa opção já existe, não é possível adicioná-la novamente");
               echo json_encode($retornar);
               exit;
            } else {

               foreach ($variantes_opcao as $variante) {
                  if (!empty($variante)) { //inserir a variante
                     $codigo_produto_nf_novo = md5(uniqid(time())); //gerar um novo codigo para nf
                     $dados = array('codigo_nf' => $codigo_nf, 'codigo_nf_novo' => $codigo_produto_nf_novo);
                     // $produto_id_variante = insert_produto_variante($dados); //INSERIR O PRODUTO NO ESTOQUE
                     // if ($produto_id_variante != false) { //INSERIR A VARIANTE
                     $query = "INSERT INTO `tb_variante_produto` (`cl_descricao`, `cl_tipo`, `cl_valor`, 
             `cl_variante_nf`, `cl_produto_pai_codigo_nf`,`cl_ordem_opcao`,`cl_incluir_titulo` ) 
             VALUES ('$nome_opcao', '$opcao_exibicao', '$variante', '$codigo_nf_novo', '$codigo_nf', '$ordem_opcao', '$incluir_titulo'  ) ";
                     $inserir_variante = mysqli_query($conecta, $query);
                     if (!$inserir_variante) {
                        $erro = true;
                        break;
                     } else {
                        $mensagem = utf8_decode("Adicionou uma nova variação $nome_opcao: $variante para o produto $descricao_produto");
                        registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                     }
                     //  }
                  }
               }
            }

            /*VERIFICAR SE O PRODUTO TEM MOVIMENTAÇÃO NO SISTEMA */

            // Verifica se a variante está presente na coluna cl_variacao de algum produto
            $query = "SELECT * FROM tb_produtos where cl_codigo_nf_pai ='$codigo_nf'";
            $consulta_produto = mysqli_query($conecta, $query);
            if (mysqli_num_rows($consulta_produto) > 0) {
               while ($linha = mysqli_fetch_assoc($consulta_produto)) {
                  $produto_id_filho = $linha['cl_id'];
                  $valida_mov_produto = consulta_tabela_query($conecta, "SELECT * FROM tb_ajuste_estoque where
                  cl_produto_id ='$produto_id_filho' and cl_ajuste_inicial ='0' ", 'cl_id');
                  if (empty($valida_mov_produto)) {
                     $query = "DELETE FROM tb_produtos where cl_id ='$produto_id_filho'"; //DELETAR O PRODUTO
                     $delete = mysqli_query($conecta, $query);
                     if (!$delete) {
                        $retornar["dados"] =  array("sucesso" => false, "title" => "Erro ao remover os produtos");
                        echo json_encode($retornar);
                        exit;
                     }
                  }
               }
            }


            // Função para gerar todas as combinações possíveis de variações
            function gerar_combinacoes($variações)
            {
               $combinacoes = [[]];
               foreach ($variações as $var) {
                  $temp = [];
                  foreach ($combinacoes as $comb) {
                     foreach ($var as $item) {
                        $temp[] = array_merge($comb, [$item]);
                     }
                  }
                  $combinacoes = $temp;
               }
               return $combinacoes;
            }

            // 1. Recuperar todas as variações existentes para o produto pai
            $query = "SELECT * FROM tb_variante_produto WHERE cl_produto_pai_codigo_nf = '$codigo_nf' ORDER BY cl_descricao, cl_ordem_opcao";
            $resultado = mysqli_query($conecta, $query);
            $variações = [];
            while ($row = mysqli_fetch_assoc($resultado)) {
               $variações[$row['cl_descricao']][] = $row['cl_id'];
            }

            // 2. Gerar todas as combinações possíveis
            $combinacoes = gerar_combinacoes(array_values($variações));

            // 3. Inserir cada combinação na tabela `tb_produtos`
            foreach ($combinacoes as $combinacao) {
               $combinacao_ids = implode(',', $combinacao);
               $codigo_produto_nf_novo = md5(uniqid(time())); // gerar um novo código para o produto

               $dados = array(
                  'codigo_nf' => $codigo_nf,
                  'codigo_nf_novo' => $codigo_produto_nf_novo,
                  'variacao' => $combinacao_ids
               );
               $produto_id_variante = insert_produto_variante($dados); //INSERIR O PRODUTO NO ESTOQUE
               if ($produto_id_variante === false) {
                  $erro = true;
                  break;
               }
            }



            if ($erro == false) {
               $retornar["dados"] = array("sucesso" => true, "title" => "Variante adicionado com sucesso ");
               $mensagem = utf8_decode("Usúario $nome_usuario_logado novas variantes ao produto de código $produto_id");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
               mysqli_commit($conecta);
            } else {
               mysqli_rollback($conecta);
               $retornar["dados"] = array("sucesso" => false, "title" => "Erro, entre em contato com o suporte");
               $mensagem = utf8_decode("Tentativa sem sucesso de adicioanr variantes ao produto $produto_id ");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
         }
      } else {
         if (empty($codigo_nf)) {
            $retornar["dados"] =  array("sucesso" => false, "title" => "Produto pai não identificado, favor, refaça a operação");
         } elseif (empty($nome_opcao)) {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("nome opção"));
         } elseif (empty($variantes_opcao)) {
            $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("variantes"));
         } else {
            $variantes_opcao = array_map('trim', explode(',', $variantes_opcao));

            $opcao_descricao = consulta_tabela_query($conecta, "SELECT * FROM tb_variante_produto WHERE cl_id ='$opcao_id' ", 'cl_descricao');
            $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_variante_produto where cl_descricao ='$opcao_descricao' and cl_produto_pai_codigo_nf = '$codigo_nf' ");
            if ($resultados) {
               foreach ($resultados as $linha) {
                  $variante_id = ($linha['cl_id']);
                  $variante_descricao = ($linha['cl_valor']);
                  $opcao_variacao = ($linha['cl_descricao']);
                  // $produto_id_filho = ($linha['cl_produto_id']);
                  // Verifica se o valor de $variante_descricao está no array $variantes_opcao
                  if (!in_array($variante_descricao, $variantes_opcao)) {  // O valor não está contido no array $variantes_opcao
                     //TRECHO DE CODIGO PARA VERIFICA SE A VARIAÇÃO JÁ TEM MOVIMENTAÇÃO, LEMBRE-SE


                     $query = "DELETE FROM tb_variante_produto where cl_id ='$variante_id'"; //DELETAR A VARIANTE
                     $delete = mysqli_query($conecta, $query);
                     if (!$delete) {
                        $erro = true;
                        break;
                     } else {
                        $mensagem = utf8_decode("Removeu a variação $opcao_variacao: $variante_descricao para o produto $descricao_produto");
                        registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                        //       // Verifica se a variante está presente na coluna cl_variacao de algum produto
                        //       $query = "SELECT * FROM tb_produtos where cl_codigo_nf_pai ='$codigo_nf'";
                        //       $consulta_produto = mysqli_query($conecta, $query);
                        //       if (mysqli_num_rows($consulta_produto) > 0) {
                        //          while ($linha = mysqli_fetch_assoc($consulta_produto)) {
                        //             $produto_id_filho = $linha['cl_id'];
                        //             $valida_mov_produto = consulta_tabela_query($conecta, "SELECT * FROM tb_ajuste_estoque where
                        // cl_produto_id ='$produto_id_filho' and cl_ajuste_inicial ='0' ", 'cl_id');
                        //             if (empty($valida_mov_produto)) {
                        //                $query = "DELETE FROM tb_produtos where cl_id ='$produto_id_filho'"; //DELETAR O PRODUTO
                        //                $delete = mysqli_query($conecta, $query);
                        //                if (!$delete) {
                        //                   $retornar["dados"] =  array("sucesso" => false, "title" => "Erro ao remover os produtos");
                        //                   echo json_encode($retornar);
                        //                   exit;
                        //                }
                        //             }
                        //          }
                        //       }

                        //    Verifica se a variante está presente na coluna cl_variacao de algum produto
                        $query = "SELECT cl_id, cl_variacao FROM tb_produtos WHERE FIND_IN_SET('$variante_id', cl_variacao)";
                        $result = mysqli_query($conecta, $query);

                        if (mysqli_num_rows($result) > 0) {
                           while ($produto = mysqli_fetch_assoc($result)) {
                              $produto_id = $produto['cl_id'];
                              $variacao = $produto['cl_variacao'];

                              // Remove o ID da variante da string cl_variacao
                              $novas_variacoes = array_diff(explode(',', $variacao), array($variante_id));
                              $nova_variacao_str = implode(',', $novas_variacoes);

                              // Faz o update na tabela tb_produtos
                              $update_query = "UPDATE tb_produtos SET cl_variacao = '$nova_variacao_str',cl_status_ativo='NAO',cl_codigo_nf_pai='' WHERE cl_id = '$produto_id'";
                              $update = mysqli_query($conecta, $update_query);

                              if (!$update) {
                                 $erro = true;
                                 break;
                              }
                           }
                        }
                     }
                  }
               }
            }


            /*verificar se já existe a opção da variante */
            $existe_opcao_variante = consulta_tabela_query($conecta, "SELECT * FROM tb_variante_produto WHERE cl_descricao ='$opcao_descricao' and cl_produto_pai_codigo_nf='$codigo_nf'", 'cl_id');
            if (!empty($existe_opcao_variante)) { //eXISTE A OPÇÃO DE VARIANTE
               foreach ($variantes_opcao as $variante) {
                  if (!empty($variante)) {
                     $codigo_variante_nf = consulta_tabela_query($conecta, "SELECT * FROM tb_variante_produto WHERE cl_valor ='$variante' and cl_produto_pai_codigo_nf='$codigo_nf'", 'cl_variante_nf');
                     if (empty($codigo_variante_nf)) { //Verificar se já existe a variante, se não existir, inserir a variante com a mesmma ordem
                        $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_variante_produto where cl_id ='$existe_opcao_variante' ");
                        if ($resultados) {
                           foreach ($resultados as $linha) {
                              $nome_opcao = ($linha['cl_descricao']);
                              $variante_nf = ($linha['cl_variante_nf']);
                              $ordem_opcao = ($linha['cl_ordem_opcao']);
                              $opcao_exibicao = ($linha['cl_tipo']);
                           }
                        }

                        //  $codigo_produto_nf_novo = md5(uniqid(time())); //gerar um novo codigo para nf
                        // $dados = array('codigo_nf' => $codigo_nf, 'codigo_nf_novo' => $codigo_produto_nf_novo);
                        // $produto_id_variante = insert_produto_variante($dados); //INSERIR O PRODUTO NO ESTOQUE

                        // if ($produto_id_variante != false) {
                        /*Inserir uma nova variante para uma opção já existente */
                        $query = "INSERT INTO `tb_variante_produto` (`cl_descricao`, `cl_tipo`, `cl_valor`, 
            `cl_variante_nf`, `cl_produto_pai_codigo_nf`,`cl_ordem_opcao`, `cl_incluir_titulo`  ) 
            VALUES ('$nome_opcao', '$opcao_exibicao', '$variante', '$variante_nf', '$codigo_nf', '$ordem_opcao', '$incluir_titulo' ) ";
                        $inserir_variante = mysqli_query($conecta, $query);
                        if (!$inserir_variante) {
                           $erro = true;
                           break;
                        } else {
                           $mensagem = utf8_decode("Adicionou uma nova variação $nome_opcao: $variante para o produto $descricao_produto");
                           registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                        }
                        //  }
                     }
                  }
               }
            }

            //realizar o update na coluna cl_incluir_titulo para concatenar o valor da variante no titutlo do produto
            $query = "UPDATE tb_variante_produto set 
               cl_incluir_titulo ='$incluir_titulo' where cl_descricao ='$opcao_descricao' and cl_produto_pai_codigo_nf='$codigo_nf' ";
            $update = mysqli_query($conecta, $query);


            // //Deletar todos os produtos variantes
            // $query = "DELETE FROM tb_produtos where cl_codigo_nf_pai ='$codigo_nf'"; //DELETAR O PRODUTO
            // $delete = mysqli_query($conecta, $query);
            // if (!$delete) {
            //    $retornar["dados"] =  array("sucesso" => false, "title" => "Erro ao remover os produtos");
            //    echo json_encode($retornar);
            //    exit;
            // }

            // Função para gerar todas as combinações possíveis de variações
            function gerar_combinacoes($variações)
            {
               $combinacoes = [[]];
               foreach ($variações as $var) {
                  $temp = [];
                  foreach ($combinacoes as $comb) {
                     foreach ($var as $item) {
                        $temp[] = array_merge($comb, [$item]);
                     }
                  }
                  $combinacoes = $temp;
               }
               return $combinacoes;
            }

            // 1. Recuperar todas as variações existentes para o produto pai
            $query = "SELECT * FROM tb_variante_produto WHERE cl_produto_pai_codigo_nf = '$codigo_nf' ORDER BY cl_descricao, cl_ordem_opcao";
            $resultado = mysqli_query($conecta, $query);
            $variações = [];
            while ($row = mysqli_fetch_assoc($resultado)) {
               $variações[$row['cl_descricao']][] = $row['cl_id'];
            }

            // 2. Gerar todas as combinações possíveis
            $combinacoes = gerar_combinacoes(array_values($variações));

            // 3. Inserir cada combinação na tabela `tb_produtos`
            foreach ($combinacoes as $combinacao) {
               $combinacao_ids = implode(',', $combinacao);
               $codigo_produto_nf_novo = md5(uniqid(time())); // gerar um novo código para o produto

               $dados = array(
                  'codigo_nf' => $codigo_nf,
                  'codigo_nf_novo' => $codigo_produto_nf_novo,
                  'variacao' => $combinacao_ids
               );

               $valida_variacao = consulta_tabela_query($conecta, "SELECT * FROM tb_produtos where cl_variacao ='$combinacao_ids' and cl_codigo_nf_pai ='$codigo_nf' ", 'cl_id'); //verificar se já existe o produto com a mesma variação
               if (empty($valida_variacao)) {
                  $produto_id_variante = insert_produto_variante($dados); //INSERIR O PRODUTO NO ESTOQUE
                  if ($produto_id_variante === false) {
                     $erro = true;
                     break;
                  }
               }
            }

            if ($erro == false) {
               $retornar["dados"] = array("sucesso" => true, "title" => "Opção alterada com sucesso");
               $mensagem = utf8_decode("Usúario $nome_usuario_logado alterou a opção de variantes do produto de código $produto_id");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
               mysqli_commit($conecta);
            } else {
               mysqli_rollback($conecta);
               $retornar["dados"] = array("sucesso" => false, "title" => "Erro, entre em contato com o suporte");
               $mensagem = utf8_decode("Tentativa sem sucesso de alterar opção de variantes do produto de código $produto_id");
               registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            }
         }
      }
   }



   if ($acao == "show_opcao") {
      $opcao_id = $_POST['opcao_id'];
      $codigo_nf = $_POST['codigo_nf'];
      $opcao = (consulta_tabela($conecta, 'tb_variante_produto', 'cl_id', $opcao_id, 'cl_descricao'));
      $query = "SELECT * from tb_variante_produto where cl_descricao = '$opcao' and cl_produto_pai_codigo_nf = '$codigo_nf' ";
      $consultar = mysqli_query($conecta, $query);
      $variantes_opcao_str = "";
      while ($linha = mysqli_fetch_assoc($consultar)) {
         $nome_opcao = utf8_encode($linha['cl_descricao']);
         $variantes_opcao = utf8_encode($linha['cl_valor']);
         $incluir_titulo = utf8_encode($linha['cl_incluir_titulo']);

         $variantes_opcao = $variantes_opcao . ", ";
         $variantes_opcao_str = $variantes_opcao_str . $variantes_opcao;
      }


      $informacao = array(
         "nome_opcao" => $nome_opcao,
         "variantes_opcao" => $variantes_opcao_str,
         "incluir_titulo" => $incluir_titulo
      );

      $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
   }



   //    if ($acao == "opcao_produto") { //criar variantes de produtos
   //       // Iniciar uma transação MySQL
   //       mysqli_begin_transaction($conecta);

   //       foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
   //          ${$name} = utf8_decode($value);
   //          // Remover aspas simples usando str_replace
   //          ${$name} = str_replace("'", "", ${$name});
   //       }
   //       $count_variante = 0;
   //       $check_variante_inserido = false;
   //       $erro = false;
   //       $qtd_existente = 0;
   //       if (empty($codigo_nf)) {
   //          $retornar["dados"] =  array("sucesso" => false, "title" => "Produto pai não identificado, favor, refaça a operação");
   //       } elseif (empty($nome_opcao)) {
   //          $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("nome opção"));
   //       } elseif (empty($variantes_opcao)) {
   //          $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("variantes"));
   //       } else {
   //          $produto_id = consulta_tabela($conecta, 'tb_produtos', 'cl_codigo', $codigo_nf, 'cl_id');
   //          // Dividir a string em um array, removendo espaços em branco ao redor dos títulos
   //          $variantes_opcao = array_map('trim', explode(',', $variantes_opcao));
   //          $qtd_variantes_opcao = count($variantes_opcao);

   //          // Loop foreach para processar cada título
   //          $qtd_opcao = consulta_tabela_query($conecta, "SELECT count(*) as qtd FROM tb_variante_produto WHERE cl_descricao='$nome_opcao'
   //           AND cl_produto_pai_codigo_nf='$codigo_nf'", 'qtd'); //consultar se a opção de variação já existe

   //          if ($qtd_opcao == 0) { //verificar se a opção já existe na tabela, se a opção não existir na tabela
   //             $query = "SELECT * FROM tb_variante_produto WHERE cl_produto_pai_codigo_nf='$codigo_nf' group by cl_produto_nf,cl_descricao ";
   //             $consulta = mysqli_query($conecta, $query);
   //             $qtd_consulta = mysqli_num_rows($consulta);
   //             if ($qtd_consulta > 0) { //inserir apenas as variantes
   //                while ($linha = mysqli_fetch_assoc($consulta)) {
   //                   $id_pai_variante = $linha['cl_id'];
   //                   $produto_nf = $linha['cl_produto_nf']; //codigo do produto filho já existe
   //                   $descricao_filho = $linha['cl_descricao']; //codigo do produto filho já existe

   //                   foreach ($variantes_opcao as $variante) {
   //                      // Verificar se a variante já existe
   //                      $check_query = "SELECT * FROM tb_variante_produto WHERE cl_descricao='$nome_opcao' AND cl_valor='$variante' AND cl_produto_nf='$produto_nf'";
   //                      $check_result = mysqli_query($conecta, $check_query);
   //                      if (mysqli_num_rows($check_result) == 0) {
   //                         // Inserir detalhes variação
   //                         $query = "INSERT INTO `tb_variante_produto` (`cl_descricao`, `cl_tipo`, `cl_valor`, 
   //                       `cl_produto_nf`, `cl_produto_pai_codigo_nf`,`cl_pai_id_variante`) VALUES ('$nome_opcao', '$opcao_exibicao', '$variante', '$produto_nf', '$codigo_nf','$id_pai_variante' )";
   //                         $inserir_variante = mysqli_query($conecta, $query);
   //                         if (!$inserir_variante) {
   //                            $erro = true;
   //                            break;
   //                         }
   //                      }
   //                   }
   //                }
   //             }
   //          }

   //          foreach ($variantes_opcao as $variante) {
   //             $codigo_nf_novo = md5(uniqid(time())); //gerar um novo codigo para nf

   //             // Verificar se a variante já existe
   //             $check_query = "SELECT * FROM tb_variante_produto WHERE cl_descricao='$nome_opcao' AND cl_valor='$variante' AND cl_produto_pai_codigo_nf='$codigo_nf'";
   //             $check_result = mysqli_query($conecta, $check_query);
   //             if (mysqli_num_rows($check_result) == 0) { //variação não existente na tabela
   //                $querys = "INSERT INTO `tb_produtos` (
   //                `cl_codigo`,
   //                `cl_data_cadastro`,
   //                `cl_descricao`,
   //                `cl_tamanho`,
   //                `cl_localizacao`,
   //                `cl_referencia`,
   //                `cl_equivalencia`,
   //                `cl_observacao`,
   //                `cl_codigo_barra`,
   //                `cl_preco_custo`,
   //                `cl_preco_venda`,
   //                `cl_preco_sugerido_venda`,
   //                `cl_preco_promocao`,
   //                `cl_data_valida_promocao`,
   //                `cl_data_validade`,
   //                `cl_peso_produto`,
   //                `cl_ult_preco_compra`,
   //                `cl_desconto_maximo`,
   //                `cl_margem_lucro`,
   //                `cl_cest`,
   //                `cl_ncm`,
   //                `cl_cst_icms`,
   //                `cl_cst_pis_s`,
   //                `cl_cst_pis_e`,
   //                `cl_cst_cofins_s`,
   //                `cl_cst_cofins_e`,
   //                `cl_estoque_minimo`,
   //                `cl_estoque_maximo`,
   //                `cl_cfop_interno`,
   //                `cl_cfop_externo`,
   //                `cl_fabricante_id`,
   //                `cl_fabricante`,
   //                `cl_grupo_id`,
   //                `cl_und_id`,
   //                `cl_tipo_id`,
   //                `cl_status_ativo`,
   //                `cl_descricao_delivery`,
   //                `cl_descricao_extendida_delivery`,
   //                `cl_qtd_adicional_obrigatorio_delivery`,
   //                `cl_status_adicional_obrigatorio_delivery`,
   //                `cl_img_produto`,
   //                `cl_min_produto_delivery`,
   //                `cl_lancamento`,
   //                `cl_gtin`,
   //                `cl_tipo_ecommerce`,
   //                `cl_condicao`,
   //                `cl_destaque`,
   //                `cl_fixo`,
   //                `cl_codigo_nf_pai`
   //            )
   //            SELECT
   //                '$codigo_nf_novo',
   //                '$data_lancamento',
   //                `cl_descricao`,
   //                `cl_tamanho`,
   //                `cl_localizacao`,
   //                `cl_referencia`,
   //                `cl_equivalencia`,
   //                `cl_observacao`,
   //                `cl_codigo_barra`,
   //                `cl_preco_custo`,
   //                `cl_preco_venda`,
   //                `cl_preco_sugerido_venda`,
   //                `cl_preco_promocao`,
   //                `cl_data_valida_promocao`,
   //                `cl_data_validade`,
   //                `cl_peso_produto`,
   //                `cl_ult_preco_compra`,
   //                `cl_desconto_maximo`,
   //                `cl_margem_lucro`,
   //                `cl_cest`,
   //                `cl_ncm`,
   //                `cl_cst_icms`,
   //                `cl_cst_pis_s`,
   //                `cl_cst_pis_e`,
   //                `cl_cst_cofins_s`,
   //                `cl_cst_cofins_e`,
   //                `cl_estoque_minimo`,
   //                `cl_estoque_maximo`,
   //                `cl_cfop_interno`,
   //                `cl_cfop_externo`,
   //                `cl_fabricante_id`,
   //                `cl_fabricante`,
   //                `cl_grupo_id`,
   //                `cl_und_id`,
   //                '8',/*grupo variação */
   //                `cl_status_ativo`,
   //                `cl_descricao_delivery`,
   //                `cl_descricao_extendida_delivery`,
   //                `cl_qtd_adicional_obrigatorio_delivery`,
   //                `cl_status_adicional_obrigatorio_delivery`,
   //                `cl_img_produto`,
   //                `cl_min_produto_delivery`,
   //                `cl_lancamento`,
   //                `cl_gtin`,
   //                `cl_tipo_ecommerce`,
   //                `cl_condicao`,
   //                `cl_destaque`,
   //                `cl_fixo`,
   //                '$codigo_nf'
   //            FROM
   //                `tb_produtos`
   //            WHERE
   //                `cl_codigo` = '$codigo_nf' ";
   //                $inserir_produto = mysqli_query($conecta, $querys);
   //                if (!$inserir_produto) {
   //                   $erro = true;
   //                   break;
   //                }

   //                //atualizar o a coluna cl_produto_pai para 1
   //                $produto_variante_id = mysqli_insert_id($conecta);
   //                update_registro($conecta, 'tb_produtos', 'cl_codigo', $codigo_nf, '', '', 'cl_produto_pai', 1); //definir o produto como pai


   //                /*inserir uma nova variação ou opção, se caso o usuário for adicionar pela priemira vez uma opção de variação o trecho de código é utilizado*/
   //                $query = "INSERT INTO `tb_variante_produto` (`cl_descricao`, `cl_tipo`, `cl_valor`, 
   //         `cl_produto_nf`, `cl_produto_pai_codigo_nf`) VALUES ('$nome_opcao', '$opcao_exibicao', '$variante', '$codigo_nf_novo', '$codigo_nf') ";
   //                $inserir_variante = mysqli_query($conecta, $query);
   //                if (!$inserir_variante) {
   //                   $erro = true;
   //                   break;
   //                } else {
   //                   $id_pai_variante_primeira_vez = mysqli_insert_id($conecta);
   //                }


   //                $query = "SELECT * FROM tb_variante_produto WHERE cl_produto_pai_codigo_nf='$codigo_nf' and cl_descricao !='$nome_opcao' group by cl_valor ";
   //                $consulta = mysqli_query($conecta, $query);
   //                $qtd_consulta = mysqli_num_rows($consulta);
   //                if ($qtd_consulta > 0) { //Verificar os tipos de opção existem, se caso o usuário for adicionar pela priemira vez uma opção de variação o trecho de código não é utilizado
   //                   while ($linha = mysqli_fetch_assoc($consulta)) {
   //                      $id_pai_variante_existente = $linha['cl_id'];
   //                      $valor_filho = $linha['cl_valor']; //codigo do produto filho já existe
   //                      $descricao_filho = $linha['cl_descricao']; //codigo do produto filho já existe

   //                      $id_pai_variante = $id_pai_variante_primeira_vez != $id_pai_variante_existente ? $id_pai_variante_primeira_vez : $id_pai_variante_existente;

   //                      /*inserir detalhes variação */
   //                      $query = "INSERT INTO `tb_variante_produto` (`cl_descricao`, `cl_tipo`, `cl_valor`, 
   //                         `cl_produto_nf`, `cl_produto_pai_codigo_nf`, `cl_pai_id_variante` ) 
   //                         VALUES ('$descricao_filho', '$opcao_exibicao', '$valor_filho', '$codigo_nf_novo', '$codigo_nf', '$id_pai_variante' ) ";
   //                      $inserir_variante = mysqli_query($conecta, $query);
   //                      if (!$inserir_variante) {
   //                         $erro = true;
   //                         break;
   //                      }
   //                   }
   //                }
   //             }
   //          }

   //          if ($erro == false) {
   //             $retornar["dados"] = array("sucesso" => true, "title" => "Adicionou novas variantes ao produto de código $qtd_existente ");
   //             $mensagem = utf8_decode("Usúario $nome_usuario_logado novas variantes ao produto de código $produto_id");
   //             registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
   //             mysqli_commit($conecta);
   //          } else {
   //             mysqli_rollback($conecta);
   //             $retornar["dados"] = array("sucesso" => false, "title" => "Erro, entre em contato com o suporte");
   //             $mensagem = utf8_decode("Tentativa sem sucesso de adicioanr variantes ao produto $produto_id ");
   //             registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
   //          }
   //       }
   //    }

   echo json_encode($retornar);
}


if (isset($_POST['formulario_produto_ecommerce'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";
   $retornar = array();
   $acao = $_POST['acao'];

   $alterar_preco_venda = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "25");
   $assumir_peso_und = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "65"); ///assumnir peso da unidade 

   $ncm_obrigatorio = verficar_paramentro($conecta, "tb_parametros", "cl_id", "13");
   $serie_ajst = verifcar_descricao_serie($conecta, 2);

   $id_user_logado = verifica_sessao_usuario();
   $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $id_user_logado, "cl_usuario");
   $altera_estoque = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '104', 'cl_valor'); //ALTERAR ESTOQUE PELA TELA DE EDITAR PRODUTO
   $altera_preco = consulta_tabela($conecta, 'tb_parametros', 'cl_id', '25', 'cl_valor');

   if ($acao == "show") {
      $id_produto = $_POST['produto_id'];
      $select = "SELECT * from tb_produtos where cl_id = $id_produto";
      $consultar_produtos = mysqli_query($conecta, $select);
      $linha = mysqli_fetch_assoc($consultar_produtos);
      $codigo_produto_b = ($linha['cl_codigo']);
      $descricao_b = utf8_encode($linha['cl_descricao']);
      $descricao_completa = utf8_encode($linha['cl_descricao_extendida_delivery']);

      $referencia_b = utf8_encode($linha['cl_referencia']);
      $equivalencia_b = utf8_encode($linha['cl_equivalencia']);
      $codigo_barras_b = ($linha['cl_codigo_barra']);
      $grupo_id_b = ($linha['cl_grupo_id']);
      $fabricante_b = utf8_encode($linha['cl_fabricante']);
      $tipo_b = ($linha['cl_tipo_id']);
      $estoque_b = ($linha['cl_estoque']);
      $est_minimo_b = ($linha['cl_estoque_minimo']);
      $est_max_b = ($linha['cl_estoque_maximo']);
      $local_b = utf8_encode($linha['cl_localizacao']);
      $tamanho_b = utf8_encode($linha['cl_tamanho']);
      $und_b = ($linha['cl_und_id']);
      $peso_produto = ($linha['cl_peso_produto']);
      $status_ativo_b = ($linha['cl_status_ativo']);
      $preco_venda_b = ($linha['cl_preco_venda']);
      $preco_custo_b = ($linha['cl_preco_custo']);
      $margem_b = ($linha['cl_margem_lucro']);
      $preco_promocao_b = ($linha['cl_preco_promocao']);
      $desconto_maximo_b = ($linha['cl_desconto_maximo']);
      $ult_preco_compra_b = ($linha['cl_ult_preco_compra']);
      $cest_b = ($linha['cl_cest']);
      $ncm_b = ($linha['cl_ncm']);
      $cst_icms_b = ($linha['cl_cst_icms']);
      $pis_s_b = ($linha['cl_cst_pis_s']);
      $pis_e_b = ($linha['cl_cst_pis_e']);
      $cofins_s_b = ($linha['cl_cst_cofins_s']);
      $cofins_e_b = ($linha['cl_cst_cofins_e']);

      $observacao_b = utf8_encode($linha['cl_observacao']);
      $condicao_b = utf8_encode($linha['cl_condicao']);
      $destaque_b = utf8_encode($linha['cl_destaque']);

      $data_valida_promocao =  ($linha['cl_data_valida_promocao']);
      $data_validade =  ($linha['cl_data_validade']);

      /*delivery */
      $descricao_delivery = utf8_encode($linha['cl_descricao_delivery']);
      $descricao_ext_delivery = utf8_encode($linha['cl_descricao_extendida_delivery']);
      $img_produto = ($linha['cl_img_produto']);

      $lancamento = ($linha['cl_lancamento']);
      $fixo = ($linha['cl_fixo']);
      $tipo_ecommerce = ($linha['cl_tipo_ecommerce']);

      $und_id = ($linha['cl_und_id']);
      $tipoProduto = ($linha['cl_tipo_id']);

      $informacao = array(
         "codigo_nf" => $codigo_produto_b,
         "descricao" => $descricao_b,
         "descricao_completa" => $descricao_completa,
         "referencia" => $referencia_b,
         "equivalencia" => $equivalencia_b,
         "codigo_barras" => $codigo_barras_b,
         "grupo_id" => $grupo_id_b,
         "fabricante" => $fabricante_b,
         "tipo" => $tipo_b,
         "estoque" => $estoque_b,
         "est_minimo" => $est_minimo_b,
         "est_maximo" => $est_max_b,
         "local" => $local_b,
         "peso_produto" => $peso_produto,
         "tamanho" => $tamanho_b,
         "und" => $und_b,
         "status_ativo" => $status_ativo_b,
         "preco_venda" => $preco_venda_b,
         "preco_custo" => $preco_custo_b,
         "margem" => $margem_b,
         "preco_promocao" => $preco_promocao_b,
         "desconto_maximo" => $desconto_maximo_b,
         "ult_preco_compra" => $ult_preco_compra_b,
         "cest" => $cest_b,
         "ncm" => $ncm_b,
         "cst_icms" => $cst_icms_b,
         "pis_s" => $pis_s_b,
         "pis_e" => $pis_e_b,
         "cofins_s" => $cofins_s_b,
         "cofins_e" => $cofins_e_b,
         "observacao" => $observacao_b,
         "condicao" => $condicao_b,
         "destaque" => $destaque_b,
         "data_valida_promocao" => $data_valida_promocao,
         "data_validade" => $data_validade,
         "descricao_delivery" => $descricao_delivery,
         "img_produto" => $img_produto,
         "descricao_ext_delivery" => $descricao_ext_delivery,
         "lancamento" => $lancamento,
         "tipoProduto" => $tipoProduto,
         "tipo_ecommerce" => $tipo_ecommerce,
         "und_id" => $und_id,
         "fixo" => $fixo,
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
      if (isset($_POST['lancamento'])) {
         $lancamento = 'SIM';
      } else {
         $lancamento = 'NAO';
      }
      if (isset($_POST['status'])) {
         $status = 'SIM';
      } else {
         $status = 'NAO';
      }
      if (isset($_POST['destaque'])) {
         $destaque = 'SIM';
      } else {
         $destaque = 'NAO';
      }
      if (isset($_POST['fixo'])) {
         $fixo = 'SIM';
      } else {
         $fixo = 'NAO';
      }



      if ($descricao == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("titulo"));
      } elseif (empty($referencia)) {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("referencia"));
      } elseif ($grupo_estoque == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("categoria"));
      } elseif ($tipoProduto == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("tipo"));
      } elseif ($ncm == "" and $ncm_obrigatorio == "S") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("ncm"));
      } elseif ($unidade_md == "0") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("und"));
      } elseif ($peso_produto == "" and $assumir_peso_und != "S") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("peso do produto"));
      } elseif ($prc_promocao > $prc_venda) {
         $retornar["dados"] =  array("sucesso" => false, "title" => "Preço promoção não pode ser maior do que o preço de venda, favor, verifique ");
      } else {

         if ($prc_custo != "") {
            if (verificaVirgula($prc_custo)) { //verificar se tem virgula
               $prc_custo = formatDecimal($prc_custo); // formatar virgula para ponto
            }
         }
         if ($prc_venda != "") {
            if (verificaVirgula($prc_venda)) { //verificar se tem virgula
               $prc_venda = formatDecimal($prc_venda); // formatar virgula para ponto
            }
         }
         if ($margem_lucro != "") {
            if (verificaVirgula($margem_lucro)) { //verificar se tem virgula
               $margem_lucro = formatDecimal($margem_lucro); // formatar virgula para ponto
            }
         }
         if ($prc_promocao != "") {
            if (verificaVirgula($prc_promocao)) { //verificar se tem virgula
               $prc_promocao = formatDecimal($prc_promocao); // formatar virgula para ponto
            }
         }

         if ($estoque != "") {
            if (verificaVirgula($estoque)) { //verificar se tem virgula
               $estoque = formatDecimal($estoque); // formatar virgula para ponto
            }
         }
         if ($peso_produto != "") {
            if (verificaVirgula($peso_produto)) { //verificar se tem virgula
               $peso_produto = formatDecimal($peso_produto); // formatar virgula para ponto
            }
         }
         if ($cest != "") {
            if (verificaVirgula($cest)) { //verificar se tem virgula
               $cest = formatDecimal($cest); // formatar virgula para ponto
            }
         }
         if ($ncm != "") {
            if (verificaVirgula($ncm)) { //verificar se tem virgula
               $ncm = formatDecimal($ncm); // formatar virgula para ponto
            }
         }
         if ($cst_icms != "") {
            if (verificaVirgula($cst_icms)) { //verificar se tem virgula
               $cst_icms = formatDecimal($cst_icms); // formatar virgula para ponto
            }
         }
         if ($cst_pis_s != "") {
            if (verificaVirgula($cst_pis_s)) { //verificar se tem virgula
               $cst_pis_s = formatDecimal($cst_pis_s); // formatar virgula para ponto
            }
         }
         if ($cst_pis_e != "") {
            if (verificaVirgula($ncm)) { //verificar se tem virgula
               $ncm = formatDecimal($ncm); // formatar virgula para ponto
            }
         }
         if ($cst_cofins_s != "") {
            if (verificaVirgula($cst_cofins_s)) { //verificar se tem virgula
               $cst_cofins_s = formatDecimal($cst_cofins_s); // formatar virgula para ponto
            }
         }
         if ($cst_cofins_e != "") {
            if (verificaVirgula($cst_cofins_e)) { //verificar se tem virgula
               $cst_cofins_e = formatDecimal($cst_cofins_e); // formatar virgula para ponto
            }
         }
         if ($assumir_peso_und == 'S') { //se o parametro estiver setado como S O PESO VAI ASSUMIR O VALOR DA COLUNA cl_peso_kg da taela de unidade de medida
            $peso_produto = consulta_tabela($conecta, "tb_unidade_medida", 'cl_id', $unidade_md, "cl_peso_kg");
         }

         $descricao = strtoupper($descricao);
         $referencia = strtoupper($referencia);

         $inset = "INSERT INTO tb_produtos (cl_data_cadastro,cl_codigo,cl_descricao,cl_descricao_extendida_delivery,cl_referencia,cl_codigo_barra,cl_observacao,cl_preco_custo,cl_preco_venda,cl_estoque,
           cl_preco_promocao,cl_data_valida_promocao,cl_margem_lucro,cl_cest,cl_ncm,cl_cst_icms,cl_cst_pis_s,cl_cst_pis_e,cl_cst_cofins_s,cl_cst_cofins_e,cl_und_id,
           cl_grupo_id,cl_status_ativo,cl_lancamento,cl_tipo_id,cl_peso_produto,cl_tipo_ecommerce,cl_condicao,cl_destaque,cl_fabricante,cl_fixo )
            VALUES ('$data_lancamento','$codigo_nf','$descricao','$descricao_completa','$referencia','$codigo_barras','$observacao','$prc_custo','$prc_venda','$estoque',
            '$prc_promocao','$data_valida_promocao','$margem_lucro','$cest','$ncm','$cst_icms','$cst_pis_s','$cst_pis_e','$cst_cofins_s','$cst_cofins_e','$unidade_md',
           '$grupo_estoque','$status','$lancamento','$tipoProduto','$peso_produto','$tipo','$condicao','$destaque','$fabricante','$fixo' )";
         $operacao_inserir = mysqli_query($conecta, $inset);
         if ($operacao_inserir) {
            //pegar o id do ultimo produto cadastrado
            $id_produto_b = mysqli_insert_id($conecta);
            $retornar["dados"] = array("sucesso" => true, "title" => "Cadastro realizado com sucesso, código do produto $id_produto_b");

            //registrar no log
            $mensagem =  utf8_decode("Cadastrou o produto de código $id_produto_b");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            mysqli_commit($conecta);
         } else {
            mysqli_rollback($conecta);
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, entre em contato com o suporte");
            $mensagem =  utf8_decode("Tentativa sem sucesso de cadastrar o produto $descricao ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }
   if ($acao == "responder_duvida") {
      mysqli_begin_transaction($conecta);
      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = utf8_decode($value);
         // Remover aspas simples usando str_replace
         ${$name} = str_replace("'", "", ${$name});
      }
      $codigo_nf = consulta_tabela($conecta, 'tb_duvida_loja', 'cl_id', $pergunta_id, 'cl_codigo_nf');

      if (empty($codigo_nf)) {
         $retornar["dados"] = array("sucesso" => false, "title" => "Dúvida não identificada, favor, verifique");
      } elseif (empty($mensagem)) {
         $retornar["dados"] = array("sucesso" => false, "title" =>  mensagem_alerta_cadastro("resposta"));
      } else {
         $usuario_id = consulta_tabela($conecta, 'tb_duvida_loja', 'cl_id', $pergunta_id, 'cl_usuario_id');
         $produto_id = consulta_tabela($conecta, 'tb_duvida_loja', 'cl_id', $pergunta_id, 'cl_produto_id');
         $query = "INSERT INTO `tb_duvida_loja` (`cl_data`, `cl_codigo_nf`, 
         `cl_usuario_id`, `cl_origem_mensagem`, `cl_produto_id`, `cl_mensagem`, `cl_respondido`) 
         VALUES ('$data', '$codigo_nf', '$usuario_id', '1', '$produto_id', '$mensagem', '0')";
         $insert = mysqli_query($conecta, $query);
         if ($insert) {
            update_registro($conecta, 'tb_duvida_loja', 'cl_id', $pergunta_id, '', '', 'cl_respondido', '1');

            $retornar["dados"] = array("sucesso" => true, "title" => "Resposta registrada com sucesso");
            $mensagem =  utf8_decode("Registro a dúvida de código $pergunta_id - Ecommerce ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            mysqli_commit($conecta);
         } else {
            mysqli_rollback($conecta);
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, entre em contato com o suporte");
            $mensagem =  utf8_decode("Tentativa sem sucesso de registrar a resposta da dpuvida de código $pergunta_id - Ecommerce  ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }
   if ($acao == "update") {
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);
      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = utf8_decode($value);
         // Remover aspas simples usando str_replace
         ${$name} = str_replace("'", "", ${$name});
      }

      if (isset($_POST['lancamento'])) {
         $lancamento = 'SIM';
      } else {
         $lancamento = 'NAO';
      }
      if (isset($_POST['status'])) {
         $status = 'SIM';
      } else {
         $status = 'NAO';
      }
      if (isset($_POST['destaque'])) {
         $destaque = 'SIM';
      } else {
         $destaque = 'NAO';
      }
      if (isset($_POST['fixo'])) {
         $fixo = 'SIM';
      } else {
         $fixo = 'NAO';
      }


      if ($descricao == "") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("titulo"));
      } elseif (empty($referencia)) {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("referência"));
      } elseif ($grupo_estoque == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("categoria"));
      } elseif ($tipoProduto == "0") {
         $retornar["dados"] = array("sucesso" => false, "title" => mensagem_alerta_cadastro("tipo"));
      } elseif ($ncm == "" and $ncm_obrigatorio == "S") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("ncm"));
      } elseif ($unidade_md == "") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("und"));
      } elseif ($peso_produto == "" and $assumir_peso_und != "S") {
         $retornar["dados"] =  array("sucesso" => false, "title" => mensagem_alerta_cadastro("peso"));
      } elseif (($altera_preco == "S") and isset($prc_custo) and ($prc_promocao > $prc_venda)) {
         $retornar["dados"] =  array("sucesso" => false, "title" => "Preço promoção não pode ser maior do que o preço de venda, favor, verifique ");
      } elseif ($tipoProduto == "8" && $status == "NAO") {
         $retornar["dados"] =  array("sucesso" => false, "title" => "Não é possivel inativar uma variante, recomendo zerar o seu estoque");
      } else {
         if (isset($prc_custo) and $prc_custo != "") {
            if (verificaVirgula($prc_custo)) { //verificar se tem virgula
               $prc_custo = formatDecimal($prc_custo); // formatar virgula para ponto
            }
         }
         if (isset($prc_venda) and $prc_venda != "") {
            if (verificaVirgula($prc_venda)) { //verificar se tem virgula
               $prc_venda = formatDecimal($prc_venda); // formatar virgula para ponto
            }
         }
         if ($margem_lucro != "") {
            if (verificaVirgula($margem_lucro)) { //verificar se tem virgula
               $margem_lucro = formatDecimal($margem_lucro); // formatar virgula para ponto
            }
         }
         if (isset($prc_promocao) and $prc_promocao != "") {
            if (verificaVirgula($prc_promocao)) { //verificar se tem virgula
               $prc_promocao = formatDecimal($prc_promocao); // formatar virgula para ponto
            }
         }

         if (isset($estoque) and $estoque != "") {
            if (verificaVirgula($estoque)) { //verificar se tem virgula
               $estoque = formatDecimal($estoque); // formatar virgula para ponto
            }
         }
         if ($peso_produto != "") {
            if (verificaVirgula($peso_produto)) { //verificar se tem virgula
               $peso_produto = formatDecimal($peso_produto); // formatar virgula para ponto
            }
         }
         if ($cest != "") {
            if (verificaVirgula($cest)) { //verificar se tem virgula
               $cest = formatDecimal($cest); // formatar virgula para ponto
            }
         }
         if ($ncm != "") {
            if (verificaVirgula($ncm)) { //verificar se tem virgula
               $ncm = formatDecimal($ncm); // formatar virgula para ponto
            }
         }
         if ($cst_icms != "") {
            if (verificaVirgula($cst_icms)) { //verificar se tem virgula
               $cst_icms = formatDecimal($cst_icms); // formatar virgula para ponto
            }
         }
         if ($cst_pis_s != "") {
            if (verificaVirgula($cst_pis_s)) { //verificar se tem virgula
               $cst_pis_s = formatDecimal($cst_pis_s); // formatar virgula para ponto
            }
         }
         if ($cst_pis_e != "") {
            if (verificaVirgula($ncm)) { //verificar se tem virgula
               $ncm = formatDecimal($ncm); // formatar virgula para ponto
            }
         }
         if ($cst_cofins_s != "") {
            if (verificaVirgula($cst_cofins_s)) { //verificar se tem virgula
               $cst_cofins_s = formatDecimal($cst_cofins_s); // formatar virgula para ponto
            }
         }
         if ($cst_cofins_e != "") {
            if (verificaVirgula($cst_cofins_e)) { //verificar se tem virgula
               $cst_cofins_e = formatDecimal($cst_cofins_e); // formatar virgula para ponto
            }
         }

         if ($assumir_peso_und == 'S' and $peso_produto == '') { //se o parametro estiver setado como S O PESO VAI ASSUMIR O VALOR DA COLUNA cl_peso_kg da taela de unidade de medida
            $peso_produto = consulta_tabela($conecta, "tb_unidade_medida", 'cl_id', $unidade_md, "cl_peso_kg");
         }

         $descricao = strtoupper($descricao);
         $referencia = strtoupper($referencia);
         // Use prepared statements to prevent SQL injection
         $update = "UPDATE tb_produtos SET
cl_descricao = '$descricao',
cl_descricao_extendida_delivery = '$descricao_completa',
cl_referencia = '$referencia',
cl_codigo_barra = '$codigo_barras',

cl_observacao = '$observacao',";

         if ($altera_estoque == "S") {
            $update .= " cl_estoque = '$estoque', ";
         };
         if ($altera_preco == "S") {
            $update .= " 
            cl_data_valida_promocao='$data_valida_promocao',
            cl_preco_promocao = '$prc_promocao', 
            cl_preco_venda = '$prc_venda', 
            cl_preco_custo = '$prc_custo', ";
         };

         $update .= " cl_margem_lucro = '$margem_lucro',
cl_cest = '$cest',
cl_ncm = '$ncm',
cl_cst_icms = '$cst_icms',
cl_cst_pis_s = '$cst_pis_s',
cl_cst_pis_e = '$cst_pis_e',
cl_cst_cofins_s = '$cst_cofins_s',
cl_cst_cofins_e = '$cst_cofins_e',
cl_grupo_id = '$grupo_estoque',
cl_status_ativo = '$status',
cl_lancamento = '$lancamento',
cl_peso_produto = '$peso_produto',
cl_tipo_ecommerce = '$tipo',
cl_tipo_id = '$tipoProduto',
cl_und_id = '$unidade_md',
cl_condicao = '$condicao',
cl_destaque = '$destaque',
cl_fixo = '$fixo',
cl_fabricante = '$fabricante'
WHERE cl_id = '$id' ";

         $operacao_update = mysqli_query($conecta, $update);
         if ($operacao_update) {
            $retornar["dados"] = array("sucesso" => true, "title" => "Produto alterado com sucesso");
            $mensagem =  utf8_decode("Alterou o produto de código $id ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
            mysqli_commit($conecta);
         } else {
            mysqli_rollback($conecta);
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, não foi possivel realizar a ação, favor, verifique com o suporte");
            //registrar no log
            $mensagem =  utf8_decode("Usúario $nome_usuario_logado tentou alterar o produto de código $id sem sucesso, Erro");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }

   if ($acao == "delete_img") {
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);
      $imagem = $_POST['imagem'];

      $delete = "DELETE from tb_imagem_produto where cl_descricao = '$imagem' ";
      $operacao_delete = mysqli_query($conecta, $delete);
      if ($operacao_delete) {
         $retornar["dados"] = array("sucesso" => true, "title" => "Produto removido com sucesso");
         $imagem_existente = '../../../img/produto/' . $imagem . ".*";

         $lista_imagens = glob($imagem_existente);

         foreach ($lista_imagens as $imagem) {
            unlink($imagem); // Excluir a imagem existente
         }
         mysqli_commit($conecta);
      } else {
         mysqli_rollback($conecta);
         $retornar["dados"] = array("sucesso" => false, "title" => "Erro, não foi possivel realizar a ação, favor, verifique com o suporte");
      }
   }
   if ($acao == 'clonar') {
      // Iniciar uma transação MySQL
      mysqli_begin_transaction($conecta);
      $form_id = $_POST['form_id'];
      $codigo_nf_novo = md5(uniqid(time())); //gerar um novo codigo para nf

      if (empty($form_id)) {
         $retornar["dados"] = array("sucesso" => "false", "title" => "Produto não encontrado");
      } else {
         $insert = "INSERT INTO `tb_produtos` (
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
            `cl_fixo`
        )
        SELECT
            '$codigo_nf_novo',
            '$data_lancamento',
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
            `cl_fixo`
        FROM
            `tb_produtos`
        WHERE
            `cl_id` = '$form_id' ";
         $operacao_clonagem = mysqli_query($conecta, $insert);
         if ($operacao_clonagem) {
            $novo_id = mysqli_insert_id($conecta);
            $retornar["dados"] = array("sucesso" => true, "title" => "Produto clonado com sucesso, código do produto $novo_id");

            $mensagem = utf8_decode("Usúario $nome_usuario_logado gerou um novo produto de código $novo_id do resultado da clonagem do produto de código $form_id ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

            mysqli_commit($conecta);
         } else {
            mysqli_rollback($conecta);
            $retornar["dados"] = array("sucesso" => false, "title" => "Erro, entre em contato com o suporte");

            $mensagem = utf8_decode("Tentativa sem sucesso de clonar o produto $form_id ");
            registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
         }
      }
   }
   if ($acao == "delete_img_produto") {
      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = utf8_decode($value);
         ${$name} = str_replace("'", "", ${$name}); //remover aspas simples
      }

      $codigo_nf = (consulta_tabela($conecta, 'tb_imagem_produto', 'cl_id', $id, 'cl_codigo_nf'));
      $descricao_imagem = (consulta_tabela($conecta, 'tb_imagem_produto', 'cl_id', $id, 'cl_descricao'));
      $produto_id = (consulta_tabela($conecta, 'tb_produtos', 'cl_codigo', $codigo_nf, 'cl_id'));

      $query = "DELETE from tb_imagem_produto where cl_id = $id";
      $delete = mysqli_query($conecta, $query);
      if ($delete) {
         $retornar["dados"] =  array("sucesso" => true, "title" => "Imagem removida com sucesso");

         $imagem_existente = '../../../img/produto/' . $descricao_imagem;
         if (file_exists($imagem_existente)) {
            $lista_imagens = glob($imagem_existente);
            foreach ($lista_imagens as $imagem) {
               unlink($imagem); // Excluir a imagem existente
            }
         }

         $mensagem = utf8_decode("Removeu uma imagem do produto de código $produto_id");
         registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
      } else {
         $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
         $mensagem = utf8_decode("Tentativa sem sucesso de remover uma imagem do produto de código $produto_id");
         registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
      }
   }

   if ($acao == "ordernar_imagem_produto") { // ordernar baner

      // Decodifica os dados JSON recebidos
      $dados = json_decode($_POST['dados'], true);

      // Loop através dos dados
      foreach ($dados as $div) {
         $id = $div['id'];
         $nova_posicao = $div['position'];
         $nova_posicao = $nova_posicao + 1;

         update_registro($conecta, 'tb_imagem_produto', 'cl_id', $id, '', '', 'cl_ordem', $nova_posicao);
      }

      // $posicao_atual = consulta_tabela($conecta, 'tb_baner_delivery', 'cl_id', $id, 'cl_ordem'); //verificar a posição atual do baner que está sendo processado
      // $baner_alterado_ordem_id = consulta_tabela($conecta, 'tb_baner_delivery', 'cl_ordem', $nova_posicao, 'cl_id'); //baner que vai ser alterado a sua posição


      // update_registro($conecta, 'tb_baner_delivery', 'cl_id', $baner_alterado_ordem_id, '', '', 'cl_ordem', $posicao_atual);
      $retornar["dados"] =  array("sucesso" => true, "title" => "Baner alterado com sucesso");
   }
   /*marcadores */
   if ($acao == "create_marcador") {
      mysqli_begin_transaction($conecta);
      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = utf8_decode($value);
         // Remover aspas simples usando str_replace
         ${$name} = str_replace("'", "", ${$name});
      }
      $query = "INSERT INTO `tb_marcadores` (`cl_codigo_nf`, `cl_descricao`) VALUES ( '$codigo_nf', '$descricao')";
      $operacao_inserir = mysqli_query($conecta, $query);
      if ($operacao_inserir) {
         $retornar["dados"] = array("sucesso" => true, "title" => "Marcador adicionado com sucesso");
         $mensagem =  utf8_decode("Adicionou o marcador $descricao");

         mysqli_commit($conecta);
      } else {
         mysqli_rollback($conecta);
         $retornar["dados"] = array("sucesso" => false, "title" => "Erro, entre em contato com o suporte");
         $mensagem =  utf8_decode("Tentativa sem sucesso de adicionar o marcador $descricao");
         registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
      }
   }
   if ($acao == "remove_marcador") {
      mysqli_begin_transaction($conecta);
      foreach ($_POST as $name => $value) { //define os valores das variaveis e os nomes com refencia do name do input no formulario
         ${$name} = utf8_decode($value);
         // Remover aspas simples usando str_replace
         ${$name} = str_replace("'", "", ${$name});
      }
      $descricao = consulta_tabela($conecta, 'tb_marcadores', 'cl_id', $id, 'cl_descricao');

      $query = "DELETE FROM `tb_marcadores` WHERE `cl_id` = $id";
      $operacao_delete = mysqli_query($conecta, $query);
      if ($operacao_delete) {
         $retornar["dados"] = array("sucesso" => true, "title" => "Marcador removido com sucesso");
         $mensagem =  utf8_decode("Removeu o marcador $descricao");
         mysqli_commit($conecta);
      } else {
         mysqli_rollback($conecta);
         $retornar["dados"] = array("sucesso" => false, "title" => "Erro, entre em contato com o suporte");
         $mensagem =  utf8_decode("Tentativa sem sucesso de remover o marcador $descricao");
         registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
      }
   }
   echo json_encode($retornar);
}


if (isset($_GET['img_produtos'])) {
   include "../../../conexao/conexao.php";
   include "../../../funcao/funcao.php";
   $codigo_nf = isset($_GET['codigo_nf']) ? $_GET['codigo_nf'] : '';
}




//consultar grupo estoque
$select = "SELECT subgrup.cl_id,subgrup.cl_descricao,grp.cl_descricao 
as grupo from tb_subgrupo_estoque as subgrup inner join 
tb_grupo_estoque as grp on grp.cl_id = subgrup.cl_grupo_id ";
$consultar_subgrupo_estoque = mysqli_query($conecta, $select);

//consultar cfop
$select = "SELECT * from tb_cfop";
$consultar_cfop_interno = mysqli_query($conecta, $select);

//consultar cfop
$select = "SELECT * from tb_cfop";
$consultar_cfop_externo = mysqli_query($conecta, $select);


//consultar tipo produto
$select = "SELECT * from tb_tipo_produto";
$consultar_tipo_produto = mysqli_query($conecta, $select);


// //consultar tipo produto
// $select = "SELECT * from tb_fabricantes";
// $consultar_fabricantes = mysqli_query($conecta, $select);

//consultar unidade medida
$select = "SELECT * from tb_unidade_medida";
$consultar_und_medida = mysqli_query($conecta, $select);

//consultar cest
$select = "SELECT * from tb_cest";
$consultar_cest = mysqli_query($conecta, $select);

//consultar icms
$select = "SELECT * from tb_icms";
$consultar_icms = mysqli_query($conecta, $select);

//consultar pis
$select = "SELECT * from tb_pis";
$consultar_pis_s = mysqli_query($conecta, $select);
$consultar_pis_e = mysqli_query($conecta, $select);

//consultar cofins
$select = "SELECT * from tb_cofins";
$consultar_cofins_s = mysqli_query($conecta, $select);
$consultar_cofins_e = mysqli_query($conecta, $select);


if (isset($_GET['produto_delivery'])) { //Prroduto que estão incluidos na categoria adicional
   include "../../../funcao/funcao.php";
   $id_produto = $_GET['produto_id'];
   $qtd_max_obg = consulta_tabela($conecta, "tb_produtos", "cl_id", $id_produto, "cl_qtd_adicional_obrigatorio_delivery");

   // $select = "SELECT * from tb_produtos WHERE cl_id ='$id_produto'";//tipo produto adicinoal
   // $consultar_produto = mysqli_query($conecta, $select);
   // $linha = mysqli_fetch_assoc($consultar_produto);

   // $status_obg = $linha['cl_status_adicional_obrigatorio_delivery'];

   $select = "SELECT prd.cl_preco_promocao,prd.cl_data_valida_promocao, prd.cl_preco_venda,prd.cl_preco_promocao,prd.cl_data_valida_promocao, prd.cl_id,prd.cl_descricao,sub.cl_descricao as subgrupo from tb_produtos as prd inner join tb_subgrupo_estoque as sub
   on sub.cl_id = prd.cl_grupo_id WHERE cl_status_ativo ='SIM' 
  and cl_tipo_id ='5' order by prd.cl_grupo_id desc"; //tipo produto adicinoal
   $consultar_produto_adicional_delivery_obg = mysqli_query($conecta, $select);
   $qtd_consultar_produto_adicional_delivery_obg = mysqli_num_rows($consultar_produto_adicional_delivery_obg);
   $consultar_produto_adicional_delivery = mysqli_query($conecta, $select);

   $select = "SELECT * from tb_produtos WHERE  cl_status_ativo ='SIM' and cl_tipo_id ='2' order by cl_grupo_id desc"; //tipo produto delivery
   $consultar_produto_complemento_delivery = mysqli_query($conecta, $select);

   $select = "SELECT * from tb_produtos WHERE cl_id ='$id_produto'"; //tipo produto delivery
   $consultar_info_produto = mysqli_query($conecta, $select);
   $consultar = mysqli_query($conecta, $select);
   $linha = mysqli_fetch_assoc($consultar);
   $tempo_preparo = $linha['cl_min_produto_delivery'];
   $status_obg = $linha['cl_status_adicional_obrigatorio_delivery'];
   $img_produto = $linha['cl_img_produto'];
   $descricao_delivery = utf8_encode($linha['cl_descricao_delivery']);
   $descricao_extendida_delivery = utf8_encode($linha['cl_descricao_extendida_delivery']);

   $select = "SELECT * FROM tb_subgrupo_estoque ";
   $consulta_categoria = mysqli_query($conecta, $select);
}



//consultar informações para tabela
if ((isset($_GET['consultar_cest'])) or (isset($_GET['consultar_ncm']))) {
   include "../../../../conexao/conexao.php";
   include "../../../../funcao/funcao.php";
   $pesquisa = $_GET['conteudo_pesquisa'];
   $select = "SELECT * from tb_cest where cl_cest like '%{$pesquisa}%' or cl_ncm like '%{$pesquisa}%' or cl_descricao like '%{$pesquisa}%'  order by cl_id";
   $buscar_cest_ncm = mysqli_query($conecta, $select);
}
