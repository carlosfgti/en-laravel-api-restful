<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function __construct(private User $model)
    {
    }

    public function getPaginate(string $filter = '', int $totalPerPage = 15, int $page = 1): LengthAwarePaginator
    {
        return $this->model->where(function ($query) use ($filter) {
            if ($filter !== '') {
                $query->where('name', 'LIKE', "%$filter%");
                $query->orWhere('email', $filter);
            }
        })->paginate($totalPerPage, ['*'], 'page', $page);
    }
}