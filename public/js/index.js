var tabela = $('#tabelaVendas').DataTable({

    dom: 'Blfrtip',

    "pageLength": -1,

    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],

    columns: [
        {
            data: "name",
            sClass: "text-center"
        },
        {
            data: "price_at_sale",
            sClass: "text-center",
            render: function (data, type, row) {
                // Formata o valor como moeda brasileira
                return parseFloat(data).toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL',
                });
            }
        },
        {
            data: "supplier",
            sClass: "text-center"
        },
        {
            data: "date_sale",
            sClass: "text-center"
        },
        {
            data: "address",
            sClass: "text-center"
        },
    ],

    language: {
        "lengthMenu": "Exibindo _MENU_ linhas por página",
        "sInfo": "Mostrando _START_ até _END_ de _TOTAL_ registros.",
        "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
        "sInfoFiltered": "(Filtrados de _MAX_ registros)",
        "zeroRecords": "Nenhum registro encontrado",
        "info": "Exibindo página _PAGE_ de _PAGES_",
        "infoEmpty": "Nenhum registro encontrado",
        "infoFiltered": "(filtrando  de _MAX_ linhas)",
        "search": "Filtro geral:",
        "loadingRecords": "Carregando ...",
        paginate: {
            "first": "Primeiro",
            "last": "Ultimo",
            "next": "Próximo",
            "previous": "Anterior"
        },
    },
    "footerCallback": function (row, data, start, end, display) {
        var api = this.api();

        // Calcula a soma do price_at_sale
        var total = api
            .column(1, { page: 'current' })
            .data()
            .reduce(function (a, b) {
                return parseFloat(a) + parseFloat(b);
            }, 0);

        // Atualiza a célula do rodapé com o valor total formatado como moeda brasileira
        $(api.column(1).footer()).html('Total: ' + total.toLocaleString('pt-BR', {
            style: 'currency',
            currency: 'BRL',
        }));
    },
});
document.addEventListener('DOMContentLoaded', async function () {
    $('.tabela_vendas').hide();
    $('.loading').hide();

    try {
        var url = 'http://localhost:8989/getProducts';

        var response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
        });

        var resultado = await response.json();

        var select = document.getElementById('produtos');

        select.innerHTML = '';

        resultado.forEach(function (item) {
            console.log(item)
            var option = document.createElement('option');
            option.value = item.reference; // Substitua 'id' pelo nome do atributo no seu objeto resultado
            option.text = item.name; // Substitua 'name' pelo nome do atributo no seu objeto resultado
            select.add(option);
        });

        $('#produtos').select2({
            matcher: function (params, data) {
                if ($.trim(params.term) === '') { return data; }

                if (typeof data.text === 'undefined') { return null; }

                var q = params.term.toLowerCase();
                if (data.text.toLowerCase().indexOf(q) > -1 || data.id.toLowerCase().indexOf(q) > -1) {
                    return $.extend({}, data, true);
                }

                // Return `null` if the term should not be displayed
                return null;
            }
        });
    } catch (error) {
        // Lide com erros de rede ou JSON parsing
        console.error('Erro:');
    }
});


function formatarCEP(cep) {
    var cep = cep.replace(/\D/g, ''); // Remove caracteres não numéricos
    var cep = cep.replace(/^(\d{5})(\d{3})$/, '$1-$2'); // Adiciona a máscara
    return cep;
}

// Adiciona um listener ao input para formatar o CEP enquanto é digitado
document.getElementById('cep').addEventListener('input', function () {
    this.value = formatarCEP(this.value);
});

function limpa_formulário_cep() {
    //Limpa valores do formulário de cep.
    document.getElementById('rua').value = ("");
    document.getElementById('bairro').value = ("");
    document.getElementById('cidade').value = ("");
    document.getElementById('uf').value = "";
}


