<?php
require './vendor/autoload.php';

use NajmulFaiz\Bpjs\VClaim\Peserta;

try {
    $date = date("Y-m-d");
    $nik = $_POST['nik'] ?? '';

    if ($_SERVER['REMOTE_ADDR'] !== '127.0.0.1' && $_SERVER['REMOTE_ADDR'] !== '::1') {
        http_response_code(403);
        echo json_encode(["metaData" => ["code" => 403, "message" => "Akses ditolak"]]);
        exit;
    }

    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
        http_response_code(403);
        echo json_encode(["metaData" => ["code" => 403, "message" => "Akses ditolak"]]);
        exit;
    }

    if (empty($nik) || !is_numeric($nik)) {
        http_response_code(400);
        echo json_encode(["metaData" => ["code" => 400, "message" => "Input NIK tidak valid"]]);
        exit;
    }

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $vclaim_conf = [
        'cons_id' => $_ENV['CONS_ID'],
        'secret_key' => $_ENV['SECRET_KEY'],
        'user_key' => $_ENV['USER_KEY'],
        'base_url' => $_ENV['BASE_URL'],
        'service_name' => $_ENV['SERVICE_NAME']
    ];

    $peserta = new Peserta($vclaim_conf);

    switch (strlen($nik)) {
        case 13:
            $data = $peserta->getByNoKartu($nik, $date);
            break;
        case 16:
            $data = $peserta->getByNIK($nik, $date);
            break;
        default:
            $data = [
                "metaData" => [
                    "code" => 401,
                    "message" => "Nomor Tidak Valid"
                ],
            ];
            break;
    }

    echo json_encode($data);
} catch (Exception $e) {
    http_response_code(500) ;
    $data = [
        "metaData" => [
            "code" => 500,
            "message" => "Ada kesalahan"
        ],
    ];

    echo json_encode($data);
}

