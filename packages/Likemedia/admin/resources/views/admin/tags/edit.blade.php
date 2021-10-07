@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('tags.index') }}">Taguri</a></li>
        <li class="breadcrumb-item active" aria-current="page">Editarea tagului</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Editarea tagului </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('tags.create'),
    ]
    ])
</div>
<div class="list-content">
    <div class="tab-area">
        @include('admin::admin.alerts')
    </div>
    <form class="form-reg" method="POST" action="{{ route('tags.update', $tag->id) }}">
        {{ csrf_field() }} {{ method_field('PATCH') }}
        <div class="part full-part" style="padding: 15px;">
            <ul>
                <li>
                    <label>{{trans('variables.title_table')}}</label>
                    <input type="text" name="name" value="{{ $tag->name }}" />
                </li>
                <li>
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
