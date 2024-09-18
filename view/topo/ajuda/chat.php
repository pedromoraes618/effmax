<div class="modal fade" id="modal_cunsultar_chat" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Chat</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_chat">
                    <div class="col mb-3">
                        <textarea name=""  class="form-control mb-2" id="pergunta_chat" placeholder="Faça a sua pergunta" cols="20" rows="5"></textarea>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-outline-secondary" id="buscar_chat">Pesquisar</button>
                        </div>
                    </div>
                    <div class="consulta_chat mb-3 p-2 border" style="max-height: 200px;overflow:auto" id="resposta">
                        <!-- informações da pesquisa -->
                   
                    </div>
                </form>
            </div>
            <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Send message</button>
                </div> -->
        </div>
    </div>
</div>