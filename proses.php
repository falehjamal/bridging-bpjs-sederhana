<?php
require './vendor/autoload.php';

use NajmulFaiz\Bpjs\VClaim\Peserta;

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

$date = date("Y-m-d");
$nik = $_POST['nik'] ?? '';

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
