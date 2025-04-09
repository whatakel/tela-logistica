<?php
include('API/_config.php');
include('API/_lista-produtos.php');
include('API/_info-itens.php');
include('API/_relatorios.php');

// echo '<pre>'; print_r($produto); echo '</pre>';
// echo '<pre>'; print_r($pedidos); echo '</pre>';
$listaProdutos = [];
$listaVendas = [];
$itensAgrupados = [];

// Só processa os pedidos se as datas foram fornecidas
if (isset($_GET['DataInicial']) && isset($_GET['DataFinal'])) {
    // Usar os dados já processados em _info-itens.php
    if (!empty($itens_agrupados)) {
        foreach ($itens_agrupados as $descricao => $quantidade) {
            $itensAgrupados[$descricao] = [
                'descricao' => $descricao,
                'quantidade' => $quantidade
            ];
        }
    } else {
        // Fallback para o processamento original caso $itens_agrupados esteja vazio
        foreach ($pedidos as $pedido) {
            // Remove espaços em branco nas pontas e normaliza o texto
            $descricao = trim($pedido->Descricao);
            $qtde = floatval($pedido->Qtde); // Garante que é número

            // Cria um nó para o produto se não existir
            if (!isset($itensAgrupados[$descricao])) {
                $itensAgrupados[$descricao] = [
                    'descricao' => $descricao,
                    'quantidade' => 0
                ];
            }
            
            // Soma a quantidade ao nó existente
            $itensAgrupados[$descricao]['quantidade'] += $qtde;
        }
    }
}

// // Exibir agrupado
// foreach ($itensAgrupados as $descricao => $total) {
//     echo $descricao . ": " . $total . "<br>";
// }



foreach ($resposta->data as $produto) {
    $listaProdutos[] = [
        'nome' => $produto->nome
    ];
};

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
    <title>Relatórios</title>
</head>
<Style>
    #vendasTable_filter {
        margin-bottom: 0px;
        ;
    }

    ;
</Style>

