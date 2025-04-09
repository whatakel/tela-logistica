<?php
include('_config.php');
date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para Brasília
$dataHoje = date('Y-m-d 00:00:00'); // Define a data de hoje com horário fixo às 00:00

// Inicializar variáveis com valores vazios
$pedidos = array();
$pedidos_abertos = array();
$qtd_pedidos = 0;
$erro = false;

// Formatar as datas recebidas para o formato correto
$dataInicial = isset($_GET['DataInicial']) ? $_GET['DataInicial'] . ' 00:00:00' : null;
$dataFinal = isset($_GET['DataFinal']) ? $_GET['DataFinal'] . ' 23:59:59' : null;

// Só faz a chamada à API se ambas as datas foram fornecidas
if ($dataInicial && $dataFinal) {
    curl_setopt_array($curl, array(
        // CURLOPT_URL => $urlapi . "/pedidos/DataInicial>=" . urlencode($dataInicial) . "/DataFinal<=" . urlencode($dataFinal),
        CURLOPT_URL => $urlapi . '/pedidos/DataInicial>=' . date('Y-m-d', strtotime('-1 day')),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'x-api-secret: ' . $xapisecret,
            'x-api-public: ' . $xapipublic,
            'Authorization: ' . $token_tipo . ' ' . $token,
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);
    $err      = curl_error($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    // Debug information
    echo "<!-- Debug Info: \n";
    echo "URL: " . curl_getinfo($curl, CURLINFO_EFFECTIVE_URL) . "\n";
    echo "HTTP Code: " . $httpcode . "\n";
    echo "Response Length: " . strlen($response) . "\n";
    echo "Raw Response: " . htmlspecialchars($response) . "\n";
    echo "-->";

    // Verificar se houve erro no curl
    if ($err) {
        $erro = true;
    } else {
        // Tentar decodificar a resposta JSON
        $resposta = json_decode($response);

        // Verificar se houve erro na decodificação JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Se não for JSON válido, tentar tratar como array vazio
            $resposta = new stdClass();
            $resposta->data = array();
            $resposta->status = 200;
            $resposta->status_message = "Nenhum dado encontrado";
        }

        // Verificar se a resposta contém erro
        if ($httpcode !== 200) {
            $error_message = isset($resposta->status_message) ? $resposta->status_message : 'Erro desconhecido';
            $erro = true;
        } else {
            // Verificar se há dados na resposta
            if (isset($resposta->data) && is_array($resposta->data)) {
                foreach ($resposta->data as $pedido) {
                    if (isset($pedido->itens) && (is_array($pedido->itens) || is_object($pedido->itens))) {
                        foreach ($pedido->itens as $item) {
                            $pedidos_abertos[] = $item;
                        }
                    }
                }
            }
        }
    }
}

$pedidos = $pedidos_abertos;
$qtd_pedidos = count($pedidos);

// Primeiro, vamos mostrar o array original
//echo '<div style="font-family: Arial, sans-serif; padding: 20px;">';
//echo '<h3>Array Original:</h3>';
//echo '<pre>';
//print_r($pedidos);
//echo '</pre>';

// Agora vamos agrupar e somar as quantidades
$itens_agrupados = array();

foreach ($pedidos as $item) {
    $descricao = $item->Descricao;
    $qtde = floatval($item->Qtde);
    
    if (!isset($itens_agrupados[$descricao])) {
        $itens_agrupados[$descricao] = 0;
    }
    $itens_agrupados[$descricao] += $qtde;
}

// Mostrar o resultado agrupado
//echo '<h3>Itens Agrupados por Descrição:</h3>';
//echo '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">';
//echo '<tr style="background-color: #f2f2f2;">';
//echo '<th>Descrição</th>';
//echo '<th>Quantidade Total</th>';
//echo '</tr>';

//foreach ($itens_agrupados as $descricao => $quantidade) {
//    echo '<tr>';
//    echo '<td>' . htmlspecialchars($descricao, ENT_QUOTES, 'UTF-8') . '</td>';
//    echo '<td>' . number_format($quantidade, 4, ',', '.') . '</td>';
//    echo '</tr>';
//}

//echo '</table>';
//echo '</div>';

// echo '<pre>';
// var_dump($response);
// echo '</pre>';
