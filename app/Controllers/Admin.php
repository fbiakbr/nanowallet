<?php

namespace App\Controllers;

use App\Models\Kelas;
use App\Models\Saldo;
use App\Models\Siswa;
use App\Models\Pemasukan;
use App\Controllers\BaseController;

class Admin extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Admin | Dashboard'
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
}
