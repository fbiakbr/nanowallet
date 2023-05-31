<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Input Pemasukan</h4>
                    <p class="fst-italic"><small>* Pastikan data yang diinput sudah benar</small></p>
                    <p class="fst-italic"><small>* Apabila data yang diinput salah, silahkan hapus data pemasukan yang salah kemudian input ulang</small></p>
                    <form action="<?= base_url('admin/save_pemasukan') ?>" method="post">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputUsername1">Scan ID Card</label>
                                    <input type="password" class="form-control" id="exampleInputUsername1" placeholder="Scan ID Card" name="rfid" autofocus>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">NIS</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="NIS" name="nis" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Nama Siswa</label>
                                    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Nama Siswa" name="nama_siswa" required readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputConfirmPassword1">Kelas</label>
                                    <input type="text" class="form-control" id="exampleInputConfirmPassword1" placeholder="Kelas" name="kelas" required readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputConfirmPassword1">Tanggal</label>
                                    <input type="date" class="form-control" id="exampleInputConfirmPassword1" name="tgl_pemasukan" required readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputConfirmPassword1">Jam</label>
                                    <input type="text" class="form-control" id="exampleInputConfirmPassword1" placeholder="" name="jam" readonly required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputConfirmPassword1">Jumlah</label>
                                    <input type="text" class="form-control" id="exampleInputConfirmPassword1" placeholder="Rp 0" name="jumlah" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputConfirmPassword1">Keterangan</label>
                                    <input type="text" class="form-control" id="exampleInputConfirmPassword1" placeholder="Keterangan" name="keterangan">
                                </div>
                            </div>
                        </div>
                        <button id="savePemasukan" type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    setInterval(() => {
        let date = new Date();
        let day = date.getDate();
        let month = date.getMonth() + 1;
        let year = date.getFullYear();
        let hour = date.getHours();
        let minute = date.getMinutes();
        let second = date.getSeconds();
        if (day < 10) {
            day = '0' + day;
        }
        if (month < 10) {
            month = '0' + month;
        }
        if (hour < 10) {
            hour = '0' + hour;
        }
        if (minute < 10) {
            minute = '0' + minute;
        }
        if (second < 10) {
            second = '0' + second;
        }
        let today = year + '-' + month + '-' + day;
        let time = hour + ':' + minute + ':' + second;

        let tglPemasukan = document.querySelector('input[name="tgl_pemasukan"]');
        let jam = document.querySelector('input[name="jam"]');

        tglPemasukan.value = today;
        jam.value = time;
    }, 1000);

    let dataSiswa = <?= json_encode($siswa) ?>;
    let dataKelas = <?= json_encode($kelas) ?>;

    dataSiswa.forEach((item, index) => {
        dataSiswa[index].kelas = dataKelas.find((kelas) => {
            return kelas.id_kelas == item.kelas;
        }).nama_kelas;
    });
    // console.log(dataSiswa);

    let rfid = document.querySelector('input[name="rfid"]');
    let nis = document.querySelector('input[name="nis"]');
    let namaSiswa = document.querySelector('input[name="nama_siswa"]');
    let kelas = document.querySelector('input[name="kelas"]');
    let jumlah = document.querySelector('input[name="jumlah"]');
    let keterangan = document.querySelector('input[name="keterangan"]');

    rfid.addEventListener('keyup', () => {
        let rfidValue = rfid.value;
        let siswa = dataSiswa.find((item) => {
            return item.rfid == rfidValue;
        });
        if (siswa) {
            nis.value = siswa.nis;
            namaSiswa.value = siswa.nama_siswa;
            kelas.value = siswa.kelas;
        } else {
            nis.value = '';
            namaSiswa.value = '';
            kelas.value = '';
        }
    });

    nis.addEventListener('keyup', () => {
        let nisValue = nis.value;
        let siswa = dataSiswa.find((item) => {
            return item.nis == nisValue;
        });
        if (siswa) {
            rfid.value = siswa.rfid;
            namaSiswa.value = siswa.nama_siswa;
            kelas.value = siswa.kelas;
        } else {
            rfid.value = '';
            namaSiswa.value = '';
            kelas.value = '';
        }
    });

    function formatRupiah(angka, prefix) {
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : rupiah ? 'Rp ' + rupiah : '';
    }

    jumlah.addEventListener('keyup', () => {
        jumlah.value = formatRupiah(jumlah.value, 'Rp ');
    });

    let submit = document.querySelector('button[type="submit"]');
    submit.addEventListener('click', (e) => {
        let nisValue = nis.value;
        let rfidValue = rfid.value;
        let siswa = dataSiswa.find((item) => {
            return item.nis == nisValue || item.rfid == rfidValue;
        });
        if (!siswa) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'NIS atau RFID tidak ditemukan!',
            });
        } else if (jumlah.value == 'Rp 0') {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Jumlah tidak boleh 0!',
            });
        }
    });
</script>
<?= $this->endSection(); ?>