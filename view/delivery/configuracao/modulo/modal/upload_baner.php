<div class="modal fade" id="modal_upload_baner"  data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Baner</h1>
        <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="baner">
          <!-- <input type="file" id="file-input" name="file-input" />
          <p>Tamanho do arquivo: no máximo 10 MB<br>Extensão de arquivo: .JPEG, .PNG</p> -->
          <div class="mb-3">

            <input class="form-control form-control-sm mb-2" type="file" id="file-input" name="file-input">
            <div class="p-2">
              <label>Tamanho do arquivo: no máximo 3 MB<br>Extensão de arquivo: .MP4</label>
            </div>
          </div>
          <div>
        </form>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-sm btn-success" id="upload_baner"><i class="bi bi-check-all"></i> Salvar</button>

        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
<script src="js/delivery/configuracao/modulo/modal/upload_baner.js"></script>