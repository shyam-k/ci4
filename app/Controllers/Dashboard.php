<?php

namespace App\Controllers;

use App\Models\StudentModel;

class Dashboard extends BaseController
{
    public function index(): string
    {
        if (!auth()->loggedIn()) {
            return redirect()->to(config('Auth')->loginRedirect());
        }
        $userModel = new StudentModel();
        $student = $userModel->getStudentByUserId(auth()->getUser()->id);
        if(is_null($student)){
            $student = [
                'mark_10th' => '',
                'mark_12th' => '',
                'certificate_10th' => '',
                'certificate_12th' => '',
                
            ]; 
        }
        return view('dashboard', ['student' => $student]);
 
    }
}
