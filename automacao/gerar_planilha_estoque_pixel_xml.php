<?php
include "../conexao/conexao.php";
include "../funcao/funcao.php";

// Obtendo o nome do e-commerce e da empresa
$nomeEcommerce = utf8_encode(consulta_tabela($conecta, 'tb_parametros', 'cl_id', '64', 'cl_valor'));
$nomeSite = utf8_encode(consulta_tabela($conecta, 'tb_empresa', 'cl_id', '1', 'cl_empresa'));

// Definindo o nome do arquivo XML
$nome_arquivo = "produtos.xml";

// Abrindo o arquivo para escrita
$arquivo = fopen($nome_arquivo, 'w');

// Escrevendo o cabeçalho do RSS
fwrite($arquivo, "<?xml version='1.0' encoding='UTF-8' ?>\n");
fwrite($arquivo, "<rss version='2.0'>\n");
fwrite($arquivo, "<channel>\n");
fwrite($arquivo, "<title>$nomeSite - Produtos</title>\n");
fwrite($arquivo, "<link>$url_init</link>\n");
fwrite($arquivo, "<description>Feed de produtos do $nomeSite</description>\n");

// Consultando os produtos no banco de dados
$query = "SELECT prd.*, prd.cl_id as produtoid, prd.cl_descricao as produto, sub.cl_descricao as categoria, grup.cl_descricao as grupo
          FROM tb_produtos as prd 
          LEFT JOIN tb_subgrupo_estoque as sub ON sub.cl_id = prd.cl_grupo_id 
          LEFT JOIN tb_grupo_estoque as grup ON grup.cl_id = sub.cl_grupo_id ";
$resultados = consulta_linhas_tb_query($conecta, $query);

