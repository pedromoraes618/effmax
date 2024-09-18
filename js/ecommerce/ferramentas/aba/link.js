

$("#deep_link").submit(function (e) {
    e.preventDefault()
    var formulario = $(this);
    var retorno = update_link(formulario, "gerar_link")
})

function update_link(dados, acao) {
    $(".titulo-link-gerado").html()
    $.ajax({
        type: "POST",
        data: "formulario_ferramentas_ecommerce=true&acao=" + acao + "&" + dados.serialize(),
        url: "modal/ecommerce/ferramentas/gerenciar_ferramentas.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $("#url_link_deep_link").val($dados.link)
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

$("#copiar_link").click(function () {
    copiar()
})
function copiar() {
    let inputUrl = document.getElementById("url_link_deep_link")
    inputUrl.select()
    inputUrl.setSelectionRange(0, 99999)
    navigator.clipboard.writeText(inputUrl.value)
}