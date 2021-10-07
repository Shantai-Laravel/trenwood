<?php
      $amount = 0;
      $descountTotal = 0;
      $setAmount = 0;
?>

@if (!empty($cartProducts))
    @foreach ($cartProducts as $key => $cartProduct)

        @if ($cartProduct->subproduct)
            <?php $price = $cartProduct->subproduct->price - ($cartProduct->subproduct->price * $cartProduct->subproduct->discount / 100); ?>

            @if ($price)
                <?php
                    $amount +=  $price * $cartProduct->qty;
                    $descountTotal += ($cartProduct->subproduct->price -  ($cartProduct->subproduct->price - ($cartProduct->subproduct->price * $cartProduct->subproduct->discount / 100))) * $cartProduct->qty;
                ?>
            @endif
        @else
            <?php $price = $cartProduct->product->price - ($cartProduct->product->price * $cartProduct->product->discount / 100); ?>

            @if ($price)
                <?php
                    $amount +=  $price * $cartProduct->qty;
                    $descountTotal += ($cartProduct->product->price -  ($cartProduct->product->price - ($cartProduct->product->price * $cartProduct->product->discount / 100))) * $cartProduct->qty;
                ?>
            @endif
        @endif

    @endforeach
@endif

@if (!empty($cartSets))
    @foreach ($cartSets as $key => $cartSet)

        <?php
          $price = $cartSet->price - ($cartSet->price * $cartSet->set->discount / 100);
          $setAmount += $price * $cartSet->qty;
        ?>

    @endforeach

    <?php $amount = $amount + $setAmount; ?>
@endif

