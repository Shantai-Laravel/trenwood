@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

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

        <form class="form-reg" method="POST" action="{{ route('metas.update') }}">
            {{ csrf_field() }} {{ method_field('PATCH') }}

            @if (!empty($langs))


                @foreach ($langs as $lang)

                    <div class="tab-content {{ $loop->first ? ' active-content' : '' }}" id={{ $lang->lang }}>

                        <div class="part full-part">
                            <ul>

                                <h6>Seo</h6>
                                <li>
                                    <label>{{trans('variables.meta_title_page')}}</label>
                                    <input type="text" name="title_{{ $lang->lang }}"
                                        @foreach($metas->translations as $translation)
                                           @if ($translation->lang_id == $lang->id)
                                                value="{{ $translation->title }}"
                                            @endif
                                            @endforeach
                                    >
                                </li>
                                <li>
                                    <label>{{trans('variables.meta_keywords_page')}}</label>
                                    <input type="text" name="keywords_{{ $lang->lang }}"
                                         @foreach($metas->translations as $translation)
                                           @if ($translation->lang_id == $lang->id)
                                                value="{{ $translation->keywords }}"
                                            @endif
                                            @endforeach
                                    >
                                </li>
                                <li>
                                    <label>{{trans('variables.meta_description_page')}}</label>
                                    <input type="text" name="description_{{ $lang->lang }}"
                                         @foreach($metas->translations as $translation)
                                           @if ($translation->lang_id == $lang->id)
                                                value="{{ $translation->description }}"
                                            @endif
                                            @endforeach
                                    >
                                </li>
                            </ul>

                                <input type="submit" value="{{trans('variables.save_it')}}">


                        </div>
                    </div>
                @endforeach
            @endif

        </form>
    </div>

@stop

@section('footer')
    <footer>
        @include('admin::admin.footer')
    </footer>
@stop
