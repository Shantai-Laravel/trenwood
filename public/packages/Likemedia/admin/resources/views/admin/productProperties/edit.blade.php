@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
            <li class="breadcrumb-item"><a href="{{ route('properties.index') }}">Properties</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Property</li>
        </ol>
    </nav>
    <div class="title-block">
        <h3 class="title"> Editarea Property </h3>
        @include('admin::admin.list-elements', [
        'actions' => [
        trans('variables.add_element') => route('properties.create'),
        ]
        ])
    </div>

<div class="list-content">
    <form class="form-reg" method="POST" action="{{ route('properties.update', $property->id) }}" enctype="multipart/form-data">
        {{ csrf_field() }} {{ method_field('PATCH') }}
        <div class="row">
            <div class="col-md-4">
                <ul>
                    <li>
                        <label>Key (unique)</label>
                        <input type="text" name="key" value="{{ $property->key }}">
                    </li>
                </ul>
            </div>
            <div class="col-md-4">
                <ul>
                    <li>
                        <label>Group</label>
                        <select name="group_id">
                            <option value="0">Fara grupa</option>
                            @foreach($groups as $group)
                            <option value="{{ $group->id }}" {{ $property->group_id == $group->id ? 'selected' : ''}}>{{ $group->translation()->first()->name }}</option>
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
                                <option {{ $property->type == 'text' ? 'selected' : ''}} value="text">Text</option>
                                <option {{ $property->type == 'textarea' ? 'selected' : ''}} value="textarea">Textarea</option>
                                <option {{ $property->type == 'number' ? 'selected' : ''}} value="number">Number</option>
                                <option {{ $property->type == 'select' ? 'selected' : ''}} value="select" class="multiData">Select</option>
                                <option {{ $property->type == 'checkbox' ? 'selected' : ''}} value="checkbox" class="multiData">Checkbox</option>
                            </select>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <div class="form-group"><br><br>
                        <label>
                            <input class="checkbox" type="checkbox" name="multilingual" {{ $property->multilingual == 1 ? 'checked' : ''}}>
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
        <div class="tab-content {{ $loop->first ? ' active-content' : '' }}" id={{ $lang->lang }}>
            <div class="part col-md-4">
                <ul>
                    <li>
                        <label>Name [{{ $lang->lang }}]</label>
                        <input type="text" name="name_{{ $lang->lang }}" data-lang="{{ $lang->lang }}" required
                        @foreach($property->translations as $translation)
                        @if ($translation->lang_id == $lang->id)
                        value="{{ $translation->name }}"
                        @endif
                        @endforeach
                        >
                    </li>
                </ul>
            </div>
            <div class="part col-md-4">
                <ul>
                    <li>
                        <label> Unit [{{ $lang->lang }}]</label>
                        <input type="text" name="unit_{{ $lang->lang }}"
                        @foreach($property->translations as $translation)
                        @if ($translation->lang_id == $lang->id)
                        value="{{ $translation->unit }}"
                        @endif
                        @endforeach
                        >
                    </li>
                </ul>
            </div>
            <div class="part col-md-4">
                <ul>
                    <li>
                        <label> Default Value [{{ $lang->lang }}]</label>
                        <input type="text" name="value_{{ $lang->lang }}" data-lang="{{ $lang->lang }}"
                        @foreach($property->translations as $translation)
                        @if ($translation->lang_id == $lang->id)
                        @if (is_array(json_decode($translation->value)))
                            value="{{ implode(',', json_decode($translation->value)) }}"
                        @else
                            value="{{ $translation->value }}"
                        @endif
                        @endif
                        @endforeach
                        >
                    </li>
                </ul>
            </div>
            <div class="col-md-12  white-bg">
                <div class="multiDataWrapp {{ (($property->type == 'select') || ($property->type == 'checkbox') ) ? 'show' : '' }}" ><hr>
                    <div class="form-group hide to-clone" data-id="0">
                        <label> Case [{{ $lang->lang }}]</label>
                        <input type="text" name="case_{{ $lang->lang }}[]"  class="form-control"><i class="del-field fa fa-trash"></i>
                    </div>

                    @if (count($multidatas) > 0)
                        @foreach ($multidatas as $key => $multidata)
                            <div class="form-group" data-id="{{ $multidata->id }}">
                                <label> Case [{{ $lang->lang }}]</label>
                                <input type="text" name="case_{{ $lang->lang }}[{{ $multidata->id }}]"  class="form-control" value="{{ $multidata->translationByLanguage($lang->id)->first()->value }}"><i class="del-field fa fa-trash"></i>
                            </div>
                        @endforeach
                    @else
                        <div class="form-group" data-id="1">
                            <label> Case [{{ $lang->lang }}]</label>
                            <input type="text" name="case_{{ $lang->lang }}[]"  class="form-control"><i class="del-field fa fa-trash"></i>
                        </div>
                    @endif
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

            <?php $property = $property->id; ?>
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
