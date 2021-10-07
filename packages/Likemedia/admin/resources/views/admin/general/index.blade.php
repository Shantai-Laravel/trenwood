@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="page">General settings</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> General settings </h3>
</div>

<div class="list-content">
    <form class="form-reg" role="form" method="POST" action="{{ route('general.updateMenu') }}">
        {{ csrf_field() }}
        <ul>
            <div class="form-group">
                <li>
                    <input type="checkbox" name="changeCategory" {{ $changeMenu ? 'checked' : ''}}>Modificarea structurii meniului
                </li>
            </div>
        </ul>
        <input type="submit" value="Salveaza">
    </form>
</div>

<div class="col-md-6">
  <div class="card">
      <div class="card-block">
          <div class="title-block">
              <h3 class="title"> Adauga un parametru multilang </h3>
          </div>
          <form method="post" action="{{ route('general.updateSettings') }}">
              {{ csrf_field() }}
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

                  @if (count($generals) > 0)
                      @foreach ($generals as $general)
                          <div class="multiDataWrapp _{{ $lang->lang }}" style="display: block">
                              <div class="form-group">
                                  <label> {{$general->name}} [{{ $lang->lang }}]</label>
                              </div>

                              <input type="hidden" name="id[{{$general->id}}]" value="">

                              <div class="form-group">
                                  <label>Name</label>
                                  <input type="text" name="name_{{ $lang->lang }}[]" value="{{!empty($general->translationByLanguage($lang->id)->first()) ? $general->translationByLanguage($lang->id)->first()->name : ''}}"  class="form-control">
                              </div>

                              <div class="form-group">
                                  <label>Body</label>
                                  <textarea name="body_{{ $lang->lang }}[]" rows="5" cols="100">{{!empty($general->translationByLanguage($lang->id)->first()) ? $general->translationByLanguage($lang->id)->first()->body : ''}}</textarea>
                              </div>

                              <div class="form-group">
                                  <label>Description</label>
                                  <textarea name="description_{{ $lang->lang }}[]" rows="5" cols="100">{{!empty($general->translationByLanguage($lang->id)->first()) ? $general->translationByLanguage($lang->id)->first()->description : ''}}</textarea>
                              </div>

                          </div>
                      @endforeach
                  @endif

              </div>
              @endforeach
              @endif
              <div class="form-group text-center">
                  <hr>
                  <input type="submit" value="Save" class="btn btn-primary form-control">
              </div>
          </form>
      </div>
  </div>
</div>
@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
