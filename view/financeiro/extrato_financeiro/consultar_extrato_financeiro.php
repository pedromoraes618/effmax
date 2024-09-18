<?php
include "../../../conexao/conexao.php";
include "../../../modal/financeiro/extrato_financeiro/gerenciar_extrato_financeiro.php";
include "../../../funcao/funcao.php";
?>
<div class="title">
    <label class="form-label">Extrato financeiro</label>
</div>
<hr>
<div class="row mb-2">
    <div class="col-auto mb-2">
        <div class="input-group">
            <span class="input-group-text">Data</span>
            <input type="date" class="form-control " id="data_inicial" name="data_incial" title="Data vencimento" placeholder="Data Inicial" value="<?php echo $data_lancamento ?>">
            <input type="date" class="form-control " id="data_final" name="data_final" title="Data vencimento" placeholder="Data Final" value="<?php echo $data_lancamento; ?>">
        </div>
    </div>
    <div class="col-md-auto mb-2">

        <select name="conta_financeira" class="form-select chosen-select" id="conta_financeira">
            <option value="0">Selecione a conta financeira..</option>
            <?php
            while ($linha = mysqli_fetch_assoc($consultar_conta_financeira)) {
                $descricao = utf8_encode($linha['cl_banco']);
                $conta = $linha['cl_conta'];
                echo "<option value='$conta'>$descricao</option>";
            }
            ?>
        </select>

    </div>

    <div class="col-md-auto  d-grid gap-2 d-sm-block mb-2">
        <div class="btn-group">
            <button class="btn btn-dark" id="consultar"><i class="bi bi-search"></i> Extrato</button>
            <button class="btn   btn-dark" id="resumo"><i class="bi bi-search"></i> Resumo</button>
            <button class="btn  btn-dark" id="openReport" type="button"><i class="bi bi-printer"></i> Imprimir</button>
        </div>
    </div>
</div>

<div class="tabela"></div>

<script src="js/relatorio/formar_relatorio.js"></script>
<script src="js/financeiro/extrato_financeiro/consultar_extrato_financeiro.js"></script>