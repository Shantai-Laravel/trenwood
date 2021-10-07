@extends('front.app')
@section('content')
@include('front.layouts.header')
<div class="collectionOne registration">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-sm-8 four">
        <img src="{{asset('fronts/img/icons/404.png')}}" alt="">
        <p>{{trans('front.404.title')}}</p>
        <a href="{{url($lang->lang)}}" class="btnError">{{trans('front.404.back')}}</a>
      </div>
    </div>
  </div>
</div>
@include('front.layouts.footer')
@stop
