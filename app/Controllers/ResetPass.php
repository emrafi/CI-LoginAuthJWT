<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class ResetPass extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        helper(['form']);
        $rules = [
            'email' => 'required|valid_email',
        ];
        if (!$this->validate($rules)) return $this->fail($this->validator->getErrors());

        $model = new UserModel();
        $email = $this->request->getPost('email');
        $userInfo = $model->getEmail($email);

        if (!$userInfo) {
            session()->setFlashdata('item', array(
                'message' => 'Mohon maaf, email anda tidak terdaftar',
                'class'   => 'warning'
            ));
            return redirect()->to(base_url() . '/forgotPass');
        }

        $token = $this->insertToken($userInfo['id']);
        helper('encoding');
        $encode_code = base64url_encode(implode($token));
        $url = base_url() . '/resetRequest/' . $encode_code;
        $link =  '<a href="' . $url . '">Reset Password</a>';

        $message = "
                    <html>
                    <head>
                        <title>Permintaan Reset Password</title>
                    </head>
                    <body>
                        <h2>Password anda akan diubah.</h2>
                        <p>Klik link di bawah ini untuk menuju halaman perubahan password</p>
                        <h4>$link</h4>
                    </body>
                    </html>
                    ";
        helper('mail');
        $mail = mailing($email, $message);
        if ($mail) {
            session()->setFlashdata('item', array(
                'message' => 'Permintaan perubahan password, link telah dikirim ke email anda',
                'class'   => 'success'
            ));
        } else {
            session()->setFlashdata('item', array(
                'message' => 'Ada masalah, gagal mengirim link ganti password',
                'class'   => 'warning'
            ));
        }
        return redirect()->to(base_url() . '/forgotPass');
    }

    public function forgotPassForm()
    {
        $data = [
            'title' => 'Forgot Password | Harian Jogja',
        ];
        return view('forgotPass', $data);
    }

    public function resetPassForm()
    {
        $data = [
            'title' => 'Reset Password | Harian Jogja',
        ];
        return view('resetPass', $data);
    }

    public function insertToken($id)
    {
        $code = substr(sha1(rand()), 0, 30);
        $time = time();
        $data = array(
            'token' => $code . $id . $time
        );
        $model = new UserModel();
        $model->update($id, $data);
        return $data;
    }

    public function resetProcess()
    {
        $uri = service('uri');
        helper('encoding');
        $decode_code = base64url_decode($uri->getSegment(2));
        $model = new UserModel();
        $user = $model->where('token', $decode_code)->find();

        if (!$user) {
            session()->setFlashdata('item', array(
                'message' => 'Token tidak valid, gagal mengganti password',
                'class'   => 'warning'
            ));
            return redirect()->to(base_url() . '/forgotPass');
        } else {
            $token_id = substr($user[0]['token'], 30, 1);
            $token_created_time = substr($user[0]['token'], 31);
            $expired = $token_created_time + 3000;
            $accessTime = time();

            if ($accessTime >= $expired) {
                session()->setFlashdata('item', array(
                    'message' => 'Token expired, coba ulang permintaan',
                    'class'   => 'warning'
                ));
                return redirect()->to(base_url() . '/forgotPass');
            } else {
                session()->setFlashdata('item', array(
                    'message' => 'Permintaan berhasil, silakan ganti password anda',
                    'class'   => 'success',
                    'id'      => $token_id
                ));
                return redirect()->to(base_url() . '/resetAllow');
            }
        }
    }

    public function updatePass()
    {
        $id = $this->request->getPost('id');
        $data = array('password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT));

        $model = new UserModel();
        $update = $model->update($id, $data);

        if (!$update) {
            session()->setFlashdata('item', array(
                'message' => 'Terjadi masalah, password gagal dirubah',
                'class'   => 'warning',
                'id'      => $id
            ));
            return redirect()->to(base_url() . '/resetAllow');
        } else {
            session()->setFlashdata('item', array(
                'message' => 'Password telah diganti, silakan login dengan password yang baru',
                'class'   => 'success',
            ));
            return redirect()->to(base_url() . '/loginForm');
        }
    }
}
