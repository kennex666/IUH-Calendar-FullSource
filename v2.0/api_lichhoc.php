<?php
error_reporting(0);
ini_set('display_errors', 1);

require_once 'Package/GenerateICS.php';
require_once 'Package/functions.php';

if (!isset($_GET['sc'])){
    die(json_encode(array('error' => 'Lỗi: Không có mã trường. Vui lòng liên hệ m.me/DThaiBao666 để được hỗ trợ!')));}
    
$dataSchool = json_decode(file_get_contents('DataBin/'. strtolower($_GET['sc']).'.json'), true);

//$_GET['k'] = 'pbekzkCNs99D4hRJsLvuTrKCzVt7AeUhb0YmHxpmMFM';

if (!isset($_GET['token']))
    die(json_encode(array('error' => 'Lỗi: Không có token xác minh. Vui lòng liên hệ m.me/DThaiBao666 để được hỗ trợ!')));

if (!isset($_GET['timeGenerate']))
    die(json_encode(array('error' => 'Lỗi: Không có thời gian tạo. Vui lòng liên hệ m.me/DThaiBao666 để được hỗ trợ!')));

$loaiToken = (isset($_GET['loai'])) ? $_GET['loai'] : 0;

if ($_GET['token'] != md5(strtolower($_GET['sc']).$_GET['timeGenerate']. $loaiToken .$_GET['k']. strtolower($_GET['sc'])))
    die(json_encode(array('error' => 'Lỗi: Token xác minh không hợp lệ. Vui lòng liên hệ m.me/DThaiBao666 để được hỗ trợ!')));



define('_TRANS_INTO_TIME', $dataSchool['TranslateTime']);



$event = new ObxStudios\ICS();

if ($_GET['timeGenerate'] + 331536000 < time()) {
    $event->addEvent(date('d/m/Y H:i', time() + 86400),  date('d/m/Y H:i', time()), "Lịch học của bạn đã hết hạn!", "Hãy vào Google để gỡ lịch và cài lại nhé!\\nLink: https://1boxstudios.com/schendar", '');
    $event->addEvent(date('d/m/Y H:i', time() + 172800),  date('d/m/Y H:i', time()), "Lịch học của bạn đã hết hạn!", "Hãy vào Google để gỡ lịch và cài lại nhé!\\nLink: https://1boxstudios.com/schendar", '');
    $event->addEvent(date('d/m/Y H:i', time() + 259200),  date('d/m/Y H:i', time()), "Lịch học của bạn đã hết hạn!", "Hãy vào Google để gỡ lịch và cài lại nhé!\\nLink: https://1boxstudios.com/schendar", '');
    $event->addEvent(date('d/m/Y H:i', time() + 345600),  date('d/m/Y H:i', time()), "Lịch học của bạn đã hết hạn!", "Hãy vào Google để gỡ lịch và cài lại nhé!\\nLink: https://1boxstudios.com/schendar", '');
    $event->addEvent(date('d/m/Y H:i', time() + 432000),  date('d/m/Y H:i', time()), "Lịch học của bạn đã hết hạn!", "Hãy vào Google để gỡ lịch và cài lại nhé!\\nLink: https://1boxstudios.com/schendar", '');
    $event->addEvent(date('d/m/Y H:i', time() + 518400),  date('d/m/Y H:i', time()), "Lịch học của bạn đã hết hạn!", "Hãy vào Google để gỡ lịch và cài lại nhé!\\nLink: https://1boxstudios.com/schendar", '');

    $event->save();
    $event->show();
    exit();
}

// DEBUG
// print_r(json_encode(
//      analyticsData(Curl_Request_Get('05/12/2022', 0), 0)
//      ));;
// exit();
//time() > $lastTimeRequest + 24 * 60 * 60
//if (isset($dataSchool['informations']['timeformat'])){
	//if ($dataSchool['informations']['timeformat'] == "ms"){
	
	$tuan1 = date('d/m/Y', time());

	$tuan2 = date('d/m/Y', time() + 7 * 24 * 60 * 60);

	$tuan3 = date('d/m/Y', time() + 14 * 24 * 60 * 60);

	$tuan4 = date('d/m/Y', time() + 21 * 24 * 60 * 60);

	$tuan5 = date('d/m/Y', time() + 28 * 24 * 60 * 60);

