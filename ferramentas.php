<?php 
include('API/_config.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="plugins/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="plugins/fontawesome-free-6.6.0-web/css/all.min.css">
    <title>Documentos</title>
    <style>
        .document-box {
            transition: transform 0.2s;
            cursor: pointer;
        }
        .document-box:hover {
            transform: translateY(-5px);
        }
        .document-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .document-title {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid p-0">
        <div class="p-3">
            <!-- Título da página em uma box azul -->
            <div class="card-header bg-white border-0 p-0">
                <div class="d-flex justify-content-between align-items-center p-3 my-3 border rounded">
                    <h3 class="mb-0 text-primary">Ferramentas</h3>
                </div>
            </div>

            <!-- Container principal com card -->
            <div class="card">
                <div class="card-body">
                    <!-- Subtítulo -->
                    <div class="text-center mb-4">
                        <h4 class="text-muted">Modelos de Impressão</h4>
                    </div>

                    <!-- Grid de documentos -->
                    <div class="row g-4">
                        <!-- Documento 1 -->
                        <div class="col-md-4 col-lg-3">
                            <div class="card document-box h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-file-excel document-icon text-success"></i>
                                    <h5 class="document-title">Inventário de estoque</h5>
                                    <p class="text-muted mb-0">Tabela de conferência</p>
                                </div>
                            </div>
                        </div>

                        <!-- Documento 2 -->
                        <div class="col-md-4 col-lg-3">
                            <div class="card document-box h-100">
                                <div class="card-body text-center">
                                    <i class="fa-solid fa-location-dot document-icon text-warning"></i>
                                    <h5 class="document-title">Etiqueta identificação</h5>
                                    <p class="text-muted mb-0">Para separação de cargas</p>
                                </div>
                            </div>
                        </div>

                        <!-- Documento 3 -->
                        <div class="col-md-4 col-lg-3">
                            <div class="card document-box h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-file-excel document-icon text-success"></i>
                                    <h5 class="document-title">Controle de Estoque</h5>
                                    <p class="text-muted mb-0">Planilha de controle de estoque</p>
                                </div>
                            </div>
                        </div>

                        <!-- Documento 4 -->
                        <div class="col-md-4 col-lg-3">
                            <div class="card document-box h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-file-pdf document-icon text-danger"></i>
                                    <h5 class="document-title">Checklist de conferência</h5>
                                    <p class="text-muted mb-0">Entrada ou saída de materiais</p>
                                </div>
                            </div>
                        </div>

                        <!-- Documento 4 -->
                        <div class="col-md-4 col-lg-3">
                            <div class="card document-box h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-qrcode document-icon text-primary"></i>
                                    <h5 class="document-title">QR-CODE</h5>
                                    <p class="text-muted mb-0">Para detalhamento do pedido</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>