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

@if (count($cartProducts) !== 0 || count($cartSets) !== 0)
  <div class="col-auto">
    <div class="buttonCartHeader cartAdded">
      <div class="nmbCart">{{count($cartProducts) + count($cartSets)}}</div>
      <div class="nameCart">{{trans('front.general.cart')}}</div>
      <div class="amount">
        {{$amount}}
      </div>
    </div>
  </div>
@else
  <div class="col-auto">
    <div class="buttonCartHeader">
      <div class="nameCart">{{trans('front.general.cart')}}</div>
      <div class="amount">
        {{$amount}}
      </div>
    </div>
  </div>
@endif
