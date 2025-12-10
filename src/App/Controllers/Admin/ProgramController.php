<?php

namespace App\Controllers\Admin;

use App\Controllers\Base\AuthenticatedController;
use App\Services\ProgramService;
use Core\Helpers\Helper;
use Core\Http\Request;
use Core\Http\Session;

class ProgramController extends AuthenticatedController
{

    protected ProgramService $programService;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->programService = new ProgramService();
    }
    public function programPage()
    {
        $search = $this->request->get('search') ?? '';
        $filter = $this->request->get('filter') ?? '';
        $page = (int)($this->request->get('page') ?? 1);
        $limit = 10;
        $programsData = $this->programService->getPrograms($search,$filter, $page, $limit);

        return $this->view($this->currentUser->role . '/program', [
            'title'=> 'Program Management',
            'showTopbar'=>false,
            'programs' => $programsData['data'],
            'pagination'=> $programsData['pagination'],
            'search' => $search,
            'filter' => $filter,
        ]);
    }

    public function createProgram()
    {
        $data=$this->request->only(['code','description']);
        $errors = [];

        if(!$data['code']){
            $errors['code'] = "Program code is required.";
        }
        if(!$data['description']){
            $errors['description'] = "Description is required.";
        }

        if (!empty($errors)) {
            return $this->view($this->currentUser->role . '/program', [
                'title' => 'Program Management',
                'showTopbar' => false,
                'errors' => $errors,
                'old' => $data
            ]);
        }

        $this->programService->save($data);
        return Helper::redirect('/program');
    }

    public function deleteProgram(int $id)
    {
        $deleted = $this->programService->delete($id);
        if(!$deleted) {
            Session::flash('error', "Program deleted successfully.");
        }

        Session::flash('success', "Program deleted successfully.");
        return Helper::redirect('/program');

    }
}