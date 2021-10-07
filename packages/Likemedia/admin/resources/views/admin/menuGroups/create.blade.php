@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
@include('admin::admin.speedbar')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Contol Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('groups.index') }}">Grupuri de menu</a></li>
        <li class="breadcrumb-item active" aria-current="page">Crearea grupei de menu</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Crearea grupei de menu </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('groups.create'),
    ]
    ])
</div>
<div class="list-content">
    @include('admin::admin.alerts')
    <form class="form-reg" method="POST" action="{{ route('groups.store') }}">
        {{ csrf_field() }}
        <div class="part left-full">
            <ul>
                <li>
                    <label for="name">{{trans('variables.title_table')}}</label>
                    <input type="text" name="name" id="name">
                </li>
                <li>
                    <label for="slug">Slug</label>
                    <input type="text" name="slug" id="slug">
                </li>
                <li>
                    <br><input type="submit" value="{{trans('variables.save_it')}}"><br>
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
