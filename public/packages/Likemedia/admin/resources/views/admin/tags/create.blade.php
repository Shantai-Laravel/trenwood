@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('tags.index') }}">Taguri</a></li>
        <li class="breadcrumb-item active" aria-current="page">Crearea tagului</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Crearea tagului </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('tags.create'),
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
    <form class="form-reg" method="POST" action="{{ route('tags.store') }}">
        {{ csrf_field() }}
        @if (!empty($langs))
        @foreach ($langs as $lang)
        <div class="tab-content {{ $loop->first ? ' active-content' : '' }}" id={{ $lang->
            lang }}>
            <div class="part full-part">
                <ul>
                    <li>
                        <label>{{trans('variables.title_table')}}</label>
                        <input type="text" name="name_{{ $lang->lang }}">
                    </li>
                    <li>
                        <br><input type="submit" value="{{trans('variables.save_it')}}">
                    </li>
                </ul>
            </div>
        </div>
        @endforeach
        @endif
    </form>
</div>
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
