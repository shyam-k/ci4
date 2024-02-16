<?php

// app/Controllers/StudentController.php

namespace App\Controllers;

use App\Models\StudentModel;
use CodeIgniter\Controller;

class StudentController extends BaseController
{

 
    public function index()
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
        return view('student_form', ['student' => $student]);
    }

    public function saveMarks()
    {
      
        if (!auth()->loggedIn()) {
            return redirect()->to(config('Auth')->loginRedirect());
        }
        


        
        // Validate the form data
        $validationRules = [
            'mark_10th' => 'required|numeric',
            'mark_12th' => 'required|numeric',
          //  'certificate_10th'   => 'uploaded[certificate]|max_size[certificate,1024]|ext_in[certificate,pdf,docx,jpg,png]',
          //  'certificate_12th'   => 'uploaded[certificate]|max_size[certificate,1024]|ext_in[certificate,pdf,docx,jpg,png]',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->to('/dashboard')->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = auth()->getUser()->id; // Replace this with the actual way to get the user ID

        // Example: Get form data
        $data = [
            'user_id'       => $userId,
            'mark_10th' => $this->request->getPost('mark_10th'),
            'mark_12th' => $this->request->getPost('mark_12th'),
        ]; 

        // Example: Upload certificate
        $file = $this->request->getFile('certificate_10th');
        if ($file->isValid() && !$file->hasMoved())
        {
            $file->move('./uploads');
            $data['certificate_10th'] = $file->getName();
        }

        $file = $this->request->getFile('certificate_12th');
        if ($file->isValid() && !$file->hasMoved())
        {
            $file->move('./uploads');
            $data['certificate_12th'] = $file->getName();
        }



        // Save data to the database using the model
        $studentModel = new StudentModel();
        $studentModel->upsert($data);
  


        return redirect()->to('/dashboard')->with('success', 'Marks and certificate uploaded successfully!');
    }

    public function eligibility()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to(config('Auth')->loginRedirect());
        }
        $userModel = new StudentModel();
        $student = $userModel->getStudentByUserId(auth()->getUser()->id);
        $courses = [
            'Course A' => 60,
            'Course B' => 65,
            'Course C' => 70,
        ];    
        $eligibilityMessages = [];
    
 
        foreach ($courses as $course => $threshold) {
            if ($student['mark_10th'] >= $threshold && $student['mark_12th'] >= $threshold) {
                $eligibilityMessages[$course] = "Congratulations! You are eligible for $course.";
            } else {
                $eligibilityMessages[$course] = "Sorry, you are not eligible for $course. Minimum eligibility criteria not met.";
            }
        } 
        return view('eligibility', ['eligibilityMessages' => $eligibilityMessages]);
    }
}
