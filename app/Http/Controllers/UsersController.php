<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
            return response()->json(['code' => 422, 'errors' => $validator->errors()]);
        }

        $photo_file = $request->file('photo_file')->store('photos');
        $user = Users::create([
           'name' => $request->name,
           'surname' => $request->surname,
           'patronymic' => $request->patronymic,
            'login' => $request->login,
            'password' => $request->password,
           'photo_file' => $photo_file,
            'role_id' => $request->role_id,
        ]);

        $user_id = $user->id;
        return response()->json(['id' => $user_id, 'status' => 'created'], 201);
    }
}
