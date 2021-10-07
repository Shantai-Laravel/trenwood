@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
            <li class="breadcrumb-item"><a href="{{ route('properties.index') }}">Properties</a></li>
            <li class="breadcrumb-item active" aria-current="page">Create Property</li>
        </ol>
    </nav>
    <div class="title-block">
        <h3 class="title"> Create Property </h3>
        @include('admin::admin.list-elements', [
        'actions' => [
        trans('variables.add_element') => route('properties.create'),
        ]
        ])
    </div>

<div class="list-content">
    <form class="form-reg" method="POST" action="{{ route('properties.store') }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row">
            @include('admin::admin.alerts')
            <div class="col-md-4">

                    <ul>
                        <li>
                            <label>Key (unique)</label>
                            <input type="text" name="key">
                        </li>
                    </ul>

            </div>
            <div class="col-md-4">
                <ul>
                    <li>
                        <label>Group</label>
                        <select name="group_id">
                            @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->translation()->first()->name }}</option>
                            @endforeach
                        </select>
                    </li>
                </ul>
            </div>
            <div class="col-md-4">
                <div class="col-md-6">
                    <ul>
                        <li>
                            <label>Type</label>
                            <select name="type" class="type-select">
                                <option value="text">Text</option>
                                <option value="textarea">Textarea</option>
                                <option value="number">Number</option>
                                <option value="select" class="multiData">Select</option>
                                <option value="checkbox" class="multiData">Checkbox</option>
                            </select>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <div class="form-group"><br><br>
                        <label>
                            <input class="checkbox" type="checkbox" name="multilingual">
                            <span>Multilingual</span>
                        </label>
                    </div>
                </div>
            </div>
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
            lang }}>
            <div class="part col-md-4">
                <ul>
                    <li>
                        <label>Name [{{ $lang->lang }}]</label>
                        <input type="text" name="name_{{ $lang->lang }}">
                    </li>
                </ul>
            </div>
            <div class="part col-md-4">
                <ul>
                    <li>
                        <label>Unit [{{ $lang->lang }}]</label>
                        <input type="text" name="unit_{{ $lang->lang }}">
                    </li>
                </ul>
            </div>
            <div class="part col-md-4">
                <ul>
                    <li>
                        <label> Default Value [{{ $lang->lang }}]</label>
                        <input type="text" name="value_{{ $lang->lang }}">
                    </li>
                </ul>
            </div>
            <div class="col-md-12  white-bg">
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
            </div>
        </div>
        @endforeach
        @endif
        <div class="part col-md-12"><br>
            <div class="title-block">
                <h3 class="title"> Categorii </h3>
            </div>

            <?php $property = 0; ?>
            @include('admin::admin.productProperties.categoriesTree')

        </div>
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
