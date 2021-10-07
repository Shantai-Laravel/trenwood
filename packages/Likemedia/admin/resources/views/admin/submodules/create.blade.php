@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

    @include('admin::admin.speedbar')

    @include('admin::admin.list-elements', [
        'actions' => [
            trans('variables.elements_list') => route('modules.index'),
            trans('variables.add_element') => route('submodules.index'),
        ]
    ])

    <div class="list-content">

        <form class="form-reg" method="POST" action="{{ route('submodules.store') }}">
            {{ csrf_field() }}

            <div class="part left-part">
                <ul>
                    <li>
                        <select name="module_id">
                            @foreach($modules as $module)
                                <option value="{{ $module->id }}">{{ $module->translation()->first()->name }}</option>
                            @endforeach
                        </select>
                    </li>
                    @foreach($langs as $lang)
                        <li>
                            <label for="name">{{trans('variables.title_table')}} {{ $lang->lang }}</label>
                            <input type="text" name="name_{{ $lang->lang }}" id="name_{{ $lang->lang }}">
                        </li>
                        <li>
                            <label>{{trans('variables.description')}} {{ $lang->lang }}</label>
                            <textarea name="description_{{ $lang->lang }}"></textarea>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="part right-part">
                <ul>
                    <li>
                        <label for="src">Link</label>
                        <input type="text" name="src" id="src">
                    </li>
                    <li>
                        <label for="src">Controller</label>
                        <input type="controller" name="controller" id="controller">
                    </li>
                    <li>
                        <label for="table_name">Table name</label>
                        <input type="text" name="table_name" id="table_name">
                    </li>
                    <li>
                        <label for="icon">Icon</label>
                        <input type="text" name="icon" id="icon">
                    </li>
                    <input type="submit" value="{{trans('variables.save_it')}}">
                </ul>
            </div>

        </form>
    </div>

@stop

@section('footer')
    <footer>
        @include('admin::admin.footer')
    </footer>
@stop
