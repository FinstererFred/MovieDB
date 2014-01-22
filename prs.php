<?
error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT);
require_once 'Zend/Loader.php';
require_once 'db.class.php';
require_once 'credentials.php';
error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT);
/*
$url = 'http://example.com/image.php';
$img = '/my/folder/flower.gif';
file_put_contents($img, file_get_contents($url));
Else use cURL:



$sql = "select * from filme";
$stmt = $db->prepare($sql);
$stmt->execute();

while($temp = $stmt->fetch(PDO::FETCH_ASSOC))
{

  $ch = curl_init($temp['poster']);
  $fp = fopen('poster/'.$temp['id'].'.jpg', 'wb');
  curl_setopt($ch, CURLOPT_FILE, $fp);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_exec($ch);
  curl_close($ch);
  fclose($fp);
  echo 'insert: '.$temp['id']."<br/>";
  flush();
  ob_flush();

}
exit;

*/

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
$spreadsheetService = new Zend_Gdata_Spreadsheets($client);
// Get your spreadsheets feed
$feed = $spreadsheetService->getSpreadsheetFeed();
foreach($feed->entries as $entry) {
 $title = $entry->title->text;
  if ($title == "Filme") $id = $entry->id;
  
}

// Get spreadsheet key
$spreadsheetsKey = basename($id);   
echo 'Your spreadsheet key is: ' . $spreadsheetsKey .'</br>';

$query = new Zend_Gdata_Spreadsheets_DocumentQuery();
$query->setSpreadsheetKey($spreadsheetsKey);
$feed = $spreadsheetService->getWorksheetFeed($query);

foreach($feed->entries as $entry) {
    echo 'Your "'. $entry->title->text .'" worksheet ID is: ';
    $worksheetId = basename($entry->id);
    echo $worksheetId.'</br>';
}
$worksheetId = 'od6';

$query = new Zend_Gdata_Spreadsheets_CellQuery();
$query->setSpreadsheetKey($spreadsheetsKey);
$query->setWorksheetId($worksheetId);
$query->setMinRow(7);
$query->setMaxRow(10);

$colNr = 4;
$query->setMinCol($colNr);
$query->setMaxCol($colNr);
$query->setMinRow($startingRow);
$query->setMaxRow($endingRow);

$columnFeed = $spreadsheetService->getCellFeed($query);
foreach($columnFeed as $cellEntry){
    $result[$cellEntry->cell->getRow()] = $cellEntry->cell->getText();
}

$query = new Zend_Gdata_Spreadsheets_CellQuery();
$query->setSpreadsheetKey($spreadsheetsKey);
$query->setWorksheetId($worksheetId);
$query->setMinRow(7);
$query->setMaxRow(10);

$colNr = 2;
$query->setMinCol($colNr);
$query->setMaxCol($colNr);
$query->setMinRow($startingRow);
$query->setMaxRow($endingRow);

$columnFeed = $spreadsheetService->getCellFeed($query);
foreach($columnFeed as $cellEntry){
    $result2[$cellEntry->cell->getRow()] = $cellEntry->cell->getText();
}


$sql = "SELECT * from filme";
$stmt = $db->prepare($sql);
$stmt->execute();
$bestehende=array();
while($temp = $stmt->fetch(PDO::FETCH_ASSOC))
{
  $bestehende[] = $temp['tt'];
  $lastId = $temp['id'];
}
foreach ($result as $key => $res)
{
  if( !in_array($res, $bestehende) && $key >= 6)
  {
    if($res != '.') 
    { 

    echo 'insert: '.$res."<br/>";
    flush();
    ob_flush();
    $film = parse($res);

    $sql = 'insert into filme (id, tt,name,plot,poster,rating) values (:id, :tt,:name,:plot,:poster,:rating)';
    $stmt=$db->prepare($sql);
    $stmt->bindParam(':id', $result2[$key], PDO::PARAM_INT);
    $stmt->bindParam(':tt', $res, PDO::PARAM_STR);
    $stmt->bindParam(':name', $film['name'], PDO::PARAM_STR);
    $stmt->bindParam(':plot', $film['plot'], PDO::PARAM_STR);
    $stmt->bindParam(':poster', $film['poster'], PDO::PARAM_STR);
    $stmt->bindParam(':rating', $film['rating'], PDO::PARAM_STR);
    $stmt->execute();
    }
    $cnt++;
  }
} 

function parse($id)
{
  $contents = file_get_contents('http://m.imdb.com/title/'.$id.'/');

  // poster
  $match = '';
  preg_match('/src="http:\/\/ia\.media.*_S/', $contents, $match);
  $poster = explode('src="',$match[0]);
  $poster = $poster[1].'X675.jpg';

  // plot
  $match = '';
  preg_match('/op="description.*?</is', $contents, $match);
  $plot = explode('>', $match[0]);
  $plot = explode('<', $plot[1]);

  // rating
  $match = '';
  preg_match('/inline-block text-left vertically-middle">[0-9]\.?[0-9]?[0-9]?/', $contents, $match);
  $rating = explode('>', $match[0]);
  
  // name
  $match = '';
  preg_match('/:title\' content=".*"/s', $contents, $match);
  $name = explode('content="', $match[0]);
  $name = explode('(', $name[1]);

  return array('name' => trim(strip_tags($name[0])), 'poster' => $poster, 'plot' => trim($plot[0]), 'rating' => $rating[1]);
}


exit; 
// Get cell feed
$query = new Zend_Gdata_Spreadsheets_CellQuery();
$query->setSpreadsheetKey($spreadsheetsKey);
$query->setWorksheetId($worksheetId);
$cellFeed = $spreadsheetService->getCellFeed($query);

// Echo all cells
foreach($cellFeed as $cellEntry) {
  $row = $cellEntry->cell->getRow();
  $col = $cellEntry->cell->getColumn();
  $val = $cellEntry->cell->getText();
  echo "$row, $col = $val</br>";
}

exit;
$updatedCell = $spreadsheetService->updateCell(3,
                                               2,
                                               'Hello from PHP!',
                                               $spreadsheetsKey,
                                               $worksheetId);

/*

*/
?>