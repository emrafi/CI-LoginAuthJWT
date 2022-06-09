<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

class Login extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    use ResponseTrait;
    public function index()
    {
        helper(['form']);
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]',
        ];
        if (!$this->validate($rules)) return $this->fail($this->validator->getErrors());

        $model = new UserModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $user = $model->getEmail($email);

        //cek email
        if (!$user) {
            session()->setFlashdata('item', array(
                'message' => 'Maaf, email tidak ditemukan',
                'class'   => 'warning'
            ));
            return redirect()->to(base_url() . '/loginForm');
            // return $this->failNotFound('Maaf, email tidak ditemukan');
            //cek password
        } elseif (!password_verify($password, $user['password'])) {
            session()->setFlashdata('item', array(
                'message' => 'Maaf, password yang anda masukkan salah',
                'class'   => 'warning'
            ));
            return redirect()->to(base_url() . '/loginForm');
            // return $this->fail('Maaf, password yang anda masukkan salah');
            // cek status
        } elseif ($user['is_active'] == "disable") {
            session()->setFlashdata('item', array(
                'message' => 'Maaf, akun anda belum terverifikasi',
                'class'   => 'warning'
            ));
            return redirect()->to(base_url() . '/loginForm');
            // return $this->fail('Maaf, akun anda belum terverifikasi');
        } else {
            //jwt
            helper('jwt');
            $payload = [
                'msg' => 'Otentikasi berhasil',
                'data' => $user,
                'access_token' => createJWT($email)
            ];
            return $this->respond($payload);



            // $client = \Config\Services::curlrequest();
            $token = $payload['access_token'];
            // $headers = [
            //     'Authorization' => 'Bearer' . $token
            // ];

            // $data = [
            //     'email' => $email,
            //     'password' => $password
            // ];
            // $url = base_url() . '/me';
            // $response = $client->request('POST', $url, ['form_params' => $data, 'headers' => $headers]);
            // echo $response;
            /* API URL */
            $url = base_url() . '/me';

            /* Init cURL resource */
            $ch = curl_init($url);

            /* Array Parameter Data */
            $data = [
                'email' => $email,
                'password' => $password
            ];

            /* pass encoded JSON string to the POST fields */
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            /* set the content type json */
            $headers = [];
            $headers[] = 'Content-Type:application/json';
            $token = "your_token";
            $headers[] = "Authorization: Bearer " . $token;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            /* set return type json */
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            /* execute request */
            $result = curl_exec($ch);

            /* close cURL resource */
            curl_close($ch);
        }
    }

    public function loginForm()
    {
        $data = [
            'title' => 'Login | Harian Jogja',
        ];
        return view('login', $data);
    }
}
