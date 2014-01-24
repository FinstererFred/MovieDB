<?
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

require_once 'Zend/Loader.php';
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


function updateUserList($action, $benutzer, $filmnr)
{
	
	$client = getClientLoginHttpClient(USERNAME, PASSWORD);
	$spreadsheetsKey ='trx8-vrg1_0Dhp_HvY8OpAQ';
	$worksheetId = 'od6';
	$spreadsheetService = new Zend_Gdata_Spreadsheets($client);

	$query = new Zend_Gdata_Spreadsheets_CellQuery();
	$query->setSpreadsheetKey($spreadsheetsKey);
	$query->setWorksheetId($worksheetId);
	$query->setMinCol(2);
	$query->setMaxCol(2);
	$query->setMinRow(6);
	$cellFeed = $spreadsheetService->getCellFeed($query);
	$maxfilm =  0;

	foreach($cellFeed as $cellEntry) 
	{
  		$cellFilm = $cellEntry->cell->getText();
  		if($maxfilm == 0) $maxfilm = $cellFilm;
		
		if($cellFilm == $filmnr)
		{
			$movie = $cellEntry->cell->getRow();
			break;
		} 
	}  		



	$user_offset = 6;
	$user = $user_offset + $benutzer;

	if($action == 'copy') $value = 'A';
	if($action == 'watched') $value = 'Y';
	if($action == 'delete') $value = '';

	$updatedCell = $spreadsheetService->updateCell($movie,$user,$value,$spreadsheetsKey,$worksheetId);
}
?>