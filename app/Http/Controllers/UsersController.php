<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function showUsers() {
        return Users::all();
    }

    public function addUser(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'login' => 'required',
            'password' => 'required',
            'role_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 400, 'errors' => $validator->errors()]);
        }

        $user = Users::create([
           'name' => $request->name,
           'login' => $request->login,
            'password' => $request->password,
            'role_id' => $request->role_id,
        ]);

        $user_id = $user->id;
        return response()->json(['id' => $user_id, 'status' => 'created'], 201);
    }
}
