<?
include('db.class.php');
error_reporting(E_ALL ^ E_NOTICE);
$action = $_GET['action'];
$type = $_GET['type'];
$filmNr = (int)$_GET['filmNr'];
$benutzer = (int)$_GET['benutzer'];
$datum = date("Y-m-d H:i:s",time());

if($action == 'add' )
{
	if($type == 'mysql')
	{
		$sql = "INSERT into film_leihliste (usernr, datum, filmnr) VALUES (:usernr, :datum, :filmnr)";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('usernr',$benutzer,PDO::PARAM_INT);
		$stmt->bindParam('datum', $datum,PDO::PARAM_STR);
		$stmt->bindParam('filmnr',$filmNr,PDO::PARAM_INT);
		$stmt->execute();
		echo json_encode('Film zur Leihliste hinzugefuegt');
	}

	if($type == 'gsheet')
	{
		// todo: hier google sheet abgleichen
	}
}

if($action == 'remove')
{
	if($type == 'mysql')
	{
		$sql = "DELETE from film_leihliste WHERE usernr = :usernr and filmnr = :filmnr LIMIT 1";
		$stmt = $db->prepare($sql);
		$stmt->bindParam('usernr',$benutzer,PDO::PARAM_INT);
		$stmt->bindParam('filmnr',$filmNr,PDO::PARAM_INT);
		$stmt->execute();
		echo json_encode('Film von der Leihliste geloescht');
	}

	if($type == 'gsheet')
	{
		// todo: hier google sheet abgleichen
	}
}

if($action == 'leihliste')
{
	$sql = "SELECT * from film_leihliste fl left join filme f on fl.filmnr = f.id where fl.usernr = :usernr";
	$stmt = $db->prepare($sql);
	$stmt->bindParam('usernr',$benutzer,PDO::PARAM_INT);
	$stmt->execute();
	$out = array();
	while($temp = $stmt->fetchAll(PDO::FETCH_ASSOC))
	{
		$out[] = $temp;
	}

	echo json_encode($out);
}

if($action == 'angeschaut')
{
	$sql = "UPDATE film_leihliste SET angeschaut = 1 where usernr = :usernr and filmnr = :filmnr";
	$stmt = $db->prepare($sql);
	$stmt->bindParam('usernr', $benutzer, PDO::PARAM_INT);
	$stmt->bindParam('filmnr',$filmNr,PDO::PARAM_INT);
	$stmt->execute();
	echo json_encode('ok');
}

?>