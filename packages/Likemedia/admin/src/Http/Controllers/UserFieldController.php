<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\UserField;
use App\Models\Country;

class UserFieldController extends Controller
{
    public function index()
    {
      $countries = Country::all();
      $userfields = UserField::all();
      return view('admin::admin.userfields.index', compact('userfields', 'countries'));
    }

    public function store(Request $request)
    {
        foreach ($request->except('_token') as $fieldKey => $request) {
            foreach ($request as $key => $field) {
              if(is_array($field)) {
                  UserField::where('id', $key)->update([
                      $fieldKey => json_encode($field)
                  ]);
              } else {
                  UserField::where('id', $key)->update([
                      $fieldKey => $field
                  ]);
              }
            }
        }

        return redirect()->back();
    }
}
