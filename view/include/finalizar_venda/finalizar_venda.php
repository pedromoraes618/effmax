<?php
include "../../../conexao/conexao.php";
include "../../../modal/venda/venda_mercadoria/gerenciar_venda.php";
include "../../../modal/autorizador/usuario.php";
include "../../../funcao/funcao.php";
?>
<div class="modal fade " id="modal_finalizar_venda" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content border border-primary">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Finalizar venda</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row  mb-2">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end btn-acao">
                        <button type="submit" class="btn btn-sm btn-success" id="finalizar_venda"><i class="bi bi-check-all"></i> Finalizar venda</button>
                        <!-- <button type="submit" class="btn btn-warning" id="adiar">Finalizar venda</button> -->
                        <button type="button" id="fechar_modal" class="btn btn-sm  btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md  mb-2">
                        <div class="card mb-2">
                            <div class="card-header">Selecione a forma de pagamento</div>
                            <ul class="list-group listar_fpg_venda">
                                <?php
                                $resultados = consulta_linhas_tb_query($conecta, 'SELECT fpg.*,fpg.cl_descricao as formapg,fpg.cl_id as idfpg,tp.* from tb_forma_pagamento as fpg left join 
                                tb_tipo_pagamento as
                                   tp on tp.cl_id = fpg.cl_tipo_pagamento_id');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = $linha['idfpg'];
                                        $descricao = utf8_encode($linha['formapg']);
                                        $icone = utf8_encode($linha['cl_icone']);
                                        $desconto_maximo = formatarPorcentagem($linha['cl_desconto_maximo']);
                                ?>
                                        <li id_fpg="<?= $id ?>" class="list-group-item d-flex justify-content-between  align-items-start seleciona_fpg">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold descricao_fpg_<?= $id ?>"><?= $descricao; ?></div>
                                                <div class="text-muted" style="font-size: 0.8em;">Desconto max <?= $desconto_maximo; ?></div>

                                            </div>


                                            <div>
                                                <span> <?= $icone; ?></span>
                                            </div>
                                        </li>

                                <?php
                                    }
                                }
                                ?>
                            </ul>
                        </div>

                        <div class="card mb-2">
                            <div class="card-header">Selecione o vendedor</div>
                            <div class="col">
                                <select class="form-select chosen-select" name="vendedor_id_venda" id="vendedor_id_venda">
                                    <option value="0">Selecione..</option>
                                    <?php
                                    if (isset($_GET['id_user_logado'])) {
                                        $id_user_logado = $_GET['id_user_logado'];
                                    }
                                    while ($linha = mysqli_fetch_assoc($consultar_vendedor)) {
                                        $vendedor = $linha['cl_usuario'];
                                        $id_vendedor = $linha['cl_id'];
                                    ?>
                                        <option <?php if ($id_user_logado == $id_vendedor) {
                                                    echo 'selected';
                                                } ?> value="<?php echo $id_vendedor; ?>"><?php echo $vendedor; ?></option>
                                    <?php
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">Selecione o autorizador (Desconto)</div>
                            <div class="row">
                                <div class="input-group">

                                    <select class="form-select chosen-select" name="autorizador_id" id="">
                                        <option value="0">Selecione..</option>
                                        <?php
                                        if (isset($_GET['id_user_logado'])) {
                                            $id_user_logado = $_GET['id_user_logado'];
                                        }
                                        while ($linha = mysqli_fetch_assoc($consultar_usuarios_autorizados)) {
                                            $user_autorizador = $linha['cl_usuario'];
                                            $id_user_autorizador = $linha['cl_id'];
                                        ?>
                                            <option <?php if ($id_user_logado == $id_user_autorizador) {
                                                        echo 'selected';
                                                    } ?> value="<?php echo $id_user_autorizador; ?>"><?php echo $user_autorizador; ?></option>
                                        <?php
                                        }
                                        ?>

                                    </select>


                                    <input type="password" autocomplete="disabled" name="senha_autorizador" class="form-control" placeholder="Digite a senha">

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md  mb-2">
                        <div class="card mb-2">
                            <div class="card-header">Resumo</div>
                            <div class="card-body">
                                <ul class="list-group ">
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">Cliente</div>
                                            <?php if (isset($_GET['cliente_razao']) and $_GET['cliente_razao'] != "") {
                                                echo $_GET['cliente_razao'];
                                            } else {
                                                echo 'Cliente Avulso';
                                            } ?>
                                        </div>
                                        <span class="badge bg-primary rounded-pill"><i class="bi bi-person-circle"></i></span>
                                    </li>
                                    <li class="list-group-item mb-3 d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">Pagamento</div>
                                            <div class="descricao_forma_pagamento_venda">A definir...</div>
                                            <input type="hidden" id="id_forma_pagamento_venda" name="forma_pagamento_id_venda" class="form-control">
                                        </div>
                                        <span class="badge bg-primary rounded-pill"><i class="bi bi-cart-check-fill"></i></span>
                                    </li>
                                    <li class="list-group-item d-flex shadow border border-success-subtle justify-content-between align-items-start">

                                        <div class="col">
                                            <div class="input-group mb-1">
                                                <span class="input-group-text ">Sub total </span>
                                                <input type="text" aria-label="First name" readonly onchange="updateTotal()" name="sub_total_venda" id="sub_total_venda" class="form-control" value="<?php echo $_GET['vlr_total_venda']; ?>">
                                            </div>
                                            <div class="input-group mb-1">
                                                <span class="input-group-text ">Desconto</span>
                                                <input type="text" placeholder="R$" onchange="updateTotal()" name="desconto_venda_real" id="desconto_venda_real" class="form-control">
                                                <input type="text" placeholder="%" disabled id="desconto_venda_porcentagem" class="form-control">
                                            </div>
                                            <div class="input-group mb-1">
                                                <span class="input-group-text ">Vlr Crédito</span>
                                                <input type="number" step="any" placeholder="R$" onchange="updateTotal()" name="valor_credito" id="valor_credito" class="form-control">
                                                <input type="text" disabled id="credito_atual" class="form-control">
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-text">Total</span>
                                                <input type="text" disabled aria-label="First name" id="valor_liquido_venda" class="form-control" value="<?php echo $_GET['vlr_total_venda']; ?>">
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-header">Documentos</div>
                            <div class="card-body">
                                <input type="radio" class="btn-check" name="gerar_documento" id="option5" value="recibo" autocomplete="off" checked>
                                <label class="btn" for="option5">Recibo</label>

                                <input type="radio" class="btn-check" name="gerar_documento" id="option6" value="nfc" autocomplete="off">
                                <label class="btn" for="option6">NFC</label>

                                <input type="radio" class="btn-check" name="gerar_documento" id="option7" value="nfe" autocomplete="off">
                                <label class="btn" for="option7">NFE</label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="alert"></div>
    <div class="modal_externo_2"></div>
</div>

<script src="js/include/finalizar_venda/finalizar_venda.js"></script>