@extends('front.app')
@section('content')
@include('front.layouts.header')

<div class="collectionOne">
  <div class="container">
    <div class="row">
       <div class="col-auto">
          <div class="crumbs">
             <ul>
                <li><a href="{{url($lang->lang)}}">{{trans('front.general.homePage')}}</a> / </li>
                <li><a href="{{url($lang->lang.'/thanks')}}">{{trans('front.thanks.page')}}</a></li>
             </ul>
          </div>
       </div>
    </div>
    <div class="thankPage row justify-content-center">
        <div class="col-12">
           <div class="row justify-content-center">
              <h6 class="col-12">
                 {{trans('front.thanks.thanks')}} {{Auth::guard('persons')->user()->name}}!
              </h6>
           </div>
           <div class="row">
              <div class="col-12">
                 <div class="row justify-content-center blocComm">
                    <div class="col-12">
                       <h3>{{trans('front.thanks.gift')}}</h3>
                    </div>
                    <div class="col-md-9 col-12">
                       <p>
                          {{trans('front.thanks.promocode', ["name" => $promocode->name, "treshold" => $promocode->treshold, "date" => date("j F Y", strtotime($promocode->valid_to)), "discount" => $promocode->discount])}}
                       </p>
                    </div>
                    <div class="col-lg-4 col-md-6 col-10">
                          <a class="buttSilver redirectWithPromo" href="{{url($lang->lang.'/promocode/'.$promocode->id)}}">{{trans('front.thanks.discount', ["discount" => $promocode->discount])}}</a>
                    </div>
                 </div>
              </div>
           </div>
           <div class="row plscheck justify-content-center">
              <div class="col-12">
                 <h5 class="text-center">{{trans('front.thanks.new')}}</h5>
              </div>
              <div class="col-md-4 col-6">
                    <a class="buttonCartLogged" href="{{url($lang->lang)}}">{{trans('front.thanks.back')}}</a>
              </div>
              <div class="col-md-4 col-6">
                <a class="btnTransparent" href="{{url($lang->lang.'/catalog')}}">{{trans('front.thanks.viewProducts')}}</a>
              </div>
           </div>
        </div>
     </div>
  </div>
  @if (count($collections) > 0)
    <div class="collectionSlide">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12 titleSlide">
            {{trans('front.thanks.collections')}}
          </div>
          <div class="col-12">
            <div class="slideColl">
              @foreach ($collections as $collection)
                @foreach ($collection->sets as $set)
                  <div class="slideIt">
                    @if ($set->photo()->first())
                      <img src="/images/sets/og/{{ $set->photo()->first()->src }}" alt="">
                    @else
                        <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
                    @endif
                    <div class="onHover">
                      <div class="addSetToWish {{$set->inWishList ? 'addedWishList' : ''}}" data-set_id="{{$set->id}}">{{trans('front.general.addToWish')}}</div>
                      <div class="titleOnHover">{{$set->translationByLanguage($lang->id)->first()->name}}</div>
                      <div class="row price priceProduct">
                        <div class="col-auto">{{$set->price}} {{trans('front.general.currency')}}</div>
                        @if ($set->discount > 0)
                            <div class="col-auto reduce">{{$set->price - ($set->price * $set->discount / 100)}} {{trans('front.general.currency')}}</div>
                        @endif
                      </div>
                      <a href="#" class="buttSilver">{{trans('front.general.viewSet')}}</a>
                      <a class="buttViewCollection" href="{{url($lang->lang.'/catalog/'.$set->alias)}}">{{trans('front.general.viewSet')}}</a>
                    </div>
                  </div>
                @endforeach
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>
@include('front.layouts.footer')
@stop
