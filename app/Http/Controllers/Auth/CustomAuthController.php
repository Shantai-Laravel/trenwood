<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Cookie;

class CustomAuthController extends Controller
{

    public function login()
    {
        if (Request::isMethod('post'))
            return $this->checkLogin();

        if (Auth::user()) {
            return redirect()->route('back');
        }

        return view('admin::auth.login', get_defined_vars());
    }

    public function checkLogin()
    {
        // dd('ok');
        $validation = Validator::make(Input::all(), [
            'login' => 'required|min:3',
            'password' => 'required|min:4',
        ]);

        if (Auth::attempt(array('login' => Input::get('login'), 'password' => Input::get('password')))){
            // echo "error";
            return redirect()->route('back');
        }

        // dd(Auth::user());

        echo "success";

        return redirect()->back();
    }

    public function register() { }

    public function checkRegister() { }

    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}
