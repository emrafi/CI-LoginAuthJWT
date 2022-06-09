<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

class Register extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    use ResponseTrait;
    public function index()
    {
        //get tanggal register
        date_default_timezone_set('Asia/Jakarta');
        $register_date = date("Y-m-d");

        //rules input form register
        helper(['form']);
        $rules = [
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            // 'confpassword' => 'matches[password]'
        ];
        if (!$this->validate($rules)) return $this->fail($this->validator->getErrors());

        //generate simple random code
        $set = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = substr(str_shuffle($set), 0, 12);

        $nama = $this->request->getVar('nama');
        $email = $this->request->getVar('email');

        //data register
        $data = [
            'nama' => $nama,
            'email' => $email,
            'verify_code' => $code,
            'password' => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
            'tgl_register' => $register_date
        ];

        //simpan data
        $model = new UserModel();
        $model->save($data);
        $id = $model->getInsertID();

        // $register = $model->save($data);
        // $this->respondCreated($register);
        helper('encoding');
        $encode_code = base64url_encode($code);
        $url = base_url() . '/activate/' . $id . '/' . $encode_code;
        $link =  '<a href="' . $url . '">Activate My Account</a>';

        $message = "
                    <html>
                    <head>
                        <title>Verification Code</title>
                    </head>
                    <body>
                        <h2>Thank you for registering.</h2>
                        <p>Please click the link below to activate your account.</p>
                        <h4>$link</h4>
                    </body>
                    </html>
                    ";
        helper('mail');
        $mail = mailing($email, $message);
        if ($mail) {
            session()->setFlashdata('item', array(
                'message' => 'Lakukan verifikasi, link aktivasi telah dikirim ke email anda',
                'class'   => 'success'
            ));
        } else {
            session()->setFlashdata('item', array(
                'message' => 'Ada masalah, gagal mengirim link aktivasi',
                'class'   => 'warning'
            ));
        }
        return redirect()->to(base_url() . '/registerForm');
    }

    public function activate()
    {
        $uri = service('uri');
        $id =  $uri->getSegment(2);
        helper('encoding');
        $decode_code = base64url_decode($uri->getSegment(3));

        //fetch user details
        $model = new UserModel();
        $user = $model->getUser($id);

        //if token matches
        if ($user['verify_code'] == $decode_code) {
            //update user active status
            $data = [
                'is_active' => 'active'
            ];
            $query = $model->activate($data, $id);

            if ($query) {
                session()->setFlashdata('item', array(
                    'message' => 'Aktivasi berhasil, silahkan login untuk melanjutkan',
                    'class'   => 'success'
                ));
            } else {
                session()->setFlashdata('item', array(
                    'message' => 'Terjadi kesalahan, aktivasi gagal',
                    'class'   => 'warning'
                ));
            }
        } else {
            session()->setFlashdata('item', array(
                'message' => 'Aktivasi gagal, token tidak valid',
                'class'   => 'warning'
            ));
        }
        return redirect()->to(base_url() . '/loginForm');
    }

    public function registerForm()
    {
        $data = [
            'title' => 'Register | Harian Jogja',
        ];
        return view('register', $data);
    }
}
