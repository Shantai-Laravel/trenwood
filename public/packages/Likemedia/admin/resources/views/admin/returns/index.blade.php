@extends('admin::admin.app')
@include('admin::admin.nav-bar')
@include('admin::admin.left-menu')
@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/back') }}">Control Panel</a></li>
        <li class="breadcrumb-item active" aria-current="brand">Returns </li>
    </ol>
</nav>

<br>

<div class="title-block">
    <h3 class="title"> Returns </h3>
    @include('admin::admin.list-elements', [
    'actions' => [
      trans('variables.elements_list') => route('returns.index'),
      trans('variables.add_element') => route('returns.create'),
    ]
    ])
</div>

@include('admin::admin.alerts')

<div class="row filterReturns">
  <label class="radio-inline">
    <input type="radio" name="inlineRadioOptions" id="inlineRadio1" checked value="new">{{trans('front.cabinet.historyOrder.new')}}
  </label>
  <label class="radio-inline">
    <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="processing">{{trans('front.cabinet.historyOrder.processing')}}
  </label>
  <label class="radio-inline">
    <input type="radio" name="inlineRadioOptions" id="inlineRadio3" value="cancelled">{{trans('front.cabinet.historyOrder.cancelled')}}
  </label>
  <label class="radio-inline">
    <input type="radio" name="inlineRadioOptions" id="inlineRadio3" value="completed">{{trans('front.cabinet.historyOrder.completed')}}
  </label>
</div>

<hr>

<div class="row">
  <form class="form-reg" action="{{route('returns.changeAmount')}}" method="post">
    {{ csrf_field() }}
    <label class="radio-inline">
      <input type="text" name="returnAmount" id="inlineRadio3" value="{{$userfield->value}}">
      <input type="submit" name="submit" value="OK">
    </label>
  </form>

</div>

<div class="returns">
  @include('admin::admin.returns.returns')
</div>

@stop
@section('footer')
<footer>
    @include('admin::admin.footer')
</footer>
@stop