@if(count($cartProducts) > 0 || count($cartSets) > 0)
  <div class="cartItems">
    <div class="row headCart">
      <div class="col-md-1">
      </div>
      <div class="col-md-4">
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

  <div class="col-md-12">
    @if (count($cartProducts) > 0)
      @foreach ($cartProducts as $key => $cartProduct)
          @if ($cartProduct->subproduct)
              <div class="row cartOneItem">
                <div class="col-md-1">
                  <img src="{{asset('images/trashIcon.png')}}" data-product_id="{{$cartProduct->product_id}}" data-subproduct_id="{{$cartProduct->subproduct_id}}" class="buttonRemove removeItem"  style="width: 40px; height: 40px; cursor: pointer;">
                </div>
                <div class="col-lg-4 col-md-12">
                  <div class="imgCartItem">
                    @if (getMainSubProductImage($cartProduct->subproduct_id))
                     @php $image = getMainSubProductImage($cartProduct->subproduct_id) @endphp
                     <img src="{{ asset('images/subproducts/'.$image->image) }}" >
                    @else
                     <img src="{{ asset('images/no-image.png') }}">
                    @endif
                  </div>
                  <div class="cartDescr">
                    <p>{{$cartProduct->product->translationByLanguage($lang->id)->first()->name}}</p>
                    <?php $subproduct = $cartProduct->subproduct;?>
                    <div>
                        @foreach (json_decode($subproduct->combination) as $key => $combination)
                            @if ($key != 0)
                              <p>{{getParamById($key, $lang->id)->name}}: <span>{{getParamValueById($combination, $lang->id)->value}}</span></p>
                            @endif
                        @endforeach
                    </div>
                    <p>{{trans('front.general.inStock')}}: <b class="stoc">{{$cartProduct->subproduct->stock}}</b></p>
                  </div>
                </div>
                <div class="col-md-2">
                  <input style="height: 39px; width: 100%" type="text" name="productPrice" data-id="{{$cartProduct->subproduct_id}}" value="{{$cartProduct->subproduct->price}}">
                </div>
                <div class="col-lg-2 col-6 justify-content-center ngh">
                  <div class="plusminus" style="width: 100%;">
                    <div class="minus minusProduct" data-product_id="{{ $cartProduct->product_id }}" data-subproduct_id="{{$cartProduct->subproduct_id}}">-</div>
                    <input type="text" class="form-control" id="niti" name="number" value="{{ $cartProduct->qty }}" >
                    <div class="plus plusProduct" data-product_id="{{ $cartProduct->product_id }}" data-subproduct_id="{{$cartProduct->subproduct_id}}">+</div>
                  </div>
                </div>
                <div class="col-md-2 colRed">
                  <input style="width: 100%; height: 39px;" type="text" data-id="{{$cartProduct->subproduct_id}}" name="productDiscount" value="{{$cartProduct->subproduct->discount}}">
                </div>

                <div class="col-md-1">
                  {{ ($cartProduct->subproduct->price - ($cartProduct->subproduct->price * $cartProduct->subproduct->discount / 100)) * $cartProduct->qty}}
                </div>
              </div>
          @else
              <div class="row cartOneItem">
                <div class="col-md-1">
                  <img src="{{asset('images/trashIcon.png')}}" data-product_id="{{$cartProduct->product_id}}" data-subproduct_id="0" class="buttonRemove removeItem"  style="width: 40px; height: 40px; cursor: pointer;">
                </div>
                <div class="col-lg-3 col-md-12">
                  <div class="imgCartItem">
                    @if (getMainProductImage($cartProduct->product_id, $lang->id))
                     @php $image = getMainProductImage($cartProduct->product_id, $lang->id) @endphp
                     <img src="{{ asset('images/products/sm/'.$image->src) }}" alt="{{$image->alt}}" title="{{$image->title}}" >
                    @else
                     <img src="{{ asset('images/no-image.png') }}" alt="">
                    @endif
                  </div>
                  <div class="cartDescr">
                    <p>{{$cartProduct->product->translationByLanguage($lang->id)->first()->name}}</p>
                    <p>{{trans('front.general.inStock')}}: {{$cartProduct->product->stock}}</p>
                  </div>
                </div>
                <div class="col-md-2">
                  <input style="height: 39px; width: 100%" type="text" name="productPrice" data-id="{{$cartProduct->product_id}}" value="{{$cartProduct->product->price}}">
                </div>
                <div class="col-lg-2 col-6 justify-content-center ngh">
                  <div class="plusminus" style="width: 100%;">
                    <div class="minus minusProduct" data-product_id="{{ $cartProduct->product_id }}" data-subproduct_id="0">-</div>
                    <input type="text" class="form-control" id="niti" name="number" value="{{ $cartProduct->qty }}" >
                    <div class="plus plusProduct" data-product_id="{{ $cartProduct->product_id }}" data-subproduct_id="0">+</div>
                  </div>
                </div>
                <div class="col-md-2 colRed">
                  <input style="width: 100%; height: 39px;" type="text" data-id="{{$cartProduct->product_id}}" name="productDiscount" value="{{$cartProduct->product->discount}}">
                </div>

                <div class="col-md-1">
                  {{ ($cartProduct->product->price - ($cartProduct->product->price * $cartProduct->product->discount / 100)) * $cartProduct->qty}}
                </div>
              </div>
          @endif
      @endforeach
    @endif
  </div>

    <div class="col-md-12">
      @if (count($cartSets) > 0)
        @foreach ($cartSets as $cartSet)
            <div class="row set">
              <div class="col-md-12">
                <div class="row cartOneItem">
                  <div class="col-md-1">
                    <img src="{{asset('images/trashIcon.png')}}" data-id="{{$cartSet->id}}" class="buttonRemoveSet removeItem"  style="width: 40px; height: 40px; cursor: pointer;">
                  </div>
                  <div class="col-lg-4 col-md-12">
                    <div class="imgCartItem">
                      @if ($cartSet->set()->first()->mainPhoto()->first())
                      <img src="/images/sets/og/{{ $cartSet->set()->first()->mainPhoto()->first()->src }}" alt="">
                      @else
                      <img src="{{ asset('/images/no-image.png') }}" alt="">
                      @endif
                    </div>
                    <div class="cartDescr">
                      <p>{{ $cartSet->set->translationByLanguage($lang->id)->first()->name }}</p>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <input style="height: 39px; width: 100%" type="text" name="setPrice" data-id="{{$cartSet->id}}" value="{{$cartSet->price}}">
                  </div>
                  <div class="col-lg-2 col-6 justify-content-center ngh">
                    <div class="plusminus" style="width: 100%;">
                      <div class="minus minusSet" data-id="{{$cartSet->id}}">-</div>
                      <input type="text" class="form-control" id="niti" name="number" value="{{ $cartSet->qty }}" >
                      <div class="plus plusSet" data-id="{{$cartSet->id}}">+</div>
                    </div>
                  </div>
                  <div class="col-md-2 colRed">
                    <input style="width: 100%; height: 39px;" type="text" data-id="{{$cartSet->set_id}}" name="setDiscount" value="{{$cartSet->set->discount}}">
                  </div>


                  <div class="col-md-1">
                    {{ ($cartSet->price - ($cartSet->price * $cartSet->set->discount / 100)) * $cartSet->qty}}
                  </div>
                </div>
              </div>
              @foreach ($cartSet->cart as $cartProduct)
                <div class="col-md-12">
                  <div class="row cartOneItem setProduct" style="display: none;">
                    <div class="col-md-1">
                    </div>
                    <div class="col-lg-4 col-md-12">
                      <div class="imgCartItem">
                        @if (getMainSubProductImage($cartProduct->subproduct_id))
                         @php $image = getMainSubProductImage($cartProduct->subproduct_id) @endphp
                         <img src="{{ asset('images/subproducts/'.$image->image) }}" >
                        @else
                         <img src="{{ asset('images/no-image.png') }}">
                        @endif
                      </div>
                      <div class="cartDescr">
                        <p>{{$cartProduct->product->translationByLanguage($lang->id)->first()->name}}</p>
                        @if ($cartProduct->subproduct)
                          <div class="txtWish">{{trans('front.general.inStock')}}: <span class="stock">{{$cartProduct->subproduct->stock}}</span></div>
                          <div class="txtWish">{{trans('front.cart.cod')}} <strong class="code">{{$cartProduct->subproduct->code}}</strong></div>
                        @else
                          <div class="txtWish" style="display: none;">{{trans('front.general.inStock')}}: <span class="stock">></span></div>
                          <div class="txtWish" style="display: none;">{{trans('front.cart.cod')}} <strong class="code"></strong></div>
                        @endif
                        <?php
                          $propertyValueID = getPropertiesData($cartProduct->product->id, ParameterId('color'));
                        ?>
                        @if (!is_null($propertyValueID) && $propertyValueID !== 0)
                          <?php
                            $propertyValue = getMultiDataList($propertyValueID, $lang->id)->value;
                          ?>
                          <div class="d-flex blockItem"><div>{{GetParameter('color', $lang->id)}}: <span>{{$propertyValue}}</span></div></div>
                        @endif
                      </div>

                      <select name="subproductSize" data-id="{{$cartProduct->id}}">
                          <option value="">{{trans('front.cart.size')}}</option>
                          @foreach ($cartProduct->product->subproducts as $subKey => $subproduct)
                              @foreach (json_decode($subproduct->combination) as $key => $combination)
                                  @if ($key != 0)
                                    <?php $property = getMultiDataList($combination, $lang->id); ?>

                                    @if ($subproduct->stock > 0)
                                        <option {{$cartProduct->subproduct && $cartProduct->subproduct->id === $subproduct->id ? 'selected' : ''}} value="{{$subproduct->id}}">{{$property->value}} - {{trans('front.general.inStock')}}</option>
                                    @else
                                        <option disabled>{{$property->value}} - {{trans('front.general.notInStock')}}</option>
                                    @endif

                                  @endif
                              @endforeach
                          @endforeach
                        </select>
                    </div>

                  </div>
                </div>
              @endforeach
            </div>
        @endforeach
      @endif
    </div>

    <div class="row">
      <div class="col-12" style="padding-left: 15px; margin: 20px 0; font-weight: bold;">
        {{trans('front.admin.discount')}} {{$descountTotal}}
      </div>
      <div class="col-md-12 totalsBtn">
        <input type="button" id="removeAllItems" name="remAllItems" value="{{trans('front.admin.remove')}}">
      </div>

      <div class="col-12" style="padding-left: 15px;">{{trans('front.cart.addPromo')}}</div>
      <div class="col-md-4 col-6">
        <input type="text" id="codPromo" class="codPromo" name="codPromo" value="{{ !is_null($promocode) ? $promocode->name : Session::get('promocode') }}">
      </div>

      <div class="col-md-3 col-6">
        <div class="btnDark promocodeAction">
          {{trans('front.cart.addPromoBtn')}}
        </div>
      </div>

      <div class="col">
        @if (!is_null($promocode))
            @if ($promocode->treshold <= $amount)
                <span class="amount">{{trans('front.cart.total')}}: {{ ($amount - ($amount * $promocode->discount / 100)) }} {{trans('front.general.currency')}}</span>
            @else
                <span class="amount">{{trans('front.cart.total')}}: {{ $amount }} {{trans('front.general.currency')}}</span>
            @endif
        @else
            <span class="amount">{{trans('front.cart.total')}}: {{ $amount }} {{trans('front.general.currency')}}</span>
        @endif
      </div>

      <br><br>
      <div class="col">
        @if (!is_null($promocode))
            @if ($promocode->treshold <= $amount)
                <div class="text-center"  style="display: block; color: red;">
                    {{trans('front.admin.promo', ['promo' => $promocode->discount])}}
                </div>
            @else
                <div class="text-center"  style="display: block; color: red;">
                    {{trans('front.admin.promoTreshold', ['promo' => $promocode->treshold])}}
                </div>
            @endif
        @else
            @if (Session::get('promocode'))
                <div class="text-center"  style="display: block; color: red;">
                    {{trans('front.admin.promoErr')}}
                </div>
            @endif
        @endif
      </div>
    </div>
  </div>
@else
  <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
@endif
