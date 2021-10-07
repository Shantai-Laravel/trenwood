@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/back') }}">Contol Panel</a></li>
            <li class="breadcrumb-item"><a href="{{ route('properties.index') }}">Parametri</a></li>
            <li class="breadcrumb-item"><a href="{{ route('properties-groups.index') }}">Grupe de parametri</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editarea grupei de parametri</li>
        </ol>
    </nav>
    <div class="title-block">
        <h3 class="title"> Editarea grupei de parametri </h3>
        @include('admin::admin.list-elements', [
        'actions' => [
        trans('variables.add_element') => route('properties-groups.create'),
        ]
        ])
    </div>

<div class="list-content">
    <form class="form-reg" method="POST" action="{{ route('properties-groups.update', $group->id) }}" enctype="multipart/form-data">
        {{ csrf_field() }} {{ method_field('PATCH') }}

        <div class="tab-area">
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

        @if (!empty($langs))
        @foreach ($langs as $lang)
        <div class="tab-content {{ $loop->first ? ' active-content' : '' }}" id={{ $lang->lang }}>
            <div class="part col-md-12">
                <ul>
                    <li>
                        <label>Name [{{ $lang->lang }}]</label>
                        <input type="text" name="name_{{ $lang->lang }}" data-lang="{{ $lang->lang }}" required
                        @foreach($group->translations as $translation)
                        @if ($translation->lang_id == $lang->id)
                        value="{{ $translation->name }}"
                        @endif
                        @endforeach
                        >
                    </li>
                </ul>
            </div>

        </div>
        @endforeach
        @endif
        <ul>
            <li>
                <hr><input type="submit" value="Save">
            </li>
        </ul>
    </form>
</div>
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
