<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
class UserController extends Controller
{
    public function index(){
        $user = Auth::user();
        $usuarios = User::get();

        return [
            'usuario' => $user,
            'usuarios' => $usuarios
        ];
    }
}
