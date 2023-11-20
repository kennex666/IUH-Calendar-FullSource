<?php
$dataHeader = array();
$dataCalendar = "";

function generateDataGetCalendar($data, $listReplace){
    global $dataHeader;
    global $dataCalendar;

    $dataReturn = array();

    $dataHeader[] = 'Host: '. $data['host'];
    $dataHeader[] = 'Sec-Ch-Ua: \"Google Chrome\";v=\"107\", \"Chromium\";v=\"107\", \"Not=A?Brand\";v=\"24\"';
    $dataHeader[] = 'Sec-Ch-Ua-Mobile: ?0';
    $dataHeader[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36';
    $dataHeader[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
    $dataHeader[] = 'Accept: text/html, */*; q=0.01';
    $dataHeader[] = 'X-Requested-With: XMLHttpRequest';
    $dataHeader[] = 'Sec-Ch-Ua-Platform: \"Windows\"';
    $dataHeader[] = 'Origin: '. ($data['isHttps'] ? "https://" : "http://") .$data['host'];
    $dataHeader[] = 'Sec-Fetch-Site: same-origin';
    $dataHeader[] = 'Sec-Fetch-Mode: cors';
    $dataHeader[] = 'Sec-Fetch-Dest: empty';
    $dataHeader[] = 'Accept-Language: en-US,en;q=0.9,vi;q=0.8';

    $dataCalendar = $data['dataLich'];

    
    foreach ($listReplace as $key => $value) {
        $dataCalendar = str_replace('{{'. $key. '}}', $value, $dataCalendar);
    }

    $dataReturn['header'] = $dataHeader;
    $dataReturn['dataCalendar'] = $dataCalendar;

    $dataReturn['api'] = ($data['isHttps'] ? "https://" : "http://") . $data['host']. $data['pathGetLich'];

    return $dataReturn;
}

function Curl_Request_Get($data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $data['api']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data['dataCalendar']);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $data['header']);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    return $result;
}

function Curl_Check_API_Status($data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $data['api']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data['dataCalendar']);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $data['header']);

    $result = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        return 500;
    }
    curl_close($ch);
    return $statusCode;
    
}


function getLich($data, $loaiLich = 1)
{
    if (strpos($data, '</tr>') !== false)
        $data = explode('</tr>', $data)[0];
    $data = explode('<td>', $data);
    $flag = 'NORMAL';
    unset($data[0]);
    foreach ($data as $key => $value) {
        $value = explode('</div>', $value);
        foreach ($value as $key1 => $value1) {
            if ($flag == 'PAUSE') {
                $value1 = '[Tạm ngưng] [line_wrap] ' . $value1;
                $flag = 'NORMAL';
            } else if ($loaiLich == 2) {
                $value1 = '[THI] ' . $value1;
            }
            $value1 = str_replace('<p>', '<p>[line_wrap] ', $value1);
            $value[$key1] = rip_tags(strip_tags($value1));
            if ($value[$key1] == 'Tạm ngưng') {
                $flag = 'PAUSE';
                unset($value[$key1]);
            } else {
                $value[$key1] = str_replace(' [line_wrap] ', '\n', $value[$key1]);
            }
        }
        $flag = 'NORMAL';
        array_pop($value);
        $data[$key - 1] = $value;
    }
    
    unset($data[0]);
    return $data;
}

function getTimeline($data)
{
    //$data = str_replace('</span><br>', ' ', $data);
    $data = explode(
        '<th>',
        $data
    );

    unset($data[0]);
    foreach ($data as $key => $value) {
        $data[$key] = explode('<br>', $data[$key])[1];
        $data[$key - 1] = rip_tags(strip_tags($data[$key]));
        //echo $data[$key]. '<p>';
    }
    unset($data[0]);
    unset($data[8]);
    
    return $data;
}


function analyticsData($data, $loaiLich = 1)
{
    $data = explode(

        '<tr role="row">',
        $data

    );

    // 1 => Timeline
    // 2 => Buoi sang
    // 3 => Buoi chieu
    // 4 => Buoi toi
    $getTimeline = getTimeline($data[1]);

    $sang = getLich($data[2], $loaiLich);

    $chieu = getLich($data[3], $loaiLich);

    $toi = getLich($data[4], $loaiLich);




    //print_r(json_encode($getTimeline));
    $arrayData = array(
        'mon' => array(),
        'tue' => array(),
        'wed' => array(),
        'thu' => array(),
        'fri' => array(),
        'sat' => array(),
        'sun' => array(),
    );

    $counter = 0;
    foreach ($arrayData as $key => $value) {
        $counter++;
        $arrayData[$key] = array(
            'time' => $getTimeline[$counter],
            'morning' => array($sang[$counter]),
            'afternoon' => array($chieu[$counter]),
            'evening' => array($toi[$counter])
        );
    }




    return $arrayData;
}

