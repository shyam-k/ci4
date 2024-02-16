<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'mark_10th', 'mark_12th', 'certificate_10th','certificate_12th'];
    public function getStudentByUserId($userId)
    {
        return $this->where('user_id', $userId)->first();
    }
}
