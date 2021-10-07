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

<div class="row promo1CodCart align-items-center justify-content-center">
    @php
      $amount = $amount + $setAmount;
    @endphp

    @if ($promocode != null)
       @if ($promocode->user_id !== 0)
          @if (is_null($userdata))
            <div class="invalid-feedback text-center"  style="display: block">
                {{trans('front.cart.loginUsePromo')}}
            </div>
          @elseif((!is_null($userdata)) && ($promocode->user_id !== $userdata->id))
            <div class="invalid-feedback text-center"  style="display: block">
                {{trans('front.cart.anotherPromo')}}
            </div>
          @elseif ($promocode->treshold <= $amount)
              <div class="invalid-feedback text-center"  style="display: block">
                  - {{ $promocode->discount }}% {{trans('front.cart.withPromo')}}
                  <?php $promocodeDiscount = $promocode->discount?>
              </div>
          @else
              <div class="invalid-feedback text-center"  style="display: block">
                  {{trans('front.cart.promoCommand')}} > {{ $promocode->treshold }} {{trans('front.cart.currency')}}.
              </div>
          @endif
      @elseif ($promocode->treshold <= $amount)
          <div class="invalid-feedback text-center"  style="display: block">
              - {{ $promocode->discount }}% {{trans('front.cart.withPromo')}}
                <?php $promocodeDiscount = $promocode->discount?>
          </div>
      @else
          <div class="invalid-feedback text-center"  style="display: block">
              {{trans('front.cart.promoCommand')}} {{ $promocode->treshold }} {{trans('front.cart.currency')}}.
          </div>
       @endif
   @else
       @if (Session::get('promocode'))
           <div class="invalid-feedback text-center"  style="display: block">
               {{trans('front.cart.promoError')}}
           </div>
       @endif
   @endif
  <div class="col-12">{{trans('front.cart.promoQuestion')}}</div>
  <div class="col-md-9 col-8">
    <input type="text" id="codPromo" class="codPromo" name="codPromo" placeholder="{{trans('front.cart.addPromo')}}" value="{{ !is_null($promocode) ? $promocode->name : Session::get('promocode') }}">
  </div>
  <div class="col-md-3 col-4">
    <div class="buttonCartLogged promocodeAction">
      {{trans('front.cart.addPromoBtn')}}
    </div>
  </div>

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
  <div class="col-12">
    <div class="row">
      <div class="col-md-9 col-8 text-right tictic">{{trans('front.cart.totalCart')}}</div>
      <div class="col-md-3 col-4 text-right tictic">{{ $amount }} {{trans('front.general.currency')}}</div>
    </div>
  </div>
  <div class="col-12">
    <div class="row">
      <div class="col-md-9 col-8 text-right tictic">{{trans('front.cart.totalTax')}}</div>
      <div class="col-md-3 col-4 text-right tictic">{{$deliveryPrice}} {{trans('front.general.currency')}}</div>
    </div>
  </div>
  <div class="col-12">
    <div class="row">
      <div class="col-md-9 col-8 text-right tictic"><b>{{trans('front.cart.totalSum')}}</b></div>
      <div class="col-md-3 col-4 text-right tictic"><b>{{ $amount + $deliveryPrice }} {{trans('front.general.currency')}}</b></div>
    </div>
  </div>
</div>