if (isset($_GET['loai']) && $_GET['loai'] != 0) {
    $loai = $_GET['loai'];

    if ($loai == 1) {
        $event->setName("Lịch học ({$dataSchool['schoolCode']})");
    } else {
        $event->setName("Lịch thi ({$dataSchool['schoolCode']})");
    }

   
    $result = createCalendar(analyticsData(Curl_Request_Get(
        generateDataGetCalendar($dataSchool['informations'], array(
            'k' => $_GET['k'],
            'pNgayHienTai' => $tuan1,
            'pLoaiLich' => $loai,
        ))
    ), $loai));

    createCalendar(analyticsData(Curl_Request_Get(
        generateDataGetCalendar($dataSchool['informations'], array(
            'k' => $_GET['k'],
            'pNgayHienTai' => $tuan2,
            'pLoaiLich' => $loai,
        ))
    ), $loai));
    createCalendar(analyticsData(Curl_Request_Get(
        generateDataGetCalendar($dataSchool['informations'], array(
            'k' => $_GET['k'],
            'pNgayHienTai' => $tuan3,
            'pLoaiLich' => $loai,
        ))
    ), $loai));
    createCalendar(analyticsData(Curl_Request_Get(
        generateDataGetCalendar($dataSchool['informations'], array(
            'k' => $_GET['k'],
            'pNgayHienTai' => $tuan4,
            'pLoaiLich' => $loai,
        ))
    ), $loai));
    createCalendar(analyticsData(Curl_Request_Get(
        generateDataGetCalendar($dataSchool['informations'], array(
            'k' => $_GET['k'],
            'pNgayHienTai' => $tuan5,
            'pLoaiLich' => $loai,
        ))
    ), $loai));
} else {
    $loai = 1;
    $result = createCalendar(analyticsData(Curl_Request_Get(
        generateDataGetCalendar($dataSchool['informations'], array(
            'k' => $_GET['k'],
            'pNgayHienTai' => $tuan1,
            'pLoaiLich' => $loai,
        ))
    ), $loai));

    createCalendar(analyticsData(Curl_Request_Get(
        generateDataGetCalendar($dataSchool['informations'], array(
            'k' => $_GET['k'],
            'pNgayHienTai' => $tuan2,
            'pLoaiLich' => $loai,
        ))
    ), $loai));
    createCalendar(analyticsData(Curl_Request_Get(
        generateDataGetCalendar($dataSchool['informations'], array(
            'k' => $_GET['k'],
            'pNgayHienTai' => $tuan3,
            'pLoaiLich' => $loai,
        ))
    ), $loai));
    createCalendar(analyticsData(Curl_Request_Get(
        generateDataGetCalendar($dataSchool['informations'], array(
            'k' => $_GET['k'],
            'pNgayHienTai' => $tuan4,
            'pLoaiLich' => $loai,
        ))
    ), $loai));
    createCalendar(analyticsData(Curl_Request_Get(
        generateDataGetCalendar($dataSchool['informations'], array(
            'k' => $_GET['k'],
            'pNgayHienTai' => $tuan5,
            'pLoaiLich' => $loai,
        ))
    ), $loai));


    $loai = 2;
    createCalendar(analyticsData(Curl_Request_Get(
        generateDataGetCalendar($dataSchool['informations'], array(
            'k' => $_GET['k'],
            'pNgayHienTai' => $tuan1,
            'pLoaiLich' => $loai,
        ))
    ), $loai));
    createCalendar(analyticsData(Curl_Request_Get(
        generateDataGetCalendar($dataSchool['informations'], array(
            'k' => $_GET['k'],
            'pNgayHienTai' => $tuan2,
            'pLoaiLich' => $loai,
        ))
    ), $loai));
    createCalendar(analyticsData(Curl_Request_Get(
        generateDataGetCalendar($dataSchool['informations'], array(
            'k' => $_GET['k'],
            'pNgayHienTai' => $tuan3,
            'pLoaiLich' => $loai,
        ))
    ), $loai));
    createCalendar(analyticsData(Curl_Request_Get(
        generateDataGetCalendar($dataSchool['informations'], array(
            'k' => $_GET['k'],
            'pNgayHienTai' => $tuan4,
            'pLoaiLich' => $loai,
        ))
    ), $loai));
    createCalendar(analyticsData(Curl_Request_Get(
        generateDataGetCalendar($dataSchool['informations'], array(
            'k' => $_GET['k'],
            'pNgayHienTai' => $tuan5,
            'pLoaiLich' => $loai,
        ))
    ), $loai));
}
$event->save();
$event->show();
