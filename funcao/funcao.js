function exportTableToCSV(filename, tabelaSelector) {
    var csv = [];
    var rows = document.querySelectorAll(tabelaSelector + " tr"); // Captura as linhas da tabela

    if (rows.length === 0) {
        console.error("Nenhuma linha encontrada na tabela.");
        return;
    }

    for (var i = 0; i < rows.length; i++) {
        var row = [];
        var cols = rows[i].querySelectorAll("td, th");

        for (var j = 0; j < cols.length; j++) {
            // Adiciona aspas ao redor do texto e substitui vírgulas por ponto e vírgula
            var text = cols[j].innerText.replace(/"/g, '""'); // Escapa aspas
            row.push('"' + text + '"'); // Adiciona aspas ao redor do texto
        }

        csv.push(row.join(",")); // Junta os valores da linha com vírgulas
    }

    // Baixar o arquivo CSV
    var csvFile = new Blob(["\ufeff" + csv.join("\n")], { // Adiciona BOM UTF-8
        type: "text/csv;charset=utf-8;"
    });
    var downloadLink = document.createElement("a");
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
}