// function is_iterable($var)
// {
//     return $var !== null
//         && (is_array($var)
//             || $var instanceof Traversable
//             || $var instanceof Iterator
//             || $var instanceof IteratorAggregate
//         );
// }

function rip_tags($string)
{

    // ----- remove HTML TAGs -----
    $string = preg_replace('/<[^>]*>/', ' ', $string);

    // ----- remove control characters -----
    $string = str_replace("\r", '', $string);    // --- replace with empty space
    $string = str_replace("\n", ' ', $string);   // --- replace with space
    $string = str_replace("\t", ' ', $string);   // --- replace with space

    // ----- remove multiple spaces -----
    $string = trim(preg_replace('/ {2,}/', ' ', $string));

    return $string;
}

function createCalendar($getData)
{
    global $event;
    if (is_null($getData['mon']['time'])) {
        $event->addEvent(date('d/m/Y H:i', time() + 86400),  date('d/m/Y H:i', time()), "Lỗi hệ thống lịch học!", "Hãy báo với Admin nếu bạn gặp sự cố này.\\nLink: m.me/DThaiBao616 hoặc contact@1boxstudios.com", '');
        $event->addEvent(date('d/m/Y H:i', time() + 172800),  date('d/m/Y H:i', time()), "Lỗi hệ thống lịch học!", "Hãy báo với Admin nếu bạn gặp sự cố này.\\nLink: m.me/DThaiBao616 hoặc contact@1boxstudios.com", '');
        $event->addEvent(date('d/m/Y H:i', time() + 259200),  date('d/m/Y H:i', time()), "Lỗi hệ thống lịch học!", "Hãy báo với Admin nếu bạn gặp sự cố này.\\nLink: m.me/DThaiBao616 hoặc contact@1boxstudios.com", '');
        $event->addEvent(date('d/m/Y H:i', time() + 345600),  date('d/m/Y H:i', time()), "Lỗi hệ thống lịch học!", "Hãy báo với Admin nếu bạn gặp sự cố này.\\nLink: m.me/DThaiBao616 hoặc contact@1boxstudios.com", '');
        $event->addEvent(date('d/m/Y H:i', time() + 432000),  date('d/m/Y H:i', time()), "Lỗi hệ thống lịch học!", "Hãy báo với Admin nếu bạn gặp sự cố này.\\nLink: m.me/DThaiBao616 hoặc contact@1boxstudios.com", '');
        $event->addEvent(date('d/m/Y H:i', time() + 518400),  date('d/m/Y H:i', time()), "Lỗi hệ thống lịch học!", "Hãy báo với Admin nếu bạn gặp sự cố này.\\nLink: m.me/DThaiBao616 hoặc contact@1boxstudios.com", '');
        $event->save();
        $event->show();
        exit();
    }

    foreach ($getData as $daysofweek) {
        $time = null;
        foreach ($daysofweek as $key => $value) {
            if ($key == 'time') {
                $time = $value;
            } else {
                foreach ($value as $key1 => $value1) {
                    // ?????
                    if (!is_iterable($value1)){
                    }else
                    foreach ($value1 as $key2 => $value2) {
                        $getHM = strpos($value2, '\\nTiết:');
                        if ($getHM !== false) {
                            $temp = explode('\\nTiết:', $value2);
                            $temp = explode('\\n', $temp[1]);
                            $temp = explode('-', $temp[0]);
                            $getHM = array(
                                'start' => (int) $temp[0],
                                'end' => (int) $temp[1],
                            );
                            //print_r($getHM);
                        } else {
                            $getHM = array(
                                'start' => '1',
                                'end' => '1',
                            );
                        }
                        $event->addEvent($time . ' ' . _TRANS_INTO_TIME[$getHM['start']], $time . ' ' . _TRANS_INTO_TIME[$getHM['end'] + 1] , '[' . explode(' ', explode('Phòng: ', explode('\n', $value2)[2])[1])[0]. '] '. explode('\n', $value2)[0], $value2, '');
                    }
                }
            }
        }
    }
    return true;
}