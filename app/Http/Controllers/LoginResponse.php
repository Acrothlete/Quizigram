<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class LoginResponse extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $is_Admin = Auth::user()->is_admin;
        
        switch ($is_Admin) {
            case true:
                return redirect('/admin');
            case false:
                return redirect('/dashboard');
            default:
                return redirect('/dashboard');
            }
    }

}
