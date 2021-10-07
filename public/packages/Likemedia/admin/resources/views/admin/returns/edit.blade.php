@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('returns.index') }}">Returns</a></li>
        <li class="breadcrumb-item active" aria-current="brand">Create return</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> Create return </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
      trans('variables.elements_list') => route('returns.index'),
      trans('variables.add_element') => route('returns.create'),
    ]
    ])
</div>

@include('admin::admin.alerts')

<div class="list-content">

    <div class="col-md-12">
      <div class="col-md-4">
        <label for="">Выберите клиента</label>
        <input type="hidden" name="user_id" value="{{$users[0]->id}}">
        <select class="form-control" name="users_return" data-return_id="{{!empty($return) ? $return->id : '0'}}">
          @foreach ($users as $user)
              <option value="{{$user->id}}">{{$user->name.' '.$user->surname}}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <div class="orders">
          @include('admin::admin.returns.products')
        </div>
      </div>
      <div class="col-md-4">
        <label>Выберите статус возврата</label>
        <select class="form-control" name="status_return">
            <option {{!empty($return) &&$return->status == 'new' ? 'selected' : ''}} value="new">{{trans('front.cabinet.historyOrder.new')}}</option>
            <option {{!empty($return) &&$return->status == 'processing' ? 'selected' : ''}} value="processing">{{trans('front.cabinet.historyOrder.processing')}}</option>
            <option {{!empty($return) &&$return->status == 'cancelled' ? 'selected' : ''}} value="cancelled">{{trans('front.cabinet.historyOrder.cancelled')}}</option>
            <option {{!empty($return) &&$return->status == 'completed' ? 'selected' : ''}} value="completed">{{trans('front.cabinet.historyOrder.completed')}}</option>
        </select>
      </div>
    </div>

    <br>
    <br>
    <br>
    <br>

    <div class="col-lg-6 col-md-12 cartReturn">
      @include('admin::admin.returns.cartBlock')
    </div>

    <div class="returnCreate">
      @include('admin::admin.returns.returnBlock')
    </div>
</div>

<div class="modal" id="addModalCart">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">

      </div>
    </div>
  </div>
</div>

@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
