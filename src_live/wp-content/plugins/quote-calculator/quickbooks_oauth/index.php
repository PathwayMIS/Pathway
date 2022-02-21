<?php
  require_once("config.php");
?>

<html>
<head>

  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <title>My Connect Page</title>
  <script type="text/javascript" src="https://appcenter.intuit.com/Content/IA/intuit.ipp.anywhere.js"></script>
  <script>
    // Runnable uses dynamic URLs so we need to detect our current //
    // URL to set the grantUrl value   ########################### //
    /*######*/ var parser = document.createElement('a');/*#########*/
    /*######*/parser.href = document.url;/*########################*/
    // end runnable specific code snipit ##########################//
    intuit.ipp.anywhere.setup({
        menuProxy: '',
        grantUrl: 'http://'+parser.hostname+'/oauth.php?start=t' 
        // outside runnable you can point directly to the oauth.php page
    });
  </script>
</head>

</head>
<body>

<?php
require_once('quickbooks-online-v3-sdk-master/src/config.php');  // Default V3 PHP SDK (v2.0.1) from IPP
require_once(PATH_SDK_ROOT . 'Core/ServiceContext.php');
require_once(PATH_SDK_ROOT . 'DataService/DataService.php');
require_once(PATH_SDK_ROOT . 'PlatformService/PlatformService.php');
require_once(PATH_SDK_ROOT . 'Utility/Configuration/ConfigurationManager.php');
error_reporting(E_ERROR | E_PARSE);

// print connect to QuickBooks button to the page
echo "<ipp:connectToIntuit></ipp:connectToIntuit><br />";

// After the oauth process the oauth token and secret 
// are storred in session variables. 
if(!isset($_SESSION['token'])){
  echo "<h3>You are not currently authenticated</h3>";
} else {
  $token = unserialize($_SESSION['token']);
  $requestValidator = new OAuthRequestValidator($token['oauth_token'], $token['oauth_token_secret'], OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET);
  $realmId = $_SESSION['realmId'];
  
  $serviceType = $_SESSION['dataSource'];
  $serviceContext = new ServiceContext($realmId, $serviceType, $requestValidator);
  
  $dataService = new DataService($serviceContext);
  
  $startPosition = 1;
  $maxResults = 10;
  $allCustomers = $dataService->FindAll('Customer', $startPosition, $maxResults);
  
  echo "<pre><h2>Customers List</h2>";
  var_dump($allCustomers);
  echo "</pre>";
}
?>

</body>
</html>