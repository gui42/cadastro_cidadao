$(document).ready(function() {
    $("#cadastroForm").submit(function(event) {
        event.preventDefault()
        $("#resultado").innerHTML = ''
        var nome = $("#nome").val()
        var data = {
            nome: nome
        }
        $.ajax({
            url: "app/api.php",
            type: "PUT",
            dataType: "json",
            data: JSON.stringify(data),
            contentType: "application/json; charset=utf-8",
            success: function(response) {
                $("#nome").val("")
                atualizaSpan(response)
                atualizaVizualizar()
            },
            error: function(xhr, status, error) {
                alert("Erro ao cadastrar pessoa");
            }
        })
    })

    $("#formSearch").submit(function(event) {
        event.preventDefault()
        var valor_busca = $("#valorBusca").val()
        var criterio_busca = document.querySelector('input[name="criterio-busca"]:checked').value
        var data = {
            valor_busca,
            criterio_busca
        }
        $.ajax({
            url: "app/api.php",
            type: "POST",
            dataType: "json",
            data: JSON.stringify(data),
            contentType: "application/json; charset=utf-8",
            success: function(response) {
                resultado = response.data
                if(resultado.length){
                    atualizaTableResultado('table-busca-body', resultado)
                }else{
                    atualizaTableSemResultado('table-busca-body')
                }
            },
            error: function(xhr, status, error) {
                alert("Erro ao cadastrar pessoa: " + xhr.responseText);
            }
        })
    })
    atualizaVizualizar()
})

function atualizaVizualizar(){
    $.ajax({
        url: "app/api.php",
        type: "GET",
        dataType: "json",
        contentType: "application/json; charset=utf-8",
        success:function(response){
            if(response.success){
                data = response.data
                if(data.length)
                    atualizaTableResultado('table-pessoas-body', data)
                else
                    atualizaTableSemResultado('table-pessoas-body')
            }
        }
    })
}
function atualizaSpan(resultado){
    span = document.getElementById('resultado')
    span.innerHTML = ''
    if(resultado.success){
        text = document.createTextNode(`${resultado.data.nome} - ${resultado.data.NIS}`)
        span.appendChild(text);
    }
}

function atualizaTableResultado(table_id, resultado){
    table = document.getElementById(table_id);
    table.innerHTML = ''
    resultado.forEach(pv=>{
        row = document.createElement("tr")
        table_data_nome = criarTableData(pv['nome'])
        table_data_NIS = criarTableData(pv['NIS'])
        row.appendChild(table_data_nome)
        row.appendChild(table_data_NIS)
        table.appendChild(row)
    })
    
}

function atualizaTableSemResultado(table_id){
    table = document.getElementById(table_id);
    table.innerHTML = ''
    node = document.createTextNode("Cidadão não encontrado");
    table.appendChild(node);
}

function criarTableData(conteudo){
    table_data = document.createElement('td');
    table_conteudo = document.createTextNode(conteudo)
    table_data.appendChild(table_conteudo)
    return table_data
}

function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}