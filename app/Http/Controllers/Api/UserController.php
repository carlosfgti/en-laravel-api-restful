<?php

namespace App\Http\Controllers\Api;

use App\DTO\User\CreateUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return UserResource::collection($this->userRepository->getPaginate(
            filter: $request->get('filter', ''),
            totalPerPage: $request->get('total_per_page', 15),
            page: $request->get('page', 1),
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userRepository->createNew(new CreateUserDTO(...$request->validated()));
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!$user = $this->userRepository->findById($id)) {
            return response()->json(['message' => 'user not found'], Response::HTTP_NOT_FOUND);
        }
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
