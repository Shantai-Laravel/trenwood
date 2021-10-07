@extends('front.app')
@section('content')
@include('front.layouts.header')
<div class="collectionOne search">
  <div class="container-fluid">
    <div class="row">
       <div class="col-auto">
          <div class="crumbs">
             <ul>
                <li><a href="{{url($lang->lang)}}">{{trans('front.general.homePage')}}</a> / </li>
                <li><a href="{{url($lang->lang.'/search')}}">{{trans('front.search.search')}}</a></li>
             </ul>
          </div>
       </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-12">
        <h5>{!!trans('front.search.result', ['result' => $search])!!}</h5>
      </div>
      <div class="col-xl-7 col-lg-8 col-md-10 col-sm-10 col-12">
          <form action="{{url($lang->lang.'/search')}}" method="get">
            <div class="row justify-content-center dorinCaifuet">
              <div class="col-md-6 col-7 searchInput">
                <input type="text" name="value" value="{{$search}}" placeholder="{{trans('front.search.placeholder')}}">
              </div>
              <div class="col-md-4 col-5">
                <input class="buttSilver" type="submit" value="{{trans('front.search.search')}}">
              </div>
            </div>
          </form>
          <div class="row justify-content-center">
            <div class="col-md-auto col-12 bag">
              {{trans('front.sort.sort')}}
            </div>
            <div class="col-md-auto col-12 text-center radSer">
              <label class="radioSearch">
                <input type="radio" name="radioSearch">
                <span class="checkSearch sortByLowPrice">{{trans('front.sort.low')}}</span>
              </label>
              <label class="radioSearch">
                <input type="radio" name="radioSearch">
                <span class="checkSearch sortByHighPrice">{{trans('front.sort.high')}}</span>
              </label>
              <label class="radioSearch">
                <input type="radio" name="radioSearch">
                <span class="checkSearch sortByDesc">{{trans('front.sort.new')}}</span>
              </label>
            </div>
          </div>
      </div>
    </div>
    <div class="row gallery searchBox">
        @include('front.inc.searchBox')
    </div>
  </div>
</div>
@include('front.layouts.footer')
@include('front.modals.modals')
@stop
