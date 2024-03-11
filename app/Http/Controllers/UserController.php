<?php

namespace App\Http\Controllers;

use App\Models\Users;

class UserController extends Controller
{
    public function showUsers() {
        return Users::all();
    }
}
