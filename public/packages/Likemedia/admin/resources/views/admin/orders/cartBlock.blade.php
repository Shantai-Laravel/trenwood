<?php
      $amount = 0;
      $descountTotal = 0;
      $setAmount = 0;
?>

@if (!empty($order))
    @foreach ($order->orderProductsNoSet()->get() as $key => $orderProduct)

        @if ($orderProduct->subproduct)
            <?php $price = $orderProduct->subproduct->price - ($orderProduct->subproduct->price * $orderProduct->subproduct->discount / 100); ?>

            @if ($price)
                <?php
                    $amount +=  $price * $orderProduct->qty;
                    $descountTotal += ($orderProduct->subproduct->price -  ($orderProduct->subproduct->price - ($orderProduct->subproduct->price * $orderProduct->subproduct->discount / 100))) * $orderProduct->qty;
                ?>
            @endif
        @else
            <?php $price = $orderProduct->product->price - ($orderProduct->product->price * $orderProduct->product->discount / 100); ?>

            @if ($price)
                <?php
                    $amount +=  $price * $orderProduct->qty;
                    $descountTotal += ($orderProduct->product->price -  ($orderProduct->product->price - ($orderProduct->product->price * $orderProduct->product->discount / 100))) * $orderProduct->qty;
                ?>
            @endif
        @endif

    @endforeach

    @foreach ($order->orderSets()->get() as $key => $orderSet)

        <?php
          $price = $orderSet->price - ($orderSet->price * $orderSet->set->discount / 100);
          $setAmount += $price * $orderSet->qty;
        ?>

    @endforeach

    <?php $amount = $amount + $setAmount; ?>

@endif

