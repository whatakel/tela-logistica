<?php 
include('API/_config.php');
include('API/_lista-clientes.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-header bg-white border-0 p-0">
                    <div class="d-flex justify-content-between align-items-center p-3 my-3 border rounded">
                        <h3 class="mb-0 text-primary">Lista de Clientes</h3>
                    </div>
                </div>
                <div>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="clientesTable" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Nome</th>
                                            <th scope="col">Fantasia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if (isset($clientes) && isset($clientes->data) && !empty($clientes->data)) {
                                            $contador = 1;
                                            foreach ($clientes->data as $cliente) {
                                                echo '<tr>';
                                                echo '<th scope="row">' . $contador . '</th>';
                                                echo '<td>' . (isset($cliente->Nome) ? htmlspecialchars($cliente->Nome, ENT_QUOTES, 'UTF-8') : 'N/A') . '</td>';
                                                echo '<td>' . (isset($cliente->Fantasia) ? htmlspecialchars($cliente->Fantasia, ENT_QUOTES, 'UTF-8') : 'N/A') . '</td>';
                                                echo '</tr>';
                                                $contador++;
                                            }
                                        } else {
                                            echo '<tr><td colspan="3" class="text-center">Nenhum cliente encontrado</td></tr>';
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

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#clientesTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
                },
                "paging": false,
                "dom": '<"top"f>rt<"bottom"i>',
                "ordering": true,
                "searching": true,
                "info": true,
                "lengthChange": false,
                "responsive": true
            });
        });
    </script>
</body>
</html>