@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

  <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control panel</a></li>
          <li class="breadcrumb-item"><a href="{{ route('frontusers.index') }}">Front Users</a></li>
          <li class="breadcrumb-item active" aria-current="page">Create Front User</li>
      </ol>
  </nav>

  <div class="title-block">
      <h3 class="title"> Create Front User </h3>
      @include('admin::admin.list-elements', [
      'actions' => [
      trans('variables.add_element') => route('frontusers.create'),
      ]
      ])
  </div>

    @if (count($userfields) > 0)
      <div class="list-content">
          <div class="tab-area">
              @include('admin::admin.alerts')
          </div>


          <form class="form-reg" role="form" method="POST" action="{{ route('frontusers.store') }}" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="part left-part">
                  <h3>Personal Information</h3>
                  <ul>
                      @foreach ($userfields as $key => $userfield)
                          @if ($userfield->field_group == 'personaldata' || $userfield->field_group == 'company')
                              <li>
                                  <label for="{{$userfield->field}}">{{ucfirst($userfield->field)}}</label>
                                  <input type="hidden" name="userfield_id[]" value="{{$userfield->id}}">
                                  @if  ($userfield->type == 'checkbox')
                                      <input type="{{$userfield->type}}" name="{{$userfield->field}}" class="name" id="{{$userfield->field}}" {{old($userfield->field) ? 'checked': '' }}>
                                  @else
                                      <input type="{{$userfield->type}}" name="{{$userfield->field}}" class="name" id="{{$userfield->field}}" value="{{old($userfield->field)}}">
                                  @endif
                              </li>
                          @endif
                      @endforeach
                      <li>
                          <label for="password">Password</label>
                          <input type="password" name="password" class="name" id="password">
                      </li>
                      <li>
                          <label for="repeatpassword">Repeat Password</label>
                          <input type="password" name="repeatpassword" class="name" id="repeatpassword">
                      </li>
                  </ul>
              </div>
              <div class="part right-part">
                  <h3>Address Information</h3>
                  <div class="address">
                    @include('admin::admin.frontusers.address')
                  </div>
              </div>
              <ul>
                  <div class="row">
                      <div class="col-md-6">
                          <li>
                              <input type="submit" value="{{trans('variables.save_it')}}">
                          </li>
                      </div>
                  </div>
              </ul>
          </form>
      </div>
    @else
      <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
    @endif
@stop

@section('footer')
    <footer>
        @include('admin::admin.footer')
    </footer>
@stop
