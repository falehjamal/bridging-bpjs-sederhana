<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Peserta Bpjs</title>
    <link rel="stylesheet" href="public/assets/css/bootstrap.min.css">
    <script src="public/assets/js/jquery.min.js"></script>
    <script src="public/assets/js/bootstrap.bundle.min.js"></script>
    <style>
body{
    margin-top:100px;
    background-color: #aeaeae;
  font-family: sans-serif;
}

:root {
  --radius: 50;
  --PI: 3.14159265358979;
  --circumference: calc(var(--PI) * var(--radius) * 2px)
}
.container {
  display: block;
  flex-flow: column; 
  align-items: center;
}
h1 {
  color: #444;
}
.circle-svg {
  background: #fff;
}
.circle-svg circle {
  stroke: red;
  stroke-width: 4;
  fill: transparent;
  transform-origin: center;
  stroke-dasharray: var(--circumference);
  animation: spinner 2s ease-out infinite;
}

@keyframes spinner {
  from {
    stroke-dashoffset: var(--circumference);
    stroke: red;
    transform: rotateZ(0deg)
  }
  to {
    stroke-dashoffset: calc(var(--circumference) * -1);
    stroke: green;
    transform: rotateZ(720deg)
  }
}

    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="card">
                <div class="card-header">
                    Cek Kepesertaan BPJS
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Masukan Nomor KTP/No Kartu Peserta BPJS" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                <button class="input-group-text" id="submit">Cari</button>
                        </div>
                    </form>


                            <!-- loading -->
                    <div id="loading" style="display: none;">
                    <div class="container d-flex justify-content-center align-items-center">
                        <svg class="circle-svg" height="200" width="200">
                            <circle cx="100" cy="100" r="50"></circle>
                        </svg>
                    </div>
                    </div>


                    <div class="row">
                     <div class="col-lg-12 isi">
                        <!-- isi disini -->
                     </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
<script>
    $(document).ready(function(){
        $('form').submit(function(e){
    e.preventDefault();
    $('#loading').show();
    $(".isi").empty();
    var nik = $('input[type="text"]').val();

    $.ajax({
        url: 'proses.php',
        method: 'POST',
        data: {'nik':nik},
        success: function(response) {
            var res = $.parseJSON(response);

            if (res.metaData.code == 201) {
                $('.isi').html(`
                <div class="alert alert-danger">`+res.metaData.message+`</div>
                `);
                return;
            }
            if (res.metaData.code == 404) {
                $('.isi').html(`
                <div class="alert alert-danger">Mohon Isi NIK</div>
                `);
                return;
            }
            if (res.metaData.code == 401) {
                $('.isi').html(`
                <div class="alert alert-danger">Nomor salah coyyy</div>
                `);
                return;
            }

            var nama = res.response.peserta.nama;
            var nik = res.response.peserta.nik;
            var sex = res.response.peserta.sex;
            var tglLahir = res.response.peserta.tglLahir;
            var tglCetakKartu = res.response.peserta.tglCetakKartu;
            var noKartu = res.response.peserta.noKartu;
            var umurSekarang = res.response.peserta.umur.umurSekarang;
            var statusPeserta = res.response.peserta.statusPeserta.keterangan;
            var hakkelas = res.response.peserta.hakKelas.keterangan;
            var nohp = res.response.peserta.mr.noTelepon;
            var jenisPeserta = res.response.peserta.jenisPeserta.keterangan;

            if (statusPeserta == "AKTIF") {
                statusPeserta = `<span class="badge bg-success">`+statusPeserta+`</span>`;
            } else {
                statusPeserta = `<span class="badge bg-danger">`+statusPeserta+`</span>`;
            }
            sex = sex == "L" ? "Laki-laki" : "Perempuan";
            nohp = nohp == null ? "-" : nohp;
            tglLahir = tglLahir.split("-").reverse().join("-");
            tglCetakKartu = tglCetakKartu.split("-").reverse().join("-");

            // Create a two-column layout for displaying data
            var html = `
                <div class="row">
                    <div class="col-md-6">
                        <table class="table">
                            <tr><th colspan="3">Data Kamu</th></tr>
                            <tr><td><strong>NIK</strong></td><td>:</td><td>`+nik+`</td></tr>
                            <tr><td><strong>Nama</strong></td><td>:</td><td>`+nama+`</td></tr>
                            <tr><td><strong>Jenis Kelamin</strong></td><td>:</td><td>`+sex+`</td></tr>
                            <tr><td><strong>Tanggal Lahir</strong></td><td>:</td><td>`+tglLahir+`</td></tr>
                            <tr><td><strong>Umur</strong></td><td>:</td><td>`+umurSekarang+`</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table">
                            <tr><th colspan="3">Informasi Tambahan</th></tr>
                            <tr><td><strong>No Kartu Peserta</strong></td><td>:</td><td>`+noKartu+`</td></tr>
                            <tr><td><strong>Kelas Peserta</strong></td><td>:</td><td>`+hakkelas+` `+statusPeserta+`</td></tr>
                            <tr><td><strong>Jenis Peserta</strong></td><td>:</td><td>`+jenisPeserta+`</td></tr>
                            <tr><td><strong>Tanggal Cetak Kartu</strong></td><td>:</td><td>`+tglCetakKartu+`</td></tr>
                            <tr><td><strong>No Telepon</strong></td><td>:</td><td>`+nohp+`</td></tr>
                        </table>
                    </div>
                </div>
            `;

            $('.isi').html(html);

        },
        error: function(xhr, status, error) {
            console.log(error);
        },
        complete:()=>{
            $('#loading').hide();
        }
    });
});

    });
</script>
</body>
</html>
