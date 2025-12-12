<?php

namespace App\Controllers\Admin;

use App\Controllers\Base\AuthenticatedController;
use App\Services\ProgramService;
use App\Services\SectionService;
use Core\Helpers\Helper;
use Core\Http\Request;
use Core\Validation\Validator;

class SectionController extends AuthenticatedController
{
    protected $sectionService;
    protected $programService;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->sectionService = new SectionService();
        $this->programService = new ProgramService();
    }

    public function sectionPage()
    {
        $programs = $this->programService->getPrograms();
        $sections = $this->sectionService->getSectionWithProgram();
        return $this->view($this->currentUser->role . '/sections', [
            'title' => 'Section Management',
            'showTopbar' => false,
            'programs' => $programs['data'],
            'sections' => $sections
        ]);
    }

    public function createSection()
    {
        $capacity = 30;
        $data = $this->request->only(['year_level', 'section_code']);
        $programId = $this->request->input('program_id');

        $errors = [];
        $data['program_id'] = $programId;

        $rules = [
            'year_level' => 'required|min:1',
            'section_code' => 'required|min:2',
            'program_id' => 'required|exists:programs,id'
        ];


        $validator = new Validator($data, $rules);
        if ($validator->validate()) {
            return $this->view($this->currentUser->role . '/sections', [
                'title' => 'Section Management',
                'showTopbar' => false,
                'errors' => $validator->errors(),
                'old' => $data
            ]);
        }

        $this->sectionService->createSection($data, $programId);

        return Helper::redirect('/sections', 201);
    }
}
