@php
      $amount = 0;
      $setAmount = 0;
      $promocodeDiscount = 0;
@endphp
@if (!empty($cartProducts))
    @foreach ($cartProducts as $key => $cartProduct)

      @php $price = $cartProduct->subproduct->price - ($cartProduct->subproduct->price * $cartProduct->subproduct->discount / 100); @endphp
      @if ($price && ($cartProduct->subproduct->stock > 0))
          @php
              $amount +=  $price * $cartProduct->qty;
          @endphp
      @endif

    @endforeach

@endif

@if (!empty($cartSets))
    @foreach ($cartSets as $key => $cartSet)

      @php $price = $cartSet->price - ($cartSet->price * $cartSet->set->discount / 100); @endphp
      @php
          $setAmount +=  $price * $cartSet->qty;
      @endphp

    @endforeach

@endif

@php
  $amount = $amount + $setAmount;
@endphp


@if ((count($cartProducts) > 0) || (count($cartSets) > 0))
    <div class="col-12 buttonsScroll">
     <div id="btnTopCart">
     </div>
    </div>
@endif




@if ((count($cartProducts) > 0) || (count($cartSets) > 0))
<div class="col-12">
  <div class="row wrappCart">
    <div class="wishScrollBlock col-12">
      @if (count($cartProducts) > 0)
        <div class="row justify-content-center">
          <div class="col-10">
          @foreach ($cartProducts as $cartProduct)
            <div class="itemCart">
              <div class="row">
                <div class="col-3">
                    @if ($cartProduct->product->withoutBack()->first())
                        <img id="prOneBig1" src="{{ asset('images/products/og/'.$cartProduct->product->withoutBack()->first()->src ) }}">
                    @else
                        <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
                    @endif
                </div>
                <div class="col-9">
                  <div class="nameProduct">{{$cartProduct->product->translationByLanguage($lang->id)->first()->name}}</div>
                  <div>{!!trans('front.header.qty', ['unit' => $cartProduct->qty])!!}</div>
                  <div class="cod">{{trans('front.header.cod')}} <span>{{$cartProduct->subproduct->code}}</span></div>
                </div>
              </div>
              <div class="deleteItemCart removeItemCart" data-product_id="{{$cartProduct->product->id}}" data-id="{{$cartProduct->id}}"></div>
            </div>
          @endforeach
          </div>
        </div>
      @endif

      @if (count($cartSets) > 0)
        <div class="row justify-content-center">
          <div class="col-10">
          @foreach ($cartSets as $cartSet)
            <div class="itemCart">
              <div class="row">
                <div class="col-3">
                  @if ($cartSet->set()->first())
                  <img src="/images/sets/og/{{ $cartSet->set()->first()->withoutBack()->first()->src }}" alt="">
                  @else
                    <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
                  @endif
                </div>
                <div class="col-9">
                  <div class="nameProduct">{{$cartSet->set()->first()->translationByLanguage($lang->id)->first()->name}}</div>
                  <div>{!!trans('front.header.qty', ['unit' => $cartSet->qty])!!}</div>
                </div>
              </div>
              <div class="deleteItemCart removeSetCart" data-id="{{$cartSet->id}}"></div>
            </div>
          @endforeach
          </div>
        </div>
      @endif
    </div>
  </div>
</div>
@endif

@if ((count($cartProducts) > 0) || (count($cartSets) > 0))
<div class="col-12 buttonsScroll">
 <div id="btnBottomCart">
 </div>
</div>
@endif

@if (count($cartProducts) === 0 && count($cartSets) === 0)
<div class="col-12">
      {{trans('front.general.buyText')}}
</div>
@endif
<div class="col-12">
    <div class="total">
      <div>{{trans('front.header.cartTotal')}}</div>
      <div class="amount">
        {{$amount}}
      </div>
    </div>
    <div class="alertCart">
      {{trans('front.header.cartDelivery')}}
    </div>
  </div>
