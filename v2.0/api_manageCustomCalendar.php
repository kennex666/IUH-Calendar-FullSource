<?php

error_reporting(0);
ini_set('display_errors', 1);
require_once 'Package/GenerateICS.php';
$data = json_decode(file_get_contents('kennex_calendar.json'), true);
if ($_GET['q'] == 'write'){
	$dataGet = file_get_contents('php://input');
	$jsonData = json_decode($dataGet, true);
	if (empty($jsonData['title']) || empty($jsonData['timeStart'])) {
		die(json_encode([ 'status' =>  100, 'message' => "Thiếu dữ liệu!"]));
	}
	
	if (empty($jsonData['timeEnd']))
		$jsonData['timeEnd'] = $jsonData['timeStart'];
	if (empty($jsonData['description']))
		$jsonData['description'] = "Kennen AI added!";
	
	$data[] = [
		'title' => $jsonData['title'],
		'description' => $jsonData['description'],
		'timeStart' => $jsonData['timeStart'],
		'timeEnd' => $jsonData['timeEnd'],
	];
	
	file_put_contents('kennex_calendar.json', json_encode($data));
		die(json_encode([ 'status' =>  200, 'message' => "Thêm lịch thành công!"]));

}else
if ($_GET['q'] == 'read'){
	$event = new ObxStudios\ICS();
	$event->setName("Lịch Kennen tạo");
	foreach ($data as $key => $value){
		$event->addEventStandardTime($value['timeStart'],  $value['timeEnd'], $value['title'], $value['description'], '');
	}

    $event->save();
    $event->show();
}