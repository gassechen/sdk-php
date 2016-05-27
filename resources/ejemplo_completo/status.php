<?php
include_once dirname(__FILE__)."/FlatDb.php";
include_once dirname(__FILE__)."/../../vendor/autoload.php";

$operationid = $_GET['ord'];

$db = new FlatDb();
$db->openTable('ordenes');

$orden = $db->getRecords(array("id","merchantId","security","status","data","mediodepago","sar","form","gaa","requestkey","answerkey"),array("id" => $operationid));

$data = json_decode($orden[0]['data'],true);

$merchantId = $orden[0]['merchantId'];
$security = $orden[0]['security'];
$authorize = "PRISMA ".$security;

$http_header = array('Authorization'=>$authorize,
 'user_agent' => 'PHPSoapClient');

//datos constantes
define('CURRENCYCODE', 032);
define('MERCHANT', $merchantId);
define('ENCODINGMETHOD', 'XML');
define('SECURITY', $security);


$connector = new Decidir\Connector($http_header, Decidir\Connector::DECIDIR_ENDPOINT_TEST);

$gaa_data = new \Decidir\Operation\GetByOperationId\Data(array("idsite" => MERCHANT, "idtransactionsit" => $operationid));

try{
	$rta = $connector->Operation()->getByOperationId($gaa_data);
} catch(\Exception $e) {
	var_dump($e);die();
}
echo "<pre>";
print_r($rta->toArray());
echo "</pre>";

?>
<a href="index.php">Volver</a>
