<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
include "../../../modal/estoque/produto/gerenciar_produto.php";
?>
<div class="modal fade" id="modal_variante_produto" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl ">
        <div class="modal-content ">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Variantes de produto</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


            <div class="modal-body">
                <form id="variacao_produto">
                    <div class="row  mb-2">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                            <button type="submit" id="button_form" class="btn btn-sm btn-success">Salvar</button>
                            <button type="button" class="btn btn-sm btn-secondary btn-closer" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Variação</th>
                                <th scope="col">Preço da venda</th>
                                <th scope="col">Preço de custo</th>
                                <th scope="col">Ref/SKU</th>
                                <th scope="col">Estoque</th>
                                <th scope="col">Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Array para armazenar produtos com suas variações agrupadas por `cl_produto_pai_codigo_nf`
                            $produtos_variacoes = [];

                            // Extrair os dados
                            while ($linha = mysqli_fetch_assoc($consultar_variantes)) {
                                $codigo_nf_filho = $linha['cl_produto_pai_codigo_nf'];
                                $descricao = $linha['cl_descricao'];
                                $valor_variacao = $linha['cl_valor'];
                                $preco_venda = $linha['cl_preco_venda'];
                                $estoque = $linha['cl_estoque'];
                                $ativo = $linha['cl_status_ativo'];

                                // Agrupar variações por código do produto pai
                                if (!isset($produtos_variacoes[$codigo_nf_filho])) {
                                    $produtos_variacoes[$codigo_nf_filho] = [
                                        'preco_venda' => $preco_venda,
                                        'estoque' => $estoque,
                                        'ativo' => $ativo,
                                        'variacoes' => []
                                    ];
                                }

                                $produtos_variacoes[$codigo_nf_filho]['variacoes'][] = [
                                    'descricao' => $descricao,
                                    'valor' => $valor_variacao
                                ];
                            }

                            // Função para gerar todas as combinações de variações
                            function gerar_combinacoes($variacoes, $prefixo = '')
                            {
                                $combinacoes = [];
                                if (count($variacoes) == 1) {
                                    foreach ($variacoes[0] as $variacao) {
                                        $combinacoes[] = $prefixo . $variacao['descricao'] . ': ' . $variacao['valor'];
                                    }
                                } else {
                                    foreach ($variacoes[0] as $variacao) {
                                        $combinacoes = array_merge($combinacoes, gerar_combinacoes(array_slice($variacoes, 1), $prefixo . $variacao['descricao'] . ': ' . $variacao['valor'] . ' | '));
                                    }
                                }
                                return $combinacoes;
                            }

                            // Gerar e exibir todas as combinações de variações
                            foreach ($produtos_variacoes as $produto) {
                                $preco_venda = ($produto['preco_venda']);
                                $estoque = $produto['estoque'];
                                $status = $produto['ativo'];


                                // Agrupar variações por tipo
                                $variacoes_por_tipo = [];
                                foreach ($produto['variacoes'] as $variacao) {
                                    $tipo = explode(': ', $variacao['descricao'])[0];
                                    if (!isset($variacoes_por_tipo[$tipo])) {
                                        $variacoes_por_tipo[$tipo] = [];
                                    }
                                    $variacoes_por_tipo[$tipo][] = $variacao;
                                }

                                // Gerar combinações únicas de variações
                                $variacoes = array_values($variacoes_por_tipo);
                                $combinacoes = gerar_combinacoes($variacoes);

                                // Exibir cada combinação em uma linha da tabela
                                foreach ($combinacoes as $combinacao) {
                            ?>
                                    <tr class="variante">
                                        <td><?php echo rtrim($combinacao, ' | '); ?></td>
                                        <td><input type="text" class="form-control" value="<?= $preco_venda; ?>"></td>
                                        <td><input type="text" class="form-control" placeholder="R$ 0"></td>
                                        <td><input type="text" class="form-control" placeholder="3561512135156"></td>
                                        <td><input type="text" class="form-control" placeholder="0.00" value="<?= $estoque; ?>"></td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" <?= $status == "SIM" ? 'checked' : ''; ?>>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- <script src="js/include/produto/opcao_produto.js"></script> -->