$("#voltar").click(function (e) {
    $(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display", "none") // aparecer tela de consulta

})

const formulario_post = document.getElementById("cfop");
let form_id = document.getElementById("id")
let titulo = document.getElementById('title_modal')
let btn_form = document.getElementById('button_form')


//retorna os dados para o formulario
if (form_id.value == "") {
    $('#button_form').html('Cadastrar');
    $(".title .sub-title").html("Cadastrar cfop")
} else {
    $('#button_form').html('Alterar');
    $(".title .sub-title").html("Editar cfop")
    show(form_id.value) // funcao para retornar os dados para o formulario
}

//formulario para cadastro
$("#cfop").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);


    if (form_id.value == "") {//cadastrar
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja cadastrar esse cfop",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                var retorno = create(formulario)
            }
        })
    } else {//editar
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja alterar esse cfop",
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

function create(dados) {
    $.ajax({
        type: "POST",
        data: "formulario_cfop=true&acao=create&" + dados.serialize(),
        url: "modal/configuracao/cfop/gerenciar_cfop.php",
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
            $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta

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


function update(dados) {
    $.ajax({
        type: "POST",
        data: "formulario_cfop=true&acao=update&" + dados.serialize(),
        url: "modal/configuracao/cfop/gerenciar_cfop.php",
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
            $('#pesquisar_filtro_pesquisa').trigger('click'); // clicar automaticamente para realizar a consulta
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



//mostrar as informações no formulario show
function show(id) {
    $.ajax({
        type: "POST",
        data: "formulario_cfop=true&acao=show&form_id=" + id,
        url: "modal/configuracao/cfop/gerenciar_cfop.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
   
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#cfop_saida").val($dados.valores['cfop_saida'])
            $("#cfop_entrada").val($dados.valores['cfop_entrada'])
            $("#descricao").val($dados.valores['descricao'])
        }
    }

    function falha() {
        console.log("erro");
    }

}

