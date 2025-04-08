<?php
include('API/_config.php');
include('API/_lista-produtos.php');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="plugins/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="plugins/fontawesome-free-6.6.0-web/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/lista-produtos.css">
</head>

<body class="bg-light">
    <div class="container-fluid p-0">
        <div class="p-3">
            <div class="card-header bg-white border-0 p-0">
                <div class="d-flex justify-content-between align-items-center p-3 my-3 border rounded">
                    <h3 class="mb-0 text-primary">Lista de Produtos</h3>

                    <div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cadastroModal">
                            <i class="fas fa-plus me-2"></i>Solicitar Cadastro
                        </button>
                    </div>
                </div>
                <div>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-container">
                                <div id="loading-message" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Carregando...</span>
                                    </div>
                                    <p class="mt-2">Carregando produtos...</p>
                                </div>
                                <table id="produtosTable" class="table table-striped table-hover" style="display: none;">
                                    <thead>
                                        <tr>
                                            <th>SKU</th>
                                            <th>Estoque</th>
                                            <th>Nome</th>
                                            <th>Marca</th>
                                            <th>Peso</th>
                                            <th>Venda</th>
                                            <th>Compra</th>
                                            <th>Grupo</th>
                                            <th>Subgrupo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($resposta->data) && !empty($resposta->data)) {
                                            // Sort products alphabetically by name
                                            usort($resposta->data, function ($a, $b) {
                                                return strcmp($a->nome, $b->nome);
                                            });


                                            foreach ($resposta->data as $produto) {
                                                $marcas_excluidas = ['frigonovak', 'bella vista', 'diehl'];

                                                // Se a marca estiver na lista de exclusão, pula para o próximo item
                                                if (in_array(strtolower($produto->marca), $marcas_excluidas)) {
                                                    continue;
                                                }

                                                echo '
                                                    <tr class="item-tabela">
                                                        <td class="fw-medium column-codigo">' . htmlspecialchars($produto->sku, ENT_QUOTES, 'UTF-8') . '</td>
                                                        <td class="text-center column-estoque text-white">' . htmlspecialchars($produto->estoque, ENT_QUOTES, 'UTF-8') . '</td>
                                                        <td class="column-nome">' . htmlspecialchars($produto->nome, ENT_QUOTES, 'UTF-8') . '</td>
                                                        <td class="column-marca">' . htmlspecialchars($produto->marca, ENT_QUOTES, 'UTF-8') . '</td>
                                                        <td class="column-peso">' . number_format($produto->peso, 2, ',', '.') . ' ' . htmlspecialchars($produto->unidade, ENT_QUOTES, 'UTF-8') . '</td>
                                                        <td class="text-center fw-medium column-valor">R$ ' . number_format($produto->valor, 2, ',', '.') . '</td>
                                                        <td class="text-center fw-medium column-compra">R$ ' . number_format($produto->valor_compra, 2, ',', '.') . '</td>
                                                        <td class="column-grupo">' . htmlspecialchars($produto->grupo, ENT_QUOTES, 'UTF-8') . '</td>
                                                        <td class="column-subgrupo">' . htmlspecialchars($produto->subespecificacao, ENT_QUOTES, 'UTF-8') . '</td>
                                                    </tr>
                                                    ';
                                            }
                                        } else {
                                            echo '<tr><td colspan="10" class="text-center py-4">Nenhum produto encontrado</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Modal de Cadastro -->
    <div class="modal fade" id="cadastroModal" tabindex="-1" aria-labelledby="cadastroModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cadastroModalLabel">Solicitar Cadastro de Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="cadastroForm">
                        <div class="row g-2">
                            <div class="col-md-12">
                                <label for="solicitante" class="form-label">Nome do Solicitante</label>
                                <input type="text" class="form-control" id="solicitante">
                            </div>
                            <div class="col-md-4">
                                <label for="nomeProduto" class="form-label">Nome do Produto</label>
                                <input type="text" class="form-control" id="nomeProduto">
                            </div>
                            <div class="col-md-4">
                                <label for="skuProduto" class="form-label">SKU</label>
                                <input type="text" class="form-control" id="skuProduto">
                            </div>
                            <div class="col-md-4">
                                <label for="marcaProduto" class="form-label">Marca</label>
                                <input type="text" class="form-control" id="marcaProduto">
                            </div>
                            <div class="col-md-4">
                                <label for="categoriaProduto" class="form-label">Categoria</label>
                                <select class="form-select" id="categoriaProduto">
                                    <option value="">Selecione uma categoria</option>
                                    <option value="frios">Frios</option>
                                    <option value="secos">Secos</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="especificacaoProduto" class="form-label">Especificação</label>
                                <input type="text" class="form-control" id="especificacaoProduto">
                            </div>
                            <div class="col-md-4">
                                <label for="subespecificacaoProduto" class="form-label">Subespecificação</label>
                                <input type="text" class="form-control" id="subespecificacaoProduto">
                            </div>
                            <div class="col-md-4">
                                <label for="fornecedorProduto" class="form-label">Fornecedor</label>
                                <input type="text" class="form-control" id="fornecedorProduto">
                            </div>
                            <div class="col-md-4">
                                <label for="faturadoPorProduto" class="form-label">Faturado por</label>
                                <input type="text" class="form-control" id="faturadoPorProduto">
                            </div>
                            <div class="col-md-4">
                                <label for="precoVendaProduto" class="form-label">Preço de Venda</label>
                                <input type="number" step="0.01" class="form-control" id="precoVendaProduto">
                            </div>
                            <div class="col-md-4">
                                <label for="precoCompraProduto" class="form-label">Preço de Compra</label>
                                <input type="number" step="0.01" class="form-control" id="precoCompraProduto">
                            </div>
                            <div class="col-md-4">
                                <label for="quantidadeEmbalagemProduto" class="form-label">Qtd. Embalagem</label>
                                <input type="number" class="form-control" id="quantidadeEmbalagemProduto">
                            </div>
                            <div class="col-md-4">
                                <label for="unidadeProduto" class="form-label">Unidade</label>
                                <select class="form-select" id="unidadeProduto">
                                    <option value="">Selecione uma unidade</option>
                                    <option value="kg">KG</option>
                                    <option value="pacote">Pacote</option>
                                    <option value="litros">Litros</option>
                                    <option value="caixa">Caixa</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="pesoUnidadeProduto" class="form-label">Peso/Unidade</label>
                                <input type="number" step="0.01" class="form-control" id="pesoUnidadeProduto">
                            </div>
                            <div class="col-md-4">
                                <label for="itensEmbalagemProduto" class="form-label">Itens/Embalagem</label>
                                <input type="number" class="form-control" id="itensEmbalagemProduto">
                            </div>
                            <div class="col-md-4">
                                <label for="pedidoMinimoProduto" class="form-label">Pedido Mínimo</label>
                                <input type="number" class="form-control" id="pedidoMinimoProduto">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="submitCadastro">Enviar Solicitação</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Detalhes do Produto -->
    <div class="modal fade" id="detalhesModal" tabindex="-1" aria-labelledby="detalhesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalhesModalLabel">Detalhes do Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Código:</strong> <span id="modal-codigo"></span></p>
                            <p><strong>Nome:</strong> <span id="modal-nome"></span></p>
                            <p><strong>Marca:</strong> <span id="modal-marca"></span></p>
                            <p><strong>Grupo:</strong> <span id="modal-grupo"></span></p>
                            <p><strong>Subgrupo:</strong> <span id="modal-subgrupo"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Peso:</strong> <span id="modal-peso"></span></p>
                            <p><strong>Unidade:</strong> <span id="modal-unidade"></span></p>
                            <p><strong>Preço de Venda:</strong> <span id="modal-valor"></span></p>
                            <p><strong>Preço de Compra:</strong> <span id="modal-compra"></span></p>
                            <p><strong>Estoque:</strong> <span id="modal-estoque"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Iframe oculto para impressão -->
    <iframe id="printFrame" style="display: none;"></iframe>

    <script src="plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.excel.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        document.getElementById('submitCadastro').addEventListener('click', function() {
            const form = document.getElementById('cadastroForm');
            if (form.checkValidity()) {
                // Collect form data
                const formData = {
                    solicitante: document.getElementById('solicitante').value,
                    nomeProduto: document.getElementById('nomeProduto').value,
                    skuProduto: document.getElementById('skuProduto').value,
                    marcaProduto: document.getElementById('marcaProduto').value,
                    categoriaProduto: document.getElementById('categoriaProduto').value,
                    especificacaoProduto: document.getElementById('especificacaoProduto').value,
                    subespecificacaoProduto: document.getElementById('subespecificacaoProduto').value,
                    fornecedorProduto: document.getElementById('fornecedorProduto').value,
                    faturadoPorProduto: document.getElementById('faturadoPorProduto').value,
                    precoVendaProduto: document.getElementById('precoVendaProduto').value,
                    precoCompraProduto: document.getElementById('precoCompraProduto').value,
                    quantidadeEmbalagemProduto: document.getElementById('quantidadeEmbalagemProduto').value,
                    unidadeProduto: document.getElementById('unidadeProduto').value,
                    pesoUnidadeProduto: document.getElementById('pesoUnidadeProduto').value,
                    itensEmbalagemProduto: document.getElementById('itensEmbalagemProduto').value,
                    pedidoMinimoProduto: document.getElementById('pedidoMinimoProduto').value
                };
                console.log(formData)
                // Send data to generate HTML
                fetch('impressao/gerar-pdf.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(formData)
                    })
                    .then(response => {
                        if (response.ok) {
                            return response.text();
                        } else {
                            throw new Error('Erro ao gerar documento');
                        }
                    })
                    .then(html => {
                        // Usa o iframe oculto para impressão
                        const printFrame = document.getElementById('printFrame');
                        printFrame.contentDocument.write(html);
                        printFrame.contentDocument.close();

                        // Fecha o modal e reseta o formulário
                        const modal = bootstrap.Modal.getInstance(document.getElementById('cadastroModal'));
                        modal.hide();
                        form.reset();
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Erro ao gerar o documento. Por favor, tente novamente.');
                    });
            } else {
                form.reportValidity();
            }
        });
        // Inicialização do DataTables
        $(document).ready(function() {
            var table = $('#produtosTable').DataTable({
                paging: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json',
                    search: ''
                },
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                },
                autoWidth: true,
                dom: '<"row align-items-center mb-3 justify-content-end"<"col-md-4"B><"col-md-4"><"col-md-4 text-end">>' +
                    '<"container-tabela-topo rounded mb-3"' +
                    '<"row align-items-center justify-content-between"<"col-md-auto filtros-table py-1"><"col-md-auto"><"col-md-auto text-end py-1"f>>' +
                    '>' +
                    '<"row"<"col-sm-12"tr>>',

                buttons: [{
                    extend: 'excelHtml5',
                    text: `<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="25" height="25" viewBox="0,0,256,256"> <g fill="#ffffff" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.12,5.12)"><path d="M28.8125,0.03125l-28,5.3125c-0.47266,0.08984 -0.8125,0.51953 -0.8125,1v37.3125c0,0.48047 0.33984,0.91016 0.8125,1l28,5.3125c0.0625,0.01172 0.125,0.03125 0.1875,0.03125c0.23047,0 0.44531,-0.07031 0.625,-0.21875c0.23047,-0.19141 0.375,-0.48437 0.375,-0.78125v-48c0,-0.29687 -0.14453,-0.58984 -0.375,-0.78125c-0.23047,-0.19141 -0.51953,-0.24219 -0.8125,-0.1875zM32,6v7h2v2h-2v5h2v2h-2v5h2v2h-2v6h2v2h-2v7h15c1.10156,0 2,-0.89844 2,-2v-34c0,-1.10156 -0.89844,-2 -2,-2zM36,13h8v2h-8zM6.6875,15.6875h5.125l2.6875,5.59375c0.21094,0.44141 0.39844,0.98438 0.5625,1.59375h0.03125c0.10547,-0.36328 0.30859,-0.93359 0.59375,-1.65625l2.96875,-5.53125h4.6875l-5.59375,9.25l5.75,9.4375h-4.96875l-3.25,-6.09375c-0.12109,-0.22656 -0.24609,-0.64453 -0.375,-1.25h-0.03125c-0.0625,0.28516 -0.21094,0.73047 -0.4375,1.3125l-3.25,6.03125h-5l5.96875,-9.34375zM36,20h8v2h-8zM36,27h8v2h-8zM36,35h8v2h-8z"></path></g></g> </svg> Exportar para Excel`,
                    filename: function() {
                        const hoje = new Date();
                        const dia = String(hoje.getDate()).padStart(2, '0');
                        const mes = String(hoje.getMonth() + 1).padStart(2, '0');
                        const ano = hoje.getFullYear();
                        return `Relatório produtos AGA -  ${dia}-${mes}-${ano}`;
                    },
                    title: function() {
                        const hoje = new Date();
                        const dia = String(hoje.getDate()).padStart(2, '0');
                        const mes = String(hoje.getMonth() + 1).padStart(2, '0');
                        const ano = hoje.getFullYear();
                        return `Relatório produtos AGA - ${dia}/${mes}/${ano}`;
                    },
                    className: 'btn btn-success',
                    exportOptions: {
                        columns: ':visible'
                    }

                }],
                pageLength: 25,
                order: [
                    [1, 'asc']
                ], // Ordenar por nome (coluna 1) em ordem ascendente
                columnDefs: [{
                        // Configuração para colunas que devem ser visíveis em dispositivos móveis
                        targets: [1, 2, 5], // Nome, Valor e Estoque
                        className: 'd-table-cell'
                    },
                    {
                        // Configuração para colunas que devem ser ocultadas em dispositivos móveis
                        targets: [0, 3, 4, 6, 7, 8], // SKU, Marca, Peso, Compra, Grupo, Subgrupo
                        className: 'd-none d-md-table-cell'
                    },
                    {
                        // Priorização da coluna Nome
                        targets: [2],
                        width: 'auto'
                    },
                    {
                        // Redução do tamanho das outras colunas
                        targets: [3, 8],
                        width: '20%',
                    },
                    {
                        // Redução do tamanho das outras colunas
                        targets: [1, 4, 5, 6, 7],
                        width: '5%',
                        className: 'col-min'
                    },
                    {
                        targets: [0],
                        width: 'auto'
                    },
                    {
                        targets: 1, // Coluna de estoque
                        createdCell: function(td, cellData, rowData, row, col) {
                            if (cellData < 50) {
                                $(td).addClass('estoque-baixo text-white');
                            } else if (cellData < 100) {
                                $(td).addClass('estoque-medio text-white');
                            } else {
                                $(td).addClass('estoque-alto text-white');
                            }
                        }
                    },
                    {
                        targets: [4, 5], // Colunas de valor e compra
                        render: function(data, type, row) {
                            if (type === 'sort') {
                                // Para ordenação, remover R$ e converter para número
                                return parseFloat(data.replace('R$ ', '').replace('.', '').replace(',', '.'));
                            }
                            return data;
                        }
                    },
                    {
                        targets: 3, // Coluna de peso
                        render: function(data, type, row) {
                            if (type === 'sort') {
                                // Para ordenação, extrair apenas o número
                                return parseFloat(data.split(' ')[0].replace('.', '').replace(',', '.'));
                            }
                            return data;
                        }
                    }
                ],
                initComplete: function() {
                    // Criar um container para os filtros
                    var filterContainer = $('<div class="d-flex align-items-center"></div>');
                    filterContainer.appendTo($('.filtros-table'));

                    // Criar os filtros para grupo, subgrupo e marca
                    this.api().columns([7, 8, 3]).every(function() {
                        var column = this;
                        var title = $(column.header()).text();

                        // Criar select para filtro
                        var select = $('<select class="form-select form-select-sm ms-2" style="width: auto;"><option value="">Todos</option></select>')
                            .appendTo(filterContainer)
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            })
                            .on('keydown', function(e) {
                                // Limpar o filtro quando ESC for pressionado
                                if (e.key === 'Escape') {
                                    $(this).val('');
                                    column.search('', true, false).draw();
                                }
                            });

                        // Adicionar label ao select
                        var label = $('<span class="ms-2 me-1">' + title + ':</span>');
                        label.insertBefore(select);

                        // Preencher opções do select
                        column.data().unique().sort().each(function(d) {
                            select.append('<option value="' + d + '">' + d + '</option>');
                        });
                    });
                    
                    // Mostrar a tabela e ocultar a mensagem de carregamento
                    $('#produtosTable').show();
                    $('#loading-message').hide();
                }
            });
        });

        // Evento de clique na linha da tabela para abrir o modal de detalhes
        $(document).on('click', '#produtosTable tbody tr', function() {
            const row = $(this);
            const detalhesModal = new bootstrap.Modal(document.getElementById('detalhesModal'));

            // Preencher o modal com os dados do produto
            $('#modal-codigo').text(row.find('td:eq(0)').text());
            $('#modal-nome').text(row.find('td:eq(1)').text());
            $('#modal-marca').text(row.find('td:eq(2)').text());
            $('#modal-peso').text(row.find('td:eq(3)').text());
            $('#modal-valor').text(row.find('td:eq(4)').text());
            $('#modal-compra').text(row.find('td:eq(5)').text());
            $('#modal-estoque').text(row.find('td:eq(6)').text());
            $('#modal-grupo').text(row.find('td:eq(7)').text());
            $('#modal-subgrupo').text(row.find('td:eq(8)').text());

            // Exibir o modal
            detalhesModal.show();
        });
    </script>
</body>

</html>