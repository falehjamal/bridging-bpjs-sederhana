<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Peserta Bpjs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js"></script>
    
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
                                <input type="text" class="form-control" placeholder="Masukan Nomor KTP" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                <button class="input-group-text" id="submit">Cari</button>
                        </div>
                    </form>


                            <!-- loading -->
                    <div class="container" id="loading" style="display: none;">
                    <svg class="circle-svg" height="200" width="200">
                    <circle cx="100" cy="100" r="50"></circle>
                    </svg>
                    </div>


                    <div class="row">
                     <div class="col-lg-7 isi">

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
                    if (res.metaData.code==201) {
                        $('.isi').html(`
                        <div class="alert alert-danger">`+res.metaData.message+`</div>
                        `);
                        return;
                    }
                    if (res.metaData.code==404) {
                        $('.isi').html(`
                        <div class="alert alert-danger">Mohon Isi NIK</div>
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
                    
                    if (sex=="L") {
                        sex="Laki-laki"
                    }else if(sex=="P"){
                        sex="Perempuan"
                    }
                     
                     var html = `
                            <table class="table">
                                <tr>
                                    <th colspan="2">Data kamu</th>
                                </tr>
                                <tr>
                                    <td>NIK</td>
                                    <td>:</td>
                                    <td>`+nik+`</td>
                                </tr>
                                <tr>
                                    <td>Nama</td>
                                    <td>:</td>
                                    <td>`+nama+`</td>
                                </tr>
                                <tr>
                                    <td>Jenis Kelamin</td>
                                    <td>:</td>
                                    <td>`+sex+`</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Lahir</td>
                                    <td>:</td>
                                    <td>`+tglLahir+`</td>
                                </tr>
                                <tr>
                                    <td>No Kartu Peserta</td>
                                    <td>:</td>
                                    <td>`+noKartu+`</td>
                                </tr>
                                <tr>
                                    <td>Kelas Peserta</td>
                                    <td>:</td>
                                    <td>`+hakkelas+`</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Cetak Kartu</td>
                                    <td>:</td>
                                    <td>`+tglCetakKartu+` (`+statusPeserta+`)</td>
                                </tr>
                                <tr>
                                    <td>Umur</td>
                                    <td>:</td>
                                    <td>`+umurSekarang+`</td>
                                </tr>
                            </table>
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
