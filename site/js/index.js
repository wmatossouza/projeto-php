$(document).ready(function () {
    carregaTabela()
});

function carregaTabela(){
    $.ajax({
        type: "GET",
        url: "/php/carrega.php?acao=teste",
        dataType: "json",
        success: function (response) {
            var html = '';
            $.each(response, function (index, val) { 
                html += '<tr>'+
                            '<td>' + val.id + '</td>'+
                            '<td>' + val.nome + '</td>'+
                            '<td>' + val.endereco + '</td>'+
                            '<td>' + val. telefone + '</td>'+
                            '<td>' + val.email + '</td>'+
                            '<td>' + val.sexo + '</td>'+
                            '<td width=250>'+
                                '<a class="btn btn-primary">Info</a>'+
                                '<a class="btn btn-warning">Atualizar</a>'+
                                '<a class="btn btn-danger">Excluir</a>'+
                            '</td>'+
                        '</tr>'    
            });
            $("#tabela").html(html);
        }
    });
}