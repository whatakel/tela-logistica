<?php
include('_config.php');


/***** obtem os dados dos produtos *****/

$erro           = false;
$mensagem_erro  = '';

curl_setopt_array($curl, array(
    CURLOPT_URL => $urlapi . '/produtos/',
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

$response   = curl_exec($curl);
$err        = curl_error($curl);
$httpcode   = curl_getinfo($curl, CURLINFO_HTTP_CODE);

if ($err) {
    $erro = true;
    $mensagem_erro = 'Erro obtenção dos produtos: ' . $httpcode . ' - ' . $err;
} else {
    if (
        $httpcode != 200
        && $httpcode != 201
    ) {
        $erro = true;
        $mensagem_erro = 'Erro obtenção dos produtos HTTP: ' . $httpcode;
    }
}

if ($erro) {
    echo $mensagem_erro;
    exit;
}

$resposta = json_decode($response);


$produto = $resposta->data;
// echo '<pre>'; print_r($produto); echo '</pre>';

curl_close($curl);

?>