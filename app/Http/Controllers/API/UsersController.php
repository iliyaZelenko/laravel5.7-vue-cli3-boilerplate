<?php

namespace App\Http\Controllers\API;

use App\User;

class UsersController extends BaseController
{
    public function index()
    {
        return User::orderBy('id', 'desc')
            ->get();
    }

    public function show($group)
    {
        return $this->getUser($group);
    }

    public function store()
    {
        $group = User::create(request()->all());

        return $this->getUser($group->id);
    }

    public function update($group)
    {
        User::whereId($group)->update(request()->all());

        return $this->getUser($group);
    }

    public function destroy($group)
    {
        User::destroy($group);
    }

    protected function getUser($group)
    {
        return User::whereId($group)->first();
    }
}
