<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
?>
<div class="title">
    <label class="form-label">Configuração</label>
</div>
<hr>
<div class="navbar navbar sticky-top  flex-md-nowrap p-0 shadow">
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
</div>

<div class="row mb-2">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="position-sticky pt-3 sidebar-sticky">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link fw-semibold text-dark" id="aba_site" aria-current="page" href="#">
                        <i class="bi bi-box-arrow-in-up-right"></i>
                        Configurações do site
                        <div class="text-muted" style="font-size: 0.7em;">Personalize seu site.</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold text-dark" id="aba_frete" aria-current="page" href="#">
                        <i class="bi bi-truck"></i>
                        Frete
                        <div class="text-muted" style="font-size: 0.7em;">Gerencie como os clientes recebem seus pedidos.</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold text-dark" id="aba_politicas" aria-current="page" href="#">
                        <i class="bi bi-truck"></i>
                        Políticas
                        <div class="text-muted" style="font-size: 0.7em;">Define sua políticas para o site.</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold text-dark" id="aba_layout" aria-current="page" href="#">

                        <i class="bi bi-columns"></i> Layout
                        <div class="text-muted" style="font-size: 0.7em;">Personalize o layout do site.</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold text-dark" id="aba_integracao" aria-current="page" href="#">

                        <i class="bi bi-boxes"></i> Integrações
                        <div class="text-muted" style="font-size: 0.7em;">Defina as integrações de pagamento, frete etc..</div>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="col-md  layout">

    </div>
</div>

<div class="tabela"></div>
<div class="modal_show"></div>


<script src="js/ecommerce/configuracao/consultar_configuracao.js"></script>