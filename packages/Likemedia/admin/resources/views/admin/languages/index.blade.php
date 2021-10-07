@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="page">Limbi</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Limbi </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('languages.create'),
    ]
    ])
</div>

@include('admin::admin.alerts')

@if(!empty($languages))
<div class="card">
    <div class="card-block">
        <table class="table table-hover table-striped" id="tablelistsorter">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{trans('variables.title_table')}}</th>
                    <th>{{trans('variables.description')}}</th>
                    <th>{{trans('variables.lang')}}</th>
                    <th>{{trans('variables.delete_table')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($languages as $key => $language)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $language->lang }}</td>
                    <td>{{ $language->description }}</td>
                    @if($language->default == 1)
                    <td>Default Language</td>
                    @else
                    <td>
                        <form action="{{ route('languages.default', $language->id) }}" method="post">
                            {{ csrf_field() }} {{ method_field('PATCH') }}
                            <button type="submit" class="btn btn-link">
                            <a><i class="fa fa-plus"></i></a>
                    </td>
                    </button>
                    </form>
                    @endif
                    <td class="destroy-element">
                        <form action="{{ route('languages.destroy', $language->id) }}" method="post">
                            {{ csrf_field() }} {{ method_field('DELETE') }}
                            <button type="submit" class="btn btn-link"><a><i class="fa fa-trash"></i></a></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan=7></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@else
<div class="empty-response">{{trans('variables.list_is_empty')}}</div>
@endif
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
