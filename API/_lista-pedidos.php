<?php
    include('_config.php');
    date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário para Brasília
    $dataHoje = date('Y-m-d 00:00:00'); // Define a data de hoje com horário fixo às 00:00
    
    
    curl_setopt_array($curl, array(
        CURLOPT_URL => $urlapi . '/pedidos/DataInicial=' . urlencode($dataHoje),
        // CURLOPT_URL => $urlapi . '/pedidos/DataInicial>=' . date('Y-m-d', strtotime('-2 weeks')),
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

    if ($err) {
        echo $err . '<br>';
        $erro = true;
    } else {
        $resposta = json_decode($response);
        $pedidos_abertos = [];
        if (isset($resposta->data) && (is_array($resposta->data) || is_object($resposta->data))) {
            foreach ($resposta->data as $dado) {
                $pedidos_abertos[] = $dado;
            }
        } else {
            // echo "Nenhum pedido encontrado";
        }
    
        $erro = false;
    }

    $pedidos = [];
    $pedidos = $pedidos_abertos;
    $qtd_pedidos = count($pedidos);

    // echo '<pre>'; print_r($pedidos); echo '</pre>';