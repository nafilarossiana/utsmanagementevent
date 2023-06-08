<?php

namespace App\Controllers;

use App\Models\registerpesertaModel;
use CodeIgniter\HTTP\Request;

class Pages extends BaseController
{
    protected $registerModel;
    public function home()
    {
        $data=[
            'title' => 'Home | WEB Event Organizer'
            
            
        ];
        echo view ('layout/header',$data);
        echo view ('pages/home');
        echo view ('layout/footer');
    }


    public function event()
    {
    
        $data=[
            'title' => 'Event | WEB Event Organizer', 
        ];

        echo view ('layout/header',$data);
        echo view ('pages/event');
        echo view ('layout/footer');

    }


    
    public function register()
    {
        $registerpeserta= $this->registerModel->findAll();
        $data=[
            'title' => 'Register',
            'registerpeserta' => $registerpeserta
        ];
        
        echo view ('layout/header',$data);
        echo view ('pages/register');
        echo view ('layout/footer');
    }

    public function addregister()
    {
        //session();
        $registerpeserta= $this->registerModel->findAll();
        $data=[
            'title' => 'Add Registration',
            'registerpeserta' => $registerpeserta,
            'validation' => \Config\Services::validation()
        ];
        echo view ('layout/header');
        echo view ('pages/addregister',$data);
        echo view ('layout/footer');
    }
    public function __construct()
    {
        $this->registerModel = new registerpesertaModel();
    }
    public function save(){
        if(!$this->validate([
            'namapeserta' => [
                'required' => 'required',
                'errors' =>[
                    'required' => '{field} harus diisi',
                ]
                ],
                'fotoktm' =>[
                'rules'=> 'uploaded[fotoktm]|is_image[fotoktm]|mime_in[fotoktm,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'pilih gambar Foto KTM terlebihh dahulu',
                    'is_image' => 'Yang anda pilih bukan gambar',
                    'mime_in' => 'Yang Anda pilih bukan gambar'
                ]
                ]
        ])){
            // $validation = \Config\Services::validation();
            return redirect()->to('/pages/addregister')->withInput();   
            // return redirect()->to('/pages/addregister')->withInput()->with('validation', $validation);
        }
        $filektm = $this->request->getFile('fotoktm');
        $namaktm = $filektm -> getRandomName();
        //pindah ke folder imgg
        $filektm->move('img', $namaktm);

        $this->registerModel->save([
            'idregister' => $this->request->getVar('idregister'),
            'namapeserta' => $this->request->getVar('namapeserta'),
            'nim' => $this->request->getVar('nim'),
            'email' => $this->request->getVar('email'),
            'telp' => $this->request->getVar('telp'),
            'institusi' => $this->request->getVar('institusi'),
            'alamat' => $this->request->getVar('alamat'),
            'jeniskegiatan' => $this->request->getVar('jeniskegiatan'),
            'namakegiatan' => $this->request->getVar('namakegiatan'),
            'fotoktm' => $namaktm
            ]);
            session()->setFlashdata('pesan','Data berhasil ditambahkan');
            return redirect()->to('/pages/register');
        }
        public function delete($idregister){
        
            $this->registerModel->delete($idregister);
            session()->setFlashdata('pesan','Data berhasil dihapus');
            return redirect()->to('/pages/register');
        }
        public function editregis($idregister=null){
            $registerModel =$this->registerModel->find($idregister);
                    $data=[
                        'title' => 'Edit Registration',
                        'validation' =>  \Config\Services::validation(), 
                        $registerModel = $this->registerModel->find($idregister)
                        
                    ];
                    if (is_array($registerModel)) {
                        $data['registerModel'] = $registerModel;
                    }
                    echo view ('layout/header');
                    echo view ('pages/editregis',$data);
                    echo view ('layout/footer');
                    //return redirect()->to('/pages/editregis');
                
            }
            public function update($idregister)
            {
                if (!$this->validate([
                    'namapeserta' => [
                        'required' => 'required',
                        'errors' => [
                            'required' => '{field} harus diisi',
                        ]
                    ],
                    'fotoktm' => [
                        'rules' => 'uploaded[fotoktm]|is_image[fotoktm]|mime_in[fotoktm,image/jpg,image/jpeg,image/png]',
                        'errors' => [
                            'uploaded' => 'pilih gambar Foto KTM terlebihh dahulu',
                            'is_image' => 'Yang anda pilih bukan gambar',
                            'mime_in' => 'Yang Anda pilih bukan gambar'
                        ]
                    ]
                ])) {
                    // $validation = \Config\Services::validation();
                    return redirect()->to('/pages/editregis/' . $idregister)->withInput();
                    // return redirect()->to('/pages/addregister')->withInput()->with('validation', $validation);
                }
                $filektm = $this->request->getFile('fotoktm');
                $namaktm = $filektm->getRandomName();
                //pindah ke folder imgg
                $filektm->move('img', $namaktm);
        
                //ambil nama file 
        
                $this->registerModel->save([
                    'idregister' => $idregister,
                    'namapeserta' => $this->request->getVar('namapeserta'),
                    'nim' => $this->request->getVar('nim'),
                    'email' => $this->request->getVar('email'),
                    'telp' => $this->request->getVar('telp'),
                    'institusi' => $this->request->getVar('institusi'),
                    'alamat' => $this->request->getVar('alamat'),
                    'jeniskegiatan' => $this->request->getVar('jeniskegiatan'),
                    'namakegiatan' => $this->request->getVar('namakegiatan'),
                    'fotoktm' => $namaktm
                ]);
                session()->setFlashdata('pesan', 'Data berhasil diubah');
                return redirect()->to('/pages/register');
        
            }
        
    }
    