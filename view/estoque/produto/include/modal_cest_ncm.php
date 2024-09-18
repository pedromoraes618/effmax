<?php
if (isset($_GET['consultar_cest'])) {
    $titulo = "Consultar Cest";
    $pesquisa = "buscar_cest";
} else {
    $titulo = "Consultar Ncm";
    $pesquisa = "buscar_ncm";
}
?>

<div class="modal fade" id="modal_cunsultar_cest_ncm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"><?php echo $titulo; ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="conteudo_pesquisa" placeholder="Tente pesquisar pelo cest, ncm ou descrição do produto" aria-label="Recipient's username" aria-describedby="button-addon2">
                    <button class="btn btn-outline-secondary" type="button" id="<?php echo $pesquisa; ?>">Pesquisar</button>
                </div>
                <div class="tabela mb-3">
                    <!-- informações da pesquisa -->
                </div>
            </div>

        </div>
    </div>
</div>

<script src="js/estoque/produto/include/modal_cest_ncm.js"></script>