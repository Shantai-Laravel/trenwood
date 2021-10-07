@extends('front.app')
@section('content')
@include('front.layouts.header')
<div class="collectionOne registration">
  <div class="container">
    <div class="row crumbs">
       <div class="col-auto">
          <ul>
            <li><a href="{{url($lang->lang)}}">{{trans('front.general.homePage')}}</a> / </li>
            <li><a href="{{url($lang->lang.'/wishList')}}">{{trans('front.wishList.wishList')}}</a></li>
          </ul>
       </div>
    </div>
    <div class="row justify-content-center">
          <div class="col-12">
             <h3>{{trans('front.wishList.wishList')}}</h3>
          </div>
          <div class="col-lg-9 col-md-12 cabFormNew wishListBlock">
              @include('front.inc.wishListBlock')
          </div>
       </div>
  </div>
</div>
@include('front.layouts.footer')
@stop
