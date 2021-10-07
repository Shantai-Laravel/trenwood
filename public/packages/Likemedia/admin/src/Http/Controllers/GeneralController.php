<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\General;


class GeneralController extends Controller
{
    public function index()
    {
      $changeMenu = json_decode(file_get_contents(storage_path('globalsettings.json')), true)['changeCategory'];
      $generals = General::all();

      return view('admin::admin.general.index', compact('changeMenu', 'generals'));
    }

    public function updateMenu(Request $request)
    {
      $general = json_decode(file_get_contents(storage_path('globalsettings.json')), true);

      if($request->changeCategory == 'on') {
        $general['changeCategory'] = true;
      } else {
        $general['changeCategory'] = false;
      }

      $file_handle = fopen(storage_path('globalsettings.json'), 'w');
      fwrite($file_handle, json_encode($general));
      fclose($file_handle);

      session()->flash('message', 'This option has been updated!');

      return redirect()->route('general.index');
    }

    public function updateSettings(Request $request) {
        foreach ($request->id as $key => $generalId) {
            $general = General::find($key);

            $general->translations()->delete();

            foreach ($this->langs as $lang):
                $general->translations()->create([
                    'lang_id' => $lang->id,
                    'name' => request('name_' . $lang->lang)[$key-1],
                    'body' => request('body_' . $lang->lang)[$key-1],
                    'description' => request('description_' . $lang->lang)[$key-1],
                ]);
            endforeach;
        }

        return redirect()->back();
    }

}
