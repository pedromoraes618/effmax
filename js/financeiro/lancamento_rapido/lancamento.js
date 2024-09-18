const formulario_post = document.getElementById("lancamento_rapido");
let id_formulario = document.getElementById("id")
let tipo = document.getElementById("tipo")

//modal para consultar o cliente
$("#modal_parceiro").click(function () {
    $.ajax({
        type: 'GET',
        data: "adicionar_lancamento_financeiro=true&tipo=RECEITA",
        url: "view/include/parceiro/pesquisa_parceiro.php",
        success: function (result) {
            return $(".modal_externo").html(result) + $("#modal_pesquisa_parceiro").modal('show');

        },
    });
});


$("#lancamento_rapido").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);

    if (id_formulario.value == "") {//cadastrar
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja adicionar esse lançamento?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                var retorno = create(formulario, tipo.value)
            }
        })
    } else {//editar
        //e.preventDefault()
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja alterar esse lançamento",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                var retorno = update(formulario)
            }
        })
    }


})

function create(dados, tipo) {

    $.ajax({
        type: "POST",
        data: "formulario_lancamento_financeiro=true&acao=create_lancamento_rapido&" + dados.serialize() + "&tipo=" + tipo,
        url: "modal/financeiro/lancamento_financeiro/gerenciar_lancamento_financeiro.php",
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
            formulario_post.reset(); // redefine os valores do formulário
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
$(document).ready(function () {
    $('.select2-modal').select2({
        dropdownParent: $('#modal_lancamento_rapido'), // Para garantir que funcione no modal
        width: '100%',

    });
});