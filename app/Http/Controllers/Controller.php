<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use App\Models\Lang;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminUserActionPermision;
use View;

class Controller extends BaseController
{
    use AuthorizesRequests,  DispatchesJobs, ValidatesRequests;

    protected $langs;

    protected $lang;

    public function __construct()
    {
        $this->langs = Lang::all();

        $this->lang = Lang::where('lang', session('applocale') ?? 'ro')->first();

        if (!Auth::check()) {
            return redirect('/auth/login');
        }

    }

}
