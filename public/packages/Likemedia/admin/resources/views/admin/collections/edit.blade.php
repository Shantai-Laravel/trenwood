@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('collections.index') }}">Collections</a></li>
        <li class="breadcrumb-item active" aria-current="collection">Edit collection</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Editarea collectiei </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
    trans('variables.add_element') => route('collections.create'),
    ]
    ])
</div>

@include('admin::admin.alerts')

<div class="list-content">
    <form class="form-reg" role="form" method="POST" action="{{ route('collections.update', $collection->id) }}" id="add-form" enctype="multipart/form-data">
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
                        @foreach($collection->translations as $translation)
                        @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                        value="{{ $translation->name }}"
                        @endif
                        @endforeach
                        >
                    </li>
                    <li>
                        <label for="description-{{ $lang->lang }}">Description [{{ $lang->lang }}]</label>
                        <textarea name="description_{{ $lang->lang }}" id="description-{{ $lang->lang }}">@foreach($collection->translations as $translation) @if($translation->lang_id == $lang->id && !is_null($translation->lang_id)){{ $translation->description }} @endif @endforeach </textarea>
                    </li>
                    <li>
                        <label for="body-{{ $lang->lang }}">Body [{{ $lang->lang }}]</label>
                        <textarea name="body_{{ $lang->lang }}" id="body-{{ $lang->lang }}">@foreach($collection->translations as $translation) @if($translation->lang_id == $lang->id && !is_null($translation->lang_id)){{ $translation->body }} @endif @endforeach </textarea>
                    </li>
                    <li>
                        @foreach($collection->translations as $translation)
                        @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                            @if ($translation->image)
                            <img src="{{ asset('images/collections/'. $translation->image ) }}" width="200px">
                            <input type="hidden" name="old_image_{{ $lang->lang }}" value="{{ $translation->image }}"/>
                            @endif
                        @endif
                        @endforeach

                        <label for="img-{{ $lang->lang }}">Image (multilingual) [{{ $lang->lang }}]</label>
                        <input type="file" name="image_{{ $lang->lang }}" id="img-{{ $lang->lang }}"/>
                    </li>
                </ul>
            </div>
            <div class="part right-part">

                <ul>
                    <hr>
                    <h6>Seo Texts</h6>
                    <li>
                        <label for="meta_title_{{ $lang->lang }}">Seo Title [{{ $lang->lang }}]</label>
                        <input type="text" name="seo_title_{{ $lang->lang }}"
                        id="seo_title_{{ $lang->lang }}"
                        @foreach($collection->translations as $translation)
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
                        @foreach($collection->translations as $translation)
                        @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                        value="{{ $translation->seo_description }}"
                        @endif
                        @endforeach
                        >
                    </li>
                    <li>
                        <label for="seo_keywords_{{ $lang->lang }}">Seo Keywords [{{ $lang->lang }}]</label>
                        <input type="text" name="seo_keywords_{{ $lang->lang }}"
                        id="seo_keywords_{{ $lang->lang }}"
                        @foreach($collection->translations as $translation)
                        @if($translation->lang_id == $lang->id && !is_null($translation->lang_id))
                        value="{{ $translation->seo_keywords }}"
                        @endif
                        @endforeach
                        >
                    </li>
                    <li>
                        <label for="seo_text_{{ $lang->lang }}">Seo text [{{ $lang->lang }}]</label>
                        <textarea  name="seo_text_{{ $lang->lang }}" id="seo_text-{{ $lang->lang }}"> @foreach($collection->translations as $translation) @if($translation->lang_id == $lang->id && !is_null($translation->lang_id)){{ $translation->seo_text }} @endif @endforeach </textarea>
                    </li>
                </ul>
            </div>
        </div>
        @endforeach
        @endif

        <ul class="part full-part">
            <li>
                <div class="row">
                    <div class="col-md-6">
                        @if ($collection->img)
                            <img src="{{ asset('images/collections/'. $collection->banner ) }}" width="200px">
                            <input type="hidden" name="old_banner" value="{{ $collection->banner }}"/>
                        @endif
                        <label for="image">Collection Banner</label>
                        <input type="file" name="banner" id="img"/>
                    </div>
                </div>
            </li>
            <li><br>
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
