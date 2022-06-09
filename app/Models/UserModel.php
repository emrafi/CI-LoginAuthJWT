<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class UserModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama', 'alamat', 'tgl_lahir', 'no_hp', 'email', 'password', 'tgl_register', 'verify_code', 'token', 'is_active'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getEmail($email)
    {
        $builder = $this->table('users');
        $data = $builder->where('email', $email)->first();
        return $data;
    }

    public function getUser($id)
    {
        $builder = $this->table('users');
        $data = $builder->where('id', $id)->first();
        return $data;
    }

    public function activate($data, $id)
    {
        $builder = $this->table('users');
        return $builder->update($id, $data);
    }

    public function updatePass($post)
    {
        $builder = $this->table('users');
        $builder->where('id', $post['id']);
        $builder->update('users', array(
            'password' => $post['password']
        ));
        return true;
    }
}
