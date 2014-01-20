<?php 

include('db.class.php');

$sql = "SELECT f.*,fl.usernr from filme f left join film_leihliste fl on f.id = fl.filmnr order by f.id desc";

$stmt = $db->prepare($sql);
$stmt->execute();

$out = array();

while($temp = $stmt->fetch(PDO::FETCH_ASSOC))
{
	$out[] = array('filmnr' => $temp['id'],'tt' => $temp['tt'] ,'name' => $temp['name'], 'rating' => $temp['rating'], 'plot' => $temp['plot'], 'poster' => $temp['poster'], 'date' => date('d.m. Y',strtotime($temp['datum'])), 'aufliste' => $temp['usernr']);
}

echo json_encode($out);

?>