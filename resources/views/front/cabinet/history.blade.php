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
        <li><a href="{{route('cabinet.wishList')}}">{{trans('front.cabinet.wishList')}}</a></li>
        <li class="pageActiveCab"><a href="{{route('cabinet.history')}}">{{trans('front.cabinet.history')}}</a></li>
        <li><a href="{{route('cabinet.return')}}">{{trans('front.cabinet.return')}}</a></li>
        <li><a href="{{url($lang->lang.'/logout')}}">{{trans('front.cabinet.logout')}}</a></li>
      </ul>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-12 borderBottom">
          <h3>{{trans('front.cabinet.history')}}</h3>
        </div>
        <div class="col-lg-3 col-md-12">
          <div class="cabCat">
            <div class="sal">
              {{trans('front.cabinet.hello', ['name' => $userdata->name, 'surname' => $userdata->surname])}}
            </div>
            <ul>
              <li><a href="{{route('cabinet')}}">{{trans('front.cabinet.userdata')}}</a></li>
              <li><a href="{{route('cart')}}">{{trans('front.cabinet.cart')}}</a></li>
              <li><a href="{{route('cabinet.wishList')}}">{{trans('front.cabinet.wishList')}}</a></li>
              <li class="pageActiveCab"><a href="{{route('cabinet.history')}}">{{trans('front.cabinet.history')}}</a></li>
              <li><a href="{{route('cabinet.return')}}">{{trans('front.cabinet.return')}}</a></li>
              <li><a href="{{url($lang->lang.'/logout')}}">{{trans('front.cabinet.logout')}}</a></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-9 col-md-12 cabFormNew">
          @if (count($orders) > 0)
              @foreach ($orders as $order)
                <div class="row align-items-center historyItem">
                  <div class="col-sm-7 col-12">
                    <div class="row align-items-center">
                      <div class="col-lg-4 col-5 textGrey">
                        {{trans('front.cabinet.historyAll.id', ['id' => $order->id])}}
                      </div>
                      <div class="col-lg-8 col-7 status{{ucfirst($order->status)}} ">
                        {{trans('front.cabinet.status.'.$order->status)}}
                      </div>
                      <div class="col-12">
                        {{trans('front.cabinet.historyAll.date', ['date' => date('d/m/Y H:i:s', strtotime($order->datetime))])}},
                        <br>
                        {!!trans('front.cabinet.historyAll.total', ['amount' => $order->amount])!!}
                      </div>
                    </div>
                  </div>
                  <div class="offset-1 col-sm-1 col-3">
                    <div class="historyCartImg" data-toggle="modal" data-target="#addAgainToCart{{$order->id}}">
                      <img src="{{asset('fronts/img/icons/cartIcon.svg')}}" alt="">
                    </div>
                    <div class="modal" id="addAgainToCart{{$order->id}}">
                      <div class="modal-dialog">
                        <div class="modal-content">

                          <!-- Modal Header -->
                          <div class="modal-header">
                            <h4 class="modal-title">{{trans('front.cabinet.historyAll.addToCart')}}</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>

                          <!-- Modal body -->
                          <div class="modal-body">
                            <div class="row justify-content-center">
                              <div class="col-9">
                                <form action="{{route('cabinet.historyCart', $order->id)}}" method="post">
                                  {{ csrf_field() }}
                                  <div class="row justify-content-center">
                                    <div class="col-6">
                                        <input class="btnSubmit" type="submit" name="saveChangesCabPers" value="{{trans('front.cabinet.yes')}}">
                                    </div>
                                    <div class="col-6">
                                      <input class="btnSubmit" type="button" data-dismiss="modal" value="{{trans('front.cabinet.no')}}">
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-3 col-6">
                    <a href="{{route('cabinet.historyOrder', $order->id)}}">
                      <div class="buttonCartLogged">
                        {{trans('front.cabinet.details')}}
                      </div>
                  </a>
                  </div>
                </div>
              @endforeach
          @else
            <div class="row align-items-center historyItem">
              <div class="col-sm-7 col-12">
                {{trans('front.cabinet.historyAll.noHistory')}}
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@include('front.layouts.footer')
@stop
