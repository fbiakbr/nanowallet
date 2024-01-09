<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Kehilangan/Kerusakan Kartu</h4>
                    <a href="<?= base_url('admin/form_kehilangan') ?>" class="btn btn-sm btn-primary"><i class="mdi mdi-download"></i> Form Kehilangan</a>
                    <p class="mt-2 fw-bold"><small>* Pastikan siswa telah mengisi formulir kehilangan kartu.</small></p>
                    <p class="mt-2 fw-bold"><small>* Pastikan siswa telah membayar <span class="text-danger">Rp 5.000</span> untuk biaya ganti kartu.</small></p>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" class="form-control" id="nis" placeholder="NIS" name="nis" required autofocus>
                                <button onclick="search()" id="btn" class="btn btn-sm mt-2 btn-primary"><i class="mdi mdi-book-search"></i> Cari Data Siswa</button>
                            </div>
                        </div>
                        <div class="col-md-9 table-responsive">
                            <table id="siswa" class="table table-bordered">
                                <thead class="text-center">
                                    <tr>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>RFID</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center" id="data"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function search() {
        let nis = document.getElementById('nis').value;
        let btn = document.getElementById('btn');
        if (nis != '') {
            let dataSiswa = <?= json_encode($siswa) ?>;
            let dataKelas = <?= json_encode($kelas) ?>;
            dataSiswa.forEach((item, index) => {
                dataSiswa[index].kelas = dataKelas.find((kelas) => {
                    return kelas.id_kelas == item.kelas;
                }).nama_kelas;
            });
            // console.log(nis);
            let table = document.getElementById('siswa');
            let tbody = document.getElementById('data');
            tbody.innerHTML = '';
            dataSiswa.forEach((item, index) => {
                if (item.nis == nis) {
                    tbody.innerHTML += `
                    <tr>
                        <td>${item.nis}</td>
                        <td>${item.nama_siswa}</td>
                        <td>${item.kelas}</td>
                        <td>${item.rfid}</td>
                        <td><a href="<?= base_url('admin/update_kartu') ?>?rfid=${item.rfid}" class="btn btn-sm btn-primary"><i class="mdi mdi-book-plus"></i> Kehilangan</a></td>
                    </tr>
                    `
                }
            })
            if (tbody.innerHTML == '') {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center">Data Tidak Ditemukan!</td></tr>`
            }
        } else {
            alert('NIS harus diisi!');
        }
    }
</script>
<?= $this->endSection(); ?>