<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
?>
<div class="title">
    <label class="form-label">Ajuste de preço</label>
</div>
<hr>
<div class="navbar navbar sticky-top  flex-md-nowrap p-0 shadow">
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
</div>

<div class="row mb-2" id="card-ajuste-preco">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="position-sticky pt-3 sidebar-sticky">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link fw-semibold text-dark" id="aba_consultar_ajuste" aria-current="page" href="#">
                        <i class="bi bi-eye-fill"></i>
                        Consultar Ajustes
                        <div class="text-muted" style="font-size: 0.7em;">Consulte os ajustes realizados.</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold text-dark" id="aba_ajuste_item" aria-current="page" href="#">
                        <i class="bi bi-box-arrow-in-up-right"></i>
                        Ajuste por item
                        <div class="text-muted" style="font-size: 0.7em;">Atualize o preço de cada item do seu estoque.</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold text-dark" id="aba_ajuste_lote" aria-current="page" href="#">
                        <i class="bi bi-stack"></i>
                        Ajuste em lote
                        <div class="text-muted" style="font-size: 0.7em;">Atualize o preço de vários produtos.</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold text-dark" id="aba_ajuste_promocao" aria-current="page" href="#">
                        <i class="bi bi-percent"></i>
                        Defina as promoções
                        <div class="text-muted" style="font-size: 0.7em;">Define as promoções para os produtos.</div>
                    </a>
                </li>

            </ul>
        </div>
    </nav>
    <div class="col-md layout">

    </div>
</div>
<div class="modal_show"></div>




<script src="js/estoque/ajuste_preco/consultar_ajuste_preco.js"></script>