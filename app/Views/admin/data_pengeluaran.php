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
                    <h4 class="card-title">Data Pengeluaran</h4>
                    <a href="<?= base_url('admin/pdf_pengeluaran') ?>" class="btn btn-primary btn-sm mb-3"><span class="mdi mdi-printer"></span> Export PDF</a>
                    <a href="<?= base_url('admin/excel_pengeluaran') ?>" class="btn btn-success btn-sm mb-3"><span class="mdi mdi-file-excel"></span> Export Excel</a>
                    <div class="row table-responsive">
                        <table id="table1" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Pengeluaran</th>
                                    <th>Jam</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                    <th>Invoice</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                array_multisort(
                                    array_column($data, 'tgl_pengeluaran'),
                                    SORT_DESC,
                                    array_column($data, 'jam'),
                                    SORT_DESC,
                                    $data
                                );
                                foreach ($data as $key => $value) : ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td><?= $value['tgl_pengeluaran'] ?></td>
                                        <td><?= $value['jam'] ?></td>
                                        <td><?= $value['nis'] ?></td>
                                        <td><?= $value['nama_siswa'] ?></td>
                                        <td><?= $value['kelas'] ?></td>
                                        <td><?= "Rp " . number_format($value['jumlah'], 0, ',', '.') ?></td>
                                        <td><?= $value['keterangan'] ?></td>
                                        <td>
                                            <a href="<?= base_url('admin/invoice_pengeluaran/' . $value['id_pengeluaran']) ?>" class="btn btn-primary btn-sm"><span class="mdi mdi-note-text"></span> Invoice</a>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($value['keterangan'] == "PENARIKAN SALDO") : ?>
                                                <a href="<?= base_url('admin/delete_pengeluaran/' . $value['id_pengeluaran']) ?>" id="hapusPengeluaran" class="btn btn-danger btn-sm" onclick="alertDelete(event)"><span class="mdi mdi-delete"></span> Hapus</a>
                                            <?php endif; ?>
                                            <?php if ($value['keterangan'] != "PENARIKAN SALDO") : ?>
                                                -
                                            <?php endif; ?>
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
    let hapusPengeluaran = document.getElementById('hapusPengeluaran');
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
                document.location.href = hapusPengeluaran.href;
            }
        })
    }
</script>
<?= $this->endSection(); ?>