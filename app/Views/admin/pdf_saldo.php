<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<h3>
    <center>Laporan Data Saldo</center>
</h3>
<table border="1" cellpadding="5" cellspacing="0" align="center">
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

<style>
    * {
        font-family: 'Arial', Helvetica, sans-serif;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        font-family: Arial, sans-serif;
        font-size: 14px;
        margin-bottom: 20px;
    }

    th,
    td {
        border: 1px solid#ccc;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }
</style>

<body>

</body>

</html>