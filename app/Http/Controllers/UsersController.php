<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Resources\UsersResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('include')){
            $includes = explode(',',$request->get('include'));
        }
        /** @var Collection $users */
        $users = User::with((isset($includes) ? $includes: []))->get();
        return $this->responseJSON(new UsersResource($users));
    }

    public function show(Request $request ,$id)
    {
        if ($request->has('include')){
            $includes = explode(',',$request->get('include'));
        }
        /** @var User $user */
        $user = User::with((isset($includes) ? $includes: []))->find($id);
        if ($user == null) return $this->return404('User');
        return $this->responseJSON(new UsersResource($user));
    }

    public function update(Request $request ,$id)
    {
        /** @var User $user */
        $user = User::find($id);
        if ($user == null) return $this->return404('User');
        $user->update([
            'name' => $request->get('name'),
            'email' => $request->get('email')
        ]);
        return $this->responseJSON($user->fresh());
    }
}