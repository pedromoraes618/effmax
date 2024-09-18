<?php
include "../../../../modal/servico/ordem_servico/gerenciar_ordem_servico.php";
?>
<div class="accordion " id="accordionPanelsStayOpenExample">
    <div class="accordion-item mb-2 ">
        <h2 class="accordion-header ">
            <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne-2" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne-2">
                Histórico de lançamentos
            </button>
        </h2>
        <div id="panelsStayOpen-collapseOne-2" class="accordion-collapse collapse show ">
            <div class="accordion-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Dt pagamento</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Forma pagamento</th>
                            <th scope="col">Status</th>
                            <th scope="col">Vl. liquido</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        while ($linha = mysqli_fetch_assoc($consulta_financeiro)) {
                            $id = $linha['cl_id'];
                            $data_pagamento = $linha['cl_data_pagamento'];
                            $tipo = $linha['cl_documento'];
                            $forma_pagamento = utf8_encode($linha['fpagamento']);
                            $valor = $linha['cl_valor_liquido'];
                            $idstatusl = $linha['idstatusl'];
                            $statusl = $linha['statusl'];
                            if ($idstatusl == "2") { //status recebido
                                $total += $valor;
                            }

                        ?>
                            <tr <?php if ($idstatusl == 5) {
                                    echo "class='table-danger' title='Canecelado'";
                                } ?>>
                                <th scope="row"><?= formatDateB($data_pagamento); ?></th>
                                <td><?= $tipo; ?></td>
                                <td><?= $forma_pagamento; ?></td>
                                <td><?= $statusl; ?></td>
                                <td><?= real_format($valor); ?></td>
                                <?php if ($idstatusl != 5) { ?>
                                    <td><button type="button" id="<?php echo $id; ?>" class="btn btn-sm btn-danger cancelar_taxa">Cancelar</button></td>
                                <?php } else {
                                    echo "<td></td>";
                                } ?>
                            </tr>
                        <?php } ?>

                    </tbody>
                    <tfoot>
                        <th scope="col" colspan="4">Total</th>
                        <th scope="col"><?php echo real_format($total); ?></th>
                        <th></th>

                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="js/servico/ordem_servico/table/edita_registros_ordem_servico.js">