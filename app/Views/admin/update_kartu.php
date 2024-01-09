<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Update Kartu</h4>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <button onclick="tambah_data_kehilangan()" id="btnTambah" class="btn btn-sm mt-2 btn-dark"><i class="mdi mdi-book-plus"></i> Tambah Data Kehilangan</button>
                                <button onclick="update_kartu()" id="btnUpdate" class="btn btn-sm mt-2 btn-primary"><i class="mdi mdi-card-bulleted"></i> Update Kartu</button>
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
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <tr>
                                        <td><?= $nis ?></td>
                                        <td><?= $nama_siswa ?></td>
                                        <td><?= $kelas ?></td>
                                        <td><?= $rfid ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let btnUpdate = document.getElementById('btnUpdate');
    let btnTambah

    function update_kartu() {
        Swal.fire({
            title: 'Scan RFID',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showConfirmButton: false,
            preConfirm: (newRfid) => {
                let newRfidValue = newRfid;
                console.log(newRfidValue);
                if (!newRfidValue) {
                    Swal.showValidationMessage('RFID harus diisi')
                } else if (newRfidValue.length != 10) {
                    Swal.showValidationMessage('RFID harus 10 digit')
                } else if (newRfidValue.match(/[^0-9]/)) {
                    Swal.showValidationMessage('RFID harus angka')
                } else if (newRfidValue == '<?= $rfid ?>')
                    Swal.showValidationMessage('RFID tidak boleh sama dengan sebelumnya')
                else {
                    $.ajax({
                        url: '<?= base_url('admin/save_kartu') ?>',
                        type: 'POST',
                        data: {
                            rfid: newRfidValue,
                            nis: '<?= $nis ?>'
                        },
                        success: function(response) {
                            Swal.fire('Success!', 'Data has been updated.', 'success');
                        },
                        error: function(error) {
                            Swal.fire('Error!', 'There was an error updating the data.', 'error');
                        }
                    });
                }
            }
        })
    }

    function tambah_data_kehilangan() {
        Swal.fire({
            title: "Tambah Data Kehilangan?",
            showCancelButton: true,
            confirmButtonText: "Ya, Tambah",
            cancelButtonText: "Batal",
            preConfirm: (rfid) => {
                // show confirm button

            }
        })
    }
</script>
<?= $this->endSection(); ?>