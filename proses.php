<?php
require './vendor/autoload.php';
require 'authApi.php';

use NajmulFaiz\Bpjs\VClaim\Peserta;

$date = date("Y-m-d");
$nik = $_POST['nik'] ?? '';
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
?>
