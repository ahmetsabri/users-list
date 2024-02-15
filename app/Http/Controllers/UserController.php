<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query();

        if ($request->query('term')) {
            $users = $users->search($request->query('term'));
        }
        $users = $users->paginate();

        return view('users.index', compact('users'));
    }


    public function destroy(User $user){
        $user->delete();
        return response()->json(status:204);
    }

    public function update(Request $request,User $user){
        $validator = $request->validate([
            'name' =>['required'],
            'email' => ['required','email'],
            'password' => ['sometimes','nullable']
        ],$request->all());

        $user->fill([
            'name' => $validator['name'],
            'email' => $validator['email'],
        ]);

        if($validator['password']){
            $user->password = $request->password;
        }
        $user->saveOrFail();

        return back();
    }
}
