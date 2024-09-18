var id_nf_fiscal = document.getElementById("nf_id")
var codigo_nf_fiscal = document.getElementById("codigo_nf")
var cpf = document.getElementById("cpf")
show_cpf_nota(id_nf_fiscal.value, codigo_nf_fiscal.value)
//mostrar as informações no formulario show
//formulario para alterar
$(".adicionar_cpf").click(function () {
    alter_cpf_nota(id_nf_fiscal.value, cpf.value)
})


function alter_cpf_nota(nf_id, cpf) {
    $.ajax({
        type: "POST",
        data: "venda_mercadoria=true&acao=update_cpf&nf_id=" + nf_id + "&cpf=" + cpf,
        url: "modal/venda/venda_mercadoria/gerenciar_venda.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: $dados.title,
                showConfirmButton: false,
                timer: 3500
            })

        } else {
            Swal.fire({
                icon: 'error',
                title: 'Verifique!',
                text: $dados.title,
                timer: 7500,

            })

        }
    }

    function falha() {
        console.log("erro");
    }

}

function show_cpf_nota(id, codigo_nf) {
    $.ajax({
        type: "POST",
        data: "venda_mercadoria=true&acao=show&nf_id=" + id + "&codigo_nf=" + codigo_nf,
        url: "modal/venda/venda_mercadoria/gerenciar_venda.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {

        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#cpf").val($dados.valores['cpf_cnpj_avulso_nf'])
        }
    }

    function falha() {
        console.log("erro");
    }

}