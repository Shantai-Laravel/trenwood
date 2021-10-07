@php
$amount = 0;
$notInStock = false;
$promocodeDiscount = 0;
@endphp

@if (!empty($cartProducts))
  @foreach ($cartProducts as $key => $cartProduct)
    @if ($cartProduct->subproduct_id > 0)
        @if ($cartProduct->subproduct)
        @php $price = $cartProduct->subproduct->price - ($cartProduct->subproduct->price * $cartProduct->subproduct->discount / 100); @endphp
            @if ($price && ($cartProduct->subproduct->stock > 0))
                @php
                $amount +=  $price * $cartProduct->qty;
                @endphp
            @else
                 @php $notInStock = $cartProduct->subproduct->stock > 0 ? false : true; @endphp
            @endif
        @endif
    @endif
  @endforeach
@endif

@php
    $setAmount = 0;
@endphp

@if ($notInStock == true)
  <div class="invalid-feedback text-center" style="display: block;">
    {{trans('front.cart.notInStock')}}
  </div>
@endif
@if(count($cartProducts) === 0 && count($cartSets) === 0)
  <div class="invalid-feedback text-center" style="display: block">
      {{trans('front.cart.empty')}}
  </div>
@endif
@if (Session::has('success'))
  <div class="valid-feedback text-center" style="display: block">
      {{ Session::get('success') }}
  </div>
@endif

