<?php
      $amount = 0;
      $descountTotal = 0;
      $setAmount = 0;
?>

@if (!empty($return))
    @foreach ($return->returnProductsNoSet()->get() as $key => $returnProduct)

        @if ($returnProduct->product)
        <?php $price = $returnProduct->subproduct->price - ($returnProduct->subproduct->price * $returnProduct->subproduct->discount / 100); ?>

            @if ($price)
                <?php
                    $amount +=  $price * $returnProduct->qty;
                    $descountTotal += ($returnProduct->subproduct->price -  ($returnProduct->subproduct->price - ($returnProduct->subproduct->price * $returnProduct->subproduct->discount / 100))) * $returnProduct->qty;
                ?>
            @endif

        @endif

    @endforeach

    @foreach ($return->returnSets()->get() as $key => $returnSet)
        <?php
          $price = $returnSet->price - ($returnSet->price * $returnSet->set->discount / 100);
          $setAmount += $price * $returnSet->qty;
        ?>

    @endforeach

    <?php $amount = $amount + $setAmount; ?>
@endif

@if(isset($return) && (count($return->returnProductsNoSet()->get()) > 0 || count($return->returnSets()->get()) > 0))
<div class="cartItems">
    <div class="row headCart">
      <div class="col-md-1">
      </div>
      <div class="col-md-3">
        {{trans('front.cart.cartProduct')}}
      </div>
      <div class="col-md-2">
        {{trans('front.cart.price')}}
      </div>
      <div class="col-md-2">
        {{trans('front.cart.qty')}}
      </div>
      <div class="col-md-2">
        {{trans('front.admin.discount')}} %
      </div>
      <div class="col-md-1">
        {{trans('front.cart.total')}}
      </div>
    </div>

    @if (count($return->returnProductsNoSet) > 0)
      @foreach ($return->returnProductsNoSet as $key => $returnProduct)
          @if ($returnProduct->subproduct)
              <div class="row cartOneItem">
                <div class="col-md-1">
                  <img src="{{asset('images/trashIcon.png')}}" data-return_id="{{$returnProduct->return_id}}" data-order_product_id="{{$returnProduct->orderProduct_id}}" data-product_id="{{$returnProduct->product_id}}" data-subproduct_id="{{$returnProduct->subproduct_id}}" class="buttonRemoveReturn removeItem"  style="width: 40px; height: 40px; cursor: pointer;">
                </div>
                <div class="col-lg-3 col-md-12">
                  <div class="imgCartItem">
                    @if (!is_null($returnProduct->product->mainImage()->first()))
                        @php $image = getMainProductImage($returnProduct->product_id, $lang->id) @endphp
                        <img src="{{ asset('images/products/sm/'.$returnProduct->product->mainImage()->first()->src) }}" alt="{{$image->alt}}" title="{{$image->title}}" class="cartImg">
                    @else
                        <img src="{{ asset('/images/no-image.png') }}" alt="">
                    @endif
                  </div>
                  <div class="cartDescr">
                    <p>{{$returnProduct->product->translationByLanguage($lang->id)->first()->name}}</p>
                    <?php $subproduct = $returnProduct->subproduct;?>
                    <div style="margin-left: 80px;">
                        @foreach (json_decode($subproduct->combination) as $key => $combination)
                            @if ($key != 0)
                              <p>{{getParamById($key, $lang->id)->name}}: <span>{{getParamValueById($combination, $lang->id)->value}}</span></p>
                            @endif
                        @endforeach
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  {{$returnProduct->subproduct->price}}
                </div>
                <div class="col-lg-2 col-6 justify-content-center ngh">
                  <div class="plusminus" style="width: 100%;">
                    <div class="minus minusProduct" data-return_id="{{$returnProduct->return_id}}" data-order_product_id="{{$returnProduct->orderProduct_id}}" data-product_id="{{ $returnProduct->product_id }}" data-subproduct_id="{{$returnProduct->subproduct_id}}">-</div>
                    <input type="text" class="form-control" id="niti" name="number" value="{{ $returnProduct->qty }}" >
                    <div class="plus plusProduct" data-return_id="{{$returnProduct->return_id}}" data-order_product_id="{{$returnProduct->orderProduct_id}}" data-product_id="{{ $returnProduct->product_id }}" data-subproduct_id="{{$returnProduct->subproduct_id}}">+</div>
                  </div>
                </div>
                <div class="col-md-1 colRed">
                  {{$returnProduct->subproduct->discount}}
                </div>

                <div class="col-md-1 col-6">
                  {{ $returnProduct->subproduct->price * $returnProduct->qty}}
                </div>
                <div class="col-md-2">
                  {{ ($returnProduct->subproduct->price - ($returnProduct->subproduct->price * $returnProduct->subproduct->discount / 100)) * $returnProduct->qty}}
                </div>
              </div>
          @else
              <div class="row cartOneItem">
                <div class="col-md-1">
                  <img src="{{asset('images/trashIcon.png')}}" data-return_id="{{$returnProduct->return_id}}" data-order_product_id="{{$returnProduct->orderProduct_id}}" data-product_id="{{$returnProduct->product_id}}" data-subproduct_id="0" class="buttonRemoveReturn removeItem"  style="width: 40px; height: 40px; cursor: pointer;">
                </div>
                <div class="col-lg-3 col-md-12">
                  <div class="imgCartItem">
                    @if (!is_null($returnProduct->product->mainImage()->first()))
                        @php $image = getMainProductImage($returnProduct->product_id, $lang->id) @endphp
                        <img src="{{ asset('images/products/sm/'.$returnProduct->product->mainImage()->first()->src) }}" alt="{{$image->alt}}" title="{{$image->title}}" class="cartImg">
                    @else
                        <img src="{{ asset('/images/no-image.png') }}" alt="">
                    @endif
                  </div>
                  <div class="cartDescr">
                    <p>{{$returnProduct->product->translationByLanguage($lang->id)->first()->name}}</p>
                  </div>
                </div>
                <div class="col-md-2">
                  {{$returnProduct->product->price}}
                </div>
                <div class="col-lg-2 col-6 justify-content-center ngh">
                  <div class="plusminus" style="width: 100%;">
                    <div class="minus minusProduct" data-return_id="{{$returnProduct->return_id}}" data-order_product_id="{{$returnProduct->orderProduct_id}}" data-product_id="{{ $returnProduct->product_id }}" data-subproduct_id="0">-</div>
                    <input type="text" class="form-control" id="niti" name="number" value="{{ $returnProduct->qty }}" >
                    <div class="plus plusProduct" data-return_id="{{$returnProduct->return_id}}" data-order_product_id="{{$returnProduct->orderProduct_id}}" data-product_id="{{ $returnProduct->product_id }}" data-subproduct_id="0">+</div>
                  </div>
                </div>
                <div class="col-md-1 colRed">
                  {{$returnProduct->product->discount}}
                </div>

                <div class="col-md-1 col-6">
                  {{ $returnProduct->product->price * $returnProduct->qty}}
                </div>
                <div class="col-md-2">
                  {{ ($returnProduct->product->price - ($returnProduct->product->price * $returnProduct->product->discount / 100)) * $returnProduct->qty}}
                </div>
              </div>
          @endif
      @endforeach
    @endif

    @if (count($return->returnSets) > 0)
        @foreach ($return->returnSets as $returnSet)
          <div class="row set">
            <div class="row cartOneItem">
              <div class="col-md-1">
                <img src="{{asset('images/trashIcon.png')}}" data-id="{{$returnSet->id}}" class="buttonRemoveSetReturn removeItem"  style="width: 40px; height: 40px; cursor: pointer;">
              </div>
              <div class="col-lg-3 col-md-12">
                <div class="imgCartItem">
                  @if ($returnSet->set()->first()->mainPhoto()->first())
                  <img src="/images/sets/og/{{ $returnSet->set()->first()->mainPhoto()->first()->src }}" alt="">
                  @else
                  <img src="{{ asset('/images/no-image.png') }}" alt="">
                  @endif
                </div>
                <div class="cartDescr">
                  <p>{{ $returnSet->set->translationByLanguage($lang->id)->first()->name }}</p>
                </div>
              </div>
              <div class="col-md-2">
                {{$returnSet->price}}
              </div>
              <div class="col-lg-2 col-6 justify-content-center ngh">
                <div class="plusminus" style="width: 100%;">
                  <div class="minus minusSet" data-id="{{$returnSet->id}}">-</div>
                  <input type="text" class="form-control" id="niti" name="number" value="{{ $returnSet->qty }}" >
                  <div class="plus plusSet" data-id="{{$returnSet->id}}">+</div>
                </div>
              </div>
              <div class="col-md-1 colRed">
                {{$returnSet->set->discount}}
              </div>

              <div class="col-md-1 col-6">
                {{ $returnSet->price * $returnSet->qty}}
              </div>
              <div class="col-md-2">
                {{ ($returnSet->price - ($returnSet->price * $returnSet->set->discount / 100)) * $returnSet->qty}}
              </div>
            </div>
            @foreach ($returnSet->returnProducts as $returnProduct)
              <div class="row cartOneItem setProduct" style="display: none;">
                <div class="col-md-1">
                </div>
                <div class="col-lg-3 col-md-12">
                  <div class="imgCartItem">
                    @if (!is_null($returnProduct->product->mainImage()->first()))
                        @php $image = getMainProductImage($returnProduct->product_id, $lang->id) @endphp
                        <img src="{{ asset('images/products/sm/'.$returnProduct->product->mainImage()->first()->src) }}" alt="{{$image->alt}}" title="{{$image->title}}" class="cartImg">
                    @else
                        <img src="{{ asset('/images/no-image.png') }}" alt="">
                    @endif
                  </div>
                  <div class="cartDescr">
                    <p>{{$returnProduct->product->translationByLanguage($lang->id)->first()->name}}</p>
                    <?php $subproduct = $returnProduct->subproduct;?>
                    <div style="margin-left: 80px;">
                        @foreach (json_decode($subproduct->combination) as $key => $combination)
                            @if ($key != 0)
                              <p>{{getParamById($key, $lang->id)->name}}: <span>{{getParamValueById($combination, $lang->id)->value}}</span></p>
                            @endif
                        @endforeach
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  {{$returnProduct->subproduct->price}}
                </div>
                <div class="col-lg-2 col-6 justify-content-center ngh">
                  {{$returnProduct->qty}}
                </div>
                <div class="col-md-1 colRed">
                  {{$returnProduct->subproduct->discount}}
                </div>

                <div class="col-md-1 col-6">
                  {{ $returnProduct->subproduct->price * $returnProduct->qty}}
                </div>
                <div class="col-md-2">
                  {{ ($returnProduct->subproduct->price - ($returnProduct->subproduct->price * $returnProduct->subproduct->discount / 100)) * $returnProduct->qty}}
                </div>
              </div>
            @endforeach
          </div>
        @endforeach
    @endif
    {{trans('front.admin.discount')}} {{$descountTotal}}
    <div class="col totalsBtn">
      <input type="button" id="removeAllItemsReturn" data-return_id="{{$return->id}}" name="remAllItems" value="{{trans('front.admin.remove')}}">
    </div>
    {{trans('front.cart.total')}} : {{$amount}}
</div>
@else
<div class="empty-response">{{trans('variables.list_is_empty')}}</div>
@endif
