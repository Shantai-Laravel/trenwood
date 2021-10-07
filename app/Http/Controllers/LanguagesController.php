<?php

namespace App\Http\Controllers;


use App\Models\Lang;
use App\Models\FrontUser;

class LanguagesController
{
    public function set($lang) {
        $lang = Lang::where('lang', $lang)->first()->lang;
        session(['applocale' => $lang]);

        return back();
    }

    public function changeLang() {
        $user = FrontUser::where('id', auth('persons')->id())->first();

        if(count($user) > 0) {
            $user->lang = request('lang');
            $user->save();
        }
    }
}
