
$('.remover_produto_ajuste').click(function () {
    var item_indice = $(this).attr("item_indice")
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja remover esse ajuste",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            remove_item(codigo_nf, item_indice)
        }
    })

})

function remove_item(codigo_nf, item_indice) {

    // localStorage.removeItem(codigo_nf[item_indice])
    // Recupera o valor do localStorage
    var localStorageValue = localStorage.getItem(codigo_nf); // Substitua 'seuNomeDeChave' pela chave correta

    // Verifica se o valor existe no localStorage
    if (localStorageValue) {
        // Parse o valor para obter o array
        var arrayNoLocalStorage = JSON.parse(localStorageValue);

        // Remove o item de índice 0 do array
        arrayNoLocalStorage.splice(item_indice, 1);

        // Armazena o array atualizado de volta no localStorage
        localStorage.setItem(codigo_nf, JSON.stringify(arrayNoLocalStorage));

   
        tabela_produtos_localstorage(codigo_nf, arrayNoLocalStorage);

        Swal.fire({
            position: 'center',
            icon: 'success',
            title: "Ajuste removido com sucesso",
            showConfirmButton: false,
            timer: 3500
        })

    }
}
