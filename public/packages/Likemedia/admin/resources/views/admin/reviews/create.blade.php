@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

  <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control panel</a></li>
          <li class="breadcrumb-item"><a href="{{ route('review.index') }}">Review</a></li>
          <li class="breadcrumb-item active" aria-current="page">Create Review</li>
      </ol>
  </nav>

  <div class="title-block">
      <h3 class="title"> Create Review </h3>
      @include('admin::admin.list-elements', [
      'actions' => [
      trans('variables.add_element') => route('review.create'),
      ]
      ])
  </div>

    <div class="list-content">
        <div class="tab-area">
            @include('admin::admin.alerts')
        </div>

        <form class="form-reg" role="form" method="POST" action="{{ route('review.store') }}" enctype="multipart/form-data">
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
            <div class="tab-content {{ $loop->first ? ' active-content' : '' }}" id={{ $lang->lang }}>
                <div class="part left-part">
                    <ul>
                        <li>
                            <label for="author-{{ $lang->lang }}">{{trans('variables.author')}}</label>
                            <input type="text" name="author_{{ $lang->lang }}" class="name"
                                id="author-{{ $lang->lang }}" value="{{old('author_'.$lang->lang)}}">
                        </li>
                        <li>
                            <label for="text-{{ $lang->lang }}">Text</label>
                            <textarea name="text_{{ $lang->lang }}"
                                id="text-{{ $lang->lang }}">{{old('text_'.$lang->lang)}}</textarea>
                        </li>
                        <li>
                            <label for="alt-{{ $lang->lang }}">Alt</label>
                            <input type="text" name="alt_{{ $lang->lang }}" class="name"
                                id="alt-{{ $lang->lang }}" value="{{old('alt_'.$lang->lang)}}">
                        </li>
                        <li>
                            <label for="title-{{ $lang->lang }}">Title</label>
                            <input type="text" name="title_{{ $lang->lang }}" class="name"
                                id="title-{{ $lang->lang }}" value="{{old('title_'.$lang->lang)}}">
                        </li>
                    </ul>
                </div>
            </div>
            @endforeach
            @endif
            <ul>
                <div class="row">
                    <div class="col-md-6">
                        <li>
                            <label for="avatar">Avatar</label>
                            <input type="file" name="avatar"/>
                        </li>
                    </div>
                </div>
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
