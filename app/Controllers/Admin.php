<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use App\Models\Kelas;
use App\Models\Saldo;
use App\Models\Siswa;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Admin extends BaseController
{
    public function index()
    {
        $pemasukan = new Pemasukan();
        $pengeluaran = new Pengeluaran();
        $saldo = new Saldo();
        $data = [
            'title' => 'Admin | Dashboard',
            'pemasukan' => $pemasukan->findAll(),
            'pengeluaran' => $pengeluaran->findAll(),
            'saldo' => $saldo->findAll(),
            'pemasukan_past' => $pemasukan->where('MONTH(tgl_pemasukan)', date('m') - 1)->findAll(),
            'pemasukan_this_month' => $pemasukan->where('MONTH(tgl_pemasukan)', date('m'))->findAll(),
            'pemasukan_this_month_percentage' => count($pemasukan->where('MONTH(tgl_pemasukan)', date('m'))->findAll()) / count($pemasukan->findAll()) * 100,
            'check_increase_decrease' => count($pemasukan->where('MONTH(tgl_pemasukan)', date('m'))->findAll()) > count($pemasukan->where('MONTH(tgl_pemasukan)', date('m') - 1)->findAll()) ? 'Increased' : 'Decreased',
            'pengeluaran_past' => $pengeluaran->where('MONTH(tgl_pengeluaran)', date('m') - 1)->findAll(),
            'pengeluaran_this_month' => $pengeluaran->where('MONTH(tgl_pengeluaran)', date('m'))->findAll(),
            'pengeluaran_this_month_percentage' => count($pengeluaran->where('MONTH(tgl_pengeluaran)', date('m'))->findAll()) / count($pengeluaran->findAll()) * 100,
            'check_increase_decrease_pengeluaran' => count($pengeluaran->where('MONTH(tgl_pengeluaran)', date('m'))->findAll()) > count($pengeluaran->where('MONTH(tgl_pengeluaran)', date('m') - 1)->findAll()) ? 'Increased' : 'Decreased',
        ];
        return view('admin/index', $data);
    }
    public function data_saldo()
    {
        $saldo = new Saldo();
        $siswa = new Siswa();
        $kelas = new Kelas();
        $data = [];
        foreach ($saldo->findAll() as $key => $value) {
            $data[$key]['id_saldo'] = $value['id_saldo'];
            $data[$key]['nis'] = $value['nis'];
            $data[$key]['saldo'] = $value['saldo'];
            $data[$key]['nama_siswa'] = $siswa->where('nis', $value['nis'])->first()['nama_siswa'];
            $data[$key]['kelas'] = $kelas->where('id_kelas', $siswa->where('nis', $value['nis'])->first()['kelas'])->first()['nama_kelas'];
        }
        $data = [
            'title' => 'Admin | Data Saldo',
            'data' => $data
        ];
        return view('admin/data_saldo', $data);
    }
    public function pdf_saldo()
    {
        $saldo = new Saldo();
        $siswa = new Siswa();
        $kelas = new Kelas();
        $data = [];
        foreach ($saldo->findAll() as $key => $value) {
            $data[$key]['id_saldo'] = $value['id_saldo'];
            $data[$key]['nis'] = $value['nis'];
            $data[$key]['saldo'] = $value['saldo'];
            $data[$key]['nama_siswa'] = $siswa->where('nis', $value['nis'])->first()['nama_siswa'];
            $data[$key]['kelas'] = $kelas->where('id_kelas', $siswa->where('nis', $value['nis'])->first()['kelas'])->first()['nama_kelas'];
        }
        $filename = 'Data Saldo - ' . date('d-m-Y') . '.pdf';
        $data = [
            'data' => $data
        ];
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('admin/pdf_saldo', $data));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($filename, ['Attachment' => false]);
    }
    public function excel_saldo()
    {
        $saldo = new Saldo();
        $siswa = new Siswa();
        $kelas = new Kelas();
        $data = [];
        foreach ($saldo->findAll() as $key => $value) {
            $data[$key]['id_saldo'] = $value['id_saldo'];
            $data[$key]['nis'] = $value['nis'];
            $data[$key]['saldo'] = $value['saldo'];
            $data[$key]['nama_siswa'] = $siswa->where('nis', $value['nis'])->first()['nama_siswa'];
            $data[$key]['kelas'] = $kelas->where('id_kelas', $siswa->where('nis', $value['nis'])->first()['kelas'])->first()['nama_kelas'];
        }
        $data = [
            'data' => $data
        ];
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'NIS')
            ->setCellValue('B1', 'Nama Siswa')
            ->setCellValue('C1', 'Kelas')
            ->setCellValue('D1', 'Saldo');
        $column = 2;
        foreach ($data['data'] as $key => $value) {
            $sheet->setCellValue('A' . $column, $value['nis']);
            $sheet->setCellValue('B' . $column, $value['nama_siswa']);
            $sheet->setCellValue('C' . $column, $value['kelas']);
            $sheet->setCellValue('D' . $column, $value['saldo']);
            $column++;
        }
        $writer = new Xlsx($spreadsheet);
        $filename = 'Data Saldo - ' . date('d-m-Y');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    public function input_pemasukan()
    {
        $siswa = new Siswa();
        $kelas = new Kelas();
        $data = [
            'title' => 'Admin | Tambah Saldo',
            'siswa' => $siswa->findAll(),
            'kelas' => $kelas->findAll(),
        ];
        return view('admin/input_pemasukan', $data);
    }
    public function save_pemasukan()
    {
        $saldo = new Saldo();
        $pemasukan = new Pemasukan();
        $data = [
            'tgl_pemasukan' => $this->request->getPost('tgl_pemasukan'),
            'jam' => $this->request->getPost('jam'),
            'nis' => $this->request->getPost('nis'),
            'nama_siswa' => $this->request->getPost('nama_siswa'),
            'kelas' => $this->request->getPost('kelas'),
            'jumlah' => $this->request->getPost('jumlah'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];
        $data['jumlah'] = str_replace('Rp ', '', $data['jumlah']);
        $data['jumlah'] = str_replace('.', '', $data['jumlah']);
        $data['jumlah'] = str_replace(',', '.', $data['jumlah']);
        $data['jumlah'] = (int) $data['jumlah'];
        // dd($data);
        $pemasukan->insert($data);
        $data_saldo = $saldo->where('nis', $data['nis'])->first();
        if ($saldo->where('nis', $data['nis'])->first() == null) {
            $data_saldo = [
                'nis' => $data['nis'],
                'saldo' => $data['jumlah']
            ];
            $saldo->insert($data_saldo);
        } else {
            $data_saldo['saldo'] += $data['jumlah'];
            $saldo->update($data_saldo['id_saldo'], $data_saldo);
        }
        session()->setFlashdata('message', 'Saldo berhasil ditambahkan');
        return redirect()->to(base_url('admin/data_saldo'));
    }
    public function data_pemasukan()
    {
        $pemasukan = new Pemasukan();
        $data = [
            'title' => 'Admin | Data Pemasukan',
            'data' => $pemasukan->findAll()
        ];
        return view('admin/data_pemasukan', $data);
    }
    public function invoice_pemasukan()
    {
        $pemasukan = new Pemasukan();
        $filename = 'Invoice Pemasukan - ' . $pemasukan->where('id_pemasukan', $this->request->uri->getSegment(3))->first()['nama_siswa'] . '.pdf';
        $data = [
            'title' => 'Admin | Invoice Pemasukan',
            'data' => $pemasukan->where('id_pemasukan', $this->request->uri->getSegment(3))->first()
        ];
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('admin/invoice_pemasukan', $data));
        $dompdf->setPaper('A5', 'portrait');
        $dompdf->render();
        $dompdf->stream($filename, ['Attachment' => false]);
    }
    public function pdf_pemasukan()
    {
        $pemasukan = new Pemasukan();
        $filename = 'Data Pemasukan - ' . date('d-m-Y') . '.pdf';
        $data = [
            'title' => 'Admin | Data Pemasukan',
            'data' => $pemasukan->findAll()
        ];
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('admin/pdf_pemasukan', $data));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream($filename, ['Attachment' => false]);
    }
    public function delete_pemasukan($id_pemasukan)
    {
        $pemasukan = new Pemasukan();
        $saldo = new Saldo();
        $data_saldo = $saldo->where('nis', $pemasukan->where('id_pemasukan', $id_pemasukan)->first()['nis'])->first();
        $data_saldo['saldo'] -= $pemasukan->where('id_pemasukan', $id_pemasukan)->first()['jumlah'];
        $saldo->update($data_saldo['id_saldo'], $data_saldo);
        $pemasukan->where('id_pemasukan', $id_pemasukan)->delete();
        session()->setFlashdata('message', 'Data pemasukan berhasil dihapus');
        return redirect()->to(base_url('admin/data_pemasukan'));
    }
    public function excel_pemasukan()
    {
        $pemasukan = new Pemasukan();
        $filename = 'Data Pemasukan - ' . date('d-m-Y');
        $data = [
            'data' => $pemasukan->findAll()
        ];
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Tanggal Pemasukan')
            ->setCellValue('B1', 'Jam')
            ->setCellValue('C1', 'NIS')
            ->setCellValue('D1', 'Nama Siswa')
            ->setCellValue('E1', 'Kelas')
            ->setCellValue('F1', 'Jumlah')
            ->setCellValue('G1', 'Keterangan');
        $column = 2;
        foreach ($data['data'] as $key => $value) {
            $bulan = [
                '01' => 'Januari',
                '02' => 'Februari',
                '03' => 'Maret',
                '04' => 'April',
                '05' => 'Mei',
                '06' => 'Juni',
                '07' => 'Juli',
                '08' => 'Agustus',
                '09' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Desember',
            ];
            $value['tgl_pemasukan'] = date_format(date_create($value['tgl_pemasukan']), "d") . " " . $bulan[date_format(date_create($value['tgl_pemasukan']), "m")] . " " . date_format(date_create($value['tgl_pemasukan']), "Y");
            $sheet->setCellValue('A' . $column, $value['tgl_pemasukan']);
            $sheet->setCellValue('B' . $column, $value['jam']);
            $sheet->setCellValue('C' . $column, $value['nis']);
            $sheet->setCellValue('D' . $column, $value['nama_siswa']);
            $sheet->setCellValue('E' . $column, $value['kelas']);
            $sheet->setCellValue('F' . $column, $value['jumlah']);
            $sheet->setCellValue('G' . $column, $value['keterangan']);
            $column++;
        }
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    public function data_pengeluaran()
    {
        $pengeluaran = new Pengeluaran();
        $data = [
            'title' => 'Admin | Data Pengeluaran',
            'data' => $pengeluaran->findAll()
        ];
        return view('admin/data_pengeluaran', $data);
    }
    public function invoice_pengeluaran()
    {
        $pengeluaran = new Pengeluaran();
        $filename = 'Invoice Pengeluaran - ' . $pengeluaran->where('id_pengeluaran', $this->request->uri->getSegment(3))->first()['nama_siswa'] . '.pdf';
        $data = [
            'title' => 'Admin | Invoice Pengeluaran',
            'data' => $pengeluaran->where('id_pengeluaran', $this->request->uri->getSegment(3))->first()
        ];
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('admin/invoice_pengeluaran', $data));
        $dompdf->setPaper('A5', 'portrait');
        $dompdf->render();
        $dompdf->stream($filename, ['Attachment' => false]);
    }
    public function pdf_pengeluaran()
    {
        $pengeluaran = new Pengeluaran();
        $filename = 'Data Pengeluaran - ' . date('d-m-Y') . '.pdf';
        $data = [
            'title' => 'Admin | Data Pengeluaran',
            'data' => $pengeluaran->findAll()
        ];
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('admin/pdf_pengeluaran', $data));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream($filename, ['Attachment' => false]);
    }
    public function excel_pengeluaran()
    {
        $pengeluaran = new Pengeluaran();
        $filename = 'Data Pengeluaran - ' . date('d-m-Y');
        $data = [
            'data' => $pengeluaran->findAll()
        ];
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Tanggal Pengeluaran')
            ->setCellValue('B1', 'Jam')
            ->setCellValue('C1', 'NIS')
            ->setCellValue('D1', 'Nama Siswa')
            ->setCellValue('E1', 'Kelas')
            ->setCellValue('F1', 'Jumlah')
            ->setCellValue('G1', 'Keterangan');
        $column = 2;
        foreach ($data['data'] as $key => $value) {
            $bulan = [
                '01' => 'Januari',
                '02' => 'Februari',
                '03' => 'Maret',
                '04' => 'April',
                '05' => 'Mei',
                '06' => 'Juni',
                '07' => 'Juli',
                '08' => 'Agustus',
                '09' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Desember',
            ];
            $value['tgl_pengeluaran'] = date_format(date_create($value['tgl_pengeluaran']), "d") . " " . $bulan[date_format(date_create($value['tgl_pengeluaran']), "m")] . " " . date_format(date_create($value['tgl_pengeluaran']), "Y");
            $sheet->setCellValue('A' . $column, $value['tgl_pengeluaran']);
            $sheet->setCellValue('B' . $column, $value['jam']);
            $sheet->setCellValue('C' . $column, $value['nis']);
            $sheet->setCellValue('D' . $column, $value['nama_siswa']);
            $sheet->setCellValue('E' . $column, $value['kelas']);
            $sheet->setCellValue('F' . $column, $value['jumlah']);
            $sheet->setCellValue('G' . $column, $value['keterangan']);
            $column++;
        }
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    public function delete_pengeluaran($id_pengeluaran)
    {
        $pengeluaran = new Pengeluaran();
        $saldo = new Saldo();
        $data_saldo = $saldo->where('nis', $pengeluaran->where('id_pengeluaran', $id_pengeluaran)->first()['nis'])->first();
        $data_saldo['saldo'] += $pengeluaran->where('id_pengeluaran', $id_pengeluaran)->first()['jumlah'];
        $saldo->update($data_saldo['id_saldo'], $data_saldo);
        $pengeluaran->where('id_pengeluaran', $id_pengeluaran)->delete();
        session()->setFlashdata('message', 'Data pengeluaran berhasil dihapus');
        return redirect()->to(base_url('admin/data_pengeluaran'));
    }
    public function tarik_saldo()
    {
        $siswa = new Siswa();
        $kelas = new Kelas();
        $saldo = new Saldo();
        $data = [
            'title' => 'Admin | Tarik Saldo',
            'siswa' => $siswa->findAll(),
            'kelas' => $kelas->findAll(),
            'saldo' => $saldo->findAll(),
        ];
        return view('admin/tarik_saldo', $data);
    }
    public function save_penarikan()
    {
        $saldo = new Saldo();
        $pengeluaran = new Pengeluaran();
        $data = [
            'tgl_pengeluaran' => $this->request->getPost('tgl_pengeluaran'),
            'jam' => $this->request->getPost('jam'),
            'nis' => $this->request->getPost('nis'),
            'nama_siswa' => $this->request->getPost('nama_siswa'),
            'kelas' => $this->request->getPost('kelas'),
            'jumlah' => $this->request->getPost('jumlah'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];
        $data['jumlah'] = str_replace('Rp ', '', $data['jumlah']);
        $data['jumlah'] = str_replace('.', '', $data['jumlah']);
        $data['jumlah'] = str_replace(',', '.', $data['jumlah']);
        $data['jumlah'] = (int) $data['jumlah'];
        // dd($data);
        $pengeluaran->insert($data);
        $data_saldo = $saldo->where('nis', $data['nis'])->first();
        if ($saldo->where('nis', $data['nis'])->first() == null) {
            $data_saldo = [
                'nis' => $data['nis'],
                'saldo' => $data['jumlah']
            ];
            $saldo->insert($data_saldo);
        } else {
            $data_saldo['saldo'] -= $data['jumlah'];
            $saldo->update($data_saldo['id_saldo'], $data_saldo);
        }
        session()->setFlashdata('message', 'Saldo berhasil ditarik');
        return redirect()->to(base_url('admin/data_pengeluaran'));
    }
    public function kehilangan_kartu()
    {
        $siswa = new Siswa();
        $kelas = new Kelas();
        $data = [
            'title' => 'Admin | Kehilangan Kartu',
            'siswa' => $siswa->findAll(),
            'kelas' => $kelas->findAll(),
        ];
        return view('admin/kehilangan_kartu', $data);
    }
    public function form_kehilangan()
    {
        $file = '../public/doc/form_kehilangan.pdf';
        return $this->response->download($file, null);
    }
    public function update_kartu()
    {
        $rfid = $this->request->getGet('rfid');
        $siswa = new Siswa();
        $kelas = new Kelas();
        $id_kelas = $siswa->where('rfid', $rfid)->first()['kelas'];
        $nama_kelas = $kelas->where('id_kelas', $id_kelas)->first()['nama_kelas'];
        $data = [
            'title' => 'Admin | Update Kartu ' . $rfid,
            'rfid' => $rfid,
            'nis' => $siswa->where('rfid', $rfid)->first()['nis'],
            'nama_siswa' => $siswa->where('rfid', $rfid)->first()['nama_siswa'],
            'kelas' => $nama_kelas,
        ];
        return view('admin/update_kartu', $data);
    }
    public function save_kartu()
    {
    }
    public function login()
    {
        return view('admin/login');
    }
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('admin'));
    }
}
