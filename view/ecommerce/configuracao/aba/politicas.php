<div class="container">
    <div class="mb-3">
        <h4 class="fw-semibold">Site</h4>
        <span> Defina as políticas para o seu site</span>
    </div>


    <div class="accordion " id="accordionPanelsStayOpenExample">
        <div class="accordion-item mb-2 ">
            <h2 class="accordion-header ">
                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                    Políticas
                </button>
            </h2>
            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show ">
                <div class="accordion-body">
                    <div class="accordion-body p-0">
                        <form id="politica" style="max-height: 900px;">
                            <div class="row ">
                                <div class="col-md" style="margin-bottom: 10%">
                                    <label for="instrucao_retirada">Termos e Condições</label>
                                    <div id="termos_condicoes"></div>
                                </div>
                            </div>
                            <div class="row  mb-3">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-start ">
                                    <button type="submit" id="button_form" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md" style="margin-bottom: 10%;">
                                    <label for="politicas_privacidade">Politica de Privacidades</label>
                                    <div id="politicas_privacidade"></div>
                                </div>
                            </div>
                            <div class="row  mb-3">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-start ">
                                    <button type="submit" id="button_form" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md" style="margin-bottom: 70px;">
                                    <label for="politicas_devolucao">Política de Devolução</label>
                                    <div id="politicas_devolucao"></div>
                                </div>
                            </div>
                            <div class="row  mb-3">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-start ">
                                    <button type="submit" id="button_form" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var quill = new Quill('#termos_condicoes', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                [{
                    'script': 'sub'
                }, {
                    'script': 'super'
                }],
                [{
                    'indent': '-1'
                }, {
                    'indent': '+1'
                }],
                [{
                    'direction': 'rtl'
                }],
                [{
                    'size': ['small', false, 'large', 'huge']
                }],
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],
                [{
                    'color': []
                }, {
                    'background': []
                }],
                ['image', 'code-block', 'link'],
                [{
                    'align': []
                }],
                ['clean'],

            ],
        },
        table: true, // Habilita o módulo de tabela
    });
    var quill = new Quill('#politicas_privacidade', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                [{
                    'script': 'sub'
                }, {
                    'script': 'super'
                }],
                [{
                    'indent': '-1'
                }, {
                    'indent': '+1'
                }],
                [{
                    'direction': 'rtl'
                }],
                [{
                    'size': ['small', false, 'large', 'huge']
                }],
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],
                [{
                    'color': []
                }, {
                    'background': []
                }],
                ['image', 'code-block', 'link'],
                [{
                    'align': []
                }],
                ['clean'],

            ],
        },
        table: true, // Habilita o módulo de tabela
    });
    var quill = new Quill('#politicas_devolucao', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                [{
                    'script': 'sub'
                }, {
                    'script': 'super'
                }],
                [{
                    'indent': '-1'
                }, {
                    'indent': '+1'
                }],
                [{
                    'direction': 'rtl'
                }],
                [{
                    'size': ['small', false, 'large', 'huge']
                }],
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],
                [{
                    'color': []
                }, {
                    'background': []
                }],
                ['image', 'code-block', 'link'],
                [{
                    'align': []
                }],
                ['clean'],

            ],
        },
        table: true, // Habilita o módulo de tabela
    });
</script>
<script src="js/ecommerce/configuracao/aba/politicas.js"></script>