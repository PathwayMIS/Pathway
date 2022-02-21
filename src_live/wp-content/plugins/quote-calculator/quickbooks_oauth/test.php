 <?php

 $params = array(
	'oauth_consumer_key'=>'qyprdCFKOSf96xt4MIna1Kah2fS4y6',
	'oauth_token'=>'10dc89d8b7f77b4c2bbb688bc4021b550bcf',
	'oauth_signature_method'=>'HMAC-SHA1',
	'oauth_timestamp'=> time(),
	'oauth_nonce'=> time(),
	'oauth_version'=> '1.0'
);

$post_string = '';
foreach($params as $key => $value)
{
    $post_string .= $key.'='.($value).'&';
}
$post_string = rtrim($post_string, '&');

$base_string = urlencode($post_string);

$signature = base64_encode(hash_hmac('sha1', $base_string, str_replace('+', ' ', str_replace('%7E', '~', rawurlencode('5PTC8fTEqqzGrJWztrrYev2urA2zqh84xTpKzMcu'))), true));

$params['oauth_signature'] = $signature;

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://oauth.intuit.com/oauth/v1/get_request_token",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_HTTPHEADER => array(
    "authorization: OAuth oauth_consumer_key=\"qyprdCFKOSf96xt4MIna1Kah2fS4y6\",oauth_token=\"10dc89d8b7f77b4c2bbb688bc4021b550bcf\",oauth_signature_method=\"HMAC-SHA1\",oauth_timestamp=\"".time()."\",oauth_nonce=\"".time()."\",oauth_version=\"1.0\",oauth_signature=\"".$signature."\"",
    "cache-control: no-cache"
  )		
));

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
     echo "cURL Error #:" . $err;
  } else {
     var_dump( $response );
  }