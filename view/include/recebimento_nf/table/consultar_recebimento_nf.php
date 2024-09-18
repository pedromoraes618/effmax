<?php
include "../../../../modal/recebimento_nf/nf_saida/gerenciar_recebimento.php";
?>
<?php if ($qtd > 0) { ?>
    <div class="accordion " id="accordionPanelsStayOpenExample">
        <div class="accordion-item mb-2 ">
            <h2 class="accordion-header ">
                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                    Histórico de lançamentos
                </button>
            </h2>
            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show ">
                <div class="accordion-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Dt pagamento</th>
                                <th scope="col">Usuário</th>
                                <th scope="col">Forma pagamento</th>
                                <th scope="col">Status</th>
                                <th scope="col">Vl Liquido</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            while ($linha = mysqli_fetch_assoc($consulta)) {
                                $id = $linha['cl_id'];
                                $data_pagamento = $linha['cl_data_pagamento'];
                                $forma_pagamento = utf8_encode($linha['fpagamento']);
                                $valor = $linha['cl_valor_liquido'];
                                $status_lancamento = $linha['statusl'];
                                $status_lancamento_id = $linha['statusid'];
                                $usuario = $linha['cl_usuario'];
                                if ($status_lancamento_id == 2) { //status recebido
                                    $total += $valor;
                                }

                            ?>
                                <tr <?php if ($status_lancamento_id == 5) {
                                        echo "class='table-danger' title='Canecelado'";
                                    } ?>>
                                    <th scope="row"><?= formatDateB($data_pagamento); ?></th>
                                    <td><?= $usuario; ?></td>

                                    <td><?= $forma_pagamento; ?></td>
                                    <td><?= $status_lancamento; ?></td>
                                    <td><?= real_format($valor); ?></td>
                                    <?php if ($status_lancamento_id != 5) { ?>
                                        <td><button type="button" id="<?php echo $id; ?>" class="btn btn-sm btn-danger cancelar_lancamento">Cancelar</button></td>
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
<?php } ?>

<script src="js/include/recebimento_nf/table/consultar_recebimento_nf.js">