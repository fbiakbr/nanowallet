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
<?= $this->endSection(); ?>