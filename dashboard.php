<?php
include('API/_config.php');
include('API/_lista-pedidos.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$statusNames = [];
$pedidosData = [];
$clientesData = [];
foreach ($pedidos as $pedido) {
    $pedidosData[] = [
        'codigo' => $pedido->codsite_lj_pedidos,
        'logo' => $pedido->lo_logo,
        'data' => date('d/m/y', strtotime($pedido->pe_dthr)),
        'status' => $pedido->pe_status_title,
        'valor' => number_format($pedido->pe_valor_total, 2, ',', '.'),
        'cliente' => $pedido->cl_nome,
        'empresa' => $pedido->lo_descricao
    ];
}

?>


<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="plugins/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="plugins/fontawesome-free-6.6.0-web/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <title>Dashboard</title>
</head>

<body>
    <?php

    ?>
    <div class="main-content flex-grow-1 ms-auto">
        <div class="container-fluid p-0">
            <div class="daily-summary-container d-flex row rounded">
                <div class="d-flex gap-3  align-items-center gap-1" id="greeting"></div>
                <div class="ctn-highlight-cards d-flex flex-wrap gap-3 mt-3">
                    <div class="ctn-card col">
                        <div class="card card-highlight">
                            <div class="card-body">
                                <h5 class="card-title">Pedidos em aberto</h5>
                                <p class="card-text abertos-qtd display-4">0</p>
                            </div>
                        </div>
                    </div>
                    <div class="ctn-card col">
                        <div class="card card-highlight">
                            <div class="card-body">
                                <h5 class="card-title">Pedidos separados</h5>
                                <p class="card-text separados-qtd display-4">0</p>
                            </div>
                        </div>
                    </div>
                    <div class="ctn-card col">
                        <div class="card card-highlight">
                            <div class="card-body">
                                <h5 class="card-title">Pedidos enviados</h5>
                                <p class="card-text enviados-qtd display-4">0</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-100 d-flex align-items-center justify-content-between">
        <div class="ctn-pedidos d-flex align-items-center row" id="lista-pedidos" style="width:100vw;">
            <h3 class="titulo-lista text-primary">Lista de Pedidos</h3>
            <?php
            // Função de comparação para ordenar os pedidos pela data no formato brasileiro

            if (!$pedidosData) {
                echo '
                    <div class="col-12 mb-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <span>Nenhum pedido recebido no dia de hoje</span>
                        </div>
                    </div>
                </div>
                ';
                return;
            }

            $contador = 0;
            foreach ($pedidosData as $pedido) {
                $contador++;
                echo '
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card box-pedido item-order box-pedido h-100 shadow-sm" data-codigo="' . $pedido['codigo'] . '">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="' . $pedido['logo'] . '" width="50px" alt="" class="logo-pedido rounded" data-empresa="' . $pedido['empresa'] . '">
                                    <p class="data-pedido m-0 text-muted">' . $pedido['data'] . '</p>
                                </div>
                                <div class="pedido-status badge bg-secondary text-white" data-status="' . $pedido['status'] . '">
                                    <span>' . $pedido['status'] . '</span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center border-top border-bottom py-2">
                                <p class="loja-pedido-valor fw-bold m-0">R$ ' . $pedido['valor'] . '</p>
                                <p class="numero-pedido m-0 text-secondary">#' . $pedido['codigo'] . '</p>
                            </div>
                            <p class="cliente-pedido mt-2 mb-0">' . $pedido['cliente'] . '</p>
                        </div>
                    </div>
                </div>
            ';
            }
            ?>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="pedidoModal" tabindex="-1" aria-labelledby="pedidoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-pedido">
                <div class="modal-header">
                    <h5 class="modal-title" id="pedidoModalLabel">Pedido: <?php echo $pedido['codigo'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="item-pedido" style="background-color: #f8f9fa;">
                        <div class="item-body">
                            <div class="card item-total d-flex flex-row justify-content-center align-items-center" style="font-size: 1.2em;">
                            </div>
                        </div>
                    </div>
                    <iframe class="iframe-pedido" src="" title="Main Content" width="100%" height="100%"></iframe>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="./node_modules/jquery/dist/jquery.min.js"></script>
        <script src="plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            //contador pedidos
            $(document).ready(function() {
                $(function() {
                    const pedidosQtd = $('.box-pedido');
                    const contadorPedidos = [
                        'abertos-qtd',
                        'separados-qtd',
                        'enviados-qtd'
                    ]

                    let abertosQtd = 0;
                    let separadosQtd = 0;
                    let enviadosQtd = 0;

                    $(pedidosQtd).each( function(){
                        const status = $(this).find('.pedido-status').attr('data-status').toLocaleLowerCase()

                        if(status == 'novo' || status == 'aceito' || status == 'confirmado'){
                            abertosQtd++
                            $('.abertos-qtd').text(abertosQtd)
                        } if(status == 'pesagem'){
                            separadosQtd++
                            $('.separados-qtd').text(separadosQtd)
                        } if(status == 'faturando'){
                            enviadosQtd++
                            $('.enviados-qtd').text(enviadosQtd)
                        }
                    })

                    $(contadorPedidos).each(function(){
                    })
                })

                function atualizarSaudacao() {
                    // Obtém a data atual
                    const agora = new Date();
                    const hora = agora.getHours();

                    // Determina o período do dia com prefixo correto
                    let saudacao;
                    if (hora >= 5 && hora < 12) {
                        icone = "<div class='greeting-icon'>☀️</div>";
                        saudacao = "<h5>Bom dia!</h5>";
                    } else if (hora >= 12 && hora < 18) {
                        icone = "<div class='greeting-icon'>☀️</div>";
                        saudacao = "<h5>Boa tarde!</h5>";
                    } else {
                        icone = "<div class='greeting-icon'>🌙</div>";
                        saudacao = "<h5>Boa noite!</h5>";
                    }

                    // Formata a data no padrão dd-mm-yyyy
                    const dia = String(agora.getDate()).padStart(2, '0');
                    const mes = String(agora.getMonth() + 1).padStart(2, '0');
                    const ano = agora.getFullYear();
                    const dataFormatada = `${dia}/${mes}/${ano}`;

                    // Atualiza o texto no container com a data destacada em HTML
                    $("#greeting").html(`
                        <div class="greeting-icon ctn-icon rounded p-2">${icone}</div>
                        <div class="ctn-saudacao d-flex flex-column lh-1">${saudacao}
                            Informações sobre o dia de hoje: 
                            <p class="date-highlight m-0">${dataFormatada}</p>
                        </div>`);
                }

                // Chama a função imediatamente
                atualizarSaudacao();

                // Evento de clique no pedido para abrir o modal
                $('.box-pedido').click(function() {

                    const pedido = $(this);
                    const modal = new bootstrap.Modal(document.getElementById('pedidoModal'));

                    // Preenche os dados do modal
                    $('#modalLogo').attr('src', pedido.find('.logo-pedido').attr('src'));
                    $('#modalData').text(pedido.find('.data-pedido').text());
                    $('#modalStatus').text(pedido.find('.pedido-status span').text());
                    $('#modalValor').text('R$ ' + pedido.find('.loja-pedido-valor').text());
                    $('#modalCodigo').text('#' + pedido.find('.numero-pedido').text());
                    $('#modalCliente').text(pedido.find('.cliente-pedido').text());

                    // Abre o modal
                    modal.show();
                });
            });


            // Evento de clique no pedido para URL codigo

            $(function() {
                $('.box-pedido').click(function() {
                    let codigo = $(this).attr("data-codigo");
                    $('.iframe-pedido').attr("src", "itens-pedido.php?codigo=" + codigo)
                    console.log($('.iframe-pedido').attr('src'));
                });
            });
        </script>
</body>

</html>