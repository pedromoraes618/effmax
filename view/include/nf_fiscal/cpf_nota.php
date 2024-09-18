<div class="modal fade" id="modal_cpf_nota" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">

            <div class="modal-header">
                <h1 class="modal-title fs-5">Cpf na Nota</h1>
                <button type="text" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3 align-items-center mb-2">
                    <div class="col-md mb-2">
                        <input type="text" pattern="[0-9]*" inputmode="numeric" maxlength="11" class="form-control"  id="cpf" name="cpf" placeholder="">
                    </div>
                    <button type="button" class="btn btn-success btn-sm adicionar_cpf" id="">Salvar</button>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/include/nf_fiscal/cpf_nota.js"></script>