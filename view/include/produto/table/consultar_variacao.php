<?php
include "../../../../conexao/conexao.php";
include "../../../../funcao/funcao.php";
include "../../../../modal/estoque/produto/gerenciar_produto.php";
?>
<div class="card   shadow-0  border-0 mb-2 ">
    <div class="card-header header-card-dashboard">
        <h6><i class="bi bi-exclamation-octagon"></i> Opção de Produto</h6>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-hover" style="cursor:pointer">
            <tbody>
                <?php
                // Extrair os dados
                $descricao_registro = '';
                while ($linha = mysqli_fetch_assoc($consultar_opcoes)) {
                    $codigo_nf_filho = $linha['cl_produto_pai_codigo_nf'];
                    $descricao = ($linha['titulovariante']);
                    $valor_variacao = $linha['cl_valor'];
                    $preco_venda = $linha['cl_preco_venda'];
                    $estoque = $linha['cl_estoque'];
                    $ativo = $linha['cl_status_ativo'];
                    if ($descricao != $descricao_registro) {
                        $id = $linha['idvariante'];

                        $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_variante_produto where cl_descricao = '$descricao' and cl_produto_pai_codigo_nf='$codigo_nf_filho' ");
                ?>
                        <tr class="opcao" data-opcao-id='<?= $id; ?>'>
                            <td><?= utf8_encode($descricao) ?></td>
                            <td>
                                <?php
                                foreach ($resultados as $linha) {
                                    $variante = utf8_encode($linha['cl_valor']);
                                    echo $variante . " ";
                                }
                                ?>
                            </td>
                        </tr>
                <?php
                    }
                    $descricao_registro = $descricao;
                } ?>
            </tbody>
        </table>
    </div>
</div>
<div class="card  shadow-0 border-0 mb-2 ">
    <div class="card-header header-card-dashboard">
        <h6><i class="bi bi-exclamation-octagon"></i> Variações</h6>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Descrição</th>
                    <th scope="col">Variação</th>
                    <th scope="col">Preço</th>
                    <th scope="col">Estoque</th>
                    <th scope="col">Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Array para armazenar produtos com suas variações agrupadas por `cl_produto_pai_codigo_nf`

                // Extrair os dados
                while ($linha = mysqli_fetch_assoc($consultar_variantes)) {
                    $span_variante = '';

                    $produto_id = $linha['cl_id'];
                    $descricao = utf8_encode($linha['cl_descricao']);
                    $referencia = utf8_encode($linha['cl_referencia']);
                    $valor_variacao = $linha['cl_preco_venda'];
                    $preco_venda = $linha['cl_preco_venda'];
                    $estoque = $linha['cl_estoque'];
                    $status = $linha['cl_status_ativo'];
                    $variacoes = $linha['cl_variacao'];
                    $status_class = $status == "SIM" ? "success" : "danger";

                    $variacoes = array_map('trim', explode(',', $variacoes));
                    if (count($variacoes) > 0) {
                        foreach ($variacoes as $variacao) {
                            $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_variante_produto where cl_id ='$variacao' ");
                            if ($resultados) {
                                foreach ($resultados as $linha) {
                                    $descricao_variante = utf8_encode($linha['cl_descricao']);
                                    $valor_variante = utf8_encode($linha['cl_valor']);
                                    $span_variante = $span_variante . " <span>($descricao_variante $valor_variante)</span> ";
                                }
                            }
                        }
                    }

                ?>
                    <tr class="variante">
                        <td><?= $descricao; ?>
                            <hr class="mb-1"><?= $referencia; ?>
                        </td>
                        <td><?= $span_variante; ?></td>
                        <td><?= real_format($preco_venda); ?> </td>
                        <td><?= $estoque; ?></td>
                        <td><span class="badge text-bg-<?= $status_class; ?>"><?= $status; ?></span></td>
                        <td>
                            <div class="btn-group">
                                <button type="button" data-id=<?php echo $produto_id; ?> class="btn btn-info   btn-sm editar_produto ">Editar</button>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script src="js/include/produto/table/consultar_variacao.js">