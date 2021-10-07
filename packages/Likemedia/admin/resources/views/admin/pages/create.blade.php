@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('pages.index') }}">Pagini</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create Page</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Create Page </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('pages.create'),
    ]
    ])
</div>
@include('admin::admin.alerts')
<div class="list-content">
    <form class="form-reg" role="form" method="POST" action="{{ route('pages.store') }}" id="add-form"
        enctype="multipart/form-data">
        {{ csrf_field() }}
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
                        <input type="text" name="title_{{ $lang->lang }}" class="name"
                            id="title-{{ $lang->lang }}" data-lang="{{ $lang->lang }}">
                    </li>
                    <li class="ckeditor">
                        <label for="body-{{ $lang->lang }}">{{trans('variables.body')}} [{{ $lang->lang }}]</label>
                        <textarea name="body_{{ $lang->lang }}" id="body-{{ $lang->lang }}"
                            data-type="ckeditor"></textarea>
                        <script>
                            CKEDITOR.replace('body-{{ $lang->lang }}', {
                                language: '{{$lang}}',
                            });
                        </script>
                    </li>
                </ul>
            </div>
            <div class="part right-part">
                <ul>
                    <li>
                        <label for="slug-{{ $lang->lang }}">Slug [{{ $lang->lang }}]</label>
                        <input type="text" name="slug_{{ $lang->lang }}" class="slug" id="slug-{{ $lang->lang }}">
                    </li>
                </ul>
                <ul>
                    <hr>
                    <h6>Seo тексты</h6>
                    <li>
                        <label for="meta_title_{{ $lang->lang }}">{{trans('variables.meta_title_page')}} [{{ $lang->lang }}]</label>
                        <input type="text" name="meta_title_{{ $lang->lang }}"
                            id="meta_title_{{ $lang->lang }}">
                    </li>
                    <li>
                        <label for="meta_keywords_{{ $lang->lang }}">{{trans('variables.meta_keywords_page')}} [{{ $lang->lang }}]</label>
                        <input type="text" name="meta_keywords_{{ $lang->lang }}"
                            id="meta_keywords_{{ $lang->lang }}">
                    </li>
                    <li>
                        <label for="meta_description_{{ $lang->lang }}">{{trans('variables.meta_description_page')}} [{{ $lang->lang }}]</label>
                        <input type="text" name="meta_description_{{ $lang->lang }}"
                            id="meta_description_{{ $lang->lang }}">
                    </li>
                </ul>
                <ul>
                    <hr>
                    <h6>Дополнительно</h6>
                    <li>
                        <label for="img-{{ $lang->lang }}">{{trans('variables.img')}} [{{ $lang->lang }}]</label>
                        <input type="file" name="image_{{ $lang->lang }}" id="img-{{ $lang->lang }}"/>
                    </li>
                </ul>
            </div>
        </div>
        @endforeach
        @endif
        <div class="list-content white-bg">
            <div class="row"><br>
                <div class="col-md-12">
                    <div class="col-md-6 form-group">
                        <label for="alias">Alias *(must be unique)</label>
                        <input type="text" name="alias" class="form-control" id="alias"/>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="alias">Gallery</label>
                        <select class="form-control" name="gallery_id">
                            @if (!empty($galleries))
                            @foreach ($galleries as $key => $gallery)
                            <option value="{{ $gallery->id }}">{{ $gallery->alias }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-12">
                        <li>
                            <input type="submit" value="{{trans('variables.save_it')}}" data-form-id="add-form">
                        </li>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
