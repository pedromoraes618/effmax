<?php include "../../../modal/estoque/ajuste_estoque1/gerenciar_ajuste_estoque.php"; ?>
<div class="modal fade" id="modal_historico_ajuste" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Ajuste de estoque</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="pedido">
                <input type="hidden" id="codigo_nf" name="codigo_nf" value="<?= $codigo_nf; ?>">
                <div class="modal-body">
                    <div class="title mb-2">
                        <label class="form-label sub-title">Historico do ajuste <?= $codigo_ajuste; ?></label>
                    </div>

                    <div class="row  mb-3">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                            <button type="button" id="openReport" class="btn btn-sm btn-dark"><i class="bi bi-printer"></i> Imprimir</button>
                            <button type="button" class="btn btn-sm  btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card m-1 card-print card-dashboard position-relative shadow border-0 mb-2 ">
                            <div class="card-header header-card-dashboard ">
                                <h6><i class="bi bi-exclamation-octagon"></i> Ajuste de estoque</h6>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Item</th>
                                            <th scope="col">Código</th>
                                            <th scope="col">Descrição</th>
                                            <th scope="col">Fabricante</th>
                                            <th scope="col">Localização</th>
                                            <th scope="col">Qtd</th>
                                            <th scope="col">Tipo</th>
                                        </tr>
                                    </thead>
                                    <tbody id="produtos-tbody">
                                        <?php
                                        $item = 0;
                                        while ($linha = mysqli_fetch_assoc($consultar)) {
                                            $codigo_nf = $linha['cl_codigo_nf'];
                                            $documento = $linha['cl_documento'];
                                            $data = $linha['cl_data_lancamento'];
                                            $usuario_id = $linha['cl_usuario_id'];
                                            $usuario = utf8_encode($linha['cl_usuario']);
                                            $descricao =  utf8_decode($linha['cl_descricao']);
                                            $localizacao =  utf8_decode($linha['cl_localizacao']);
                                            $fabricante =  utf8_decode($linha['cl_fabricante']);
                                            $referencia =  utf8_decode($linha['cl_referencia']);
                                            $tipo = $linha['tipoajst'];
                                            $prdid = $linha['prdid'];
                                            $quantidade = $linha['cl_quantidade'];
                                            $item++;

                                        ?>
                                            <tr>
                                                <th><?= $item; ?></th>
                                                <th><?= $prdid; ?></th>
                                                <td><?= $descricao . "<br>" . $referencia; ?></td>
                                                <td><?= $fabricante; ?></td>
                                                <td><?= $localizacao; ?></td>
                                                <td><?= $quantidade; ?></td>

                                                <td><?= $tipo; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>

                                </table>
                                <div class="adjustment-info">
                                    <div>
                                        <p class="mb-1">Ajuste: <span><?= $documento; ?></span></p>
                                    </div>
                                    <div>
                                        <p class="mb-1">Data: <span><?= formatDateB($data); ?></span></p>
                                    </div>
                                    <div>
                                        <p class="mb-1">Quantidade de Ajuste: <span><?= $item; ?></span></p>
                                    </div>
                                    <div>
                                        <p class="mb-1">Usuário: <span><?= $usuario; ?></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="js/relatorio/formar_relatorio.js"></script>


<!-- <script src="js/ecommerce/pedido/pedido_tela.js"></script> -->