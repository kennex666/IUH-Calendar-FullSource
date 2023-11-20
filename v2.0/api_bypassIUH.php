<?php
error_reporting(0);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Check if it's a preflight OPTIONS request and respond with CORS headers
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $mssv = $_GET['mssv'];
    $idDot = $_POST['idDot'];
    $webVer = $_GET['webVer'];
    $accountID = isset($_GET['idAccount']) ? $_GET['idAccount'] : "10001903";

    if ($accountID == "19122003"){
        echo "";
        return;
    }

    if (strpos(strtolower($_SERVER['HTTP_REFERER']), "dkhp.iuh.edu.vn") == false) {
        header("Location: https://iuh.edu.vn/");
    } else {
        if ($idDot <= 55) {
            echo "";
            return;
        }
        if (!empty($webVer) && $webVer == "1.0.0") {
            switch ($mssv) {
                    //case '21123021': // Luân
                    //case '21063601': // Khang Duy
                case '21037621': // Bảo
                    if (time() > 1698505200)
                        echo ""; // Hạn chế theo khoảng thời gian
                    else
                        echo "";
                    break;
                case '11111':  // Test
                    echo "Máy chủ test dkhp.iuh.edu.vn!";
                    break;
                default:
                    echo "Đợt này sinh viên không được đăng ký!";
            }
        } else {
            echo "<span style=\"color:red;\">Tài khoản " . $accountID . " đã bị khoá chức năng.</span><br>Vui lòng liên hệ với QTV!";
        }
    }
} else {
    header("Location: https://iuh.edu.vn/");
}
