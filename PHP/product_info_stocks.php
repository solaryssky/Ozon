<?php
ini_set('max_execution_time', '600');
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$file = 'ozon_description.csv';

// POST -запрос к серверу
$curl_cookie = '';
$curl_error  = false;
$headers = array(
   'Host: api-seller.ozon.ru',
   'Client-Id:  000',
   'Api-Key:  ABCD123',
   'Content-Type: application/json'
);
$url = 'https://api-seller.ozon.ru/v3/product/info/stocks';

$arguments = array(
      'filter' => 
   array(
	  'visibility' => 'VISIBLE'	  
	  ),	
	  'last_id' => "",
	  'limit' => 1000
	  );


function web_post( $url, $arguments = [], $headers =  '', $sleep = 0, $timeout = 600 ) {

    global $curl_error,  $curl_cookie; 

    if( $sleep > 0 ){
       sleep( $sleep );
    }

    $ch = curl_init();

    $copt = [ 
       CURLOPT_URL => $url, 
       CURLOPT_COOKIE => $curl_cookie,
       CURLOPT_POST  => 1, 
       CURLOPT_POSTFIELDS  => $arguments,
       CURLOPT_COOKIESESSION  => 1, 
       CURLOPT_SSL_VERIFYHOST => 0, 
       CURLOPT_SSL_VERIFYPEER => 0, 
       CURLOPT_VERBOSE  => 0,
       CURLOPT_FOLLOWLOCATION => 1, 
       CURLOPT_UNRESTRICTED_AUTH => 1,
       CURLOPT_FAILONERROR => 1, 
       CURLOPT_AUTOREFERER  => 1, 
       CURLOPT_TIMEOUT  => $timeout, 
       CURLOPT_CONNECTTIMEOUT => $timeout, 
       CURLOPT_RETURNTRANSFER => 1,
       CURLOPT_HTTPHEADER  => $headers
   ];

   curl_setopt_array($ch,$copt); 

   if(false === ( $data = curl_exec( $ch ))){
        $curl_error = curl_error($ch);
   }
   curl_close($ch);
   return $data; 
}

$json = web_post( $url, json_encode($arguments), $headers);
$result = json_decode($json, true);

//получаем информацию о товаре

$url = 'https://api-seller.ozon.ru/v1/product/info/description';

foreach($result['result']['items'] as $value){
	$product_id = $value['product_id'];
	$offer_id = $value['offer_id'];
	
	$arguments = array(
            'offer_id' => $offer_id,
            'product_id' => $product_id
			);
	
$json = web_post( $url, json_encode($arguments), $headers);
$result_2[] = json_decode($json, true);	
}
