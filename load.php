<?php 

include('db.class.php');
$usernr = (int)$_GET['benutzer'];

$sql = "SELECT f.*,fl.usernr,fl.filmnr as leihfilmnr, fl.datum, fl.kopiert, fl.angeschaut from filme f left join film_leihliste fl on f.id = fl.filmnr and fl.usernr = :usernr order by f.id desc";
$stmt = $db->prepare($sql);
$stmt->bindParam(':usernr', $usernr, PDO::PARAM_INT);
$stmt->execute();

$out = array();

while($temp = $stmt->fetch(PDO::FETCH_ASSOC))
{
	$out[] = array('filmnr' => $temp['id'],'tt' => $temp['tt'] ,'name' => $temp['name'], 'rating' => $temp['rating'], 'plot' => $temp['plot'], 'poster' => $temp['poster'], 'date' => date('d.m. Y',strtotime($temp['datum'])), 'aufliste' => $temp['usernr'], 'angeschaut' => $temp['angeschaut']);
}

echo json_encode($out);

?>