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
        <div class="col-lg-9 col-md-12 cabFormNew historyOneOpen">
          <div class="row borders">
            <div class="col-12">
              <h5>{{trans('front.cabinet.historyAll.idDetails', ['id' => $order->id])}}</h5>
            </div>
          </div>
          <div class="row borders">
            <div class="col-12 textGrey">
              {{trans('front.cabinet.historyAll.secondStatus', ['id' => $order->id, 'status' => $order->secondarystatus])}}
            </div>
          </div>
          <div class="row borders">
            <div class="col-sm-4 col-12">
              <div class="textGreyUp">
                {{trans('front.cabinet.historyAll.deliveryDetails')}}
              </div>
              <ul>
                <li>{{$userdata->name}} {{$userdata->surname}},</li>
                <li>{{$userdata->phone}}, {{$userdata->email}}</li>
              </ul>
            </div>
            <div class="col-sm-4 col-12">
              <div class="textGreyUp">
                {{trans('front.cabinet.historyAll.deliveryFactory')}}
              </div>
              <ul>
                @if (count($order->addressById()->first()) > 0)
                    <?php $address = $order->addressById()->first(); ?>
                    <li>  {{$address->getCountryById()->first() ? $address->getCountryById()->first()->name.',' : ''}}
                          {{$address->getRegionById()->first() ? $address->getRegionById()->first()->name.',' : ''}}
                          {{$address->getCityById()->first() ? $address->getCityById()->first()->name.',' : ''}}
                          {{$address->address}}</li>
                @else
                    <?php $address = $order->addressPickupById()->first(); ?>
                    @if (!is_null($address))
                        <li>{{$address->value}}</li>
                    @endif
                @endif
              </ul>
            </div>
            <div class="col-sm-4 col-12">
              <div class="textGreyUp">
                {{trans('front.cabinet.historyAll.payment')}}
              </div>
              <ul>
                <li>{{$order->payment}}</li>
                <li>{{trans('front.cabinet.historyAll.paymentTotal', ['amount' => $order->amount])}}</li>
              </ul>
            </div>
          </div>
          <div class="row borders">
            <div class="col-12">
              <h5>{{trans('front.cabinet.historyAll.status')}}</h5>
            </div>
          </div>
          <div class="row borders">
            <div class="col-12">
                <div class="row padLit">
                  <div class="col-12 emptyBox">
                    <div class="fillBox{{ucfirst($order->status)}}">
                    </div>
                  </div>
                </div>
              </div>
            <div class="col-md-3 col-sm-6 col-8 comands">
              <div class="{{$order->status == 'pending' ? 'comandaPlasataActive' : 'comandaPlasata'}}">
                25% <br><strong>{{trans('front.cabinet.status.pending')}}</strong>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-8 comands">
              <div class="{{$order->status == 'processing' ? 'comandaInProcesareActive' : 'comandaInProcesare'}}">
                50% <br><strong>{{trans('front.cabinet.status.processing')}}</strong>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-8 comands">
              <div class="{{$order->status == 'inway' ? 'comandaInLivrareActive' : 'comandaInLivrare'}}">
                75% <br><strong>{{trans('front.cabinet.status.inway')}}</strong>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-8 comands">
              <div class="{{$order->status == 'completed' ? 'comandaLivrataActive' : 'comandaLivrata'}}">
                100% <br><strong>{{trans('front.cabinet.status.completed')}}</strong>
              </div>
            </div>
          </div>
          <div class="row borders">
            <div class="col-12">
              <h5>{{trans('front.cabinet.historyAll.products')}}</h5>
            </div>
          </div>

            @if (count($order->orderSets) > 0)
                @foreach ($order->orderSets as $orderSet)
                  <div class="row borders">
                    <div class="col-12">
                      <div class="row oneSetHistory">
                        <div class="historyImgItem col-sm-2 col-3">
                          @if ($orderSet->set()->first())
                          <img src="/images/sets/og/{{ $orderSet->set()->first()->withoutBack()->first()->src }}" alt="">
                          @else
                          <img src="{{ asset('/images/no-image.png') }}" alt="">
                          @endif
                        </div>
                        <div class="col-lg-7 col-md-5 col-sm-5 col-9 band">
                          <div class="namSetRetur">
                            {{ $orderSet->set->translationByLanguage($lang->id)->first()->name }}  {!!trans('front.cabinet.historyAll.oneSet')!!}
                          </div>
                          <div>
                            {{trans('front.cabinet.historyAll.cod')}} <span class="stoc">{{ $orderSet->set->id}}</span>
                          </div>
                        </div>
                        <div class="offset-lg-0 offset-md-2 col-sm-2 col-6 text-right margMobile">
                          <div>
                            {{ $orderSet->set->price }} {{trans('front.general.currency')}}
                          </div>
                          <div class="textGrey">
                            {{ $orderSet->qty }} {{trans('front.cabinet.unit')}}
                          </div>
                        </div>
                        <div class="col-sm-1 col-3 margMobile">
                          <div class="historyCartImg" data-toggle="modal" data-target="#addSetAgainToCart{{$orderSet->id}}">
                            <img src="{{asset('fronts/img/icons/cartIcon.svg')}}" alt="">
                          </div>
                        </div>

                        <div class="modal" id="addSetAgainToCart{{$orderSet->id}}">
                          <div class="modal-dialog">
                            <div class="modal-content">

                              <!-- Modal Header -->
                              <div class="modal-header">
                                <h4 class="modal-title">{{trans('front.cabinet.historyAll.addSetToCart')}}</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>

                              <!-- Modal body -->
                              <div class="modal-body">
                                <div class="row justify-content-center">
                                  <div class="col-9">
                                    <form action="{{route('cabinet.historyCartSet', $orderSet->id)}}" method="post">
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

                        @if (count($orderSet->orderProduct) > 0)
                            <div class="returSetOpen col-11">
                              @foreach ($orderSet->orderProduct as $orderProduct)
                                <div class="row returItemSet">
                                  <div class="historyImgItem col-sm-2 col-3">
                                    @if ($orderProduct->product->withoutBack()->first())
                                        <img id="prOneBig1" src="{{ asset('images/products/og/'.$orderProduct->product->withoutBack()->first()->src ) }}">
                                    @else
                                        <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
                                    @endif
                                  </div>
                                  <div class="col-sm-5 col-9">
                                    <div>
                                      {{$orderProduct->product->translationByLanguage($lang->id)->first()->name}} {!!trans('front.cabinet.historyAll.oneProduct')!!}
                                    </div>
                                    <div>
                                      {{trans('front.cabinet.historyAll.cod')}} <span class="stoc">{{$orderProduct->subproduct->code}}</span>
                                    </div>
                                  </div>
                                  <div class="offset-md-2 col-sm-2 col-7 text-right margMobile">
                                    <div>
                                      {{$orderProduct->subproduct->price - ($orderProduct->subproduct->price * $orderProduct->subproduct->discount / 100)}} {{trans('front.general.currency')}}
                                    </div>
                                    <div class="textGrey">
                                      {{$orderProduct->qty}} {{trans('front.cabinet.unit')}}
                                    </div>
                                  </div>
                                  <div class="col-sm-1 col-3 margMobile">
                                    <div class="historyCartImg" data-toggle="modal" data-target="#addAgainToCart{{$orderProduct->id}}">
                                      <img src="{{asset('fronts/img/icons/cartIcon.svg')}}" alt="">
                                    </div>
                                  </div>

                                  <div class="modal" id="addAgainToCart{{$orderProduct->id}}">
                                    <div class="modal-dialog">
                                      <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                          <h4 class="modal-title">{{trans('front.cabinet.historyAll.addProductToCart')}}</h4>
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <!-- Modal body -->
                                        <div class="modal-body">
                                          <div class="row justify-content-center">
                                            <div class="col-9">
                                              <form action="{{route('cabinet.historyCartProduct', $orderProduct->id)}}" method="post">
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
                              @endforeach
                            </div>
                        @endif
                        </div>
                      </div>
                    </div>
                @endforeach
            @endif

            @if (count($order->orderProductsNoSet) > 0)
                @foreach ($order->orderProductsNoSet as $orderProduct)
                  <div class="row borders">
                    <div class="col-12">
                      <div class="row oneItemHistory">
                        <div class="historyImgItem col-sm-2 col-3">
                          @if ($orderProduct->product->withoutBack()->first())
                              <img id="prOneBig1" src="{{ asset('images/products/og/'.$orderProduct->product->withoutBack()->first()->src ) }}">
                          @else
                              <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
                          @endif
                        </div>
                        <div class="col-sm-5 col-8">
                          <div class="oneProductName">
                            {{$orderProduct->product->translationByLanguage($lang->id)->first()->name}} {!!trans('front.cabinet.historyAll.oneProduct')!!}
                          </div>
                          <div>
                            {{trans('front.cabinet.historyAll.cod')}} <span class="stoc">{{$orderProduct->subproduct->code}}</span>
                          </div>
                        </div>
                        <div class="offset-md-2 col-sm-2 col-6 text-right margMobile">
                          <div>
                            {{$orderProduct->subproduct->price - ($orderProduct->subproduct->price * $orderProduct->subproduct->discount / 100)}} {{trans('front.general.currency')}}
                          </div>
                          <div class="textGrey">
                            {{$orderProduct->qty}} {{trans('front.cabinet.unit')}}
                          </div>
                        </div>
                        <div class="historyCartImg" data-toggle="modal" data-target="#addAgainToCart{{$orderProduct->id}}">
                          <div class="historyCartImg">
                            <img src="{{asset('fronts/img/icons/cartIcon.svg')}}" alt="">
                          </div>
                        </div>

                        <div class="modal" id="addAgainToCart{{$orderProduct->id}}">
                          <div class="modal-dialog">
                            <div class="modal-content">

                              <!-- Modal Header -->
                              <div class="modal-header">
                                <h4 class="modal-title">{{trans('front.cabinet.historyAll.addProductToCart')}}</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>

                              <!-- Modal body -->
                              <div class="modal-body">
                                <div class="row justify-content-center">
                                  <div class="col-9">
                                    <form action="{{route('cabinet.historyCartProduct', $orderProduct->id)}}" method="post">
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
                    </div>
                  </div>
                @endforeach
            @endif
            <div class="row totalHistoryOpen">
              <div class="col-12">
                {{trans('front.cabinet.historyAll.deliveryMethod', ['delivery' => $order->delivery])}}
              </div>
              <div class="col-12">
                {{trans('front.cabinet.historyAll.paymentMethod', ['payment' => $order->payment])}}
              </div>
              <div class="col-12">
                {!!trans('front.cabinet.historyAll.totalSum', ['amount' => $order->amount])!!}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@include('front.layouts.footer')
@stop
