<?php

namespace Admin\Http\Controllers;

use App\Models\PropertyGroup;
use App\Models\PropertyGroupTranslation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PropertyGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $propetyGroups = PropertyGroup::get();

        return view('admin::admin.propertiesGroups.index', compact('propetyGroups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin::admin.propertiesGroups.create');
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
        foreach ($this->langs as $lang){
            $toValidate['name_'.$lang->lang] = 'required|unique:properties_groups_translation,name|max:255';
        }

        $validator = $this->validate($request, $toValidate);

        $group = new PropertyGroup();
        $group->save();

        foreach ($this->langs as $lang):
            $group->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('name_' . $lang->lang),
            ]);
        endforeach;

        session()->flash('message', 'New item has been created!');

        return redirect()->route('properties-groups.index');
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
        $group = PropertyGroup::with(['translations'])->findOrFail($id);

        return view('admin::admin.propertiesGroups.edit', compact('group'));
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
        $group = PropertyGroup::findOrFail($id);
        $group->save();

        foreach ($this->langs as $lang):
            $group->translations()->where('property_group_id', $group->id)->where('lang_id', $lang->id)->update([
                'lang_id' => $lang->id,
                'name' => request('name_' . $lang->lang),
            ]);
        endforeach;

        session()->flash('message', 'New item has been updated!');

        return redirect()->route('properties-groups.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PropertyGroup::where('id', $id)->delete();
        PropertyGroupTranslation::where('property_group_id', $id)->delete();

        return redirect()->back();
    }
}
