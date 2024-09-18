<?php
include "../../../../modal/estoque/produto/gerenciar_produto.php";

?>
<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">Data</th>
            <th scope="col">Detalhe</th>
            <th scope="col">Mensagem</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $valor_total = 0;
        while ($linhas = mysqli_fetch_assoc($consultar_perguntas_clientes)) {

            $id =  ($linhas['duvid']);
            $data =  ($linhas['cl_data']);
            $nome =  utf8_encode($linhas['cl_nome']);
            $mensagem =  utf8_encode($linhas['cl_mensagem']);
            $descricao_prd =  utf8_encode($linhas['cl_descricao']);
            $respondido =  ($linhas['cl_respondido']);
            if ($respondido == 0) {
                $span_respondido = "<span class='badge text-bg-secondary'>Aguardando a resposta</span>";
            } else {
                $span_respondido = "<span class='badge text-bg-primary'>Respondido</span>";
            }

            
        ?>
            <tr>
                <td><?= formatarTimeStamp($data); ?></td>
                <td>
                    <?= "Cliente: $nome <hr class='mb-1'>Produto: $descricao_prd ";  ?></td>
                <td><?= ($mensagem); ?></td>
                <td><?= $span_respondido; ?></td>
                <td><button type="button" class="btn btn-sm btn-primary responder" id="<?= $id; ?>">Responder</button></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<label>
    Perguntas <?php echo $qtd; ?>
</label>
<script src="js/estoque/produto_ecommerce/table/editar_pergunta.js">