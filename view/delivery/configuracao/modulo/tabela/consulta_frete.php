<?php

include "../../../../../modal/delivery/configuracao/gerenciar_configuracao.php";
?>

<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">Bairro</th>
            <th scope="col">Valor</th>
            <th scope="col">Frete Grátis até</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        <?php while ($linha = mysqli_fetch_assoc($consulta_frete_delivery)) {
            $id = ($linha['cl_id']);
            $bairro = utf8_encode($linha['cl_bairro']);
            $valor = $linha['cl_valor'];
            $promocao_frete_gratis_delivery = $linha['cl_promocao_frete_gratis_delivery'];
            if (($promocao_frete_gratis_delivery != "" and $promocao_frete_gratis_delivery != "0000-00-00") and $promocao_frete_gratis_delivery < $data_lancamento) { //promoção não é mais valida
                $promocao_valida = false;
            } else {
                $promocao_valida = true;
            }
        ?>
            <tr>
                <input type="hidden" name="id<?php echo $id; ?>" class="form-control" value="<?php echo $id; ?>">
                <td><?php echo $bairro; ?></td>
                <td><input type="text" name="valor<?php echo $id; ?>" class="form-control" value="<?php echo $valor; ?>"></td>
                <td><input type="date" name="data_promocao<?php echo $id; ?>" class="form-control <?php if ($promocao_valida == false) {
                                                                echo 'is-invalid';
                                                            } ?>" value="<?php echo $promocao_frete_gratis_delivery; ?>">
                    <div id="validationServer03Feedback" class="invalid-feedback">
                        Promoção não é mais valida
                    </div>
                </td>
                <!-- <td><button type="button" id="<?php echo $id; ?>" title='Remover item' class="btn btn-sm btn-danger remover_frete"><i style="font-size: 1.4em;" class="bi bi-trash"></i></button></td> -->

            </tr>
        <?php } ?>
    </tbody>
</table>



<script src="js/delivery/configuracao/modulo/tabela/consulta_frete.js"></script>