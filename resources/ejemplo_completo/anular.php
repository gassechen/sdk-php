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

$anul = new \Decidir\Authorize\Execute\Anulacion(array("security" => SECURITY, "merchant" => MERCHANT, "nro_operacion" => $operationid));

try {
	$rta = $connector->Authorize()->execute($anul);
} catch(Exception $e) {
	var_dump($e);die();
}

$db->updateRecords(array("status" => "ANULADA"),array("id" => $operationid));
header("Location: index.php");
