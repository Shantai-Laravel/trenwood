@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="page">Taguri</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Taguri </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('tags.create'),
    ]
    ])
</div>

@if(count($tags) or count($zeroCountTags))
<div class="card">
    <div class="card-block">
        <table class="table table-hover table-striped" id="tablelistsorter">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{trans('variables.title_table')}}</th>
                    <th>Articles Count</th>
                    <th>{{trans('variables.edit_table')}}</th>
                    <th>{{trans('variables.delete_table')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tags as $key => $tag)
                <tr id="{{ $tag->id }}">
                    <td>{{ $key + 1 }}</td>
                    <td>
                        {{ $tag->name ?? trans('variables.another_name')}}
                    </td>
                    <td>{{ $tag->count }}</td>
                    <td>
                        <a href="{{ route('tags.edit', $tag->id) }}">
                        <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td class="destroy-element">
                        <form action="{{ route('tags.destroy', $tag->id) }}" method="post">
                            {{ csrf_field() }} {{ method_field('DELETE') }}
                            <button type="submit" class="btn-link">
                            <a>
                            <i class="fa fa-trash"></i>
                            </a>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @foreach($zeroCountTags as $zeroTag)
                <tr id="{{ $zeroTag->id }}">
                    <td>
                        {{ $zeroTag->name ?? trans('variables.another_name')}}
                    </td>
                    <td>0</td>
                    <td>
                        <a href="{{ route('tags.edit', $zeroTag->id) }}">
                        <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td class="destroy-element">
                        <form action="{{ route('tags.destroy', $zeroTag->id) }}" method="post">
                            {{ csrf_field() }} {{ method_field('DELETE') }}
                            <button type="submit" class="btn-link">
                            <a>
                            <i class="fa fa-trash"></i>
                            </a>
                            </button>
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
