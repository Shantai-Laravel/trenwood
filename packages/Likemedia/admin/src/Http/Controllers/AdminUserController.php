<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminUserActionPermision;
use App\Models\Module;
use Illuminate\Support\Facades\Request;
use App\Models\AdminUserGroup;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;


class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('admin::admin.user.users', compact('users'));
    }

    public function createItem()
    {
        return view('admin::admin.user.create-group', get_defined_vars());
    }

    public function createUser()
    {
        $view = 'admin::admin.user.create-user';

        return view($view, get_defined_vars());
    }

    public function cartItems()
    {
        $view = 'admin::admin.user.group-cart';

        $deleted_group = AdminUserGroup::where('deleted', 1)
            ->where('active', 0)
            ->paginate(10);

        return view($view, get_defined_vars());
    }

    public function membersList()
    {
        $view = 'admin::admin.user.users';

        $user_group_id = AdminUserGroup::where('deleted', 0)
            ->where('active', 1)
            ->where('alias', Request::segment(4))
            ->first();

        if(is_null($user_group_id)){
            return App::abort(503, 'Unauthorized action.');
        }

        $user = User::with('group')
            ->where('admin_user_group_id', $user_group_id->id)
            ->paginate(10);

        $topTitle = 'Группа - '.getGroupNameByAlias(Request::segment(4));

        return view($view, get_defined_vars());
    }

    public function all()
    {
        $view = 'admin::admin.user.users';

        $user = User::with('group')
            ->paginate(10);

        $topTitle = 'Все Пользователи';

        return view($view, get_defined_vars());
    }

    public function editUser($id)
    {

        $view = 'admin::admin.user.edit-user';

        $group_name = User::find($id)->group->name;

        $user = User::findOrFail($id);

        if(str_slug($group_name) != Request::segment(4)){
            return App::abort(503, 'Unauthorized action.');
        }

        return view($view, get_defined_vars());

    }

    public function save($id){

        $group_id = AdminUserGroup::where('alias', Request::segment(4))->first()->id;

        $item = Validator::make(Input::all(), [
            'login' => 'required|min:3',
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'min:4',
        ]);

        if($item->fails()){
            return response()->json([
                'status' => false,
                'messages' => $item->messages(),
            ]);
        }


        if(!empty(Input::get('password'))){
            $data = array_filter([
                'login' => Input::get('login'),
                'name' => Input::get('name'),
                'email' => Input::get('email'),
                'password' => bcrypt(Input::get('password')),
                'remember_token' => Input::get('_token'),
                'admin_user_group_id' => $group_id,
            ]);
        }
        else {
            $data = array_filter([
                'login' => Input::get('login'),
                'name' => Input::get('name'),
                'email' => Input::get('email'),
                'remember_token' => Input::get('_token'),
                'admin_user_group_id' => $group_id,
            ]);
        }

        if(is_null($id)){
            User::create($data);
            return response()->json([
                'status' => true,
                'messages' => [Lang::get('variables.save', array(), $this->lang()['lang'])],
                'redirect' => urlForLanguage($this->lang()['lang'], 'memberslist')
            ]);
        }
        else{
            User::where('id', $id)
                ->update($data);

            return response()->json([
                'status' => true,
                'messages' => [Lang::get('variables.save', array(), $this->lang()['lang'])],
                'redirect' => urlForLanguage($this->lang()['lang'], 'edituser/'.$id)
            ]);
        }
    }

    public function editList($id)
    {
        $view = 'admin::admin.user.edit-list';

        $group = AdminUserGroup::findOrFail($id);
        $lang = $this->lang()['lang'];


        $result = [];
        $modules = Module::with('modulesPermission.modules')
            ->orderBy('position', 'asc')
            ->get()
            ->toArray();

        foreach ($modules as $keyModules => $singleModules) {
            $result[$keyModules]['name_'.$lang] = $singleModules;
            $result[$keyModules]['permission'] = $singleModules['modules_permission'];
            $result[$keyModules]['roles'] = [];

            if (isset($singleModules['modules_permission']) && count($singleModules['modules_permission'])) {
                $pretendentRole = AdminUserActionPermision::where('admin_user_group_id', $id)->get();
                foreach ($pretendentRole as $k => $roles) {
                    if (count($roles))
                        $result[$keyModules]['roles'][] = $roles;
                }

                }
            }

        $child_modules = AdminUserGroup::findOrFail($id)->userPermission;
        $arr = [];
        $new = [];
        $save = [];
        $active = [];
        $del_to_rec = [];
        $del_from_rec = [];
        foreach($child_modules as $k => $v){
            $arr[] = $v->modules_id;
            $new[] = $v->new.$v->modules_id;
            $save[] = $v->save.$v->modules_id;
            $active[] = $v->active.$v->modules_id;
            $del_to_rec[] = $v->del_to_rec.$v->modules_id;
            $del_from_rec[] = $v->del_from_rec.$v->modules_id;
        }

//        if(str_slug($group->name) != Request::segment(4)){
//            return App::abort(503, 'Unauthorized action.');
//        }

        return view($view, get_defined_vars());
    }

    public function saveList($id)
    {
        $item = Validator::make(Input::all(), [
            'name' => 'required',
        ]);

        if($item->fails()){
            return response()->json([
                'status' => false,
                'messages' => $item->messages(),
            ]);
        }

        $data = [
            'name' => Input::get('name'),
            'alias' => str_slug(Input::get('name')),
            'active' => 1,
            'deleted' => 0,
        ];

        if(is_null($id)){
            $new_group = AdminUserGroup::create($data);
        }
        else{
            AdminUserGroup::where('id', $id)->update($data);
            $new_group = AdminUserGroup::find($id);
        }

        $new_group_id = AdminUserGroup::findOrFail($new_group->id)->id;

        AdminUserActionPermision::where('admin_user_group_id', $id)->delete();

        $new = Input::get('new');
        $save = Input::get('save');
        $active = Input::get('active');
        $del_to_rec = Input::get('del_to_rec');
        $del_from_rec = Input::get('del_from_rec');
        $modules_id = Input::get('modules_id');
        $arr = ['new' => $new, 'save' => $save, 'active' => $active, 'del_to_rec' => $del_to_rec, 'del_from_rec' => $del_from_rec];


            foreach ($modules_id as $key => $mod_id) {
                $arr['modules_id'] = $mod_id;
                isset($arr['new'][$key]) ? $new_val = 1 : $new_val = 0;
                isset($arr['save'][$key]) ? $save_val = 1 : $save_val = 0;
                isset($arr['active'][$key]) ? $active_val = 1 : $active_val = 0;
                isset($arr['del_to_rec'][$key]) ? $del_to_rec_val = 1 : $del_to_rec_val = 0;
                isset($arr['del_from_rec'][$key]) ? $del_from_rec_val = 1 : $del_from_rec_val = 0;

                $data = [
                    'new' => $new_val,
                    'save' => $save_val,
                    'active' => $active_val,
                    'del_to_rec' => $del_to_rec_val,
                    'del_from_rec' => $del_from_rec_val,
                    'admin_user_group_id' => $new_group_id,
                    'modules_id' => $mod_id,

                ];

                AdminUserActionPermision::create($data);
            }




        if(is_null(AdminUserGroup::find($id))){
            return response()->json([
                'status' => true,
                'messages' => [Lang::get('variables.save', array(), $this->lang()['lang'])],
                'redirect' => urlForLanguage($this->lang()['lang'], '')
            ]);
        }
        return response()->json([
            'status' => true,
            'messages' => [Lang::get('variables.save', array(), $this->lang()['lang'])],
            'redirect' => urlForLanguage($this->lang()['lang'], 'editlist/'.$id)
        ]);

    }

    public function destroyUser($id)
    {
        $admin_user = User::findOrFail($id);
        Session::flash('message', $admin_user->name . '<br />was successful deleted! ');

        if(!is_null($admin_user))
            User::destroy($id);

        return redirect()->back();
    }

    public function destroyGroup($id)
    {
        $group = AdminUserGroup::findOrFail($id);

        if(!is_null($group)){
            if($group->deleted == 1 && $group->active == 0){
                Session::flash('message', $group->name . '<br />was successful deleted! ');

                AdminUserGroup::destroy($id);
                AdminUserActionPermision::where('admin_user_group_id', $id)->delete();
            }
            elseif($group->deteled == 0){
                Session::flash('message', $group->name . '<br />was successful added to cart! ');
                AdminUserGroup::where('id', $id)
                    ->update(['active' => 0, 'deleted' => 1]);
            }
        }

        return redirect()->back();
    }

    public function restore($id)
    {
        $admin_group = AdminUserGroup::findOrFail($id);
        Session::flash('message', $admin_group->name . '<br />was successful restored! ');

        AdminUserGroup::where('id', $id)
            ->update(['active' => 1, 'deleted' => 0]);

        return redirect()->back();
    }


}
