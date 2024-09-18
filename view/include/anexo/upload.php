<div class="modal fade" id="modal_upload_anexo" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Upload</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="upload_anexo">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label class="form-label" for="descricao">Descrição</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" placeholder="ex. certificado curso x..">
                        </div>
                        <div class="col-md-12">
                            <input class="form-control form-control-sm mb-2" type="file" id="file_input_anexo" name="file_input_anexo">
                            <div class="p-2">
                                <label>Tamanho do arquivo: no máximo 1 mb</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" id="adicionar_arquivo">Upload</button>
                <button type="button" class="btn btn-sm  btn-secondary" id="fechar_modal_upload_anexo" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<script src="js/include/anexo/upload.js"></script>