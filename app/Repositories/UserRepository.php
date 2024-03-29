<?php

namespace App\Repositories;

use App\DTO\User\CreateUserDTO;
use App\DTO\User\EditUserDTO;
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

    public function createNew(CreateUserDTO $dto): User
    {
        $data = (array) $dto;
        $data['password'] = bcrypt($dto->password);
        return $this->model->create($data);
    }

    public function findById(string $id): ?User
    {
        return $this->model->find($id);
    }

    public function update(EditUserDTO $dto): bool
    {
        if (!$user = $this->findById($dto->id)) {
            return false;
        }
        $data = (array) $dto;
        $data['password'] = bcrypt($dto->password);
        if ($dto->password === null) {
            unset($data['password']);
        }
        return $user->update($data);
    }

    public function delete(string $id): ?bool
    {
        if (!$user = $this->findById($id)) {
            return null;
        }
        return $user->delete();
    }
}