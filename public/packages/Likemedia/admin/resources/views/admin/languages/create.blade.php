@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('languages.index') }}">Limbi</a></li>
        <li class="breadcrumb-item active" aria-current="page">Creare Limba</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Creare Limba </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('languages.create'),
    ]
    ])
</div>

@include('admin::admin.alerts')

<div class="list-content">
    <form class="form-reg" role="form" method="POST" action="{{ route('languages.store') }}" id="add-form">
        {{ csrf_field() }}
        <div class="part full-part">
            <ul>
                <li>
                    <label for="name">{{trans('variables.title_table')}}</label>
                    <input type="text" name="name" id="name" placeholder="en" value="{{ old('name')}}">
                </li>
                <li>
                    <label for="description">{{ trans('variables.description') }}</label>
                    <input type="text" name="description" id="description" placeholder="English" value="{{ old('description')}}">
                </li>
                <input type="submit" value="{{trans('variables.save_it')}}" data-form-id="add-form">
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
