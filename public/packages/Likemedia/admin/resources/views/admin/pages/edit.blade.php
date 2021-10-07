@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('pages.index') }}">Pagini</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit page</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Editarea page </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('pages.create'),
    ]
    ])
</div>
@include('admin::admin.alerts')
<div class="list-content">
    <form class="form-reg" role="form" method="POST" action="{{ route('pages.update', $page->id) }}" id="add-form" enctype="multipart/form-data">
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
                        <input type="text" name="title_{{ $lang->lang }}" class="name"
                        id="title-{{ $lang->lang }}" data-lang="{{ $lang->lang }}"
                        @foreach($page->translations as $translation)
                        @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                        value="{{ $translation->title }}"
                        @endif
                        @endforeach
                        >
                    </li>
                    <li class="ckeditor">
                        <label for="body-{{ $lang->lang }}">{{trans('variables.body')}} [{{ $lang->lang }}]</label>
                        <textarea name="body_{{ $lang->lang }}" id="body-{{ $lang->lang }}"
                            data-type="ckeditor">
                                        @foreach($page->translations as $translation)
                                            @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                                                {!! $translation->body !!}
                                            @endif
                                        @endforeach
                                    </textarea>
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
                        <input type="text" name="slug_{{ $lang->lang }}" class="slug"
                        id="slug-{{ $lang->lang }}"
                        @foreach($page->translations as $translation)
                        @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                        value="{{ $translation->slug }}"
                        @endif
                        @endforeach
                        >
                    </li>
                    <li>
                        <input type="submit" value="{{trans('variables.save_it')}}" data-form-id="add-form">
                    </li>
                </ul>
                <ul>
                    <hr>
                    <h6>Seo тексты</h6>
                    <li>
                        <label for="meta_title_{{ $lang->lang }}">{{trans('variables.meta_title_page')}} [{{ $lang->lang }}]</label>
                        <input type="text" name="meta_title_{{ $lang->lang }}"
                        id="meta_title_{{ $lang->lang }}"
                        @foreach($page->translations as $translation)
                        @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                        value="{{ $translation->meta_title }}"
                        @endif
                        @endforeach
                        >
                    </li>
                    <li>
                        <label for="meta_keywords_{{ $lang->lang }}">{{trans('variables.meta_keywords_page')}} [{{ $lang->lang }}]</label>
                        <input type="text" name="meta_keywords_{{ $lang->lang }}"
                        id="meta_keywords_{{ $lang->lang }}"
                        @foreach($page->translations as $translation)
                        @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                        value="{{ $translation->meta_keywords }}"
                        @endif
                        @endforeach
                        >
                    </li>
                    <li>
                        <label for="meta_description_{{ $lang->lang }}">{{trans('variables.meta_description_page')}} [{{ $lang->lang }}]</label>
                        <input type="text" name="meta_description_{{ $lang->lang }}"
                        id="meta_description_{{ $lang->lang }}"
                        @foreach($page->translations as $translation)
                        @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                        value="{{ $translation->meta_description }}"
                        @endif
                        @endforeach
                        >
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
                        <input type="text" name="alias" class="form-control" id="alias" value="{{ $page->alias }}"/>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="alias">Gallery</label>
                        <select class="form-control" name="gallery_id">
                        @if (!empty($galleries))
                        @foreach ($galleries as $key => $gallery)
                        <option {{ $gallery->id == $page->gallery_id ? 'selected' : '' }} value="{{ $gallery->id }}">{{ $gallery->alias }}</option>
                        @endforeach
                        @endif
                        </select>
                    </div>
                </div>
            </div>
            <h4 class="text-center">Traduceri</h4>

            <input type="hidden" name="page_id" value="{{ $page->id }}">
            @if (!empty($langs))
            @foreach ($langs as $lang)
            <div class="col-md-6">
                <div class="multiDataWrapp show" >
                    <br>
                    <div class="form-group hide to-clone" data-id="0">
                        <label> Traduction  [{{ $lang->lang }}] <span class="label-nr">#1</span> </label>
                        <textarea name="case_{{ $lang->lang }}[]"  class="form-control small-text"></textarea>
                    </div>
                    @if (count($translations) > 0)
                    @foreach ($translations as $key => $multidata)
                    <div class="form-group" data-id="{{ $multidata->number }}">
                        <label> Traduction  [{{ $lang->lang }}] <span class="label-nr">#{{ $multidata->number }}</span></label>
                        <textarea type="text" name="case_{{ $lang->lang }}[{{ $multidata->number }}]"  class="form-control small-text">{{ $multidata->translationByLanguage($lang->id, $multidata->id)->value }}</textarea></i>
                    </div>
                    <hr>
                    @endforeach
                    @else
                    <div class="form-group" data-id="1">
                        <label> Traduction [{{ $lang->lang }}] <span class="label-nr">#1</span></label>
                        <textarea name="case_{{ $lang->lang }}[]"  class="form-control small-text"></textarea>
                    </div>
                    @endif
                    <div class="text-center">
                        <a class="text-warning add-field" href="#"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
            <ul>
                <li class="text-center">
                    <hr>
                    <input type="submit" class="btn btn-primary" value="{{trans('variables.save_it')}}" data-form-id="add-form">
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
