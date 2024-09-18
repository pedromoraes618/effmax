<?php
include "../../../modal/empresa/historico/gerenciar_historico.php";

?>

<div class="modal fade" id="modal_historico_parceiro" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header mb-2">
                <h1 class="modal-title fs-5">Histórico <?php echo $razao_social; ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


            <div class="modal-body">
                <div class="title mb-2">
                    <label class="form-label sub-title">Histórico Geral</label>
                </div>

                <div class="row">
                    <div class="col-md-auto col-auto  mb-2">
                        <input type="hidden" id="id" value="<?php echo $parceiro_id; ?>">

                        <div class="input-group">
                            <span class="input-group-text">Movimento</span>
                            <input type="date" class="form-control " id="data_inicial" name="data_incial" title="Data vencimento" placeholder="Data Inicial" value="<?php echo $data_inicial_ano_bd; ?>">
                            <input type="date" class="form-control " id="data_final" name="data_final" title="Data vencimento" placeholder="Data Final" value="<?php echo $data_lancamento; ?>">
                        </div>
                    </div>
                    <div class="col-md  mb-2">

                        <input type="text" class="form-control" id="pesquisa_conteudo_historico" placeholder="Pesquise pela palavra chave" aria-label="Recipient's username" aria-describedby="button-addon2">
                    </div>
                    <div class="col-md-auto  d-grid gap-2 d-sm-block mb-2">
                        <button type="button" class="btn  btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-auto  d-grid gap-2 d-sm-block mb-2">
                        <button class="btn btn-sm btn-dark" title="Todos os registros do financeiro desse parceiro" id="financeiro">Financeiro</button>
                        <button class="btn btn-sm btn-dark" title="Todos os registros de vendas desse parceiro" id="vendas">Vendas</button>
                        <button class="btn btn-sm btn-dark" title="Todos os registros de produtos vendidos para esse parceiro" id="produtos_vendidos">Produtos Vendidos</button>
                        <button class="btn btn-sm btn-dark" title="Todos os registros de compras que sua empresa realizou nessa parceiro" id="compras">Compras</button>
                        <button class="btn btn-sm btn-dark" title="Todos os registros de compras dos itens que foi realizado nesse Parceiro" id="produtos_comprados">Produtos Comprados</button>
                        <button class="btn btn-sm btn-dark" title="Todos os registros de compras dos itens que foi realizado nesse Parceiro" id="duplicatas_atraso">Duplicatas em atraso</button>

                        <!-- <button class="btn btn-dark" id="print_relatorio" type="button">Imprimir</button> -->
                    </div>

                </div>
                <div class="row">
                    <div class="tabela_externa">
                    </div>
                </div>
            </div>
        </div>
    </div>
   
</div>

<script src="js/empresa/historico/gerenciar_historico.js"></script>