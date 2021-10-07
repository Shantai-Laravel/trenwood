@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('brands.index') }}">Brands</a></li>
        <li class="breadcrumb-item active" aria-current="brand">Edit brand</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Editarea brand </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('brands.create'),
    ]
    ])
</div>

@include('admin::admin.alerts')

<div class="list-content">
    <form class="form-reg" role="form" method="POST" action="{{ route('brands.update', $brand->id) }}" id="add-form" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}

        <div class="tab-area">
            <ul class="nav nav-tabs nav-tabs-bordered">
                @if (!empty($langs))
                @foreach ($langs as $lang)
                <li class="nav-item">
                    <a href="#{{ $lang->lang }}" class="nav-link  {{ $loop->first ? ' open active' : '' }}"
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
            <div class="part left-part">
                <ul>
                    <li>
                        <label for="name-{{ $lang->lang }}">{{trans('variables.title_table')}} [{{ $lang->lang }}]</label>
                        <input type="text" name="title_{{ $lang->lang }}"
                        id="title-{{ $lang->lang }}"
                        @foreach($brand->translations as $translation)
                        @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                        value="{{ $translation->name }}"
                        @endif
                        @endforeach
                        >
                    </li>
                    
                    <li>
                        <label for="descr-{{ $lang->lang }}">Description [{{ $lang->lang }}]</label>
                        <textarea name="description_{{ $lang->lang }}" id="descr-{{ $lang->lang }}"> @foreach($brand->translations as $translation) @if($translation->lang_id == $lang->id && !is_null($translation->lang_id)){{ $translation->description }} @endif @endforeach </textarea>
                    </li>
                    <li>
                        <label for="seo_text_{{ $lang->lang }}">{{trans('variables.meta_title_page')}} [{{ $lang->lang }}]</label>
                        <textarea  name="seo_text_{{ $lang->lang }}" id="seo_text-{{ $lang->lang }}"> @foreach($brand->translations as $translation) @if($translation->lang_id == $lang->id && !is_null($translation->lang_id)){{ $translation->seo_text }} @endif @endforeach </textarea>
                    </li>
                </ul>
            </div>
            <div class="part right-part">

                <ul>
                    <hr>
                    <h6>Seo тексты</h6>
                    <li>
                        <label for="meta_title_{{ $lang->lang }}">Seo Title [{{ $lang->lang }}]</label>
                        <input type="text" name="seo_title_{{ $lang->lang }}"
                        id="seo_title_{{ $lang->lang }}"
                        @foreach($brand->translations as $translation)
                        @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                        value="{{ $translation->seo_title }}"
                        @endif
                        @endforeach
                        >
                    </li>
                    <li>
                        <label for="seo_descr_{{ $lang->lang }}">Seo Description [{{ $lang->lang }}]</label>
                        <input type="text" name="seo_descr_{{ $lang->lang }}"
                        id="seo_descr_{{ $lang->lang }}"
                        @foreach($brand->translations as $translation)
                        @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                        value="{{ $translation->seo_descr }}"
                        @endif
                        @endforeach
                        >
                    </li>
                    <li>
                        <label for="seo_keywords_{{ $lang->lang }}">Seo Keywords [{{ $lang->lang }}]</label>
                        <input type="text" name="seo_keywords_{{ $lang->lang }}"
                        id="seo_keywords_{{ $lang->lang }}"
                        @foreach($brand->translations as $translation)
                        @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                        value="{{ $translation->seo_keywords }}"
                        @endif
                        @endforeach
                        >
                    </li>
                </ul>
                <ul>
                    <hr>
                    <h6>Дополнительно</h6>
                    <div class="row">
                        <li>
                            @foreach($brand->translations as $translation)
                            @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                @if ($translation->banner)
                                <img src="{{ asset('images/brands/'. $translation->banner ) }}" width="200px">
                                <input type="hidden" name="old_image_{{ $lang->lang }}" value="{{ $translation->banner }}"/>
                                @endif
                            @endif
                            @endforeach

                            <label for="img-{{ $lang->lang }}">{{trans('variables.img')}} [{{ $lang->lang }}]</label>
                            <input type="file" name="image_{{ $lang->lang }}" id="img-{{ $lang->lang }}"/>
                        </li>
                    </div>
                </ul>
            </div>
        </div>
        @endforeach
        @endif

        <div class="row">
            <div class="col-md-4">
                <ul>
                    <li>
                        <label>Parent Brand</label>
                        <select name="parent_id">
                            <option value="0">--</option>
                        @foreach($allBrands as $oneBrand)
                            <option {{ $oneBrand->id == $brand->parent_id ? 'selected' : '' }} value="{{ $oneBrand->id }}">{{ $oneBrand->translation()->first()->name }}</option>
                        @endforeach
                        </select>
                    </li>
                </ul>
            </div>
        </div>
        <ul>
            <li>
                <div class="row">
                    <div class="col-md-6">
                        @if ($brand->img)
                            <img src="{{ asset('images/brands/'. $brand->img ) }}" width="200px">
                            <input type="hidden" name="logo_old" value="{{ $brand->img }}"/>
                        @endif
                        <label for="img">Brand Logo</label>
                        <input type="file" name="logo" id="img"/>
                    </div>
                    <div class="col-md-6">
                        @if ($brand->picture)
                            <img src="{{ asset('images/brands/'. $brand->picture ) }}" width="200px">
                            <input type="hidden" name="picture_old" value="{{ $brand->picture }}"/>
                        @endif
                        <label for="img">Brand Picture</label>
                        <input type="file" name="picture" id="img"/>
                    </div>
                </div>
            </li>
            <li>
                <input type="submit" value="{{trans('variables.save_it')}}">
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
