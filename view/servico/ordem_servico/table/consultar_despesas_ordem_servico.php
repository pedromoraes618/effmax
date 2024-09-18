<?php
include "../../../../modal/servico/ordem_servico/gerenciar_ordem_servico.php";
?>
<div class="accordion " id="accordionPanelsStayOpenExample">
    <div class="accordion-item mb-2 ">
        <h2 class="accordion-header ">
            <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne-2" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne-2">
                Contabiliza
            </button>
        </h2>
        <div id="panelsStayOpen-collapseOne-2" class="accordion-collapse collapse show ">
            <div class="accordion-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Dt Pagamento</th>
                            <th scope="col">Classificação</th>
                            <th scope="col">Forma Pagamento</th>
                            <th scope="col">Status</th>
                            <th scope="col">Valor</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $total = 0;
                        while ($linha = mysqli_fetch_assoc($consulta_despesa_contabiliza)) {
                            $id = $linha['cl_id'];
                            $data_pagamento = $linha['cl_data_pagamento'];
                            $classicacao = utf8_encode($linha['classificacao']);
                            $forma_pagamento = utf8_encode($linha['fpagamento']);
                            $valor = $linha['cl_valor_liquido'];
                            $status_lancamento = $linha['statusl'];
                            $status_lancamento_id = $linha['statusid'];
                            if ($status_lancamento_id == 4) { //status recebido
                                $total += $valor;
                            }

                        ?>
                            <tr <?php if ($status_lancamento_id == 5) {
                                    echo "class='table-danger' title='Canecelado'";
                                } ?>>
                                <th scope="row"><?= formatDateB($data_pagamento); ?></th>
                                <td><?= $classicacao; ?></td>
                                <td><?= $forma_pagamento; ?></td>
                                <td><?= $status_lancamento; ?></td>
                                <td><?= real_format($valor); ?></td>
                                <?php if ($status_lancamento_id != 5) { ?>
                                    <td><button type="button" id="<?php echo $id; ?>" class="btn btn-sm btn-danger cancelar_despesa">Cancelar</button></td>
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
    <div class="accordion-item mb-2 ">
        <h2 class="accordion-header ">
            <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo-2" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo-2">
                Não Contabiliza
            </button>
        </h2>
        <div id="panelsStayOpen-collapseTwo-2" class="accordion-collapse collapse show ">
            <div class="accordion-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Dt Pagamento</th>
                            <th scope="col">Classificação</th>
                            <th scope="col">Forma Pagamento</th>
                            <th scope="col">Status</th>
                            <th scope="col">Valor</th>
                            <th></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $total = 0;
                        while ($linha = mysqli_fetch_assoc($consulta_despesa_nao_contabiliza)) {
                            $id = $linha['cl_id'];
                            $data_pagamento = $linha['cl_data_pagamento'];
                            $classicacao = utf8_encode($linha['classificacao']);
                            $forma_pagamento = utf8_encode($linha['fpagamento']);
                            $valor = $linha['cl_valor_liquido'];
                            $status_lancamento = $linha['statusl'];
                            $status_lancamento_id = $linha['statusid'];

                            if ($status_lancamento_id == 4) { //status recebido
                                $total += $valor;
                            }

                        ?>
                            <tr <?php if ($status_lancamento_id == 5) {
                                    echo "class='table-danger' title='Canecelado'";
                                } ?>>
                                <th scope="row"><?= formatDateB($data_pagamento); ?></th>
                                <td><?= $classicacao; ?></td>
                                <td><?= $forma_pagamento; ?></td>
                                <td><?= $status_lancamento; ?></td>
                                <td><?= real_format($valor); ?></td>
                                <?php if ($status_lancamento_id != 5) { ?>
                                    <td><button type="button" id="<?php echo $id; ?>" class="btn btn-sm btn-danger cancelar_despesa">Cancelar</button></td>
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


<script src="js/servico/ordem_servico/table/edita_despesa_ordem_servico.js">