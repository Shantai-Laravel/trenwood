@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Orders</a></li>
        <li class="breadcrumb-item active" aria-current="brand">Edit order</li>
    </ol>
</nav>
<div class="title-block">
    <h3 class="title"> {{trans('front.admin.createOrder')}} </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
      trans('variables.elements_list') => route('order.index'),
      trans('variables.add_element') => route('order.create'),
    ]
    ])
</div>

@include('admin::admin.alerts')

<div class="list-content">
    <div class="col-lg-7 col-md-12">
      <label for="">{{trans('front.admin.chooseClient')}}</label>
      <select class="form-control" name="users">
          @if (count($users) > 0)
              @foreach ($users as $user)
                  <option value="{{$user->id}}">{{$user->name.' '.$user->surname}}</option>
              @endforeach
          @endif
      </select>
      <hr>
      <input type="hidden" name="user_id" value="{{$frontuser->id}}">
      <input type="text" placeholder="{{trans('front.cart.cod')}}: 0524026" value="" class="form-control artProdus" style="max-width: 50%">
      <a href="javascript:void(0)" class="searchProductByCode">
        <div class="main-button">{{trans('front.general.addToCart')}}</div>
      </a>
    </div>

    <div class="col-lg-7 col-md-12 cartHere">
      @include('admin::admin.orders.cartBlockCreate')
    </div>

    <div class="orderCreate col-md-5">
      @include('admin::admin.orders.orderBlock')
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
