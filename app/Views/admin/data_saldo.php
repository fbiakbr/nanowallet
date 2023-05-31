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
                    <h4 class="card-title">Data Saldo</h4>
                    <a href="<?= base_url('admin/pdf_saldo') ?>" class="btn btn-primary btn-sm mb-3"><span class="mdi mdi-printer"></span> Export PDF</a>
                    <table id="table1" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            array_multisort(
                                array_column($data, 'kelas'),
                                SORT_ASC,
                                array_column($data, 'nama_siswa'),
                                SORT_ASC,
                                $data
                            );
                            foreach ($data as $key => $value) : ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= $value['nis'] ?></td>
                                    <td><?= $value['nama_siswa'] ?></td>
                                    <td><?= $value['kelas'] ?></td>
                                    <td><?= "Rp " . number_format($value['saldo'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>
<?= $this->endSection(); ?>