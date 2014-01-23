<?

require_once 'Zend/Loader.php';
require_once 'db.class.php';
require_once 'credentials.php';
error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT);


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

$query = new Zend_Gdata_Spreadsheets_CellQuery();
$query->setSpreadsheetKey($spreadsheetsKey);
$query->setWorksheetId($worksheetId);

$user_offset = 6;
$benutzer = $_GET['benutzer'];

$query->setMinCol($benutzer+$user_offset);
$query->setMaxCol($benutzer+$user_offset);
$query->setMinRow(6);
$columnFeed = $spreadsheetService->getCellFeed($query);
foreach($columnFeed as $cellEntry){
    $benutzer_filme[$cellEntry->cell->getRow()] = $cellEntry->cell->getText();
   
}

$query->setMinCol(2);
$query->setMaxCol(2);
$query->setMinRow(6);
$columnFeed = $spreadsheetService->getCellFeed($query);
foreach($columnFeed as $cellEntry){
    $filme[$cellEntry->cell->getRow()] = $cellEntry->cell->getText();
}

$datum = date("Y-m-d H:i:s",time());

foreach ($benutzer_filme as $key => $value) 
{
  echo $value.' :: '.$filme[$key]."<br>";
  ob_flush();
  $sql = "insert into film_leihliste (usernr, datum, filmnr, kopiert, angeschaut) VALUES (:usernr, :datum, :filmnr, :kopiert, :angeschaut);";
  $stmt = $db->prepare($sql);

  $angeschaut = 0;
  $kopiert = 0;


  if($value == 'X') $kopiert = 1;
  if($value == 'Y') $angeschaut = 1;

  $stmt->bindParam(':usernr', $benutzer, PDO::PARAM_INT);
  $stmt->bindParam(':datum', $datum, PDO::PARAM_STR);
  $stmt->bindParam(':filmnr', $filme[$key], PDO::PARAM_INT);
  $stmt->bindParam(':kopiert', $kopiert, PDO::PARAM_INT);
  $stmt->bindParam(':angeschaut', $angeschaut, PDO::PARAM_INT);

  $stmt->execute();
}

exit; 
?>