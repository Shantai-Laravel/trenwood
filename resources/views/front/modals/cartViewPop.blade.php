<div class="row justify-content-center cartBl">
    <div class="col-12 text-right">
        Shoping Cart ({{ count($cartProducts) + count($cartSets) }})
    </div>
    @if ($checkStock == 'true')
        @php
            $product = $subproduct->product()->first();
            $color = getFullParameterById(2, $product->id, $langId = $lang->id);
        @endphp
        <div class="col-lg-4 col-6">
            @if ($product->withoutBack()->first())
            <img src="{{ asset('/images/products/og/'.$product->withoutBack()->first()->src ) }}" alt="">
            @else
            <img src="{{ asset('fronts/img/products/noimage.png') }}" alt="img-advice">
            @endif
        </div>
        <div class="col-lg-6 col-6 descrItemCart">
          <div>{{ $product->translation($lang->id)->first()->name }}</div>

          @if ($color)
          <div>{{ $color['prop'] }}: <span>{{ $color['val'] }}</span></div>
          @endif
          <div>Marime:<b> {{ propByCombination($subproduct->id)[1] }}</b></div>
          <div>Pret: <b>{{ $subproduct->actual_price }} Lei</b></div>
        </div>
    @else
        {{ trans('front.cart.productNotExist') }}
    @endif
</div>
