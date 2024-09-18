<?php
include "../../../../modal/anexo/gerenciar_anexo.php";
?>

<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">Código</th>
            <th scope="col">Descrição</th>
            <th scope="col">Arquivo</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $valor_total = 0;
        while ($linha = mysqli_fetch_assoc($consulta)) {
            $id = $linha['cl_id'];
            $descricao = utf8_encode($linha['cl_descricao']);
            $arquivo = utf8_encode($linha['cl_arquivo']);
            $nome_original = utf8_encode($linha['cl_nome_original']);
        ?>
            <tr>
                <th><?= ($id); ?></th>
                <td><?= $descricao; ?></td>
                <td><a href="arquivos/anexo/<?= $arquivo; ?>" target="_blank" title="<?= $nome_original; ?>"><i class="fs-5 bi bi-file-earmark"></i></a></td>
                <td><button type="buttom" id='<?php echo $id; ?>' class="btn btn-sm btn-danger remover_arquivo"><i class="bi bi-trash3"></i></button></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<label>
    Registros <?php echo $qtd; ?>
</label>
<script src="js/include/anexo/table/editar_anexo.js"></script>