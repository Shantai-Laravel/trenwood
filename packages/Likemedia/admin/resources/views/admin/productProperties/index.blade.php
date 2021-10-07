@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="page">Properties</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Properties </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
        trans('variables.add_element') => route('properties.create'),
        trans('Grupe de parametri') => route('properties-groups.index'),
        ]
    ])
</div>

<div class="row">
    <div class="col-md-8">
        @if (count($groups))
            @foreach ($groups as $key => $group)
                @if(count((array)$group->properties) > 0)
                <div class="card">
                    <div class="card-block">
                        <div class="title-block">
                            <h3 class="title"> <a href="{{ route('properties-groups.edit', $group->id) }}">{{ $group->translations()->first()->name }}</a> </h3>
                        </div>

                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Key</th>
                                    <th>Type</th>
                                    <th>Group</th>
                                    <th class="text-center">Actions</th>
                                    <th class="text-center">Filter</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($group->properties as $key => $product)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>{{ $product->key }}</td>
                                    <td>{{ $product->type }}</td>
                                    <td><small>{{ $group->translations()->first()->name }}</small></td>
                                    <td class="text-center">
                                        <a href="{{ route('properties.edit', $product->id) }}"><i class="fa fa-edit"></i></a>
                                        <form action="{{ route('properties.destroy', $product->id) }}" method="post">
                                            {{ csrf_field() }} {{ method_field('DELETE') }}
                                            <button type="submit" class="btn-link destroy-element">
                                                <a><i class="fa fa-trash"></i></a>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                      <form action="{{ route('properties.makeFilter', $product->id) }}" id="makeFilter{{$product->id}}" method="post">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label>
                                                <input class="checkbox" type="checkbox" onclick="document.getElementById('makeFilter{{$product->id}}').submit();" name="filter" {{ $product->filter == 1 ? 'checked' : '' }}>
                                                <span></span>
                                            </label>
                                        </div>
                                      </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            @endforeach
        @else
        <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
        @endif

    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-block">
                <div class="title-block">
                    <h3 class="title"> Adauga un parametru </h3>
                </div>
                @include('admin::admin.alerts')
                <form method="post" action="{{ route('properties.store') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label>Key (unique)</label>
                        <input type="text" name="key" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Group</label>
                        <select name="group_id" class="form-control">
                            @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->translation()->first()->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select name="type" class="form-control type-select">
                            <option value="text">Text</option>
                            <option value="textarea">Textarea</option>
                            <option value="number">Number</option>
                            <option value="select" class="multiData">Select</option>
                            <option value="checkbox" class="multiData">Checkbox</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>
                            <input class="checkbox" type="checkbox" name="multilingual" >
                            <span>Multilingual</span>
                        </label>
                    </div>
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
                            <label>Name [{{ $lang->lang }}] </label>
                            <input type="text" name="name_{{ $lang->lang }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Unit [{{ $lang->lang }}]</label>
                            <input type="text" name="unit_{{ $lang->lang }}" class="form-control">
                        </div>

                        <div class="multiDataWrapp _{{ $lang->lang }}"><hr>
                            <div class="form-group hide to-clone" data-id="0">
                                <label> Case [{{ $lang->lang }}]</label>
                                <input type="text" name="case_{{ $lang->lang }}[]"  class="form-control"><i class="del-field fa fa-trash"></i>
                            </div>
                            <div class="form-group" data-id="1">
                                <label> Case [{{ $lang->lang }}]</label>
                                <input type="text" name="case_{{ $lang->lang }}[]"  class="form-control"><i class="del-field fa fa-trash"></i>
                            </div>
                            <div class="text-center">
                                <a class="text-warning add-field" href="#"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>

                        <div class="form-group">
                            <label> Default Value [{{ $lang->lang }}]</label>
                            <textarea name="value_{{ $lang->lang }}" class="form-control" rows="3" cols="80"></textarea>
                        </div>

                    </div>
                    @endforeach
                    @endif
                    <div class="form-group text-center">
                        <hr>
                        <input type="submit" value="Save" class="btn btn-primary form-control">
                    </div>

                    <h6>Categorii</h6>

                    <?php $property = 0; ?>
                    @include('admin::admin.productProperties.categoriesTree')
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
