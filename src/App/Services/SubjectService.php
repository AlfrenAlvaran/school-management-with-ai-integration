<?php

namespace App\Services;

use App\Base\BaseService;
use App\Models\Prerequisite;
use App\Models\Subjects;

class SubjectService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Subjects::class);
    }

    /**
     * Create new subject
     */
    public function save(array $data)
    {
        return $this->model->create($data);
    }

    public function createSubjectWithPrerequisites(array $data, array $prerequisiteIds)
    {
     
        $pdo = $this->model->connection();
        $pdo->beginTransaction();

        try {
           
            $subject = $this->model->create($data);
            $subject_id = $subject->id;

           
            $validPrereqIds = [];
            if (!empty($prerequisiteIds)) {
                foreach ($prerequisiteIds as $id) {
                    $prereq = Subjects::find($id);
                    if ($prereq) {
                        $validPrereqIds[] = $id;
                    }
                }
            }


           
            foreach ($validPrereqIds as $prereqId) {
                Prerequisite::create([
                    'subject_id' => $subject_id,
                    'prerequisite_id' => $prereqId
                ]);
            }

           
            $pdo->commit();

            return $subject;
        } catch (\Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }


    public function getAllSubjectsWithPrerequisites()
    {
        $subjects = $this->model
            ->query()
            ->select('subjects.id, subjects.code, subjects.title, subjects.units, subjects.category')
            ->orderBy('subjects.title')
            ->get();

        $result = [];

        foreach ($subjects as $subject) {
           
            $prereqRows = Prerequisite::query()
                ->select('subjects.id', 'subjects.code', 'subjects.title')
                ->join('subjects', 'subjects.id', '=', 'prerequisites.prerequisite_id')
                ->where('prerequisites.subject_id', '=', $subject->id)
                ->get();

            $prerequisites = [];
            foreach ($prereqRows as $row) {
                $prerequisites[] = (object)[
                    'id' => $row->id,
                    'code' => $row->code,
                    'title' => $row->title
                ];
            }

            $result[] = (object)[
                'id' => $subject->id,
                'code' => $subject->code,
                'title' => $subject->title,
                'units' => $subject->units,
                'category' => $subject->category,
                'prerequisites' => $prerequisites
            ];
        }

        return $result;
    }
}
