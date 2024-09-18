<?php
include "../../../modal/servico/ordem_servico/gerenciar_ordem_servico.php";
?>
<div class="modal fade " id="modal_pecas_ordem_servico" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border ">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Material</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="pecas">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end btn-acao">
                        <button type="button" class="btn btn-dark btn-sm " id="openReport">Imprimir</button>
                        <button type="button" class="btn btn-success btn-sm requisitar_material"><i class="bi bi-box-arrow-left"></i> Requisitar Material</button>
                        <button type="button" class="btn btn-danger btn-sm cancelar_requisicao_material"><i class="bi bi-box-arrow-in-left"></i> Cancelar Requisição</button>
                        <button type="button" class="btn btn-sm btn-secondary btn-fechar" data-bs-dismiss="modal">Fechar</button>

                    </div>
                    <div class="card p-2 card-print border-0 border-top shadow tabela_material tabela_modal  mb-2" style="max-height:500px;"></div>
            </div>
        </div>
    </div>
</div>


<div class="modal_externo_3"></div>

<script src="js/include/servico/pecas_aplicadas.js"></script>
<script src="js/relatorio/formar_relatorio.js"></script>