<?php

// include "../../../conexao/conexao.php";
// include "../../../modal/financeiro/lancamento_financeiro/gerenciar_lancamento_financeiro.php";
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
?>
<div class="modal fade" id="modal_tabela_preco" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Tabela de Preço</h1>
                <button type="button" class="btn-close btn-close-modal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="tabela_preco">
                <div class="modal-body">
                    <div class="row  mb-2">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end btn-acao">
                            <button type="submit" class="btn btn-sm btn-success">Salvar</button>
                            <button type="button" class="btn  btn-sm btn-secondary btn-close-modal" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                    <input type="hidden" name="item_id" id="item_id" value="<?php echo $_GET['id_item']; ?>">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Forma Pagamento</th>
                                <th scope="col">Preço Venda</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $resultados = consulta_linhas_tb_filtro($conecta, 'tb_forma_pagamento', "cl_ativo", "S");
                            if ($resultados) {
                                foreach ($resultados as $linha) {
                                    $id = $linha['cl_id'];
                                    $descricao = utf8_encode($linha['cl_descricao']);
                                    $valor = consulta_tabela_2_filtro($conecta, "tb_tabela_preco", "cl_produto_id", $_GET['id_item'], 'cl_forma_pagamento_id', $id, "cl_valor");
                            ?>
                                    <tr>

                                        <td><?php echo $descricao; ?></td>
                                        <td><input type="number" step="any" name="fpg<?php echo $id ?>" value="<?php echo $valor; ?>" class="form-control"></td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="alert"></div>

<script src="js/include/produto/tabela_preco.js"></script>