async function pesquisacep(valor) {
    var cep = valor.replace(/\D/g, '');

    if (cep != "") {

        var validacep = /^[0-9]{8}$/;

        if (validacep.test(cep)) {

            document.getElementById('rua').value = "...";
            document.getElementById('bairro').value = "...";
            document.getElementById('cidade').value = "...";
            document.getElementById('uf').value = "";

            const url = 'https://viacep.com.br/ws/'+ cep + '/json/';
           
            const response = await fetch(url);
            const resultado = await response.json();

            console.log(resultado)
            if (resultado.erro){
                alert("Cep Não Encontrado");
                limpa_formulário_cep();
            }else{
                document.getElementById('rua').value = resultado.logradouro;
                document.getElementById('bairro').value = resultado.bairro;
                document.getElementById('cidade').value = resultado.localidade;
                document.getElementById('uf').value = resultado.uf
            }

           

           

        } 
        else {
            limpa_formulário_cep();
            alert("Formato de CEP inválido.");
        }
    } 
    else {
       
        limpa_formulário_cep();
    }
};

let cepInput = document.getElementById('cep');

cepInput.addEventListener('input', function () {
    // Verifica se o comprimento do valor é igual a 9
    if (this.value.length === 9) {
        // Chama a função quando o nono caractere é digitado
        pesquisacep(this.value);
    }else{
        limpa_formulário_cep();
    }
});
async function cadastrarVenda(){
    $('#cadastrar').attr('disabled', true);
    $('#cadastrar').text('Salvando...');
    const dados = {
        produtos: document.getElementById('produtos').value,
        data_venda: document.getElementById('data_venda').value,
        cep: document.getElementById('cep').value,
        uf: document.getElementById('uf').value,
        cidade: document.getElementById('cidade').value,
        bairro: document.getElementById('bairro').value,
        rua: document.getElementById('rua').value,
        numero: document.getElementById('numero').value,
        complemento: document.getElementById('complemento').value,
    };
    url = 'http://localhost:8989/insertSale'
    try {
        var response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(dados)
        });

        if (response.ok) {
            const result = await response.json();
            $('#cadastrar').text('Cadastrar Venda');
            $('#cadastrar').attr('disabled', false);
            pesquisarVenda()

        } else if (response.status === 422) {
            $('#cadastrar').text('Cadastrar Venda');
            $('#cadastrar').attr('disabled', false);
            const elementosErro = document.querySelectorAll('.message_error');

            elementosErro.forEach(elemento => {
                elemento.parentNode.removeChild(elemento);
            });
            const errorDetails = await response.json();
            for (const campoId in errorDetails.error) {

                
                inserirMensagemErro(campoId, errorDetails.error[campoId][0]);
            }
        } else {
            alert('Houve um erro interno')
            $('#cadastrar').text('Cadastrar Venda');
            $('#cadastrar').attr('disabled', false);
        }
        
        
    } catch (error) {
        alert('Houve um erro interno')
        $('#cadastrar').text('Cadastrar Venda');
        $('#cadastrar').attr('disabled', false);

    }
        
    
}
function inserirMensagemErro(campoId, mensagem) {
    const campo = document.getElementById(campoId);

    // Cria uma div para a mensagem de erro
    const divErro = document.createElement('div');
    divErro.className = 'error-message';
    divErro.innerHTML = `<p class="message_error" style="color: red;">${mensagem}</p>`;

    // Insere a div após o campo
    campo.parentNode.insertBefore(divErro, campo.nextSibling);
}
async function pesquisarVenda(){
    $('.loading').show();
    $('.tabela_vendas').hide();
    url = 'http://localhost:8989/getSales'
    try {
        var response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
        });

        if (response.ok) {
            $('.loading').hide();
            $('.tabela_vendas').show();
            const result = await response.json();
            tabela.clear();
            tabela.rows.add(result).draw()

        } else {
            $('.loading').hide();
            alert('Houve um erro interno')
        }


    } catch (error) {
        $('.loading').hide();
        alert('Houve um erro interno')

    }
}