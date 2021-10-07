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

<div class="productsListMobile">
  @if (count($cartProducts) > 0)
      @foreach ($cartProducts as $key => $cartProduct)
          @php
            $color = getFullParameterById(2, $cartProduct->product->id, $langId = $lang->id);
          @endphp
          <div class="row cartUserItem">
            <div class="col-11">
              <div class="row">
                <div class="col-12">
                  <div class="row">
                    <div class="col-3 cartImg d-flex align-items-center">
                      @if ($cartProduct->product->withoutBack()->first())
                          <img src="{{ asset('images/products/og/'.$cartProduct->product->withoutBack()->first()->src ) }}">
                      @else
                          <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
                      @endif
                    </div>
                    <div class="col-9 namProduct">
                      <div><strong>{{$cartProduct->product->translationByLanguage($lang->id)->first()->name}}</strong> {{trans('front.cart.oneProduct')}}</div>
                      <div class="priceCart">{{$cartProduct->subproduct->price - ($cartProduct->subproduct->price * $cartProduct->subproduct->discount / 100)}} {{trans('front.general.currency')}}</div>
                      <div class="row descrCartitem">
                        <div class="col-5">
                          {{trans('front.cart.cod')}}
                        </div>
                        <div class="col-7">
                          {{$cartProduct->subproduct->code}}
                        </div>
                        <div class="col-5">
                          {{ $color['prop'] }}
                        </div>
                        <div class="col-7">
                          {{ $color['val'] }}
                        </div>
                        <div class="col-5">
                          {{trans('front.cart.size')}}
                        </div>
                        <div class="col-7">
                          {{ propByCombination($cartProduct->subproduct->id)[1] }}
                        </div>
                        <div class="col-5">
                          <b>{{trans('front.cart.total')}}</b>
                        </div>
                        <div class="col-7">
                          <b>{{ ($cartProduct->subproduct->price - ($cartProduct->subproduct->price * $cartProduct->subproduct->discount / 100)) * $cartProduct->qty}} {{trans('front.general.currency')}}</b>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-1" style="padding: 0">
              <div class="deletItem4 removeItemCart" data-id="{{$cartProduct->id}}">
                <img src="{{asset('fronts/img/icons/close.svg')}}" alt="">
              </div>
            </div>
            <div class="col-12">
              <div class="row detitemMobile">
                <div class="col-11" style="padding-left: 30px">
                  <div class="row">
                    <div class="col-8 wishCartMove moveFromCartToWishList" data-id="{{$cartProduct->id}}">
                      {{trans('front.cart.moveWish')}}
                    </div>
                    <div class="col-4">
                      <select class="changeQty" data-id="{{ $cartProduct->id }}">
                          @for ($i = 1; $i <= $cartProduct->subproduct->stock; $i++)
                              <option value="{{ $i }}" {{ $cartProduct->qty == $i ? 'selected' : '' }}>{{ $i }}</option>
                          @endfor
                      </select>
                    </div>
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
            <div class="col-11">
              <div class="row">
                <div class="col-12">
                  <div class="row">
                    <div class="col-3 cartImg">
                      @if ($cartSet->set()->first())
                      <img src="/images/sets/og/{{ $cartSet->set()->first()->withoutBack()->first()->src }}" alt="">
                      @else
                        <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
                      @endif
                    </div>
                    <div class="col-9 namProduct setDetMobile">
                      <div class="buttMobile" data-toggle="tooltip" data-placement="top" title="{{trans('front.cart.title')}}"><strong>{{ $cartSet->set()->first()->translationByLanguage($lang->id)->first()->name }}</strong> {{trans('front.cart.oneSet')}}</div>
                      <div class="priceCart">{{$cartSet->price}} {{trans('front.general.currency')}}</div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
            <div class="col-1" style="padding: 0;">
              <div class="deletItem4 removeSetCart" data-id="{{$cartSet->id}}">
                <img src="{{asset('fronts/img/icons/close.svg')}}" alt="">
              </div>
            </div>
            <div class="col-12">
              <div class="row detitemMobile">
                <div class="col-11" style="padding-left: 30px">
                  <div class="row">
                    <div class="col-8 wishCartMove moveSetFromCartToWishList" data-id="{{$cartSet->id}}">
                      {{trans('front.cart.moveWish')}}
                    </div>
                    <div class="col-4">
                      <select class="changeQtySet" data-id="{{ $cartSet->id }}">
                           @for ($i = 1; $i <= $cartSet->qty + 10; $i++)
                               <option value="{{ $i }}" {{ $cartSet->qty == $i ? 'selected' : '' }}>{{ $i }}</option>
                           @endfor
                       </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 setDetMobileOpen">
              @php
                  $amountProds = 0;
              @endphp
              @if (count($cartSet->cart) > 0)
                  @foreach ($cartSet->cart as $cartProduct)
                    @php
                      $color = getFullParameterById(2, $cartProduct->product->id, $langId = $lang->id);
                    @endphp
                    <div class="row itMobile">
                      <div class="col-10">
                        <div class="row">
                          <div class="col-12">
                            <div class="row">
                              <div class="col-3 cartImg d-flex align-items-center">
                                @if ($cartProduct->product->withoutBack()->first())
                                    <img src="{{ asset('images/products/og/'.$cartProduct->product->withoutBack()->first()->src ) }}">
                                @else
                                    <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
                                @endif
                              </div>
                              <div class="col-9 namProduct">
                                <div><strong>{{ $cartProduct->product->translationByLanguage($lang->id)->first()->name }}</strong></div>
                                <div class="priceCart">
                                  {{$cartProduct->subproduct->price - ($cartProduct->subproduct->price * $cartProduct->subproduct->discount / 100)}} {{trans('front.general.currency')}}
                                </div>
                                <div class="row descrCartitem">
                                  <div class="col-5">
                                    {{trans('front.cart.cod')}}
                                  </div>
                                  <div class="col-7">
                                    {{$cartProduct->subproduct->code}}
                                  </div>
                                  <div class="col-5">
                                    {{ $color['prop'] }}
                                  </div>
                                  <div class="col-7">
                                    {{ $color['val'] }}
                                  </div>
                                  <div class="col-5">
                                    {{trans('front.cart.size')}}
                                  </div>
                                  <div class="col-7">
                                    {{ propByCombination($cartProduct->subproduct->id)[1] }}
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                        </div>
                      </div>
                      <div class="col-1">
                        <div class="deletItem4">
                          <img src="{{asset('fronts/img/icons/close.svg')}}" alt="">
                        </div>
                      </div>
                    </div>

                    @php
                      $amountProds +=  $cartProduct->subproduct->price - ($cartProduct->subproduct->price * $cartProduct->subproduct->discount / 100)
                    @endphp
                  @endforeach
              @endif

              <div class="row">
                <div class="col-12">
                  <div class="row fwb totalSetMobile">
                    <div class="col-6">
                      {{trans('front.cart.total')}}
                    </div>
                    <div class="col-6 d-flex justify-content-between">
                      <div class="">
                        {{ $amountProds }} {{trans('front.general.currency')}}
                      </div>
                      <div class="reduce">
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
          </div>
      @endforeach
  @endif
</div>
