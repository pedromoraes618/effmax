<div class="modal fade" id="modal_opcao_produto" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Opção de produto</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="variacao_opcao">
                    <input type="hidden" id="opcao_id" name="opcao_id" value="<?= isset($_GET['opcao_id']) ? $_GET['opcao_id'] : ''; ?>">
                    <div class="row mb-2">
                        <div class="col-md-7">
                            <label for="nome_opcao" class="form-label">Nome opção</label>
                            <input type="text" class="form-control" id="nome_opcao" name="nome_opcao" list="datalistOpcao" placeholder="Ex. Tamanho ou Cor">
                            <datalist id="datalistOpcao">
                                <option value="Tamanho"></option>
                                <option value="Cor"></option>
                                <option value="Peso"></option>
                            </datalist>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Exibição</label>
                            <div class="opcao-tipo">
                                <input type="radio" class="btn-check btn-sm" name="opcao_exibicao" value="lista" id="opcao_lista" autocomplete="off" checked>
                                <label class="btn" for="opcao_lista"><i class="bi bi-list-ol"></i> Lista</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md">
                            <label for="variantes_opcao" class="form-label">Variantes da opção
                                <span class="d-inline-block BD-" tabindex="0" data-bs-toggle="popover" data-bs-placement="top" data-bs-trigger="hover focus" data-bs-content="Separe a opção por virgula, exemplo vermelho,azul,preto">
                                    <i class="bi bi-info-circle"></i>
                                </span></label>
                            <input type="text" class="form-control" id="variantes_opcao" aria-describedby="varianteOpcaoHelp" name="variantes_opcao" placeholder="Ex. Azul,Verde,Vermelho">
                            <div id="varianteOpcaoHelp" class="form-text">Adicione uma vírgula depois de cada variante.</div>
                            </span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="incluir_titulo" name="incluir_titulo">
                                <label class="form-check-label" for="incluir_titulo">Incluir no titulo do produto?</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                        <button type="submit" id="button_form" class="btn btn-sm btn-success">Salvar</button>
                        <button type="button" class="btn btn-sm btn-secondary btn-closer" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="js/include/produto/opcao_produto.js"></script>