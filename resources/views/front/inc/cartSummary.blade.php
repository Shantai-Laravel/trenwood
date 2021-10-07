@php
      $amount = 0;
      $setAmount = 0;
      $promocodeDiscount = 0;
      $deliveryPrice = getContactInfo('delivery')->translationByLanguage()->first()->value;
      $threshold = getContactInfo('treshold')->translationByLanguage()->first()->value;
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

      @php
          $price = $cartSet->price - ($cartSet->price * $cartSet->discount / 100);
          $setAmount +=  $price * $cartSet->qty;
      @endphp

    @endforeach

@endif

@php
$amount = $amount + $setAmount;
@endphp

@if ($promocode != null)
   @if ($promocode->user_id !== 0)
      @if ($promocode->treshold <= $amount)
            <?php $promocodeDiscount = $promocode->discount?>
      @endif
    @elseif ($promocode->treshold <= $amount)
      <?php $promocodeDiscount = $promocode->discount?>
   @endif
@endif

@php
   if (!is_null($promocode)){
         if ($promocode->treshold <= $amount){
             $amount = $amount - ($amount * $promocodeDiscount / 100);
         }
     }

     if ($threshold < $amount) {
            $deliveryPrice = 0;
     }
@endphp

<div class="fixedForm">
  <form action="">
    <div class="bcgFixed">
      <h6>{{trans('front.cart.cartSum')}}</h6>
      <ul>
        <li>{{trans('front.cart.cartProduct')}} <b>{{$amount}}</b></li>
        <li>{{trans('front.cart.cartDelivery')}} <b>{{$deliveryPrice}} {{trans('front.general.currency')}}</b></li>
        <li>{{trans('front.cart.cartTotal')}} <b> {{$amount + $deliveryPrice}}</b></li>
      </ul>
    </div>
    <input type="text" value="{{getContactInfo('phone')->translationByLanguage()->first()->value}}" style="text-align: center;width: 100%; margin: 10px 0;" readonly>
      <input type="submit" value="{{trans('front.cart.checkout')}}">
  </form>
</div>
