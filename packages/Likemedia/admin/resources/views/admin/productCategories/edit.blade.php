@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('product-categories.index') }}">Categoriile Produselor</a></li>
        <li class="breadcrumb-item active" aria-current="page">Editarea Categoriei</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Editarea Categoriei </h3>
</div>
<div class="list-content">
    <div class="tab-area">
        @include('admin::admin.alerts')
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
    <form class="form-reg" method="post" action="{{ route('product-categories.update', $menuItem->id) }}" enctype="multipart/form-data">
        {{ csrf_field() }} {{ method_field('PATCH') }}
        @if (!empty($langs))
        @foreach ($langs as $key => $lang)
        <div class="tab-content {{ $key == 0 ? ' active-content' : '' }}" id={{ $lang->
            lang }}>
            <div class="part full-part">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{trans('variables.title_table')}}[{{ $lang->lang }}]</label>
                            <input type="text" name="name_{{ $lang->lang }}" class="form-control"
                            @foreach($menuItem->translations as $translation)
                            @if ($translation->lang_id == $lang->id)
                            value="{{ $translation->name }}"
                            @endif
                            @endforeach
                            >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Body[{{ $lang->lang }}]</label>
                            <input type="text" name="body_{{ $lang->lang }}" class="form-control"
                            @foreach($menuItem->translations as $translation)
                            @if ($translation->lang_id == $lang->id)
                            value="{{ $translation->body }}"
                            @endif
                            @endforeach
                            >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Slug[{{ $lang->lang }}]</label>
                        <input type="text" name="slug_{{ $lang->lang }}" class="form-control"
                        @foreach($menuItem->translations as $translation)
                        @if ($translation->lang_id == $lang->id)
                        value="{{ $translation->url }}"
                        @endif
                        @endforeach
                        >
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label>Seo Title[{{ $lang->lang }}]</label>
                        <input type="text" name="seo_title_{{ $lang->lang }}" class="form-control"
                        @foreach($menuItem->translations as $translation)
                        @if ($translation->lang_id == $lang->id)
                        value="{{ $translation->seo_title }}"
                        @endif
                        @endforeach
                        >
                    </div>
                    <div class="col-md-4">
                        <label>Seo Description[{{ $lang->lang }}]</label>
                        <input type="text" name="seo_description_{{ $lang->lang }}" class="form-control"
                        @foreach($menuItem->translations as $translation)
                        @if ($translation->lang_id == $lang->id)
                        value="{{ $translation->seo_description }}"
                        @endif
                        @endforeach
                        >
                    </div>
                    <div class="col-md-4">
                        <label>Seo Keywords[{{ $lang->lang }}]</label>
                        <input type="text" name="seo_keywords_{{ $lang->lang }}" class="form-control"
                        @foreach($menuItem->translations as $translation)
                        @if ($translation->lang_id == $lang->id)
                        value="{{ $translation->seo_keywords }}"
                        @endif
                        @endforeach
                        >
                    </div>
                    <div class="col-md-12">
                        <label>Seo Text[{{ $lang->lang }}]</label>
                        <textarea name="seo_text_{{ $lang->lang }}" class="form-control">@foreach($menuItem->translations as $translation)@if($translation->lang_id == $lang->id){{ $translation->seo_text }}@endif @endforeach</textarea>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif
        <div class="part col-md-6">
            <li>
                <label>Image</label>
                <input type="file" name="img">

                <input type="hidden" name="image_old" value="{{ $menuItem->img }}">

                @if ($translation->lang_id == $lang->id)
                    <img id="upload-img" src="/images/categories/sm/{{ $menuItem->img }}">
                @endif
            </li>
        </div>
        <div class="part col-md-12"><br>
            <div class="title-block">
                <h3 class="title"> Parametri </h3>
            </div>

            <?php $property = 0; ?>
            @include('admin::admin.productCategories.propertiesTree')

        </div>

        <div class="part full-part">
            <ul>
                <li>
                    <br><br>
                    <input type="submit" value="{{trans('variables.save_it')}}">
                </li>
            </ul>
        </div>
    </form>
</div>
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
