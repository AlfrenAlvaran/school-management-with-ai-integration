<?php

namespace App\Controllers\Admin;

use App\Controllers\Base\AuthenticatedController;
use App\Models\Subjects;
use App\Services\SubjectService;

use Core\Helpers\Helper;

class SubjectController extends AuthenticatedController
{
    protected SubjectService $subjectService;

    public function __construct($request)
    {
        parent::__construct($request);
        $this->subjectService = new SubjectService();
    }

    public function subjectsPage()
    {
        $subjects = $this->subjectService->getAllSubjectsWithPrerequisites();
        $this->view('admin/subjects', [
            'title' => 'Subject Management',
            'showTopbar' => false,
            'subjects' => $subjects
        ]);
    }

    public function createSubject()
    {
        $data = $this->request->only(['code', 'title', 'units', 'category']);
        $prereq_ids = $this->request->input('prereq_ids') ?? [];
      
        $errors = [];

   
        if (!$data['code']) $errors['code'] = "Subject code is required.";
        if (!$data['title']) $errors['title'] = "Subject title is required.";
        if (!isset($data['units']) || $data['units'] < 1 || $data['units'] > 6) $errors['units'] = "Units must be between 1 and 6.";
        if (!$data['category']) $errors['category'] = "Category is required.";

        if (!empty($errors)) {
            return $this->view('admin/subjects', [
                'title' => 'Subject Management',
                'showTopbar' => false,
                'errors' => $errors,
                'old' => $data
            ]);
        }

        
        $validPrerequisiteIds = [];
        foreach ($prereq_ids as $prereq_id) {
            if (Subjects::find((int)$prereq_id)) {
                $validPrerequisiteIds[] = $prereq_id;
            }
        }

        
       
        $this->subjectService->createSubjectWithPrerequisites($data, $validPrerequisiteIds);

        return Helper::redirect('/subjects', 200);
    }
}
