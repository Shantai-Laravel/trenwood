@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('menus.index') }}">Grupuri de menu</a></li>
        <li class="breadcrumb-item"><a href="{{ url('back/menus/group/'.Request::get('group')) }}">Grupa {{ $menuGroup->name }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Crearea elementului menu</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Crearea elementului menu </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('menus.create').'?group='.$menuGroup->id,
    ]
    ])
</div>

<div class="list-content">
<div class="tab-area">
    @include('admin::admin.alerts')
    <ul class="nav nav-tabs nav-tabs-bordered">
        @if (!empty($langs))
        @foreach ($langs as $key => $lang)
        <li class="nav-item">
            <a href="#{{ $lang->lang }}" class="nav-link  {{ $key == 0 ? ' open active' : '' }}"
                data-target="#{{ $lang->lang }}">{{ $lang->lang }}</a>
        </li>
        @endforeach
        @endif
    </ul>
</div>
<form class="form-reg" method="POST" action="{{ route('menus.store') }}" enctype="multipart/form-data">
    {{ csrf_field() }}
    @if (!empty($langs))
    @foreach ($langs as $lang)
    <div class="tab-content {{ $loop->first ? ' active-content' : '' }}" id={{ $lang->
        lang }}>
        <div class="part full-part">
            <ul>
                <li>
                    <label>{{trans('variables.title_table')}} [{{ $lang->lang }}]</label>
                    <input type="text" name="name_{{ $lang->lang }}" class="name">
                </li>
            </ul>
        </div>
    </div>
    @endforeach
    @endif
    <div>
        <div class="part full-part">
            <ul>
                <li>
                    <label>Parent</label>
                    <select name="parent_id" class="form-control">
                        <option value="0">- - -</option>
                        @if (!empty($menus))
                        @foreach($menus as $menu)
                        <option value="{{ $menu->id }}">{{ $menu->translation()->first()->name }}</option>
                        @endforeach
                        @endif
                    </select>
                </li>
                <li>
                    <label>Link</label>
                    <select class="form-control categorySelect" name="link" >
                        @if (!empty($pages))
                        <optgroup label="Pagini Statice">
                            @foreach ($pages as $key => $page)
                            <option value="/page/{{ $page->translation()->first()->slug }}">{{ !is_null($page->translation()->first()) ? $page->translation()->first()->title : '' }}</option>
                            @endforeach
                        </optgroup>
                        @endif
                        @if (!empty($categories))
                        <optgroup label="Categorii">
                            @foreach ($categories as $key => $category)
                            <option data="category" data-id="{{ $category->id }}" value="{{ !is_null($category->translation()->first()) ? $category->translation()->first()->name : '' }}">{{ $category->translation()->first()->name }}</option>
                            @endforeach
                        </optgroup>
                        @endif
                    </select>
                </li>
                <li>
                    <br><br>
                    <input type="submit" value="{{trans('variables.save_it')}}">
                </li>
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
