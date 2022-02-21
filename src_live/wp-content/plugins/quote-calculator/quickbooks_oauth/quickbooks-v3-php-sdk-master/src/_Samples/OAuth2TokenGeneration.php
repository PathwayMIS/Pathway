<?php
ini_set('display_errors', '1');
//Replace the line with require "vendor/autoload.php" if you are using the Samples from outside of _Samples folder
include('../config.php');
session_start();
//unset($_SESSION['code']);
//unset($_SESSION['realmId']);
//unset($_SESSION['accessToken']);




use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;

//http://zos.loc/wordpress_calc/wp-content/plugins/quote-calculator/quickbooks_oauth/quickbooks-v3-php-sdk-master/src/_Samples/OAuth2TokenGeneration.php
//https://developer.intuit.com/v2/OAuth2Playground/RedirectUrl
$dataService = DataService::Configure(array(
  'auth_mode' => 'oauth2',
  'ClientID' => "Q09qtB5VGrRv7hPwSScHAC3QXhDJbzTtXtryYef627XuK5MTQH",
  'ClientSecret' => "V1ebkBCgLtAgXjJwUfl8wz0jQZvcLYOwSbD6RjlO",
  'RedirectURI' => "https://acp.pathwaymis.co.uk/wp-content/plugins/quote-calculator/quickbooks_oauth/quickbooks-v3-php-sdk-master/src/_Samples/OAuth2TokenGeneration.php",
  //'RedirectURI' => "https://bestwebsoft.com/inprogress/ec/custom_plugin/wp-content/plugins/quote-calculator/quickbooks_oauth/quickbooks-v3-php-sdk-master/src/_Samples/OAuth2TokenGeneration.php",
  'scope' => "com.intuit.quickbooks.accounting",
  'baseUrl' => "production"
  //'baseUrl' => "development"
));
/*$serviceContext = $dataService->getServiceContext();
//Add a new Invoice
$platformService = new PlatformService($serviceContext);
$result = $platformService->Disconnect();*/

$OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
if( ! isset( $_GET['code'] ) && ! isset( $_SESSION['code'] )  ){

	$url = $OAuth2LoginHelper->getAuthorizationCodeURL();
	header('Location: ' . $url);
}
if( ! isset( $_SESSION['code'] )  ){
	$_SESSION['code'] = $_GET['code'];
	$_SESSION['realmId'] = $_GET['realmId'];
	$accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken( $_GET['code'], $_GET['realmId']);
	$_SESSION['accessToken'] = $accessToken;
}
//var_dump($_SESSION['accessToken']);
 //It will return something like:https://b200efd8.ngrok.io/OAuth2_c/OAuth_2/OAuth2PHPExample.php?state=RandomState&code=Q0115106996168Bqap6xVrWS65f2iXDpsePOvB99moLCdcUwHq&realmId=193514538214074
//get the Code and realmID, use for the exchangeAuthorizationCodeForToken
$dataService->updateOAuth2Token( $_SESSION['accessToken'] );
//echo '<pre>'; var_dump($accessToken, $dataService);
//var_dump($accessToken->accessTokenKey);
//var_dump($accessToken->refresh_token);

$dataService->throwExceptionOnError(true);

//$accounts = $dataService->FindAll( 'Item', 0, 100 );
//$accounts = $dataService->FindAll( 'TaxCode', 0, 2 );
//$accounts = $dataService->FindById( 'estimate', 39440 );
//$dataService->Delete($accounts);
//echo '<pre>'; var_dump($accounts); 
//$accounts = $dataService->FindAll( 'Account', 0, 100 );
//$accounts = $dataService->FindAll( 'Customer', 0, 10 );
//$accounts = $dataService->FindAll( 'TaxRate', 0, 100 );
//$accounts = $dataService->Query( "select * from Customer where GivenName LIKE '".$quote_calculator_estimate_customer['customers_given_name']."' AND FamilyName LIKE '".$quote_calculator_estimate_customer['customers_family_name']."' AND DisplayName LIKE '".$quote_calculator_estimate_customer['customers_display_name']."'" );
//$accounts = $dataService->Query( "select * from Item where Type = 'Service'" );
//$accounts = $dataService->FindById( 'item', 146 );
//echo '<pre>'; var_dump($accounts); 
//$accounts = $dataService->FindById( 'Customer', 147 );
$name = "St Peter's Parish Hall";
$where .= "DisplayName LIKE '".addslashes($name)."'";
$accounts = $dataService->Query( "select * from Customer where " . $where );
echo '<pre>'; var_dump( $accounts );
/*foreach( $accounts as $item ){
	if( empty( $item->ExpenseAccountRef ) ){
		var_dump( $item );
	}
}*/

//$entities = $dataService->Query("SELECT * FROM TaxRate");

// Echo some formatted output
/*if (count($entities) > 0) {
   echo '<pre>'; var_dump($entities); 

}*/