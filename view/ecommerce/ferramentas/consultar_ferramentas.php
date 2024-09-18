<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
?>
<div class="title">
    <label class="form-label">Ferramentas</label>
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
                    <a class="nav-link fw-semibold text-dark" id="aba_link" aria-current="page" href="#">
                        <i class="bi bi-box-arrow-in-up-right"></i>
                        Links
                        <div class="text-muted" style="font-size: 0.7em;">Personalize os seus link.</div>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="col-md layout">

    </div>
</div>

<div class="tabela"></div>
<div class="modal_show"></div>


<script src="js/ecommerce/ferramentas/consultar_ferramentas.js"></script>