if ($resultados) {
    foreach ($resultados as $linha) {
        $productID = $linha['produtoid'];
        $codigo = $linha['cl_codigo'];

        $titulo = htmlspecialchars(utf8_encode($linha['produto']), ENT_XML1, 'UTF-8');
        $valor_unitario = $linha['cl_preco_venda'];
        $categoria = htmlspecialchars(utf8_encode($linha['categoria']), ENT_XML1, 'UTF-8');
        $referencia = htmlspecialchars(utf8_encode($linha['cl_referencia']), ENT_XML1, 'UTF-8');
        $condicao = htmlspecialchars(utf8_encode($linha['cl_condicao']), ENT_XML1, 'UTF-8');
        $estoque = $linha['cl_estoque'];
        $fabricante = htmlspecialchars(utf8_encode($linha['cl_fabricante']), ENT_XML1, 'UTF-8');
        $preco_promocao = $linha['cl_preco_promocao'];
        $data_validade_promocao = $linha['cl_data_valida_promocao'];
        $status = $linha['cl_status_ativo'];
        $descricao = htmlspecialchars(utf8_encode($linha['cl_descricao_extendida_delivery']), ENT_XML1, 'UTF-8');
        $grupo = htmlspecialchars(utf8_encode($linha['grupo']), ENT_XML1, 'UTF-8');
        $estoque_tag = $estoque > 0 ? "in stock" : "out of stock";
        $condicao_tag = $condicao == "USADO" ? "used" : "new";
        $status_tag = $status == "SIM" ? 'active' : 'archived';

        $fb_product_category  = "146"; //midia e muscia
        $produto_titulo = $titulo . " - " . $referencia;
        // $descricao = $descricao != "<p><br></p>" ? $descricao : $titulo;
        $imagem_produto_default = verficar_paramentro($conecta, 'tb_parametros', "cl_id", "34");
        $img_produto_principal = consulta_tabela_query($conecta, "SELECT cl_descricao FROM tb_imagem_produto WHERE cl_codigo_nf ='$codigo' ORDER BY cl_ordem ASC LIMIT 1", 'cl_descricao');
        $imagen_produto_secundaria = array(); // Array para armazenar as descrições das imagens

        $query_img = consulta_linhas_tb_query($conecta, "SELECT cl_descricao FROM tb_imagem_produto WHERE cl_codigo_nf ='$codigo' ORDER BY cl_ordem ASC");
        if ($query_img) {
            foreach ($query_img as $linha) {
                $descricao_imagem = utf8_encode($linha['cl_descricao']);
                $imagen_produto_secundaria[] = "$url_init/$nomeSite/img/produto/$descricao_imagem"; // imagens secundarias
            }
        }

        if (count($imagen_produto_secundaria) === 0) {
            $img_produto_principal = "$url_init/$nomeSite/$imagem_produto_default";
        } else {
            $img_produto_principal = "$url_init/$nomeSite//img/produto/$img_produto_principal";
        }

        $moeda = "BRL";
        if ($data_validade_promocao >= $data_lancamento and $preco_promocao > 0) {
            $data_inicio_promocao = date('Y-m-d\TH:i:sO', strtotime($data_lancamento));
            $data_fim_promocao = date('Y-m-d\TH:i:sO', strtotime($data_validade_promocao));
            $sale_price_effective_date = "$data_inicio_promocao/$data_fim_promocao";

            $preco_promocao = number_format($preco_promocao, 2, '.', '') . " $moeda";
            $data_promocao = $sale_price_effective_date;
        } else {
            $preco_promocao = "";
            $data_promocao = "";
        }

        $url_produto = htmlspecialchars(("$url_init/$nomeEcommerce/?product-details=$productID&$referencia"), ENT_XML1, 'UTF-8');


        // Escrevendo os dados do produto no arquivo XML
        fwrite($arquivo, "<item>\n");
        fwrite($arquivo, "<id>$productID</id>\n");
        fwrite($arquivo, "<title>$produto_titulo</title>\n");
        fwrite($arquivo, "<description>$produto_titulo</description>\n");
        fwrite($arquivo, "<rich_text_description>$descricao</rich_text_description>\n");

        fwrite($arquivo, "<link>$url_produto</link>\n");
        fwrite($arquivo, "<image_link>$img_produto_principal</image_link>\n");
        if (count($imagen_produto_secundaria) > 0) {
            $imagen_produto_secundaria = implode(',', $imagen_produto_secundaria);
            fwrite($arquivo, "<additional_image_link>$imagen_produto_secundaria</additional_image_link>\n");
        }


        fwrite($arquivo, "<availability>$estoque_tag</availability>\n");
        fwrite($arquivo, "<condition>$condicao_tag</condition>\n");
        fwrite($arquivo, "<price>" . number_format($valor_unitario, 2, '.', '') . " $moeda</price>\n");
        fwrite($arquivo, "<brand>$fabricante</brand>\n");
        fwrite($arquivo, "<google_product_category>Media &gt; Music &amp; Audio &gt; Music Records</google_product_category>\n");
        fwrite($arquivo, "<fb_product_category>$fb_product_category</fb_product_category>\n");
        fwrite($arquivo, "<quantity_to_sell_on_facebook></quantity_to_sell_on_facebook>\n");
        fwrite($arquivo, "<sale_price>$preco_promocao</sale_price>\n");
        fwrite($arquivo, "<sale_price_effective_date>$data_promocao</sale_price_effective_date>\n");
        fwrite($arquivo, "<item_group_id></item_group_id>\n");
        fwrite($arquivo, "<status>$status_tag</status>\n");
        fwrite($arquivo, "<gender></gender>\n");
        fwrite($arquivo, "<color></color>\n");
        fwrite($arquivo, "<size></size>\n");
        fwrite($arquivo, "<age_group></age_group>\n");
        fwrite($arquivo, "<material></material>\n");
        fwrite($arquivo, "<pattern></pattern>\n");
        fwrite($arquivo, "<shipping>BR::Standard:10.0 BRL</shipping>\n");
        fwrite($arquivo, "<shipping_weight></shipping_weight>\n");
        fwrite($arquivo, "<video_url></video_url>\n");
        fwrite($arquivo, "<video_tag></video_tag>\n");
        fwrite($arquivo, "<style></style>\n");
        fwrite($arquivo, "</item>\n");
    }
}

// Fechando o canal e o arquivo RSS
fwrite($arquivo, "</channel>\n");
fwrite($arquivo, "</rss>\n");
fclose($arquivo);

// Fechando a conexão com o banco de dados
$conecta->close();

echo "Arquivo XML RSS gerado com sucesso!";
