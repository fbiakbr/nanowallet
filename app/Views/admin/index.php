<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-home"></i>
            </span> Dashboard
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="<?= base_url('assets/images/dashboard/circle.svg') ?>" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Total Pemasukan <i class="mdi mdi-chart-line mdi-24px float-right"></i>
                    </h4>
                    <h3 class="mb-5 pt-5"><?= "Rp " . number_format(array_sum(array_column($pemasukan, 'jumlah')), 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="<?= base_url('assets/images/dashboard/circle.svg') ?>" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Pemasukan <br>Bulan Ini <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                    </h4>
                    <h3 class="mb-5 pt-4"><?= "Rp " . number_format(array_sum(array_column($pemasukan_this_month, 'jumlah')), 0, ',', '.') ?></h3>
                    <h6 class="card-text"><?= $check_increase_decrease ?> <?= number_format($pemasukan_this_month_percentage) ?>% since last month</h6>
                </div>
            </div>
        </div>
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <img src="<?= base_url('assets/images/dashboard/circle.svg') ?>" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Total Pengeluaran <i class="mdi mdi-vote-outline mdi-24px float-right"></i>
                    </h4>
                    <h3 class="mb-5 pt-5"><?= "Rp " . number_format(array_sum(array_column($pengeluaran, 'jumlah')), 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-primary card-img-holder text-white">
                <div class="card-body">
                    <img src="<?= base_url('assets/images/dashboard/circle.svg') ?>" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Pengeluaran <br>Bulan Ini <i class="mdi mdi-vector-arrange-above mdi-24px float-right"></i>
                    </h4>
                    <h3 class="mb-5 pt-4"><?= "Rp " . number_format(array_sum(array_column($pengeluaran_this_month, 'jumlah')), 0, ',', '.') ?></h3>
                    <h6 class="card-text"><?= $check_increase_decrease_pengeluaran ?> <?= number_format($pengeluaran_this_month_percentage) ?>% since last month</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="clearfix">
                        <h4 class="card-title float-left">Pemasukan</h4>
                    </div>
                    <canvas id="pemasukan" class="mt-4"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="clearfix">
                        <h4 class="card-title float-left">Total Pengeluaran</h4>
                    </div>
                    <canvas id="totalpengeluaran" class="mt-4"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/chart.umd.min.js') ?>"></script>
<script>
    let pemasukan = document.getElementById('pemasukan');
    let pemasukanChart = new Chart(pemasukan, {
        type: 'line',
        data: {
            labels: [
                <?php foreach ($pemasukan_this_month as $key => $value) : ?> '<?= $value['tgl_pemasukan'] ?>',
                <?php endforeach; ?>
            ],
            datasets: [{
                label: 'Pemasukan',
                data: [
                    <?php foreach ($pemasukan_this_month as $key => $value) : ?>
                        <?= $value['jumlah'] ?>,
                    <?php endforeach; ?>
                ],
                backgroundColor: [
                    'rgb(176, 121, 255)',
                ],
                borderColor: [
                    'rgb(176, 121, 255)',
                ],
                borderWidth: 1,
                fill: false,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value, index, values) {
                            return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    let totalpengeluaran = document.getElementById('totalpengeluaran');
    let totalpengeluaranChart = new Chart(totalpengeluaran, {
        type: 'doughnut',
        data: {
            labels: [
                <?php foreach ($pengeluaran_this_month as $key => $value) : ?> '<?= $value['keterangan'] ?>',
                <?php endforeach; ?>
            ],
            datasets: [{
                label: 'Total Pengeluaran',
                data: [
                    <?php foreach ($pengeluaran_this_month as $key => $value) : ?>
                        <?= $value['jumlah'] ?>,
                    <?php endforeach; ?>
                ],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 205, 86)',
                    'rgb(54, 162, 235)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)',
                    'rgb(255, 159, 64)',
                    'rgb(201, 203, 207)',
                    'rgb(255, 99, 132)',
                    'rgb(255, 205, 86)',
                    'rgb(54, 162, 235)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)',
                    'rgb(255, 159, 64)',
                    'rgb(201, 203, 207)',
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 205, 86)',
                    'rgb(54, 162, 235)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)',
                    'rgb(255, 159, 64)',
                    'rgb(201, 203, 207)',
                    'rgb(255, 99, 132)',
                    'rgb(255, 205, 86)',
                    'rgb(54, 162, 235)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)',
                    'rgb(255, 159, 64)',
                    'rgb(201, 203, 207)',
                ],
                borderWidth: 1,
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
<?= $this->endSection(); ?>