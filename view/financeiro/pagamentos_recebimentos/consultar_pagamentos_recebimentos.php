<?php
include "../../../conexao/conexao.php";
include "../../../modal/financeiro/pagamentos_recebimentos/gerenciar_pagamentos_recebimentos.php";
include "../../../funcao/funcao.php";
?>
<div class="title">
    <label class="form-label">Pagamentos e Recebimentos</label>
</div>
<hr>
<div class="row mb-2">
    <div class="col-auto mb-2">
        <div class="input-group">
            <span class="input-group-text">Dt Vencimento</span>
            <input type="date" class="form-control " id="data_inicial" name="data_incial" title="Data vencimento" placeholder="Data Inicial" value="<?php echo $data_inicial_mes_bd ?>">
            <input type="date" class="form-control " id="data_final" name="data_final" title="Data vencimento" placeholder="Data Final" value="<?php echo $data_final_mes_bd; ?>">
        </div>
    </div>
    <div class="col-md-auto mb-2">

        <select name="conta_financeira" class="form-control" id="conta_financeira">
            <option value="0">Conta Financeira..</option>
            <?php
            while ($linha = mysqli_fetch_assoc($consultar_conta_financeira)) {
                $descricao = utf8_encode($linha['cl_banco']);
                $conta = $linha['cl_conta'];
                echo "<option value='$conta'>$descricao</option>";
            }
            ?>
        </select>

    </div>
    <div class="col-md-auto mb-2">
        <select name="forma_pagamento" class="form-control" id="forma_pagamento">
            <option value="0">Forma de Pagamento.</option>
            <?php
            while ($linha = mysqli_fetch_assoc($consultar_forma_pagamento)) {
                $id = utf8_encode($linha['cl_id']);
                $descricao = utf8_encode($linha['cl_descricao']);

                echo "<option value='$id'>$descricao</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-auto mb-2">
        <select name="ordem" class="form-control" id="ordem">
            <option value="0">Ordernar Por.</option>
            <option value="1">Cliente / Fornecedor</option>
            <option value="2">Classificação</option>
            <option value="3">Periodo</option>

        </select>
    </div>

</div>
<div class="row">
    <div class="col-md mb-2">
        <input type="text" id="rz_parceiro" class="form-control" placeholder="Pesquise pela Razão social ou Nome fantasia">
    </div>
    <div class="col-md-auto  d-grid gap-2 d-sm-block mb-2">
        <button class="btn btn-sm btn-dark" id="consultar_a_receber">Contas a Receber</button>
        <button class="btn btn-sm btn-dark" id="consultar_a_pagar">Contas a Apagar</button>
        <button class="btn btn-sm btn-dark" id="consultar_recebidas">Contas Recebidas</button>
        <button class="btn btn-sm btn-dark" id="consultar_pagar">Contas Pagas</button>
        <button class="btn btn-sm btn-dark" id="openReport" type="button">Imprimir</button>
    </div>

</div>

<div class="tabela  print ">

</div>


<script src="js/financeiro/pagamentos_recebimentos/consultar_pagamentos_recebimentos.js"></script>
<script src="js/relatorio/formar_relatorio.js"></script>