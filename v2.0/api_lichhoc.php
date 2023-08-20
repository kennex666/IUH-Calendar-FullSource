<?php

require_once 'Package/GenerateICS.php';

if (!isset($_GET['sc'])){
    die(json_encode(array('error' => 'Lỗi: Không có mã trường. Vui lòng liên hệ m.me/DThaiBao666 để được hỗ trợ!')));}
    
$dataSchool = json_decode(file_get_contents('DataBin/'. strtolower($_GET['sc']).'.json'), true);

//$_GET['k'] = 'pbekzkCNs99D4hRJsLvuTrKCzVt7AeUhb0YmHxpmMFM';

if (!isset($_GET['token']))
    die(json_encode(array('error' => 'Lỗi: Không có token xác minh. Vui lòng liên hệ m.me/DThaiBao666 để được hỗ trợ!')));

if (!isset($_GET['timeGenerate']))
    die(json_encode(array('error' => 'Lỗi: Không có thời gian tạo. Vui lòng liên hệ m.me/DThaiBao666 để được hỗ trợ!')));

if ($_GET['token'] != md5('iuh'.$_GET['timeGenerate'].$_GET['k'].'iuh'))
    die(json_encode(array('error' => 'Lỗi: Token xác minh không hợp lệ. Vui lòng liên hệ m.me/DThaiBao666 để được hỗ trợ!')));



define('_TRANS_INTO_TIME', $dataSchool['TranslateTime']);



$event = new ObxStudios\ICS();

if ($_GET['timeGenerate'] + 331536000 < time()) {
    $event->addEvent(date('d/m/Y H:i', time() + 86400),  date('d/m/Y H:i', time()), "Lịch học của bạn đã hết hạn!", "Hãy vào Google gỡ lịch và cài lại nhé!", '');
    $event->addEvent(date('d/m/Y H:i', time() + 172800),  date('d/m/Y H:i', time()), "Lịch học của bạn đã hết hạn!", "Hãy vào Google gỡ lịch và cài lại nhé!", '');
    $event->addEvent(date('d/m/Y H:i', time() + 259200),  date('d/m/Y H:i', time()), "Lịch học của bạn đã hết hạn!", "Hãy vào Google gỡ lịch và cài lại nhé!", '');
    $event->addEvent(date('d/m/Y H:i', time() + 345600),  date('d/m/Y H:i', time()), "Lịch học của bạn đã hết hạn!", "Hãy vào Google gỡ lịch và cài lại nhé!", '');
    $event->addEvent(date('d/m/Y H:i', time() + 432000),  date('d/m/Y H:i', time()), "Lịch học của bạn đã hết hạn!", "Hãy vào Google gỡ lịch và cài lại nhé!", '');
    $event->addEvent(date('d/m/Y H:i', time() + 518400),  date('d/m/Y H:i', time()), "Lịch học của bạn đã hết hạn!", "Hãy vào Google gỡ lịch và cài lại nhé!", '');

    $event->save();
    $event->show();
}

// DEBUG
// print_r(json_encode(
//      analyticsData(Curl_Request_Get('05/12/2022', 0), 0)
//      ));;
// exit();
//time() > $lastTimeRequest + 24 * 60 * 60
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

    $result = createCalendar(analyticsData(Curl_Request_Get($tuan1, $loai), $loai));
    createCalendar(analyticsData(Curl_Request_Get($tuan2, $loai), $loai));
    createCalendar(analyticsData(Curl_Request_Get($tuan3, $loai), $loai));
    createCalendar(analyticsData(Curl_Request_Get($tuan4, $loai), $loai));
    createCalendar(analyticsData(Curl_Request_Get($tuan5, $loai), $loai));
} else {
    $result = createCalendar(analyticsData(Curl_Request_Get($tuan1, 1), 1));
    createCalendar(analyticsData(Curl_Request_Get($tuan2, 1), 1));
    createCalendar(analyticsData(Curl_Request_Get($tuan3, 1), 1));
    createCalendar(analyticsData(Curl_Request_Get($tuan4, 1), 1));
    createCalendar(analyticsData(Curl_Request_Get($tuan5, 1), 1));
    createCalendar(analyticsData(Curl_Request_Get($tuan1, 2), 2));
    createCalendar(analyticsData(Curl_Request_Get($tuan2, 2), 2));
    createCalendar(analyticsData(Curl_Request_Get($tuan3, 2), 2));
    createCalendar(analyticsData(Curl_Request_Get($tuan4, 2), 2));
    createCalendar(analyticsData(Curl_Request_Get($tuan5, 2), 2));
}
$event->save();
$event->show();