<div class="productsList">
  <div class="row prodheader">
    <div class="col-md-5">
      {{trans('front.cart.product')}}
    </div>
    <div class="col-md-2 text-center">
      {{trans('front.cart.price')}}
    </div>
    <div class="col-md-3 text-center">
      {{trans('front.cart.qty')}}
    </div>
    <div class="col-md-2">
      {{trans('front.cart.total')}}
    </div>
  </div>

  @if (count($cartProducts) > 0)
      @foreach ($cartProducts as $key => $cartProduct)

          <div class="row cartUserItem">
            <div class="col-md-5 col-12">
              <div class="row">
                <div class="col-3 cartImg">
                  @if ($cartProduct->product->withoutBack()->first())
                      <img src="{{ asset('images/products/og/'.$cartProduct->product->withoutBack()->first()->src ) }}">
                  @else
                      <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
                  @endif
                </div>
                <div class="col-9 namProduct">
                  <div><strong>{{$cartProduct->product->translationByLanguage($lang->id)->first()->name}}</strong> {{trans('front.cart.oneProduct')}}</div>
                  <div>
                    <div>{{trans('front.cart.infoStock')}} <b class="stoc">{{$cartProduct->subproduct->stock}}</b></div>
                    <div>{{trans('front.cart.cod')}} <b>{{$cartProduct->subproduct->code}}</b></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-7 col-12">
              <div class="row detitemMobile">
                <div class="col-md-3 col-6 text-center">
                  {{$cartProduct->subproduct->price - ($cartProduct->subproduct->price * $cartProduct->subproduct->discount / 100) }} {{trans('front.general.currency')}}
                </div>
                <div class="offset-md-2 offset-0 col-md-3 col-6 text-left">
                  <select class="changeQty" data-id="{{ $cartProduct->id }}">
                      @for ($i = 1; $i <= $cartProduct->subproduct->stock; $i++)
                          <option value="{{ $i }}" {{ $cartProduct->qty == $i ? 'selected' : '' }}>{{ $i }}</option>
                      @endfor
                  </select>
                </div>
                <div class="col-md-3 col-6 text-center">
                  {{ ($cartProduct->subproduct->price - ($cartProduct->subproduct->price * $cartProduct->subproduct->discount / 100)) * $cartProduct->qty}} {{trans('front.general.currency')}}
                </div>
                <div class="col-1">
                  <div class="deletItem removeItemCart" data-id="{{$cartProduct->id}}">
                    <img src="{{asset('fronts/img/icons/close.svg')}}" alt="">
                  </div>
                </div>
              </div>
              <div class="row justify-content-end">
                <div class="col-auto">
                  <div class="wishCartMove moveFromCartToWishList" data-id="{{$cartProduct->id}}">
                    {{trans('front.cart.moveWish')}}
                  </div>
                </div>
              </div>
            </div>
          </div>
      @endforeach
  @endif

  @if (count($cartSets) > 0)
      @foreach ($cartSets as $cartSet)
        <div class="row cartUserSet justify-content-center">
          <div class="col-5 nam">
            <div class="row">
              <div class="col-3 cartImg">
                @if ($cartSet->set()->first())
                  <img src="/images/sets/og/{{ $cartSet->set()->first()->withoutBack()->first()->src }}" alt="">
                @else
                  <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
                @endif
              </div>
              <div class="col-9 namSet">
                <div class="namSetButton" data-toggle="tooltip" data-placement="top" title="{{trans('front.cart.title')}}"><strong>{{ $cartSet->set()->first()->translationByLanguage($lang->id)->first()->name }}</strong> {{trans('front.cart.oneSet')}}</div>
                <div></div>
              </div>
            </div>
          </div>
          <div class="col-7">
            <div class="row detitemMobile">
              <div class="col-md-3 text-center">
                {{ $cartSet->price }} {{trans('front.general.currency')}}
              </div>
              <div class="offset-2 col-md-3 col-8 text-left">
                <select class="changeQtySet" data-id="{{ $cartSet->id }}">
                     @for ($i = 1; $i <= $cartSet->qty + 10; $i++)
                         <option value="{{ $i }}" {{ $cartSet->qty == $i ? 'selected' : '' }}>{{ $i }}</option>
                     @endfor
                 </select>
              </div>
              <div class="col-md-3 col-9 text-center">
                {{ $cartSet->price * $cartSet->qty }} {{trans('front.general.currency')}}
              </div>
              <div class="col-1">
                <div class="deletItem2 removeSetCart" data-id="{{$cartSet->id}}">
                  <img src="{{asset('fronts/img/icons/close.svg')}}" alt="">
                </div>
              </div>
            </div>
            <div class="row justify-content-end">
              <div class="col-auto">
                <div class="wishCartMove moveSetFromCartToWishList" data-id="{{$cartSet->id}}">
                  {{trans('front.cart.moveWish')}}
                </div>
              </div>
            </div>
          </div>
          <div class="col-11 detSet">
            <div class="row">
              @php
                  $amountProds = 0;
              @endphp
              @if (count($cartSet->cart) > 0)
                  @foreach ($cartSet->cart as $cartProduct)
                      <div class="col-12">
                        <div class="row">
                          <div class="col-5">
                            <div class="row">
                              <div class="col-3 cartImg">
                                @if ($cartProduct->product->withoutBack()->first())
                                    <img src="{{ asset('images/products/og/'.$cartProduct->product->withoutBack()->first()->src ) }}">
                                @else
                                    <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
                                @endif
                              </div>
                              <div class="col-9 namSet">
                                <div><strong>{{ $cartProduct->product->translationByLanguage($lang->id)->first()->name }}</strong> {{trans('front.cart.oneProduct')}}</div>
                                <div>
                                  <div>{{trans('front.cart.infoStock')}} <b class="stoc">{{ $cartProduct->subproduct->stock }}</b></div>
                                  <div>{{trans('front.cart.cod')}} <b>{{ $cartProduct->subproduct->code }}</b></div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-7">
                            <div class="row detitemMobile">
                              <div class="col-md-3 text-center">
                                {{$cartProduct->subproduct->price - ($cartProduct->subproduct->price * $cartProduct->subproduct->discount / 100)}} {{trans('front.general.currency')}}
                                @php
                                  $amountProds +=  $cartProduct->subproduct->price - ($cartProduct->subproduct->price * $cartProduct->subproduct->discount / 100)
                                @endphp
                              </div>
                              <div class="col-md-8 col-9 text-right">
                                {{ ($cartProduct->subproduct->price - ($cartProduct->subproduct->price * $cartProduct->subproduct->discount / 100)) * $cartProduct->qty}} {{trans('front.general.currency')}}
                              </div>
                              <div class="col-1 text-right">
                                <div class="deletItem3">

                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                  @endforeach
              @endif
              <div class="col-12">
                <div class="row justify-content-between fwb">
                  <div class="col-auto">
                    {{trans('front.cart.totalSet')}}
                  </div>
                  <div class="col-auto text-right reduce">
                    {{ $amountProds }} {{trans('front.general.currency')}}
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="row justify-content-between fwb">
                  <div class="col-auto">
                    {{trans('front.cart.priceSet')}} {{ $cartSet->set()->first()->translationByLanguage($lang->id)->first()->name }}
                  </div>
                  <div class="col-auto">
                    {{$cartSet->price}} {{trans('front.general.currency')}}
                    @php
                        $setAmount +=  $cartSet->price * $cartSet->qty;
                    @endphp
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
  @endif
</div>