@if(isset($order) && (count($order->orderProductsNoSet()->get()) > 0 || count($order->orderSets()->get()) > 0))
  <div class="cartItems">
    <div class="row headCart">
      <div class="col-md-1">
      </div>
      <div class="col-md-3">
        Produs
      </div>
      <div class="col-md-2">
        Price lei
      </div>
      <div class="col-md-2">
        Cantitate
      </div>
      <div class="col-md-1">
        Reducere %
      </div>
      <div class="col-md-1">
        Total lei
      </div>
      <div class="col-md-2">
        Total %
      </div>
    </div>

    @if (count($order->orderProductsNoSet()->get()) > 0)
      @foreach ($order->orderProductsNoSet()->get() as $key => $orderProduct)
        @if ($orderProduct->subproduct)
            <div class="row cartOneItem">
              <div class="col-md-1">
                <img src="{{asset('fronts/img/icons/trashIcon.png')}}" data-order_id="{{$order->id}}" data-product_id="{{$orderProduct->product_id}}" data-subproduct_id="{{$orderProduct->subproduct_id}}" class="buttonRemove removeItem"  style="width: 40px; height: 40px; cursor: pointer;">
              </div>
              <div class="col-lg-3 col-md-12">
                <div class="imgCartItem">
                  @if (getMainSubProductImage($orderProduct->subproduct_id))
                   @php $image = getMainSubProductImage($orderProduct->subproduct_id) @endphp
                   <img src="{{ asset('images/subproducts/'.$image->image) }}" >
                  @else
                   <img src="{{ asset('fronts/img/icons/noimage.jpg') }}">
                  @endif
                </div>
                <div class="cartDescr">
                  <p>{{$orderProduct->product->translationByLanguage($lang->id)->first()->name}}</p>
                  <?php $subproduct = $orderProduct->subproduct;?>
                  <div style="margin-left: 80px;">
                      @foreach (json_decode($subproduct->combination) as $key => $combination)
                          @if ($key != 0)
                            <p>{{getParamById($key, $lang->id)->name}}: <span>{{getParamValueById($combination, $lang->id)->value}}</span></p>
                          @endif
                      @endforeach
                  </div>
                  <p>In stock: {{$orderProduct->subproduct->stock}}</p>
                </div>
              </div>
              <div class="col-md-2">
                <input style="height: 39px; width: 100%" type="text" name="productPrice" data-order_id="{{$order->id}}" data-id="{{$orderProduct->subproduct_id}}" value="{{$orderProduct->subproduct->price}}">
              </div>
              <div class="col-lg-2 col-6 justify-content-center ngh">
                <div class="plusminus" style="width: 100%;">
                  <div class="minus minusProduct" data-order_id="{{$order->id}}" data-product_id="{{ $orderProduct->product_id }}" data-subproduct_id="{{$orderProduct->subproduct_id}}">-</div>
                  <input type="text" class="form-control" id="niti" name="number" value="{{ $orderProduct->qty }}" >
                  <div class="plus plusProduct" data-order_id="{{$order->id}}" data-product_id="{{ $orderProduct->product_id }}" data-subproduct_id="{{$orderProduct->subproduct_id}}">+</div>
                </div>
              </div>
              <div class="col-md-1 colRed">
                <input style="width: 100%; height: 39px;" type="text" data-order_id="{{$order->id}}" data-id="{{$orderProduct->subproduct_id}}" name="productDiscount" value="{{$orderProduct->subproduct->discount}}">
              </div>

              <div class="col-md-1">
                {{ $orderProduct->subproduct->price * $orderProduct->qty}}
              </div>
              <div class="col-md-2">
                {{ ($orderProduct->subproduct->price - ($orderProduct->subproduct->price * $orderProduct->subproduct->discount / 100)) * $orderProduct->qty}}
              </div>
            </div>
        @else
            <div class="row cartOneItem">
              <div class="col-md-1">
                <img src="{{asset('fronts/img/icons/trashIcon.png')}}" data-order_id="{{$order->id}}" data-product_id="{{$orderProduct->product_id}}" data-subproduct_id="0" class="buttonRemove removeItem"  style="width: 40px; height: 40px; cursor: pointer;">
              </div>
              <div class="col-lg-3 col-md-12">
                <div class="imgCartItem">
                  @if (getMainProductImage($orderProduct->product_id, $lang->id))
                   @php $image = getMainProductImage($orderProduct->product_id, $lang->id) @endphp
                   <img src="{{ asset('images/products/sm/'.$image->src) }}" alt="{{$image->alt}}" title="{{$image->title}}" >
                  @else
                   <img src="{{ asset('fronts/img/icons/noimage.jpg') }}" alt="">
                  @endif
                </div>
                <div class="cartDescr">
                  <p>{{$orderProduct->product->translationByLanguage($lang->id)->first()->name}}</p>
                  <p>In stock: {{$orderProduct->product->stock}}</p>
                </div>
              </div>
              <div class="col-md-2">
                <input style="height: 39px; width: 100%" type="text" name="productPrice" data-order_id="{{$order->id}}" data-id="{{$orderProduct->product_id}}" value="{{$orderProduct->product->price}}">
              </div>
              <div class="col-lg-2 col-6 justify-content-center ngh">
                <div class="plusminus" style="width: 100%;">
                  <div class="minus minusProduct" data-order_id="{{$order->id}}" data-product_id="{{ $orderProduct->product_id }}" data-subproduct_id="0">-</div>
                  <input type="text" class="form-control" id="niti" name="number" value="{{ $orderProduct->qty }}" >
                  <div class="plus plusProduct" data-order_id="{{$order->id}}" data-product_id="{{ $orderProduct->product_id }}" data-subproduct_id="0">+</div>
                </div>
              </div>
              <div class="col-md-1 colRed">
                <input style="width: 100%; height: 39px;" type="text" data-order_id="{{$order->id}}" data-id="{{$orderProduct->product_id}}" name="productDiscount" value="{{$orderProduct->product->discount}}">
              </div>

              <div class="col-md-1 col-6">
                {{ $orderProduct->product->price * $orderProduct->qty}}
              </div>
              <div class="col-md-2">
                {{ ($orderProduct->product->price - ($orderProduct->product->price * $orderProduct->product->discount / 100)) * $orderProduct->qty}}
              </div>
            </div>
        @endif
      @endforeach
    @endif

    @if (count($order->orderSets()->get()) > 0)
        @foreach ($order->orderSets()->get() as $orderSet)
          <div class="row set">
            <div class="row cartOneItem">
              <div class="col-md-1">
                <img src="{{asset('fronts/img/icons/trashIcon.png')}}" data-order_id="{{$order->id}}" data-id="{{$orderSet->id}}" class="buttonRemoveSet removeItem"  style="width: 40px; height: 40px; cursor: pointer;">
              </div>
              <div class="col-lg-3 col-md-12">
                <div class="imgCartItem">
                  @if ($orderSet->set()->first())
                  <img src="/images/sets/og/{{ $orderSet->set()->first()->mainPhoto()->first()->src }}" alt="">
                  @else
                  <img src="{{ asset('/images/no-image.png') }}" alt="">
                  @endif
                </div>
                <div class="cartDescr">
                  <p>{{ $orderSet->set->translationByLanguage($lang->id)->first()->name }}</p>
                </div>
              </div>
              <div class="col-md-2">
                <input style="height: 39px; width: 100%" type="text" name="setPrice" data-order_id="{{$order->id}}" data-id="{{$orderSet->id}}" value="{{$orderSet->price}}">
              </div>
              <div class="col-lg-2 col-6 justify-content-center ngh">
                <div class="plusminus" style="width: 100%;">
                  <div class="minus minusSet" data-order_id="{{$order->id}}" data-id="{{$orderSet->id}}">-</div>
                  <input type="text" class="form-control" id="niti" name="number" value="{{ $orderSet->qty }}" >
                  <div class="plus plusSet" data-order_id="{{$order->id}}" data-id="{{$orderSet->id}}">+</div>
                </div>
              </div>
              <div class="col-md-1 colRed">
                <input style="width: 100%; height: 39px;" type="text" data-id="{{$orderSet->set_id}}" data-order_id="{{$order->id}}" name="setDiscount" value="{{$orderSet->set->discount}}">
              </div>

              <div class="col-md-1 col-6">
                {{ $orderSet->price * $orderSet->qty}}
              </div>
              <div class="col-md-2">
                {{ ($orderSet->price - ($orderSet->price * $orderSet->set->discount / 100)) * $orderSet->qty}}
              </div>
            </div>
            @foreach ($orderSet->orderProduct as $orderProduct)
              <div class="row cartOneItem setProduct" style="display: none;">
                <div class="col-md-1">
                </div>
                <div class="col-lg-3 col-md-12">
                  <div class="imgCartItem">
                    @if (getMainSubProductImage($orderProduct->subproduct_id))
                     @php $image = getMainSubProductImage($orderProduct->subproduct_id) @endphp
                     <img src="{{ asset('images/subproducts/'.$image->image) }}" >
                    @else
                     <img src="{{ asset('fronts/img/icons/noimage.jpg') }}">
                    @endif
                  </div>
                  <div class="cartDescr">
                    <p>{{$orderProduct->product->translationByLanguage($lang->id)->first()->name}}</p>
                    @if ($orderProduct->subproduct)
                      <div class="txtWish">In stock: <span class="stock">{{$orderProduct->subproduct->stock}}</span></div>
                      <div class="txtWish">Cod produs <strong class="code">{{$orderProduct->subproduct->code}}</strong></div>
                    @else
                      <div class="txtWish" style="display: none;">In stock: <span class="stock">></span></div>
                      <div class="txtWish" style="display: none;">Cod produs <strong class="code"></strong></div>
                    @endif
                    <?php
                      $propertyValueID = getPropertiesData($orderProduct->product->id, ParameterId('color'));
                    ?>
                    @if (!is_null($propertyValueID) && $propertyValueID !== 0)
                      <?php
                        $propertyValue = getMultiDataList($propertyValueID, $lang->id)->value;
                      ?>
                      <div class="d-flex blockItem"><div>{{GetParameter('color', $lang->id)}}: <span>{{$propertyValue}}</span></div></div>
                    @endif
                  </div>

                  <select name="subproductSize" data-id="{{$orderProduct->id}}">
                      <option value="">Select Size</option>
                      @foreach ($orderProduct->product->subproducts as $subKey => $subproduct)
                          @foreach (json_decode($subproduct->combination) as $key => $combination)
                              @if ($key != 0)
                                <?php $property = getMultiDataList($combination, $lang->id); ?>

                                @if ($subproduct->stock > 0)
                                    <option {{$orderProduct->subproduct && $orderProduct->subproduct->id === $subproduct->id ? 'selected' : ''}} value="{{$subproduct->id}}">{{$property->value}} - in stock</option>
                                @else
                                    <option disabled>{{$property->value}} - not in stock</option>
                                @endif

                              @endif
                          @endforeach
                      @endforeach
                    </select>
                </div>

              </div>
            @endforeach
          </div>
        @endforeach
    @endif

    {{trans('front.cart.totalDiscount')}} {{$descountTotal}}
    <div class="col totalsBtn">
      <input type="button" id="removeAllItems" data-order_id="{{$order->id}}" name="remAllItems" value="{{trans('front.cart.deleteAllBtn')}}">
    </div>

    @if ($order->promocode)
        <div class="col-md-3 col-6">Promocode</div>
        <div class="col-md-3 col-6">{{ $order->promocode->name }}</div>
        @if ($order->promocode->treshold <= $amount)
            <span class="amount">Total: {{ ($amount - ($amount * $order->promocode->discount / 100)) }} lei</span>
            <br><br>
            <div class="text-center"  style="display: block; color: red;">
                - {{ $order->promocode->discount }}% with promocode
            </div>
        @else
            <span class="amount">Total: {{ $amount }} lei</span>
            <br><br>
            <div class="text-center"  style="display: block; color: red;">
                Acest promocode poate fi utilizat pentru comenzi > {{ $order->promocode->treshold }} lei.
            </div>
        @endif

    @else
        <div class="col-md-3 col-6">{{trans('front.cart.promo')}}</div>
        <div class="col-md-3 col-6">
          <input type="text" id="codPromo" class="codPromo" name="codPromo" value="{{ !is_null($promocode) ? $promocode->name : Session::get('promocode') }}">
        </div>

        <div class="col-md-2 col-6">
          <div class="btnDark promocodeAction" data-order_id="{{ $order->id }}">
            {{trans('front.cart.applyBtn')}}
          </div>
        </div>

        @if (!is_null($promocode))
            @if ($promocode->treshold <= $amount)
                <span class="amount">Total: {{ ($amount - ($amount * $promocode->discount / 100)) }} lei</span>
            @else
                <span class="amount">Total: {{ $amount }} lei</span>
            @endif
        @else
            <span class="amount">Total: {{ $amount }} lei</span>
        @endif

        <br><br>
        <div class="col">
          @if (!is_null($promocode))
              @if ($promocode->treshold <= $amount)
                  <div class="text-center"  style="display: block; color: red;">
                      - {{ $promocode->discount }}% with promocode
                  </div>
              @else
                  <div class="text-center"  style="display: block; color: red;">
                      Acest promocode poate fi utilizat pentru comenzi > {{ $promocode->treshold }} lei.
                  </div>
              @endif
          @else
              @if (Session::get('promocode'))
                  <div class="text-center"  style="display: block; color: red;">
                      Acest promocode nu exista sau nu este valabil.
                  </div>
              @endif
          @endif
        </div>

    @endif
  </div>
@else
  <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
@endif
