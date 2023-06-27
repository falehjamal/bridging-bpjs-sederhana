<?php
require './vendor/autoload.php';

use NajmulFaiz\Bpjs\VClaim\Peserta;
use NajmulFaiz\Bpjs\VClaim\Referensi;
use NajmulFaiz\Bpjs\VClaim\RencanaKontrol;

require 'authApi.php';

$nik = @$_POST['nik'];
$date =  date("Y-m-d");
// $referensi = new NajmulFaiz\Bpjs\VClaim\Referensi($vclaim_conf);
// $surat = new RencanaKontrol($vclaim_conf);
$peserta = new Peserta($vclaim_conf);
$data = $peserta->getByNIK($nik,$date);

echo json_encode($data);

?>
