<?
error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT);
require_once 'Zend/Loader.php';
require_once 'db.class.php';
require_once 'credentials.php';


Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_AuthSub');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_Spreadsheets');


function getClientLoginHttpClient($user, $pass)
{
  $service = Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME;
  $client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, $service);
  return $client;
}

$client = getClientLoginHttpClient(USERNAME, PASSWORD);
$spreadsheetsKey ='trx8-vrg1_0Dhp_HvY8OpAQ';
$worksheetId = 'od6';
$spreadsheetService = new Zend_Gdata_Spreadsheets($client);
/*
$query = new Zend_Gdata_Spreadsheets_DocumentQuery();
$query->setSpreadsheetKey($spreadsheetsKey);
$feed = $spreadsheetService->getWorksheetFeed($query);



$query = new Zend_Gdata_Spreadsheets_CellQuery();
$query->setSpreadsheetKey($spreadsheetsKey);
$query->setWorksheetId($worksheetId);
*/
$updatedCell = $spreadsheetService->updateCell(6,
                                               7,
                                               'X',
                                               $spreadsheetsKey,
                                               $worksheetId);

?>