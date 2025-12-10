<?php

namespace App\Services;

use App\Base\BaseService;
use App\Models\Programs;

class ProgramService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Programs::class);
    }

    /** Create new program */
    public function save(array $data)
    {
        return $this->model->create($data);
    }

    /** Get programs with search, filter, pagination */
    public function getPrograms(
        string $search = '',
        string $filter = '',
        int $page = 1,
        int $limit = 10
    ): array {

        $query = $this->model->query();

        /** 🔍 SEARCH */
        if (!empty($search)) {
            $query->where('code', 'LIKE', "%$search%")
                ->orWhere('description', 'LIKE', "%$search%");
        }

        /** 🏷 FILTER */
        if (!empty($filter)) {
            $query->where('status', '=', $filter);
        }

        /** FIXED COUNT */
        $total = (clone $query)->count();

        /** PAGINATION VALUES */
        $offset = ($page - 1) * $limit;

        /** GET ACTUAL DATA */
        $data = $query->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();

        return [
            'data' => $data,
            'pagination' => [
                'total' => $total,
                'limit' => $limit,
                'current_page' => $page,
                'total_pages' => ceil($total / $limit),
            ]
        ];
    }


    public function delete(int $id)
    {
        $program = $this->model->find($id);
        if (!$program) {
            return false;
        }
        return $program->delete();
    }
}
