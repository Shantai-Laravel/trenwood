@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="brand">Orders </li>
    </ol>
</nav>

<br>

<div class="title-block">
    <h3 class="title"> Orders </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
      trans('variables.elements_list') => route('order.index'),
      trans('variables.add_element') => route('order.create'),
    ]
    ])
</div>

@include('admin::admin.alerts')

<div class="row filterOrders">
  <label class="radio-inline radioPending">
    <input type="radio" name="inlineRadioOptions" id="inlineRadio1" checked value="pending">{{trans('front.cabinet.historyOrder.pending')}}
  </label>
  <label class="radio-inline radioProcessing">
    <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="processing">{{trans('front.cabinet.historyOrder.processing')}}
  </label>
  <label class="radio-inline radioInway">
    <input type="radio" name="inlineRadioOptions" id="inlineRadio3" value="inway">{{trans('front.cabinet.historyOrder.inway')}}
  </label>
  <label class="radio-inline radioCompleted">
    <input type="radio" name="inlineRadioOptions" id="inlineRadio3" value="completed">{{trans('front.cabinet.historyOrder.completed')}}
  </label>
</div>

<div class="orders">
  @include('admin::admin.orders.orders')
</div>

@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
