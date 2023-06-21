<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <?php if (session()->getFlashdata('message')) : ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('message') ?>
                        </div>
                    <?php endif; ?>
                    <h4 class="card-title">Data Pemasukan</h4>
                    <a href="<?= base_url('admin/pdf_pemasukan') ?>" class="btn btn-primary btn-sm mb-3"><span class="mdi mdi-printer"></span> Export PDF</a>
                    <a href="<?= base_url('admin/excel_pemasukan') ?>" class="btn btn-success btn-sm mb-3"><span class="mdi mdi-file-excel"></span> Export Excel</a>
                    <p class="fw-bold"><small>* Mohon untuk tidak menghapus data pemasukan yang sudah ada, karena data pemasukan yang dihapus akan mengurangi saldo dari siswa tersebut.</small></p>
                    <p class="fw-bold"><small>* Terkecuali ada kesalahan input data pemasukan, silahkan hapus data pemasukan yang salah kemudian input ulang.</small></p>
                    <div class="row table-responsive">
                        <table id="table1" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Pemasukan</th>
                                    <th>Jam</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jumlah</th>
                                    <th>Invoice</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                array_multisort(
                                    array_column($data, 'tgl_pemasukan'),
                                    SORT_DESC,
                                    array_column($data, 'jam'),
                                    SORT_DESC,
                                    $data
                                );
                                foreach ($data as $key => $value) : ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td><?= $value['tgl_pemasukan'] ?></td>
                                        <td><?= $value['jam'] ?></td>
                                        <td><?= $value['nis'] ?></td>
                                        <td><?= $value['nama_siswa'] ?></td>
                                        <td><?= $value['kelas'] ?></td>
                                        <td><?= "Rp " . number_format($value['jumlah'], 0, ',', '.') ?></td>
                                        <td>
                                            <a href="<?= base_url('admin/invoice_pemasukan/' . $value['id_pemasukan']) ?>" class="btn btn-primary btn-sm"><span class="mdi mdi-note-text"></span> Invoice</a>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('admin/delete_pemasukan/' . $value['id_pemasukan']) ?>" id="hapusPemasukan" class="btn btn-danger btn-sm" onclick="alertDelete(event)"><span class="mdi mdi-delete"></span> Hapus</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let hapusPemasukan = document.getElementById('hapusPemasukan');
    let alertDelete = (e) => {
        e.preventDefault();
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.location.href = hapusPemasukan.href;
            }
        })
    }
</script>
<?= $this->endSection(); ?>