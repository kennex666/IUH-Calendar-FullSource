<?php
namespace ObxStudios;

class ICS
{
    var $data;
    var $name;
    var $nameCalender = "Lịch học & thi";


    function __construct()
    {
    }

    function setName($nameCalender)
    {
        $this->nameCalender = $nameCalender;
    }

    function addEvent($start, $end, $name, $description, $location)
    {
        $start = $this->createTimeForCalendar($start);
        $end = $this->createTimeForCalendar($end);
        $now = date('Ymd\THis', time());
        $randomID = md5($start . $end . $name . $description . $location . $now);
        $name = html_entity_decode($name);
        $description = html_entity_decode($description);

        if (empty($this->data)) {
            $this->data = "BEGIN:VCALENDAR\nPRODID:1BoxStudios Calendar Creator\nVERSION:2.0\nCALSCALE:GREGORIAN\nMETHOD:PUBLISH\nX-WR-CALNAME:{$this->nameCalender}\nX-WR-TIMEZONE:Asia/Ho_Chi_Minh";
            //\nX-WR-TIMEZONE:Asia/Ho_Chi_Minh
        }
        $this->data .= "\nBEGIN:VEVENT\nDTSTART:$start\nDTEND:$end\nDTSTAMP:$now\nUID:$randomID@1boxstudios.com\nDESCRIPTION:$description\nLAST-MODIFIED:$now\nLOCATION:\nSEQUENCE:0\nSTATUS:CONFIRMED\nSUMMARY:$name\nTRANSP:OPAQUE\nBEGIN:VALARM\nACTION:DISPLAY\nDESCRIPTION:$description\nTRIGGER:-P0DT0H10M0S\nEND:VALARM\nEND:VEVENT";
        // $this->data = "BEGIN:VCALENDAR\nVERSION:2.0\nMETHOD:PUBLISH\nBEGIN:VTIMEZONE\nTZID:Asia/Bangkok\n";
    }

    function save()
    {
        if (empty($this->data)) {
            $this->data = "BEGIN:VCALENDAR\nPRODID:1BoxStudios Calendar Creator\nVERSION:2.0\nCALSCALE:GREGORIAN\nMETHOD:PUBLISH\nX-WR-CALNAME:{$this->nameCalender}\nX-WR-TIMEZONE:Asia/Ho_Chi_Minh";
        }
        $this->data .= "\nEND:VCALENDAR";
    }

    function show()
    {
        header("Content-type:text/calendar");
        header('Content-Disposition: attachment; filename="lichhoc.ics"');
        Header('Content-Length: ' . strlen($this->data));
        Header('Connection: close');
        echo $this->data;
    }

    function createTimeForCalendar($time)
    {
        $time_format = sscanf($time, "%d/%d/%d %d:%d");
        if ($time_format[0] >= 0 && $time_format[0] <= 9)
            $time_format[0] = '0' . $time_format[0];

        if ($time_format[1] >= 0 && $time_format[1] <= 9)
            $time_format[1] = '0' . $time_format[1];

        if ($time_format[3] >= 0 && $time_format[3] <= 9)
            $time_format[3] = '0' . $time_format[3];

        if ($time_format[4] >= 0 && $time_format[4] <= 9)
            $time_format[4] = '0' . $time_format[4];

        $time_format = $time_format[2] . $time_format[1] . $time_format[0] . 'T' . $time_format[3] . $time_format[4] . '00';
        return $time_format;
    }
}