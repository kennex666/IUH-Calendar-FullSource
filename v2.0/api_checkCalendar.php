<?php
error_reporting(0);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Check if it's a preflight OPTIONS request and respond with CORS headers

define("_URL_API", "https://api-iuh.1boxstudios.cf/v2.0/gateway/calendar.js?");

$dataHeader = array();
$dataCalendar = "";

function generateDataGetCalendar($data, $listReplace)
{
    global $dataHeader;
    global $dataCalendar;

    $dataReturn = array();

    $dataHeader[] = 'Host: ' . $data['host'];
    $dataHeader[] = 'Sec-Ch-Ua: \"Google Chrome\";v=\"107\", \"Chromium\";v=\"107\", \"Not=A?Brand\";v=\"24\"';
    $dataHeader[] = 'Sec-Ch-Ua-Mobile: ?0';
    $dataHeader[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36';
    $dataHeader[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
    $dataHeader[] = 'Accept: text/html, */*; q=0.01';
    $dataHeader[] = 'X-Requested-With: XMLHttpRequest';
    $dataHeader[] = 'Sec-Ch-Ua-Platform: \"Windows\"';
    $dataHeader[] = 'Origin: ' . ($data['isHttps'] ? "https://" : "http://") . $data['host'];
    $dataHeader[] = 'Sec-Fetch-Site: same-origin';
    $dataHeader[] = 'Sec-Fetch-Mode: cors';
    $dataHeader[] = 'Sec-Fetch-Dest: empty';
    $dataHeader[] = 'Accept-Language: en-US,en;q=0.9,vi;q=0.8';

    $dataCalendar = $data['dataLich'];


    foreach ($listReplace as $key => $value) {
        $dataCalendar = str_replace('{{' . $key . '}}', $value, $dataCalendar);
    }

    $dataReturn['header'] = $dataHeader;
    $dataReturn['dataCalendar'] = $dataCalendar;

    $dataReturn['api'] = ($data['isHttps'] ? "https://" : "http://") . $data['host'] . $data['pathGetLich'];

    return $dataReturn;
}


function Curl_Check_API_Status($data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $data['api']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data['dataCalendar']);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    curl_setopt($ch, CURLOPT_HTTPHEADER, $data['header']);

    $result = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        return 500;
    }
    curl_close($ch);
    return $statusCode;
}

if (!isset($_GET['sc']) || !file_exists('DataBin/' . strtolower($_GET['sc']) . '.json')) {
    die(json_encode(array('error' => 'Mã trường này không tồn tại hoặc chưa được hỗ trợ trên hệ thống!', 'code' => 501)));
}

if (!isset($_GET['k'])) {
    die(json_encode(array('error' => 'Không có khoá xác minh sinh viên!', 'code' => 502)));
}

$dataSchool = json_decode(file_get_contents('DataBin/' . strtolower($_GET['sc']) . '.json'), true);

define('_TRANS_INTO_TIME', $dataSchool['TranslateTime']);

$options = $_GET['o'];

switch ($options) {
    case 'csts': // Check status
        if (
            Curl_Check_API_Status(
                generateDataGetCalendar($dataSchool['informations'], array(
                    'k' => $_GET['k'],
                    'pNgayHienTai' => date('d/m/Y', time()),
                    'pLoaiLich' => 1,
                ))
            ) == 500
        ) {
            die(json_encode(array('error' => 'Lỗi: Lịch học không hợp lệ!', 'code' => 503)));
        }
        die(json_encode(
            array("status" => 200, "data" => "Valid")
        ));
        break;
    case 'gntk': // Generate token
        if (
            Curl_Check_API_Status(
                generateDataGetCalendar($dataSchool['informations'], array(
                    'k' => $_GET['k'],
                    'pNgayHienTai' => date('d/m/Y', time()),
                    'pLoaiLich' => 1,
                ))
            ) == 500
        ) {
            die(json_encode(array('error' => 'Lỗi: Lịch học không hợp lệ!', 'code' => 503)));
        }

        $timeGenerate = time();
        $sc = strtolower($_GET['sc']);

        die(json_encode(
            array("status" => 200, "data" => array(
                'normal' => _URL_API . "sc=$sc&k={$_GET['k']}&token=" .         md5(
                    strtolower($sc) . $timeGenerate . "0" . $_GET['k'] . strtolower($sc)
                ) . "&timeGenerate=" . $timeGenerate . "&loai=0",
                'onlyStudy' => _URL_API . "sc=$sc&k={$_GET['k']}&token=" .   md5(
                    strtolower($sc) . $timeGenerate . "1" . $_GET['k'] . strtolower($sc)
                ) . "&timeGenerate=" . $timeGenerate . "&loai=1",
                'onlyExams' => _URL_API . "sc=$sc&k={$_GET['k']}&token=" .         md5(
                    strtolower($sc) . $timeGenerate . "2" . $_GET['k'] . strtolower($sc)
                ) . "&timeGenerate=" . $timeGenerate . "&loai=2",
            ))
        ));
        break;
}
