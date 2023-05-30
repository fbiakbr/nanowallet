<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use App\Models\Kelas;
use App\Models\Saldo;
use App\Models\Siswa;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Controllers\BaseController;

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
    public function input_pemasukan()
    {
        $siswa = new Siswa();
        $kelas = new Kelas();
        $data = [
            'title' => 'Admin | Input Pemasukan',
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
