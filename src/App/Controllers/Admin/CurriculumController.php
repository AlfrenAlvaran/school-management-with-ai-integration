<?php

namespace App\Controllers\Admin;

use App\Controllers\Base\AuthenticatedController;

class CurriculumController extends AuthenticatedController {
    public function curriculumPage()
    {
        return $this->view($this->currentUser->role . '/curriculum', [
            'title'=> 'Curriculum Management',
            'showTopbar'=>false
        ]);
    }
}