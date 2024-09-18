<div class="container">
    <div class="mb-3">
        <h4 class="fw-semibold">Link</h4>
        <span> Gerencie os seus links.</span>
    </div>


    <div class="accordion " id="accordionPanelsStayOpenExample">
        <div class="accordion-item mb-2 ">
            <h2 class="accordion-header ">
                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                    Deep link
                </button>
            </h2>
            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show ">
                <div class="accordion-body">
                    <form id="deep_link">
                        <div class="d-flex align-items-center mb-2">
                            <div>
                                <div class="form-text mb-1">Plataforma</div>
                                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">

                                    <input type="radio" class="btn-check app" checked name="btnApp" id="all" autocomplete="off" value="all">
                                    <label class="btn btn-outline-primary" for="all">Todos</label>

                                    <input type="radio" class="btn-check app" name="btnApp" id="instagram" autocomplete="off" value="instagram">
                                    <label class="btn btn-outline-primary" for="instagram"><i class="bi bi-instagram fs-5"></i></label>

                                    <input type="radio" class="btn-check app" name="btnApp" id="facebook" autocomplete="off" value="facebook">
                                    <label class="btn btn-outline-primary" for="facebook"><i class="bi bi-facebook fs-5"></i></label>
                                </div>
                            </div>
                            <div class="mx-3">
                                <i class="bi bi-arrow-right "></i>
                            </div>
                            <div>
                                <div class="form-text mb-1">Dispositivo</div>
                                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">

                                    <input type="radio" class="btn-check dispositivo" name="btnDisp" checked id="android" autocomplete="off" value="android">
                                    <label class="btn btn-outline-dark" for="android" title="Android"> Android</label>

                                    <input type="radio" class="btn-check dispositivo" name="btnDisp" id="ios" autocomplete="off" value="ios">
                                    <label class="btn btn-outline-dark" for="ios">IOS</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="input-group">
                                <input type="text" class="form-control" name="url_link_deep_link" id="url_link_deep_link" placeholder="Digite o link" aria-label="Recipient's username" aria-describedby="button-addon2">
                                <button class="btn btn-dark" type="submit" id="gerar_deep_link">Gerar</button>
                                <button class="btn btn-warning" type="button" id="copiar_link">Copitar</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="js/ecommerce/ferramentas/aba/link.js"></script>