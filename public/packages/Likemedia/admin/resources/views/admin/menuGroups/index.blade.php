@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Contol Panel</a></li>
        <li class="breadcrumb-item active" aria-current="page">Grupuri de menu</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Grupuri de menu </h3>
    @include('admin::admin.list-elements', [
        'actions' => [
            trans('variables.add_element') => route('groups.create'),
        ]
    ])
</div>

@if(count($menuGroups))
<div class="card">
    <div class="card-block">
        <table class="table table-hover table-striped" id="tablelistsorter">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{trans('variables.title_table')}}</th>
                    <th>Slug</th>
                    <th>Menus</th>
                    <th>{{trans('variables.edit_table')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($menuGroups as $key => $menuGroup)
                <tr id="{{ $menuGroup->id }}">
                    <td>#{{ $key + 1 }}</td>
                    <td>   {{ $menuGroup->name }} </td>
                    <td>   [{{ $menuGroup->slug }}] </td>
                    <td>
                        <a href="{{ route('menus.group', $menuGroup->id) }}">
                        <i class="fa fa-sign-in"></i>
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('groups.edit', $menuGroup->id) }}">
                        <i class="fa fa-edit"></i>
                        </a>
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
