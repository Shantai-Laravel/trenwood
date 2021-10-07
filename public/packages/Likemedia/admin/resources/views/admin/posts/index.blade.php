@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="page">Articole</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Articole
        @if (Request::segment(4))
            @if (!is_null($category))
                (Categoria <i>{{ $category->translation->first()->name }}</i>)
            @endif
        @endif
    </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('posts.create'),
    ]
    ])
</div>

@if(!$posts->isEmpty())
<div class="card">
    <div class="card-block">
        <table class="table table-hover table-striped" id="tablelistsorter">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{trans('variables.title_table')}}</th>
                    <th>URL</th>
                    <th>Votes</th>
                    <th>Rating</th>
                    <th>{{trans('variables.edit_table')}}</th>
                    <th>{{trans('variables.delete_table')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $key => $post)
                <tr>
                    <td>
                        {{ $key + 1 }}
                    </td>
                    <td>
                        <p>{{ $post->translation->first()->title }}</p>
                    </td>
                    <td>
                        <p>{{ $post->translation->first()->url }}</p>
                    </td>
                    <td>
                        {{ $post->votes }}
                    </td>
                    <td>
                        {{ $post->rating }}
                    </td>
                    <td>
                        <a href="{{ route('posts.edit', $post->id) }}">
                        <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td class="destroy-element">
                        <form action="{{ route('posts.destroy', $post->id) }}" method="post">
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
                    <td colspan=9>
                        {{ $posts->links() }}
                    </td>
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
