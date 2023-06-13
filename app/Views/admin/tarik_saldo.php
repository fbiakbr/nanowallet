<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tarik Saldo</h4>
                    <p class="fst-italic"><small>* Pastikan data yang diinput sudah benar</small></p>
                    <p class="fst-italic"><small>* Apabila data yang diinput salah, silahkan hapus pada data pengeluaran kemudian input ulang</small></p>
                    <form action="<?= base_url('admin/save_penarikan') ?>" method="post">
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputConfirmPassword1">Tanggal</label>
                                    <input type="date" class="form-control" id="exampleInputConfirmPassword1" name="tgl_pengeluaran" required readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputConfirmPassword1">Jam</label>
                                    <input type="text" class="form-control" id="exampleInputConfirmPassword1" placeholder="" name="jam" readonly required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputConfirmPassword1">Saldo</label>
                                    <input type="text" class="form-control" id="exampleInputConfirmPassword1" placeholder="Saldo" name="saldo" readonly required>
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
                                    <input type="text" class="form-control" id="keterangan" placeholder="Keterangan" name="keterangan" readonly>
                                </div>
                            </div>
                        </div>
                        <button id="savePenarikan" class="btn btn-gradient-primary me-2">Submit</button>
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

        let tglPemasukan = document.querySelector('input[name="tgl_pengeluaran"]');
        let jam = document.querySelector('input[name="jam"]');

        tglPemasukan.value = today;
        jam.value = time;
    }, 100);

    let dataSiswa = <?= json_encode($siswa) ?>;
    let dataKelas = <?= json_encode($kelas) ?>;
    let dataSaldo = <?= json_encode($saldo) ?>;
    let dataPIN = [];

    dataSiswa.forEach((item, index) => {
        dataSiswa[index].kelas = dataKelas.find((kelas) => {
            return kelas.id_kelas == item.kelas;
        }).nama_kelas;
    });
    // console.log(dataSiswa);

    dataSiswa.forEach((item, index) => {
        let tanggalLahir = item.tanggal_lahir.split('-');
        let tanggalLahirString = tanggalLahir[2] + tanggalLahir[1] + tanggalLahir[0];
        dataPIN.push(tanggalLahirString);
    });
    // console.log(dataPIN);

    let rfid = document.querySelector('input[name="rfid"]');
    let nis = document.querySelector('input[name="nis"]');
    let namaSiswa = document.querySelector('input[name="nama_siswa"]');
    let kelas = document.querySelector('input[name="kelas"]');
    let saldo = document.querySelector('input[name="saldo"]');
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
            saldo.value = formatRupiah(dataSaldo.find((item) => {
                return item.nis == siswa.nis;
            }).saldo.toString(), 'Rp ');
        } else {
            nis.value = '';
            namaSiswa.value = '';
            kelas.value = '';
            saldo.value = '';
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
            saldo.value = formatRupiah(dataSaldo.find((item) => {
                return item.nis == siswa.nis;
            }).saldo.toString(), 'Rp ');
        } else {
            rfid.value = '';
            namaSiswa.value = '';
            kelas.value = '';
            saldo.value = '';
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

    keterangan.value = 'PENARIKAN SALDO';

    let savePenarikan = document.querySelector('#savePenarikan');

    savePenarikan.addEventListener('click', (e) => {
        e.preventDefault();
        let rfidValue = rfid.value;
        let nisValue = nis.value;
        let namaSiswaValue = namaSiswa.value;
        let kelasValue = kelas.value;
        let saldoValue = saldo.value;
        let jumlahValue = jumlah.value;
        let keteranganValue = keterangan.value;
        if (rfidValue == '' || nisValue == '' || namaSiswaValue == '' || kelasValue == '' || saldoValue == '' || jumlahValue == '' || keteranganValue == '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Ada data yang kosong!',
            });
        } else if (parseInt(jumlahValue.replace(/[^,\d]/g, '')) > parseInt(saldoValue.replace(/[^,\d]/g, ''))) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Jumlah melebihi saldo!',
            });
            jumlah.value = saldoValue;
        } else if (parseInt(jumlahValue.replace(/[^,\d]/g, '')) < 10000 || parseInt(jumlahValue.replace(/[^,\d]/g, '')) == 0 || parseInt(jumlahValue.replace(/[^,\d]/g, '')) < 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Jumlah minimal penarikan saldo adalah Rp 10.000!',
            });
            jumlah.value = 'Rp 10.000';
        } else {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Saldo akan ditarik sebesar " + jumlahValue,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#8d41ff',
                cancelButtonColor: '#f44336',
                confirmButtonText: 'Ya, tarik saldo',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Masukkan PIN',
                        input: 'password',
                        inputLabel: 'PIN',
                        inputPlaceholder: 'Masukkan PIN',
                        inputAttributes: {
                            autocapitalize: 'off'
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Submit',
                        showLoaderOnConfirm: true,
                        preConfirm: (pin) => {
                            if (pin == dataPIN.find((item) => {
                                    return item == pin;
                                })) {
                                let form = document.querySelector('form');
                                Swal.fire({
                                    title: 'Tunggu sebentar',
                                    html: 'Sedang menarik saldo',
                                    timerProgressBar: true,
                                    timer: 1500,
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                        setTimeout(() => {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Berhasil',
                                                text: 'Saldo berhasil ditarik',
                                                showConfirmButton: false,
                                                timer: 1500,
                                                allowOutsideClick: false,
                                                allowEscapeKey: false,
                                                didOpen: () => {
                                                    setTimeout(() => {
                                                        form.submit();
                                                    }, 1500);
                                                }
                                            });
                                        }, 3000);
                                    }
                                });
                            } else {
                                Swal.showValidationMessage(
                                    `PIN salah!`
                                )
                            }
                        },
                    });
                }
            });
        }
    });
</script>
<?= $this->endSection(); ?>