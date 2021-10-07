@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Contol Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('properties.index') }}">Parametri</a></li>
        <li class="breadcrumb-item active" aria-current="page">Grupe de parametri</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Grupe de parametri </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
        trans('variables.add_element') => route('properties-groups.create'),
        ]
    ])
</div>

<div class="row">
    <div class="col-md-8">
        @if(count($propetyGroups))
        <div class="card">
            <div class="card-block">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($propetyGroups as $key => $propetyGroup)
                            @if ($propetyGroup->id != 14)
                            {{-- group no category --}}
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{ $propetyGroup->translation()->first()->name }}</td>
                                <td class="text-center">
                                    <a href="{{ route('properties-groups.edit', $propetyGroup->id) }}"><i class="fa fa-edit"></i></a>

                                        <form action="{{ route('properties-groups.destroy', $propetyGroup->id) }}" method="post">
                                            {{ csrf_field() }} {{ method_field('DELETE') }}
                                            <button type="submit" class="btn-link">
                                                <a><i class="fa fa-trash"></i></a>
                                            </button>
                                        </form>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
        @endif
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-block">
                <div class="title-block">
                    <h3 class="title"> Adauga un grup </h3>
                </div>
                @include('admin::admin.alerts')
                <form method="post" action="{{ route('properties-groups.store') }}">
                    {{ csrf_field() }}
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
                    <div class="tab-content {{ $loop->first ? ' active-content' : '' }}" id={{ $lang->
                        lang }}><br>
                        <div class="form-group">
                            <label>Name [{{ $lang->lang }}]</label>
                            <input type="text" name="name_{{ $lang->lang }}" class="form-control">
                        </div>
                    </div>
                    @endforeach
                    @endif
                    <div class="form-group text-center">
                        <hr>
                        <input type="submit" value="Save" class="btn btn-primary form-control">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
