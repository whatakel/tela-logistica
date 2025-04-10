<?php
include('_config.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://aga.totem.app.br/_custom/api/v1/clientes',
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

// echo $httpcode . '<br>';
if ($err) {
	echo $err . '<br>';
	$erro = true;
} else {
	$clientes = json_decode($response);
	if (
		$httpcode != 200
		&& $httpcode != 201
	) {
		// echo "Erro" . '<br>';
		var_dump($resposta);
		$erro = true;
	} else {
		// echo "Sucesso" . '<br>';
		//echo '<pre>';            print_r($resposta->data);            echo '</pre>';

		foreach ($clientes->data as $cliente) {
			// var_dump($dado);
			// echo '<pre>';
			// print_r($cliente->Fantasia);
			// echo '</pre>';
			// echo '<h1>'.$dado->pe_status.'</h1>';
		}

		$erro = false;
	}
}

// echo '<pre> <br>';
// print_r($clientes);
// echo '</pre>';

curl_close($curl);
