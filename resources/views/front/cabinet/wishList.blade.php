@extends('front.app')
@section('content')
@include('front.layouts.header')
<div class="collectionOne registration">
  <div class="container">
    <div class="cabCat bagDate">
      <div class="sal">
        {{trans('front.cabinet.hello', ['name' => $userdata->name, 'surname' => $userdata->surname])}}
      </div>
      <ul>
        <li><a href="{{route('cabinet')}}">{{trans('front.cabinet.userdata')}}</a></li>
        <li><a href="{{route('cart')}}">{{trans('front.cabinet.cart')}}</a></li>
        <li class="pageActiveCab"><a href="{{route('cabinet.wishList')}}">{{trans('front.cabinet.wishList')}}</a></li>
        <li><a href="{{route('cabinet.history')}}">{{trans('front.cabinet.history')}}</a></li>
        <li><a href="{{route('cabinet.return')}}">{{trans('front.cabinet.return')}}</a></li>
        <li><a href="{{url($lang->lang.'/logout')}}">{{trans('front.cabinet.logout')}}</a></li>
      </ul>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-12 borderBottom">
          <h3>{{trans('front.cabinet.wishList')}}</h3>
        </div>
        <div class="col-lg-3 col-md-12">
          <div class="cabCat">
            <div class="sal">
              {{trans('front.cabinet.hello', ['name' => $userdata->name, 'surname' => $userdata->surname])}}
            </div>
            <ul>
              <li><a href="{{route('cabinet')}}">{{trans('front.cabinet.userdata')}}</a></li>
              <li><a href="{{route('cart')}}">{{trans('front.cabinet.cart')}}</a></li>
              <li class="pageActiveCab"><a href="{{route('cabinet.wishList')}}">{{trans('front.cabinet.wishList')}}</a></li>
              <li><a href="{{route('cabinet.history')}}">{{trans('front.cabinet.history')}}</a></li>
              <li><a href="{{route('cabinet.return')}}">{{trans('front.cabinet.return')}}</a></li>
              <li><a href="{{url($lang->lang.'/logout')}}">{{trans('front.cabinet.logout')}}</a></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-9 col-md-12 cabFormNew wishListBlock">
          @include('front.inc.wishListBlock')
        </div>
      </div>
    </div>
  </div>
</div>
@include('front.layouts.footer')
@stop
