<?php
if (isset($_GET['kardex_produto'])) {
    include "../../../../conexao/conexao.php";
    include "../../../../funcao/funcao.php";

    $kardex = $_GET['kardex_produto'];
    $id_produto = $_GET['id_produto'];

    $select = "SELECT * from tb_produtos where cl_id = $id_produto";
    $consultar_produto = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consultar_produto);
    $descricao_produto = utf8_encode($linha['cl_descricao']);
    $estoque = $linha['cl_estoque'];

    $select = "SELECT * from tb_ajuste_estoque where cl_produto_id = '$id_produto' and cl_ajuste_inicial ='1'";
    $consultar_ajuste_inicial = mysqli_query($conecta, $select);
    $linha = mysqli_fetch_assoc($consultar_ajuste_inicial);
    $estoque_inicial = $linha['cl_quantidade'];

    $select = "SELECT ajst.*,prc.*,user.*,ajst.cl_valor_venda as valorv,ajst.cl_parceiro_id, ajst.cl_valor_compra as valorc, ajst.cl_tipo as tipo,
    emp.cl_razao_social as empresa from tb_ajuste_estoque as ajst 
    left join tb_empresa as emp on emp.cl_id = ajst.cl_empresa_id 
    left join tb_parceiros as prc on prc.cl_id = ajst.cl_parceiro_id 
    left join tb_users as user on user.cl_id = ajst.cl_usuario_id 
    where ajst.cl_produto_id = $id_produto and ajst.cl_ajuste_inicial ='0' ";

    if ($kardex == "detalhado") {
        $data_inicial = $_GET['data_inicial'];
        $data_final = $_GET['data_final'];
        $pesquisa = $_GET['conteudo_pesquisa_kardex'];

        // //formatar data para o banco de dados
        // $data_inicial =  formatarDataParaBancoDeDados($data_inicial);
        // $data_final =  formatarDataParaBancoDeDados($data_final);

        $select .= " and ajst.cl_data_lancamento between '$data_inicial' and '$data_final'
         and (ajst.cl_documento like '%{$pesquisa}%'  or user.cl_usuario like '%{$pesquisa}%' )";
    }
    $select .= "order by ajst.cl_id ";
    $consultar_historico_produto = mysqli_query($conecta, $select);
}
