<div class="modal fade" id="modal_upload_subgrupo_estoque_img"  data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Imagem da Categoria</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="upload_img_subgrupo_estoque">
          <!-- <input type="file" id="file-input" name="file-input" />
          <p>Tamanho do arquivo: no máximo 10 MB<br>Extensão de arquivo: .JPEG, .PNG</p> -->
          <div class="mb-3">

            <input class="form-control form-control-sm mb-2" type="file" id="file-input" name="file-input">
            <div class="p-2">
              <label>Tamanho do arquivo: no máximo 5 MB<br>Extensão de arquivo: .JPEG, .PNG</label>
            </div>
          </div>
          <div>
        </form>
      </div>
      <div class="modal-footer">
       
        <button type="button" class="btn btn-sm btn-success" id="upload_img">Alterar</button>
        <button type="button" class="btn btn-sm btn-secondary" id="fechar_modal_upload_img_subrupo_estoque"  data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
<script src="js/include/subgrupo_estoque/subgrupo_upload_img.js"></script>