@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')

@section('content')

    @include('admin::admin.speedbar')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control panel</a></li>
            <li class="breadcrumb-item active" aria-current="page">AutoMeta</li>
        </ol>
    </nav>
    <div class="title-block">
        <h3 class="title"> AutoMeta </h3>
        @include('admin::admin.list-elements', [
            'actions' => [
                trans('variables.elements_list') => route('autometa.index'),
                trans('variables.add_element') => route('autometa.create'),
            ]
        ])
    </div>

    @if(count($metas))

        <table class="el-table" id="tablelistsorter">
            <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>{{trans('variables.lang')}}</th>
                <th>{{trans('variables.title_table')}}</th>
                <th>Descrierea</th>
                <th>{{trans('variables.edit_table')}}</th>
                <th>{{trans('variables.delete_table')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($metas as $key => $meta)
                <tr id="{{ $meta->id }}">
                    <td>
                       {{ $key + 1 }}
                    </td>

                    <td>
                      @if($meta->type == 1)
                        Category
                      @else
                         Product
                      @endif
                    </td>

                    <td>
                       {{ $meta->lang }}
                    </td>

                    <td>
                       {{ $meta->name }}
                    </td>
                    <td>
                        {{ $meta->description }}
                    </td>

                    <td>
                        <a href="{{ route('autometa.edit', $meta->meta_id) }}">
                            <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td class="destroy-element">
                        <form action="{{ route('autometa.destroy', $meta->meta_id) }}" method="post">
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

    @else
        <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
    @endif


@stop

@section('footer')
    <footer>
        @include('admin::admin.footer')
    </footer>
@stop