<body class="bg-light">
    <div class="container-fluid p-0">
        <div class="p-3">
            <div class="card-header bg-white border-0 p-0">
                <div class="d-flex justify-content-between align-items-center p-3 my-3 border rounded">
                    <h3 class="mb-0 text-primary">Histórico de Vendas</h3>
                </div>

                <!-- Filtro de Datas -->
                <div class="card mb-3">
                    <div class="card-body">
                        <form id="filtroDatasForm" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="dataInicial" class="form-label">Data Inicial</label>
                                <input type="date" class="form-control" id="dataInicial" required>
                            </div>
                            <div class="col-md-4">
                                <label for="dataFinal" class="form-label">Data Final</label>
                                <input type="date" class="form-control" id="dataFinal" required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100" id="gerarRelatorio">
                                    <i class="fas fa-search me-2"></i>Gerar Relatório
                                </button>
                            </div>
                        </form>
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
                                    <p class="mt-2">Carregando dados de vendas...</p>
                                </div>
                                <table id="vendasTable" class="table table-striped table-hover" style="display: none;">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Vendido</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Verificar se existem itens agrupados
                                        if (!empty($itensAgrupados)) {
                                            // Ordenar os itens por quantidade (decrescente)
                                            arsort($itensAgrupados);

                                            // Loop através dos itens agrupados
                                            foreach ($itensAgrupados as $descricao => $item) {
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($descricao, ENT_QUOTES, 'UTF-8') . "</td>";
                                                echo "<td class='text-center'>" . number_format($item['quantidade'], 2, ',', '.') . "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            // Adicionar uma linha vazia para manter a estrutura da tabela
                                            echo "<tr>";
                                            echo "<td></td>";
                                            echo "<td></td>";
                                            echo "</tr>";
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
        // Função para converter data para formato brasileiro
        function converterParaFormatoBrasileiro(data) {
            if (!data) return '';
            const [ano, mes, dia] = data.split('-');
            return `${dia}/${mes}/${ano}`;
        }

        // Função para converter data para formato com hífens (para nome do arquivo)
        function converterParaFormatoComHifens(data) {
            if (!data) return '';
            const [ano, mes, dia] = data.split('-');
            return `${dia}-${mes}-${ano}`;
        }

        // Definir data inicial como primeiro dia do mês atual
        document.addEventListener('DOMContentLoaded', function() {
            const hoje = new Date();
            const primeiroDiaMes = new Date(hoje.getFullYear(), hoje.getMonth(), 1);

            // Formatar a data de hoje para o formato YYYY-MM-DD
            const hojeFormatado = hoje.toISOString().split('T')[0];

            // Definir a data máxima como hoje para ambos os campos
            document.getElementById('dataInicial').max = hojeFormatado;
            document.getElementById('dataFinal').max = hojeFormatado;

            // Adicionar evento de clique para abrir o calendário ao clicar em qualquer parte do input
            document.getElementById('dataInicial').addEventListener('click', function() {
                this.showPicker();
            });

            document.getElementById('dataFinal').addEventListener('click', function() {
                this.showPicker();
            });

            // Adicionar validação para data final não ser menor que a inicial
            document.getElementById('dataInicial').addEventListener('change', function() {
                const dataInicial = new Date(this.value);
                const dataFinal = new Date(document.getElementById('dataFinal').value);
                
                if (dataFinal < dataInicial) {
                    alert('A data final não pode ser menor que a data inicial.');
                }
                
                // Definir a data mínima para o campo de data final
                document.getElementById('dataFinal').min = this.value;
            });

            // Adicionar validação para data final
            document.getElementById('dataFinal').addEventListener('change', function() {
                const dataInicial = new Date(document.getElementById('dataInicial').value);
                const dataFinal = new Date(this.value);
                
                if (dataFinal < dataInicial) {
                    alert('A data final não pode ser menor que a data inicial.');
                }
            });

            // Inicializar DataTable
            inicializarDataTable();
        });

        // Função para inicializar o DataTable
        function inicializarDataTable() {
            var table = $('#vendasTable').DataTable({
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
                dom: '<"container-tabela-topo rounded p-2"' +
                    '<"row align-items-center justify-content-between gap-3"<"col-md-3"B><"col-md-4"<"periodo-info">><"col-md-4 text-end mb-0"f>>' +
                    '>' +
                    '<"row"<"sm-12"tr>>',

                buttons: [{
                    extend: 'excelHtml5',
                    text: `<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="25" height="25" viewBox="0,0,256,256"> <g fill="#ffffff" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.12,5.12)"><path d="M28.8125,0.03125l-28,5.3125c-0.47266,0.08984 -0.8125,0.51953 -0.8125,1v37.3125c0,0.48047 0.33984,0.91016 0.8125,1l28,5.3125c0.0625,0.01172 0.125,0.03125 0.1875,0.03125c0.23047,0 0.44531,-0.07031 0.625,-0.21875c0.23047,-0.19141 0.375,-0.48437 0.375,-0.78125v-48c0,-0.29687 -0.14453,-0.58984 -0.375,-0.78125c-0.23047,-0.19141 -0.51953,-0.24219 -0.8125,-0.1875zM32,6v7h2v2h-2v5h2v2h-2v5h2v2h-2v6h2v2h-2v7h15c1.10156,0 2,-0.89844 2,-2v-34c0,-1.10156 -0.89844,-2 -2,-2zM36,13h8v2h-8zM6.6875,15.6875h5.125l2.6875,5.59375c0.21094,0.44141 0.39844,0.98438 0.5625,1.59375h0.03125c0.10547,-0.36328 0.30859,-0.93359 0.59375,-1.65625l2.96875,-5.53125h4.6875l-5.59375,9.25l5.75,9.4375h-4.96875l-3.25,-6.09375c-0.12109,-0.22656 -0.24609,-0.64453 -0.375,-1.25h-0.03125c-0.0625,0.28516 -0.21094,0.73047 -0.4375,1.3125l-3.25,6.03125h-5l5.96875,-9.34375zM36,20h8v2h-8zM36,27h8v2h-8zM36,35h8v2h-8z"></path></g></g> </svg> Exportar para Excel`,
                    filename: function() {
                        const dataInicial = document.getElementById('dataInicial').value;
                        const dataFinal = document.getElementById('dataFinal').value;
                        
                        if (dataInicial && dataFinal) {
                            const [anoInicial, mesInicial, diaInicial] = dataInicial.split('-');
                            const [anoFinal, mesFinal, diaFinal] = dataFinal.split('-');
                            return `AGA - Relatório de Vendas - ${diaInicial}-${mesInicial}-${anoInicial} até ${diaFinal}-${mesFinal}-${anoFinal}`;
                        }
                        return 'AGA - Relatório de Vendas';
                    },
                    title: function() {
                        const dataInicial = document.getElementById('dataInicial').value;
                        const dataFinal = document.getElementById('dataFinal').value;
                        
                        if (dataInicial && dataFinal) {
                            const dataInicialBR = converterParaFormatoBrasileiro(dataInicial);
                            const dataFinalBR = converterParaFormatoBrasileiro(dataFinal);
                            return `AGA - Relatório de Vendas - ${dataInicialBR} até ${dataFinalBR}`;
                        }
                        return 'AGA - Relatório de Vendas';
                    },
                    className: 'btn btn-success',
                    exportOptions: {
                        columns: ':visible',
                        format: {
                            body: function(data, row, column, node) {
                                // Formatar números com separador de milhares e vírgula decimal
                                if (column === 1) { // Coluna de quantidade
                                    return data.replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                }
                                return data;
                            }
                        }
                    },
                    customize: function(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        $('row c[r^="B"]', sheet).attr('s', '2'); // Estilo para coluna de números
                    }
                }],
                pageLength: 25,
                order: [
                    [1, 'desc']
                ],
                columnDefs: [{
                        targets: [0, 1],
                        className: 'd-table-cell'
                    },
                    {
                        targets: [0],
                        width: 'auto'
                    },
                    {
                        targets: [1],
                        width: '20%',
                        className: 'text-center'
                    }
                ],
                initComplete: function() {
                    var filterContainer = $('<div class="d-flex align-items-center"></div>');
                    filterContainer.appendTo($('.filtros-table'));

                    // Adicionar mensagem quando não houver dados
                    if ($('#vendasTable tbody tr td').length === 0 || 
                        ($('#vendasTable tbody tr td').length === 2 && 
                         $('#vendasTable tbody tr td:first').text().trim() === '')) {
                        $('#vendasTable tbody').html('<tr><td colspan="2">Nenhum pedido encontrado</td></tr>');
                        // Esconder botão Excel e barra de busca quando não houver dados
                        $('.dt-buttons').hide();
                        $('.dataTables_filter').hide();
                    } else {
                        // Mostrar botão Excel e barra de busca quando houver dados
                        $('.dt-buttons').show();
                        $('.dataTables_filter').show();
                    }

                    // Obter parâmetros da URL
                    const urlParams = new URLSearchParams(window.location.search);
                    const dataInicial = urlParams.get('DataInicial');
                    const dataFinal = urlParams.get('DataFinal');
                    
                    // Atualizar os campos de data com os valores da URL
                    if (dataInicial) document.getElementById('dataInicial').value = dataInicial;
                    if (dataFinal) document.getElementById('dataFinal').value = dataFinal;
                    
                    // Adicionar informação do período
                    var periodoInfo = '';
                    if (dataInicial && dataFinal) {
                        periodoInfo = 'Período: ' + converterParaFormatoBrasileiro(dataInicial) + ' a ' + converterParaFormatoBrasileiro(dataFinal);
                    } else {
                        periodoInfo = 'Selecione um período para visualizar os dados';
                    }
                    
                    $('.periodo-info').html('<div class="alert alert-info mb-0 py-2 text-center">' + periodoInfo + '</div>');

                    $('#vendasTable').show();
                    $('#loading-message').hide();
                }
            });
        }

        // Evento de submit do formulário de filtro
        document.getElementById('filtroDatasForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const dataInicial = document.getElementById('dataInicial').value;
            const dataFinal = document.getElementById('dataFinal').value;

            if (!dataInicial || !dataFinal) {
                alert('Por favor, selecione as datas inicial e final.');
                return;
            }
            
            // Verificação adicional de segurança
            if (new Date(dataFinal) < new Date(dataInicial)) {
                alert('A data final não pode ser menor que a data inicial.');
                return;
            } else {
                // Atualizar a informação do período antes de redirecionar
                $('.periodo-info').html('<div class="alert alert-info mb-0 py-2 text-center">Período: ' + dataInicial + ' a ' + dataFinal + '</div>');
                
                const url = `relatorio.php?DataInicial=${encodeURIComponent(dataInicial)}&DataFinal=${encodeURIComponent(dataFinal)}`;
                console.log(url);
                window.location.href = url;
                console.log(dataFinal);
                console.log(dataFinal);
            }
        });

        // Função para atualizar a informação do período
        function atualizarInformacaoPeriodo() {
            const dataInicial = document.getElementById('dataInicial').value;
            const dataFinal = document.getElementById('dataFinal').value;
            const periodoInfo = dataInicial && dataFinal ? 
                'Período: ' + converterParaFormatoBrasileiro(dataInicial) + ' até ' + converterParaFormatoBrasileiro(dataFinal) : 
                'Selecione um período para visualizar os dados';
            
            $('.periodo-info').html('<div class="alert alert-info mb-0 py-2 text-center">' + periodoInfo + '</div>');
        }

        // Atualizar a informação do período quando a página carregar
        document.addEventListener('DOMContentLoaded', function() {
            atualizarInformacaoPeriodo();
        });

        // Atualizar a informação do período quando as datas forem alteradas
        document.getElementById('dataInicial').addEventListener('change', atualizarInformacaoPeriodo);
        document.getElementById('dataFinal').addEventListener('change', atualizarInformacaoPeriodo);
    </script>
</body>

</html>