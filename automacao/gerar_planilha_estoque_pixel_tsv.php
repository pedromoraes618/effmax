<?php
include "../conexao/conexao.php";
include "../funcao/funcao.php";

// Definindo o nome do arquivo TSV
$nome_arquivo = "produtos.tsv";

// Abrindo o arquivo para escrita
$arquivo = fopen($nome_arquivo, 'w');

// Obtendo o nome do e-commerce e da empresa
$nomeEcommerce = utf8_encode(consulta_tabela($conecta, 'tb_parametros', 'cl_id', '64', 'cl_valor'));
$nomeSite = utf8_encode(consulta_tabela($conecta, 'tb_empresa', 'cl_id', '1', 'cl_empresa'));
$empresa = consulta_tabela($conecta, 'tb_empresa', 'cl_id', '1', 'cl_empresa');

// Definindo as colunas do TSV
$colunas = [
    'id', 'title', 'description', 'availability', 'condition', 'price', 'link', 'image_link',
    'brand', 'google_product_category', 'fb_product_category', 'quantity_to_sell_on_facebook',
    'sale_price', 'sale_price_effective_date', 'item_group_id', 'gender', 'color', 'size',
    'age_group', 'material', 'pattern', 'shipping', 'shipping_weight', 'video[0].url', 'video[0].tag[0]', 'style[0]'
];

// Escrevendo as colunas no arquivo TSV
fwrite($arquivo, implode("\t", $colunas) . "\n");

// Consultando os produtos no banco de dados
$query = "SELECT prd.*, prd.cl_id as produtoid, prd.cl_descricao as produto, sub.cl_descricao as categoria, grup.cl_descricao as grupo
          FROM tb_produtos as prd 
          LEFT JOIN tb_subgrupo_estoque as sub ON sub.cl_id = prd.cl_grupo_id 
          LEFT JOIN tb_grupo_estoque as grup ON grup.cl_id = sub.cl_grupo_id
          WHERE prd.cl_status_ativo ='SIM' and prd.cl_id = '1'";
$resultados = consulta_linhas_tb_query($conecta, $query);

if ($resultados) {
    foreach ($resultados as $linha) {
        $productID = $linha['produtoid'];
        $codigo = $linha['cl_codigo'];
        $descricao = $linha['produto'];
        $valor_unitario = $linha['cl_preco_venda'];
        $categoria = $linha['categoria'];
        $referencia = $linha['cl_referencia'];
        $destaque = $linha['cl_destaque'];
        $condicao = $linha['cl_condicao'];
        $estoque = $linha['cl_estoque'];
        $grupo_id = $linha['cl_grupo_id'];
        $fabricante = $linha['cl_fabricante'];
        $preco_promocao = $linha['cl_preco_promocao'];
        $data_validade_promocao = $linha['cl_data_valida_promocao'];
        $grupo = $linha['grupo'];
        $estoque_tag = $estoque > 0 ? "in stock" : "out of stock";
        $condicao_tag = $condicao == "USADO" ? "used" : "new";
        $produto_descricao = $descricao . " - " . $referencia;

        $imagem_produto_default = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "34");
        $img_produto = consulta_tabela_query($conecta, "SELECT cl_descricao FROM tb_imagem_produto WHERE cl_codigo_nf ='$codigo' ORDER BY cl_ordem ASC LIMIT 1", 'cl_descricao');
        $diretorio_imagem = $img_produto == "" ? "$imagem_produto_default" : "img/produto/$img_produto";

        if ($data_validade_promocao >= date('Y-m-d') && $preco_promocao > 0) {
            $valor_unitario = $preco_promocao;
        }

        $url_produto = "$url_init/$nomeEcommerce/?product-details=$productID&$descricao";
        $url_produto_img = "$url_init/$nomeSite/$diretorio_imagem";
        $moeda = "BRL";

        // Montando os dados do produto para o TSV
        $dados_produto = [
            $productID,
            $descricao,
            $produto_descricao,
            $estoque_tag,
            $condicao_tag,
            number_format($valor_unitario, 2, '.', '') . " BRL",
            $url_produto,
            $url_produto_img,
            $fabricante,
            "Media > Music & Audio > Music Records",
            "",
            "",
            "",
            "",
            "$grupo-$categoria",
            "",
            "",
            "",
            "",
            "",
            "BR::Standard:10.0 BRL",
            "",
            "",
            "",
            ""
        ];

        // Escrevendo os dados do produto no arquivo TSV
        fwrite($arquivo, implode("\t", $dados_produto) . "\n");
    }
}

// Fechando o arquivo TSV
fclose($arquivo);

// Fechando a conexão com o banco de dados
$conecta->close();

echo "Arquivo TSV gerado com sucesso!";
?>
