<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MenuGroup;

class MenuGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menuGroups = MenuGroup::all();

        return view('admin::admin.menuGroups.index', compact('menuGroups'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('admin::admin.menuGroups.create' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $toValidate = [];
        $toValidate['name'] = 'required|max:255';
        $toValidate['slug'] = 'required|max:255|unique:menus_groups';

        $validator = $this->validate($request, $toValidate);

        $menuGroup = MenuGroup::create([
            'name' =>  $request->get('name'),
            'slug' => $request->get('slug'),
        ]);

        session()->flash('message', 'New item has been created!');

        return redirect()->route('groups.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menuGroup = MenuGroup::findOrFail($id);

        return view('admin::admin.menuGroups.edit', compact('menuGroup'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $menuGroup = MenuGroup::where('id', $id)->update([
          'name' =>  $request->get('name'),
          'slug' => $request->get('slug'),
      ]);

      session()->flash('message', 'New item has been created!');

      return redirect()->route('groups.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
