<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet" crossorigin="anonymous">
</head>

<body class="antialiased">
    <div class="container mt-5">
        <h2 class="mb-4">Cadastro de Vendas</h2>

        <div>
            <div class="mb-3">
                <label for="produtos" class="form-label">Selecione o Produto</label>
                <select class="form-select" id="produtos" required>
                    <option value="sem-produtos" selected disabled>Selecione um produto</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="data_venda" class="form-label">Data de Venda</label>
                <input type="date" class="form-control" id="data_venda" required>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    <label for="inputEndereco" class="form-label">CEP</label>
                    <input type="text" class="form-control" id="cep" maxlength="9">
                </div>
                <div class="col-lg-3">
                    <label for="inputEndereco" class="form-label">Estado</label>
                    <select id="uf" class="form-select" name="uf">
                        <option value="" selected disabled>Selecione um estado</option>
                        <option value="AC">Acre</option>
                        <option value="AL">Alagoas</option>
                        <option value="AP">Amapá</option>
                        <option value="AM">Amazonas</option>
                        <option value="BA">Bahia</option>
                        <option value="CE">Ceará</option>
                        <option value="DF">Distrito Federal</option>
                        <option value="ES">Espírito Santo</option>
                        <option value="GO">Goiás</option>
                        <option value="MA">Maranhão</option>
                        <option value="MT">Mato Grosso</option>
                        <option value="MS">Mato Grosso do Sul</option>
                        <option value="MG">Minas Gerais</option>
                        <option value="PA">Pará</option>
                        <option value="PB">Paraíba</option>
                        <option value="PR">Paraná</option>
                        <option value="PE">Pernambuco</option>
                        <option value="PI">Piauí</option>
                        <option value="RJ">Rio de Janeiro</option>
                        <option value="RN">Rio Grande do Norte</option>
                        <option value="RS">Rio Grande do Sul</option>
                        <option value="RO">Rondônia</option>
                        <option value="RR">Roraima</option>
                        <option value="SC">Santa Catarina</option>
                        <option value="SP">São Paulo</option>
                        <option value="SE">Sergipe</option>
                        <option value="TO">Tocantins</option>
                        <option value="EX">Estrangeiro</option>
                    </select>
                </div>
                <div class="col-lg-3">
                    <label for="inputEndereco" class="form-label">Cidade</label>
                    <input type="text" class="form-control" id="cidade" required>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <label for="inputEndereco" class="form-label">Bairro</label>
                    <input type="text" class="form-control" id="bairro" required>
                </div>
                <div class="col-lg-3">
                    <label for="inputEndereco" class="form-label">Rua</label>
                    <input type="text" class="form-control" id="rua" required>
                </div>
                <div class="col-lg-3">
                    <label for="inputEndereco" class="form-label">Numero</label>
                    <input type="text" class="form-control" id="numero"  pattern="^[0-9]+$" required>
                </div>
                <div class="col-lg-3">
                    <label for="inputEndereco" class="form-label">Complemento</label>
                    <input type="text" class="form-control" id="complemento" required>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    <button type="submit" id="cadastrar" class="btn btn-success mt-3 w-100" onclick="cadastrarVenda()">Cadastrar Venda</button>
                </div>
                <div class="col-lg-6">
                    
                </div>
                <div class="col-lg-3">
                    <button type="submit" class="btn btn-primary mt-5 w-100" onclick="pesquisarVenda()">Pesquisar</button>
                </div>
            </div>
            
        </div>

        <hr class="my-4">
        <div class="loading"><h3>Aguarde...</h3></div>
        <div class="tabela_vendas">
            <h3>Vendas Registradas</h3>
            <table id="tabelaVendas" class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Fornecedor</th>
                        <th>Data Venda</th>
                        <th>Endereço de entrega</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" style="text-align:right">Total:</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

</body>
<script src="{{ asset('js/index.js') }}"></script>

</html>
