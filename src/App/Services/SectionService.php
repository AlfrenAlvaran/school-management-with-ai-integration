<?php

namespace App\Services;

use App\Base\BaseService;
use App\Models\Programs;
use App\Models\Sections;

class SectionService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Sections::class);
    }

    // create new section

    public function createSection(array $data, int $id)
    {
        $pdo = $this->model->connection();
        $pdo->beginTransaction();

        try {
            $program = Programs::find($id);
            if (!$program) {
                throw new \Exception("Invalid program ID");
            }

            $data['program_id'] = $id;
            $section = $this->model->create($data);

            $pdo->commit();
            return $section;
        } catch (\Throwable $th) {
            $pdo->rollBack();
            throw $th;
        }
    }

    // Get section with Program 
    public function getSectionWithProgram()
    {

        return $this->model->query()->with('program')->get();
    }

    public function deleteSection(int $id): bool
    {
        return $this->model
            ->query()
            ->where('id', '=', $id)
            ->delete();
    }
}
