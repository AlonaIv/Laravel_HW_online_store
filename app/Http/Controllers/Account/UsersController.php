<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        return view('account/index', ['user' => auth()->user()]);
    }

    public function edit(User $user)
    {
        return view('account/users/edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        if ($request->validated()) {
            $data = collect($request->validated())->toArray();
            $user->update($data);

            return redirect()->route('account.index', ['user' => auth()->user()]);
        }

        return redirect()->back()->withInput();
    }
}
