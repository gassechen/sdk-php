<?php
include_once dirname(__FILE__)."/../../vendor/autoload.php";

$http_header = array('Authorization'=>'PRISMA ONO3XNIF6PC2A1LIZ62P4JC1',
 'user_agent' => 'PHPSoapClient');

//datos constantes
define('CURRENCYCODE', "032");
define('MERCHANT', "00181115");
define('ENCODINGMETHOD', 'XML');
define('SECURITY', 'ONO3XNIF6PC2A1LIZ62P4JC1');

//creo instancia de la SDK
$connector = new Decidir\Connector($http_header, Decidir\Connector::DECIDIR_ENDPOINT_TEST);


//SendAuthorizeRequest
$medio_pago = new Decidir\Data\Mediopago\TarjetaCredito(array("medio_pago" => 1, "cuotas" => 6));
$split = new Decidir\Data\SplitTransacciones\MontoFijo( array(
                                                       'impdist'=>'40.00#10.00',//Importe de cada una de las substransacciones. Los importes deben postearse separados por "#".
                                                       'sitedist'=>'00181115#00281115',//Número de comercio de cada uno de los subcomercios asociados al comercio padre
                                                       'cuotasdist'=>'05#01',//cantidad de cuotas para cada subcomercio. Decimal de 2 dígitos.
                                                       'idmodalidad'=>'S',// indica si la transacción es distribuida. (S= transacción distribuida; N y null = no distribida)
                                                   ));

$sar_data = new Decidir\Authorize\SendAuthorizeRequest\Data(array(
	"security" => SECURITY,
	"encoding_method" => ENCODINGMETHOD,
	"merchant" => MERCHANT,
	"nro_operacion" => 'helpdesk-oc-pc-0518-0',
	"monto" => 50.00,
	"email_cliente" => "ejemplo@misitio.com"
));
$sar_data->setMedioPago($medio_pago);
$sar_data->setSplitData($split);

$rta = $connector->Authorize()->sendAuthorizeRequest($sar_data);

echo "<h3>Respuesta SendAuthorizeRequest</h3>";
var_dump($rta);
die;

//GetAuthorizeAnswer
$gaa_data = new \Decidir\Authorize\GetAuthorizeAnswer\Data(array(
	"security" => SECURITY,
	"merchant" => MERCHANT,
	"requestKey" => 'cdf96aaf-dd1c-195b-eeee-130a3df96110',
	"answerKey" => '77215fe6-f9d5-f1c2-372b-c0065e0c4429'
));

$rta = $connector->Authorize()->getAuthorizeAnswer($gaa_data);

echo "<h3>Respuesta GetAuthorizeAnswer</h3>";
var_dump($rta);

//GetOperationById
$gobi_data = new \Decidir\Operation\GetByOperationId\Data(array("idsite" => MERCHANT, "idtransactionsit" => 123456));

$rta = $connector->Operation()->getByOperationId($gobi_data);

echo "<h3>Respuesta GetOperationById</h3>";
var_dump($rta);

//Execute - Anulacion
$anul = new \Decidir\Authorize\Execute\Anulacion(array("security" => SECURITY, "merchant" => MERCHANT, "nro_operacion" => 123456));

$rta = $connector->Authorize()->execute($anul);

echo "<h3>Respuesta Execute - Anulacion</h3>";
var_dump($rta);

//Execute - Devolucion Totoal
$devol = new \Decidir\Authorize\Execute\Devolucion\Total(array("security" => SECURITY, "merchant" => MERCHANT, "nro_operacion" => 123456));

$rta = $connector->Authorize()->execute($devol);

echo "<h3>Respuesta Execute - Devolucion Total</h3>";
var_dump($rta);

//Execute - Devolucion Parcial
$devol = new \Decidir\Authorize\Execute\Devolucion\Parcial(array("security" => SECURITY, "merchant" => MERCHANT, "nro_operacion" => 123456, "monto" => 10.00));

$rta = $connector->Authorize()->execute($devol);

echo "<h3>Respuesta Execute - Devolucion Parcial</h3>";
var_dump($rta);
