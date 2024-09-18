//ao clicar no botão cadastrar produto
$(".realizar_ajuste").click(function(e) {
    $(".bloco-pesquisa-menu .bloco-pesquisa-1").css("display","block")
    $(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display","block")
  //  $(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display","none") // aparecer tela de cadastro
 var id_produto = $(this).attr("id_produto")
  $.ajax({
        type: 'GET',
        data: "editar_produto=true&id_produto="+id_produto,
        url: "view/estoque/ajuste_estoque/cadastro_ajuste_estoque.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });

    $.ajax({
        type: 'GET',
        data: "consultar_ajuste_estoque=true&id_produto="+id_produto,
        url: "view/estoque/ajuste_estoque/table/consultar_historico_ajuste_estoque.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-2").html(result);
        },
    });
})



$("#voltar_consulta").click(function(e) {
  
    //consultar informação tabela
    $(".bloco-pesquisa-menu .bloco-pesquisa-1").css("display","block")
    $(".bloco-pesquisa-menu .bloco-pesquisa-2").css("display","none") // aparecer tela de cadastro
    $.ajax({
        type: 'GET',
        data: "consultar_ajuste_estoqueo=inicial",
        url: "view/estoque/ajuste_estoque/consultar_ajuste_estoque.php",
        success: function(result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1").html(result);
        },
    });
    })
    
    

const fomulario_produto = document.getElementById("cadastrar_ajuste_estoque");
var id_produto = document.getElementById("id_produto");


//formulario para cadstro
$("#cadastrar_ajuste_estoque").submit(function(e) {
    e.preventDefault()
    var formulario = $(this);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja realizar esse ajuste?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = ajuste_estoque(formulario,id_produto.value)
        } 
    })

})

function ajuste_estoque(dados,id_produto) {
    $.ajax({
        type: "POST",
        data: dados.serialize(),
        url: "modal/estoque/ajuste_estoque/gerenciar_ajuste_estoque.php",
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
            var estoque = document.getElementById("est_atual");
          
            //$("#est_atual").value = $dados.qtd// atualizar valor no estoque atual
            
             //RECARREGAR TABELA
             $.ajax({
                type: 'GET',
                data: "consultar_ajuste_estoque=true&id_produto="+id_produto,
                url: "view/estoque/ajuste_estoque/table/consultar_historico_ajuste_estoque.php",
                success: function(result) {
                    return $(".bloco-pesquisa-menu .bloco-pesquisa-2").html(result), estoque.value = $dados.qtd;
                },
            });

            fomulario_produto.reset(); // redefine os valores do formulário
           
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
